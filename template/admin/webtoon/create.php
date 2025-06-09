<?php

use Riotoon\Entity\{Category, Webtoon};
use Riotoon\Repository\{CategoryRepository, WebtoonRepository};
use Riotoon\Service\BuildErrors;

$repository = new WebtoonRepository();
$webtoon = new Webtoon();

/** @var Category */
$categories = CategoryRepository::findAll();

if (isset($_POST['validate'], $_FILES['image']['name'])) {
    if (!empty($_POST['title']) and !empty($_POST['author']) and !empty($_FILES['image']['size']) and !empty($_POST['release-year']) and !empty($_POST['status']) and !empty($_POST['synopsis']) and !empty($_POST['genres'])) {
        $name = replace(clean($_POST['title'])) . '-cover_' . rand(1000, 99999);
        $path = uploadFile($_FILES['image'], $name);
        $webtoon->setTitle($_POST['title'])
            ->setAuthor($_POST['author'])
            ->setReleaseYear($_POST['release-year'])
            ->setStatus($_POST['status'])
            ->setCover(str_replace('../public/', '', $path))
            ->setSynopsis($_POST['synopsis']);
        $errors = BuildErrors::getErrors();
        //voir si l'image a bien été uploadé et qu'il n'y a plus d'erreurs
        if (empty($errors) and move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
            $id = $repository->create($webtoon);
            foreach ($_POST['genres'] as $v)
                CategoryRepository::addCategoriesForWebtoon($id, $v);
            $_POST = [];
        } else
            BuildErrors::setErrors('fail', "Ajout des données échoué");
    } else
        BuildErrors::setErrors('empty', 'Veuillez remplir tous les champs');
}

$errors = BuildErrors::getErrors();

?>

<div class="fs-4" style="margin-top: 95px;">
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <?= messageFlash('warning', $err) ?>
        <?php endforeach ?>
    <?php endif ?>
    <h1 class="mb-3">Ajouter un Webtoon</h1>
    <form method="post" class="row g-3 mt-2 justify-content-center" enctype="multipart/form-data">
        <div class="col-md-5 form-group">
            <label class="form-label">Titre<i class="text-danger">*</i></label>
            <input type="text" name="title" value="<?= $_POST['title'] ?? '' ?>" class="form-control"
                placeholder="ex: the beginning after the end">
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">Auteur<i class="text-danger">*</i></label>
            <input type="text" name="author" value="<?= $_POST['author'] ?? '' ?>" class="form-control"
                placeholder="ex: TurtleMe">
        </div>
        <div class="col-md-3 form-group">
            <label class="form-label">Sortie en<i class="text-danger">*</i></label>
            <input type="tel" maxlength="4" pattern="[0-9-() ]*" minlength="2" name="release-year"
                value="<?= $_POST['release-year'] ?? '' ?>" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Photo<i class="text-danger">*</i></label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Statut<i class="text-danger">*</i></label>
            <select class="form-select" name="status">
                <option value="EN COURS" select>En cours</option>
                <option value="TERMINE">Terminé</option>
            </select>
        </div>
        <div class="col-md-10">
            <label class="form-label">Synopsis<i class="text-danger">*</i></label>
            <textarea name="synopsis" rows="6" class="form-control"><?= unClean($_POST['synopsis'] ?? '') ?></textarea>
        </div>
        <div class="col-md-10 mt-3">
            <label class="form-label">Genres<i class="text-danger">*</i></label>
            <div id="grid">
                <?php foreach ($categories as $cat) :?>
                    <div>
                        <input type="checkbox" value="<?= $cat->getId() ?>" name="genres[]"><?= $cat->getName() ?>
                    </div>
                <?php endforeach?>
            </div>
        </div>
        <div class="col-10 mb-5">
            <button type="submit" name="validate" class="btn btn-outline-primary btn-lg">Ajouter</button>
        </div>
    </form>
</div>