<?php

require_once __DIR__ . "/../../../vendor/autoload.php";

use \App\Utils\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

//instancia a classe Payload
$obPayload = (new Payload) -> setPixKey('44933331898')
                           -> setDescription('jperegrinos')
                           -> setMerchantName('Marlon Ricardo')
                           -> setMerchantCity('MOGI GUACU')
                           -> setAmount(0.01)
                           -> setTxid('jperegrinos123');


//cria nossa requisição pix
$payloadQrCode = $obPayload->getPayload();

//instancia e cria o qrcode;
$obQrCode = new Qrcode($payloadQrCode);

//cria a imágem do nosso QrCode;
$qrCodeImage = (new Output\Png)->output($obQrCode, 400);

header('Content-type: image/png');

echo $qrCodeImage;