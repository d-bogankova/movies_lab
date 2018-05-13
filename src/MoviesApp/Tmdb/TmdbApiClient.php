<?php

namespace MoviesApp\Tmdb;

use MoviesApp\Exceptions\ApiException;
use MoviesApp\Curl\Curl;
use MoviesApp\Logger\Logger;

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
     * @var
     */
    private $locale;

    /**
     * @var
     */
    private $logger;

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
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param mixed $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * TmdbApiClient constructor.
     * @param Curl $curl
     * @param string $apiKey
     * @param string $locale
     * @param Logger $logger
     */
    function __construct(Curl $curl, $apiKey, $locale, $logger) {

        $this->setCurl($curl);
        $this->setApiKey($apiKey);
        $this->setLocale($locale);
        $this->setLogger($logger);
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

            $response = $this->curl->execute(self::IMDB_API_URI . '/movie/now_playing?api_key=' . $this->apiKey . '&page=' . $i . '&language=' . $this->locale);
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

        $data = [];

        $response = $this->curl->execute(self::IMDB_API_URI . '/genre/movie/list?api_key=' . $this->apiKey . '&language=' . $this->locale);
        $curlInfo = $this->curl->getLastRequestInfo();

        if (self::HTTP_OK == $curlInfo['http_code']) {

            $response = json_decode($response);
            $data = $response->genres;
        }

        return $data;
    }


    /**
     * @param $movieId
     * @return bool|mixed
     * @throws \Exception
     */
    public function getMovieDetails($movieId){

        $response = $this->curl->execute(self::IMDB_API_URI . '/movie/' . $movieId . '?api_key=' . $this->apiKey . '&language=' . $this->locale);
        $curlInfo = $this->curl->getLastRequestInfo();

        if (self::HTTP_OK == $curlInfo['http_code']) {

            $response = json_decode($response);
        } else {
            throw new ApiException('Unexpected API response.');
        }

        return $response;
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
