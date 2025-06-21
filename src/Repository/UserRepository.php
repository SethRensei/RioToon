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

    public function edit(User $user)
    {
        try {
            $query = $this->connection->prepare('UPDATE "user" SET fullname = :ful, roles = :rol
            WHERE u_id = :id');
            $query->bindValue(':ful', $user->getFullname());
            $query->bindValue(':rol', $user->getRoles());
            $query->bindValue(':id', $user->getId());

            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de la mise à jour : " . $e->getMessage());
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

    public function find(string $search)
    {
        try {
            $query = $this->connection->prepare('SELECT * FROM "user"
                WHERE pseudo = :sea OR email = :sea');
            $query->bindValue('sea', clean($search));
            $query->execute();
            $query->setFetchMode(\PDO::FETCH_CLASS, User::class);
            $this->items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible d'éxecuter la requête" . $e->getMessage());
        }
        return $this->items;
    }

    public function editPassword(User $user)
    {
        try {
            $query = $this->connection->prepare('UPDATE "user" SET password = :pas
            WHERE pseudo = :pse');
            $query->bindValue(':pas', $user->getPassword());
            $query->bindValue(':pse', $user->getPseudo());

            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors du changement du mot de passe : " . $e->getMessage());
        }
    }

    public function addProfilePicture(User $user)
    {
        try {
            $query = $this->connection->prepare('UPDATE "user" SET profile_picture = :pro WHERE pseudo = :pse');
            $query->bindValue(':pro', $user->getProfile());
            $query->bindValue(':pse', $user->getPseudo());

            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de l'ajout de la photo de profil : " . $e->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $query = $this->connection->query('SELECT * FROM "user"');
            $this->items = $query->fetchAll(\PDO::FETCH_CLASS, User::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les information : " . $e->getMessage());
        }

        return $this->items;
    }

    public function getCountLike($id)
    {
        try {
            $query = $this->connection->prepare('SELECT COUNT("user") as user_count FROM vote 
                WHERE "user"= :id AND vote = 1');
            $query->bindValue(":id", clean($id));
            $query->execute();
            $this->items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible de récupérer le nombre total de likes : " . $e->getMessage());
        }

        return $this->items;
    }

    public function getCountDislike($id)
    {
        try {
            $query = $this->connection->prepare('SELECT COUNT("user") as user_count FROM vote 
                WHERE "user"= :id AND vote = -1');
            $query->bindValue(":id", clean($id));
            $query->execute();
            $this->items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible de récupérer le nombre total de likes : " . $e->getMessage());
        }

        return $this->items;
    }
}
