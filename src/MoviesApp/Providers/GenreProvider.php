<?php

namespace MoviesApp\Providers;

use MoviesApp\App;
use MoviesApp\DbMysqli\DbMysqli;
use MoviesApp\Models\Genre;
use MoviesApp\Normalizers\GenreNormalizer;
use MoviesApp\Normalizers\NormalizerInterface;
use MoviesApp\Tmdb\TmdbApiClient;

/**
 * Class GenreProvider
 * @package MoviesApp
 */
class GenreProvider
{

    /**
     * @var GenreNormalizer
     */
    private $genreNormalizer;

    /**
     * @var DbMysqli
     */
    private $db;

    /**
     * @var TmdbApiClient
     */
    private $tmdbApiClient;

    /**
     * @param NormalizerInterface $genreNormalizer
     * @param DbMysqli $db
     * @param TmdbApiClient $tmdbApiClient
     */
    function __construct(
        NormalizerInterface $genreNormalizer,
        DbMysqli $db,
        TmdbApiClient $tmdbApiClient
    ) {

        $this->genreNormalizer = $genreNormalizer;
        $this->db = $db;
        $this->tmdbApiClient = $tmdbApiClient;
    }

    /**
     * @return Genre
     */
    public function createGenre() {

        return new Genre();
    }

    /**
     * Saves genre in database
     * @param Genre $genre
     * @return mixed
     */
    public function persistGenre(Genre $genre) {

        if ($id = $genre->getId()) {

            $this->db->customQuery(sprintf(
                    "UPDATE genres SET
                     external_id = %u, 
                     name = '%s', 
                     WHERE id = %u",
                    $genre->getExternalId(),
                    $this->db->real_escape_string($genre->getName()),
                    $genre->getId()
                )
            );

        } else {

            $date = date('Y-m-d H:i:s');
            $this->db->customQuery(sprintf(
                    "INSERT INTO genres
                     (external_id, name, date_created)
                     VALUES
                     (%u,'%s','%s')",
                    $genre->getExternalId(),
                    $this->db->real_escape_string($genre->getName()),
                    $date
                )
            );

            $id = $this->db->insert_id;

            $genre->setId($id);
        }

        return $genre->getId();
    }

    /**
     * Receives genres from TMDB API and saves them in database
     */
    public function updateGenres() {

        $genres = $this->tmdbApiClient->getGenres();

        foreach($genres as $externalGenre) {

            if ($this->isGenreExists($externalGenre->id)) {

                continue;
            }

            $genre = $this->createGenre();
            $genre->setExternalId($externalGenre->id);
            $genre->setName($externalGenre->name);

            $this->persistGenre($genre);
        }
    }

    /**
     * Finds Genre by id
     * @param $externalId
     * @return bool|Genre
     */
    public function findByExternalId($externalId) {

        $data = $this->db->customQuery(sprintf("SELECT * FROM genres WHERE external_id = %u", intval($externalId)))->fetch_assoc();

        if (empty($data)) {

            return false;
        }

        return $this->genreNormalizer->denormalize($data);
    }

    /**
     * Checks if genre with external Id exists
     * @param $externalId
     * @return bool
     */
    public function isGenreExists($externalId) {

        $data = $this->db->customQuery(sprintf("SELECT id FROM genres WHERE external_id = %u", intval($externalId)))->fetchValue();

        if (!empty($data)) {

            return true;
        }

        return false;
    }
}
