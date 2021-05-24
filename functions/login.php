<?php
require_once("../functions/database_request.php");
session_start();
/**
 * Обработка нажатия кнопки "Авторизация"
 */
if (isset($_POST['log'])) {

    $login = $_POST['login'];
    $password = $_POST['password'];

    $check = checkEmployees($login, $password);
    if ($check) {
        $_SESSION['login'] = $login;
        $_SESSION['password'] = $password;
            $position = get_position_employees($login);
        $_SESSION['position'] = $position;
        $_SESSION['authorized'] = true;
        header("Location: /admin.php");
        exit;
    } else {
        $_SESSION['error'] = 1;
        header("Location: /login_admin.php");
    }
}