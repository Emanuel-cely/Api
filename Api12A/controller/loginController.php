<?php 
class LoginController{
    private $_method;
    private $_data;

    function __construct($_method, $_data){
        $this-> _method = $_method;
        $this-> _data = $_data;
    }
    public function index(){
        switch($this->_method){
            case 'POST':
                if (isset($this->_data['user_mail']) && isset($this->_data['user_pss'])) {
                    $response = ["response" => "Inicio de sesión correcto", "Estado" => "OK"];
                } else {
                    $response = ["response" => "Datos no válidos", "Estado" => "FAIL"];
                }
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                return;
            default: 
            $json = array(
                "ruta: " => "No encontrado"
            );
            echo json_encode($json, true);
            return;
        }
    }
}
?>