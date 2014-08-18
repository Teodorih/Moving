-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.1.71-community-log - MySQL Community Server (GPL)
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры базы данных Square_coordinates
CREATE DATABASE IF NOT EXISTS `square_coordinates` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `Square_coordinates`;


-- Дамп структуры для таблица Square_coordinates.coordinates
CREATE TABLE IF NOT EXISTS `coordinates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `coord_x` int(11) NOT NULL,
  `coord_y` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_coordinates_users` (`user_id`),
  CONSTRAINT `FK_coordinates_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица Square_coordinates.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL DEFAULT '0',
  `user_hash` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
square_coordinates