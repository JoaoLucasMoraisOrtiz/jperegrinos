<?php

//cria nossas rotas de página

use \App\Controller\Pages;
use \App\Http\Response;
use \App\Controller\Users\Validation;
use \App\Utils;
use \App\Model\Entity\Organization;

/* 
                        -=-=-=-=-=-=- ROTAS DE GET -=-=-=-=-=-=-
*/

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

        session_start();

        if (!isset($_SESSION['user'])) {
            //retorna uma nova Response com HTTPCode = 200 na página de Login
            return new Response(200, Pages\Login::getLogin());
        } else {
            //retorna uma nova Response com HTTPCode = 200 na página de Home
            return new Response(200, Pages\Home::getHome());
        }
    }
]);

$obRouter->get('/destroy', [
    function () {

        session_start();
        session_destroy();
        return new Response(200, Pages\Login::getLogin());
    }
]);

/* 
                        -=-=-=-=-=-=- ROTAS DE POST -=-=-=-=-=-=-
*/

$obRouter->post('/validation', [
    function () {
        session_start();

        $googleResponse = $_POST;

        $confirm  = new Validation;

        $confirm->user($googleResponse);
    }
]);

$obRouter->post('/payment', [
    function () {
        session_start();

        $pixKey = getenv('PIX_KEY');
        $merchantName = getenv('MERCHANT_NAME');
        $merchantCity = getenv('MERCHANT_CITY');
        $amount = str_replace("_", ".", array_keys($_POST)[0]);
        $txid = 'jp';

        $QrCode = Utils\Pix\GenerateQrCode::createQrCode($pixKey, $merchantName, $merchantCity, $amount, $txid);
        $img = "<img src='data:image/png;base64," . $QrCode[0] . "'>";
        print_r($img);
        print_r("<div id='qrCode' hidden>" . $QrCode[1] . "</div>");
    }
]);

$obRouter->post('/payment/confirmation', [
    function () {
        session_start();

        $date = $_POST['date'];
        $date = json_decode($date, true);
        /* print_r($date["value"]); */

        $_SESSION['amounth'] = $date['value'];
        $_SESSION['payment'] = $date['method'];

        $ObOrganization = new Organization;

        $ObOrganization->db_methods("POST", "users", [
            'id' => $_SESSION['user'],
            'name' => $_SESSION['name'],
            'email' => $_SESSION['email'],
            'amounth' => $_SESSION['amounth'],
            'payment' => $_SESSION['payment'],
        ]);

        print_r(getenv('URL'));
        exit;
    }
]);
