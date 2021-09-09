/*
SQLyog Ultimate v12.4.3 (32 bit)
MySQL - 10.5.8-MariaDB-1:10.5.8+maria~focal : Database - houses
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`houses` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `houses`;

/*Table structure for table `house` */

DROP TABLE IF EXISTS `house`;

CREATE TABLE `house` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `user` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_house_user` (`user`),
  CONSTRAINT `fk_house_user` FOREIGN KEY (`user`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `house` */

insert  into `house`(`id`,`name`,`user`) values 
(1,'The Brown House',1),
(2,'Phalcon',1),
(3,'La Cucina',2),
(4,'The White House',2);

/*Table structure for table `house_room` */

DROP TABLE IF EXISTS `house_room`;

CREATE TABLE `house_room` (
  `house` int(11) unsigned NOT NULL,
  `room` int(11) unsigned NOT NULL,
  PRIMARY KEY (`house`,`room`),
  KEY `fk_house_room_room` (`room`),
  CONSTRAINT `fk_house_room_house` FOREIGN KEY (`house`) REFERENCES `house` (`id`),
  CONSTRAINT `fk_house_room_room` FOREIGN KEY (`room`) REFERENCES `room` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `house_room` */

insert  into `house_room`(`house`,`room`) values 
(1,1),
(1,7),
(1,11),
(2,1),
(2,3),
(2,5),
(2,8),
(2,9),
(3,3),
(3,5),
(3,6),
(3,7),
(3,10),
(3,11),
(4,1),
(4,3),
(4,5),
(4,7);

/*Table structure for table `room` */

DROP TABLE IF EXISTS `room`;

CREATE TABLE `room` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

/*Data for the table `room` */

insert  into `room`(`id`,`name`) values 
(1,'Office'),
(2,'Bathroom'),
(3,'Living Room'),
(4,'Dining Room'),
(5,'Family Room'),
(6,'Kitchen'),
(7,'Master Bedroom'),
(8,'Guest Bedroom'),
(9,'Workshop'),
(10,'Foyer'),
(11,'Sitting Room'),
(12,'Game Room'),
(13,'Break Room');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` char(60) NOT NULL,
  `class` enum('developer','admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`class`) values 
(1,'test@test.com','$2y$10$eVMyY0xIMW1kR1A0V1ZCRO9SbVhxnvKzzxCSGkdaWH9Ej5LrEpmxG','user'),
(2,'admin@test.com','$2y$10$eVMyY0xIMW1kR1A0V1ZCRO9SbVhxnvKzzxCSGkdaWH9Ej5LrEpmxG','admin');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
