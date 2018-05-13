<?php

namespace MoviesApp;

use MoviesApp\Common\Controller;
use MoviesApp\Common\Dispatcher;
use MoviesApp\Common\View;
use MoviesApp\Curl\Curl;
use MoviesApp\DbMysqli\DbMysqli;
use MoviesApp\Logger\Logger;
use MoviesApp\Normalizers\GenreNormalizer;
use MoviesApp\Normalizers\MovieNormalizer;
use MoviesApp\Providers\GenreProvider;
use MoviesApp\Providers\MovieProvider;
use MoviesApp\Tmdb\TmdbApiClient;


/**
 * Class App
 * @package MovieApp
 */
class App
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    function __construct(array $config) {

        $this->config = $config;
    }

    /**
     * Application bootstrap
     */
    public function run() {

        $curl = new Curl();

        $logger = new Logger($this->config['path']['logs'] . '/log.txt');

        $tmdbApiClient = new TmdbApiClient(
            $curl,
            $this->config['tmdbApiKey'],
            $this->config['locale'],
            $logger
        );

        $movieNormalizer = new MovieNormalizer();
        $genreNormalizer = new GenreNormalizer();

        $db = new DbMysqli(
            $this->config['database']['host'],
            $this->config['database']['user'],
            $this->config['database']['pass'],
            $this->config['database']['name']
        );
        $db->setErrorLog($this->config['path']['logs'] . '/mysql_errors.txt');

        $genreProvider = new GenreProvider($genreNormalizer, $db, $tmdbApiClient);
        $movieProvider = new MovieProvider(
            $genreNormalizer,
            $movieNormalizer,
            $db,
            $genreProvider,
            $tmdbApiClient,
            $this->config['daysToParse'],
            $this->config['path']['data']
        );

        $view = new View($this->config['path']['root'] . '/views');

        $controller = new Controller(
            $genreProvider,
            $movieProvider,
            $logger,
            $view
        );

        $dispatcher = new Dispatcher();
        $dispatcher->setController($controller);

        $logger->writeLog('INFO: Application started.');
        $dispatcher->dispatch();
        $logger->writeLog('INFO: Application finished.' . PHP_EOL);
    }
}
