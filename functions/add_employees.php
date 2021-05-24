<?php

/**
 * Скрипт добавления нового работника
 */

if (isset($_POST[''])) {
    $name = $_POST[''];
    $position = $_POST[''];
    $login = $_POST[''];
    $password = $_POST[''];
    registration($name, $position, $login, $password);
}