<?php

namespace MoviesApp\Models;

/**
 * Class Movie
 * @package MoviesApp
 */
class Movie
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $externalId;
    /**
     * @var
     */
    private $title;
    /**
     * @var
     */
    private $originalTitle;
    /**
     * @var
     */
    private $originalLanguage;
    /**
     * @var
     */
    private $overview;
    /**
     * @var
     */
    private $popularity;
    /**
     * @var
     */
    private $posterPath;
    /**
     * @var
     */
    private $backdropPath;
    /**
     * @var
     */
    private $video;
    /**
     * @var
     */
    private $voteCount;
    /**
     * @var
     */
    private $voteAverage;
    /**
     * @var
     */
    private $adult;
    /**
     * @var
     */
    private $releaseDate;
    /**
     * @var
     */
    private $dateCreated;

    /**
     * @var
     */
    private $genres = [];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param mixed $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getOriginalTitle()
    {
        return $this->originalTitle;
    }

    /**
     * @param mixed $originalTitle
     */
    public function setOriginalTitle($originalTitle)
    {
        $this->originalTitle = $originalTitle;
    }

    /**
     * @return mixed
     */
    public function getOriginalLanguage()
    {
        return $this->originalLanguage;
    }

    /**
     * @param mixed $originalLanguage
     */
    public function setOriginalLanguage($originalLanguage)
    {
        $this->originalLanguage = $originalLanguage;
    }

    /**
     * @return mixed
     */
    public function getOverview()
    {
        return $this->overview;
    }

    /**
     * @param mixed $overview
     */
    public function setOverview($overview)
    {
        $this->overview = $overview;
    }

    /**
     * @return mixed
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * @param mixed $popularity
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;
    }

    /**
     * @return mixed
     */
    public function getPosterPath()
    {
        return $this->posterPath;
    }

    /**
     * @param mixed $posterPath
     */
    public function setPosterPath($posterPath)
    {
        $this->posterPath = $posterPath;
    }

    /**
     * @return mixed
     */
    public function getBackdropPath()
    {
        return $this->backdropPath;
    }

    /**
     * @param mixed $backdropPath
     */
    public function setBackdropPath($backdropPath)
    {
        $this->backdropPath = $backdropPath;
    }

    /**
     * @return mixed
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param mixed $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }

    /**
     * @return mixed
     */
    public function getVoteCount()
    {
        return $this->voteCount;
    }

    /**
     * @param mixed $voteCount
     */
    public function setVoteCount($voteCount)
    {
        $this->voteCount = $voteCount;
    }

    /**
     * @return mixed
     */
    public function getVoteAverage()
    {
        return $this->voteAverage;
    }

    /**
     * @param mixed $voteAverage
     */
    public function setVoteAverage($voteAverage)
    {
        $this->voteAverage = $voteAverage;
    }

    /**
     * @return mixed
     */
    public function getAdult()
    {
        return $this->adult;
    }

    /**
     * @param mixed $adult
     */
    public function setAdult($adult)
    {
        $this->adult = $adult;
    }

    /**
     * @return mixed
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * @param mixed $releaseDate
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * @param Genre $genre
     */
    public function addGenre(Genre $genre)
    {
        $this->genres[] = $genre;
    }
}
