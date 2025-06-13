<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

use Riotoon\Controller\Router;

// Chargement des variables d'environnement depuis le fichier .env
$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');

define(constant_name: 'BASE_URL', value: dirname(path: $_SERVER['SCRIPT_NAME']));

$router = new Router(view_path: dirname(path: __DIR__) . '/template');

$router->get(url: '/', view: 'index', name: 'home')
    ->fallOver('/webtoon/[i:id]-[*:title]', 'post/show-detail', 'show-webt')
    ->fallOver('/webtoon/read/[i:id]-[*:title]/Chapitre-[*:chapt]', 'post/read-webtoon', 'read')
    ->fallOver('/webtoon/category/EDj-9loi0-[i:id]S2v6-jhgQ/[*:label]', 'post/category', 'category')
    
    // ADMIN
    ->get('/admin', 'admin/index', 'home-admin')
    // Webtoon
    ->fallOver(url: '/admin/add-webtoon', view: 'admin/webtoon/add', name: 'add-webt')
    ->fallOver('/admin/update-webtoon/[i:id]', 'admin/webtoon/edit', 'edit-webt')
    ->post('/admin/delete-webtoon/[i:id]', 'admin/webtoon/delete', 'del-webt')
    // Chapter
    ->fallOver('/admin/add-chapter/[i:id]', 'admin/chapter/add', 'add-chap')
    // Category
    ->fallOver('/admin/add-category', 'admin/category/add', 'add-cat')
    ->post('/admin/delete-category/[i:id]', 'admin/category/delete', 'del-cat')
    ->fallOver('/admin/update-category/[i:id]', 'admin/category/edit', 'edit-cat')
    ->get('/admin/categories', 'admin/category/index', 'see-cat')

    // Error page
    ->get('/error404', 'error404', 'error')
    ->run();