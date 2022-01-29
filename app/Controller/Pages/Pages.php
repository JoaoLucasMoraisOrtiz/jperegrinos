<?php

//arquivos com configurações de uma página genérica a qual será colocado bootstrap

//define um "diretório" para o php, podendo assim existir uma class home diferente em outro namespace
namespace App\Controller\Pages;

use \App\Utils\View;

class Pages{

    /*  getPages() é uma função publica, ou seja, pode ser acessada de outros arquivos com um 
        require ou um "use" - por utilizarmos namespaces;
        Ela é estática, pois nunca haverá: $var = new getPages().
    */

    /**
     * Método responsável por retornar o conteúdo (view) da nossa página genérica;
     * @return string;
     */
    public static function getPages($title, $image, $content){

        //Acessa a classe View e realiza o render da página, retornando a resposta da func. render($arg)
        return View::render('pages/page', [

            //pageName é o titulo da página que queremos ter no nosso title e no nosso primeiro <h1>
            'pageName' => $title,

            'img' => $image,
            
            //content é o conteúdo html completo de nossa página, que é anexado em um documento com bootstrap
            'content' => $content
        ]);
    }

}