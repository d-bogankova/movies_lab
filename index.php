<?php
use MoviesApp\App;

include 'autoload.php';

$app = new App(include 'config.php');
$app->run();