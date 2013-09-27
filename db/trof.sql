-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 27 2013 г., 13:50
-- Версия сервера: 5.5.32-MariaDB-log
-- Версия PHP: 5.5.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `trof`
--

-- --------------------------------------------------------

--
-- Структура таблицы `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(64) NOT NULL DEFAULT '',
  `lastname` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `workphone` varchar(20) NOT NULL DEFAULT '',
  `cellphone` varchar(20) NOT NULL DEFAULT '',
  `fax` varchar(20) NOT NULL DEFAULT '',
  `street` varchar(128) NOT NULL DEFAULT '',
  `citystatezip` varchar(255) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `registrationtime` datetime DEFAULT '0000-00-00 00:00:00',
  `payment_prefs_id` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `email_2` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=422 ;

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `request_id` int(6) NOT NULL DEFAULT '0',
  `translationtype_id` int(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=752 ;

-- --------------------------------------------------------

--
-- Структура таблицы `hotels`
--

CREATE TABLE IF NOT EXISTS `hotels` (
  `id` int(3) NOT NULL DEFAULT '0',
  `name` varchar(128) NOT NULL DEFAULT '',
  `url` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL DEFAULT '',
  `phone` varchar(48) NOT NULL DEFAULT '',
  `fax` varchar(48) NOT NULL DEFAULT '',
  `country` char(2) NOT NULL DEFAULT '',
  `contact_name` varchar(128) NOT NULL DEFAULT '',
  `referer` varchar(16) NOT NULL DEFAULT '',
  `entity_type` char(3) NOT NULL DEFAULT '',
  `read_count` int(3) NOT NULL DEFAULT '0',
  `read_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `language_versions` int(2) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` char(2) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `priority` int(1) NOT NULL DEFAULT '0',
  `visibility` char(1) NOT NULL DEFAULT '',
  `registrationtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Структура таблицы `newsletters`
--

CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `subject` varchar(128) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `registrationtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `group_id` int(3) NOT NULL DEFAULT '0',
  `status_id` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `pairs`
--

CREATE TABLE IF NOT EXISTS `pairs` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `source` int(3) NOT NULL DEFAULT '0',
  `target` int(3) NOT NULL DEFAULT '0',
  `visibility` int(1) NOT NULL DEFAULT '0',
  `registrationtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=79 ;

-- --------------------------------------------------------

--
-- Структура таблицы `paymenttypes`
--

CREATE TABLE IF NOT EXISTS `paymenttypes` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `fee` decimal(4,3) NOT NULL DEFAULT '0.000',
  `registrationtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rates`
--

CREATE TABLE IF NOT EXISTS `rates` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(48) NOT NULL DEFAULT '',
  `ppw` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `fixedrate` int(5) NOT NULL,
  `registrationtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Структура таблицы `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `language_pair` int(3) NOT NULL DEFAULT '0',
  `area_id` int(2) NOT NULL DEFAULT '0',
  `iscertificationrequired` int(1) NOT NULL,
  `isnotarizationrequired` int(1) NOT NULL,
  `isexpressmailrequired` int(1) DEFAULT NULL,
  `isscanrequired` int(1) NOT NULL,
  `source_text` text NOT NULL,
  `translation` text NOT NULL,
  `instructions` text NOT NULL,
  `comments` text NOT NULL,
  `problem_part` varchar(255) NOT NULL DEFAULT '',
  `customer_id` int(6) NOT NULL DEFAULT '0',
  `customer_project_id` varchar(32) DEFAULT NULL,
  `translator_id` int(6) NOT NULL DEFAULT '0',
  `translator_paid` int(1) NOT NULL,
  `tr_timestamp_taken` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `proofreader_id` int(6) NOT NULL DEFAULT '0',
  `proofreader_paid` int(1) NOT NULL,
  `ppwp` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `deadline` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deadline_translator` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deadline_proofreader` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `immediately` int(1) NOT NULL DEFAULT '0',
  `payment_prefs_id` int(2) NOT NULL DEFAULT '0',
  `postpayment` int(1) NOT NULL,
  `delivery` varchar(5) NOT NULL DEFAULT '0',
  `amountpaid` decimal(7,2) DEFAULT '0.00',
  `estimatedprice` decimal(7,2) DEFAULT '0.00',
  `discount` decimal(4,2) DEFAULT NULL,
  `currency` char(3) NOT NULL DEFAULT '',
  `ppw` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `baseppw` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `ppwt` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `privacy` int(1) NOT NULL DEFAULT '1',
  `wordcount` int(6) NOT NULL DEFAULT '0',
  `characters` int(10) NOT NULL DEFAULT '0',
  `registrationtime` datetime DEFAULT '0000-00-00 00:00:00',
  `atlanticsilicon` int(1) DEFAULT '0',
  `status_id` int(1) NOT NULL DEFAULT '0',
  `transaction_id` varchar(128) NOT NULL,
  `postal_tracking_number` varchar(128) NOT NULL,
  `isprojectactive` int(1) DEFAULT '1',
  `istranslationmemory` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1367 ;

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(1) NOT NULL DEFAULT '0',
  `siteurl` varchar(128) NOT NULL DEFAULT '',
  `softurl` varchar(128) NOT NULL DEFAULT '',
  `softname` varchar(32) NOT NULL DEFAULT '',
  `admin_name` varchar(128) NOT NULL DEFAULT '',
  `company_name` varchar(128) NOT NULL DEFAULT '',
  `mail_address` varchar(128) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `fax` varchar(20) NOT NULL DEFAULT '',
  `sitename` varchar(128) NOT NULL DEFAULT '',
  `itemsonpage` int(3) NOT NULL DEFAULT '0',
  `minimal_request_price` int(6) NOT NULL DEFAULT '0',
  `email` varchar(128) NOT NULL DEFAULT '',
  `beneficiary` varchar(128) NOT NULL DEFAULT '',
  `benaccount` varchar(128) NOT NULL DEFAULT '',
  `benbank` varchar(128) NOT NULL DEFAULT '',
  `routenumber` varchar(128) NOT NULL DEFAULT '',
  `swift` varchar(128) NOT NULL DEFAULT '',
  `interbank` varchar(128) NOT NULL DEFAULT '',
  `corracount` varchar(128) NOT NULL DEFAULT '',
  `error_level` varchar(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(32) NOT NULL DEFAULT '',
  `firstname` varchar(32) NOT NULL DEFAULT '',
  `position` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(32) NOT NULL DEFAULT '',
  `phone` varchar(32) NOT NULL DEFAULT '',
  `login` varchar(16) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `registrationtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `priority` int(1) NOT NULL DEFAULT '0',
  `registrationtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Структура таблицы `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(48) NOT NULL DEFAULT '',
  `subject` varchar(64) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `group_id` int(1) NOT NULL DEFAULT '0',
  `status_id` int(2) NOT NULL DEFAULT '0',
  `registrationtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Структура таблицы `translators`
--

CREATE TABLE IF NOT EXISTS `translators` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `registrationtime` datetime DEFAULT '0000-00-00 00:00:00',
  `firstname` varchar(32) NOT NULL DEFAULT '',
  `middlename` varchar(64) NOT NULL DEFAULT '',
  `lastname` varchar(32) NOT NULL DEFAULT '',
  `birthdate` date NOT NULL DEFAULT '0000-00-00',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `cellphone` varchar(20) NOT NULL DEFAULT '',
  `fax` varchar(20) NOT NULL DEFAULT '',
  `country` varchar(32) NOT NULL DEFAULT '',
  `city` varchar(32) NOT NULL DEFAULT '',
  `street` varchar(32) NOT NULL DEFAULT '',
  `state` varchar(16) NOT NULL DEFAULT '',
  `zip` varchar(7) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `skype` varchar(32) NOT NULL DEFAULT '',
  `icq` int(12) DEFAULT NULL,
  `gtalk` varchar(128) NOT NULL DEFAULT '',
  `language_pair` int(3) DEFAULT NULL,
  `payment_prefs_id` int(1) NOT NULL DEFAULT '0',
  `webmoney` varchar(13) NOT NULL DEFAULT '',
  `webmoneyr` varchar(14) NOT NULL DEFAULT '',
  `privat24` varchar(19) NOT NULL DEFAULT '',
  `paypal` varchar(64) NOT NULL DEFAULT '0',
  `yandexmoney` varchar(14) NOT NULL DEFAULT '',
  `trados` int(1) NOT NULL DEFAULT '0',
  `ppw` decimal(4,3) NOT NULL DEFAULT '0.000',
  `pppw` decimal(4,3) NOT NULL DEFAULT '0.000',
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=152 ;

-- --------------------------------------------------------

--
-- Структура таблицы `TranslatorsToRequests`
--

CREATE TABLE IF NOT EXISTS `TranslatorsToRequests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(6) NOT NULL,
  `translator_id` int(6) NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `deadline` datetime NOT NULL,
  `paid` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`,`translator_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 COLLATE=cp1251_bin AUTO_INCREMENT=40 ;
