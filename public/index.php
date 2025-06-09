<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

use Riotoon\Controller\Router;

// Chargement des variables d'environnement depuis le fichier .env
$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');

define(constant_name: 'BASE_URL', value: dirname(path: $_SERVER['SCRIPT_NAME']));

$router = new Router(view_path: dirname(path: __DIR__) . '/template');

$router->get(url: '/', view: 'index', name: 'home')
    ->fallOver(url: '/admin/add-webtoon', view: 'admin/webtoon/add', name: 'add-webt')
    ->run();