<?php

namespace App\Model\API;

use RouterWorks;

require_once "src/PHP/routes/index.php";

class DbManeger{

    /**
     * Método responsável por instanciar RouterWorks
     * @return instance
     */
    public static function init(){
        $obRouter = new RouterWorks;

        return $obRouter;
    }
    
}