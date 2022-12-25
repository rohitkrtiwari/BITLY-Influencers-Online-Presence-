-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 27, 2020 at 06:14 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `users`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `del_UserPosts`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `del_UserPosts` (IN `userid` INT, IN `useremail` VARCHAR(255))  BEGIN
DELETE FROM tbl_userposts WHERE id = userid and email = useremail;
END$$

DROP PROCEDURE IF EXISTS `ins_tblusercredentialstemp`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ins_tblusercredentialstemp` (IN `name` VARCHAR(100), IN `email` VARCHAR(100), IN `password` VARCHAR(100), IN `phone_number` VARCHAR(50), IN `shop_name` VARCHAR(255), IN `shop_link` VARCHAR(255), IN `created` VARCHAR(100), IN `token` VARCHAR(255), IN `varified` VARCHAR(10))  BEGIN

INSERT INTO tblusercredentialstemp (name, email, password, phone_number, shop_name, shop_link, created, token, varified) VALUES (name, email,password,phone_number,shop_name,shop_link,created,token,varified);
END$$

DROP PROCEDURE IF EXISTS `ins_UserPosts`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ins_UserPosts` (IN `email` VARCHAR(100), IN `title` VARCHAR(255), IN `link` VARCHAR(10000), IN `discription` TEXT, IN `time` VARCHAR(100), IN `date` VARCHAR(100), IN `published` VARCHAR(10), IN `imageName` VARCHAR(500))  BEGIN
INSERT INTO tbl_userposts (email, title, link, discription, time, date, published, image) VALUES (email, title, link, discription, time, date, published, imageName);
END$$

DROP PROCEDURE IF EXISTS `ins_UserRegistration`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ins_UserRegistration` (IN `name` VARCHAR(200), IN `email` VARCHAR(100), IN `password` VARCHAR(255), IN `phone_number` VARCHAR(20), IN `site_name` VARCHAR(250), IN `site_link` VARCHAR(500), IN `profile_pic` VARCHAR(1000))  BEGIN
INSERT INTO tblusercredentials (name, email, password, phone_number, site_name, site_link, profile_pic) VALUES (name, email, password, phone_number, site_name, site_link, profile_pic);
END$$

DROP PROCEDURE IF EXISTS `sel_tblusercredentials`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sel_tblusercredentials` (IN `useremail` VARCHAR(100), IN `userpassword` VARCHAR(100))  BEGIN 
SELECT email, name, password, site_name, site_link from tblusercredentials WHERE email = useremail and PASSWORD = userpassword;
END$$

DROP PROCEDURE IF EXISTS `sel_tblUserPosts`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sel_tblUserPosts` (IN `useremail` VARCHAR(255))  BEGIN
SELECT id, link, image, title, discription, views, published from tbl_userposts where email = useremail ORDER by id;
END$$

DROP PROCEDURE IF EXISTS `sel_tblUserPostsbyId`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sel_tblUserPostsbyId` (`userid` INT, `useremail` VARCHAR(255))  BEGIN
SELECT link, image, title, discription from tbl_userposts where email = useremail and id = userid;
END$$

DROP PROCEDURE IF EXISTS `sel_tblUserPostsDraft`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sel_tblUserPostsDraft` (IN `useremail` VARCHAR(255))  BEGIN
SELECT id, link, image, title, discription, views, published from tbl_userposts where email = useremail and published = 0 ORDER by id;
END$$

DROP PROCEDURE IF EXISTS `sel_tblUserPostsPublished`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sel_tblUserPostsPublished` (IN `useremail` VARCHAR(255))  BEGIN
SELECT id, link, image, title, discription, views, published from tbl_userposts where email = useremail and published = 1 ORDER by id;
END$$

DROP PROCEDURE IF EXISTS `sel_UserCredentials`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sel_UserCredentials` (IN `useremail` VARCHAR(255))  SELECT * FROM `tblusercredentials` where email = useremail$$

DROP PROCEDURE IF EXISTS `tbl_ValidateExistingUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `tbl_ValidateExistingUser` (IN `useremail` VARCHAR(100))  BEGIN
SELECT * FROM tblusercredentials WHERE email = useremail;
END$$

DROP PROCEDURE IF EXISTS `udt_tblusercredentials`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `udt_tblusercredentials` (`token` VARCHAR(255))  BEGIN 
UPDATE tblusercredentialstemp set varified = 1 where token = token;
END$$

DROP PROCEDURE IF EXISTS `udt_UserDetails`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `udt_UserDetails` (IN `udt_name` VARCHAR(100), IN `udt_email` VARCHAR(100), IN `udt_site_name` VARCHAR(100), IN `udt_site_link` VARCHAR(100), IN `useremail` VARCHAR(100))  BEGIN 
UPDATE tblusercredentials set name = udt_name, email = udt_email, site_name = udt_site_name, site_link = udt_site_link where email = useremail;
END$$

DROP PROCEDURE IF EXISTS `udt_UserPosts`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `udt_UserPosts` (IN `userid` INT, IN `useremail` VARCHAR(100), IN `PostTitle` VARCHAR(1000), IN `PostLink` VARCHAR(1000), IN `PostDiscription` TEXT, IN `PostTime` VARCHAR(100), IN `PostDate` VARCHAR(100), IN `PostPublished` INT, IN `PostImage` VARCHAR(500))  BEGIN 
UPDATE tbl_userposts set title = PostTitle, link = PostLink, discription = PostDiscription, time = PostTime, date = PostDate, published = PostPublished, image = PostImage where id = userid and email = useremail;
END$$

DROP PROCEDURE IF EXISTS `udt_UserPostsDraft`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `udt_UserPostsDraft` (`userid` INT, `useremail` VARCHAR(100))  BEGIN 
UPDATE tbl_userposts set published = 0 where id = userid and email = useremail;
END$$

DROP PROCEDURE IF EXISTS `udt_UserPostsPublish`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `udt_UserPostsPublish` (`userid` INT, `useremail` VARCHAR(100))  BEGIN 
UPDATE tbl_userposts set published = 1 where id = userid and email = useremail;
END$$

DROP PROCEDURE IF EXISTS `udt_UserProfilePicture`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `udt_UserProfilePicture` (IN `udt_profile_location` VARCHAR(100), IN `useremail` VARCHAR(100))  BEGIN 
UPDATE tblusercredentials set profile_pic = udt_profile_location where email = useremail;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tblusercredentials`
--

DROP TABLE IF EXISTS `tblusercredentials`;
CREATE TABLE IF NOT EXISTS `tblusercredentials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `site_name` varchar(250) NOT NULL,
  `site_link` varchar(500) NOT NULL,
  `profile_pic` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=348 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblusercredentials`
--

INSERT INTO `tblusercredentials` (`id`, `name`, `email`, `password`, `phone_number`, `site_name`, `site_link`, `profile_pic`) VALUES
(347, 'Rohit Tiwari', 'rohitkrtiwari2002@gmail.com', '0a168981dc87a25818456a6b4a5d5ec4', '9910790607', 'CodeWithHarry', 'http://49.50.99.254/', 'uploads/profile_photos/rohitkrtiwari2002@gmail.com_0a9fe77a34d23bd46acb_rohit2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tblusercredentialstemp`
--

DROP TABLE IF EXISTS `tblusercredentialstemp`;
CREATE TABLE IF NOT EXISTS `tblusercredentialstemp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `shop_link` varchar(255) NOT NULL,
  `created` varchar(100) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `token` varchar(255) NOT NULL,
  `varified` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_userposts`
--

DROP TABLE IF EXISTS `tbl_userposts`;
CREATE TABLE IF NOT EXISTS `tbl_userposts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(10000) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `discription` text DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `published` int(11) NOT NULL DEFAULT 0,
  `time` varchar(100) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=183 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_userposts`
--

INSERT INTO `tbl_userposts` (`id`, `link`, `image`, `discription`, `email`, `title`, `views`, `published`, `time`, `date`) VALUES
(172, '', 'uploads/rohitkrtiwari2002@gmail.com_cd07919da72fa6916944_rohit2.jpg', '', 'rohitkrtiwari2002@gmail.com', 'python cheat sheet', NULL, 1, '06:03:30', '26/07/2020'),
(173, '', 'uploads/rohitkrtiwari2002@gmail.com_979e5c08a2536f296833_rohit2.jpg', '', 'rohitkrtiwari2002@gmail.com', 'python cheat sheet', NULL, 0, '10:34:25', '23/07/2020'),
(178, 'Https://facebook.com', '', '', 'rohitkrtiwari2002@gmail.com', '26 july ', NULL, 1, '10:35:44', '26/07/2020'),
(179, 'Https://Google.com/chutiya', 'uploads/rohitkrtiwari2002@gmail.com_b872c4b1aec5d7384fbb_setting menu.png', '', 'rohitkrtiwari2002@gmail.com', 'komal ', NULL, 1, '11:13:38', '27/07/2020'),
(180, 'Https://Google.com/search?q=python_cheet_sheet', 'uploads/rohitkrtiwari2002@gmail.com_422ded4dc0a87806e95d_studentDBMS.jpg', '', 'rohitkrtiwari2002@gmail.com', '27 july', NULL, 1, '11:41:23', '27/07/2020'),
(181, 'Https://Google.com/search?q=python_cheet_sheet', 'uploads/rohitkrtiwari2002@gmail.com_5a71afb8af8b5c1e57e3_setting menu.png', '', 'rohitkrtiwari2002@gmail.com', 'looking for for acction', NULL, 1, '11:41:40', '27/07/2020'),
(182, 'Https://Google.com/search?q=python_cheet_sheet', 'uploads/rohitkrtiwari2002@gmail.com_77fb4246908b9c343d18_studentDBMS.jpg', '', 'rohitkrtiwari2002@gmail.com', 'python 27 july', NULL, 1, '11:42:03', '27/07/2020');

DELIMITER $$
--
-- Events
--
DROP EVENT `RECYCLEtemptable`$$
CREATE DEFINER=`root`@`localhost` EVENT `RECYCLEtemptable` ON SCHEDULE EVERY 1 MINUTE STARTS '2020-07-08 01:11:35' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM tblusercredentialstemp
WHERE created < (CURRENT_TIME - INTERVAL 30 MINUTE)$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
