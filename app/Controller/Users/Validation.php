<?php

namespace App\Controller\Users;

use \App\Model\Entity\Organization;
use \App\Controller\Pages\Home;

class Validation
{

    //Variáveis que são utilizadas para enviar um usuário para a DB
    /**
     * Sub vindo do google
     * @var integer
     */
    private $id;

    /**
     * nome do usuário
     * @var string
     */
    private $name;

    /**
     * email do usuário
     * @var string
     */
    private $email;

    /**
     * armazena o valor do pagamento do usuário
     * @var string
     */
    private $amounth;

    /**
     * guarda data do pagamento do usuário
     * @var string
     */
    private $date;

    /**
     * confirmação se o usuário pagou o mês
     * @var string
     */
    private $payment;

    /**
     * Função responsável por popular as variáveis privadas que são determinadas pelo usuário
     * @param array $googleResponse
     */
    private function setVariables($googleResponse)
    {
        $this->id = $googleResponse['sub'];
        $this->name = $googleResponse['name'];
        $this->email = $googleResponse['email'];
    }

    /**
     * Método responsável por verificar se o usuário existe na nossa DB
     * @param array $googleResponse
     * @return array [bool, array]
     */
    private function verifyUser($googleResponse)
    {

        $this->setVariables($googleResponse);

        $obOrganization = new Organization;
        $user = $obOrganization->db_methods('GET', 'users', $this->id);

        if ($user) {
            return [true, $user];
        } else {
            return false;
        }
    }

    /**
     * Método responsável por verificar se o usuário pagou no mês
     * @param array
     * @return bool
     */
    private function verifyPayment($id)
    {

        $id = '123';
        $obOrganization = new Organization;
        $user = $obOrganization->db_methods('INSTANCE', 'users');

        $payment = $user->getPayment('check', $id);

        return $payment;
    }

    /* 
        Funções da classe em si
    */

    /**
     * Método responsável por verificar se o usuário vindo do google existe ou não
     * @param array $googleResponse
     * @return bool 
     */
    public function user($googleResponse)
    {
        $user = $this->verifyUser($googleResponse);


        if (gettype($user) == 'array') {

            $payment = $this->verifyPayment($user[1]['id']);

            if ($payment) {
                echo getenv('URL') . "/home";
                exit();
            }
        }else{

            echo getenv('URL') . '/store';
            exit;
        }
    }
}
