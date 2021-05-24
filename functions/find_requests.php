<?php
session_start();
if (isset($_POST['phone'])){
    $_SESSION['phone'] = $_POST['phone'];
    header("Location: /order.php");
}
