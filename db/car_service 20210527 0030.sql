--
-- Скрипт сгенерирован Devart dbForge Studio 2019 for MySQL, Версия 8.2.23.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 27.05.2021 0:30:35
-- Версия сервера: 8.0.19
-- Версия клиента: 4.1
--

-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установить режим SQL (SQL mode)
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

--
-- Установка базы данных по умолчанию
--
USE car_service;

--
-- Удалить таблицу `defects_spare_part_fk`
--
DROP TABLE IF EXISTS defects_spare_part_fk;

--
-- Удалить таблицу `request_defects_fk`
--
DROP TABLE IF EXISTS request_defects_fk;

--
-- Удалить таблицу `defects`
--
DROP TABLE IF EXISTS defects;

--
-- Удалить таблицу `spare_part`
--
DROP TABLE IF EXISTS spare_part;

--
-- Удалить таблицу `order_request_fk`
--
DROP TABLE IF EXISTS order_request_fk;

--
-- Удалить таблицу `order`
--
DROP TABLE IF EXISTS `order`;

--
-- Удалить таблицу `request`
--
DROP TABLE IF EXISTS request;

--
-- Удалить таблицу `cars`
--
DROP TABLE IF EXISTS cars;

--
-- Удалить таблицу `employees`
--
DROP TABLE IF EXISTS employees;

--
-- Удалить таблицу `users`
--
DROP TABLE IF EXISTS users;

--
-- Установка базы данных по умолчанию
--
USE car_service;

--
-- Создать таблицу `users`
--
CREATE TABLE IF NOT EXISTS users (
  id int NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  phone varchar(14) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 11,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `employees`
--
CREATE TABLE IF NOT EXISTS employees (
  id int NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  `position` varchar(50) NOT NULL,
  login varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 8,
AVG_ROW_LENGTH = 3276,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `cars`
--
CREATE TABLE IF NOT EXISTS cars (
  id int NOT NULL AUTO_INCREMENT,
  brand varchar(25) NOT NULL,
  model varchar(25) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 35,
AVG_ROW_LENGTH = 2730,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `request`
--
CREATE TABLE IF NOT EXISTS request (
  id int NOT NULL AUTO_INCREMENT,
  id_user int NOT NULL,
  creation_date date NOT NULL,
  appointment_date date DEFAULT NULL,
  id_car int DEFAULT NULL,
  id_employees int DEFAULT NULL,
  request_completed bit(1) DEFAULT b'0',
  repair_completed bit(1) DEFAULT b'0',
  cost decimal(10, 2) DEFAULT NULL,
  is_active bit(1) DEFAULT b'1',
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 46,
AVG_ROW_LENGTH = 16384,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать внешний ключ
--
ALTER TABLE request
ADD CONSTRAINT request_cars_id_fk FOREIGN KEY (id_car)
REFERENCES cars (id);

--
-- Создать внешний ключ
--
ALTER TABLE request
ADD CONSTRAINT request_employees_id_fk FOREIGN KEY (id_employees)
REFERENCES employees (id);

--
-- Создать внешний ключ
--
ALTER TABLE request
ADD CONSTRAINT request_users_id_fk FOREIGN KEY (id_user)
REFERENCES users (id);

--
-- Создать таблицу `order`
--
CREATE TABLE IF NOT EXISTS `order` (
  id int NOT NULL AUTO_INCREMENT,
  id_spare_part int DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `order_request_fk`
--
CREATE TABLE IF NOT EXISTS order_request_fk (
  id_request int DEFAULT NULL,
  id_order int DEFAULT NULL
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать внешний ключ
--
ALTER TABLE order_request_fk
ADD CONSTRAINT FK_order_request_fk_id_order FOREIGN KEY (id_order)
REFERENCES `order` (id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Создать внешний ключ
--
ALTER TABLE order_request_fk
ADD CONSTRAINT FK_order_request_fk_id_request FOREIGN KEY (id_request)
REFERENCES request (id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Создать таблицу `spare_part`
--
CREATE TABLE IF NOT EXISTS spare_part (
  id int NOT NULL AUTO_INCREMENT,
  cost decimal(10, 2) NOT NULL,
  name varchar(50) NOT NULL,
  count int DEFAULT 0,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 32,
AVG_ROW_LENGTH = 528,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `defects`
--
CREATE TABLE IF NOT EXISTS defects (
  id int NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  cost decimal(10, 2) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 25,
AVG_ROW_LENGTH = 2048,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `request_defects_fk`
--
CREATE TABLE IF NOT EXISTS request_defects_fk (
  id_request int DEFAULT NULL,
  id_defects int DEFAULT NULL,
  repair_completed bit(1) DEFAULT b'0'
)
ENGINE = INNODB,
AVG_ROW_LENGTH = 862,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать внешний ключ
--
ALTER TABLE request_defects_fk
ADD CONSTRAINT FK_request_defects_fk_id_defects FOREIGN KEY (id_defects)
REFERENCES defects (id);

--
-- Создать внешний ключ
--
ALTER TABLE request_defects_fk
ADD CONSTRAINT FK_request_defects_fk_id_request FOREIGN KEY (id_request)
REFERENCES request (id);

--
-- Создать таблицу `defects_spare_part_fk`
--
CREATE TABLE IF NOT EXISTS defects_spare_part_fk (
  id_defects int NOT NULL,
  id_spare_part int NOT NULL
)
ENGINE = INNODB,
AVG_ROW_LENGTH = 682,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать внешний ключ
--
ALTER TABLE defects_spare_part_fk
ADD CONSTRAINT FK_defects_spare_part_fk_id_defects FOREIGN KEY (id_defects)
REFERENCES defects (id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Создать внешний ключ
--
ALTER TABLE defects_spare_part_fk
ADD CONSTRAINT FK_defects_spare_part_fk_id_spare_part FOREIGN KEY (id_spare_part)
REFERENCES spare_part (id) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Вывод данных для таблицы users
--
INSERT INTO users VALUES
(1, 'Соколов Алексей Александрович', '+79275618605'),
(2, 'Кузнецов Павел Алексеевич', '+79563718426'),
(3, 'Кузнецов Вячеслав Григорьевич', '(888) 456-2315'),
(4, 'Леонтьев Фёдор Георгиевич', '+79884565234'),
(5, 'Сальников Адам Николаевич', '+74956589612'),
(6, 'Кручинкина Тамара Валентиновна', '(936) 430-8522'),
(7, 'Житкова Клавдия Тимофеевна', '(949) 723-6596'),
(8, 'Геремеш Арсений Прохорович', '(908) 365-8073'),
(9, 'Сидоров Елисей Викторович', '(945) 864-6691'),
(10, 'Кудрявцев Олег Феликсович', '(957) 155-3285'),
(11, 'Денисов Аполлон Еремеевич', '(922) 432-2925');

-- 
-- Вывод данных для таблицы employees
--
INSERT INTO employees VALUES
(1, 'Иванов Виктор Сергеевич', 'механик', 'ivanov', '$2y$10$3ZHft74doU1vXh1NBp5DkeOJHAV8L8jcl5ANMKLe9lRkdYTq6yxsu'),
(2, 'Куримов Анатолий Петрович', 'слесарь', 'kyrimov', '$2y$10$3ZHft74doU1vXh1NBp5DkeOJHAV8L8jcl5ANMKLe9lRkdYTq6yxsu'),
(3, 'Шувалов Никита Георгиевич', 'механик', 'shyvalov', '$2y$10$3ZHft74doU1vXh1NBp5DkeOJHAV8L8jcl5ANMKLe9lRkdYTq6yxsu'),
(4, 'Глухов Степан Павлович', 'сварщик', 'glykhov', '$2y$10$3ZHft74doU1vXh1NBp5DkeOJHAV8L8jcl5ANMKLe9lRkdYTq6yxsu'),
(6, 'Белов Виктор Владимирович', 'автомаляр', 'belov', '$2y$10$3ZHft74doU1vXh1NBp5DkeOJHAV8L8jcl5ANMKLe9lRkdYTq6yxsu'),
(7, 'Комиссарова Екатерина Геннадьевна', 'Администратор', 'katya', '$2y$10$a3VTT51qtC/Big0BB9JoausK9KguZqON2.GhZYhJemvSYw7hT2UbO'),
(8, 'Алексеев Фрол Дамирович', 'Сварщик', 'Alekseev', '$2y$10$3ZHft74doU1vXh1NBp5DkeOJHAV8L8jcl5ANMKLe9lRkdYTq6yxsu');

-- 
-- Вывод данных для таблицы cars
--
INSERT INTO cars VALUES
(1, 'Audi', 'A4'),
(2, 'Audi', 'A5'),
(3, 'Audi', 'A7'),
(4, 'Audi', 'A6'),
(5, 'Audi', 'S5'),
(6, 'Audi', 'R8'),
(7, 'Audi ', 'Q5'),
(8, 'Lada', 'Largus'),
(9, 'Lada', 'Granta'),
(10, 'Lada', 'Priora'),
(11, 'BMW', 'X1'),
(12, 'BMW', 'X2'),
(13, 'BMW', 'X3'),
(14, 'BMW', 'X4'),
(15, 'BMW', 'X5'),
(16, 'BMW', 'X6'),
(17, 'BMW', 'M8'),
(18, 'Ford', 'Kuga'),
(19, 'Ford', 'Focus'),
(20, 'Kia', 'Rio'),
(21, 'Kia', 'Cerato'),
(22, 'Kia', 'K5'),
(23, 'Mercedes-Benz', 'A Седан'),
(24, 'Mercedes-Benz', 'C Седан'),
(25, 'Mercedes-Benz', 'E Купе'),
(26, 'Mercedes-Benz', 'E Универсал'),
(27, 'Mercedes-Benz', 'G'),
(28, 'Mercedes-Benz', 'GLA'),
(29, 'Porsche', '718 Cayman'),
(30, 'Porsche', 'Panamera'),
(31, 'Porsche', 'Macan'),
(32, 'Porsche', 'Cayenne'),
(33, 'Porcshe', '911'),
(34, 'Tesla', 'Model S'),
(35, 'Tesla ', 'Model X');

-- 
-- Вывод данных для таблицы request
--
INSERT INTO request VALUES
(1, 3, '2021-04-20', '2021-05-12', 5, 8, True, True, NULL, True),
(2, 1, '2021-05-24', '2021-05-24', 6, 4, False, True, NULL, True),
(40, 3, '2021-05-24', NULL, 2, 8, False, False, 2500.00, False),
(41, 6, '2021-05-25', '2021-05-25', 11, 4, False, False, 18500.00, True),
(42, 7, '2021-05-25', NULL, 25, 1, False, False, 6850.00, False),
(43, 8, '2021-05-25', '2021-05-25', 32, 2, False, True, 7530.00, True),
(44, 9, '2021-05-25', '2021-05-25', 34, 3, True, True, 6000.00, True),
(45, 10, '2021-05-25', '2021-05-25', 8, 6, False, False, 17300.00, True),
(46, 11, '2021-05-25', NULL, 16, 8, False, False, 21000.00, True);

-- 
-- Вывод данных для таблицы `order`
--
-- Таблица car_service.`order` не содержит данных

-- 
-- Вывод данных для таблицы spare_part
--
INSERT INTO spare_part VALUES
(1, 0.00, 'Зеркало', 5),
(3, 0.00, 'Масло', 20),
(4, 0.00, 'Двигатель', 10),
(5, 0.00, 'Тормозные колодки (комплект)', 40),
(6, 0.00, 'Кузов автомобиля', 3),
(7, 0.00, 'Шасси', 5),
(8, 0.00, 'Коробка передач', 6),
(9, 0.00, 'Фара', 20),
(10, 0.00, 'Рама', 23),
(11, 0.00, 'Подвеска', 35),
(12, 0.00, 'Колесо', 50),
(13, 0.00, 'Топливный бак', 0),
(14, 0.00, 'Дворники', 5),
(15, 0.00, 'Амортизатор', 0),
(16, 0.00, 'Сцепление', 3),
(17, 0.00, 'Бампер', 4),
(18, 0.00, 'Провода', 5),
(19, 0.00, 'Замок', 2),
(20, 0.00, 'Стекло заднее', 100),
(21, 0.00, 'Радиатор печки', 12),
(22, 0.00, 'Стекло переднее', 15),
(23, 0.00, 'Стекло боковое', 25),
(24, 0.00, 'Фаркоп', 35),
(25, 0.00, 'Наружний пыльник', 14),
(26, 0.00, 'Внутренний пыльник', 14),
(27, 0.00, 'Жидкость гидроусилителя ', 15),
(28, 0.00, 'Подвеска', 11),
(29, 0.00, 'Стеклоочиститель ', 61),
(30, 0.00, 'Дверь', 20),
(31, 0.00, 'Сигнализация', 11),
(32, 0.00, 'Тормозной шланг', 19);

-- 
-- Вывод данных для таблицы defects
--
INSERT INTO defects VALUES
(1, 'Замена бампера', 2500.00),
(3, 'Ремонт замка двери', 3500.00),
(4, 'Замена радиатора печки', 5000.00),
(5, 'Боковое стекло', 1000.00),
(6, 'Установка фаркопа', 5500.00),
(7, 'Замена пыльника наружнего', 1000.00),
(8, 'Замена пыльника внутренненго', 1030.00),
(9, 'Передние колодки (1 пара)', 800.00),
(10, 'Задние колодки (1 пара)', 800.00),
(11, 'Замена дворников', 500.00),
(12, 'Топливный бак ', 6500.00),
(13, 'Переднее стекло', 12000.00),
(14, 'Установка фары', 10000.00),
(15, 'Замена масла в двигателе', 1000.00),
(16, 'Замена жикости гидроуселителя', 750.00),
(17, 'Дигностика подвески', 350.00),
(18, 'Стеклоочиститель передний', 500.00),
(19, 'Стеклоочиститель задний', 450.00),
(20, 'Замена двери', 15000.00),
(21, 'Выпрямление двери', 9000.00),
(22, 'Замена коробки передач', 17596.00),
(23, 'Проводка', 3000.00),
(24, 'Установка сигнализации', 6000.00),
(25, 'Тормоз', 3500.00);

-- 
-- Вывод данных для таблицы request_defects_fk
--
INSERT INTO request_defects_fk VALUES
(1, 1, True),
(1, 3, True),
(1, 5, True),
(40, 1, False),
(41, 12, False),
(41, 13, False),
(42, 11, False),
(42, 17, False),
(42, 24, False),
(43, 6, False),
(43, 7, False),
(43, 8, False),
(44, 1, False),
(44, 3, False),
(45, 12, False),
(45, 14, False),
(45, 10, False),
(46, 13, False),
(46, 21, False);

-- 
-- Вывод данных для таблицы order_request_fk
--
-- Таблица car_service.order_request_fk не содержит данных

-- 
-- Вывод данных для таблицы defects_spare_part_fk
--
INSERT INTO defects_spare_part_fk VALUES
(1, 17),
(3, 19),
(4, 21),
(5, 23),
(6, 24),
(7, 25),
(8, 25),
(9, 5),
(10, 5),
(11, 14),
(12, 13),
(13, 22),
(14, 9),
(15, 3),
(16, 27),
(17, 28),
(18, 29),
(19, 29),
(20, 30),
(22, 8),
(22, 18),
(23, 18),
(24, 31),
(24, 18);

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;