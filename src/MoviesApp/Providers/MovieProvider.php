<?php

namespace MoviesApp\Providers;

use MoviesApp\App;
use MoviesApp\DbMysqli\DbMysqli;
use MoviesApp\Models\Genre;
use MoviesApp\Models\Movie;
use MoviesApp\Normalizers\GenreNormalizer;
use MoviesApp\Normalizers\MovieNormalizer;
use MoviesApp\Tmdb\TmdbApiClient;

/**
 * Class MovieProvider
 * @package MoviesApp
 */
class MovieProvider
{

    private $genreNormalizer;

    /**
     * @var
     */
    private $movieNormalizer;

    /**
     * @var DbMysqli
     */
    private $db;

    /**
     * @var GenreProvider
     */
    private $genreProvider;

    /**
     * @var TmdbApiClient
     */
    private $tmdbApiClient;

    /**
     * @var string
     */
    private $daysToParse;

    /**
     * @var string
     */
    private $dataPath;

    /**
     * @param GenreNormalizer $genreNormalizer
     * @param MovieNormalizer $movieNormalizer
     * @param DbMysqli $db
     * @param GenreProvider $genreProvider
     * @param TmdbApiClient $tmdbApiClient
     * @param string $daysToParse
     * @param string $dataPath
     */
    function __construct(
        GenreNormalizer $genreNormalizer,
        MovieNormalizer $movieNormalizer,
        DbMysqli $db,
        GenreProvider $genreProvider,
        TmdbApiClient $tmdbApiClient,
        $daysToParse,
        $dataPath
    ) {
        $this->genreNormalizer = $genreNormalizer;
        $this->movieNormalizer = $movieNormalizer;
        $this->db = $db;
        $this->genreProvider = $genreProvider;
        $this->tmdbApiClient = $tmdbApiClient;
        $this->daysToParse = $daysToParse;
        $this->dataPath = $dataPath;
    }

    /**
     * Creates Movie instance
     * @return Movie
     */
    public function createMovie() {

        return new Movie();
    }

    /**
     * Saves movie in database
     * @param Movie $movie
     * @return mixed
     * @throws \Exception
     */
    public function persistMovie(Movie $movie) {

        if ($id = $movie->getId()) {

            $this->db->customQuery(sprintf(
                    "UPDATE movies SET
                     external_id = %u, 
                     title = '%s', 
                     original_title = '%s', 
                     original_language = '%s', 
                     overview = '%s', 
                     popularity = %f, 
                     poster_path = '%s', 
                     backdrop_path = '%s', 
                     video = %b, 
                     vote_count = %u, 
                     vote_average = %f, 
                     adult = %b,
                     runtime = %u,
                     release_date = '%s' WHERE id = %u",
                    $movie->getExternalId(),
                    $this->db->real_escape_string($movie->getTitle()),
                    $this->db->real_escape_string($movie->getOriginalTitle()),
                    $this->db->real_escape_string($movie->getOriginalLanguage()),
                    $this->db->real_escape_string($movie->getOverview()),
                    $movie->getPopularity(),
                    $this->db->real_escape_string($movie->getPosterPath()),
                    $this->db->real_escape_string($movie->getBackdropPath()),
                    $movie->getVideo(),
                    $movie->getVoteCount(),
                    $movie->getVoteAverage(),
                    $movie->getAdult(),
                    $movie->getRuntime(),
                    $this->db->real_escape_string($movie->getReleaseDate()),
                    $movie->getId()
                )
            );

            $this->updateMovieGenres($movie);

        } else {

            $date = date('Y-m-d H:i:s');
            $this->db->customQuery(sprintf(
                    "INSERT INTO movies
                 (external_id, title, original_title, original_language, overview, popularity, poster_path, backdrop_path, video, vote_count, vote_average, adult, runtime, release_date, date_created)
                 VALUES
                 (%u,'%s','%s','%s','%s',%f,'%s','%s',%b,%u,%f,%b,%u,'%s','%s')",
                    $movie->getExternalId(),
                    $this->db->real_escape_string($movie->getTitle()),
                    $this->db->real_escape_string($movie->getOriginalTitle()),
                    $this->db->real_escape_string($movie->getOriginalLanguage()),
                    $this->db->real_escape_string($movie->getOverview()),
                    $movie->getPopularity(),
                    $this->db->real_escape_string($movie->getPosterPath()),
                    $this->db->real_escape_string($movie->getBackdropPath()),
                    $movie->getVideo(),
                    $movie->getVoteCount(),
                    $movie->getVoteAverage(),
                    $movie->getAdult(),
                    $movie->getRuntime(),
                    $this->db->real_escape_string($movie->getReleaseDate()),
                    $date
                )
            );

            $id = $this->db->insert_id;

            $movie->setId($id);
            $this->updateMovieGenres($movie);
            $this->downloadMovieImages($movie);
        }

        return $movie->getId();
    }

    /**
     * Returns list of Movies
     * @return array
     */
    public function getMovies() {

        $genreData = $this->db->customQuery(sprintf("SELECT * FROM genres"))->fetchAll();
        $genres = [];

        foreach ($genreData as $item) {

            $genres[intval($item['id'])] = $this->genreNormalizer->denormalize($item);
        }

        $movieData = $this->db->customQuery(sprintf(
                "SELECT * FROM movies  WHERE release_date BETWEEN '%s' AND '%s'",
                date('Y-m-d', time() - 86400 * intval($this->daysToParse)),
                date('Y-m-d')
            )
        )->fetchAll();

        $data = [];

        foreach ($movieData as $item) {

            $movie = $this->movieNormalizer->denormalize($item);
            $movieGenreIds = $this->db
                ->customQuery(sprintf("SELECT genre_id FROM movie_genres WHERE movie_id = %u", $item['id']))
                ->fetchList();

            foreach($movieGenreIds as $genre_id) {

                if (isset($genres[intval($genre_id)])) {

                    $movie->addGenre($genres[$genre_id]);
                }
            }

            $data[] = $movie;
        }

        return $data;
    }

    /**
     * Receives movies from TMD API and saves them in database
     */
    public function updateMovies() {

        $movies = $this->tmdbApiClient->getNowPlayingMovies([
            'startDate' => date('Y-m-d', time() - 86400 * intval($this->daysToParse)),
            'endDate' => date('Y-m-d')
        ]);

        foreach($movies as $externalMovie) {

            if ($this->isMovieExists($externalMovie->id)) {

                continue;
            }

            $movie = $this->createMovie();
            $movie->setExternalId($externalMovie->id);
            $movie->setAdult($externalMovie->adult);
            $movie->setBackdropPath($externalMovie->backdrop_path);
            $movie->setOriginalLanguage($externalMovie->original_language);
            $movie->setOriginalTitle($externalMovie->original_title);
            $movie->setOverview($externalMovie->overview);
            $movie->setPopularity($externalMovie->popularity);
            $movie->setPosterPath($externalMovie->poster_path);
            $movie->setTitle($externalMovie->title);
            $movie->setReleaseDate($externalMovie->release_date);
            $movie->setVideo($externalMovie->video);
            $movie->setVoteCount($externalMovie->vote_count);
            $movie->setVoteAverage($externalMovie->vote_average);

            $movieDetails = $this->tmdbApiClient->getMovieDetails($externalMovie->id);

            $movie->setRuntime($movieDetails->runtime);

            foreach ($externalMovie->genre_ids as $genreExternalId) {

                $genre = $this->genreProvider->findByExternalId($genreExternalId);

                if (!$genre instanceof Genre) {

                    continue;
                }

                $movie->addGenre($genre);
            }

            $this->persistMovie($movie);
        }
    }

    /**
     * Finds Movie by id
     * @param $id
     * @return bool|Movie
     * @throws \Exception
     */
    public function findById($id) {

        $data = $this->db->customQuery("SELECT * FROM movies WHERE id = %u", intval($id))->fetch_assoc();

        if (empty($data)) {

            return false;
        }

        $movie = $this->movieNormalizer->denormalize($data);

        $movieGenres = $this->db->customQuery(sprintf(
            "SELECT * FROM genres WHERE id IN (SELECT genre_id FROM movie_genres WHERE id = %u)",
            $movie->getId()
        ))->fetchAll();

        foreach ($movieGenres as $genreData) {

            $genre = $this->genreProvider->createGenre($genreData);
            $movie->addGenre($genre);
        }

        return $movie;
    }

    /**
     * Checks if the Movie exists in the database
     * @param $externalId
     * @return bool
     * @throws \Exception
     */
    public function isMovieExists($externalId) {

        $data = $this->db->customQuery(sprintf("SELECT id FROM movies WHERE external_id = %u", intval($externalId)))->fetchValue();

        if (!empty($data)) {

            return true;
        }

        return false;
    }

    /**
     * Downloads Movie images
     * @param Movie $movie
     */
    private function downloadMovieImages(Movie $movie){

        if (!empty($movie->getPosterPath())) {

            $filePath = $this->dataPath . '/posters' . $movie->getPosterPath();
            $this->tmdbApiClient->downloadImage($movie->getPosterPath(), $filePath);
        }

        if (!empty($movie->getBackdropPath())) {

            $filePath = $this->dataPath . '/backdrops' . $movie->getBackdropPath();
            $this->tmdbApiClient->downloadImage($movie->getBackdropPath(), $filePath);
        }
    }

    /**
     * Updates Movie genres
     * @param Movie $movie
     * @throws \Exception
     */
    private function updateMovieGenres(Movie $movie){

        $this->db->customQuery(sprintf("DELETE FROM movie_genres WHERE movie_id = %u", $movie->getId()));

        foreach ($movie->getGenres() as $genre) {

            $this->db->customQuery(sprintf(
                "INSERT INTO movie_genres (movie_id, genre_id) VALUES (%u,%u)",
                $movie->getId(), $genre->getId()
            ));
        }
    }
}
