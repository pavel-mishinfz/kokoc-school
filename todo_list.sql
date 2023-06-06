-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 04 2023 г., 02:28
-- Версия сервера: 5.7.39
-- Версия PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `todo_list`
--

-- --------------------------------------------------------

--
-- Структура таблицы `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name` char(64) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `projects`
--

INSERT INTO `projects` (`id`, `name`, `user_id`) VALUES
(1, 'Работа', 1),
(2, 'Учеба', 1),
(3, 'Домашние дела', 1),
(4, 'Домашние дела', 2),
(5, 'Входящие', 2),
(6, 'Авто', 2),
(7, 'Работа', 6),
(8, 'Учеба', 6),
(9, 'Домашние дела', 6),
(10, 'Авто', 6),
(12, 'Здоровье', 6),
(13, 'Английский', 6),
(14, 'Спорт', 6),
(19, 'Курсы кулинарии', 6),
(20, 'Учеба', 11),
(21, 'Курсовой проект', 6),
(47, '&lt;i&gt;test&lt;/i&gt;', 6),
(48, '&lt;i&gt;tsdfest&lt;/i&gt;', 6),
(49, 'adfasdfsdf', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `name` char(255) NOT NULL,
  `dt_add` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status_ext` tinyint(1) NOT NULL,
  `file_path` char(255) DEFAULT NULL,
  `dt_deadline` char(10) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `dt_add`, `status_ext`, `file_path`, `dt_deadline`, `user_id`, `project_id`) VALUES
(1, 'Отправить отчет начальнику', '2023-02-21 17:57:14', 0, NULL, '2023-10-22', 1, 1),
(2, 'Подготовиться к зачету', '2023-02-21 17:57:14', 1, NULL, '2023-02-20', 1, 2),
(3, 'Покормить кота', '2023-02-21 17:57:14', 1, NULL, NULL, 2, 3),
(4, 'Сходить в кафе', '2023-02-21 17:57:14', 0, NULL, '2023-06-30', 2, 4),
(5, 'Поменять зимнюю резину', '2023-02-21 17:57:14', 1, NULL, NULL, 2, 5),
(6, 'Сделать ремонт', '2023-02-21 17:57:14', 0, NULL, NULL, 2, 3),
(7, 'Отправить отчет начальнику', '2023-02-21 19:47:05', 1, NULL, '2023-10-22', 6, 7),
(8, 'Подготовиться к зачету', '2023-02-21 19:47:05', 1, NULL, '2023-02-20', 6, 8),
(9, 'Покормить кота', '2023-02-21 19:47:05', 1, NULL, NULL, 6, 9),
(10, 'Сходить в кафе', '2023-02-21 19:47:05', 1, NULL, '2023-06-30', 6, 9),
(11, 'Помыть машину', '2023-02-21 19:47:05', 0, NULL, NULL, 6, 10),
(12, 'Сделать ремонт', '2023-02-21 19:47:05', 0, NULL, NULL, 6, 9),
(13, 'Отправить машину на тех. осмотр', '2023-02-27 22:54:50', 0, NULL, NULL, 6, 10),
(14, 'Записаться на приме к терапевту', '2023-02-28 10:26:29', 0, '', '2023-03-03', 6, 12),
(15, 'Выучить 10 новых слов', '2023-02-28 22:23:31', 0, NULL, '2022-05-11', 6, 13),
(16, 'Начать бегать по утрам', '2023-02-28 22:23:31', 0, NULL, '2023-03-03', 6, 14),
(17, 'Сдать задания по курсам', '2023-03-03 09:50:08', 0, NULL, '2023-03-03', 11, 20),
(18, 'Купить тетрадки', '2023-03-03 10:07:07', 0, 'Andronov_LAB 1.docx', '2023-03-10', 11, 20),
(19, 'Тест', '2023-03-03 10:26:40', 0, 'Andronov_LAB 1.docx', '2023-03-17', 11, 20),
(20, 'Сдать задания по курсам', '2023-03-03 10:37:08', 0, 'avatar.jpg', '2023-03-24', 6, 8),
(23, '&lt;i&gt;test&lt;/i&gt;', '2023-04-03 17:28:51', 0, '', '2023-04-30', 6, 7),
(24, 'Научиться петь', '2023-04-03 19:47:32', 0, '', '2023-04-13', 6, 9);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` char(64) NOT NULL,
  `dt_registration` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` char(128) NOT NULL,
  `password` char(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `dt_registration`, `email`, `password`) VALUES
(1, 'Константин', '2023-02-21 17:57:14', 'konstantinae@gmail.com', 'qwerty1111'),
(2, 'Владимир', '2023-02-21 17:57:14', 'vladimir13@yandex.ru', 'secret'),
(3, 'Мария', '2023-02-21 17:57:15', 'mishinamariyafz@gmail.com', '1masha1'),
(4, 'Антон', '2023-02-21 17:57:15', 'pro100antocha@mail.ru', 'mypassword'),
(5, 'Павел', '2023-02-21 17:57:38', 'pavel.mishinfz@mail.ru', '$2y$10$/mJjofTWlNa5ZLhXwOaWA.jCdVqnAtFvYHygxzpQokMILa63Tjne6'),
(6, 'Павел', '2023-02-21 18:02:51', 'pavel.mishinfz@gmail.com', '$2y$10$QMXF6.nwrQkA9T2xJwLsNe6nOtWMgTHGMH6TPM7uKZIfU3T5mkzym'),
(7, 'Полина', '2023-02-21 18:05:10', 'polina.n@yandex.ru', '$2y$10$9poNQom67aeWc9T1hppNSePBMiTrR037FbGHsMMbqIr6PqEP/.uFi'),
(8, 'test', '2023-02-21 18:33:33', 'test@mail.ru', '$2y$10$zH4OSCqhJibOFoqML3HjguicqH0ZaPO9FZg8FhRt4XgXjIjJQ7Oam'),
(9, 'TEST', '2023-02-21 19:29:54', 'test@gmail.com', '$2y$10$Q0023mACEu96SkGIfv4BCezUIupjjw/rJiOw6bdZHQcmFmCYuFVb.'),
(10, 'Полина', '2023-02-21 19:49:39', 'polina.n@mail.ru', '$2y$10$w9ca5iaY8JunEHEGP1ixHOs18XAe6APhquwbcBUxjy4f8z7JisM.O'),
(11, 'Максим', '2023-03-03 09:53:12', 'malavog215@vootin.com', '$2y$10$336ApFVD/68nNKlbYyhHoOD8JQttiPWi6QYt.zivBpi/m972VvlAe'),
(20, 'asd', '2023-04-02 23:11:41', 'pavel.mishiiiiinfz@gmail.com', '$2y$10$zmHYq84efo3ThXNxWNKe.utECWMSkOvLUAC1dO0yjej.LjA55W0fu'),
(21, '1234', '2023-04-02 23:12:22', 'pavel.mmmmmishinfz@gmail.com', '$2y$10$aTHc0bIBQ0goutGUNNljxO/WnoaR6M4.7WmET.haygW6f8Iux6dIy'),
(22, 'dgfdfg', '2023-04-02 23:13:56', 'paveeeel.mishinfz@gmail.com', '$2y$10$v7Zp3PYFEZ35lMvRyWE9CezCV3fgy5tmXhpLdexfcMbo6VvdvBZnu'),
(23, '&lt;i&gt;test&lt;/i&gt;', '2023-04-03 17:30:47', 'pavsdfsel.mishinfz@gmail.com', '$2y$10$mwpON2N/Aoo6kI5WgQTsh.juzPrTRzRhXJ7XK9bpmcBVjEF8rOECe'),
(24, 'sdfsdfsdf', '2023-04-03 17:36:13', 'pavel.misdsfdsdfhinfz@gmail.com', '$2y$10$wyHKV23j2BM6QmVb7A23G.EFbZUWVWWRv57wBFbkKSEqQeNgDhaf.');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_name_idx` (`name`);

--
-- Индексы таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `task_name_idx` (`name`),
  ADD KEY `task_dt_add_idx` (`dt_add`),
  ADD KEY `task_status_ext_idx` (`status_ext`),
  ADD KEY `task_file_path_idx` (`file_path`),
  ADD KEY `task_dt_deadline_idx` (`dt_deadline`);
ALTER TABLE `tasks` ADD FULLTEXT KEY `task_name_search` (`name`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_email` (`email`),
  ADD KEY `user_dt_registration_idx` (`dt_registration`),
  ADD KEY `user_password_idx` (`password`),
  ADD KEY `user_name` (`name`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
