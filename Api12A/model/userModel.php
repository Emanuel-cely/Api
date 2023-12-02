<?php
require_once("ConDB.php");

class UserModel{
    static public function createUser($data){
        // Validar datos antes de continuar
        if (!self::validateData($data)) {
            $json = array(
                "response" => "Datos no válidos"
            );
            echo json_encode($json, true);
            return;
        }

        $cantMail = self::getMail($data['user_mail']);
        if ($cantMail == 0) {
            $query = "INSERT INTO users(user_id, user_mail, user_pss, user_dateCreate,
            user_identifier, user_key,  user_status) VALUES (NULL, :user_mail, :user_pss, :user_dateCreate,
            :user_identifier, :us_key, :user_status)";
            $status = 0; // 0 inactivo, 1 activo

            $stament = Connection::connecction()->prepare($query);
            $stament->bindParam(":user_mail", $data['user_mail'], PDO::PARAM_STR);
            $stament->bindParam(":user_pss", $data['user_pss'], PDO::PARAM_STR);
            $stament->bindParam(":user_dateCreate", $data['user_dateCreate'], PDO::PARAM_STR);
            $stament->bindParam(":user_identifier", $data['user_identifier'], PDO::PARAM_STR);
            $stament->bindParam(":us_key", $data['us_key'], PDO::PARAM_STR);
            $stament->bindParam(":user_status", $status, PDO::PARAM_INT);

            $message = $stament->execute() ? "OK" : Connection::connecction()->errorInfo();

            $stament->closeCursor();
            $stament = null;
            $query = "";
        } else {
            $message = "Usuario registrado";
        }
        return $message;
    }

    static public function updateUser($id, $data){
        if (!is_numeric($id) || $id <= 0 || empty($data) || !self::validateData($data)) {
            $json = array(
                "response" => "Datos no válidos"
            );
            echo json_encode($json, true);
            return;
        }

        $json = array(
            "response" => "Usuario subido"
        );
        echo json_encode($json, true);
    }

    static public function deactivateUser($id){
        if (!is_numeric($id) || $id <= 0) {
            $json = array(
                "response" => "Datos no válidos"
            );
            echo json_encode($json, true);
            return;
        }

        $json = array(
            "response" => "Usuario desactivado"
        );
        echo json_encode($json, true);
    }

    static public function activateUser($id){
        if (!is_numeric($id) || $id <= 0) {
            $json = array(
                "response" => "Datos no válidos"
            );
            echo json_encode($json, true);
            return;
        }
        $json = array(
            "response" => "Usuario activado"
        );
        echo json_encode($json, true);
    }

    static private function getMail($mail){
        $query = "SELECT user_mail FROM users WHERE user_mail = :mail";
        $stament = Connection::connecction()->prepare($query);
        $stament->bindParam(":mail", $mail, PDO::PARAM_STR);
        $stament->execute();
        $result = $stament->rowCount();
        return $result;
    }

    static function getUsers($id)
    {
        $query = "SELECT user_id, user_mail, user_dateCreate FROM users";
        $query .= ($id > 0) ? " WHERE users.user_id = :id AND user_status = 1" : " WHERE user_status = 1";
    
        $stament = Connection::connecction()->prepare($query);
    
        if ($id > 0) {
            $stament->bindParam(":id", $id, PDO::PARAM_INT);
        }
    
        $stament->execute();
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //Login
    static public function login($data){
        if (!isset($data['user_mail']) || !isset($data['user_pss'])) {
            $json = array(
                "response" => "Datos no válidos"
            );
            echo json_encode($json, true);
            return;
        }

        $user = $data['user_mail'];
        $pss = md5($data['user_pss']);

        if (!empty($user) && !empty($pss)) {
            $query = "SELECT user_id, user_identifier, us_key FROM users WHERE user_mail = :user AND
            user_pss = :pss AND user_status = 1";

            $stament = Connection::Connecction()->prepare($query);
            $stament->bindParam(":user", $user, PDO::PARAM_STR);
            $stament->bindParam(":pss", $pss, PDO::PARAM_STR);

            $stament->execute();
            $result = $stament->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            $mensaje = array(
                "COD" => "001",
                "MENSAJE" => ("ERROR EN CREDENCIALES")
            );
            return $mensaje;
        }
    }
    static public function getUseAuth(){
        $query = "SELECT user_identifier, us_key FROM users WHERE us_status = 1";
        $stament = Connection::Connecction()->prepare($query);
        $stament->execute();
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    private static function validateData($data){
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