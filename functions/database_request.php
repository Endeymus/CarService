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
    $sql = "SELECT r.id, c.brand, c.model, r.is_active, r.appointment_date, r.request_completed, r.repair_completed FROM request r 
  JOIN cars c ON r.id_car = c.id
  WHERE r.id_employees='$id_employee' 
  order by r.creation_date";
    $result = mysqli_query($link, $sql);
    close($link);
    return $result;
}

function sql_find_all_car_by_admin(): mysqli_result|bool
{
    $link = connect();
    $sql = "SELECT r.id, c.brand, c.model, e.name, r.is_active, r.appointment_date, r.request_completed, r.repair_completed FROM request r 
  JOIN cars c ON r.id_car = c.id
  join employees e ON r.id_employees = e.id
  order by r.creation_date";
    $result = mysqli_query($link, $sql);
    close($link);
    return $result;
}

/**
 * Получение идентификатора менее занятого работника
 */
function sql_find_free_employee()
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
    $result = mysqli_query($link, $sql);
    $tmp = mysqli_fetch_assoc($result);
//    var_dump($result);
//    var_dump($result['id_employees']);
    close($link);
    return $tmp['id_employees'];
}

/**
 * Получение идентификатора пользователя по номеру телефона
 * @param $phone - номер телефона пользователя
 */
function sql_find_user_by_phone($phone)
{
    $link = connect();
    $sql = "SELECT id, name FROM users WHERE phone = '$phone'";
    $id_user = mysqli_query($link, $sql);
    close($link);
    return $id_user;
}

/**
 * Добавление нового пользователя
 * @param $name - имя пользователя
 * @param $phone - номер телефона
 */
function sql_add_user($name, $phone)
{
    $link = connect();
    $sql = "insert into users(`name`, phone) values ('$name', '$phone')";
    $result = mysqli_query($link, $sql);
}

/**
 * Добавление нового заявки
 * @param $username - имя пользователя
 * @param $phone - номер телефона
 * @param $id_car - идентификатор машины
 * @param $id_defects - массив идентификаторов поломки
 */
function add_request($username, $phone, $id_car, $id_defects): int|string
{
    $link = connect();
    //Проверка пользователя наличия
    $user = sql_find_user_by_phone($phone);
    if ($user === FALSE || $user->num_rows == 0) {
        sql_add_user($username, $phone);
    }
    $user = sql_find_user_by_phone($phone);
    $id_user = $user->fetch_assoc()['id'];
    //подсчет итоговой стоимости
    $cost = calc_cost($id_defects);
//        var_dump($cost);
    //поиск менее занятого работника
    $id_employees = sql_find_free_employee();
//        var_dump($id_employees);
    $sql = "INSERT INTO `request` (`id_user`, `creation_date`, `id_car`, `id_employees`, `cost`)
  VALUES ('$id_user', CURRENT_DATE, '$id_car', '$id_employees', '$cost');";
    //создание новой заявки
    $ret = mysqli_query($link, $sql);
//        var_dump($ret);
    $id_request = mysqli_insert_id($link);
//        var_dump($id_request);
    //сохранение поломок за конкретной заявкой
    save_defects($id_request, $id_defects);
    close($link);
    return $id_request;
}

/**
 * @param $id_request - идентификатор заявки
 * @param $id_defects - массив идентификаторов поломки
 */
function save_defects($id_request, $id_defects)
{
    $link = connect();
    for ($i = 0; $i < count($id_defects); $i++) {
        $sql = "insert into request_defects_fk(`id_request`, `id_defects`) values ('$id_request', '$id_defects[$i]')";
        mysqli_query($link, $sql);
    }
    close($link);
}

/**
 * Получение цены поломки по его идентификатору
 * @param $id_defects - идентификатор поломнки
 * @return mixed
 */
function calc_cost($id_defects): mixed
{
    $full_cost = 0;
    for ($i = 0; $i < count($id_defects); $i++) {
        $cost = get_defects_by_id($id_defects[$i])['cost'];
        $full_cost += $cost;
    }
    return $full_cost;
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
function registration($name, $position, $login, $password)
{
    $enc_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "insert into employees(`name`, `position`, login, password)
                        values('$name', '$position', '$login', '$enc_password')";
    $link = connect();
    mysqli_query($link, $sql);
    close($link);
}

function exist_employees($login): bool
{
    if ($login == "") return false;
    $link = connect();
    $result = $link->query("SELECT * FROM employees WHERE `login`='$login'");
    close($link);
    return $result == false;

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
    $link = connect();
    $result = $link->query("SELECT password FROM employees WHERE `login`='$login'");
    $user = $result->fetch_assoc();
    $enc_password = $user['password'];
    close($link);
    return password_verify($password, $enc_password);
//    return $enc_password == $password;
}

/**
 * Получение идентификатора работника по его логину и паролю
 * @param $login - логин работника
 * @param $password - пароль работника
 * @return array|null - идентификатор работника или null
 */
function get_employees($login, $password): ?array
{
    if (checkEmployees($login, $password)) {
        $mysqli = connect();
        $res = $mysqli->query("SELECT id, `name` FROM employees WHERE `login`='$login'");
        $temp = $res->fetch_assoc();
        close($mysqli);
        return $temp;
    }
    return null;
}

function get_position_employees($login)
{
    $link = connect();
    $res = $link->query("SELECT `position` from employees where login = '$login'");
    close($link);
    return $res->fetch_assoc()['position'];
}

/**
 * Получение ФИО, номер телефона, марки и модели автомобиля, дата создания заявки
 * @param $id_request - идентификатор заявки
 * @return array
 */
function get_user_info($id_request): array
{
    $link = connect();
    $sql = "SELECT u.name, u.phone, c.brand, c.model, r.creation_date FROM users u
  JOIN request r ON u.id = r.id_user
  JOIN cars c ON r.id_car = c.id
  WHERE r.id='$id_request'";
    $result = $link->query($sql);
    close($link);
    return $result->fetch_assoc();
}

/**
 * Получение перечня id поломок по номеру заявки
 * @param $id_request - идентификатор заявки
 * @return mysqli_result|bool
 */
function get_all_id_defects_by_id($id_request): mysqli_result|bool
{
    $link = connect();
    $sql = "SELECT d.id FROM defects d 
  JOIN request_defects_fk rdf ON d.id = rdf.id_defects 
  JOIN request r ON rdf.id_request = r.id
  WHERE r.id='$id_request'";
    $result = mysqli_query($link, $sql);
    close($link);
    return $result;
}

/**
 * Получение перечня поломок по номеру заявки
 * @param $id_request - идентификатор заявки
 */
function get_all_defects_by_id($id_request)
{
    $link = connect();
    $sql = "SELECT d.id, rdf.repair_completed, d.name  FROM defects d 
  JOIN request_defects_fk rdf ON d.id = rdf.id_defects 
  JOIN request r ON rdf.id_request = r.id
  WHERE r.id='$id_request'";
    $result = mysqli_query($link, $sql);
    close($link);
    return $result;
}

function get_all_requests_by_id_user($id_user): mysqli_result|bool
{
    $link = connect();
    $sql = "select r.appointment_date, r.request_completed, r.repair_completed, r.is_active, r.creation_date, e.name from request r join employees e on e.id = r.id_employees where id_user = '$id_user'";
    $res = mysqli_query($link, $sql);
    close($link);
    return $res;
}

function get_all_requests_by_phone($phone): mysqli_result|bool
{
    $user = get_user_by_phone($phone);
    return get_all_requests_by_id_user($user->fetch_assoc()['id']);
}

function get_user_by_phone($phone): mysqli_result|bool
{
    $link = connect();
    $user = $link->query("select * from users where phone = '$phone'") or die($link->error);
    close($link);
    return $user;
}

/**
 * Получение всех запчастей конкретной поломки
 * @param $id_defects - идентификатор поломки
 */
function get_all_spare_part_by_id_defects($id_defects)
{
    $link = connect();
    $sql = "SELECT s.id FROM spare_part s 
  JOIN defects_spare_part_fk dspf ON s.id = dspf.id_spare_part 
  WHERE dspf.id_defects = '$id_defects'";
    $result = mysqli_query($link, $sql);
    close($link);
    return $result;
}

/**
 * Функция начала работы над заявкой
 * @param $id_request - идентификатор заявки
 */
function set_appointment($id_request)
{
    $link = connect();
    $sql = "update request set `appointment_date` = CURRENT_DATE where id = '$id_request'";
    mysqli_query($link, $sql);

    close($link);
}

/**
 * Функция установки флага завершения починки
 * @param $id_request - идентификатор заявки
 */
function set_repair_completed_request($id_request)
{
    $link = connect();
    $sql = "update request set `repair_completed` = 1 where id = '$id_request'";

    mysqli_query($link, $sql);

    close($link);
}

function set_repair_completed_defects($id_request, $id_defects)
{
    $link = connect();
    $sql = "update request_defects_fk set `repair_completed` = 1 where id_request = '$id_request' and id_defects = '$id_defects'";

    mysqli_query($link, $sql);

    close($link);
}

/**
 * Функция установки флага завершения починки
 * @param $id_request - идентификатор заявки
 */
function set_request_completed($id_request)
{
    $link = connect();
    $sql = "update request set `request_completed` = 1 where id = '$id_request'";
    mysqli_query($link, $sql);

    close($link);
}

/**
 * Уменьшение количества запчастей на складе на 1
 * @param $id_spare_part - идентификатор запчасти
 * @return bool - {@code true} если количество запчастей больше 0, в противном случае вернет {@code false}
 */
function reduce_count_of_spare_part($id_spare_part): bool
{
    return change_count_of_spare_part($id_spare_part, -1);
}

/**
 * Увеличение количества запчастей на складе на 1
 * @param $id_spare_part - идентификатор запчасти
 */
function increase_count_of_spare_part($id_spare_part)
{
    change_count_of_spare_part($id_spare_part, 1);
}

/**
 * Измененение количества запчастей на складе на определенное значение
 * @param $id_spare_part - идентификатор запчасти
 */
function change_count_of_spare_part($id_spare_part, $counter): bool
{
    $bool = true;
    $link = connect();
    $count = $link->query("select s.count from spare_part s where id = '$id_spare_part'")->fetch_assoc()['count'];
    if ($count <= 0) {
        $bool = false;
    }
    $changed_count = $count + $counter;
    $sql = "update spare_part set `count` = '$changed_count' where id = '$id_spare_part'";
    mysqli_query($link, $sql);
    close($link);
    return $bool;
}

/**
 * Резервирование запчастей конкретной заявки
 * @param $id_request - идентификатор заявки
 */
function reservation_spare_part($id_request)
{
    $defects = mysqli_fetch_assoc(get_all_id_defects_by_id($id_request));
    $bool = true;
    for ($i = 0; $i < count($defects); $i++) {
        $spare_parts = mysqli_fetch_assoc(get_all_spare_part_by_id_defects($defects['id']));
        for ($j = 0; $j < count($spare_parts); $j++) {
            $id_spare_part = $spare_parts['id'];
            $ret = reduce_count_of_spare_part($id_spare_part);
            if ($ret == false) {
                $bool = false;
            }
        }
    }
    return $bool;
}

/**
 * Откат резервирования запчастей конкретной заявки
 * @param $id_request - идентификатор заявки
 */
function rollback_reservation_spare_part($id_request)
{
    $defects = mysqli_fetch_assoc(get_all_id_defects_by_id($id_request));
    for ($i = 0; $i < count($defects); $i++) {
        $spare_parts = mysqli_fetch_assoc(get_all_spare_part_by_id_defects($defects['id']));
        for ($j = 0; $j < count($spare_parts); $j++) {
            $id_spare_part = $spare_parts['id'];
            increase_count_of_spare_part($id_spare_part);
        }
    }
}

/**
 * Изменение состояния активности заявки
 * @param $id_request - идентификатор заявки
 * @param $bit - состояние активности заявки
 */
function change_active($id_request, $bit)
{
    $link = connect();
    $link->query("update request set `is_active` = '$bit' where id = '$id_request'");
    close($link);
}

/**
 * Получение списка автомобилей
 * @return bool|mysqli_result
 */
function get_all_cars(): mysqli_result|bool
{
    $link = connect();
    $sql = "select * from cars";
    $result = mysqli_query($link, $sql);
    close($link);
    return $result;
}

/**
 * Получение списка поломок
 * @return bool|mysqli_result
 */
function get_all_defects(): mysqli_result|bool
{
    $link = connect();
    $sql = "select * from defects";
    $result = mysqli_query($link, $sql);
    close($link);
    return $result;
}










