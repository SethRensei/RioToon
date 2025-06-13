<?php

use Riotoon\Repository\CategoryRepository;
use Riotoon\Service\BuildErrors;

$is_admin = true;
$active = 'genre';
$errors = [];

$pg_title = "RioToon - Administration | Ajout d'un genre de webtoon";

if (isset($_POST['validate'])) {
    if (!empty($_POST['label'])) {
        $label = clean(ucwords($_POST['label']));
        if (!CategoryRepository::isCategory($label)) {
            if (empty($errors)) {
                CategoryRepository::add($label);
                $_POST = [];
                $_SESSION['success'] = true;
                $_SESSION['content'] = 'La categorie suivante a été ajouté : ' . unClean($label);
                header('Location:' . $router->url('see-cat'));
            } else
                BuildErrors::setErrors('fail', "Ajout échoué");
        } else
            BuildErrors::setErrors('is_exist', "Ce genre existe déjà");
    } else
        BuildErrors::setErrors('empty', 'Veuillez remplir le champ');
}
$errors = BuildErrors::getErrors();
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
    <form class="border shadow p-3 rounded" method="post" style="width: 450px;">
        <h1 class="text-center p-3 fs-2">Ajouter un nouveau genre</h1>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $err): ?>
                <?= messageFlash('warning', $err) ?>
            <?php endforeach ?>
        <?php endif ?>
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" class="form-control" name="label">
        </div>
        <div class="col-10 mb-2">
            <button type="submit" name="validate" class="btn btn-outline-primary btn-lg">Ajouter</button>
        </div>
    </form>
</div>