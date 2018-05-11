<?php

namespace MoviesApp\Normalizers;

use MoviesApp\Models\Genre;

/**
 * Class GenreNormalizer
 * @package MoviesApp
 */
class GenreNormalizer implements NormalizerInterface
{
    /**
     * @param $genre
     * @return array
     */
    public function normalize($genre) {

        $data = [];

        if (method_exists($genre, 'getId')) {
            $data['id'] = $genre->getId();
        }

        if (method_exists($genre, 'getExternalId')) {
            $data['external_id'] = $genre->getExternalId();
        }

        if (method_exists($genre, 'getName')) {
            $data['name'] = $genre->getName();
        }

        if (method_exists($genre, 'getDateCreated')) {
            $data['date_created'] = $genre->getDateCreated();
        }

        return $data;
    }

    /**
     * @param array $params
     * @return Genre
     */
    public function denormalize(array $params) {

        $genre = new Genre();

        if (isset($params['id'])) {

            $genre->setId(intval($params['id']));
        }

        if (isset($params['external_id'])) {

            $genre->setExternalId(intval($params['external_id']));
        }

        if (isset($params['name'])) {

            $genre->setName($params['name']);
        }

        if (isset($params['date_created'])) {

            $genre->setDateCreated($params['date_created']);
        }

        return $genre;
    }
}
