<?php

namespace Riotoon\Repository;

use Riotoon\Entity\Webtoon;
use Riotoon\Service\DbConnection;

class WebtoonRepository
{
    private \PDO $connec;
    private $items;

    public function __construct() {
        $this->connec = DbConnection::GetConnection();
    }

    public function create(Webtoon $webtoon) {
        try {
            $q = $this->connec->prepare("INSERT INTO webtoon(title, author, synopsis, cover, release_year, status)
            VALUES(:tit, :aut, :syn, :cov, :rel, :sta)");
            $q->bindValue(':tit', $webtoon->getTitle(), \PDO::PARAM_STR);
            $q->bindValue(':aut', $webtoon->getAuthor(), \PDO::PARAM_STR);
            $q->bindValue(':syn', $webtoon->getSynopsis(), \PDO::PARAM_STR);
            $q->bindValue(':cov', $webtoon->getCover(), \PDO::PARAM_STR);
            $q->bindValue(':rel', $webtoon->getReleaseYear(), \PDO::PARAM_INT);
            $q->bindValue(':sta', $webtoon->getStatus(), \PDO::PARAM_BOOL);

            $q->execute();
            $last_id = $this->connec->lastInsertId();
            $q->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenu à l'ajout d'un webtoon : {$e->getMessage()}");
        }

        return $last_id;
    }

    public function update(Webtoon $webtoon)
    {
        try {
            $query = $this->connec->prepare("UPDATE webtoon SET title = :tit, author = :aut, synopsis = :syn, cover = :cov,
            release_year = :rel, status = :sta, update_at = NOW()
            WHERE w_id = :id");
            $query->bindValue(':tit', $webtoon->getTitle(), \PDO::PARAM_STR);
            $query->bindValue(':aut', $webtoon->getAuthor(), \PDO::PARAM_STR);;
            $query->bindValue(':syn', $webtoon->getSynopsis(), \PDO::PARAM_STR);
            $query->bindValue(':cov', $webtoon->getCover(), \PDO::PARAM_STR);
            $query->bindValue(':rel', $webtoon->getReleaseYear(), \PDO::PARAM_INT);
            $query->bindValue(':sta', $webtoon->getStatus(), \PDO::PARAM_BOOL);
            $query->bindValue(':id', $webtoon->getId());

            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de la mise à jour : " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $query = $this->connec->prepare('DELETE FROM webtoon WHERE w_id = :id');
            $query->bindValue(':id', clean($id));
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de suppression des genres du webtoon : " . $e->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $query = $this->connec->query("SELECT * FROM webtoon");
            $this->items = $query->fetchAll(\PDO::FETCH_CLASS, Webtoon::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les information : " . $e->getMessage());
        }

        return $this->items;
    }

    public function fetchByID($value)
    {
        $q = "SELECT webtoon.*,
            STRING_AGG(category.c_id::TEXT, ',') AS c_ids,
            STRING_AGG(category.label, ',') AS categories
            FROM webtoon
            LEFT OUTER JOIN web_cat ON webtoon.w_id = web_cat.webtoon
            LEFT OUTER JOIN category ON category.c_id = web_cat.category
            WHERE webtoon.w_id = {$value}
            GROUP BY webtoon.w_id";
        try {
            $query = $this->connec->query($q);
            $query->setFetchMode(\PDO::FETCH_CLASS, Webtoon::class);
            $this->items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible d'éxecuter la requête" . $e->getMessage());
        }

        return $this->items;
    }
}
