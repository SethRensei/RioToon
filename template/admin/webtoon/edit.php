<?php

use Riotoon\Entity\{Category, Webtoon};
use Riotoon\Repository\{CategoryRepository, WebtoonRepository};
use Riotoon\Service\BuildErrors;

$id = (int) $params['id'];
$is_admin = true;
$errors = [];

$repository = new WebtoonRepository();
$webtoon = new Webtoon();

/** @var Category */
$categories = CategoryRepository::findAll();

/** @var Webtoon|false */
$webtoon = $repository->fetchByID(value: $id);

if ($webtoon === false)
    throw new Exception("Aucun webtoon n'a été trouvé");

$w_title = unClean($webtoon->getTitle());
$pg_title = 'RioToon - Administration | Modifier ' . $w_title;

$current_categories = explode(',', $webtoon->getCategories());

$result = false;

if (isset($_POST['validate'])) {
    if (!empty($_POST['title']) and !empty($_POST['author']) and !empty($_POST['release-year']) and !empty($_POST['status']) and !empty($_POST['synopsis']) and !empty($_POST['genres'])) {
        $result = true;
        
        $webtoon->setTitle($_POST['title'])
        ->setAuthor($_POST['author'])
        ->setReleaseYear($_POST['release-year'])
        ->setStatus($_POST['status'])
        ->setSynopsis($_POST['synopsis']);
        if (!empty($_FILES['image']['name'])) {
            $name = replace(clean($_POST['title'])) . '-cover_' . rand(1000, 99999);
            $path = uploadFile($_FILES['image'], $name);
            $webtoon->setCover(str_replace('../public/', '', $path));
            if (move_uploaded_file($_FILES['image']['tmp_name'], $path))
                unlink('../public/' . $webtoon->getCover());
        }
        $errors = BuildErrors::getErrors();
        //voir si l'image a bien été uploadé et qu'il n'y a plus d'erreurs
        if ($result and empty($errors)) {
            $repository->update($webtoon);
            if ($webtoon->getIDCategories() != $_POST['genres']) {
                CategoryRepository::deleteCategorieForWebtoon(webtoon: $webtoon->getId());
                foreach ($_POST['genres'] as $v)
                    CategoryRepository::addCategoriesForWebtoon(webtoon: $webtoon->getId(), category: $v);
            }
            $_POST = [];
            header('Location:' . $router->url('home-admin'));
            $_SESSION['success'] = true;
            $_SESSION['content'] = 'Vous avez modifié ce webtoon : ' . $w_title;
        } else
            BuildErrors::setErrors('fail', "Ajout des données échoué");
    } else
        BuildErrors::setErrors('empty', 'Veuillez remplir tous les champs');
}

$errors = BuildErrors::getErrors();

?>

<div class="fs-4" style="margin-top: 95px;">
    <h1><?= $w_title ?></h1>
    <div class="card mb-3 col-md-2">
        <img src="<?= BASE_URL . $webtoon->getCover() ?>">
        <div class="card-img-overlay">
            <h5 class="card-title text-danger">Photo actuelle</h5>
        </div>
    </div>
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <?= messageFlash('warning', $err) ?>
        <?php endforeach ?>
    <?php endif ?>
    <form method="post" class="row g-3 mt-2 justify-content-center" enctype="multipart/form-data">
    <div class="col-md-5 form-group">
            <label class="form-label">Titre<i class="text-danger">*</i></label>
            <input type="text" name="title" value="<?= $w_title ?>" class="form-control" placeholder="ex: the beginning after the end">
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">Auteur<i class="text-danger">*</i></label>
            <input type="text" name="author" value="<?= unClean($webtoon->getAuthor() )?>" class="form-control" placeholder="ex: TurtleMe">
        </div>
        <div class="col-md-3 form-group">
            <label class="form-label">Sortie en<i class="text-danger">*</i></label>
            <input type="tel" maxlength="4" pattern="[0-9-() ]*" minlength="1" name="release-year" value="<?= $webtoon->getReleaseYear() ?>" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Photo</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Statut<i class="text-danger">*</i></label>
            <select class="form-select" name="status">
                <option value="EN COURS" <?= !$webtoon->getStatus() ? 'selected' : '' ?>>En cours</option>
                <option value="TERMINE" <?= $webtoon->getStatus() ? 'selected' : '' ?>>Terminé</option>
            </select>
        </div>
        <div class="col-md-10">
            <label class="form-label">Synopsis<i class="text-danger">*</i></label>
            <textarea name="synopsis" rows="6" class="form-control"><?= unClean($webtoon->getSynopsis()) ?></textarea>
        </div>
        <div class="col-md-10 mt-3">
            <label class="form-label">Genres<i class="text-danger">*</i></label>
            <div id="grid">
                <?php foreach ($categories as $cat): ?>
                    <div>
                        <input type="checkbox" name="genres[]" value="<?= $cat->getId() ?>" <?= in_array($cat->getName(), $current_categories) ? 'checked' : '' ?>><?= $cat->getName() ?>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <div class="col-10 mb-5">
            <button type="submit" name="validate" class="btn btn-outline-primary btn-lg">Modifier</button>
        </div>
    </form>
</div>

<style>
    #grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, 130px);
        grid-column-gap: 3.2rem;
        grid-row-gap: 8px;
        width: 100%;
        border: 1px solid black;
        font-size: 16px !important;
        padding: 12px 0 12px 8px;
    }

    #grid div input {
        margin-right: 8px;
        text-align: left;
    }
</style>