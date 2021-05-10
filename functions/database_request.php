<?php

/**
 * Подключение к базе данных Автосервиса
 * @return mysqli
 */
function connect(): mysqli
{
    return new mysqli("localhost", "root", "root", "car_service");
}

/**
 * Закрытие активного подключения к БД
 * @param $link - активное подключение
 */
function close($link)
{
    $link->close();
}

/**
 * Получение набора всех марок и моделей автомобилей, которые ремонтирует определенный работник
 * @param $id_employee - идентификатор работника
 * @return mysqli_result|bool
 */
function sql_find_all_car_by_employee($id_employee): mysqli_result|bool
{
    $link = connect();
    $sql = "SELECT r.id, c.brand, c.model FROM request r 
  JOIN cars c ON r.id_car = c.id
  WHERE r.id_employees='$id_employee'";
    $result = mysqli_query($link, $sql);
    close($link);
    return $result;
}

/**
 * Получение идентификатора менее занятого работника
 * @return mysqli_result|bool
 */
function sql_find_free_employee(): mysqli_result|bool
{
    $link = connect();
    $sql = "SELECT G.id_employees FROM (
  SELECT r.id_employees, COUNT(r.id_employees) AS c1 FROM request r GROUP BY r.id_employees
  ) AS G
  WHERE c1 IN (
    SELECT MIN(count) AS N FROM (
  SELECT COUNT(request.id_employees) AS count FROM request GROUP BY request.id_employees
  ) AS T) 
  LIMIT 1 ";
    $result = $link->query($sql)->fetch_assoc()['id_employees'];
    close($link);
    return $result;
}

/**
 * Получение идентификатора пользователя по номеру телефона
 * @param $phone - номер телефона пользователя
 * @return mixed
 */
function sql_find_user_by_phone($phone) {
    $link = connect();
    $sql = "SELECT id FROM users WHERE phone = '$phone'";
    $id_user = $link->query($sql)->fetch_assoc()['id'];
    close($link);
    return $id_user;
}

/**
 * Добавление нового пользователя
 * @param $name - имя пользователя
 * @param $phone - номер телефона
 */
function sql_add_user($name, $phone) {
    $link = connect();
    $sql = "insert into users(`name`, phone) values ('$name', '$phone')";
    $result = mysqli_query($link, $sql);
}

/**
 * Добавление нового заявки
 * @param $username - имя пользователя
 * @param $phone - номер телефона
 * @param $id_car - идентификатор машины
 * @param $id_defects - идентификатор поломки
 */
function add_request($username, $phone, $id_car, $id_defects)
{
    $link = connect();
    $id_user = sql_find_user_by_phone($phone);
    if ($id_user == null) {
        sql_add_user($username, $phone);
    }
    $cost = calc_cost($id_defects);
    $id_employees = sql_find_free_employee();
    $sql = "INSERT INTO request (id_user, creation_date, id_car, id_employees, cost)
  VALUES ('$id_user', CURDATE(), '$id_car', '$id_employees', '$cost');";
    mysqli_query($link, $sql);
    close($link);
}

/**
 * Получение цены поломки по его идентификатору
 * @param $id_defects - идентификатор поломнки
 * @return mixed
 */
function calc_cost($id_defects): mixed
{
    $cost = get_defects_by_id($id_defects);
    return $cost['cost'];
}

/**
 * Получение поломки по идентификатору
 * @param $id - идентификатор поломки
 * @return array|null
 */
function get_defects_by_id($id): ?array
{
    $link = connect();
    $sql = "select * from defects where id = '$id'";
    $ret = $link->query($sql)->fetch_assoc();
    close($link);
    return $ret;
}

/**
 * Регистрация нового работника
 * @param $name - имя работника
 * @param $position - должность
 * @param $login - логин
 * @param $password - пароль
 */
function registration($name, $position, $login, $password) {
    $enc_password = crypt($password);
    $sql = "insert into employees(`name`, `position`, login, password)
                        values('$name', '$position', '$login', '$enc_password')";
    $link = connect();
    mysqli_query($link, $sql);
    close($link);
}

/**
 * Проверка введенного работником логина и пароля, хранящегося в БД в шифровонном виде
 * @param $login - логин работника
 * @param $password - пароль работника
 * @return bool
 */
function checkEmployees($login, $password): bool
{
    if (($login == "") || ($password == "")) return false;
    $link = connectUsersDB();
    $result = $link->query("SELECT password FROM employees WHERE `login`='$login'");
    $user = $result->fetch_assoc();
    $enc_password = $user['password'];
    close($link);
    return password_verify($password, $enc_password);
}

/**
 * Получение идентификатора работника по его логину и паролю
 * @param $login - логин работника
 * @param $password - пароль работника
 * @return mixed|null - идентификатор работника или null
 */
function get_employees_id($login, $password): mixed
{
    if (checkEmployees($login, $password)){
        $mysqli = connect();
        $res = $mysqli->query("SELECT id FROM employees WHERE `login`='$login'");
        $temp = $res->fetch_assoc();
        close($mysqli);
        return $temp['id'];
    }
    return null;
}

//TODO Получение ФИО и телефона по номеру заявки
function get_user_info($id_request): ?array
{
    $link = connect();
    $sql = ""; //здесь пиши запрос
    $result = $link->query($sql);
    close($link);
    return $result->fetch_assoc();
}

//TODO получение перечня поломок по номеру заявки
function get_all_defects_by_id($id_request): mysqli_result|bool
{
    $link = connect();
    $sql = "";//здесь пиши запрос
    $result = mysqli_query($link, $sql);
    close($link);
    return $result;
}



