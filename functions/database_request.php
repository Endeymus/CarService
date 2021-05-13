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
function sql_find_user_by_phone($phone): mixed
{
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
function add_request($username, $phone, $id_car, $id_defects)
{
    $link = connect();
    //Проверка пользователя наличия
    $id_user = sql_find_user_by_phone($phone);
    if ($id_user == null) {
        sql_add_user($username, $phone);
    }
    //подсчет итоговой стоимости
    $cost = calc_cost($id_defects);
    //поиск менее занятого работника
    $id_employees = sql_find_free_employee();
    $sql = "INSERT INTO request (id_user, creation_date, id_car, id_employees, cost)
  VALUES ('$id_user', CURDATE(), '$id_car', '$id_employees', '$cost');";
    //создание новой заявки
    mysqli_query($link, $sql);
    $id_request = mysqli_insert_id($link);
    //сохранение поломок за конкретной заявкой
    save_defects($id_request, $id_defects);
    close($link);
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
    //fixme проверить нужна ли соль
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
    if (checkEmployees($login, $password)) {
        $mysqli = connect();
        $res = $mysqli->query("SELECT id FROM employees WHERE `login`='$login'");
        $temp = $res->fetch_assoc();
        close($mysqli);
        return $temp['id'];
    }
    return null;
}

/**
 * Получение ФИО, номер телефона, марки и модели автомобиля, дата создания заявки
 * @param $id_request - идентификатор заявки
 * @return array|null
 */
function get_user_info($id_request): ?array
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
 * Получение перечня поломок по номеру заявки
 * @param $id_request - идентификатор заявки
 * @return mysqli_result|bool
 */
function get_all_defects_by_id($id_request): mysqli_result|bool
{
    $link = connect();
    $sql = "SELECT d.id, d.name, rdf.id_request, r.appointment_date FROM defects d 
  JOIN request_defects_fk rdf ON d.id = rdf.id_defects 
  JOIN request r ON rdf.id_request = r.id
  WHERE r.id='$id_request'";
    $result = mysqli_query($link, $sql);
    close($link);
    return $result;
}

/**
 * Получение всех запчастей конкретной поломки
 * @param $id_defects - идентификатор поломки
 * @return mysqli_result|bool
 */
function get_all_spare_part_by_id_defects($id_defects): mysqli_result|bool
{
    $link = connect();
    $sql = "SELECT s.id, s.cost, s.name, s.count FROM spare_part s 
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
    $sql = "insert into request(`appointment_date`) values (CURRENT_DATE) where id = '$id_request'";
    mysqli_query($link, $sql);

    close($link);
}

/**
 * Функция установки флага завершения починки
 * @param $id_request - идентификатор заявки
 */
function set_repair_completed($id_request)
{
    $link = connect();
    $sql = "insert into request(`repair_completed`) values (1) where id = '$id_request'";
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
    $sql = "insert into request(`request_completed`) values (1) where id = '$id_request'";
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
    $count = $link->query("select s.count from spare_part where id = '$id_spare_part'")->fetch_assoc()['count'];
    if ($count <= 0) {
        $bool = false;
    }
    $changed_count = $count + $counter;
    $sql = "insert into spare_part(`count`) values ('$changed_count') where id = '$id_spare_part'";
    mysqli_query($link, $sql);
    close($link);
    return $bool;
}

/**
 * Резервирование запчастей конкретной заявки
 * @param $id_request - идентификатор заявки
 */
function reservation_spare_part($id_request): bool
{
    $defects = mysqli_fetch_array(get_all_defects_by_id($id_request));
    $bool = true;
    for ($i = 0; $i < count($defects); $i++) {
        $spare_parts = mysqli_fetch_array(get_all_spare_part_by_id_defects($defects[$i]['id']));
        for($j = 0; $j < count($spare_parts); $j++) {
            $id_spare_part = $spare_parts[$j]['id'];
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
    $defects = mysqli_fetch_array(get_all_defects_by_id($id_request));
    for ($i = 0; $i < count($defects); $i++) {
        $spare_parts = mysqli_fetch_array(get_all_spare_part_by_id_defects($defects[$i]['id']));
        for($j = 0; $j < count($spare_parts); $j++) {
            $id_spare_part = $spare_parts[$j]['id'];
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
    $link->query("insert into request(`is_active`) values ('$bit') where id = '$id_request'");
    close($link);
}









