<?php

namespace Riotoon\Repository;

use Riotoon\Entity\User;
use Riotoon\Service\BuildErrors;
use Riotoon\Service\DbConnection;

class UserRepository
{
    private \PDO $connection;
    private $items;

    public function __construct()
    {
        $this->connection = DbConnection::GetConnection();
    }
    
    public function create(User $user) {
        if ($this->isUser($user))
            BuildErrors::setErrors('userExists', "Pseudo ou mail déjà existant");
        else {
            try {
                $q = $this->connection->prepare('INSERT INTO "user"(pseudo, fullname, email, password, roles)
                VALUES(:pse, :ful, :ema, :pass, :rol)');
                $q->bindValue(':pse', $user->getPseudo(), \PDO::PARAM_STR);
                $q->bindValue(':ful', $user->getFullname(), \PDO::PARAM_STR);
                $q->bindValue(':ema', $user->getEmail(), \PDO::PARAM_STR);
                $q->bindValue(':pass', $user->getPassword(), \PDO::PARAM_STR);
                $q->bindValue(':rol', $user->getRoles(),);
    
                $q->execute();
                $q->closeCursor();
            } catch (\PDOException $e) {
                die("Une erreur est survenu à l'ajout d'un l'utilisateur : {$e->getMessage()}");
            }
        }
    }

    private function isUser(User $user) : bool
    {
        try {
            $statement = $this->connection->prepare('SELECT * FROM "user" WHERE pseudo = :pse OR email = :ema');
            $statement->bindValue(':pse', clean($user->getPseudo()));
            $statement->bindValue(':ema', clean($user->getEmail()));
            $statement->execute();
            return $statement->rowCount() > 0;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return true;
    }
}
