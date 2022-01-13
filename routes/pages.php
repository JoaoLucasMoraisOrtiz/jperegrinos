<?php

//cria nossas rotas de página

use \App\Controller\Pages;
use \App\Http\Response;
use \App\Controller\Users\Validation;

//define a rota para um GET em '/'
$obRouter->get('/', [
    //quando o GET for em '/':
    function () {

        //inicia a sessão
        session_start(); 
    
        //recebe o usuário da sessão ou falso
        $login = $_SESSION['user'] ?? false;

        //se o usuário da sessão for falso
        if (!$login) {
            //retorna uma nova Response com HTTPCode = 200 na página de Home
            return new Response(200, Pages\Login::getLogin());
        } else {

            $payed = Validation::verifyPayment($_SESSION['user']);

            if ($payed) {

                return new Response(200, Pages\Home::getHome());
            } else {

                return new Response(200, Pages\Store::getStore());
            }
        }
    }
]);

//define a rota para um GET em '/store'
$obRouter->get('/store', [

    //quando o GET for em '/store':
    function () {

        session_start();

        if (!isset($_SESSION['user'])) {

            return new Response(200, Pages\Login::getLogin());
        } else {
            return new Response(200, Pages\Store::getStore());
        }
    }
]);

//define uma rota dinâmica (por exemplo para o usuário)
$obRouter->get('/user/{idUser}', [
    function ($idUser) {
        return new Response(200, 'pagina do usuário: ' . $idUser);
    }
]);

$obRouter->get('/home', [
    //quando o GET for em '/':
    function () {

        //retorna uma nova Response com HTTPCode = 200 na página de Home
        return new Response(200, Pages\Home::getHome());
    }
]);

$obRouter->post('/validation', [
    function () {
        session_start();

        $googleResponse = $_POST;

        $confirm  = new Validation;

        $confirm->user($googleResponse);
    }
]);

$obRouter->get('/destroy', [
    function () {

        session_start();
        session_destroy();
        return new Response(200, Pages\Login::getLogin());
    }
]);