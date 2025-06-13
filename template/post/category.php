<?php
use Riotoon\Entity\Webtoon;
use Riotoon\Repository\CategoryRepository;
use Riotoon\Service\{Pagination, URL};

$id = $params['id'];
$label = $params['label'];

/** @var CategoryRepository */
$category = CategoryRepository::getById(id: $id);
if ($category == false)
    throw new Exception("Aucun webtoon n'a été trouvé avec ce genre", 404);
if ($label != goodURL($category->getName()))
    throw new Exception("Aucun webtoon n'a été trouvé avec ce genre", 404);

$paginator = new Pagination("SELECT webtoon.*, STRING_AGG(category.label, ',') AS categories
    FROM webtoon
    LEFT OUTER JOIN web_cat ON webtoon.w_id = web_cat.webtoon
    LEFT OUTER JOIN category ON category.c_id = web_cat.category
    WHERE category.c_id = $id
    GROUP BY webtoon.w_id");

$page = URL::getPositiveInt('page', 1);

/** @var Webtoon*/
$webtoons = $paginator->getData(Webtoon::class, 10, $page);
?>

<div class="page-content">
<?php if (!empty($webtoons)): ?>
        <div class="webt-list">
            <?php foreach ($webtoons as $webtoon): ?>
                <a
                    href="<?= $router->url('show-webt', ['id' => $webtoon->getId(), 'title' => goodURL($webtoon->getTitle())]) ?>">
                    <div class="list">
                        <div class="text">
                            <h4><?= excerpt($webtoon->getTitle()) ?></h4>
                        </div>
                        <div class="item">
                            <img src="<?= BASE_URL . $webtoon->getCover() ?>">
                            <div class="text2">
                                <h4><?= excerpt($webtoon->getTitle()) ?></h4>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach ?>
        </div>
        <div class="rio-paginated">
            <?= $paginator->createLinks(3, 'rounded-blocks') ?>
        </div>
    <?php else: ?>
        <div class="webt-list">
            <h1>Aucun webtoons ajoutés pour le moment</h1>
        </div>
    <?php endif ?>
</div>