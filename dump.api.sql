-- phpMyAdmin SQL Dump
-- version 3.4.3.2
-- http://www.phpmyadmin.net
--
-- Хост: 10.0.0.85:3306
-- Время создания: Май 03 2012 г., 00:04
-- Версия сервера: 5.1.62
-- Версия PHP: 5.3.9-ZS5.6.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `ilyxa`
--

-- --------------------------------------------------------

--
-- Структура таблицы `api`
--

CREATE TABLE IF NOT EXISTS `api` (
  `app_id` int(5) NOT NULL AUTO_INCREMENT,
  `app_secret` varchar(32) NOT NULL,
  `expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `app_id` (`app_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=111 ;

--
-- Дамп данных таблицы `api`
--

INSERT INTO `api` (`app_id`, `app_secret`, `expiration`) VALUES
(110, '78e4cbbdc7f5383d71fc2ea34c9d9086', '2012-04-30 14:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
