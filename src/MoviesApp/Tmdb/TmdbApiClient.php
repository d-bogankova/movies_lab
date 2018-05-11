<?php

namespace MoviesApp\Tmdb;

use MoviesApp\Curl\Curl;

/**
 * Class TmdbApiClient
 * @package MoviesApp
 */
class TmdbApiClient
{
    /**
     *
     */
    const IMDB_API_URI = 'https://api.themoviedb.org/3';
    /**
     *
     */
    const TMD_IMG_URI = 'https://image.tmdb.org/t/p/w500';
    /**
     *
     */
    const HTTP_OK = 200;

    /**
     * @var Curl
     */
    private $curl;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @return Curl
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * @param Curl $curl
     */
    public function setCurl($curl)
    {
        $this->curl = $curl;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Class constructor
     * @param Curl $curl
     * @param $apiKey
     */
    function __construct(Curl $curl, $apiKey) {

        $this->curl = $curl;
        $this->apiKey = $apiKey;
    }

    /**
     * @param array $params
     * @return array
     */
    function getNowPlayingMovies(array $params = []) {

        $data = array();

        if(!isset($params['startDate'])){

            $params['startDate'] = date('Y-m-d', 0);
        }

        if(!isset($params['endDate'])){

            $params['endDate'] = date('Y-m-d', time());
        }

        for ($i = 1; $i < 999; $i++) {

            $response = $this->curl->execute(self::IMDB_API_URI . '/movie/now_playing?api_key=' . $this->apiKey . '&page=' . $i);
            $curlInfo = $this->curl->getLastRequestInfo();

            if (self::HTTP_OK == $curlInfo['http_code']) {

                $response = json_decode($response);

                foreach ($response->results as $item){

                    if($params['startDate'] > $item->release_date || $params['endDate'] < $item->release_date){

                        continue;
                    }

                    $data[] = $item;
                }
            }

            if (self::HTTP_OK != $curlInfo['http_code'] || $i >= $response->total_pages) {

                break;
            }
        };

        return $data;
    }

    /**
     * Gets genres data
     * @return array
     */
    function getGenres() {

        $data = array();

        $response = $this->curl->execute(self::IMDB_API_URI . '/genre/movie/list?api_key=' . $this->apiKey);
        $curlInfo = $this->curl->getLastRequestInfo();

        if (self::HTTP_OK == $curlInfo['http_code']) {

            $response = json_decode($response);
            $data = $response->genres;
        }

        return $data;
    }


    /**
     * @param $urlSuffix
     * @param $filePath
     * @throws \Exception
     */
    public function downloadImage($urlSuffix, $filePath) {

        if (!is_dir($dir = dirname($filePath))) {

            mkdir($dir, 0775, true);
        }

        $this->curl->download(self::TMD_IMG_URI . $urlSuffix, $filePath);
    }
}
