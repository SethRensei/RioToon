<?php

use Riotoon\Repository\CategoryRepository;
use Riotoon\Service\BuildErrors;

$id = $params['id'];
$is_admin = true;
$active = 'genre';
$errors = [];

/** @var CategoryRepository|false */
$category = CategoryRepository::getById($id);
if ($category === false)
    throw new Exception("Aucun genre n'a été trouvé");

$name = unClean($category->getName());

$pg_title = "RioToon - Administration | Modification d'un genre de webtoon";

if (isset($_POST['validate'])) {
    if (!empty($_POST['label'])) {
        $label = clean(ucwords($_POST['label']));
        if (!CategoryRepository::isCategory($label)) {
            if (empty($errors)) {
                CategoryRepository::add($label);
                $_POST = [];
                $_SESSION['success'] = true;
                $_SESSION['content'] = "La categorie suivante a été modifiée <" . $name . "> en : " . unClean($label);
                header('Location:' . $router->url('see-cat'));
            } else
                BuildErrors::setErrors('fail', "Modification échoué");
        } else
            BuildErrors::setErrors('is_exist', "Ce genre existe déjà");
    } else
        BuildErrors::setErrors('empty', 'Veuillez remplir le champ');
}
$errors = BuildErrors::getErrors();
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
    <form class="border shadow p-3 rounded" method="post" style="width: 450px;">
        <h1 class="text-center p-3">Modifier un genre</h1>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $err): ?>
                <?= messageFlash('warning', $err) ?>
            <?php endforeach ?>
        <?php endif ?>
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" class="form-control" name="label" value="<?= unClean($category->getName()) ?>">
        </div>
        <div class="col-10 mb-2">
            <button type="submit" name="validate" class="btn btn-outline-primary btn-lg">Modifier</button>
        </div>
    </form>
</div>