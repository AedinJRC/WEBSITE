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

insert  into `carstb`(`plate_number`,`color`,`brand`,`model`,`year_model`,`body_type`,`transmission`,`image`,`created_at`,`registration_from`,`registration_to`,`capacity`,`registration_schedule`) values ('DAM 6747','burat','burat','burat',199,'SUV','Manual','uploads/TOYOTA_RUSH.png','2025-04-03 09:34:55','2025-06-01','2025-07-27',90,'0000-00-00'),('DAV 8382','White','Isuzu','Travis',2021,'Sedan','Manual','uploads/ISUZU_TRAVIS.png','2025-04-03 09:46:20','2025-01-01','2025-02-16',15,'0000-00-00'),('FAD 5799','White','Toyota','Innova',2011,'Sedan','Automatic','uploads/TOYOTA_INNOVA.png','2025-04-03 09:29:53','2025-08-01','2025-09-28',8,'0000-00-00'),('NED 1154','White','Nissan','Urvan',2019,'SUV','Manual','uploads/NISSAN_URVAN.png','2025-04-22 05:54:10','2025-03-01','2025-04-13',30,'0000-00-00'),('TII 979','White','Toyota','Coaster',2011,'Sedan','Manual','uploads/TOYOTA_COASTER.png','2025-04-22 08:00:22','2025-08-01','2025-09-21',40,'0000-00-00'),('TQV 581','White','Toyota','Grandia',2012,'SUV','Manual','uploads/TOYOTA_GRANDIA.png','2025-04-22 05:33:58','2025-12-01','2026-01-18',30,'0000-00-00'),('WEO 163','Black','Honda','Civic',2013,'Sedan','Manual','uploads/HONDA_CIVIC_2013.png','2025-04-03 09:24:29','2025-02-01','2025-03-16',5,'0000-00-00'),('ZTY 362','Red and Gold','HIno','Bus',2009,'Truck','Manual','uploads/HINO_BUS.png','2025-04-22 06:00:41','2025-01-01','2025-02-09',50,'0000-00-00');

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
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `vrfid` varchar(20) NOT NULL,
  `passenger_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vrfid` (`vrfid`),
  CONSTRAINT `passengerstb_ibfk_1` FOREIGN KEY (`vrfid`) REFERENCES `vrftb` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `passengerstb` */

insert  into `passengerstb`(`id`,`vrfid`,`passenger_name`) values (1,'2025-032502','Aawefawef'),(2,'2025-032502','hsghsergse'),(3,'2025-032504','Aedin'),(4,'2025-032504','Maynard'),(5,'2025-032504','Yanna'),(6,'2025-032505','Sara'),(7,'2025-032505','Wen'),(8,'2025-032505','Joshua'),(9,'2025-032505','Dinglasan'),(10,'2025-032505','Justine');

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

insert  into `usertb`(`employeeid`,`ppicture`,`fname`,`lname`,`pword`,`role`,`created_at`,`updated_at`) values ('2022455','Maynard.png','Maynard','Rodriguez','1234','User','2025-03-24 00:00:00','2025-03-24 00:00:00'),('acc','Joshua.jpg','Ms.','Cha','1234','Accountant','2025-03-24 00:00:00','2025-03-25 09:54:38'),('admin','Vote.jpg','AAVA','DVDRC','admin','Admin','2025-03-18 11:19:39','2025-03-20 15:32:01'),('dexther','Vote.jpg','Dexther','Abuan','1234','Driver','2025-03-21 00:00:00','2025-03-21 00:00:00'),('fr','fr.png','Noel','Cogasa','1234','Director','2025-03-24 00:00:00','2025-03-25 09:40:51'),('leon','Vote.jpg','Leon','Mandigal','1234','Driver','2025-03-21 00:00:00','2025-03-21 17:26:28'),('noel','Vote.jpg','Noel','Gutierrez','1234','Driver','2025-03-21 00:00:00','2025-03-21 00:00:00'),('tanie','Vote.jpg','Tanie','Duran','1234','Driver','2025-03-21 00:00:00','2025-03-21 17:36:06');

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

insert  into `vrftb`(`id`,`name`,`department`,`activity`,`purpose`,`date_filed`,`budget_no`,`vehicle`,`driver`,`destination`,`departure`,`passenger_count`,`passenger_attachment`,`transportation_cost`,`letter_attachment`,`created_at`,`updated_at`) values ('2025-032101','Aedin Jerome','College','Recollection','School Related','2025-03-21','64815','Bus','dexther','Aefawf',NULL,50,'Letter to self.docx','ejfwafeaw','Letter to self.docx','2025-03-21 18:13:33','2025-03-21 18:25:06'),('2025-032120','Maynard','College','Retreat','School Related','2025-03-21','06548','Car','dexther','ASffawefawf',NULL,0,NULL,'Afweafa','Letter to self.docx','2025-03-21 18:28:34','2025-03-25 10:43:05'),('2025-032121','Aedin','College','Aewffaw','School Related','2025-03-21','2312','Van','dexther','feawfeawef',NULL,NULL,NULL,'awefawefawefa','Letter to self.docx','2025-03-21 18:53:47','2025-03-25 10:43:26'),('2025-032421','aaewf','College','weafa','Official Business','2025-03-25','37','XYZ123','dexther','wfaweffwaef',NULL,NULL,NULL,'waefawefawfawfe','Letter to self.docx','2025-03-25 10:46:00','2025-03-25 11:15:32'),('2025-032422','Aedin','Junior High School','awefaw','School Related','2025-03-25','124124','EWAN21','dexther','AfeFewaf',NULL,NULL,NULL,'awefawefawefa','Letter to self.docx','2025-03-25 11:14:45','2025-03-25 11:15:35'),('2025-032501','Aedin','College','afeaewfewa','Official Business','2025-03-25','54725','EWAN21','noel','awhfeawf',NULL,NULL,NULL,'awfeawefawefawef','Letter to self.docx','2025-03-25 11:18:23','2025-03-25 11:18:23'),('2025-032502','Aedin','College','awfewaef','Official Business','2025-03-25','24684','EWAN21','leon','waefaefawefawe',NULL,NULL,NULL,'awefawefawefwaef','Letter to self.docx','2025-03-25 11:26:42','2025-03-25 11:26:42'),('2025-032503','Aedin','College','awfewaef','Official Business','2025-03-25','24684','EWAN21','leon','waefaefawefawe','2025-03-25 06:00:00',NULL,NULL,'awefawefawefwaef','Letter to self.docx','2025-03-25 11:36:30','2025-03-25 11:36:30'),('2025-032504','Aedin','Grade School','awefawef','Official Business','2025-03-25','23124','EWAN21','dexther','weafafea','2025-03-25 06:00:00',3,NULL,'awefawefawef','Letter to self.docx','2025-03-25 11:39:53','2025-03-25 11:39:53'),('2025-032505','AedinJRC','College','Recollection','School Related','2025-03-25','6846','EWAN21','tanie','afaewfwefwafeeawfeawef','2025-03-25 06:00:00',5,NULL,'awefawefawefawefawef','Letter to self.docx','2025-03-25 14:29:14','2025-03-25 14:29:14');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
