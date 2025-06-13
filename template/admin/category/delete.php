<?php

use Riotoon\Repository\CategoryRepository;

$id = (int) $params['id'];
$is_admin = true;

/** @var CategoryRepository|false */
$category = CategoryRepository::getById($id);

CategoryRepository::delete($category->getId());
$_SESSION['success'] = true;
$_SESSION['content'] = 'Vous avez supprimé ce genre : ' . unClean($category->getName());
header('Location:' . $router->url('see-cat'));
exit();