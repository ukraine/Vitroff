-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июн 29 2016 г., 06:00
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
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
  `company_name` varchar(200) NOT NULL,
  `iscompany` int(1) NOT NULL,
  `vat` varchar(64) NOT NULL,
  `firstname` varchar(64) NOT NULL DEFAULT '',
  `lastname` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `workphone` varchar(20) NOT NULL DEFAULT '',
  `cellphone` varchar(20) NOT NULL DEFAULT '',
  `fax` varchar(20) NOT NULL DEFAULT '',
  `url` varchar(128) NOT NULL,
  `street` varchar(128) NOT NULL DEFAULT '',
  `citystatezip` varchar(255) NOT NULL DEFAULT '',
  `country` varchar(64) DEFAULT NULL,
  `comment` text NOT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `registrationtime` datetime DEFAULT '0000-00-00 00:00:00',
  `payment_prefs_id` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `email_2` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=745 ;

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `request_id` int(6) NOT NULL DEFAULT '0',
  `translationtype_id` int(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=937 ;


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
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=62 ;

--
-- Дамп данных таблицы `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`, `priority`, `visibility`, `registrationtime`) VALUES
(1, 'EN', 'English', 0, '', '0000-00-00 00:00:00'),
(2, 'RU', 'Russian', 0, '', '0000-00-00 00:00:00'),
(3, 'UK', 'Ukrainian', 0, '', '0000-00-00 00:00:00'),
(4, 'CS', 'Czech', 0, '', '0000-00-00 00:00:00'),
(5, 'AZ', 'Azerbaijani', 0, '', '0000-00-00 00:00:00'),
(6, 'ES', 'Spanish', 0, '', '0000-00-00 00:00:00'),

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `pairs` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `source` int(3) NOT NULL DEFAULT '0',
  `target` int(3) NOT NULL DEFAULT '0',
  `visibility` int(1) NOT NULL DEFAULT '0',
  `registrationtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=96 ;

--
-- Дамп данных таблицы `pairs`
--

INSERT INTO `pairs` (`id`, `source`, `target`, `visibility`, `registrationtime`) VALUES
(1, 1, 3, 0, '0000-00-00 00:00:00'),
(2, 1, 2, 0, '0000-00-00 00:00:00'),
(3, 1, 7, 0, '0000-00-00 00:00:00'),
(4, 1, 4, 0, '0000-00-00 00:00:00'),
(5, 1, 9, 0, '0000-00-00 00:00:00'),
(6, 1, 10, 0, '0000-00-00 00:00:00');

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
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=15 ;

--
-- Дамп данных таблицы `paymenttypes`
--

INSERT INTO `paymenttypes` (`id`, `name`, `url`, `fee`, `registrationtime`) VALUES
(1, 'MoneyBookers', 'https://www.moneybookers.com/app/login.pl', '0.020', '0000-00-00 00:00:00'),
(2, 'PayPal', 'https://www.paypal.com/us/cgi-bin/webscr?cmd=_send-money', '0.030', '0000-00-00 00:00:00'),
(3, 'YandexMoney', 'http://money.yandex.ru/shop.xml?scid=767', '0.005', '0000-00-00 00:00:00');

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
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=58 ;

--
-- Дамп данных таблицы `rates`
--

INSERT INTO `rates` (`id`, `name`, `ppw`, `fixedrate`, `registrationtime`) VALUES
(1, 'Personal correspondence', '0.0600', 0, '0000-00-00 00:00:00'),
(2, 'Business correspondence', '0.0600', 0, '0000-00-00 00:00:00'),
(3, 'Advertising materials', '0.0900', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------


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
  `source_text` text CHARACTER SET cp1251 NOT NULL,
  `translation` text CHARACTER SET cp1251 NOT NULL,
  `instructions` text NOT NULL,
  `comments` text NOT NULL,
  `problem_part` varchar(255) CHARACTER SET cp1251 NOT NULL DEFAULT '',
  `customer_id` int(6) NOT NULL DEFAULT '0',
  `customer_project_id` varchar(32) CHARACTER SET cp1251 DEFAULT NULL,
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
  `delivery` varchar(5) CHARACTER SET cp1251 NOT NULL DEFAULT '0',
  `amountpaid` decimal(7,2) DEFAULT '0.00',
  `estimatedprice` decimal(7,2) DEFAULT '0.00',
  `discount` decimal(4,2) DEFAULT NULL,
  `currency` char(3) CHARACTER SET cp1251 NOT NULL DEFAULT '',
  `ppw` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `baseppw` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `ppwt` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `privacy` int(1) NOT NULL DEFAULT '1',
  `wordcount` int(6) NOT NULL DEFAULT '0',
  `characters` int(10) NOT NULL DEFAULT '0',
  `registrationtime` datetime DEFAULT '0000-00-00 00:00:00',
  `atlanticsilicon` int(1) DEFAULT '0',
  `status_id` int(1) NOT NULL DEFAULT '0',
  `transaction_id` varchar(128) CHARACTER SET cp1251 NOT NULL,
  `postal_tracking_number` varchar(128) CHARACTER SET cp1251 NOT NULL,
  `isprojectactive` int(1) DEFAULT '1',
  `istranslationmemory` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=1667 ;


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
  `wire_transfer_fee` int(3) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `defaultPricePerHour` int(3) DEFAULT NULL,
  `error_level` varchar(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `siteurl`, `softurl`, `softname`, `admin_name`, `company_name`, `mail_address`, `phone`, `fax`, `sitename`, `itemsonpage`, `minimal_request_price`, `email`, `beneficiary`, `benaccount`, `benbank`, `routenumber`, `swift`, `interbank`, `corracount`, `wire_transfer_fee`, `currency`, `defaultPricePerHour`, `error_level`) VALUES
(1, 'http://www.1translate.com', 'http://www.karpaty-design.com', 'MAD-CMS', 'Yuriy Yatsiv', '', '', '', '', 'Virtual Translation Office', 10, 0, '', '', '', '', '', '', '', 'AMEX 751073', 50, 'USD', 50, 'E_ALL');

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
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `staff`
--

INSERT INTO `staff` (`id`, `lastname`, `firstname`, `position`, `email`, `phone`, `login`, `password`, `registrationtime`) VALUES
(1, 'Yatsiv', 'Yuriy', 'Project Manager', 'yy@1translate.com', '+7 (499) 501-654-1', 'admin', '6e60d68357b21b80d2cdc0e5adc530c1', '0000-00-00 00:00:00');

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

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id`, `name`, `description`, `priority`, `registrationtime`) VALUES
(1, 'New', 'A new request has just arrived.', 0, '0000-00-00 00:00:00'),
(2, 'Waiting for payment', 'Waiting for a payment or a down-payment from customer in order to start the translation', 0, '0000-00-00 00:00:00'),
(3, 'Payment received', 'A payment has been received and now it&#39;s time to process the translation', 0, '0000-00-00 00:00:00'),
(4, 'Waiting for translator', 'Waiting for a confirmation from a translator, assigned to a specified request', 0, '0000-00-00 00:00:00'),
(5, 'Translating', 'A translator is performing a translation', 0, '0000-00-00 00:00:00'),
(6, 'Translated', 'Translation is ready and now it&#39;s time to send a translated document either to customer or assigned proofreader', 0, '0000-00-00 00:00:00'),
(7, 'Waiting for proofreadear', 'Waiting for a confirmation from a proofreader, assigned to a specified request', 0, '0000-00-00 00:00:00'),
(8, 'Proofreading', 'A proofreader is performing a proofreading', 0, '0000-00-00 00:00:00'),
(9, 'Proofread done', 'Translation is ready and now it&#39;s time to send a translated document to customer', 0, '0000-00-00 00:00:00'),
(10, 'Completed', 'A request has been completed. A translation has been sent to a customer', 0, '0000-00-00 00:00:00'),
(11, 'Cancelled', 'A request has been cancelled either by customer or manager', 0, '0000-00-00 00:00:00'),
(12, 'Refunded', 'A refund has been issued to the customer', 0, '0000-00-00 00:00:00');

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

--
-- Дамп данных таблицы `templates`
--

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
  `yandexmoney` varchar(18) DEFAULT NULL,
  `trados` int(1) NOT NULL DEFAULT '0',
  `ppw` decimal(4,3) NOT NULL DEFAULT '0.000',
  `pppw` decimal(4,3) NOT NULL DEFAULT '0.000',
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=292 ;

--
-- Дамп данных таблицы `translators`
--
--
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
