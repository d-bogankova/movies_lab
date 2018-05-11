<?php

namespace MoviesApp\Normalizers;

use MoviesApp\Models\Movie;

class MovieNormalizer implements NormalizerInterface
{
    public function normalize($movie) {

        $data = [];

        if (method_exists($movie, 'getId')) {

            $data['id'] = $movie->getId();
        }

        if (method_exists($movie, 'getExternalId')) {

            $data['external_id'] = $movie->getExternalId();
        }

        if (method_exists($movie, 'getAdult')) {

            $data['adult'] = $movie->getAdult();
        }

        if (method_exists($movie, 'getBackdropPath')) {

            $data['backdrop_path'] = $movie->getBackdropPath();
        }

        if (method_exists($movie, 'getOriginalLanguage')) {

            $data['original_language'] = $movie->getOriginalLanguage();
        }

        if (method_exists($movie, 'getOriginalTitle')) {

            $data['original_title'] = $movie->getOriginalTitle();
        }

        if (method_exists($movie, 'getOverview')) {

            $data['overview'] = $movie->getOverview();
        }

        if (method_exists($movie, 'getPopularity')) {

            $data['popularity'] = $movie->getPopularity();
        }

        if (method_exists($movie, 'getPosterPath')) {

            $data['poster_path'] = $movie->getPosterPath();
        }

        if (method_exists($movie, 'getTitle')) {

            $data['title'] = $movie->getTitle();
        }

        if (method_exists($movie, 'getReleaseDate')) {

            $data['release_date'] = $movie->getReleaseDate();
        }

        if (method_exists($movie, 'getVideo')) {

            $data['video'] = $movie->getVideo();
        }

        if (method_exists($movie, 'getVoteCount')) {

            $data['vote_count'] = $movie->getVoteCount();
        }

        if (method_exists($movie, 'getVoteAverage')) {

            $data['vote_average'] = $movie->getVoteAverage();
        }

        if (method_exists($movie, 'getDateCreated')) {

            $data['date_created'] = $movie->getDateCreated();
        }

        return $data;
    }

    public function denormalize(array $params) {

        $movie = new Movie();

        if (isset($params['id'])) {

            $movie->setId($params['id']);
        }

        if (isset($params['external_id'])) {

            $movie->setExternalId($params['external_id']);
        }

        if (isset($params['adult'])) {

            $movie->setAdult($params['adult']);
        }

        if (isset($params['backdrop_path'])) {

            $movie->setBackdropPath($params['backdrop_path']);
        }

        if (isset($params['original_language'])) {

            $movie->setOriginalLanguage($params['original_language']);
        }

        if (isset($params['original_title'])) {

            $movie->setOriginalTitle($params['original_title']);
        }

        if (isset($params['overview'])) {

            $movie->setOverview($params['overview']);
        }

        if (isset($params['popularity'])) {

            $movie->setPopularity($params['popularity']);
        }

        if (isset($params['poster_path'])) {

            $movie->setPosterPath($params['poster_path']);
        }

        if (isset($params['title'])) {

            $movie->setTitle($params['title']);
        }

        if (isset($params['release_date'])) {

            $movie->setReleaseDate($params['release_date']);
        }

        if (isset($params['video'])) {

            $movie->setVideo($params['video']);
        }

        if (isset($params['vote_count'])) {

            $movie->setVoteCount($params['vote_count']);
        }

        if (isset($params['vote_average'])) {

            $movie->setVoteAverage($params['vote_average']);
        }

        if (isset($params['date_created'])) {

            $movie->setDateCreated($params['date_created']);
        }

        return $movie;
    }
}
