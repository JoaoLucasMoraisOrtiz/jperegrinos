<?php

namespace App\Model\API\helpers;
use PDO;

class Database{
    //função para conectar no banco de dados com o PDO (como na aula de PWI)
    public static function connection($link, $dbName, $usr, $pass): PDO
    {
        return new PDO("mysql:host=$link;dbname=$dbName", $usr, $pass);
    }

}
