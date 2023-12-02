<?php
require_once("controller/routesController.php");
require_once("controller/userController.php");
require_once("controller/loginController.php");
require_once("model/userModel.php");
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Access-Control-Allow-Headers: Authorization');

$rutasArray = explode("/", $_SERVER['REQUEST_URI']);
$endPoint = (array_filter($rutasArray)[2]);

if ($endPoint == 'login') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $ok = false;
            $identifier = $_SERVER['PHP_AUTH_USER'];
            $key = $_SERVER['PHP_AUTH_PW'];
            $users = UserModel::getUseAuth();
            foreach ($users as $u) {
                if ($identifier . ":" . $key == $u["user_identifier"] . ":" . $u["user_key"]) {
                    $ok = true;
                }
            }
            if ($ok) {
                $routes = new RoutesController();
                $routes->index();
            } else {
                $result["mensaje"] = "No autorizado";
                echo json_encode($result, true);
                return false;
            }
        } else {
            $result["mensaje"] = "Credenciales no válidas";
            echo json_encode($result, true);
            return false;
        }
    } else {
        $result["mensaje"] = "Solicitud no válido";
        echo json_encode($result, true);
        return false;
    }
} else {
    $method = $_SERVER['REQUEST_METHOD'];
    $complement = (array_key_exists(3, $rutasArray)) ? ($rutasArray)[3] : 0;
    $add = (array_key_exists(4, $rutasArray)) ? ($rutasArray)[4] : "";
    if ($add != "") {
        $complement .= "/" . $add;
    }

    $routes = new RoutesController();

    switch ($endPoint) {
        case 'users':
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)) {
                $user = new UserController($method, $complement, $_POST);
            } else {
                $user = new UserController($method, $complement, 0);
            }
            $user->index();
            break;

        case 'login':
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)) {
                $user = new LoginController($method, $_POST);
                $user->index();
            } else {
                $result["mensaje"] = "Solicitud no válido";
                echo json_encode($result, true);
                return;
            }
            break;

        default:
            $result["mensaje"] = "No encontrado";
            echo json_encode($result, true);
            return;
    }
}
?>