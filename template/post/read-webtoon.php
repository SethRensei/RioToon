<?php

use Riotoon\Entity\{Webtoon, Chapter};
use Riotoon\Repository\{ChapterRepository, WebtoonRepository};

//Récupération des paramaètres dans l'url, id et le titre du scans
$title = $params['title'];
$id = (int) $params['id'];
$chap_num = $params['chapt'];

/** @var Webtoon|false */
$webtoon = (new WebtoonRepository())->fetchByID(value: $id);

// Vérification sur le paramètre id de l'url pour un résultat faux
if ($webtoon === false)
    throw new Exception("Aucun webtoon n'a été trouvé");

// Mettre le titre récupérer dans la base de données semblable au params['title']
$is_title = goodURL($webtoon->getTitle());
// Vérification sur le paramètre title de l'url sur la correspondance avec la base de données
if ($is_title !== $title) {
    $url = $router->url('show-webt', ['title' => $is_title, 'id' => $webtoon->getId()]);
    header('Location: ' . $url, true, 308);
}

/** @var Chapter|false */
$chapter = ChapterRepository::fetchOne($chap_num, $webtoon->getId());
if ($chapter === false) {
    throw new Exception("Aucun chapitre trouvé");
}

/** @var Chapter|null */
$chapters = ChapterRepository::findWebtoon($webtoon->getId());

$web_title = unClean($webtoon->getTitle());

$imgs = comicReader('../public/' . $chapter->getPathView());

$firsr_chap = (int) end($chapters)->getNumber();
$last_chap = (int) $chapters[0]->getNumber();

$pg_title = $web_title . ' Chapitre ' . $chap_num . ' | RioToon';
$pg_desc = 'Lisez ' . $web_title . ' ' . $chapter->getNumber() . ' sur RioToon';
?>

<div class="page-content user-select-none">
        <div class="read-title">
            <h1><?= $web_title ?></h1>
    </div>
    <div class="links">
        <?php if ($chap_num > $firsr_chap): ?>
                <a href="<?= $router->url('read', ['id' => $webtoon->getId(), 'title' => $is_title, 'chapt' =>  $chap_num - 1]) ?>">&laquo;Précédent</a>
        <?php endif ?>
        <select onChange="location = this.options[this.selectedIndex].value">
            <?php foreach ($chapters as $chap): ?>
                    <option <?= $chap->getNumber() === $chapter->getNumber() ? 'selected' : '' ?>
                        value="<?= $router->url('read', ['id' => $webtoon->getId(), 'title' => $is_title, 'chapt' => $chap->getNumber()]) ?>">
                        <?= $chap->getNumber() ?></option>
            <?php endforeach ?>
        </select>
        <form method="post">
            <button type="submit" name="download" title="Connexion requise">
                <i class="fas fa-download"></i>Télécharger
            </button>
        </form>
        <?php if ($chap_num < $last_chap): ?>
                <a href="<?= $router->url('read', ['id' => $webtoon->getId(), 'title' => $is_title, 'chapt' =>  $chap_num + 1]) ?>">Suivant&raquo;</a>
        <?php endif ?>

    </div>

    <div class="read-webt">
        <?php foreach ($imgs as $img): ?>
                <img src="<?= $img ?>">
        <?php endforeach ?>
    </div>

    <div class="links">
        <?php if ($chap_num > $firsr_chap): ?>
                <a
                    href="<?= $router->url('read', ['id' => $webtoon->getId(), 'title' => $is_title, 'chapt' =>  $chap_num - 1]) ?>">&laquo;Précédent</a>
        <?php endif ?>
        <select onChange="location = this.options[this.selectedIndex].value">
            <?php foreach ($chapters as $chap): ?>
                    <option <?= $chap->getNumber() === $chapter->getNumber() ? 'selected' : '' ?>
                        value="<?= $router->url('read', ['id' => $webtoon->getId(), 'title' => $is_title, 'chapt' => $chap->getNumber()]) ?>">
                        <?= $chap->getNumber() ?>
                    </option>
            <?php endforeach ?>
        </select>
        <?php if ($chap_num < $last_chap): ?>
                <a
                    href="<?= $router->url('read', ['id' => $webtoon->getId(), 'title' => $is_title, 'chapt' =>  $chap_num + 1]) ?>">Suivant&raquo;</a>
        <?php endif ?>
    </div>
</div>