<?php

/**
 * Обработка нажатия кнопки "Авторизация"
 */
//TODO Исправить названия полей для авторизации работника
if (isset($_POST['log'])) {

    $login = $_POST['login'];
    $password = $_POST['password'];

    $check = checkEmployees($login, $password);
    if ($check) {
        $_SESSION['login'] = $login;
        $_SESSION['password'] = $password;
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error'] = 1;
    }
}