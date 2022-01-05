<?php

//arquivos com configurações das variáveis da página HOME

//define um "diretório" para o php, podendo assim existir uma class home diferente em outro namespace
namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Home extends Pages{

    /*  gethome() é uma função publica, ou seja, pode ser acessada de outros arquivos com um 
        require ou um "use" - por utilizarmos namespaces;
        Ela é estática, pois nunca haverá: $var = new getHome().
    */

    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home;
     * @return string;
     */
    public static function getHome(){

        $obOrganization = new Organization;

        //Acessa a classe View e realiza o render da home, retornando a resposta da func. render($arg)
        $pageContent =  View::render('pages/home', [
            'description' => $obOrganization->getPosts()
        ]);

        //retorna uma função do parente (ou seja, de quem a classe estende), que cria uma página
        return parent::getPages('Home', $pageContent);
    }

}