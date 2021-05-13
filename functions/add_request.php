<?php

/**
 * Скрипт добавления новой заявки
 */

//todo Добавить имена полей новой заявки
if (isset($_POST[''])) {
    $name = $_POST[''];
    $phone = $_POST[''];
    $id_car = $_POST[''];
    $id_defects[] = $_POST[''];
    add_request($name, $phone, $id_car, $id_defects);
    //TODO вызвать функцию "резервирования запчастей"
}