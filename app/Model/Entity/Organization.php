<?php

//aqui ficará nossos dados vindos das conexões com o banco de dados;

namespace App\Model\Entity;

use ActionsEvents;
use ActionsPosts;
use ActionsUsers;
use ActionsClients;

require_once __DIR__ . '/../API/controller/ActionsPost.php';
require_once __DIR__ . '/../API/controller/ActionsUsers.php';
require_once __DIR__ . '/../API/controller/ActionsEvents.php';
require_once __DIR__ . '/../API/controller/ActionsClients.php';

class Organization
{

    /**
     * Variável para guardar a coneção com o banco de dados;
     * @var instance
     */
    private $api;

    /* 
        Funções privadas da classe
    */

    /**
     * Inicia dentro da variável $api uma instância da nossa função de conexão com o banco de
     * dados, na coluna que colocarmos como parametro.
     * @param string $table
     */
    private function init($table)
    {

        if (strtolower($table) == 'posts') {

            $this->api = new ActionsPosts;
        }

        if (strtolower($table) == 'users') {

            $this->api = new ActionsUsers;
        }

        if (strtolower($table) == 'events') {
            $this->api = new ActionsEvents;
        }

        if (strtolower($table) == 'clients') {
            $this->api = new ActionsClients;
        }
    }

    /**
     * Método responsável por fazer nossas ações de CRUD em posts
     * @param string $method
     * @param string $table
     * @param array $param
     * @return array/instance quando $method == GET/quando $method == INSTANCE
     */
    public function db_methods($method, $table, $params = 0)
    {

        if (strtoupper($method) == "GET") {

            $this->init($table);
            
            return $this->api->get($params);
        }

        if (strtoupper($method) == "POST") {

            $this->init($table);

            $this->api->post($params);
        }

        if (strtoupper($method) == "PUT") {

            $this->init($table);

            $this->api->update($params);
        }

        if (strtoupper($method) == "DELETE") {

            $this->init($table);

            $this->api->delete($params);
        }

        if (strtoupper($method) == "INSTANCE") {

            $this->init($table);

            return $this->api;
        }
    }
}
