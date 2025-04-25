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
  `gsoassistant_status` enum('Pending','Clicked','Approved') DEFAULT 'Pending',
  `immediatehead_status` enum('Pending','Clicked','Approved') DEFAULT 'Pending',
  `gsodirector_status` enum('Pending','Clicked','Approved') DEFAULT 'Pending',
  `accounting_status` enum('Pending','Clicked','Approved') DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `vrftb` */

insert  into `vrftb`(`id`,`name`,`department`,`activity`,`purpose`,`date_filed`,`budget_no`,`vehicle`,`driver`,`destination`,`departure`,`passenger_count`,`passenger_attachment`,`transportation_cost`,`letter_attachment`,`created_at`,`updated_at`,`gsoassistant_status`,`immediatehead_status`,`gsodirector_status`,`accounting_status`) values ('2025-032101','Aedin Jerome','College','Recollection','School Related','2025-03-21','64815','Bus','dexther','Aefawf',NULL,50,'Letter to self.docx','ejfwafeaw','Letter to self.docx','2025-03-21 18:13:33','2025-04-25 13:18:11','Clicked','Pending','Pending','Pending'),('2025-032120','Maynard','College','Retreat','School Related','2025-03-21','06548','Car','dexther','ASffawefawf',NULL,0,NULL,'Afweafa','Letter to self.docx','2025-03-21 18:28:34','2025-03-25 10:43:05','Pending','Pending','Pending','Pending'),('2025-032121','Aedin','College','Aewffaw','School Related','2025-03-21','2312','Van','dexther','feawfeawef',NULL,NULL,NULL,'awefawefawefa','Letter to self.docx','2025-03-21 18:53:47','2025-03-25 10:43:26','Pending','Pending','Pending','Pending'),('2025-032421','aaewf','College','weafa','Official Business','2025-03-25','37','XYZ123','dexther','wfaweffwaef',NULL,NULL,NULL,'waefawefawfawfe','Letter to self.docx','2025-03-25 10:46:00','2025-03-25 11:15:32','Pending','Pending','Pending','Pending'),('2025-032422','Aedin','Junior High School','awefaw','School Related','2025-03-25','124124','EWAN21','dexther','AfeFewaf',NULL,NULL,NULL,'awefawefawefa','Letter to self.docx','2025-03-25 11:14:45','2025-03-25 11:15:35','Pending','Pending','Pending','Pending'),('2025-032501','Aedin','College','afeaewfewa','Official Business','2025-03-25','54725','EWAN21','noel','awhfeawf',NULL,NULL,NULL,'awfeawefawefawef','Letter to self.docx','2025-03-25 11:18:23','2025-03-25 11:18:23','Pending','Pending','Pending','Pending'),('2025-032502','Aedin','College','awfewaef','Official Business','2025-03-25','24684','EWAN21','leon','waefaefawefawe',NULL,NULL,NULL,'awefawefawefwaef','Letter to self.docx','2025-03-25 11:26:42','2025-03-25 11:26:42','Pending','Pending','Pending','Pending'),('2025-032503','Aedin','College','awfewaef','Official Business','2025-03-25','24684','EWAN21','leon','waefaefawefawe','2025-03-25 06:00:00',NULL,NULL,'awefawefawefwaef','Letter to self.docx','2025-03-25 11:36:30','2025-03-25 11:36:30','Pending','Pending','Pending','Pending'),('2025-032504','Aedin','Grade School','awefawef','Official Business','2025-03-25','23124','EWAN21','dexther','weafafea','2025-03-25 06:00:00',3,NULL,'awefawefawef','Letter to self.docx','2025-03-25 11:39:53','2025-03-25 11:39:53','Pending','Pending','Pending','Pending'),('2025-032505','AedinJRC','College','Recollection','School Related','2025-03-25','6846','EWAN21','tanie','afaewfwefwafeeawfeawef','2025-03-25 06:00:00',5,NULL,'awefawefawefawefawef','Letter to self.docx','2025-03-25 14:29:14','2025-03-25 14:29:14','Pending','Pending','Pending','Pending');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
