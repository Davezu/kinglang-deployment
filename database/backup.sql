-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: kinglang_booking
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_notifications`
--

LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
INSERT INTO `admin_notifications` VALUES (96,'booking_request','New booking request from Spike Spiegel to Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines',234,1,'2025-04-22 21:27:06'),(97,'booking_request','New booking request from Spike Spiegel to Apayao Provincial Capitol, Luna, Apayao, Philippines',236,1,'2025-04-22 22:50:08'),(98,'booking_request','New booking request from Spike Spiegel to Apayao Provincial Capitol, Luna, Apayao, Philippines',179,1,'2025-04-22 22:50:26'),(99,'booking_request','New booking request from Spike Spiegel to Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines',240,1,'2025-04-22 23:21:49'),(100,'booking_request','New booking request from Spike Spiegel to Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines',242,1,'2025-04-23 18:26:20'),(101,'booking_request','New booking request from Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',244,1,'2025-04-23 18:39:46'),(102,'booking_request','New booking request from Spike Spiegel to Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines',246,1,'2025-04-23 18:54:07'),(103,'booking_request','New booking request from Spike Spiegel to Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines',248,1,'2025-04-23 18:57:10'),(104,'booking_request','New booking request from Spike Spiegel to Elijah Hotel and Residences, Dasmariñas, Cavite, Philippines',250,1,'2025-04-23 19:05:52'),(105,'booking_request','New booking request from Spike Spiegel to Elijah Hotel and Residences, Dasmariñas, Cavite, Philippines',252,1,'2025-04-23 19:07:18'),(106,'booking_request','New booking request from Spike Spiegel to Quiapo Church, Plaza Miranda, Quiapo, Manila, Metro Manila, Philippines',254,1,'2025-04-23 19:10:13'),(107,'booking_request','New booking request from Spike Spiegel to Quiapo Church, Plaza Miranda, Quiapo, Manila, Metro Manila, Philippines',256,1,'2025-04-23 19:11:11'),(108,'booking_request','New booking request from Spike Spiegel to Cabuyao City, Laguna, Philippines',258,1,'2025-04-23 19:25:29'),(109,'booking_request','New booking request from Spike Spiegel to Cabuyao City, Laguna, Philippines',260,1,'2025-04-23 19:26:50'),(110,'booking_request','New booking request from Spike Spiegel to Cabuyao City, Laguna, Philippines',262,1,'2025-04-23 19:32:00'),(111,'booking_request','New booking request from Spike Spiegel to Cabuyao City, Laguna, Philippines',264,1,'2025-04-23 19:33:45'),(112,'booking_request','New booking request from Spike Spiegel to Cabuyao City, Laguna, Philippines',266,1,'2025-04-23 19:36:39'),(113,'booking_request','New booking request from Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',268,1,'2025-04-23 19:42:10'),(114,'booking_request','New booking request from Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',270,1,'2025-04-23 19:42:47'),(115,'booking_request','New booking request from Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',272,1,'2025-04-23 19:47:28'),(116,'rebooking_confirmed','Rebooking confirmed for  to ',108,1,'2025-04-23 19:47:36'),(117,'booking_request','New booking request from Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',274,1,'2025-04-23 19:50:42'),(118,'rebooking_confirmed','Rebooking confirmed for  to ',109,1,'2025-04-23 19:50:49'),(119,'booking_request','New booking request from Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',276,1,'2025-04-23 19:52:25'),(120,'rebooking_confirmed','Rebooking confirmed for  to ',110,1,'2025-04-23 19:52:32'),(121,'booking_request','New booking request from Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',278,1,'2025-04-23 19:54:56'),(122,'rebooking_confirmed','Rebooking confirmed for Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',111,1,'2025-04-23 19:55:06'),(123,'booking_request','New booking request from Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',280,1,'2025-04-23 19:57:24'),(124,'booking_request','New booking request from Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',282,1,'2025-04-23 19:57:55'),(125,'rebooking_confirmed','Rebooking confirmed for Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',113,1,'2025-04-23 19:58:02'),(126,'booking_request','New booking request from Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',284,1,'2025-04-23 19:59:16'),(127,'rebooking_confirmed','Rebooking confirmed for Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',114,1,'2025-04-23 19:59:26'),(128,'booking_request','New booking request from Spike Spiegel to Batangas Beach Resorts, Batangas, Philippines',286,1,'2025-04-23 20:03:02'),(129,'booking_request','New booking request from Spike Spiegel to Batangas Beach Resorts, Batangas, Philippines',288,1,'2025-04-23 20:04:19'),(130,'rebooking_confirmed','Rebooking confirmed for Spike Spiegel to Batangas Beach Resorts, Batangas, Philippines',116,1,'2025-04-23 20:04:31'),(131,'booking_request','New booking request from Spike Spiegel to Dalahican Ferry Terminal, 1, Lucena, Quezon, Philippines',290,1,'2025-04-23 20:18:21'),(132,'booking_request','New booking request from Spike Spiegel to Las Pinas City, Metro Manila, Philippines',293,1,'2025-04-23 20:33:51'),(133,'rebooking_confirmed','Rebooking confirmed for Spike Spiegel to Las Pinas City, Metro Manila, Philippines',118,1,'2025-04-23 20:36:24'),(134,'booking_canceled','Booking canceled for Spike Spiegel to Las Pinas City, Metro Manila, Philippines',118,1,'2025-04-23 20:44:26'),(135,'booking_request','New booking request from Spike Spiegel to Dasma Bayan Jeepney Terminal, Dasmariñas, Cavite, Philippines',295,1,'2025-04-23 20:50:47'),(136,'booking_request','New booking request from Spike Spiegel to Dasma Bayan Jeepney Terminal, Dasmariñas, Cavite, Philippines',219,1,'2025-04-23 21:27:42'),(137,'booking_request','New booking request from Kenny Ackerman to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',299,1,'2025-04-24 10:15:30'),(138,'booking_request','New booking request from Kenny Ackerman to Cubao Bus Terminal, New York Avenue, Cubao, Quezon City, Metro Manila, Philippines',301,1,'2025-04-24 10:23:36'),(139,'booking_request','New booking request from Kenny Ackerman to Cubao Bus Terminal, New York Avenue, Cubao, Quezon City, Metro Manila, Philippines',222,1,'2025-04-24 10:23:58'),(140,'booking_request','New booking request from Kenny Ackerman to Cubao Bus Terminal, New York Avenue, Cubao, Quezon City, Metro Manila, Philippines',223,1,'2025-04-24 10:29:55'),(141,'booking_request','New booking request from Kenny Ackerman to Marilao Public Market, Marilao, Bulacan, Philippines',307,1,'2025-04-24 10:35:46'),(142,'booking_request','New booking request from Kenny Ackerman to Marilao Public Market, Marilao, Bulacan, Philippines',225,1,'2025-04-24 10:36:07'),(143,'booking_request','New booking request from Spike Spiegel to Quezon City, Metro Manila, Philippines',311,1,'2025-04-24 12:44:06'),(144,'booking_request','New booking request from Spike Spiegel to Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines',314,1,'2025-04-24 14:55:42'),(145,'booking_request','New booking request from Spike Spiegel to Tayabas Bypass Road, Tayabas, Quezon, Philippines',228,1,'2025-04-24 14:57:03'),(146,'booking_request','New booking request from Spike Spiegel to Tarlac State University (TSU), Romulo Boulevard, Tarlac City, Tarlac, Philippines',320,0,'2025-04-25 12:20:05'),(147,'booking_request','New booking request from Spike Spiegel to Hagonoy Sports Complex, Capistrano Street, Taguig, Metro Manila, Philippines',322,0,'2025-04-25 12:44:45'),(148,'booking_request','New booking request from Spike Spiegel to Hagonoy Sports Complex, Capistrano Street, Taguig, Metro Manila, Philippines',233,0,'2025-04-25 14:33:05'),(149,'booking_request','New booking request from Kenny Ackerman to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',326,0,'2025-04-25 15:46:14'),(150,'booking_request','New booking request from Spike Spiegel to Tayabas Bypass Road, Tayabas, Quezon, Philippines',330,0,'2025-04-25 19:10:25'),(151,'rebooking_confirmed','Rebooking confirmed for Spike Spiegel to Tayabas Bypass Road, Tayabas, Quezon, Philippines',128,0,'2025-04-25 19:10:47'),(152,'booking_request','New booking request from Spike Spiegel to Tayabas Bypass Road, Tayabas, Quezon, Philippines',334,0,'2025-04-25 20:01:12'),(153,'rebooking_confirmed','Rebooking confirmed for Spike Spiegel to Tayabas Bypass Road, Tayabas, Quezon, Philippines',129,0,'2025-04-25 20:01:57'),(154,'booking_request','New booking request from Spike Spiegel to Tayabas Bypass Road, Tayabas, Quezon, Philippines',338,0,'2025-04-25 20:11:08'),(155,'rebooking_confirmed','Rebooking confirmed for Spike Spiegel to Tayabas Bypass Road, Tayabas, Quezon, Philippines',130,0,'2025-04-25 20:11:23'),(156,'booking_request','New booking request from Spike Spiegel to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',343,0,'2025-04-26 08:04:00'),(157,'booking_request','New booking request from Kenny Ackerman to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',347,0,'2025-04-26 10:14:36'),(158,'booking_canceled','Booking canceled for Spike Spiegel to Quezon City, Metro Manila, Philippines',123,0,'2025-04-27 09:18:07'),(159,'booking_request','New booking request from Spike Spiegel to Mina Falls, Trece Martires, Cavite, Philippines',349,0,'2025-04-27 09:34:34'),(160,'booking_request','New booking request from Spike Spiegel to Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines',351,0,'2025-04-27 19:46:10'),(161,'rebooking_confirmed','Rebooking confirmed for Spike Spiegel to Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines',134,0,'2025-04-27 19:51:00'),(162,'booking_request','New booking request from Spike Spiegel',357,0,'2025-04-28 23:43:57'),(163,'booking_request','New booking request from Spike Spiegel',359,0,'2025-04-28 23:58:45'),(164,'booking_request','New booking request from Spike Spiegel',139,0,'2025-04-29 00:05:31'),(165,'booking_request','New booking request from Spike Spiegel',140,1,'2025-04-30 09:33:30'),(166,'booking_request','New booking request from Spike Spiegel',141,0,'2025-04-30 12:14:16'),(167,'booking_canceled','Booking canceled for Spike Spiegel to Hagonoy Sports Complex, Capistrano Street, Taguig, Metro Manila, Philippines',126,0,'2025-04-30 14:03:49'),(168,'booking_request','New booking request from Spike Spiegel',142,0,'2025-04-30 17:15:44'),(169,'booking_request','New booking request from Spike Spiegel',143,0,'2025-04-30 17:19:55'),(170,'booking_request','New booking request from Spike Spiegel',144,0,'2025-04-30 17:28:51'),(171,'booking_request','New booking request from Spike Spiegel',145,0,'2025-04-30 17:43:26'),(172,'booking_request','New booking request from Spike Spiegel',146,0,'2025-04-30 18:07:15'),(173,'booking_canceled','Booking canceled for Spike Spiegel to Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',144,0,'2025-04-30 18:23:05'),(174,'booking_request','New booking request from Spike Spiegel',147,0,'2025-04-30 18:44:03'),(175,'booking_request','New booking request from Spike Spiegel',148,0,'2025-04-30 18:52:53');
/*!40000 ALTER TABLE `admin_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_buses`
--

DROP TABLE IF EXISTS `booking_buses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_buses` (
  `booking_buses_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  PRIMARY KEY (`booking_buses_id`)
) ENGINE=InnoDB AUTO_INCREMENT=292 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_buses`
--

LOCK TABLES `booking_buses` WRITE;
/*!40000 ALTER TABLE `booking_buses` DISABLE KEYS */;
INSERT INTO `booking_buses` VALUES (176,90,1),(177,90,2),(179,91,3),(180,92,3),(181,92,4),(182,93,5),(183,93,6),(184,94,7),(185,95,7),(186,95,8),(187,96,9),(188,96,10),(189,97,3),(190,98,4),(191,99,3),(192,100,4),(193,101,3),(194,102,4),(195,102,5),(196,103,6),(197,103,7),(198,104,8),(199,104,9),(200,105,10),(201,105,11),(202,105,12),(203,106,3),(204,107,4),(205,108,5),(206,109,6),(207,110,7),(208,111,8),(209,112,3),(210,113,4),(211,114,5),(212,115,6),(213,116,7),(214,117,8),(215,117,9),(216,118,10),(218,119,10),(219,119,11),(220,120,12),(223,121,13),(225,122,1),(226,123,1),(228,124,1),(229,125,1),(232,126,2),(233,126,3),(234,127,2),(235,128,2),(236,129,3),(237,129,4),(238,130,5),(239,130,6),(240,130,7),(241,131,1),(242,131,3),(243,132,1),(244,132,2),(245,132,3),(246,132,4),(247,133,1),(248,134,1),(249,134,2),(250,135,3),(251,136,3),(252,137,3),(253,138,3),(254,139,8),(255,140,4),(256,141,1),(257,142,2),(258,142,3),(259,142,4),(260,142,5),(261,142,6),(262,142,7),(263,142,8),(264,142,9),(265,142,10),(266,143,11),(267,143,12),(268,143,13),(269,144,2),(270,144,3),(271,144,4),(272,145,2),(273,145,3),(274,145,4),(275,145,5),(276,145,6),(277,145,7),(278,145,8),(279,146,1),(280,146,5),(281,146,6),(282,147,2),(283,147,3),(284,147,4),(285,147,5),(286,147,6),(287,147,7),(288,147,8),(289,147,9),(290,147,10),(291,148,1);
/*!40000 ALTER TABLE `booking_buses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_costs`
--

DROP TABLE IF EXISTS `booking_costs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_costs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total_cost` decimal(10,2) DEFAULT NULL,
  `base_rate` decimal(10,2) DEFAULT NULL,
  `total_distance` decimal(10,2) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `diesel_price` decimal(10,2) DEFAULT NULL,
  `diesel_cost` decimal(10,2) DEFAULT NULL,
  `base_cost` decimal(10,2) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `gross_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_costs`
--

LOCK TABLES `booking_costs` WRITE;
/*!40000 ALTER TABLE `booking_costs` DISABLE KEYS */;
INSERT INTO `booking_costs` VALUES (8,56385.98,20772.00,279.51,90,53.10,14841.98,41544.00,NULL,NULL),(9,300750.38,117539.00,1236.77,91,53.10,65672.38,235078.00,NULL,NULL),(10,50762.16,20772.00,173.60,92,53.10,9218.16,41544.00,NULL,NULL),(11,92306.16,20772.00,173.60,93,53.10,9218.16,83088.00,NULL,NULL),(12,96481.14,45020.00,121.30,94,53.10,6441.14,90040.00,NULL,NULL),(13,133850.16,20772.00,173.60,95,53.10,9218.16,124632.00,NULL,NULL),(14,92306.16,20772.00,173.60,96,53.10,9218.16,83088.00,NULL,NULL),(15,24472.33,20772.00,69.69,97,53.10,3700.33,20772.00,NULL,NULL),(16,45244.33,20772.00,69.69,98,53.10,3700.33,41544.00,NULL,NULL),(17,41838.45,19560.00,51.20,99,53.10,2718.45,39120.00,NULL,NULL),(18,22278.45,19560.00,51.20,100,53.10,2718.45,19560.00,NULL,NULL),(19,47469.69,20772.00,111.60,101,53.10,5925.69,41544.00,NULL,NULL),(20,130557.69,20772.00,111.60,102,53.10,5925.69,124632.00,NULL,NULL),(21,89013.69,20772.00,111.60,103,53.10,5925.69,83088.00,NULL,NULL),(22,89013.69,20772.00,111.60,104,53.10,5925.69,83088.00,NULL,NULL),(23,192873.69,20772.00,111.60,105,53.10,5925.69,186948.00,NULL,NULL),(24,43326.21,19560.00,79.21,106,53.10,4206.21,39120.00,NULL,NULL),(25,62886.21,19560.00,79.21,107,53.10,4206.21,58680.00,NULL,NULL),(26,82446.21,19560.00,79.21,108,53.10,4206.21,78240.00,NULL,NULL),(27,82446.21,19560.00,79.21,109,53.10,4206.21,78240.00,NULL,NULL),(28,102006.21,19560.00,79.21,110,53.10,4206.21,97800.00,NULL,NULL),(29,102006.21,19560.00,79.21,111,53.10,4206.21,97800.00,NULL,NULL),(30,43326.21,19560.00,79.21,112,53.10,4206.21,39120.00,NULL,NULL),(31,62886.21,19560.00,79.21,113,53.10,4206.21,58680.00,NULL,NULL),(32,82446.21,19560.00,79.21,114,53.10,4206.21,78240.00,NULL,NULL),(33,52728.72,20772.00,210.64,115,53.10,11184.72,41544.00,NULL,NULL),(34,73500.72,20772.00,210.64,116,53.10,11184.72,62316.00,NULL,NULL),(35,150484.08,71040.00,158.27,117,53.10,8404.08,142080.00,NULL,NULL),(36,83956.48,19560.00,107.66,118,53.10,5716.48,78240.00,NULL,NULL),(37,128952.59,20772.00,81.37,119,53.10,4320.59,124632.00,NULL,NULL),(38,27672.24,20772.00,129.95,120,53.10,6900.24,20772.00,NULL,NULL),(39,40226.44,19560.00,20.84,121,53.10,1106.44,39120.00,NULL,NULL),(40,94897.48,45020.00,91.48,122,53.10,4857.48,90040.00,NULL,NULL),(41,51004.56,20772.00,178.17,123,53.10,9460.56,41544.00,NULL,NULL),(42,158799.92,71040.00,314.88,124,53.10,16719.92,142080.00,NULL,NULL),(43,103533.83,45020.00,254.12,125,53.10,13493.83,90040.00,NULL,NULL),(44,66338.31,19560.00,88.19,126,53.10,4682.89,78240.00,20,82922.89),(45,39500.97,19560.00,89.83,127,53.10,4769.97,39120.00,10,43889.97),(46,58791.11,71040.00,314.65,128,53.10,16707.92,71040.00,33,87747.92),(47,150848.52,71040.00,314.65,129,53.10,16707.92,142080.00,5,158787.92),(48,218336.52,71040.00,314.65,130,53.10,16707.92,213120.00,5,229827.92),(49,63970.60,45020.00,102.42,131,53.10,5438.50,90040.00,33,95478.50),(50,59338.73,20772.00,103.15,132,53.10,5477.27,83088.00,33,88565.27),(51,35176.34,20772.00,100.90,133,53.10,5357.79,41544.00,25,46901.79),(52,132500.28,20772.00,279.51,134,53.10,14841.98,124632.00,5,139473.98),(53,20425.53,19560.00,16.30,135,53.10,865.53,19560.00,NULL,NULL),(54,20425.53,19560.00,16.30,136,53.10,865.53,19560.00,NULL,NULL),(55,20425.53,19560.00,16.30,137,53.10,865.53,19560.00,NULL,NULL),(56,98415.71,71040.00,515.55,138,53.10,27375.71,71040.00,NULL,NULL),(57,30907.73,20772.00,190.88,139,53.10,10135.73,20772.00,NULL,NULL),(58,26548.74,19560.00,9.51,140,53.10,504.98,39120.00,33,39624.98),(59,40535.15,19560.00,25.73,141,55.00,1415.15,39120.00,NULL,NULL),(60,106138.15,19560.00,17.07,142,50.20,856.91,176040.00,40,176896.91),(61,97606.22,20772.00,164.63,143,50.20,8264.43,186948.00,50,195212.43),(62,205493.96,19560.00,4.54,144,50.20,227.91,410760.00,50,410987.91),(63,498448.66,71040.00,23.28,145,50.20,1168.66,497280.00,NULL,NULL),(64,83092.70,19560.00,26.77,146,50.20,1343.85,117360.00,30,118703.85),(65,35486.81,19560.00,55.54,147,50.20,2788.11,352080.00,90,354868.11),(66,23226.78,20772.00,48.90,148,50.20,2454.78,20772.00,NULL,NULL);
/*!40000 ALTER TABLE `booking_costs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_stops`
--

DROP TABLE IF EXISTS `booking_stops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_stops` (
  `booking_stops_id` int(11) NOT NULL AUTO_INCREMENT,
  `stop_order` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`booking_stops_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_stops`
--

LOCK TABLES `booking_stops` WRITE;
/*!40000 ALTER TABLE `booking_stops` DISABLE KEYS */;
INSERT INTO `booking_stops` VALUES (39,1,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',118),(41,1,'Makati City Hall, Angono, Makati, Metro Manila, Philippines',124),(42,2,'Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines',124),(43,1,'Makati City Hall, Angono, Makati, Metro Manila, Philippines',128),(44,2,'Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines',128),(45,1,'Makati City Hall, Angono, Makati, Metro Manila, Philippines',129),(46,2,'Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines',129),(47,1,'Makati City Hall, Angono, Makati, Metro Manila, Philippines',130),(48,2,'Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines',130),(49,1,'Malabon, Metro Manila, Philippines',131),(50,2,'Marilao, Bulacan, Philippines',131),(51,3,'Cubao, Quezon City, Metro Manila, Philippines',131),(52,1,'GBR Museum, General Trias, Cavite, Philippines',132),(53,2,'Paradizoo Theme Park, Mendez, Cavite, Philippines',132);
/*!40000 ALTER TABLE `booking_stops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
  `destination` varchar(255) NOT NULL,
  `pickup_point` varchar(255) NOT NULL,
  `date_of_tour` date NOT NULL,
  `end_of_tour` date NOT NULL,
  `number_of_days` int(11) NOT NULL,
  `number_of_buses` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('Pending','Completed','Confirmed','Rejected','Canceled','Processing') NOT NULL DEFAULT 'Pending',
  `payment_status` enum('Paid','Unpaid','Partially Paid') NOT NULL DEFAULT 'Unpaid',
  `user_id` int(11) NOT NULL,
  `is_rebooking` tinyint(1) DEFAULT 0,
  `is_rebooked` tinyint(1) DEFAULT 0,
  `booked_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pickup_time` time DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `created_by` enum('Client','Admin','Super Admin') DEFAULT 'Client',
  PRIMARY KEY (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (90,'Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-04-30','2025-05-01',1,2,28192.99,'Confirmed','Partially Paid',25,0,1,'2025-04-22 21:27:06','08:58:00','2025-04-25 14:06:19','Client'),(91,'Apayao Provincial Capitol, Luna, Apayao, Philippines','Makati Sports Club - Tennis Court, L.P. Leviste Street, Makati, Metro Manila, Philippines','2025-04-30','2025-05-02',2,1,150375.19,'Canceled','Partially Paid',25,0,0,'2025-04-22 22:50:08','05:00:00','2025-04-25 14:06:19','Client'),(112,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-04-30','2025-05-02',2,1,43326.21,'Confirmed','Unpaid',25,0,1,'2025-04-23 19:57:24','04:30:00','2025-04-25 14:06:19','Client'),(113,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-04-30','2025-05-03',3,1,31443.10,'Confirmed','Partially Paid',25,0,1,'2025-04-23 19:57:55','07:00:00','2025-04-25 14:06:19','Client'),(114,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-04-30','2025-05-04',4,1,31443.11,'Confirmed','Partially Paid',25,0,1,'2025-04-23 19:59:16','05:30:00','2025-04-25 14:06:19','Client'),(115,'Batangas Beach Resorts, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-04-29','2025-05-01',2,1,26364.36,'Confirmed','Partially Paid',25,0,1,'2025-04-23 20:03:02','04:30:00','2025-04-25 14:06:19','Client'),(116,'Batangas Beach Resorts, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-04-29','2025-05-02',3,1,0.00,'Confirmed','Paid',25,0,0,'2025-04-23 20:04:19','04:30:00','2025-04-25 14:06:19','Client'),(117,'Dalahican Ferry Terminal, 1, Lucena, Quezon, Philippines','Batangas Beach Resorts, Batangas, Philippines','2025-04-30','2025-05-01',1,2,150484.08,'Confirmed','Unpaid',25,0,0,'2025-04-23 20:18:21','05:30:00','2025-04-25 14:06:19','Client'),(118,'Las Pinas City, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-04-30','2025-05-04',4,1,52513.37,'Canceled','Partially Paid',25,0,0,'2025-04-23 20:33:51','04:30:00','2025-04-25 14:06:19','Client'),(119,'Dasma Bayan Jeepney Terminal, Dasmariñas, Cavite, Philippines','Booking Online Philippines, H.V. Dela Costa, Makati, Metro Manila, Philippines','2025-04-30','2025-05-03',3,2,128952.59,'Confirmed','Unpaid',25,0,0,'2025-04-23 20:50:47','04:30:00','2025-04-25 14:06:19','Client'),(120,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-04-30','2025-05-01',1,1,27672.24,'Confirmed','Unpaid',30,0,0,'2025-04-24 10:15:30','04:30:00','2025-04-25 14:06:19','Client'),(121,'Cubao Bus Terminal, New York Avenue, Cubao, Quezon City, Metro Manila, Philippines','Makati City Hall, Angono, Makati, Metro Manila, Philippines','2025-04-30','2025-05-01',2,1,20113.22,'Confirmed','Partially Paid',30,0,0,'2025-04-24 10:23:36','04:30:00','2025-04-25 14:06:19','Client'),(122,'Marilao Public Market, Marilao, Bulacan, Philippines','Kentucky, Taguig, Metro Manila, Philippines','2025-05-20','2025-05-21',2,1,94897.48,'Confirmed','Unpaid',30,0,0,'2025-04-24 10:35:46','04:00:00','2025-04-25 14:06:19','Client'),(123,'Quezon City, Metro Manila, Philippines','Calabarzon Expressway, Malvar, Batangas, Philippines','2025-05-23','2025-05-24',2,1,51004.56,'Canceled','Unpaid',25,0,0,'2025-04-24 12:44:06','05:30:00','2025-04-25 14:18:47','Client'),(124,'Tayabas Bypass Road, Tayabas, Quezon, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-05-15','2025-05-16',2,1,79399.96,'Confirmed','Partially Paid',25,0,1,'2025-04-24 14:55:42','08:00:00','2025-04-25 14:06:19','Client'),(125,'Tarlac State University (TSU), Romulo Boulevard, Tarlac City, Tarlac, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-04-28','2025-04-29',2,1,103533.83,'Confirmed','Unpaid',25,0,0,'2025-04-25 12:20:05','04:30:00','2025-04-25 14:06:19','Client'),(126,'Hagonoy Sports Complex, Capistrano Street, Taguig, Metro Manila, Philippines','Phase 9 Bagong Silang, Caloocan, Metro Manila, Philippines','2025-05-24','2025-05-25',2,2,66338.31,'Canceled','Unpaid',25,0,0,'2025-04-25 12:44:45','04:00:00','2025-04-25 15:20:02','Client'),(127,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Phase 9 Bagong Silang, Caloocan, Metro Manila, Philippines','2025-05-22','2025-05-23',2,1,0.00,'Confirmed','Paid',30,0,0,'2025-04-25 15:46:14','04:00:00','2025-04-25 15:46:41','Client'),(128,'Tayabas Bypass Road, Tayabas, Quezon, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-05-15','2025-05-15',1,1,-20608.85,'Confirmed','Paid',25,0,1,'2025-04-25 19:10:25','04:30:00',NULL,'Client'),(129,'Tayabas Bypass Road, Tayabas, Quezon, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-05-15','2025-05-15',1,2,71448.56,'Confirmed','Partially Paid',25,0,1,'2025-04-25 20:01:12','04:30:00',NULL,'Client'),(130,'Tayabas Bypass Road, Tayabas, Quezon, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-05-15','2025-05-15',1,3,0.00,'Confirmed','Paid',25,0,0,'2025-04-25 20:11:08','04:00:00','2025-04-25 20:11:23','Client'),(131,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Pinagbuhatan, Metro Manila, Philippines','2025-05-22','2025-05-22',1,2,63970.60,'Confirmed','Unpaid',25,0,0,'2025-04-26 08:04:00','04:30:00','2025-04-26 08:08:06','Client'),(132,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','2025-05-14','2025-05-14',1,4,29669.36,'Confirmed','Partially Paid',30,0,0,'2025-04-26 10:14:36','05:00:00','2025-04-26 10:15:07','Client'),(133,'Mina Falls, Trece Martires, Cavite, Philippines','Makati, Metro Manila, Philippines','2025-05-24','2025-05-25',2,1,35176.34,'Confirmed','Unpaid',25,0,0,'2025-04-27 09:34:34','04:30:00','2025-04-28 13:30:23','Client'),(134,'Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-05-09','2025-05-11',3,2,0.00,'Confirmed','Paid',25,0,0,'2025-04-27 19:46:10','04:00:00','2025-04-27 19:51:00','Client'),(138,'Baguio, Benguet, Philippines','MOA Arena, J.W. Diokno Boulevard, Pasay, Metro Manila, Philippines','2025-05-10','2025-05-10',1,1,98415.71,'Pending','Unpaid',25,0,0,'2025-04-28 23:58:45','04:30:00',NULL,'Client'),(139,'Maragondon, Cavite, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-05-15','2025-05-15',1,1,30907.73,'Rejected','Unpaid',25,0,0,'2025-04-29 00:05:31','04:00:00',NULL,'Client'),(140,'Mandaluyong City Hall, Maysilo Circle, Mandaluyong, Metro Manila, Philippines','Makati Medical Center, Amorsolo Street, Legazpi Village, Makati, Metro Manila, Philippines','2025-05-22','2025-05-23',2,1,26548.74,'Confirmed','Unpaid',25,0,0,'2025-04-30 09:33:30','04:30:00','2025-04-30 09:34:27','Client'),(141,'Manila Zoo, Adriatico Street, Malate, Manila, Metro Manila, Philippines','Makabayan Street, Diliman, Quezon City, Metro Manila, Philippines','2025-05-17','2025-05-18',2,1,40535.15,'Pending','Unpaid',25,0,0,'2025-04-30 12:14:16','04:30:00',NULL,'Client'),(142,'Intramuros, Manila, Metro Manila, Philippines','Makati Medical Center, Amorsolo Street, Legazpi Village, Makati, Metro Manila, Philippines','2025-05-16','2025-05-16',1,9,106138.15,'Confirmed','Unpaid',25,0,0,'2025-04-30 17:15:44','04:30:00','2025-04-30 17:17:51','Client'),(143,'Silang Specialists Medical Center, Aguinaldo Highway, Silang, Cavite, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-05-16','2025-05-16',1,9,97606.22,'Confirmed','Unpaid',25,0,0,'2025-04-30 17:19:55','04:30:00','2025-04-30 17:20:13','Client'),(144,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','7573 Swimming Pool, Novaliches, Caloocan, Metro Manila, Philippines','2025-05-24','2025-05-30',7,3,205493.96,'Canceled','Unpaid',25,0,0,'2025-04-30 17:28:51','05:00:00','2025-04-30 17:44:58','Client'),(145,'Marilao Public Market, Marilao, Bulacan, Philippines','Guiguinto Municipal Hall, MacArthur Highway, Guiguinto, Bulacan, Philippines','2025-05-20','2025-05-20',1,7,498448.66,'Pending','Unpaid',25,0,0,'2025-04-30 17:43:26','04:30:00',NULL,'Client'),(146,'Parang High School, Tandang Sora Street, Marikina, Metro Manila, Philippines','San Juan, Metro Manila, Philippines','2025-05-26','2025-05-27',2,3,83092.70,'Confirmed','Unpaid',25,0,0,'2025-04-30 18:07:15','04:00:00','2025-04-30 18:08:09','Client'),(147,'Makati City Hall, Angono, Makati, Metro Manila, Philippines','Maligaya Elementary School, Quezon City, Metro Manila, Philippines','2025-05-19','2025-05-20',2,9,35486.81,'Confirmed','Unpaid',25,0,0,'2025-04-30 18:44:03','05:00:00','2025-04-30 18:45:16','Client'),(148,'Bacoor, Cavite, Philippines','Makati Medical Center, Amorsolo Street, Legazpi Village, Makati, Metro Manila, Philippines','2025-05-13','2025-05-13',1,1,23226.78,'Pending','Unpaid',25,0,0,'2025-04-30 18:52:53','05:00:00',NULL,'Client');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buses`
--

DROP TABLE IF EXISTS `buses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `buses` (
  `bus_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `capacity` varchar(2) NOT NULL DEFAULT '49',
  `status` enum('Active','Maintenance') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`bus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buses`
--

LOCK TABLES `buses` WRITE;
/*!40000 ALTER TABLE `buses` DISABLE KEYS */;
INSERT INTO `buses` VALUES (1,'KingLang01','49','Active'),(2,'KingLang02','49','Active'),(3,'KingLang03','49','Active'),(4,'KingLang04','49','Active'),(5,'KingLang05','49','Active'),(6,'KingLang06','49','Active'),(7,'KingLang07','49','Active'),(8,'KingLang08','49','Active'),(9,'KingLang09','49','Active'),(10,'KingLang10','49','Active'),(11,'KingLang11','49','Active'),(12,'KingLang12','49','Active'),(13,'KingLang13','49','Active');
/*!40000 ALTER TABLE `buses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `canceled_trips`
--

DROP TABLE IF EXISTS `canceled_trips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `canceled_trips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` text DEFAULT NULL,
  `canceled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `booking_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount_refunded` decimal(10,2) DEFAULT 0.00,
  `canceled_by` enum('Client','Admin','Super Admin') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `canceled_trips`
--

LOCK TABLES `canceled_trips` WRITE;
/*!40000 ALTER TABLE `canceled_trips` DISABLE KEYS */;
INSERT INTO `canceled_trips` VALUES (16,'malayo','2025-04-22 22:53:05',91,25,120300.15,'Super Admin'),(17,'fasd','2025-04-23 20:44:26',118,25,25154.49,'Super Admin'),(18,'fasdfaf','2025-04-27 09:18:07',123,NULL,0.00,'Super Admin'),(19,'di ka nagbabayad','2025-04-30 14:03:49',126,25,0.00,'Super Admin'),(20,'long duration','2025-04-30 18:23:05',144,25,0.00,'Super Admin');
/*!40000 ALTER TABLE `canceled_trips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_notifications`
--

DROP TABLE IF EXISTS `client_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_notifications`
--

LOCK TABLES `client_notifications` WRITE;
/*!40000 ALTER TABLE `client_notifications` DISABLE KEYS */;
INSERT INTO `client_notifications` VALUES (12,25,'payment_confirmed','Your payment of 28,192.99 for booking to Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines has been confirmed.',90,1,'2025-04-22 21:42:40'),(13,25,'payment_confirmed','Your payment of 28,192.99 for booking to Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines has been confirmed.',90,1,'2025-04-22 21:42:40'),(14,25,'payment_confirmed','Your payment of 28,192.99 for booking to Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines has been confirmed.',90,1,'2025-04-22 21:49:51'),(15,25,'payment_confirmed','Your payment of 28,192.99 for booking to Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines has been confirmed.',90,1,'2025-04-22 21:59:50'),(16,25,'payment_confirmed','Your payment of 28,192.99 for booking to Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines has been confirmed.',90,1,'2025-04-22 22:04:13'),(17,25,'payment_confirmed','Your payment of 28,192.99 for booking to Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines has been confirmed.',90,1,'2025-04-22 22:06:18'),(18,25,'payment_confirmed','Your payment of 28,192.99 for booking to Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines has been confirmed.',90,1,'2025-04-22 22:13:04'),(19,25,'payment_confirmed','Your payment of 150,375.19 for booking to Apayao Provincial Capitol, Luna, Apayao, Philippines has been confirmed.',91,1,'2025-04-22 22:52:21'),(20,25,'payment_confirmed','Your payment of 25,381.08 for booking to Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines has been confirmed.',92,1,'2025-04-23 18:25:45'),(21,25,'payment_confirmed','Your payment of 66,925.08 for booking to Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines has been confirmed.',95,1,'2025-04-23 18:56:43'),(22,25,'payment_confirmed','Your payment of 12,236.17 for booking to Elijah Hotel and Residences, Dasmariñas, Cavite, Philippines has been confirmed.',97,1,'2025-04-23 19:06:42'),(23,25,'payment_confirmed','Your payment of 20,919.23 for booking to Quiapo Church, Plaza Miranda, Quiapo, Manila, Metro Manila, Philippines has been confirmed.',99,1,'2025-04-23 19:10:54'),(24,25,'payment_confirmed','Your payment of 11,139.23 for booking to Quiapo Church, Plaza Miranda, Quiapo, Manila, Metro Manila, Philippines has been confirmed.',100,1,'2025-04-23 19:23:18'),(25,25,'payment_confirmed','Your payment of 23,734.85 for booking to Cabuyao City, Laguna, Philippines has been confirmed.',101,1,'2025-04-23 19:26:23'),(26,25,'rebooking_confirmed','Your rebooking request for the trip to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines has been confirmed.',111,1,'2025-04-23 19:55:06'),(27,25,'rebooking_confirmed','Your rebooking request for the trip to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines has been confirmed.',113,1,'2025-04-23 19:58:02'),(28,25,'payment_confirmed','Your payment of 31,443.11 for booking to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines has been confirmed.',113,1,'2025-04-23 19:58:37'),(29,25,'rebooking_confirmed','Your rebooking request for the trip to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines has been confirmed.',114,1,'2025-04-23 19:59:26'),(30,25,'payment_confirmed','Your payment of 26,364.36 for booking to Batangas Beach Resorts, Batangas, Philippines has been confirmed.',115,1,'2025-04-23 20:03:41'),(31,25,'rebooking_confirmed','Your rebooking request for the trip to Batangas Beach Resorts, Batangas, Philippines has been confirmed.',116,1,'2025-04-23 20:04:31'),(32,25,'rebooking_confirmed','Your rebooking request for the trip to Las Pinas City, Metro Manila, Philippines has been confirmed.',118,1,'2025-04-23 20:36:24'),(33,25,'booking_canceled','Your booking to Las Pinas City, Metro Manila, Philippines has been canceled. Refunded amount: 25154.488',118,1,'2025-04-23 20:44:26'),(34,25,'payment_confirmed','Your payment of 47,136.36 for booking to Batangas Beach Resorts, Batangas, Philippines has been confirmed.',116,1,'2025-04-23 21:47:38'),(35,30,'payment_confirmed','Your payment of 20,113.22 for booking to Cubao Bus Terminal, New York Avenue, Cubao, Quezon City, Metro Manila, Philippines has been confirmed.',121,0,'2025-04-24 11:37:53'),(36,25,'payment_confirmed','Your payment of 79,399.96 for booking to Tayabas Bypass Road, Tayabas, Quezon, Philippines has been confirmed.',124,1,'2025-04-24 19:33:19'),(37,30,'payment_confirmed','Your payment of 19,750.49 for booking to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines has been confirmed.',127,0,'2025-04-25 15:50:25'),(38,25,'rebooking_confirmed','Your rebooking request for the trip to Tayabas Bypass Road, Tayabas, Quezon, Philippines has been confirmed.',128,1,'2025-04-25 19:10:47'),(39,25,'rebooking_confirmed','Your rebooking request for the trip to Tayabas Bypass Road, Tayabas, Quezon, Philippines has been confirmed.',129,1,'2025-04-25 20:01:57'),(40,25,'rebooking_confirmed','Your rebooking request for the trip to Tayabas Bypass Road, Tayabas, Quezon, Philippines has been confirmed.',130,1,'2025-04-25 20:11:23'),(41,25,'payment_confirmed','Your payment of 69,468.28 for booking to Tayabas Bypass Road, Tayabas, Quezon, Philippines has been confirmed.',130,1,'2025-04-25 20:21:42'),(42,25,'payment_recorded','Your payment of PHP 51,003.10 for booking to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines has been recorded.',114,1,'2025-04-26 11:14:32'),(43,30,'payment_recorded','Your payment of PHP 19,750.48 for booking to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines has been recorded.',127,0,'2025-04-26 11:23:58'),(44,30,'payment_rejected','Your payment of 29,669.37 for booking to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines has been rejected. Reason: hehe\n',132,0,'2025-04-26 13:09:47'),(45,25,'booking_canceled','Your booking to Quezon City, Metro Manila, Philippines has been canceled. ',123,1,'2025-04-27 09:18:07'),(46,25,'rebooking_confirmed','Your rebooking request for the trip to Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines has been confirmed.',134,1,'2025-04-27 19:51:00'),(47,25,'payment_recorded','Your payment of PHP 69,468.28 for booking to Tayabas Bypass Road, Tayabas, Quezon, Philippines has been recorded.',130,1,'2025-04-30 04:54:02'),(48,25,'payment_confirmed','Your payment of 104,307.29 for booking to Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines has been confirmed.',134,1,'2025-04-30 04:56:27'),(49,30,'payment_confirmed','Your payment of 29,669.37 for booking to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines has been confirmed.',132,0,'2025-04-30 09:10:51'),(50,25,'booking_rejected','Your booking to Maragondon, Cavite, Philippines has been rejected. Reason: idk',139,1,'2025-04-30 13:14:36'),(51,25,'test_notification','This is a test notification to verify the notification system is working properly.',NULL,1,'2025-04-30 13:19:35'),(52,25,'test_notification','This is a test notification to verify the notification system is working properly.',NULL,1,'2025-04-30 13:19:44'),(53,25,'test_notification','This is a test notification to verify the notification system is working properly.',NULL,1,'2025-04-30 13:20:08'),(54,25,'test_notification','This is a test notification to verify the notification system is working properly.',NULL,1,'2025-04-30 13:50:23'),(55,25,'booking_canceled','Your booking to Hagonoy Sports Complex, Capistrano Street, Taguig, Metro Manila, Philippines has been canceled. ',126,1,'2025-04-30 14:03:49'),(56,25,'booking_canceled','Your booking to Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines has been canceled. ',144,1,'2025-04-30 18:23:05');
/*!40000 ALTER TABLE `client_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `diesel_per_liter`
--

DROP TABLE IF EXISTS `diesel_per_liter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `diesel_per_liter` (
  `price_id` int(11) NOT NULL AUTO_INCREMENT,
  `price` decimal(10,2) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`price_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diesel_per_liter`
--

LOCK TABLES `diesel_per_liter` WRITE;
/*!40000 ALTER TABLE `diesel_per_liter` DISABLE KEYS */;
INSERT INTO `diesel_per_liter` VALUES (4,53.10,'2025-04-22 20:31:44');
/*!40000 ALTER TABLE `diesel_per_liter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('Cash','Bank Transfer','Online Payment') NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_canceled` tinyint(1) DEFAULT 0,
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `status` enum('Confirmed','Pending','Rejected') DEFAULT 'Pending',
  `payment_date` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (65,28192.99,'Bank Transfer',134,25,0,'payment_90_1745359975.png','Confirmed','2025-04-23 06:12:55','2025-04-23 06:13:03',NULL),(66,150375.19,'Bank Transfer',91,25,1,'payment_91_1745362320.png','Confirmed','2025-04-23 06:52:00','2025-04-23 06:52:21',NULL),(73,31443.11,'Online Payment',118,25,1,'payment_113_1745438305.jpg','Confirmed','2025-04-24 03:58:25','2025-04-24 03:58:37',NULL),(74,26364.36,'Bank Transfer',116,25,0,'payment_115_1745438610.png','Confirmed','2025-04-24 04:03:30','2025-04-24 04:03:41',NULL),(75,47136.36,'Bank Transfer',116,25,0,'payment_116_1745444651.png','Confirmed','2025-04-24 05:44:11','2025-04-24 05:47:38',NULL),(76,20113.22,'Online Payment',121,30,0,'payment_121_1745493920.jpg','Confirmed','2025-04-24 19:25:20','2025-04-24 19:37:53',NULL),(77,79399.96,'Bank Transfer',130,25,0,'payment_124_1745523179.png','Confirmed','2025-04-25 03:32:59','2025-04-25 03:33:19',NULL),(78,19750.49,'Bank Transfer',127,30,0,'payment_127_1745596192.jpg','Confirmed','2025-04-25 23:49:52','2025-04-25 23:50:25',NULL),(79,69468.28,'Online Payment',130,25,0,'payment_130_1745612444.png','Confirmed','2025-04-26 04:20:44','2025-04-26 04:21:42',NULL),(81,19750.48,'Cash',127,30,0,NULL,'Confirmed','2025-04-26 19:23:58',NULL,''),(82,29669.37,'Online Payment',132,30,0,'payment_132_1745672967.jpg','Rejected','2025-04-26 21:09:27','2025-04-26 21:09:47',NULL),(83,29669.37,'Bank Transfer',132,30,0,'payment_132_1745674216.png','Confirmed','2025-04-26 21:30:16','2025-04-30 17:10:51',NULL),(84,69468.28,'Cash',130,25,0,NULL,'Confirmed','2025-04-30 12:54:02',NULL,''),(85,104307.29,'Bank Transfer',134,25,0,'payment_134_1745988945.jpg','Confirmed','2025-04-30 12:55:45','2025-04-30 12:56:27',NULL);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rebooking_request`
--

DROP TABLE IF EXISTS `rebooking_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rebooking_request` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) DEFAULT NULL,
  `rebooking_id` int(11) DEFAULT NULL,
  `status` enum('Pending','Rejected','Confirmed','Canceled') DEFAULT 'Pending',
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rebooking_request`
--

LOCK TABLES `rebooking_request` WRITE;
/*!40000 ALTER TABLE `rebooking_request` DISABLE KEYS */;
INSERT INTO `rebooking_request` VALUES (41,112,113,'Confirmed',25),(42,113,114,'Confirmed',25),(43,115,116,'Confirmed',25),(44,114,118,'Confirmed',25),(45,124,128,'Confirmed',25),(46,128,129,'Confirmed',25),(47,129,130,'Confirmed',25),(48,90,134,'Confirmed',25);
/*!40000 ALTER TABLE `rebooking_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rejected_trips`
--

DROP TABLE IF EXISTS `rejected_trips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rejected_trips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` text NOT NULL,
  `type` enum('Booking','Rebooking') DEFAULT 'Booking',
  `rejected_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `booking_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rejected_trips`
--

LOCK TABLES `rejected_trips` WRITE;
/*!40000 ALTER TABLE `rejected_trips` DISABLE KEYS */;
INSERT INTO `rejected_trips` VALUES (29,'hehe\n','','2025-04-26 13:09:47',132,30),(30,'idk','Booking','2025-04-30 13:14:36',139,25);
/*!40000 ALTER TABLE `rejected_trips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) NOT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=687917 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (316069,'site_name','Kinglang Transport','general',1,'2025-04-22 19:47:04','2025-04-25 19:13:31'),(316070,'site_email','info@kinglangbooking.com','general',1,'2025-04-22 19:47:04','2025-04-22 19:47:04'),(316071,'contact_phone','09394858675','general',1,'2025-04-22 19:47:04','2025-04-25 19:13:10'),(316072,'min_booking_notice_hours','24','booking',1,'2025-04-22 19:47:04','2025-04-22 19:47:04'),(316073,'max_booking_days_in_advance','60','booking',1,'2025-04-22 19:47:04','2025-04-22 19:47:04'),(316074,'allow_rebooking','1','booking',1,'2025-04-22 19:47:04','2025-04-22 19:47:04'),(316075,'rebooking_fee_percentage','10','booking',1,'2025-04-22 19:47:04','2025-04-22 19:47:04'),(316076,'payment_methods','Cash, Credit Card, Bank Transfer','payment',1,'2025-04-22 19:47:04','2025-04-25 19:14:25'),(316077,'currency','PHP','payment',1,'2025-04-22 19:47:04','2025-04-22 19:47:04'),(316078,'tax_rate','12','payment',1,'2025-04-22 19:47:04','2025-04-22 19:47:04'),(316079,'enable_email_notifications','1','notification',0,'2025-04-22 19:47:04','2025-04-22 19:47:04'),(316080,'enable_sms_notifications','0','notification',0,'2025-04-22 19:47:04','2025-04-22 19:47:04'),(607072,'company_name','KINGLANG TOURS AND TRANSPORT SERVICES INC.','company',1,'2025-04-28 16:43:21','2025-04-28 16:43:21'),(607073,'company_address','295-B, Purok 4, M. L. Quezon Ave, Lower Bicutan, Taguig, 1632 Metro Manila','company',1,'2025-04-28 16:43:21','2025-04-28 17:49:19'),(607074,'company_contact','0917-882-2727 / 0932-882-2727','company',1,'2025-04-28 16:43:21','2025-04-28 17:49:57'),(607075,'company_email','bsmillamina@yahoo.com','company',1,'2025-04-28 16:43:21','2025-04-28 17:50:09'),(607076,'bank_name','BPI Cainta Ortigas Extension Branch','payment',1,'2025-04-28 16:43:21','2025-04-28 16:43:21'),(607077,'bank_account_name','KINGLANG TOURS AND TRANSPORT SERVICES INC.','payment',1,'2025-04-28 16:43:21','2025-04-28 16:43:21'),(607078,'bank_account_number','4091-0050-05','payment',1,'2025-04-28 16:43:21','2025-04-28 16:43:21'),(607079,'bank_swift_code','BPOIPHMM','payment',1,'2025-04-28 16:43:21','2025-04-28 16:43:21'),(637364,'diesel_price','50.20','booking',1,'2025-04-30 12:01:18','2025-04-30 12:22:56');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `terms_agreements`
--

DROP TABLE IF EXISTS `terms_agreements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `terms_agreements` (
  `agreement_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `agreed_terms` tinyint(1) NOT NULL DEFAULT 0,
  `agreed_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_ip` varchar(45) NOT NULL,
  PRIMARY KEY (`agreement_id`),
  KEY `booking_id` (`booking_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `terms_agreements_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `terms_agreements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `terms_agreements`
--

LOCK TABLES `terms_agreements` WRITE;
/*!40000 ALTER TABLE `terms_agreements` DISABLE KEYS */;
INSERT INTO `terms_agreements` VALUES (2,139,25,1,'2025-04-29 00:05:31','::1'),(3,140,25,1,'2025-04-30 09:33:30','::1'),(4,141,25,1,'2025-04-30 12:14:16','::1'),(5,142,25,1,'2025-04-30 17:15:44','::1'),(6,143,25,1,'2025-04-30 17:19:55','::1'),(7,144,25,1,'2025-04-30 17:28:51','::1'),(8,145,25,1,'2025-04-30 17:43:26','::1'),(9,146,25,1,'2025-04-30 18:07:15','::1'),(10,147,25,1,'2025-04-30 18:44:03','::1'),(11,148,25,1,'2025-04-30 18:52:53','::1');
/*!40000 ALTER TABLE `terms_agreements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trip_distances`
--

DROP TABLE IF EXISTS `trip_distances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trip_distances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `origin` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `distance` decimal(10,2) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=380 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trip_distances`
--

LOCK TABLES `trip_distances` WRITE;
/*!40000 ALTER TABLE `trip_distances` DISABLE KEYS */;
INSERT INTO `trip_distances` VALUES (233,'Maligaya Street, Quezon City, Metro Manila, Philippines','Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines',141165.00,90),(234,'Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',138345.00,90),(237,'Makati Sports Club - Tennis Court, L.P. Leviste Street, Makati, Metro Manila, Philippines','Apayao Provincial Capitol, Luna, Apayao, Philippines',619113.00,91),(238,'Apayao Provincial Capitol, Luna, Apayao, Philippines','Makati Sports Club - Tennis Court, L.P. Leviste Street, Makati, Metro Manila, Philippines',617655.00,91),(239,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines',86586.00,92),(240,'Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',87014.00,92),(241,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines',86586.00,93),(242,'Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',87014.00,93),(243,'Bulacan State University, Malolos, Bulacan, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',60017.00,94),(244,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Bulacan State University, Malolos, Bulacan, Philippines',61285.00,94),(245,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines',86586.00,95),(246,'Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',87014.00,95),(247,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines',86586.00,96),(248,'Tagaytay City Market, Tagaytay - Sta. Rosa Rd, Tagaytay, Cavite, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',87014.00,96),(249,'Makati City Hall, Angono, Makati, Metro Manila, Philippines','Elijah Hotel and Residences, Dasmariñas, Cavite, Philippines',34926.00,97),(250,'Elijah Hotel and Residences, Dasmariñas, Cavite, Philippines','Makati City Hall, Angono, Makati, Metro Manila, Philippines',34760.00,97),(251,'Makati City Hall, Angono, Makati, Metro Manila, Philippines','Elijah Hotel and Residences, Dasmariñas, Cavite, Philippines',34926.00,98),(252,'Elijah Hotel and Residences, Dasmariñas, Cavite, Philippines','Makati City Hall, Angono, Makati, Metro Manila, Philippines',34760.00,98),(253,'Fairview Terraces, Barangay corner, Maligaya Street, Novaliches, Quezon City, Metro Manila, Philippines','Quiapo Church, Plaza Miranda, Quiapo, Manila, Metro Manila, Philippines',27355.00,99),(254,'Quiapo Church, Plaza Miranda, Quiapo, Manila, Metro Manila, Philippines','Fairview Terraces, Barangay corner, Maligaya Street, Novaliches, Quezon City, Metro Manila, Philippines',23840.00,99),(255,'Fairview Terraces, Barangay corner, Maligaya Street, Novaliches, Quezon City, Metro Manila, Philippines','Quiapo Church, Plaza Miranda, Quiapo, Manila, Metro Manila, Philippines',27355.00,100),(256,'Quiapo Church, Plaza Miranda, Quiapo, Manila, Metro Manila, Philippines','Fairview Terraces, Barangay corner, Maligaya Street, Novaliches, Quezon City, Metro Manila, Philippines',23840.00,100),(257,'Kento Ramen, san roque street, Kalayaan, Laguna, Philippines','Cabuyao City, Laguna, Philippines',55688.00,101),(258,'Cabuyao City, Laguna, Philippines','Kento Ramen, san roque street, Kalayaan, Laguna, Philippines',55907.00,101),(259,'Kento Ramen, san roque street, Kalayaan, Laguna, Philippines','Cabuyao City, Laguna, Philippines',55688.00,102),(260,'Cabuyao City, Laguna, Philippines','Kento Ramen, san roque street, Kalayaan, Laguna, Philippines',55907.00,102),(261,'Kento Ramen, san roque street, Kalayaan, Laguna, Philippines','Cabuyao City, Laguna, Philippines',55688.00,103),(262,'Cabuyao City, Laguna, Philippines','Kento Ramen, san roque street, Kalayaan, Laguna, Philippines',55907.00,103),(263,'Kento Ramen, san roque street, Kalayaan, Laguna, Philippines','Cabuyao City, Laguna, Philippines',55688.00,104),(264,'Cabuyao City, Laguna, Philippines','Kento Ramen, san roque street, Kalayaan, Laguna, Philippines',55907.00,104),(265,'Kento Ramen, san roque street, Kalayaan, Laguna, Philippines','Cabuyao City, Laguna, Philippines',55688.00,105),(266,'Cabuyao City, Laguna, Philippines','Kento Ramen, san roque street, Kalayaan, Laguna, Philippines',55907.00,105),(267,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',41713.00,106),(268,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',37500.00,106),(269,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',41713.00,107),(270,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',37500.00,107),(271,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',41713.00,108),(272,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',37500.00,108),(273,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',41713.00,109),(274,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',37500.00,109),(275,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',41713.00,110),(276,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',37500.00,110),(277,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',41713.00,111),(278,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',37500.00,111),(279,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',41713.00,112),(280,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',37500.00,112),(281,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',41713.00,113),(282,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',37500.00,113),(283,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',41713.00,114),(284,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',37500.00,114),(285,'Maligaya Street, Quezon City, Metro Manila, Philippines','Batangas Beach Resorts, Batangas, Philippines',105756.00,115),(286,'Batangas Beach Resorts, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',104879.00,115),(287,'Maligaya Street, Quezon City, Metro Manila, Philippines','Batangas Beach Resorts, Batangas, Philippines',105756.00,116),(288,'Batangas Beach Resorts, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',104879.00,116),(289,'Batangas Beach Resorts, Batangas, Philippines','Dalahican Ferry Terminal, 1, Lucena, Quezon, Philippines',78312.00,117),(290,'Dalahican Ferry Terminal, 1, Lucena, Quezon, Philippines','Batangas Beach Resorts, Batangas, Philippines',79957.00,117),(291,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',41713.00,118),(292,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Las Pinas City, Metro Manila, Philippines',15278.00,118),(293,'Las Pinas City, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',50664.00,118),(296,'Booking Online Philippines, H.V. Dela Costa, Makati, Metro Manila, Philippines','Dasma Bayan Jeepney Terminal, Dasmariñas, Cavite, Philippines',39677.00,119),(297,'Dasma Bayan Jeepney Terminal, Dasmariñas, Cavite, Philippines','Booking Online Philippines, H.V. Dela Costa, Makati, Metro Manila, Philippines',41690.00,119),(298,'Maligaya Street, Quezon City, Metro Manila, Philippines','Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',65652.00,120),(299,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',64296.00,120),(304,'Makati City Hall, Angono, Makati, Metro Manila, Philippines','Cubao Bus Terminal, New York Avenue, Cubao, Quezon City, Metro Manila, Philippines',9681.00,121),(305,'Cubao Bus Terminal, New York Avenue, Cubao, Quezon City, Metro Manila, Philippines','Makati City Hall, Angono, Makati, Metro Manila, Philippines',11156.00,121),(308,'Kentucky, Taguig, Metro Manila, Philippines','Marilao Public Market, Marilao, Bulacan, Philippines',47027.00,122),(309,'Marilao Public Market, Marilao, Bulacan, Philippines','Kentucky, Taguig, Metro Manila, Philippines',44451.00,122),(310,'Calabarzon Expressway, Malvar, Batangas, Philippines','Quezon City, Metro Manila, Philippines',96880.00,123),(311,'Quezon City, Metro Manila, Philippines','Calabarzon Expressway, Malvar, Batangas, Philippines',81285.00,123),(315,'Maligaya Street, Quezon City, Metro Manila, Philippines','Makati City Hall, Angono, Makati, Metro Manila, Philippines',27429.00,124),(316,'Makati City Hall, Angono, Makati, Metro Manila, Philippines','Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines',108733.00,124),(317,'Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines','Tayabas Bypass Road, Tayabas, Quezon, Philippines',24144.00,124),(318,'Tayabas Bypass Road, Tayabas, Quezon, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',154570.00,124),(319,'Maligaya Street, Quezon City, Metro Manila, Philippines','Tarlac State University (TSU), Romulo Boulevard, Tarlac City, Tarlac, Philippines',126907.00,125),(320,'Tarlac State University (TSU), Romulo Boulevard, Tarlac City, Tarlac, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',127214.00,125),(323,'Phase 9 Bagong Silang, Caloocan, Metro Manila, Philippines','Hagonoy Sports Complex, Capistrano Street, Taguig, Metro Manila, Philippines',49724.00,126),(324,'Hagonoy Sports Complex, Capistrano Street, Taguig, Metro Manila, Philippines','Phase 9 Bagong Silang, Caloocan, Metro Manila, Philippines',38464.00,126),(325,'Phase 9 Bagong Silang, Caloocan, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',46602.00,127),(326,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Phase 9 Bagong Silang, Caloocan, Metro Manila, Philippines',43230.00,127),(327,'Maligaya Street, Quezon City, Metro Manila, Philippines','Makati City Hall, Angono, Makati, Metro Manila, Philippines',27575.00,128),(328,'Makati City Hall, Angono, Makati, Metro Manila, Philippines','Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines',108357.00,128),(329,'Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines','Tayabas Bypass Road, Tayabas, Quezon, Philippines',24144.00,128),(330,'Tayabas Bypass Road, Tayabas, Quezon, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',154570.00,128),(331,'Maligaya Street, Quezon City, Metro Manila, Philippines','Makati City Hall, Angono, Makati, Metro Manila, Philippines',27575.00,129),(332,'Makati City Hall, Angono, Makati, Metro Manila, Philippines','Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines',108357.00,129),(333,'Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines','Tayabas Bypass Road, Tayabas, Quezon, Philippines',24144.00,129),(334,'Tayabas Bypass Road, Tayabas, Quezon, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',154570.00,129),(335,'Maligaya Street, Quezon City, Metro Manila, Philippines','Makati City Hall, Angono, Makati, Metro Manila, Philippines',27575.00,130),(336,'Makati City Hall, Angono, Makati, Metro Manila, Philippines','Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines',108357.00,130),(337,'Taytay Falls / Majayjay Falls, Brgy, Majayjay, Laguna, Philippines','Tayabas Bypass Road, Tayabas, Quezon, Philippines',24144.00,130),(338,'Tayabas Bypass Road, Tayabas, Quezon, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',154570.00,130),(339,'Pinagbuhatan, Metro Manila, Philippines','Malabon, Metro Manila, Philippines',25372.00,131),(340,'Malabon, Metro Manila, Philippines','Marilao, Bulacan, Philippines',19096.00,131),(341,'Marilao, Bulacan, Philippines','Cubao, Quezon City, Metro Manila, Philippines',26853.00,131),(342,'Cubao, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',20404.00,131),(343,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Pinagbuhatan, Metro Manila, Philippines',10691.00,131),(344,'Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','GBR Museum, General Trias, Cavite, Philippines',15522.00,132),(345,'GBR Museum, General Trias, Cavite, Philippines','Paradizoo Theme Park, Mendez, Cavite, Philippines',22743.00,132),(346,'Paradizoo Theme Park, Mendez, Cavite, Philippines','Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',47421.00,132),(347,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines',17461.00,132),(348,'Makati, Metro Manila, Philippines','Mina Falls, Trece Martires, Cavite, Philippines',47135.00,133),(349,'Mina Falls, Trece Martires, Cavite, Philippines','Makati, Metro Manila, Philippines',53769.00,133),(350,'Maligaya Street, Quezon City, Metro Manila, Philippines','Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines',141165.00,134),(351,'Agoncillo Municipal Hall, Agoncillo, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',138345.00,134),(352,'Moa Pasay, Seaside Boulevard, 123, Pasay, Metro Manila, Philippines','Makati City Hall, Angono, Makati, Metro Manila, Philippines',7850.00,135),(353,'Makati City Hall, Angono, Makati, Metro Manila, Philippines','Moa Pasay, Seaside Boulevard, 123, Pasay, Metro Manila, Philippines',8445.00,135),(354,'Moa Pasay, Seaside Boulevard, 123, Pasay, Metro Manila, Philippines','Makati City Hall, Angono, Makati, Metro Manila, Philippines',7850.00,136),(355,'Makati City Hall, Angono, Makati, Metro Manila, Philippines','Moa Pasay, Seaside Boulevard, 123, Pasay, Metro Manila, Philippines',8445.00,136),(356,'Moa Pasay, Seaside Boulevard, 123, Pasay, Metro Manila, Philippines','Makati City Hall, Angono, Makati, Metro Manila, Philippines',7850.00,137),(357,'Makati City Hall, Angono, Makati, Metro Manila, Philippines','Moa Pasay, Seaside Boulevard, 123, Pasay, Metro Manila, Philippines',8445.00,137),(358,'MOA Arena, J.W. Diokno Boulevard, Pasay, Metro Manila, Philippines','Baguio, Benguet, Philippines',257423.00,138),(359,'Baguio, Benguet, Philippines','MOA Arena, J.W. Diokno Boulevard, Pasay, Metro Manila, Philippines',258128.00,138),(360,'Maligaya Street, Quezon City, Metro Manila, Philippines','Maragondon, Cavite, Philippines',96359.00,139),(361,'Maragondon, Cavite, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',94521.00,139),(362,'Makati Medical Center, Amorsolo Street, Legazpi Village, Makati, Metro Manila, Philippines','Mandaluyong City Hall, Maysilo Circle, Mandaluyong, Metro Manila, Philippines',4812.00,140),(363,'Mandaluyong City Hall, Maysilo Circle, Mandaluyong, Metro Manila, Philippines','Makati Medical Center, Amorsolo Street, Legazpi Village, Makati, Metro Manila, Philippines',4693.00,140),(364,'Makabayan Street, Diliman, Quezon City, Metro Manila, Philippines','Manila Zoo, Adriatico Street, Malate, Manila, Metro Manila, Philippines',13620.00,141),(365,'Manila Zoo, Adriatico Street, Malate, Manila, Metro Manila, Philippines','Makabayan Street, Diliman, Quezon City, Metro Manila, Philippines',12114.00,141),(366,'Makati Medical Center, Amorsolo Street, Legazpi Village, Makati, Metro Manila, Philippines','Intramuros, Manila, Metro Manila, Philippines',9226.00,142),(367,'Intramuros, Manila, Metro Manila, Philippines','Makati Medical Center, Amorsolo Street, Legazpi Village, Makati, Metro Manila, Philippines',7845.00,142),(368,'Maligaya Street, Quezon City, Metro Manila, Philippines','Silang Specialists Medical Center, Aguinaldo Highway, Silang, Cavite, Philippines',82115.00,143),(369,'Silang Specialists Medical Center, Aguinaldo Highway, Silang, Cavite, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',82515.00,143),(370,'7573 Swimming Pool, Novaliches, Caloocan, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',2166.00,144),(371,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','7573 Swimming Pool, Novaliches, Caloocan, Metro Manila, Philippines',2374.00,144),(372,'Guiguinto Municipal Hall, MacArthur Highway, Guiguinto, Bulacan, Philippines','Marilao Public Market, Marilao, Bulacan, Philippines',11564.00,145),(373,'Marilao Public Market, Marilao, Bulacan, Philippines','Guiguinto Municipal Hall, MacArthur Highway, Guiguinto, Bulacan, Philippines',11719.00,145),(374,'San Juan, Metro Manila, Philippines','Parang High School, Tandang Sora Street, Marikina, Metro Manila, Philippines',13435.00,146),(375,'Parang High School, Tandang Sora Street, Marikina, Metro Manila, Philippines','San Juan, Metro Manila, Philippines',13334.00,146),(376,'Maligaya Elementary School, Quezon City, Metro Manila, Philippines','Makati City Hall, Angono, Makati, Metro Manila, Philippines',28506.00,147),(377,'Makati City Hall, Angono, Makati, Metro Manila, Philippines','Maligaya Elementary School, Quezon City, Metro Manila, Philippines',27038.00,147),(378,'Makati Medical Center, Amorsolo Street, Legazpi Village, Makati, Metro Manila, Philippines','Bacoor, Cavite, Philippines',24577.00,148),(379,'Bacoor, Cavite, Philippines','Makati Medical Center, Amorsolo Street, Legazpi Village, Makati, Metro Manila, Philippines',24319.00,148);
/*!40000 ALTER TABLE `trip_distances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact_number` varchar(13) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Client','Admin','Super Admin') NOT NULL DEFAULT 'Client',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `company_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `contact_number` (`contact_number`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (24,'Jeric Ken','Verano','vjericken@gmail.com','0949-304-8945','$2y$10$pbk92bx7LaFUFemHevGI/.HeoMFqZqpS61Hc94cKvJGUkj3.XBqmO','Client','2025-04-22 20:27:06',NULL,NULL,NULL),(25,'Spike','Spiegel','spike@gmail.com','0940-935-0945','$2y$10$7QUTUyBfvL0MzhqY4DpU.eJp81K7YLJrramoSI8NuvdlUn.rDVbxy','Client','2025-04-22 20:49:42',NULL,NULL,NULL),(26,'test','test','test@kinglang.com','0943-849-3549','$2y$10$J3OdVJcJUWT0WiulbkBV2OoQQZSs6CvCNBr.YAFv0l2bqPjum.DeO','Client','2025-04-22 20:56:37',NULL,NULL,NULL),(27,'testing','testing','testing@gmail.com','0934-905-9345','$2y$10$fiiSYAJgCagAqyTlukNQ6OpBtCtJrDmlLy2tGdQMsh1gpwKfOgg02','Client','2025-04-22 21:04:23',NULL,NULL,NULL),(28,'admin','admin','admin@kinglang.com','0938-494-5893','$2y$10$KmG5TpTF7hO//POE.g15X.veAH3KOel.x6BJDHx42zIXlfCQNQt4K','Admin','2025-04-22 21:05:35',NULL,NULL,NULL),(29,'Super','Admin','superadmin@kinglang.com','0985-938-4595','$2y$10$6r6jA5/xVXPATB4zDSUoCOkVGj7nTIcbhuSYq.rNu12mhxJhmShBG','Super Admin','2025-04-22 21:16:03',NULL,NULL,NULL),(30,'Kenny','Ackerman','kenny@gmail.com','0948-329-4858','$2y$10$9yW8reCzPOnBPhJHh3pty.Q8n0FWewuxGdKFI7244bNokCDISAR7C','Client','2025-04-24 10:13:40',NULL,NULL,'');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-01  3:14:19
