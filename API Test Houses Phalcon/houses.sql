CREATE DATABASE IF NOT EXISTS `houses`;

USE `houses`;

/*Table structure for table `house` */

DROP TABLE IF EXISTS `house`;

CREATE TABLE `house` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `user` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_house_user` (`user`)
) ENGINE=INNODB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `house` */

INSERT  INTO `house`(`id`,`name`,`user`) VALUES 
(1,'The Brown House',1),
(2,'Phalcon',1),
(3,'La Cucina',2),
(4,'The White House',2);

/*Table structure for table `house_room` */

DROP TABLE IF EXISTS `house_room`;

CREATE TABLE `house_room` (
  `house` INT(11) UNSIGNED NOT NULL,
  `room` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`house`,`room`),
  KEY `fk_house_room_room` (`room`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;

/*Data for the table `house_room` */

INSERT  INTO `house_room`(`house`,`room`) VALUES 
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
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

/*Data for the table `room` */

INSERT  INTO `room`(`id`,`name`) VALUES 
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
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(128) NOT NULL,
  `password` CHAR(60) NOT NULL,
  `class` ENUM('developer','admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user` */

INSERT  INTO `user`(`id`,`username`,`password`,`class`) VALUES 
(1,'test@test.com','$2y$10$eVMyY0xIMW1kR1A0V1ZCRO9SbVhxnvKzzxCSGkdaWH9Ej5LrEpmxG','user'),
(2,'admin@test.com','$2y$10$eVMyY0xIMW1kR1A0V1ZCRO9SbVhxnvKzzxCSGkdaWH9Ej5LrEpmxG','admin');