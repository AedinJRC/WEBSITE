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

/*Table structure for table `cars` */

DROP TABLE IF EXISTS `cars`;

CREATE TABLE `cars` (
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

/*Data for the table `cars` */

insert  into `cars`(`plate_number`,`color`,`brand`,`model`,`year_model`,`body_type`,`transmission`,`image`,`created_at`,`registration_from`,`registration_to`,`capacity`,`registration_schedule`) values ('A93600','violet','Toyota','Corolla',2013,'SUV','Automatic','uploads/2013_Honda_Civic_LX_Sedan.webp','2025-03-13 07:34:42',NULL,NULL,30,'2025-03-21'),('BURAT112','violet','Ford','Focus',1904,'SUV','Automatic','uploads/2013_Honda_Civic_LX_Sedan.webp','2025-03-18 02:27:44','2025-03-22','2025-05-01',8,'0000-00-00'),('DA93600','black','Nissan','Altima',2013,'SUV','Automatic','uploads/2013_Honda_Civic_LX_Sedan.webp','2025-03-13 07:18:12',NULL,NULL,5,'2025-03-21'),('EWAN21','LOL','Champs Line 200','LOL',1902,'Hatchback','Manual','uploads/2013_Honda_Civic_LX_Sedan.webp','2025-03-18 02:28:58','2025-03-15','2025-05-01',8,'0000-00-00'),('JEEPNEY','wala naa','Nokia','LOL',1902,'Sedan','Manual','uploads/2013_Honda_Civic_LX_Sedan.webp','2025-03-18 03:14:21','2025-03-22','2025-04-30',10,'0000-00-00'),('TARUB','black','Ford','Focus',2013,'Sedan','Automatic','uploads/2013_Honda_Civic_LX_Sedan.webp','2025-03-13 07:41:55','2025-03-21','2025-04-16',60,'0000-00-00'),('XYZ123','violet','Honda','Civic',2013,'SUV','Automatic','uploads/2013_Honda_Civic_LX_Sedan.webp','2025-03-13 08:29:19','2025-03-22','2025-04-29',6,'0000-00-00');

/*Table structure for table `departmentstb` */

DROP TABLE IF EXISTS `departmentstb`;

CREATE TABLE `departmentstb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `department` (`department`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `departmentstb` */

insert  into `departmentstb`(`id`,`department`) values (5,'College'),(2,'Grade School'),(3,'Junior High School'),(1,'Preschool'),(4,'Senior High School');

/*Table structure for table `passengerstb` */

DROP TABLE IF EXISTS `passengerstb`;

CREATE TABLE `passengerstb` (
  `id` varchar(20) NOT NULL,
  `vrfid` varchar(20) NOT NULL,
  `passenger_name` varchar(255) DEFAULT NULL,
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

insert  into `usertb`(`employeeid`,`ppicture`,`fname`,`lname`,`pword`,`role`,`created_at`,`updated_at`) values ('admin','Vote.jpg','AAVA','DVDRC','admin','Admin','2025-03-18 11:19:39','2025-03-20 15:32:01');

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
