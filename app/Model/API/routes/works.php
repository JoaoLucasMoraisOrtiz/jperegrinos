<?php
    require_once __DIR__."../../controller/index.php";
    require_once __DIR__."../../preController/index.php";

    $controller = new WorksController;
    $actions = new ActionsWorks;
    
    class RouterWorks {

        public function get($id=0) {
            global $controller, $actions;

            $data =[];
            $controller->setId($id);
            $data["work"] = $actions->read();
            return json_encode($data);
        }

        public function post($name, $type, $year, $description) {
            global $controller, $actions;

            $data =[];
            $controller-> setName($name);
            $controller-> setType($type);
            $controller-> setYear($year);
            $controller-> setDescription($description);

            $data["work"] = $actions-> create();
            return json_encode($data);
        }

        public function patch($id, $name=null, $type=null, $year=null, $description=null) {
            global $controller, $actions;

            $data =[];
            $controller-> setId($id);
            $controller-> setName($name);
            $controller-> setType($type);
            $controller-> setYear($year);
            $controller-> setDescription($description);

            //segurança para ela não deixar o post vazio;
            if($controller->getId() || $controller->getName() || $controller->getType() || $controller->getYear() ||$controller->getDescription() === null){
                echo "Preencha todos os campos!!";
            }

            $data["work"] = $actions-> update();
            return json_encode($data);
        }

        public function delete($id) {
            global $controller, $actions;

            $data = [];
            $controller->setId($id);
            $data["work"] = $actions->delete();
            return json_encode($data);
        }
    }