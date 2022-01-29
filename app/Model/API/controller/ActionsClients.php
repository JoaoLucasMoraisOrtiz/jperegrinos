<?php

require_once __DIR__ . "/../helpers/dbConection.php";

use FFI\Exception;

/* putenv("DB_NAME=jperegrinos");
putenv("DB_HOST=localhost:3306");
putenv("DB_USER=joao");
putenv("DB_PASS=");
putenv("DB_POSTS_TABLE=posts"); */


class ActionsClients
{

    /**
     * Guarda o link da nossa DB
     * @var string
     */
    private $link;

    /**
     * Guarda o nome da nossa DB
     * @var string
     */
    private $dbName;

    /**
     * Guarda o usuário da nossa DB
     * @var string
     */
    private $usr;

    /**
     * Guarda a senha da nossa DB
     * @var string
     */
    private $pass;

    /**
     * Guarda o nome da nossa tabela na DB
     * @var string
     */
    private $tableName;

    /**
     * Id gerado automaticamente
     * @var integer
     */
    private $id;

    /**
     * Nome do post
     * @var string
     */
    private $name;

    /**
     * Tipo de post (matéria, evento, sorteio)
     * @var string
     */
    private $type;

    /**
     * imagem do post
     * @var string
     */
    private $img;

    /**
     * conteúdo do post
     * @var string
     */
    private $description;

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
        $this->tableName = getenv('DB_CLIENT_TABLE');
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

            $comand = "SELECT * FROM client";
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

            //prepara uma string para ser executada posteriormente com o prepare;
            //:_mark - é uma forma de se proteger, para ninguem colocar um drop database e acabar com o banco
            $statement = $con->prepare("SELECT * FROM client WHERE id = :id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT);
            try {
                //tenta executar a string que estava sendo preparada, ou seja, envia para o DB os dados.
                $statement->execute();

                //retorna o index Zero pois se não retornará uma array com nossa array dentro
                return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
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

            //prepara uma string para ser executada posteriormente com o prepare;
            //:_mark - é uma forma de se proteger, para ninguem colocar um drop database e acabar com o banco
            $statement = $con->prepare("INSERT INTO client VALUES(NULL, :value, :name, :event)");

            //substitui o :_mark por um valor, e expecifica o tipo do valor (explicitado por segurança);
            $statement->bindValue(":name", $param['name'], PDO::PARAM_STR);
            $statement->bindValue(":value", $param['value'], PDO::PARAM_STR);
            $statement->bindValue(":event", $param['event'], PDO::PARAM_STR);
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
                exit;
                return $this->get($this->id);
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

            //prepara uma string para ser executada posteriormente com o prepare;
            //:_mark - é uma forma de se proteger, para ninguem colocar um drop database e acabar com o banco
            $statement = $con->prepare("UPDATE client SET name=:name, value=:type, event=:event WHERE id=:id");

            //substitui o :_mark por um valor, e expecifica o tipo do valor (explicitado por segurança);
            $statement->bindValue(":name", $param['name'], PDO::PARAM_STR);
            $statement->bindValue(":value", $param['value'], PDO::PARAM_STR);
            $statement->bindValue(":event", $param['event'], PDO::PARAM_STR);
            $statement->bindValue(":id", $param['id'], PDO::PARAM_INT);
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

        //prepara uma string para ser executada posteriormente com o prepare;
        //:_mark - é uma forma de se proteger, para ninguem colocar um drop database e acabar com o banco
        $statement = $con->prepare("DELETE FROM client WHERE event = :id");

        //substitui o :_mark por um valor, e expecifica o tipo do valor (explicitado por segurança);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);

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
