-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 24 2015 г., 17:17
-- Версия сервера: 5.6.24-2+deb.sury.org~trusty+2
-- Версия PHP: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `app`
--
CREATE DATABASE IF NOT EXISTS `app` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `app`;

-- --------------------------------------------------------

--
-- Структура таблицы `game_result`
--

CREATE TABLE IF NOT EXISTS `game_result` (
  `id` int(11) NOT NULL,
  `name` varbinary(100) NOT NULL,
  `score` int(11) NOT NULL,
  `date` bigint(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `game_result`
--

INSERT INTO `game_result` (`id`, `name`, `score`, `date`) VALUES
(1, 0xd0a1d0b5d180d0b3d0b5d0b9, 74, 1440288000),
(2, 0xd098d0b3d0bed180d18c, 21, 1440288000),
(3, 0xd09ad0b8d180d0b8d0bbd0bb, 61, 1440115200),
(4, 0xd09cd0b8d185d0b0d0b8d0bb, 73, 1437004800),
(5, 0xd09ad0b8d180d0b8d0bbd0bb, 40, 1440115200),
(6, 0xd09cd0b8d185d0b0d0b8d0bb, 67, 1440201600),
(7, 0xd09cd0b8d185d0b0d0b8d0bb, 75, 1440201600),
(8, 0xd0a1d0b5d180d0b3d0b5d0b9, 80, 1440201600),
(9, 0xd09ad0b8d180d0b8d0bbd0bb, 38, 1436227200),
(10, 0xd0a1d0b5d0bcd0b5d0bd, 54, 1436227200),
(11, 0xd098d0b3d0bed180d18c, 41, 1437350400),
(12, 0xd09ad0b8d180d0b8d0bbd0bb, 85, 1433116800),
(13, 0xd098d0b3d0bed180d18c, 57, 1436227200),
(14, 0xd0a1d0b5d0bcd0b5d0bd, 52, 1440288000),
(15, 0xd09ad0b8d180d0b8d0bbd0bb, 10, 1440201600),
(16, 0xd0a1d0b5d0bcd0b5d0bd, 50, 1437350400),
(17, 0xd09cd0b8d185d0b0d0b8d0bb, 38, 1440201600),
(18, 0xd098d0b3d0bed180d18c, 90, 1440201600),
(19, 0xd0a1d0b5d0bcd0b5d0bd, 15, 1440288000),
(20, 0xd0a1d0b5d180d0b3d0b5d0b9, 47, 1440288000),
(21, 0xd0a1d0b5d180d0b3d0b5d0b9, 41, 1437350400),
(22, 0xd09ad0b8d180d0b8d0bbd0bb, 32, 1437004800),
(23, 0xd098d0b3d0bed180d18c, 40, 1440115200),
(24, 0xd0a1d0b5d0bcd0b5d0bd, 40, 1437004800),
(25, 0xd0a1d0b5d180d0b3d0b5d0b9, 97, 1440201600),
(26, 0xd09ad0b8d180d0b8d0bbd0bb, 54, 1437004800),
(27, 0xd0a1d0b5d180d0b3d0b5d0b9, 86, 1440201600),
(28, 0xd0a1d0b5d0bcd0b5d0bd, 80, 1440115200),
(29, 0xd09cd0b8d185d0b0d0b8d0bb, 20, 1437004800),
(30, 0xd0a1d0b5d0bcd0b5d0bd, 35, 1433116800),
(31, 0xd09cd0b8d185d0b0d0b8d0bb, 38, 1440201600),
(32, 0xd098d0b3d0bed180d18c, 18, 1437350400),
(33, 0xd09cd0b8d185d0b0d0b8d0bb, 47, 1437350400),
(34, 0xd0a1d0b5d180d0b3d0b5d0b9, 41, 1437350400),
(35, 0xd09cd0b8d185d0b0d0b8d0bb, 100, 1440115200);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `game_result`
--
ALTER TABLE `game_result`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `game_result`
--
ALTER TABLE `game_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
