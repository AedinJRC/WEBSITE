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

insert  into `carstb`(`plate_number`,`color`,`brand`,`model`,`year_model`,`body_type`,`transmission`,`image`,`created_at`,`registration_from`,`registration_to`,`capacity`,`registration_schedule`) values ('DAM 6747','White','Toyota','Rush',2019,'SUV','Manual','uploads/TOYOTA_RUSH.png','2025-04-03 09:34:55','2025-06-01','2025-07-27',7,'0000-00-00'),('DAV 8382','White','Isuzu','Travis',2021,'Sedan','Manual','uploads/ISUZU_TRAVIS.png','2025-04-03 09:46:20','2025-01-01','2025-02-16',15,'0000-00-00'),('FAD 5799','White','Toyota','Innova',2011,'Sedan','Automatic','uploads/TOYOTA_INNOVA.png','2025-04-03 09:29:53','2025-08-01','2025-09-28',8,'0000-00-00'),('NED 1154','White','Nissan','Urvan',2019,'SUV','Manual','uploads/NISSAN_URVAN.png','2025-04-22 05:54:10','2025-03-01','2025-04-13',30,'0000-00-00'),('TII 979','White','Toyota','Coaster',2011,'Sedan','Manual','uploads/TOYOTA_COASTER.png','2025-04-22 08:00:22','2025-08-01','2025-09-21',40,'0000-00-00'),('TQV 581','White','Toyota','Grandia',2012,'SUV','Manual','uploads/TOYOTA_GRANDIA.png','2025-04-22 05:33:58','2025-12-01','2026-01-18',30,'0000-00-00'),('WEO 163','Black','Honda','Civic',2013,'Sedan','Manual','uploads/HONDA_CIVIC_2013.png','2025-04-03 09:24:29','2025-02-01','2025-03-16',5,'0000-00-00'),('ZTY 362','Red and Gold','Hino','Bus',2009,'Truck','Manual','uploads/HINO_BUS.png','2025-04-22 06:00:41','2025-01-01','2025-02-09',50,'0000-00-00');

/*Table structure for table `checklist_items` */

DROP TABLE IF EXISTS `checklist_items`;

CREATE TABLE `checklist_items` (
  `check_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`check_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `checklist_items` */

/*Table structure for table `departmentstb` */

DROP TABLE IF EXISTS `departmentstb`;

CREATE TABLE `departmentstb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `department` (`department`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `departmentstb` */

insert  into `departmentstb`(`id`,`department`) values (2,'Grade School'),(3,'Junior High School'),(1,'Preschool'),(4,'Senior High School');

/*Table structure for table `employeetb` */

DROP TABLE IF EXISTS `employeetb`;

CREATE TABLE `employeetb` (
  `employeeid` varchar(50) NOT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `mname` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`employeeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `employeetb` */

insert  into `employeetb`(`employeeid`,`lname`,`fname`,`mname`,`created_at`,`updated_at`) values ('1001','García','Juan Carlos','Martínez','2025-05-02 17:03:52','2025-05-02 17:03:52'),('1002','Rodríguez','María Fernanda','López','2025-05-02 17:03:52','2025-05-02 17:03:52'),('1003','Sánchez','José Antonio','Rivera','2025-05-02 17:03:52','2025-05-02 17:03:52'),('1004','Morales','Ana Sofía','Delgado','2025-05-02 17:03:52','2025-05-02 17:03:52'),('1005','Herrera','Luis Miguel','Vargas','2025-05-02 17:03:52','2025-05-02 17:03:52'),('1006','Torres','Camila Isabel','Romero','2025-05-02 17:03:52','2025-05-02 17:03:52'),('1007','Castro','Diego Alejandro','Rojas','2025-05-02 17:03:52','2025-05-02 17:03:52'),('1008','Vargas','Laura Gabriela','Mendoza','2025-05-02 17:03:52','2025-05-02 17:03:52'),('1009','Ruiz','Carlos Eduardo','Navarro','2025-05-02 17:03:52','2025-05-02 17:03:52'),('1010','Delgado','Daniela Mariana','Paredes','2025-05-02 17:03:52','2025-05-02 17:03:52');

/*Table structure for table `passengerstb` */

DROP TABLE IF EXISTS `passengerstb`;

CREATE TABLE `passengerstb` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `vrfid` varchar(20) NOT NULL,
  `passenger_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vrfid` (`vrfid`),
  CONSTRAINT `passengerstb_ibfk_1` FOREIGN KEY (`vrfid`) REFERENCES `vrftb` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `passengerstb` */

insert  into `passengerstb`(`id`,`vrfid`,`passenger_name`) values (12,'2025-042801','Maynard'),(13,'2025-042801','Alyanna'),(14,'2025-042801','Aedin');

/*Table structure for table `usertb` */

DROP TABLE IF EXISTS `usertb`;

CREATE TABLE `usertb` (
  `employeeid` varchar(10) NOT NULL,
  `ppicture` varchar(255) DEFAULT 'default_avatar.png',
  `fname` varchar(30) DEFAULT NULL,
  `lname` varchar(30) DEFAULT NULL,
  `pword` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'User',
  `department` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`employeeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usertb` */

insert  into `usertb`(`employeeid`,`ppicture`,`fname`,`lname`,`pword`,`role`,`department`,`created_at`,`updated_at`) values ('1003','default_avatar.png','Jose Antonio','Sanchez','1234','User','Grade School','2025-05-02 16:52:25','2025-05-02 16:52:25'),('1004','default_avatar.png','Jose','Munoz','1234','User','College','2025-04-28 11:28:45','2025-04-28 11:28:45'),('222032','default_avatar.png','Aedin Jerome','Cabrales','1234','User','Preschool','2025-04-28 09:33:25','2025-04-28 10:26:16'),('accounting','Joshua.jpg','Ms.','Cha','1234','Accountant','College','2025-03-24 00:00:00','2025-04-26 13:43:37'),('admin','default_avatar.png','AAVA','DVDRC','admin','Admin','College','2025-03-18 11:19:39','2025-04-26 13:43:42'),('dexther','Vote.jpg','Dexther','Abuan','1234','Driver','Grade School','2025-03-21 00:00:00','2025-05-02 14:59:21'),('director','fr.png','Noel','Cogasa','1234','Director','College','2025-03-24 00:00:00','2025-04-26 13:43:42'),('immediate','default_avatar.png','Rodel','Magin','1234','Immediate Head','College','2025-04-25 17:24:43','2025-04-26 13:43:42'),('leon','Vote.jpg','Leon','Mandigal','1234','Driver','College','2025-03-21 00:00:00','2025-04-26 13:43:42'),('noel','Vote.jpg','Noel','Gutierrez','1234','Driver','College','2025-03-21 00:00:00','2025-04-26 13:43:42'),('secretary','default_avatar.png','Angelu','Bautista','1234','Secretary','College','2025-04-25 17:33:50','2025-04-26 13:43:42'),('tanie','Vote.jpg','Tanie','Duran','1234','Driver','College','2025-03-21 00:00:00','2025-04-26 13:43:42'),('user','Maynard.png','Maynard','Rodriguez','1234','User','College','2025-03-24 00:00:00','2025-04-26 13:43:42');

/*Table structure for table `vehicle_checklists` */

DROP TABLE IF EXISTS `vehicle_checklists`;

CREATE TABLE `vehicle_checklists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inspected_by` varchar(50) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `check_id` int(11) NOT NULL,
  `status` enum('Good','Fair','Bad') NOT NULL,
  `checked_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `plate_number` (`plate_number`),
  CONSTRAINT `vehicle_checklists_ibfk_1` FOREIGN KEY (`plate_number`) REFERENCES `carstb` (`plate_number`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `vehicle_checklists` */

insert  into `vehicle_checklists`(`id`,`inspected_by`,`plate_number`,`check_id`,`status`,`checked_at`) values (91,'AAVA DVDRC','WEO 163',1,'Good','2025-04-30 13:42:46'),(92,'AAVA DVDRC','WEO 163',2,'Fair','2025-04-30 13:42:46'),(93,'AAVA DVDRC','WEO 163',3,'Fair','2025-04-30 13:42:46'),(94,'AAVA DVDRC','WEO 163',4,'Good','2025-04-30 13:42:46'),(95,'AAVA DVDRC','WEO 163',5,'Good','2025-04-30 13:42:46'),(96,'AAVA DVDRC','WEO 163',6,'Good','2025-04-30 13:42:46'),(97,'AAVA DVDRC','WEO 163',7,'Good','2025-04-30 13:42:46'),(98,'AAVA DVDRC','WEO 163',8,'Good','2025-04-30 13:42:46'),(99,'AAVA DVDRC','WEO 163',9,'Good','2025-04-30 13:42:46'),(100,'AAVA DVDRC','WEO 163',10,'Good','2025-04-30 13:42:46'),(101,'AAVA DVDRC','NED 1154',1,'Good','2025-05-02 14:42:49'),(102,'AAVA DVDRC','NED 1154',2,'Fair','2025-05-02 14:42:49'),(103,'AAVA DVDRC','NED 1154',3,'Fair','2025-05-02 14:42:49'),(104,'AAVA DVDRC','NED 1154',4,'Bad','2025-05-02 14:42:49'),(105,'AAVA DVDRC','NED 1154',5,'Bad','2025-05-02 14:42:49'),(106,'AAVA DVDRC','NED 1154',6,'Fair','2025-05-02 14:42:49'),(107,'AAVA DVDRC','NED 1154',7,'Fair','2025-05-02 14:42:49'),(108,'AAVA DVDRC','NED 1154',8,'Good','2025-05-02 14:42:49'),(109,'AAVA DVDRC','NED 1154',9,'Good','2025-05-02 14:42:49'),(110,'AAVA DVDRC','NED 1154',10,'Fair','2025-05-02 14:42:49');

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
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `letter_attachment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gsoassistant_status` enum('Pending','Seen','Rejected','Approved') DEFAULT 'Pending',
  `immediatehead_status` enum('Pending','Seen','Rejected','Approved') DEFAULT 'Pending',
  `gsodirector_status` enum('Pending','Seen','Rejected','Approved') DEFAULT 'Pending',
  `accounting_status` enum('Pending','Seen','Rejected','Approved') DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `vrftb` */

insert  into `vrftb`(`id`,`name`,`department`,`activity`,`purpose`,`date_filed`,`budget_no`,`vehicle`,`driver`,`destination`,`departure`,`passenger_count`,`passenger_attachment`,`transportation_cost`,`total_cost`,`letter_attachment`,`created_at`,`updated_at`,`gsoassistant_status`,`immediatehead_status`,`gsodirector_status`,`accounting_status`) values ('2025-042801','Jose Munoz','College','Recollection','School Related','2025-04-28','05124','DAM 6747','dexther','Tagaytay','2025-05-05 06:00:00',3,NULL,'Gas\r\n550\r\nTubig \r\n250\r\nKuryente \r\n750.50','1550.50','TLComp-introTL.pdf','2025-04-28 13:18:11','2025-04-29 08:17:06','Approved','Approved','Approved','Approved'),('2025-043001','Jose Munoz','College','College Night','School Related','2025-04-30','05167',NULL,NULL,'Manila','2025-05-07 06:00:00',20,'Letter to self.docx','','0.00','Letter to self.docx','2025-04-30 16:14:20','2025-04-30 16:14:20','Pending','','Pending','Pending');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
