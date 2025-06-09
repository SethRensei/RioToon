<?php

use Riotoon\Entity\{Chapter, Webtoon};
use Riotoon\Repository\{ChapterRepository, WebtoonRepository};
use Riotoon\Service\BuildErrors;

$id = (int) $params['id'];
$is_admin = true;

$chapter = new Chapter();

$repository = new ChapterRepository();

/** @var Webtoon|false */
$webtoon = (new WebtoonRepository())->fetchByID(value: $id);

if ($webtoon === false)
    throw new Exception("Aucun webtoon n'a été trouvé");

$pg_title = html_entity_decode($webtoon->getTitle()) . " | RioToon - Administration";
$errors = [];

if (isset($_POST['validate'], $_FILES['file-zip']['name'])) {
    if ($_POST['ch-num'] >= 0 and !empty($_FILES['file-zip']['name'])) {
        $directory = "../public/images/chapitres/" . $webtoon->getId() . '_' . replace(unClean($webtoon->getTitle()), '') . "/";
        $path = uploadFile($_FILES['file-zip'], "Chap_" . $_POST['ch-num'], '20971520', $directory, ['zip', 'cbz']);
        $chapter->setWebtoon($webtoon->getId())
            ->setNumber($_POST['ch-num'])
            ->setPathView(str_replace('../public/', '', $path));
        $errors = BuildErrors::getErrors();
        if(!$repository->isChapter($chapter)) {
            if (empty($errors) and move_uploaded_file($_FILES['file-zip']['tmp_name'], $path)) {
                $repository->add($chapter);
                $_POST = [];
                $success = true;
            } else
                BuildErrors::setErrors('fail', "Ajout échoué");
        } else
            BuildErrors::setErrors('exist', 'Ce chapitre existe déjà');
    } else
        BuildErrors::setErrors('empty', 'N° Chap inférieur à 0 ou tous les champs vides');
}
$errors = BuildErrors::getErrors();

?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
    <form class="border shadow p-3 rounded" method="post" enctype="multipart/form-data" style="width: 450px;">
        <h1 class="text-center p-3">Ajouter un chapitre</h1>
        <?php if (isset($success) and $success === true): ?>
            <?= messageFlash('success', 'Vous avez ajouté un chapitre') ?>
        <?php endif ?>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $err): ?>
                <?= messageFlash('warning', $err) ?>
            <?php endforeach ?>
        <?php endif ?>
        <div class="mb-3">
            <label class="form-label">N° chapitre</label>
            <input type="number" min="0" class="form-control" name="ch-num" value="<?= clean($_POST['ch-num'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Fichier (Zip ou Cbz)</label>
            <input type="file" name="file-zip" class="form-control">
        </div>

        <div class="col-10 mb-2">
            <button type="submit" name="validate" class="btn btn-outline-primary btn-lg">Ajouter</button>
        </div>
    </form>
</div>