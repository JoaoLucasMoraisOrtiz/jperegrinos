<?php

namespace App\Utils\Pix;

require_once __DIR__ . "/../../../vendor/autoload.php";

use \App\Utils\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

class GenerateQrCode
{

    /**
     * Método responsável por criar o QrCode do Pix
     * @param string $pixKey
     * @param string $merchantName
     * @param string $merchantCity
     * @param string $amount
     * @param string $txid
     * @return base64_encode image
     */
    public static function createQrCode($pixKey, $merchantName, $merchantCity, $amount, $txid, $description=0)
    {

        //instancia a classe Payload
        $obPayload = (new Payload)->setPixKey($pixKey)
            ->setDescription($description)
            ->setMerchantName($merchantName)
            ->setMerchantCity($merchantCity)
            ->setAmount($amount)
            ->setTxid($txid);

        //cria nossa requisição pix
        $payloadQrCode = $obPayload->getPayload();

        //instancia e cria o qrcode;
        $obQrCode = new Qrcode($payloadQrCode);

        $output = new Output\Png;

        //cria a imágem do nosso QrCode;
        $qrCodeImage = $output->output($obQrCode, 400);

        $img = base64_encode($qrCodeImage);

        return [$img, $payloadQrCode];
    }
}
