<?php

namespace Riotoon\Repository;

use Riotoon\Entity\Webtoon;

class WebtoonRepository
{
    private \PDO $connec;

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
}
