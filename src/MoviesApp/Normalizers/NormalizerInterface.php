<?php

namespace MoviesApp\Normalizers;


/**
 * Interface NormalizerInterface
 * @package MoviesApp
 */
interface NormalizerInterface
{
    /**
     * @param $obj
     * @return mixed
     */
    public function normalize($obj);

    /**
     * @param array $params
     * @return mixed
     */
    public function denormalize(array $params);
}
