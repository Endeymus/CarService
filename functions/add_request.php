<?php

/**
 * Скрипт добавления новой заявки
 */

//todo Добавить имена полей
if (isset($_POST[''])) {
    $name = $_POST[''];
    $phone = $_POST[''];
    $id_car = $_POST[''];
    $id_defect = $_POST[''];
    add_request($name, $phone, $id_car, $id_defect);
}