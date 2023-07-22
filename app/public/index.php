<?php
include __DIR__ . '/../includes/autoload.php';

$uri = strtok(ltrim($_SERVER['REQUEST_URI'], '/'), '?');

$jokeWebsite = new \Jokessite\JokeWebsite();
$entryPoint = new \Generic\EntryPoint($jokeWebsite);
$entryPoint -> run($uri, $_SERVER['REQUEST_METHOD']);