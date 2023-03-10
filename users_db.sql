/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 8.0.27 : Database - users_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`users_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `users_db`;

/*Table structure for table `tbl_products` */

DROP TABLE IF EXISTS `tbl_products`;

CREATE TABLE `tbl_products` (
  `fld_ai_id` int NOT NULL AUTO_INCREMENT,
  `fld_pdt_name` char(30) NOT NULL,
  `fld_price` char(30) NOT NULL,
  `fld_stock` int NOT NULL,
  `fld_pdt_img` text NOT NULL,
  `fld_is_active` enum('0','1') NOT NULL,
  `fld_is_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fld_ai_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tbl_products` */

/*Table structure for table `tbl_users` */

DROP TABLE IF EXISTS `tbl_users`;

CREATE TABLE `tbl_users` (
  `fld_ai_id` int NOT NULL AUTO_INCREMENT,
  `fld_user_name` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fld_name` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fld_email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fld_phone_number` char(12) NOT NULL,
  `fld_address` text NOT NULL,
  `fld_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fld_image` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `fld_is_active` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '1',
  `fld_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fld_ai_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

/*Data for the table `tbl_users` */

insert  into `tbl_users`(`fld_ai_id`,`fld_user_name`,`fld_name`,`fld_email`,`fld_phone_number`,`fld_address`,`fld_password`,`fld_image`,`fld_is_active`,`fld_timestamp`) values 
(1,'Har123','Harry Styles','Harry2@gmail.com','9045612345','Ruby','c7212e2d149bdd150a7deb85c1f47413',NULL,'1','2023-03-08 16:40:43'),
(2,'raja09','Raja Shaw','raja0923@gmail.com','9045612541','Ruby','94de8e93b3b90b647a944e7abcbcf9a1',NULL,'1','2023-03-08 19:25:16'),
(3,'Lisa89','Lisa Mishra','lisa@gmail.com','9045612354','Sealdah','d19ca074a2e3299523a8b48aebc4302c',NULL,'1','2023-03-08 19:58:53'),
(4,'Tiya123','Tiya Dey','tiya@yahoo.com','9045612876','Sealdah','4cbea0375e81f4d12811a754399babd2',NULL,'1','2023-03-08 20:07:17'),
(5,'ghui','Gui nag','ghui23@gmail.com','9045612345','Ruby','753e91286bebce0ddd63dc0bb65bb7b5',NULL,'1','2023-03-09 17:14:54'),
(6,'HAlwa23','halwa','Halwa@gmail.com','9045612345','Ruby','a71d4f7bc0d62c06976de8b6214020ae',NULL,'1','2023-03-10 13:15:42'),
(7,'hiyarw','vc','as12@gmail.com','9045612541','Sealdah','8f1c945f8b783e51bd0a91f6aaec9143',NULL,'1','2023-03-10 13:17:37');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
