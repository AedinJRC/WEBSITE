/*
SQLyog Ultimate - MySQL GUI v8.22 
MySQL - 5.5.5-10.4.32-MariaDB : Database - vehiclemonitoringdbms
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vehiclemonitoringdbms` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `vehiclemonitoringdbms`;

/*Table structure for table `carstb` */

DROP TABLE IF EXISTS `carstb`;

CREATE TABLE `carstb` (
  `plate_number` varchar(20) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `year_model` int(4) DEFAULT NULL,
  `body_type` varchar(50) DEFAULT NULL,
  `transmission` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `registration_from` date DEFAULT NULL,
  `registration_to` date DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `registration_schedule` date NOT NULL,
  PRIMARY KEY (`plate_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `carstb` */

insert  into `carstb`(`plate_number`,`color`,`brand`,`model`,`year_model`,`body_type`,`transmission`,`image`,`created_at`,`registration_from`,`registration_to`,`capacity`,`registration_schedule`) values ('DAM 6747','White','burat','burat',199,'SUV','Manual','uploads/TOYOTA_RUSH.png','2025-04-03 09:34:55','2025-06-01','2025-07-27',90,'0000-00-00'),('DAV 8382','White','Isuzu','Travis',2021,'Sedan','Manual','uploads/ISUZU_TRAVIS.png','2025-04-03 09:46:20','2025-01-01','2025-02-16',15,'0000-00-00'),('FAD 5799','White','Toyota','Innova',2011,'Sedan','Automatic','uploads/TOYOTA_INNOVA.png','2025-04-03 09:29:53','2025-08-01','2025-09-28',8,'0000-00-00'),('NED 1154','White','Nissan','Urvan',2019,'SUV','Manual','uploads/NISSAN_URVAN.png','2025-04-22 05:54:10','2025-03-01','2025-04-13',30,'0000-00-00'),('TII 979','White','Toyota','Coaster',2011,'Sedan','Manual','uploads/TOYOTA_COASTER.png','2025-04-22 08:00:22','2025-08-01','2025-09-21',40,'0000-00-00'),('TQV 581','White','Toyota','Grandia',2012,'SUV','Manual','uploads/TOYOTA_GRANDIA.png','2025-04-22 05:33:58','2025-12-01','2026-01-18',30,'0000-00-00'),('WEO 163','Black','Honda','Civic',2013,'Sedan','Manual','uploads/HONDA_CIVIC_2013.png','2025-04-03 09:24:29','2025-02-01','2025-03-16',5,'0000-00-00'),('ZTY 362','Red and Gold','HIno','Bus',2009,'Truck','Manual','uploads/HINO_BUS.png','2025-04-22 06:00:41','2025-01-01','2025-02-09',50,'0000-00-00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
