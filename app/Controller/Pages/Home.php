<?php

//arquivos com configurações das variáveis da página HOME

//define um "diretório" para o php, podendo assim existir uma class home diferente em outro namespace
namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Home extends Pages{

    /**
     * Método responsável por criar a parte HTML do post e popula-la com os dados da DB.
     * Em seguida ela retorna uma string contendo estes textos HTMl com os dados da DB.
     * @return string
     */
    private static function getPosts(){
        $obOrganization = new Organization;
        $posts = $obOrganization->db_methods('GET', 'posts');
        $length = count($posts);
        $str = '';

        for ($i=0; $i <= $length - 1; $i++) { 

            $img = $posts[$i]['image'];
            $name = $posts[$i]['name'];
            $description = $posts[$i]['description'];

            $htmlPost = '<div class="row""><div class="card-body"><div class="card-title"><img class="card-img-top" src="'.$img.'" alt="Card image cap" style="width: auto; heigth=auto;"><h5>'.$name.'</h5></div><div class="card-text" style="overflow: hidden;text-overflow: ellipsis;"><p>'.$description.'</p></div><a href="#" class="btn btn-primary">Go somewhere</a></div></div>';

            $str .= $htmlPost;
        }

        return $str;
    }

    /*  gethome() é uma função publica, ou seja, pode ser acessada de outros arquivos com um 
        require ou um "use" - por utilizarmos namespaces;
        Ela é estática, pois nunca haverá: $var = new getHome().
    */

    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home;
     * @return string;
     */
    public static function getHome(){
        
        $args = self::getPosts();

        //Acessa a classe View e realiza o render da home, retornando a resposta da func. render($arg)
        $pageContent =  View::render('pages/home', [
            'posts' => $args]);

        //retorna uma função do parente (ou seja, de quem a classe estende), que cria uma página
        return parent::getPages('Home', $pageContent);
    }

}