-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 03 2017 г., 10:57
-- Версия сервера: 5.5.49-0ubuntu0.14.04.1-log
-- Версия PHP: 5.6.21-1+donate.sury.org~trusty+4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `adv-avtomoder`
--
CREATE DATABASE IF NOT EXISTS `adv-avtomoder` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `adv-avtomoder`;

-- --------------------------------------------------------

--
-- Структура таблицы `adv`
--

CREATE TABLE `adv` (
  `id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `owner_phone` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `type` enum('sale','rent','','') NOT NULL,
  `price` int(11) NOT NULL,
  `owner_type` enum('owner','realtor','','') NOT NULL,
  `floor` smallint(6) NOT NULL,
  `storeys` smallint(6) NOT NULL,
  `rooms` tinyint(4) NOT NULL,
  `area` smallint(6) NOT NULL,
  `description` varchar(500) NOT NULL,
  `source_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `adv`
--

INSERT INTO `adv` (`id`, `address`, `owner_name`, `owner_phone`, `status`, `type`, `price`, `owner_type`, `floor`, `storeys`, `rooms`, `area`, `description`, `source_id`) VALUES
(6, 'sdfsdf', 'sdfsdfdsf', '89012345678', 2, 'rent', 3434, 'owner', 34, 34, 127, 3434, 'sdfsdf', 2),
(7, 'sdfsd', 'sdfdsf', 'sdfsdf', 1, 'sale', 3434, 'realtor', 32767, 34, 34, 32767, 'почасоdвая Аренда', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `rules`
--

CREATE TABLE `rules` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rules`
--

INSERT INTO `rules` (`id`, `name`, `description`) VALUES
(1, 'PhoneBlackList', 'Номер телефона собственника находится в “черном списке”'),
(2, 'StopWords', 'Описание содержит “стоп слова”'),
(3, 'MismatchDesc', 'Несоответствие описания'),
(4, 'SuspectAd', 'Подозрительное объявление'),
(5, 'DoubleAd', 'Найден дубликат');

-- --------------------------------------------------------

--
-- Структура таблицы `sources`
--

CREATE TABLE `sources` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sources`
--

INSERT INTO `sources` (`id`, `name`) VALUES
(1, 'Источник 1'),
(2, 'Источник 2'),
(3, 'Источник 3');

-- --------------------------------------------------------

--
-- Структура таблицы `source_rules`
--

CREATE TABLE `source_rules` (
  `id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `rule_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `source_rules`
--

INSERT INTO `source_rules` (`id`, `source_id`, `rule_id`) VALUES
(1, 1, 2),
(2, 1, 3),
(3, 1, 4),
(4, 1, 5),
(5, 2, 1),
(6, 2, 3),
(7, 2, 4),
(8, 2, 5),
(9, 3, 1),
(10, 3, 2),
(11, 3, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `status_history`
--

CREATE TABLE `status_history` (
  `id` int(11) NOT NULL,
  `adv_id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `rule_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `status_history`
--

INSERT INTO `status_history` (`id`, `adv_id`, `status`, `rule_id`, `date`) VALUES
(9, 6, '2', 1, '2017-03-03 07:23:01'),
(10, 7, '2', 2, '2017-03-03 07:23:58'),
(11, 7, '1', 0, '2017-03-03 07:24:23');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `adv`
--
ALTER TABLE `adv`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `sources`
--
ALTER TABLE `sources`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `source_rules`
--
ALTER TABLE `source_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `source_id` (`source_id`),
  ADD KEY `rule_id` (`rule_id`);

--
-- Индексы таблицы `status_history`
--
ALTER TABLE `status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adv_id` (`adv_id`),
  ADD KEY `rule_id` (`rule_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `adv`
--
ALTER TABLE `adv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `rules`
--
ALTER TABLE `rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `sources`
--
ALTER TABLE `sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `source_rules`
--
ALTER TABLE `source_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `status_history`
--
ALTER TABLE `status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `status_history`
--
ALTER TABLE `status_history`
  ADD CONSTRAINT `status_history_ibfk_1` FOREIGN KEY (`adv_id`) REFERENCES `adv` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
