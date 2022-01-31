<?php

require_once __DIR__ . "/../helpers/dbConection.php";

use FFI\Exception;

class ActionsUsers
{

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


    /* 
        funções privadas das classes
    */

    /**
     * Método responsável por setar o host da nossa DB
     */
    private function getHost()
    {
        $this->link = getenv('DB_HOST');
    }

    /**
     * Método responsável por setar o host da nossa DB
     */
    private function getName()
    {
        $this->dbName = getenv('DB_NAME');
    }

    /**
     * Método responsável por setar o host da nossa DB
     */
    private function getUser()
    {
        $this->usr = getenv('DB_USER');
    }

    /**
     * Método responsável por setar o host da nossa DB
     */
    private function getPass()
    {
        $this->pass = getenv('DB_PASS');
    }

    /**
     * Método responsável por setar o host da nossa DB
     */
    private function getTableName()
    {
        $this->tableName = getenv('DB_POSTS_TABLE');
    }

    /**
     * Método responsável por retornar nosso $id encriptado em hash('sha256') caso $encripted==TRUE
     * já se $encripted==FALSE retorna apenas o $id
     * @param int $id
     * @param bool $encripted
     */
    private function getId($id, $encripted = TRUE)
    {
        if ($encripted) {
            return hash("sha256", $id);
        } else {
            return $id;
        }
    }

    /**
     * Método responsável por determinar se o usuário pagou este mes ou não
     * @param string $case:
     *                      'none' => retorna o payment do usuário determinado pelo $id
     *                      'post' => retorna 'TRUE'
     *                      'chek' => verifica com base na data se o usuário pagou este mes
     * @return string ('TRUE' || 'FALSE')
     */
    public function getPayment($case = 'none', $id = '')
    {

        //se estivermos cadastrando o usuário, ele já pagou
        if ($case == 'post') {
            $this->date = strval(date('d/m/Y'));
            return 'TRUE';
        }

        if ($case == 'check') {

            $obUser = $this->get($id);

            $lastPayment = str_replace('/', '-', $obUser['date']);
            $this->date = $obUser['date'];

            //se o usuário pagou a mais de um mês retorna verdadeiro, se não, retorna falso
            if (strtotime($lastPayment) > strtotime('-1 month')) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        if ($case == 'none') {
            $obUser = $this->get($id);
            $payment = $obUser['payment'];
            $this->date = $obUser['date'];
            return $payment;
        }
    }
    /* 
        funções da classe em si
    */

    /**
     * Método responsável por trazer o id dos posts de uma table no banco de dados
     * @param int $id
     * @param string $tableName
     * @return array
     */
    public function get($id = 0)
    {
        //conexão com o BD;
        try {
            $con = '';
            $this->getHost();
            $this->getName();
            $this->getUser();
            $this->getPass();
            $this->getTableName();

            //tenta se conectar com o banco de dados
            $con = connection($this->link, $this->dbName, $this->usr, $this->pass);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {

            //em caso de erro exibe a window.allert()
            echo `window.allert('Erro ao conectar com o banco de dados! <br> {$e->getMessage()}')`;
        }

        if ($id === 0) {

            $comand = "SELECT * FROM users";
            //prepara uma string para ser executada posteriormente com o prepare;
            //:_mark - é uma forma de se proteger, para ninguem colocar um drop database e acabar com o banco
            $statement = $con->prepare($comand);

            try {


                //tenta executar a string que estava sendo preparada, ou seja, envia para o DB os dados.
                $statement->execute();

                return $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                //em caso de erro 
                echo `window.allert('Erro ao conectar com o banco de dados! <br> {$e->getMessage()}')`;
            }
        } else if ($id != 0) {

            $encriptedId = $this->getId($id);

            //prepara uma string para ser executada posteriormente com o prepare;
            //:_mark - é uma forma de se proteger, para ninguem colocar um drop database e acabar com o banco
            $statement = $con->prepare("SELECT * FROM users WHERE id = :id");
            $statement->bindValue(":id", $encriptedId, PDO::PARAM_STR);
            try {
                //tenta executar a string que estava sendo preparada, ou seja, envia para o DB os dados.
                $statement->execute();

                //retorna o index Zero pois se não retornará uma array com nossa array dentro
                return $statement->fetchAll(PDO::FETCH_ASSOC)[0] ?? 0;
            } catch (Exception $e) {
                //em caso de erro 
                echo `window.allert('Erro ao conectar com o banco de dados! <br> {$e->getMessage()}')`;
            }
        }
        return [];
    }


    /**
     * Método responsável por posts na DB
     * @param array
     */
    public function post($param): array
    {
        try {
            $con = '';
            $this->getHost();
            $this->getName();
            $this->getUser();
            $this->getPass();
            $this->getTableName();

            //tenta se conectar com o banco de dados
            $con = connection($this->link, $this->dbName, $this->usr, $this->pass);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {

            //em caso de erro exibe a window.allert()
            echo `window.allert('Erro ao conectar com o banco de dados! <br> {$e->getMessage()}')`;
        }

        try {

            $this->id =  $this->getId($param['id']);
            $this->payment = $this->getPayment($param['payment']);

            //prepara uma string para ser executada posteriormente com o prepare;
            //:_mark - é uma forma de se proteger, para ninguem colocar um drop database e acabar com o banco
            $statement = $con->prepare("INSERT INTO users VALUES(:id, :name, :email, :amounth, :payment, :date)");

            //substitui o :_mark por um valor, e expecifica o tipo do valor (explicitado por segurança);
            $statement->bindParam(":id", $this->id, PDO::PARAM_STR);
            $statement->bindValue(":name", $param['name'], PDO::PARAM_STR);
            $statement->bindValue(":email", $param['email'], PDO::PARAM_STR);
            $statement->bindValue(":amounth", $param['amounth'], PDO::PARAM_STR);
            $statement->bindValue(":payment", $this->payment, PDO::PARAM_STR);
            $statement->bindValue(":date", $this->date, PDO::PARAM_STR);
        } catch (Exception $e) {
            echo "ERROR " . $e;
            exit();
        }

        try {

            //tenta executar a string que estava sendo preparada, ou seja, envia para o DB os dados.
            if ($statement->execute()) {

                //pega o ID do usuário que foi enviado para o DB;
                $this->id = $con->lastInsertId();
                //exibe o usuário inserido na DB;
                return $this->get($param['id']);
            }
        } catch (Exception $e) {
            //em caso de erro 
            echo `window.allert('Erro ao conectar com o banco de dados! <br> {$e->getMessage()}')`;
        }
        return [];
    }

    /**
     * Método responsável por atualizar um post na DB
     * @param array
     */
    public function update($param)
    {
        try {

            $con = '';
            $this->getHost();
            $this->getName();
            $this->getUser();
            $this->getPass();
            $this->getTableName();

            //tenta se conectar com o banco de dados
            $con = connection($this->link, $this->dbName, $this->usr, $this->pass);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {

            //em caso de erro exibe a window.allert()
            echo `window.allert('Erro ao conectar com o banco de dados! <br> {$e->getMessage()}')`;
        }


        try {

            $this->id =  $this->getId($param['id']);
            $this->payment = $this->getPayment($param['payment'], $param['id']);

            //prepara uma string para ser executada posteriormente com o prepare;
            //:_mark - é uma forma de se proteger, para ninguem colocar um drop database e acabar com o banco
            $statement = $con->prepare("UPDATE users SET id=:id, name=:name, email=:email, amounth=:amounth, payment=:payment, date=:date WHERE id=:id");

            //substitui o :_mark por um valor, e expecifica o tipo do valor (explicitado por segurança);
            $statement->bindParam(":id", $this->id, PDO::PARAM_STR);
            $statement->bindValue(":name", $param['name'], PDO::PARAM_STR);
            $statement->bindValue(":email", $param['email'], PDO::PARAM_STR);
            $statement->bindValue(":amounth", $param['amounth'], PDO::PARAM_STR);
            $statement->bindValue(":payment", $this->payment, PDO::PARAM_STR);
            $statement->bindValue(":date", $this->date, PDO::PARAM_STR);
        } catch (Exception $e) {
            echo "ERROR " . $e;
            exit();
        }

        try {


            //tenta executar a string que estava sendo preparada, ou seja, envia para o DB os dados.
            $statement->execute();

            return $this->get($param['id']);
        } catch (Exception $e) {
            //em caso de erro 
            echo `window.allert('Erro ao conectar com o banco de dados! <br> {$e->getMessage()}')`;
        }
        return [];
    }

    /**
     * Método responsável por deletar um post do banco de dados
     * @param int
     */
    public function delete($id)
    {

        try {

            $con = '';
            $this->getHost();
            $this->getName();
            $this->getUser();
            $this->getPass();
            $this->getTableName();

            //tenta se conectar com o banco de dados
            $con = connection($this->link, $this->dbName, $this->usr, $this->pass);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            //em caso de erro exibe a window.allert()
            echo `window.allert('Erro ao conectar com o banco de dados! <br> {$e->getMessage()}')`;
        }

        try {
            //prepara uma string para ser executada posteriormente com o prepare;
            //:_mark - é uma forma de se proteger, para ninguem colocar um drop database e acabar com o banco
            $statement = $con->prepare("DELETE FROM users WHERE id=:id");

            //substitui o :_mark por um valor, e expecifica o tipo do valor (explicitado por segurança);
            $statement->bindValue(":id", $id, PDO::PARAM_STR);
        } catch (Exception $e) {
            echo `window.allert('Erro ao conectar com o banco de dados! <br> {$e->getMessage()}')`;
        }

        try {
            //tenta executar a string que estava sendo preparada, ou seja, envia para o DB os dados.
            $statement->execute();
        } catch (Exception $e) {
            //em caso de erro 
            echo `window.allert('Erro ao conectar com o banco de dados! <br> {$e->getMessage()}')`;
        }
        return [];
    }
}
