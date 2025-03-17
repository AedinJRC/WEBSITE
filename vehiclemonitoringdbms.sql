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

/*Table structure for table `passengerstb` */

DROP TABLE IF EXISTS `passengerstb`;

CREATE TABLE `passengerstb` (
  `vrfid` varchar(255) NOT NULL,
  `passenger1` varchar(255) DEFAULT NULL,
  KEY `vrfid` (`vrfid`),
  CONSTRAINT `passengerstb_ibfk_1` FOREIGN KEY (`vrfid`) REFERENCES `vrftb` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `passengerstb` */

/*Table structure for table `usertb` */

DROP TABLE IF EXISTS `usertb`;

CREATE TABLE `usertb` (
  `employeeid` varchar(10) NOT NULL,
  `ppicture` varchar(255) DEFAULT NULL,
  `fname` varchar(30) DEFAULT NULL,
  `lname` varchar(20) DEFAULT NULL,
  `pword` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'User',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`employeeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usertb` */

insert  into `usertb`(`employeeid`,`ppicture`,`fname`,`lname`,`pword`,`role`,`created_at`,`updated_at`) values ('admin',NULL,'AAMA','DVDRC','admin','Admin','2025-03-17 23:52:11','2025-03-17 23:52:46');

/*Table structure for table `vrftb` */

DROP TABLE IF EXISTS `vrftb`;

CREATE TABLE `vrftb` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `activity` varchar(50) DEFAULT NULL,
  `purpose` varchar(20) DEFAULT NULL,
  `date_filed` date DEFAULT NULL,
  `budget_no` varchar(10) DEFAULT NULL,
  `vehicle` varchar(50) DEFAULT NULL,
  `driver` varchar(50) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `departure` datetime DEFAULT NULL,
  `passenger_count` int(10) DEFAULT NULL,
  `passenger_attachment` varchar(255) DEFAULT NULL,
  `transportation_cost` varchar(255) DEFAULT NULL,
  `letter_attachment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `vrftb` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
