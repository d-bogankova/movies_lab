<?php

namespace MoviesApp\Common;
use MoviesApp\Logger\Logger;
use MoviesApp\Providers\GenreProvider;
use MoviesApp\Providers\MovieProvider;

/**
 * Class Controller
 * @package MoviesApp
 */
class Controller
{

    private $genreProvider;
    private $movieProvider;
    private $logger;
    private $view;

    function __construct(
        GenreProvider $genreProvider,
        MovieProvider $movieProvider,
        Logger $logger,
        View $view
    ) {
        $this->genreProvider = $genreProvider;
        $this->movieProvider = $movieProvider;
        $this->logger = $logger;
        $this->view = $view;
    }

    /**
     * Runs movie updating process
     * @throws \Exception
     */
    public function query()
    {
        $this->genreProvider->updateGenres();

        $messages[] = 'INFO: Genres are updated.';

        $this->movieProvider->updateMovies();

        $messages[] = 'INFO: Movies are updated.';

        if(isset($_REQUEST['action']) && 'query' == $_REQUEST['action']){
            foreach ($messages as $message) {
                $this->logger->writeLog($message, true);
            }
        }

        if (isset($argv[0]) || isset($_SERVER['argv'])) {

            foreach ($messages as $message) {
                $this->logger->writeLog($message, true);
            }
        } else {

            $this->view->render('index', [
                'messages' => $messages,
            ]);
        }
    }

    /**
     * Displays movies index
     */
    public function index()
    {
        $data = $this->movieProvider->getMovies();

        $this->view->render('index', [
            'data' => $data,
        ]);
    }
}
