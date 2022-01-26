<?php

//Classe responsável por criar nossas variáveis de ambiente vindas de .ent

namespace App\Utils;
use \FFI\Exception;

class Environment{

    /**
     * Método que carrega nossas variáveis de ambiente que foram setadas em um arquivo
     * @param string $dir Caminho do diretório onde a func. é chamada, até o arquivo .env
     */
    public static function load($dir){

        //$file recebe o arquivo que pegamos com o caminho fornecido em $dir
        $file = file($dir);

        //se o arquivo não existir
        if(!isset($file)){

            //exibe um erro 
            throw new Exception(500, 'Não foi possível obter o arquivo com as variáveis de ambiente');     
        }

        //inicializa a variável $vars que é um array
        $vars = [];

        //para cada linha no arquivo
        foreach ($file as $line) {

            //ele adiciona a linha na variável $vars
            array_push($vars, $line);
        }

        //para cada ítem em $vars, executa um putenv($item) com ele
        foreach($vars as $item){
            putenv(rtrim($item));
        }

    }
}