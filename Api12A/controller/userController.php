<?php
class UserController{
    private $_method;
    private $_complement;
    private $_data;

    function __construct($method, $complement, $data){
        $this->_method = $method;
        $this->_complement = $complement == null ? 0 : $complement;
        $this->_data = $data != 0 ? $data : "";
       // echo($this->_method);
       // echo( $this->_complement."    ");
        //var_dump($this->_data);

    } 

    public function index(){
    switch($this->_method){
        case "GET":
            if($this->_complement == 0){
                $user = UserModel::getUsers(0);
                $json = $user;
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                return;
            }else{
                $user = UserModel::getUsers($this->_complement);
                $json = $user;
                echo json_encode($json, true);
                return;
            }
            case 'POST':
                $createUser = UserModel::createUser($this->_data);
                $json = array(
                    "response" => $createUser
                );
                echo json_encode($json, true);
                return;
            case "UPDATE":
                $json = array(
                    "response" => "Usuario subido"
                );
                echo json_encode($json, true);
                return;
            case "DELETE":
                $json = array(
                    "response" => "Usuario eliminado"
                );
                echo json_encode($json, true);
                return;
            default:
                $json = array(
                    "response" => "No encontrado"
                );
                echo json_encode($json, true);
                return;
        }
    }

    private function generateSalting(){
        $trimmedData = ""; 
        if(($this->_data != "") || (!empty($this->_data))){
            $trimmedData = array_map('trim',$this->_data);
            $trimmedData['user_pss'] = md5($trimmedData['user_pss']);
            $identifier = str_replace("$", "ue3", crypt($trimmedData["user_mail"],'u56'));
            $key = str_replace("$", "ue2023", crypt($trimmedData["user_mail"],'65ue'));
            $trimmedData['user_identifier'] = $identifier;
            $trimmedData['us_key'] = $key;
            return $trimmedData;
        }
    }
    public function update($id, $data){
        if (!is_numeric($id) || $id <= 0 || empty($data) || !$this->validateData($data)) {
            $json = array(
                "response" => "Datos no validos"
            );
            echo json_encode($json, true);
            return;
        }

        $json = array(
            "response" => "Usuario actualizado"
        );
        echo json_encode($json, true);
    }
    public function deactivate($id){
        if (!is_numeric($id) || $id <= 0) {
            $json = array(
                "response" => "Datos no validos"
            );
            echo json_encode($json, true);
            return;
        }

        $json = array(
            "response" => "Usuario desactivado"
        );
        echo json_encode($json, true);
    }
    public function activate($id){
        if (!is_numeric($id) || $id <= 0) {
            $json = array(
                "response" => "Datos no validos"
            );
            echo json_encode($json, true);
            return;
        }

        $json = array(
            "response" => "Usuario activado"
        );
        echo json_encode($json, true);
    }

    private function validateData($data){
        if (!is_array($data) || empty($data) || !isset($data['user_mail'])) {
            return false;
        }

        if (!filter_var($data['user_mail'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
}
?>