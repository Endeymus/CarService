<?php
require_once("../functions/database_request.php");
session_start();
/**
 * Обработка нажатия кнопки "Авторизация"
 */
if (isset($_POST['reg'])) {

    $login = $_POST['login'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $position = $_POST['position'];

    $check = exist_employees($login);
    if (!$check) {
        registration($name, $position, $login, $password);
        $_SESSION['success'] = 1;
    } else {
        $_SESSION['error'] = 1;
    }
    header("Location: /redist.php");
    exit;
}