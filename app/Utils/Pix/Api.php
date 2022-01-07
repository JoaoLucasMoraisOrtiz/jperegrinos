<?php

namespace App\Utils\Pix;

class Api{

    /**
     * Guarda a URL base do nosso PSP
     * @var string
     */
    private $baseUrl;

    /**
     * Guarda o client ID do oAuth2 do nosso PSP
     * @var string
     */
    private $clientId;

    /**
     * Guarda a chave secreda do cliente oAuth2 do nosso PSP
     * @var string
     */
    private $clientSecret;


    /**
     * Caminho absoluto até o certificado
     * @var string
     */
    private $certificate;

    /**
     * Define os dados iniciais da classe
     * @param string $baseUrl
     * @param string $clientId
     * @param string $clientsecret
     * @param string $certificate
     */
    public function __construct($baseUrl, $clientId, $clientSecret, $certificate){
        
        $this-> baseUrl = $baseUrl;
        $this -> clientId = $clientId;
        $this -> clientSecret = $clientSecret;
        $this -> certificate = $certificate;
    }


}