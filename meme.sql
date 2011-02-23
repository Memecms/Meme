-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 23 feb, 2011 at 07:37 PM
-- Versione MySQL: 5.1.44
-- Versione PHP: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `meme`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `meme_category`
--

CREATE TABLE `meme_category` (
  `category_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_object_type` bigint(20) unsigned NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_name_url` varchar(255) NOT NULL,
  `category_type` bigint(20) unsigned NOT NULL,
  `category_user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `meme_category`
--

INSERT INTO `meme_category` VALUES(1, 1, 'First Category', 'first_category', 0, 1);
INSERT INTO `meme_category` VALUES(2, 3, 'kljhjhj', 'kljhjhj', 0, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `meme_category_add`
--

CREATE TABLE `meme_category_add` (
  `add_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_type` bigint(20) unsigned NOT NULL,
  `object_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`add_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `meme_category_add`
--

INSERT INTO `meme_category_add` VALUES(1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `meme_db_version`
--

CREATE TABLE `meme_db_version` (
  `db_version` varchar(255) NOT NULL,
  `svn_revision` varchar(255) NOT NULL,
  PRIMARY KEY (`db_version`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `meme_db_version`
--

INSERT INTO `meme_db_version` VALUES('0.1.9', '$Rev: 364 $');

-- --------------------------------------------------------

--
-- Struttura della tabella `meme_gallery`
--

CREATE TABLE `meme_gallery` (
  `gallery_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_title` varchar(255) NOT NULL,
  `gallery_content` text NOT NULL,
  `gallery_user` bigint(20) unsigned NOT NULL,
  `gallery_date` bigint(20) unsigned NOT NULL,
  `gallery_description` varchar(255) NOT NULL,
  `gallery_keywords` varchar(255) NOT NULL,
  `gallery_status` int(2) unsigned NOT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `meme_gallery`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `meme_gallery_add`
--

CREATE TABLE `meme_gallery_add` (
  `add_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_type` bigint(20) unsigned NOT NULL,
  `object_id` bigint(20) unsigned NOT NULL,
  `gallery_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`add_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `meme_gallery_add`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `meme_gallery_album`
--

CREATE TABLE `meme_gallery_album` (
  `album_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `album_title` varchar(255) NOT NULL,
  `album_content` text NOT NULL,
  `album_description` varchar(255) NOT NULL,
  `album_keywords` varchar(255) NOT NULL,
  `album_status` int(2) unsigned NOT NULL,
  `album_user` bigint(20) unsigned NOT NULL,
  `album_date` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`album_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `meme_gallery_album`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `meme_gallery_album_add`
--

CREATE TABLE `meme_gallery_album_add` (
  `add_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_id` bigint(20) unsigned NOT NULL,
  `album_id` bigint(20) unsigned NOT NULL,
  `album_order` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`add_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `meme_gallery_album_add`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `meme_gallery_img`
--

CREATE TABLE `meme_gallery_img` (
  `img_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `img_file` varchar(255) NOT NULL,
  `img_title` varchar(255) NOT NULL,
  `img_link` varchar(255) NOT NULL,
  `img_content` text NOT NULL,
  `img_description` varchar(255) NOT NULL,
  `img_keywords` varchar(255) NOT NULL,
  `img_user` bigint(20) unsigned NOT NULL,
  `img_date` bigint(20) unsigned NOT NULL,
  `img_status` int(2) unsigned NOT NULL,
  PRIMARY KEY (`img_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `meme_gallery_img`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `meme_gallery_img_add`
--

CREATE TABLE `meme_gallery_img_add` (
  `add_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` bigint(20) unsigned NOT NULL,
  `img_id` bigint(20) unsigned NOT NULL,
  `img_order` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`add_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `meme_gallery_img_add`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `meme_pages`
--

CREATE TABLE `meme_pages` (
  `page_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_controller` varchar(255) NOT NULL,
  `page_action` varchar(255) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_date` int(11) unsigned NOT NULL,
  `page_description` varchar(255) NOT NULL,
  `page_keywords` varchar(255) NOT NULL,
  `page_content` text NOT NULL,
  PRIMARY KEY (`page_id`),
  KEY `page_controller` (`page_controller`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dump dei dati per la tabella `meme_pages`
--

INSERT INTO `meme_pages` VALUES(1, 'index', 'index', 'Home Page', 1290705789, 'Try of description', 'KeyWord', '<p>Try of <strong>Home Page</strong></p>\r\n<p><a href="http://www.alessio.it">dfgh</a></p>\r\n<p>&nbsp;</p>\r\n<p>223</p>');

-- --------------------------------------------------------

--
-- Struttura della tabella `meme_posts`
--

CREATE TABLE `meme_posts` (
  `post_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_user` bigint(20) unsigned NOT NULL,
  `post_date` int(11) unsigned NOT NULL,
  `post_status` int(2) unsigned NOT NULL DEFAULT '0',
  `post_title` varchar(255) NOT NULL,
  `post_description` varchar(255) NOT NULL,
  `post_keywords` varchar(255) NOT NULL,
  `post_content` text NOT NULL,
  `post_home` int(1) unsigned NOT NULL DEFAULT '0',
  `post_url` varchar(255) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `meme_posts`
--

INSERT INTO `meme_posts` VALUES(1, 1, 1290873180, 0, 'Hello world!', 'Welcome to Meme Cms.', 'Meme Cms', '<p>Welcome to Meme Cms. This is your first post. Edit or delete it, then start blogging!</p>', 1, 'hello_world_');

-- --------------------------------------------------------

--
-- Struttura della tabella `meme_products`
--

CREATE TABLE `meme_products` (
  `product_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `product_content` text NOT NULL,
  `product_description` varchar(255) NOT NULL,
  `product_keywords` varchar(255) NOT NULL,
  `product_date` int(11) unsigned NOT NULL,
  `product_user` bigint(20) unsigned NOT NULL,
  `product_url` varchar(255) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dump dei dati per la tabella `meme_products`
--

INSERT INTO `meme_products` VALUES(1, 'lkjlkj', '', '', '', 1291158720, 1, '');
INSERT INTO `meme_products` VALUES(2, 'lkjlkj', '', '', '', 1291158840, 1, '');
INSERT INTO `meme_products` VALUES(3, 'lkjlkj', '', '', '', 1291158900, 1, '');
INSERT INTO `meme_products` VALUES(4, 'jkhkhb', '', '', '', 1291159140, 1, 'jkhkhb');
INSERT INTO `meme_products` VALUES(5, 'lkjljkhljkhl', '', '', '', 1291159320, 1, 'lkjljkhljkhl');

-- --------------------------------------------------------

--
-- Struttura della tabella `meme_products_attribute`
--

CREATE TABLE `meme_products_attribute` (
  `products_attribute_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `products_attribute_name` varchar(255) NOT NULL,
  `products_attribute_type` varchar(255) NOT NULL,
  `products_attribute_value1` varchar(255) NOT NULL,
  `products_attribute_value2` varchar(255) NOT NULL,
  PRIMARY KEY (`products_attribute_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `meme_products_attribute`
--

INSERT INTO `meme_products_attribute` VALUES(1, 'try', 'text_field', '', '');
INSERT INTO `meme_products_attribute` VALUES(2, 'description', 'text_field', '', '');
INSERT INTO `meme_products_attribute` VALUES(3, 'adfa', 'text_area', '', '');
INSERT INTO `meme_products_attribute` VALUES(4, 'img', 'media_image', '', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `meme_products_value`
--

CREATE TABLE `meme_products_value` (
  `product_value_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `product_attribute_id` bigint(20) NOT NULL,
  `product_value` mediumtext NOT NULL,
  PRIMARY KEY (`product_value_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dump dei dati per la tabella `meme_products_value`
--

INSERT INTO `meme_products_value` VALUES(1, 1, 1, '');
INSERT INTO `meme_products_value` VALUES(2, 1, 2, '');
INSERT INTO `meme_products_value` VALUES(3, 1, 3, '');
INSERT INTO `meme_products_value` VALUES(4, 2, 1, '');
INSERT INTO `meme_products_value` VALUES(5, 2, 2, '');
INSERT INTO `meme_products_value` VALUES(6, 2, 3, '');
INSERT INTO `meme_products_value` VALUES(7, 3, 1, '');
INSERT INTO `meme_products_value` VALUES(8, 3, 2, '');
INSERT INTO `meme_products_value` VALUES(9, 3, 3, '');
INSERT INTO `meme_products_value` VALUES(10, 4, 1, '');
INSERT INTO `meme_products_value` VALUES(11, 4, 2, '');
INSERT INTO `meme_products_value` VALUES(12, 4, 3, '');
INSERT INTO `meme_products_value` VALUES(13, 5, 1, 'jknljn');
INSERT INTO `meme_products_value` VALUES(14, 5, 2, 'lkjjknlkj');
INSERT INTO `meme_products_value` VALUES(15, 5, 3, '');

-- --------------------------------------------------------

--
-- Struttura della tabella `meme_settings`
--

CREATE TABLE `meme_settings` (
  `setting_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dump dei dati per la tabella `meme_settings`
--

INSERT INTO `meme_settings` VALUES(1, 'sitetitle', 'Meme CMS');
INSERT INTO `meme_settings` VALUES(2, 'address', 'http://memecms.com');
INSERT INTO `meme_settings` VALUES(3, 'emailaddress', 'info@memecms.com');
INSERT INTO `meme_settings` VALUES(4, 'tagline', 'memecms framework RAD');
INSERT INTO `meme_settings` VALUES(5, 'analitycs_id', '');
INSERT INTO `meme_settings` VALUES(6, 'analitycs_username', '');
INSERT INTO `meme_settings` VALUES(7, 'analitycs_password', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `meme_users`
--

CREATE TABLE `meme_users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_firstname` varchar(255) NOT NULL,
  `user_lastname` varchar(255) NOT NULL,
  `user_username` varchar(200) NOT NULL,
  `user_password` varchar(250) NOT NULL,
  `user_role` varchar(10) NOT NULL,
  `user_email` varchar(300) NOT NULL,
  `user_timesigned` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `meme_users`
--

INSERT INTO `meme_users` VALUES(1, '', '', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '6', '', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `meme_users_verification`
--

CREATE TABLE `meme_users_verification` (
  `verification_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `user_key` varchar(255) NOT NULL,
  PRIMARY KEY (`verification_id`),
  UNIQUE KEY `user_key` (`user_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `meme_users_verification`
--

