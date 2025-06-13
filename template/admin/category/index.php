<?php

use Riotoon\Repository\{CategoryRepository, WebtoonRepository};

$is_admin = true;
$active = 'genre';


/** @var CategoryRepository|null*/
$categories = CategoryRepository::getCategoriesWithWebtoonCount();
$webtoons = count((new WebtoonRepository())->findAll());

$pg_title = "RioToon - Administration | Tous les genres de Webtoon disponible";
?>

<div style="margin-top: 95px; overflow-x:hidden;">
    <?php if (isset($_SESSION['success']) && $_SESSION['success'] == true) {
        echo messageFlash('success', $_SESSION['content']);
        $_SESSION['content'] = '';
        $_SESSION['success'] = false;
    }
    ?>
    <h1 style="font-size: var(--font-h-x);">Listes des genres de Webtoon <a href="<?= $router->url('add-cat') ?>"><i class="fas fa-plus-circle"></i></a></h1>
    
    <table class="table table-hover mt-3">
        <thead>
            <tr class="table-dark">
                <th>ID</th>
                <th>Nom</th>
                <th>Nombre de Webtoons</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td translate="no">#<?= $cat->getId() ?></td>
                        <td translate="no"><?= unClean($cat->getName()) ?></td>
                        <td translate="no"><?= $cat->getWebtoonCount() . ' / ' . $webtoons ?></td>
                        <td><a href="<?= $router->url('edit-cat', ['id' => $cat->getId()]) ?>" class="text-warning ms-2">
                            <i class="fas fa-solid fa-pencil"></i></a>
                        </td>
                        <td>
                            <form action="<?= $router->url('del-cat', ['id' => $cat->getId()]) ?>" method="post"
                                onsubmit="return confirm('Voulez-vous vraiment éffectuer cette action ?')">
                                <button type="submit" class="text-danger ms-2" style="background: transparent;">
                                    <i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?= tableStyle() ?>