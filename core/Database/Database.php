<?php

namespace core\Database;

use PDO;

class Database
{
    private $pdo;

    public function __construct($login = 'root', $password = '', $database = 'worldesportdev', $host = 'localhost')
    {
        $this->pdo = new PDO("mysql:dbname=$database; host=$host", $login, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    /**
     * @param $query
     * @param bool|array $params
     * @return PDOStatement
     */
    public function query($query, $params = false)
    {
        if($params)
        {
            $req = $this->pdo->prepare($query);
            $req->execute($params);
        }
        else
        {
            $req = $this->pdo->query($query);
        }
        return $req;
    }

    /**
     * @return string dernier user id inséré
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function selectAll($table)
    {
        $req = $this->pdo->query('SELECT * FROM '.$table);
        return $req;
    }
}