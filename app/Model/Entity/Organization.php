<?php

//aqui ficará nossos dados vindos das conexões com o banco de dados;

namespace App\Model\Entity;
use App\Model\API;
use App\Model\API\DbManeger;

class Organization {

    /**
     * Variável para guardar a coneção com o banco de dados;
     * @var instance
     */
    private $api;


    public function __construct(){
        $this -> api = DbManeger::init();
    }

    public function getValues($where){
        $this->api->get();
    }

    //estes dados são demonstrativos, eles virão da DB
    public $name = 'Store';
    public $description = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto quasi maxime amet accusamus vel saepe id, incidunt enim doloribus non eius quisquam corrupti deleniti alias rem quidem aperiam laboriosam velit!';
}

