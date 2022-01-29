<?php

namespace App\Utils\Pix;

class Payload
{

    /**
     * IDs do Payload do Pix
     * @var string
     */
    const ID_PAYLOAD_FORMAT_INDICATOR = '00';
    const ID_MERCHANT_ACCOUNT_INFORMATION = '26';
    const ID_MERCHANT_ACCOUNT_INFORMATION_GUI = '00';
    const ID_MERCHANT_ACCOUNT_INFORMATION_KEY = '01';
    const ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION = '02';
    const ID_MERCHANT_CATEGORY_CODE = '52';
    const ID_TRANSACTION_CURRENCY = '53';
    const ID_TRANSACTION_AMOUNT = '54';
    const ID_COUNTRY_CODE = '58';
    const ID_MERCHANT_NAME = '59';
    const ID_MERCHANT_CITY = '60';
    const ID_ADDITIONAL_DATA_FIELD_TEMPLATE = '62';
    const ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID = '05';
    const ID_CRC16 = '63';

    /**
     * Chave pix
     * @var string;
     */
    private $pixKey;

    /**
     * Descrição do pagamento
     * @var string;
     */
    private $description;

    /**
     * Nome do titular da conta
     * @var string;
     */
    private $merchantName;

    /**
     * Nome da cidade do titular da conta
     * @var string;
     */
    private $merchantCity;

    /**
     * Id da transação PIX
     * @var string;
     */
    private $txid;

    /**
     * Valor do pagamento
     * @var string;
     */
    private $amount;

    /* 
        Funções para popular nossas classes
    */

    /**
     * Método responsável por definir o valor da chave pix
     * @param string $pixKey
     */
    public function setPixKey($pixKey)
    {
        $this->pixKey = $pixKey;

        return $this;
    }

    /**
     * Método responsável por definir o valor da descrição do pagamento
     * @param string $description
     */
    public function setDescription($description)
    {

        if($description == 0){
            $phrase = array(
                "A alegria evita mil males e prolonga a vida.",
                "A alegria de fazer o bem é a única felicidade verdadeira.",
                "A alegria não está nas coisas, está em nós.",
                "Para quem ama, qualquer sacrifício é alegria.",
                "Toda a alegria vem do amor, e todo o amor inclui o sofrimento.",
                "A alegria é a pedra filosofal que tudo converte em ouro.",
                "A alegria não está nas coisas, mas em nós.",
                "A alegria compartilhada é uma alegria dobrada",
                "O amor não se vê com os olhos mas com o coração.",
                "A medida do amor é amar sem medida.",
                "Não basta fazer coisas boas - é preciso fazê-las bem.",
                "A humildade é o primeiro degrau para a sabedoria",
                "As coisas que amamos nos dizem o que somos.",
                "Quem ama, faz sempre comunidade; não fica nunca sozinho",
                "Humildade é a verdade",
                "São felizes as vidas que se consumirem no serviço da Igreja",
                "Aquilo que o amor faz, o medo jamais poderá realizar.",
                "A amizade cuja fonte é Deus, nunca se esgota.",
                "Não busco compreender para crer, mas creio para compreender.",
                "No crepúsculo da vida, seremos julgados pelo amor.",
                "Sofrer por Deus é melhor que fazer milagres.",
                "Não se desanime se você não consegue fazer tudo como gostaria.",
                "A alma cristã deve fugir dos aplausos dos homens.",
                "Aspiremos a felicidade que nos foi preparada por Deus.",
                "Deus é nosso Pai. O que se pode temer tendo um Pai como este?",
                "Peça a Deus para sempre sentir o perfume de seus ensinamentos.",
                "Trabalhar com amor é orar com as mãos.",
                "Fale pouco de você e menos ainda dos outros.",
                "Trabalha em algo, para que o diabo te encontre sempre ocupado.",
                "A boca fala do que está cheio o coração.",
                "Amai-vos uns aos outros, como eu vos amei."
            );
    
            while (true) {
                // ordenar o array randomicamente
                srand((float)microtime() * 1000000);
                shuffle($phrase);
                /* $this->description = $frases[0]; */
    
                if (mb_strlen($phrase[0], 'UTF-8') > 62) {
                    continue;
                }else{
                    $this->description = $phrase[0];
                    return $this;
                    break;
                }
            }
        }else{
            $this->description = $description;
            return $this;
        }
    }

    /**
     * Método responsável por definir o nome do titular da conta
     * @param string $merchantName
     */
    public function setMerchantName($merchantName)
    {

        $this->merchantName = $merchantName;

        return $this;
    }

    /**
     * Método responsável por definir a cidade do titular da conta
     * @param string $merchantCity
     */
    public function setMerchantCity($merchantCity)
    {

        $this->merchantCity = $merchantCity;

        return $this;
    }

    /**
     * Método responsável por definir o id da transação
     * @param string $txid
     */
    public function setTxid($txid)
    {

        $this->txid = $txid;

        return $this;
    }

    /**
     * Método responsável por definir o valor do pagamento
     * @param float $amount
     */
    public function setAmount($amount)
    {

        $this->amount = (string)number_format($amount, 2, '.', '');

        return $this;
    }

    /* 
        Funções privadas da classe
    */

    /**
     * Método responsável por retornar o valor completo de um objeto payload, 
     * concatenando dois valores dados a um terceiro, que é determinado pelo 
     * comprimento do segundo
     * @param string $id
     * @param string $value
     * @return string $id.$size.value
     */
    private function getValue($id, $value)
    {

        //verifica se o número de $value tem menos de 2 digitos, e se for o caso, adiciona um 0 a sua esquerda
        $size = str_pad(mb_strlen($value, 'UTF-8'), 2, "0", STR_PAD_LEFT);
        return $id . $size . $value;
    }

    /**
     * Método responsável por retornar os dados completos da informação da conta
     * @return string
     */
    private function getMerchantAccountInformation()
    {

        //define o dominio do banco, que define pagamentos pix no Brasil
        $gui = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_GUI, 'br.gov.bcb.pix');

        //define a chave do pix
        $key = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_KEY, $this->pixKey);

        //descrição do pagamento se ouver
        $description = $this->description ? $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION, $this->description) : '';

        //valor completo da conta
        return $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION, $gui . $key . $description);
    }

    /**
     * Método responsável por retornar os valores completos do campo adicional do pix (TXID)
     * @return string
     */

    private function getAdditionalDataFieldTemplate()
    {

        //TXID
        $txid = $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID, $this->txid);

        //retorna o valor completo
        return $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE, $txid);
    }

    /**
     * Método responsável por calcular o valor da hash de validação do código pix
     * @return string
     */
    private function getCRC16($payload)
    {
        //ADICIONA DADOS GERAIS NO PAYLOAD
        $payload .= self::ID_CRC16 . '04';

        //DADOS DEFINIDOS PELO BACEN
        $polinomio = 0x1021;
        $resultado = 0xFFFF;

        //CHECKSUM
        if (($length = strlen($payload)) > 0) {
            for ($offset = 0; $offset < $length; $offset++) {
                $resultado ^= (ord($payload[$offset]) << 8);
                for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                    if (($resultado <<= 1) & 0x10000) $resultado ^= $polinomio;
                    $resultado &= 0xFFFF;
                }
            }
        }

        //RETORNA CÓDIGO CRC16 DE 4 CARACTERES
        return self::ID_CRC16 . '04' . strtoupper(dechex($resultado));
    }

    /* 
        Funções objetivas da classe
    */

    /**
     * Método responsável por gerar o payload completo do pix
     * @return string
     */
    public function getPayload()
    {

        //cria o payload, que é uma requisição pix
        $payload = $this->getValue(self::ID_PAYLOAD_FORMAT_INDICATOR, '01') .
            $this->getMerchantAccountInformation() .
            $this->getValue(self::ID_MERCHANT_CATEGORY_CODE, '0000') .
            $this->getValue(self::ID_TRANSACTION_CURRENCY, '986') .
            $this->getValue(self::ID_TRANSACTION_AMOUNT, $this->amount) .
            $this->getValue(self::ID_COUNTRY_CODE, 'BR') .
            $this->getValue(self::ID_MERCHANT_NAME, $this->merchantName) .
            $this->getValue(self::ID_MERCHANT_CITY, $this->merchantCity) .
            $this->getAdditionalDataFieldTemplate();

        //retorna concatenado com a verificação ciclica de redundância
        return $payload . $this->getCRC16($payload);
    }
}
