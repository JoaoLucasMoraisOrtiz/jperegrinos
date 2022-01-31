<?php

//arquivos com configurações das variáveis da página de index

//define um "diretório" para o php, podendo assim existir uma class home diferente em outro namespace
namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Admin extends Pages
{

    private static function getPosts()
    {
        $obOrganization = new Organization;
        $posts = $obOrganization->db_methods('GET', 'posts');

        $length = count($posts);
        $str = '';

        for ($i = 0; $i <= $length - 1; $i++) {

            $name = $posts[$i]['name'];
            $description = $posts[$i]['description'];
            $id = $posts[$i]['id'];

            $htmlPost = '<div class="row" style="margin: 0vh 0vw 3vh 0vw"><div class="card" style="max-width: 50vw"><div class="card-title"><h5>' . $name . '</h5></div><div class="card-body" style="overflow: hidden;text-overflow: ellipsis;"><p>' . $description . '</p></div><div class="card-footer"><button type="button" class="btn btn-warning" style="margin-right: 0.5vw" onclick="getPost(' . $id . ')">Editar</button><button type="button" class="btn btn-danger" onclick="remove(' . $id . ')">Excluir</button></div></div></div>';

            $str .= $htmlPost;
        }

        return $str;
    }

    private static function getEvent()
    {
        $obOrganization = new Organization;
        $posts = $obOrganization->db_methods('GET', 'events');

        $length = count($posts);
        $str = '';



        for ($i = 0; $i <= $length - 1; $i++) {

            $name = $posts[$i]['name'];
            $id = $posts[$i]['id'];
            $value = $posts[$i]['value'];

            $htmlPost = '
            <div class="card" style="position: static; box-shadow: 1px 2px 15px black;">
                <div class="card-title" style=" text-align: center; padding-top: 2vh;">
                    <h5 class="text-success" id="titleEvent">' . $name . '</h5>
                    <p hidden id="valueEvent">' . $value . '<p>
                    
                </div>
                <hr>
                <div class="card-footer" id="qrCodeEvent">
                    <button class="btn btn-warning" onclick="getEvent(' . $id . ')">Editar</button>
                    <button class="btn btn-danger" onclick="removeEvent(' . $id . ')">Apagar</button>
                    
                </div>
            </div>';

            $str .= $htmlPost;
        }

        return $str;
    }

    public static function getClients($require = '')
    {
        $obOrganization = new Organization;
        $posts = $obOrganization->db_methods('GET', 'clients');

        $length = count($posts);
        $str = '';



        if ($require == '') {
            for ($i = 0; $i <= $length - 1; $i++) {

                $name = $posts[$i]['name'];
                $event = $posts[$i]['event'];
                $value = $posts[$i]['value'];
                $id = $posts[$i]['id'];

                $htmlPost = '
                <div class="card" style="position: static; box-shadow: 0.5px 1px 15px black; margin-bottom: 2vh">
                    <div class="card-title" style=" text-align: center; padding-top: 1vh;">
                        <h5 class="text-danger" id="nameClient">' . $name . '</h5>
                        <div class="nameClientEvent">' . $event . '</div>
                        <p>' . $value . '</p>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-danger" onclick="deleteClient(' . $id . ')">Deletar</button>
                    </div>
                </div>';

                $str .= $htmlPost;
            }
        } else {
            for ($i = 0; $i <= $length - 1; $i++) {
                $name = $posts[$i]['name'];
                $event = $posts[$i]['event'];
                $value = $posts[$i]['value'];
                $id = $posts[$i]['id'];

                if ($require == $event) {
                    $htmlPost = '
                        <div class="card" style="position: static; box-shadow: 0.5px 1px 15px black; margin-bottom: 2vh">
                            <div class="card-title" style=" text-align: center; padding-top: 1vh;">
                                <h5 class="text-danger" id="nameClient">' . $name . '</h5>
                                <div class="nameClientEvent">' . $event . '</div>
                                <p>' . $value . '</p>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-danger" onclick="deleteClient(' . $id . ')">Deletar</button>
                            </div>
                        </div>';
                }

                $str .= $htmlPost;
            }
        }
        return $str;
    }

    private static function getClientEvent()
    {
        $obOrganization = new Organization;
        $posts = $obOrganization->db_methods('GET', 'clients');

        $length = count($posts);
        $str = '';
        $eventKey = [];

        for ($i = 0; $i <= $length - 1; $i++) {

            $event = $posts[$i]['event'];

            array_push($eventKey, $event);
        }

        $eventKey = array_unique($eventKey);
        $length = count($eventKey);

        for ($i = 0; $i <= $length - 1; $i++) {

            $event = $eventKey[$i];

            $htmlPost = '
            <option value="' . $event . '">' . $event . '</option>';

            $str .= $htmlPost;
        }
        return $str;
    }


    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home;
     * @return string;
     */
    public static function getAdmin($session)
    {

        #$obOrganization = new Organization;
        $args = self::getPosts();

        //Acessa a classe View e realiza o render da home, retornando a resposta da func. render($arg)
        $pageContent =  View::render('pages/admin', [
            'posts' => $args,
            'session' => $session,
            'events' => self::getEvent(),
            'optionsEvents' => self::getClientEvent(),
            'clients' => self::getClients()
        ]);

        return parent::getPages('Admin', '', $pageContent);
    }
}
