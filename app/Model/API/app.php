<?php

namespace App\Model\API;

require __DIR__ . "/controller/index.php";

use App\Model\API\controller\Posts\ActionsPosts;
use App\Model\API\controller\Users\ActionsUsers as UsersActionsUsers;

class DbManeger
{

    /**
     * Método responsável por instanciar a classe de controller dos posts
     * @return instance
     */
    public static function initPost()
    {
        $obRouter = new ActionsPosts;

        return $obRouter;
    }

    /**
     * Método responsável por instanciar a classe de controller dos users
     * @return instance
     */
    public static function initUsers()
    {
        $obRouter = new UsersActionsUsers;

        return $obRouter;
    }
}
