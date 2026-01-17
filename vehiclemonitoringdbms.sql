/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - vehiclemonitoringdbms
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

insert  into `carstb`(`plate_number`,`color`,`brand`,`model`,`year_model`,`body_type`,`transmission`,`image`,`created_at`,`registration_from`,`registration_to`,`capacity`,`registration_schedule`) values 
('DAM 6747','White','Toyota','Rush',2019,'SUV','Manual','uploads/TOYOTA_RUSH.png','2025-04-03 09:34:55','2025-06-01','2025-07-27',7,'0000-00-00'),
('DAV 8382','White','Isuzu','Travis',2021,'Sedan','Manual','uploads/ISUZU_TRAVIS.png','2025-04-03 09:46:20','2025-01-01','2025-02-16',15,'0000-00-00'),
('FAD 5799','White','Toyota','Innova',2011,'Sedan','Automatic','uploads/TOYOTA_INNOVA.png','2025-04-03 09:29:53','2025-08-01','2025-09-28',8,'0000-00-00'),
('NED 1154','White','Nissan','Urvan',2019,'SUV','Manual','uploads/NISSAN_URVAN.png','2025-04-22 05:54:10','2025-03-01','2025-04-13',30,'0000-00-00'),
('TII 979','White','Toyota','Coaster',2011,'Sedan','Manual','uploads/TOYOTA_COASTER.png','2025-04-22 08:00:22','2025-08-01','2025-09-21',40,'0000-00-00'),
('TQV 581','White','Toyota','Grandia',2012,'SUV','Manual','uploads/TOYOTA_GRANDIA.png','2025-04-22 05:33:58','2025-12-01','2026-01-18',30,'0000-00-00'),
('WEO 163','Black','Honda','Civic',2013,'Sedan','Manual','uploads/HONDA_CIVIC_2013.png','2025-04-03 09:24:29','2025-02-01','2025-03-16',5,'0000-00-00'),
('ZTY 362','Red and Gold','Hino','Bus',2009,'Truck','Manual','uploads/HINO_BUS.png','2025-04-22 06:00:41','2025-01-01','2025-02-09',50,'0000-00-00');

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `departmentstb` */

insert  into `departmentstb`(`id`,`department`) values 
(10,'College'),
(2,'Grade School'),
(3,'Junior High School'),
(1,'Preschool'),
(4,'Senior High School');

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

insert  into `employeetb`(`employeeid`,`lname`,`fname`,`mname`,`created_at`,`updated_at`) values 
('1001','Smith','John','Michael','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1002','Garcia','Maria','Isabel','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1003','Johnson','Robert James','William','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1004','Williams','Mary Ann','Louise','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1005','Brown','David','Edward','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1006','Jones','Jennifer Lynn','Marie','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1007','Miller','Richard','Thomas','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1008','Davis','Patricia Sue','Anne','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1009','Rodriguez','Juan Carlos','Antonio','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1010','Martinez','Ana Sofia','Luisa','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1011','Hernandez','Carlos Alberto','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1012','Lopez','Laura Elizabeth','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1013','Gonzalez','Jose Manuel','Ramon','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1014','Wilson','Barbara Jean','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1015','Anderson','Linda Marie','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1016','Thomas','Christopher Mark','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1017','Taylor','Susan Kay','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1018','Moore','Daniel Joseph','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1019','Jackson','Karen Marie','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1020','Martin','Steven Paul','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1021','Lee','Sarah Elizabeth','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1022','Perez','Miguel Angel','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1023','Thompson','Amanda Grace','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1024','White','Kevin Michael','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1025','Harris','Lisa Michelle','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1026','Sanchez','Francisco Javier','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1028','Clark','Brian Scott','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1029','Ramirez','Veronica Alejandra','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1030','Lewis','Matthew Ryan','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1031','Robinson','Nancy Ellen','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1032','Walker','Jeffrey Thomas','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1033','Young','Stephanie Ann','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1034','Allen','Timothy Wayne','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1035','King','Cynthia Marie','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1036','Wright','Paul Richard','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1037','Scott','Melissa Jane','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1038','Torres','Ricardo Andres','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1039','Nguyen','Kim Loan','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1040','Hill','Christine Marie','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1041','Flores','Guadalupe Maria','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1042','Green','Mark Anthony','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1043','Adams','Rebecca Sue','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1044','Nelson','Eric James','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1045','Baker','Amy Elizabeth','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1046','Rivera','Jorge Luis','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1047','Campbell','Heather Ann','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1048','Mitchell','Jason Robert','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1049','Carter','Emily Rose','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1050','Roberts','Justin Tyler','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1051','Gomez','Sofia Catalina','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1052','Phillips','Andrew Joseph','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1053','Evans','Katherine May','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1054','Turner','Ryan Christopher','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1055','Diaz','Isabella Maria','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1056','Parker','Nicole Lynn','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1057','Cruz','Diego Alejandro','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1058','Edwards','Victoria Anne','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1059','Collins','Patrick Sean','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1060','Reyes','Daniela Fernanda','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1061','Stewart','Megan Elizabeth','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1062','Morris','Nathaniel Scott','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1063','Morales','Valentina Sofia','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1064','Murphy','Sean Patrick','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1065','Cook','Danielle Marie','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1066','Rogers','Benjamin Thomas','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1067','Morgan','Olivia Grace','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1068','Peterson','Jonathan David','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1069','Cooper','Emma Katherine','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1070','Reed','Alexander James','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1071','Bailey','Samantha Rose','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1072','Bell','Joshua Michael','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1073','Murphy','Lauren Elizabeth','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1074','Howard','Jacob William','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1075','Kim','Ji-Hoon','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1076','Ward','Madeline Claire','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1077','Cox','Tyler Joseph','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1078','Diaz','Camila Alejandra','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1079','Richardson','Ashley Nicole','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1080','Wood','Jonathan Paul','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1081','Watson','Elizabeth Ann','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1082','Brooks','Christopher Alan','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1083','Bennett','Victoria Lynn','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1084','Gray','Justin Michael','','2025-05-02 17:48:58','2025-05-02 17:48:58'),
('1085','James','Amanda Marie','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1086','Reyes','Luis Fernando','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1087','Kelly','Erin Margaret','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1088','Sanders','Matthew James','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1089','Price','Sarah Elizabeth','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1090','Barnes','Rachel Anne','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1091','Henderson','Daniel Joseph','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1092','Coleman','Lauren Michelle','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1093','Simmons','Andrew Thomas','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1094','Patterson','Emily Grace','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1095','Jordan','Ryan Christopher','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1096','Reynolds','Michelle Lee','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1097','Hamilton','David Michael','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1098','Graham','Katherine Ann','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1099','Kim','Min-Jae','','2025-05-02 17:48:59','2025-05-02 17:48:59'),
('1100','West','Brandon Scott','','2025-05-02 17:48:59','2025-05-02 17:48:59');

/*Table structure for table `passengerstb` */

DROP TABLE IF EXISTS `passengerstb`;

CREATE TABLE `passengerstb` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `vrfid` varchar(20) NOT NULL,
  `passenger_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vrfid` (`vrfid`),
  CONSTRAINT `passengerstb_ibfk_1` FOREIGN KEY (`vrfid`) REFERENCES `vrftb` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `passengerstb` */

insert  into `passengerstb`(`id`,`vrfid`,`passenger_name`) values 
(12,'2025-042801','Maynard'),
(13,'2025-042801','Alyanna'),
(14,'2025-042801','Aedin'),
(15,'2025-050301','MONIZA, Drick'),
(16,'2025-050301','DELA VEGA, Alyanna Nicole');

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

insert  into `usertb`(`employeeid`,`ppicture`,`fname`,`lname`,`pword`,`role`,`department`,`created_at`,`updated_at`) values 
('1003','default_avatar.png','Jose Antonio','Sanchez','1234','User','Grade School','2025-05-02 16:52:25','2025-05-02 16:52:25'),
('1004','default_avatar.png','Jose','Munoz','1234','User','College','2025-04-28 11:28:45','2025-04-28 11:28:45'),
('1005','6815b4896af7b_1piso back.jpg','David','Brown','1234','User','College','2025-05-03 14:15:37','2025-05-03 14:15:37'),
('1034','681495371ef26_472325126_937136048500586_8419703786201562213_n.jpg','timothy Wayne','Allen','1234','User','Senior High School','2025-05-02 17:49:43','2025-05-02 17:49:43'),
('2001','default_avatar.png','Rodel','Magin','1234','Immediate Head','College','2025-04-25 17:24:43','2025-05-15 01:26:14'),
('2002','default_avatar.png','Angelu','Bautista','1234','Secretary','College','2025-04-25 17:33:50','2025-05-15 01:26:26'),
('2003','Joshua.jpg','Ms.','Cha','1234','Accountant','College','2025-03-24 00:00:00','2025-05-15 01:26:03'),
('2004','fr.png','Noel','Cogasa','1234','Director','College','2025-03-24 00:00:00','2025-05-15 01:27:37'),
('222032','default_avatar.png','Aedin Jerome','Cabrales','1234','User','Preschool','2025-04-28 09:33:25','2025-04-28 10:26:16'),
('admin','default_avatar.png','AAVA','DVDRC','admin','Admin','College','2025-03-18 11:19:39','2025-04-26 13:43:42'),
('dexther','Vote.jpg','Dexther','Abuan','1234','Driver','Grade School','2025-03-21 00:00:00','2025-05-02 14:59:21'),
('leon','Vote.jpg','Leon','Mandigal','1234','Driver','College','2025-03-21 00:00:00','2025-04-26 13:43:42'),
('noel','Vote.jpg','Noel','Gutierrez','1234','Driver','College','2025-03-21 00:00:00','2025-04-26 13:43:42'),
('tanie','Vote.jpg','Tanie','Duran','1234','Driver','College','2025-03-21 00:00:00','2025-04-26 13:43:42'),
('user','Maynard.png','Maynard','Rodriguez','1234','User','College','2025-03-24 00:00:00','2025-04-26 13:43:42');

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

insert  into `vehicle_checklists`(`id`,`inspected_by`,`plate_number`,`check_id`,`status`,`checked_at`) values 
(91,'AAVA DVDRC','WEO 163',1,'Good','2025-04-30 13:42:46'),
(92,'AAVA DVDRC','WEO 163',2,'Fair','2025-04-30 13:42:46'),
(93,'AAVA DVDRC','WEO 163',3,'Fair','2025-04-30 13:42:46'),
(94,'AAVA DVDRC','WEO 163',4,'Good','2025-04-30 13:42:46'),
(95,'AAVA DVDRC','WEO 163',5,'Good','2025-04-30 13:42:46'),
(96,'AAVA DVDRC','WEO 163',6,'Good','2025-04-30 13:42:46'),
(97,'AAVA DVDRC','WEO 163',7,'Good','2025-04-30 13:42:46'),
(98,'AAVA DVDRC','WEO 163',8,'Good','2025-04-30 13:42:46'),
(99,'AAVA DVDRC','WEO 163',9,'Good','2025-04-30 13:42:46'),
(100,'AAVA DVDRC','WEO 163',10,'Good','2025-04-30 13:42:46'),
(101,'AAVA DVDRC','NED 1154',1,'Good','2025-05-02 14:42:49'),
(102,'AAVA DVDRC','NED 1154',2,'Fair','2025-05-02 14:42:49'),
(103,'AAVA DVDRC','NED 1154',3,'Fair','2025-05-02 14:42:49'),
(104,'AAVA DVDRC','NED 1154',4,'Bad','2025-05-02 14:42:49'),
(105,'AAVA DVDRC','NED 1154',5,'Bad','2025-05-02 14:42:49'),
(106,'AAVA DVDRC','NED 1154',6,'Fair','2025-05-02 14:42:49'),
(107,'AAVA DVDRC','NED 1154',7,'Fair','2025-05-02 14:42:49'),
(108,'AAVA DVDRC','NED 1154',8,'Good','2025-05-02 14:42:49'),
(109,'AAVA DVDRC','NED 1154',9,'Good','2025-05-02 14:42:49'),
(110,'AAVA DVDRC','NED 1154',10,'Fair','2025-05-02 14:42:49');

/*Table structure for table `vrf_detailstb` */

DROP TABLE IF EXISTS `vrf_detailstb`;

CREATE TABLE `vrf_detailstb` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `vrf_id` varchar(20) DEFAULT NULL,
  `vehicle` varchar(50) DEFAULT NULL,
  `driver` varchar(50) DEFAULT NULL,
  `departure` datetime DEFAULT NULL,
  `return` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_vrfdetails` (`vrf_id`),
  CONSTRAINT `fk_vrfdetails` FOREIGN KEY (`vrf_id`) REFERENCES `vrftb` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `vrf_detailstb` */

insert  into `vrf_detailstb`(`id`,`vrf_id`,`vehicle`,`driver`,`departure`,`return`,`created_at`,`updated_at`) values 
(1,'2026-011301','Toyota Rush','Mr. Dexther Abuan','2026-01-20 06:00:00','2026-01-20 09:00:00','2026-01-17 16:13:42','2026-01-17 16:17:53'),
(2,'2026-011302','Toyota Rush','Mr. Dexther Abuan','2025-05-05 06:00:00','2025-05-05 06:00:00','2026-01-17 16:17:14','2026-01-17 16:17:34');

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
  `destination` varchar(255) DEFAULT NULL,
  `passenger_count` int(10) DEFAULT NULL,
  `passenger_attachment` varchar(255) DEFAULT NULL,
  `transportation_cost` varchar(255) DEFAULT NULL,
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `letter_attachment` varchar(255) DEFAULT NULL,
  `gsoassistant_status` enum('Pending','Seen','Rejected','Approved') DEFAULT 'Pending',
  `immediatehead_status` enum('Pending','Seen','Rejected','Approved') DEFAULT 'Pending',
  `gsodirector_status` enum('Pending','Seen','Rejected','Approved') DEFAULT 'Pending',
  `accounting_status` enum('Pending','Seen','Rejected','Approved') DEFAULT 'Pending',
  `user_cancelled` enum('Yes','No') DEFAULT 'No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `vrftb` */

insert  into `vrftb`(`id`,`name`,`department`,`activity`,`purpose`,`date_filed`,`budget_no`,`destination`,`passenger_count`,`passenger_attachment`,`transportation_cost`,`total_cost`,`letter_attachment`,`gsoassistant_status`,`immediatehead_status`,`gsodirector_status`,`accounting_status`,`user_cancelled`,`created_at`,`updated_at`) values 
('2025-042801','Jose Munoz','College','Recollection','School Related','2025-04-28','05124','Tagaytay',3,NULL,'Gas\r\n550\r\nTubig \r\n250\r\nKuryente \r\n750.50',1550.50,'TLComp-introTL.pdf','Approved','Approved','Approved','Approved','No','2025-04-28 13:18:11','2025-04-29 08:17:06'),
('2025-043001','Jose Munoz','College','College Night','School Related','2025-04-30','05167','Manila',20,'Letter to self.docx','200 Tubig\r\n4000 Gas',212312.00,'Letter to self.docx','Approved','Approved','Pending','Approved','No','2025-04-30 16:14:20','2025-05-15 06:25:16'),
('2025-050301','David Brown','College','Seminar','Official Business','2025-05-03','05125','Manila Zoo',2,NULL,'efjefajefa;',2000.00,'Intro_Algorithm.pdf','Approved','Approved','Approved','Approved','No','2025-05-03 14:29:22','2025-05-03 14:37:49'),
('2026-011301','Abuan, Dexther','College','aa','School Related','2026-01-13','33','bb',0,NULL,'',0.00,NULL,'Pending','Pending','Pending','Pending','No','2026-01-13 11:43:32','2026-01-13 11:43:32'),
('2026-011302','Abuan, Dexther','College','ss','School Related','2026-01-13','3','ss',0,NULL,'',88.00,NULL,'Approved','Approved','Seen','Approved','No','2026-01-13 11:49:17','2026-01-13 13:06:35');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
