<?php
require_once("../functions/database_request.php");
session_start();
/**
 * Скрипт добавления новой заявки
 */

if (isset($_POST['send_request'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $id_car = $_POST['id_car'];
    $id_defects = $_POST['id_defects'];
    $id_request = add_request($name, $phone, $id_car, $id_defects);
//    var_dump($id_request);
    $ret = reservation_spare_part($id_request);
//    var_dump($ret);
    if ($ret == false) {
        rollback_reservation_spare_part($id_request);
        change_active($id_request, 0);
    }
    header("Location: /login.php");

}