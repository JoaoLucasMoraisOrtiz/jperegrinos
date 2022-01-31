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

$obRouter->get('/destroy', [
    function () {

        session_start();
        session_destroy();
        return new Response(200, Pages\Login::getLogin());
    }
]);

$obRouter->get('/admin', [
    function () {

        session_start();

        $user = $_SESSION['admin'] ?? '';
        $pass = $_SESSION['pass'] ?? '';

        if ($user == getenv("ADMIN_USER") && $pass == getenv("ADMIN_PASS")) {
            return new Response(200, Pages\Admin::getAdmin('hide'));
        } else {
            return new Response(200, Pages\Admin::getAdmin('show'));
        }
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

        if(isset($_POST['description'])){
            $description = "nome: " . $_SESSION['name'] . " / Valor: " . $_POST['value'];
        }else{
            $description = 0;
        }

        $pixKey = getenv('PIX_KEY');
        $merchantName = getenv('MERCHANT_NAME');
        $merchantCity = getenv('MERCHANT_CITY');
        $amount = $_POST['value'];
        $txid = 'jp';

        $QrCode = Utils\Pix\GenerateQrCode::createQrCode($pixKey, $merchantName, $merchantCity, $amount, $txid, $description);
        $img = "<img style='width: 100%' src='data:image/png;base64," . $QrCode[0] . "'>";
        print_r($img);
        print_r("<div id='qrCode' hidden>" . $QrCode[1] . "</div>");
        $ObOrganization = new Organization;
        $ObOrganization -> db_methods('POST', 'clients', [
            'name'  => $_SESSION['name'],
            'value' => $_POST['value'],
            'event' => $_POST['title']
        ]);
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

$obRouter->post('/admin', [
    function () {
        session_start();

        $data = $_POST;

        if (isset($data['login'])) {

            $data = json_decode($data['login'], true);

            if ($data['user'] == getenv('ADMIN_USER')) {
                if ($data['pass'] == getenv('ADMIN_PASS')) {
                    $_SESSION['admin'] = $data['user'];
                    $_SESSION['pass'] = $data['pass'];
                    print_r('true');
                    exit;
                } else {
                    print_r('false');
                    exit;
                }
            } else {
                print_r('false');
                exit;
            }
        }

        if (isset($data['searchPost'])) {

            $data = json_decode($data['searchPost']);

            $ObOrganization = new Organization;

            $post = $ObOrganization->db_methods('GET', 'posts', $data);

            print_r(json_encode($post));
            exit;
        }

        if (isset($data['editPost'])) {

            $data = json_decode($data['editPost'], true);

            $ObOrganization = new Organization;

            $post = $ObOrganization->db_methods('PUT', 'posts', $data);

            print_r('ok');
            exit;
        }

        if (isset($data['deletePost'])) {

            $data = json_decode($data['deletePost'], true);

            $ObOrganization = new Organization;

            $post = $ObOrganization->db_methods('DELETE', 'posts', $data);

            print_r($data);
            exit;
        }

        if (isset($data['createPost'])) {
            $data = json_decode($data['createPost'], true);

            $ObOrganization = new Organization;

            $post = $ObOrganization->db_methods('POST', 'posts', $data);

            print_r('ok');
            exit;
        }

        if (isset($data['createEvent'])) {
    
            $data = json_decode($data['createEvent'], true);

            $ObOrganization = new Organization;

            $post = $ObOrganization->db_methods('POST', 'events', $data);

            print_r('ok');
            exit;
        }

        if (isset($data['deleteEvent'])) {

            $data = json_decode($data['deleteEvent'], true);

            $ObOrganization = new Organization;

            $ObOrganization->db_methods('DELETE', 'events', $data);
            
            print_r($data);
            exit;
        }

        if (isset($data['searchEvent'])) {

            $data = json_decode($data['searchEvent']);

            $ObOrganization = new Organization;

            $post = $ObOrganization->db_methods('GET', 'events', $data);

            print_r(json_encode($post));
            exit;
        }

        if (isset($data['editEvent'])) {

            $data = json_decode($data['editEvent'], true);

            $ObOrganization = new Organization;

            $post = $ObOrganization->db_methods('PUT', 'events', $data);

            print_r('ok');
            exit;
        }

        if (isset($data['deleteClient'])) {

            $data = json_decode($data['deleteClient'], true);

            $ObOrganization = new Organization;

            $post = $ObOrganization->db_methods('DELETE', 'clients', $data);

            print_r('ok');
            exit;
        }

        if (isset($data['searchClients'])) {

            $data = json_decode($data['searchClients']);

            $obAdministrator = new Pages\Admin;

            $post = $obAdministrator->getClients($data);

            print_r(json_encode($post));
            exit;
        }
    }
]);

$obRouter->post('/home', [
    function () {

        $data = $_POST;

        $data = json_decode($data['searchPost']);

        $ObOrganization = new Organization;

        $post = $ObOrganization->db_methods('GET', 'posts', $data);

        print_r($post);
        exit;
    }
]);