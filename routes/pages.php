<?php

//cria nossas rotas de página

use \App\Controller\Pages;
use \App\Http\Response;
use \App\Controller\Users\Validation;

//define a rota para um GET em '/'
$obRouter->get('/', [
    //quando o GET for em '/':
    function(){

        //retorna uma nova Response com HTTPCode = 200 na página de Home
        return new Response(200, Pages\Login::getLogin());
    }
]);

//define a rota para um GET em '/store'
$obRouter->get('/store', [

    //quando o GET for em '/store':
    function(){
        return new Response(200, Pages\Store::getStore());

    }
]);

//define uma rota dinâmica (por exemplo para o usuário)
$obRouter->get('/user/{idUser}', [
    function($idUser){
        return new Response(200, 'pagina do usuário: ' . $idUser);
    }
]);

$obRouter->get('/home', [
    //quando o GET for em '/':
    function(){

        //retorna uma nova Response com HTTPCode = 200 na página de Home
        return new Response(200, Pages\Home::getHome());
    }
]);

$obRouter->post('/validation', [
    function(){
        $googleResponse = $_POST;

        $confirm  = new Validation;

        $confirm->user($googleResponse);

        /* if($confirm){
            $payment = Validation::paymentValidation();

            if($payment){
                return new Response(200, Pages\Home::getHome());
            }else{
                return new Response(200, Pages\Store::getStore());
            }
        }else{
            return new Response(200, Pages\Store::getStore());
        } */
    }
]);