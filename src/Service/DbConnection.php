<?php

namespace Riotoon\Service;

class DbConnection
{
    /**
     * Establishes a database connection using PDO.
     * @return \PDO The PDO database connection object.
     */
    public static function GetConnection(): \PDO
    {
        // Récupérer les détails de connexion à la base de données à partir des variables d'environnement
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $name = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        try {
            $pdo = new \PDO("pgsql:host=$host;port=$port;dbname=$name", $user, $pass);
            // Définir le mode d'erreur PDO sur les exceptions
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            // Si la connexion échoue, terminez le script et affichez un message d'erreur
            die('Connexion impossible : ' . $e->getMessage() . ' à la ligne : ' . $e->getLine());
        }

        return $pdo;
    }

}
