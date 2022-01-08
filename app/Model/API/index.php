<?php

require_once __DIR__ . "/controller/ActionsPost.php";
require_once __DIR__ . "/controller/ActionsUsers.php";

//                          -=-=-=-=-=-=-=- POSTS -=-=-=-=-=-=-=-=-

/* $api = new ActionsPosts; */

//Trazendo os posts do banco de dados -->Funcionando
/* $content = $api->get(); */

//enviando um post para o Banco de dados -->Funcionando
/* $content = [
    'name'        => 'NOME DO POST',
    'type'        => 'evento',
    'image'         => 'https://picsum.photos/200/300',
    'description' => 'CONTEÚDO DO POST, COMO EXPLICANDO A DATA DO NOSSO EVENTO'
];

$response = $api->post($content); */


//editando posts na DB -->Funcionando
/* $postToEdit = $api->get(3);
$postToEdit['type'] = 'musica';

$content = $api->update($postToEdit); */


//deletando posts na DB -->Funcionando
/* $api->delete(4); */

//                            -=-=-=-=-=-=- USERS -=-=-=-=-=-=-
/* $api = new ActionsUsers; */

//Trazendo os usuários do banco de dados -->Funcionando
/* $content = $api->get(); */


//criando um usuário no banco de dados
/* $param = [
    'id' => '111',
    'name' => 'Joao Lucas Morais Ortiz',
    'email' => 'joaolucasortiz612@gmail.com',
    'amounth' => '25.00',
    'payment' => 'post'
];

$post = $api->post($param); */

//editando um usuário na DB -->Funcionando
/* $param = [
    'id' => '789',
    'name' => 'Joao Lucas Morais Ortiz',
    'email' => 'joaolucasortiz612@gmail.com',
    'amounth' => '250.00',
    'payment' => 'check'
];

$post = $api->update($param); */

//deletando um usuário na DB -->Funcionando

/* $api->delete('dcbdc94c1c2fa50ea522ee99746b8d43c3863453941898dea78a93018dfcb168'); */