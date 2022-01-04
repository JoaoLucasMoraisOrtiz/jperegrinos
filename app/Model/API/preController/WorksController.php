<?php
    class WorksController {
        
        private $id; //id gerado automaticamente
        private $name; //nome dado pelo usuário
        private $type; //tipo do trabalho (port, mat, hist, geo...)
        private $year; //I ANO, II ANO, III ANO...
        private $description; //descrição do trabalho (a pergunta em si)
        
        //cria um id
        public function setId(int $id) :void {
            $this->id = $id;
        }
        //retorna o id
        public function getId() :int {
            return $this->id;
        }

        //cria um nome
        public function setName(string $name) :void {
            $this->name = $name;
        }
        //retorna o nome
        public function getName() :string {
            return $this->name;
        }

        //cria um tipo
        public function setType(string $type) :void {
            $this->type = $type;
        }
        //retorna o tipo
        public function getType() :string {
            return $this->type;
        }

        //cria um ano
        public function setYear(string $year) :void {
            $this->year = $year;
        }
        //retorna o ano
        public function getYear() :string {
            return $this->year;
        }

        //cria uma descrição
        public function setDescription(string $description) :void {
            $this->description = $description;
        }
        //retorna a descrição
        public function getDescription() :string {
            return $this->description;
        }


    }