# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.5-10.1.19-MariaDB)
# Database: ChnLibrary
# Generation Time: 2017-05-08 17:57:24 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table USER
# ------------------------------------------------------------

DROP TABLE IF EXISTS `USER`;

CREATE TABLE `USER` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PID` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TYPE` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TITLE` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CHN_NAME` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ENG_NAME` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PHONE` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `EMAIL` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `LOGON` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PASSWORD` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ADMIN` int(2) NOT NULL DEFAULT '0',
  `ACTIVE` int(2) NOT NULL DEFAULT '1',
  `ADDED_DATE` datetime DEFAULT NULL,
  `UPDATED_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `USER` WRITE;
/*!40000 ALTER TABLE `USER` DISABLE KEYS */;

INSERT INTO `USER` (`ID`, `PID`, `TYPE`, `TITLE`, `CHN_NAME`, `ENG_NAME`, `PHONE`, `EMAIL`, `LOGON`, `PASSWORD`, `ADMIN`, `ACTIVE`, `ADDED_DATE`, `UPDATED_DATE`)
VALUES
	(1,'B7213456','Driver License','Mr112','Peter 吉','Peter Ji','8182822969','qji@yahoo.com','pji','12345',1,1,NULL,NULL),
	(2,'S12345','Student ID','Ms','michelle','michelle cheng','123423','qji@yahoo.com','','',0,1,NULL,'2017-05-08 17:23:28'),
	(3,'F12345','Faculty ID','','','Peter Shui','626-123-0770','','peter','peter',1,1,NULL,NULL),
	(4,'S-1111','Student ID',NULL,'','','','','','',0,0,'2017-05-08 17:23:56',NULL);

/*!40000 ALTER TABLE `USER` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
