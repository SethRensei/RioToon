<?php

namespace Riotoon\Repository;

use Riotoon\Entity\Category;
use Riotoon\Service\DbConnection;

class CategoryRepository extends Category
{
    private static \PDO $connec;

    public static function init() {
        if (!isset(self::$connec)) {
            self::$connec = DbConnection::GetConnection();
        }
    }
    public static function findAll()
    {
        self::init();
        try {
            $query = self::$connec->query("SELECT * FROM category ORDER BY label ASC");
            $items = $query->fetchAll(\PDO::FETCH_CLASS, Category::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les information : " . $e->getMessage());
        }

        return $items;
    }

    public static function addCategoriesForWebtoon($webtoon, $category)
    {
        self::init();
        try {
            $query = self::$connec->prepare('INSERT INTO web_cat(webtoon, category) VALUES(:web, :cat)');
            $query->bindValue(':web', clean($webtoon));
            $query->bindValue(':cat', clean($category));
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de l'insertion : " . $e->getMessage());
        }
    }

    public static function deleteCategorieForWebtoon($webtoon)
    {
        self::init();
        try {
            $query = self::$connec->prepare('DELETE FROM web_cat WHERE webtoon = :web');
            $query->bindValue(':web', clean($webtoon));
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de suppression des genres du webtoon : " . $e->getMessage());
        }
    }
}
