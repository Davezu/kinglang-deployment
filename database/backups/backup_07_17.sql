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
) ENGINE=InnoDB AUTO_INCREMENT=296 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_notifications`
--

LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
INSERT INTO `admin_notifications` VALUES (209,'booking_request','New booking request from Clarisse Anne Mercado',155,1,'2025-05-05 23:17:40'),(210,'booking_request','New booking request from Clarisse Anne Mercado',156,1,'2025-04-14 23:24:35'),(211,'booking_payment','Booking ID 156 for Clarisse Anne Mercado is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',156,1,'2025-05-01 23:32:59'),(212,'booking_request','New booking request from Miguel Andres Bautista',157,1,'2025-02-28 23:52:21'),(213,'booking_request','New booking request from Miguel Andres Bautista',157,1,'2025-02-28 23:54:23'),(214,'booking_payment','Booking ID 157 for Miguel Andres Bautista is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',157,1,'2025-03-04 23:58:29'),(215,'booking_request','New booking request from Miguel Andres Bautista',158,1,'2025-05-06 00:03:24'),(216,'booking_request','New booking request from Miguel Andres Bautista',158,1,'2025-05-06 00:06:49'),(217,'booking_request','New booking request from Miguel Andres Bautista',158,1,'2025-04-06 00:08:10'),(218,'booking_payment','Booking ID 158 for Miguel Andres Bautista is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',158,1,'2025-04-10 00:11:03'),(219,'booking_request','New booking request from Shaira Nicole Villanueva',159,1,'2025-04-10 00:19:20'),(220,'booking_payment','Booking ID 159 for Shaira Nicole Villanueva is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',159,1,'2025-05-06 00:25:49'),(221,'booking_request','New booking request from Shaira Nicole Villanueva',160,1,'2025-02-06 00:39:46'),(222,'booking_request','New booking request from Shaira Nicole Villanueva',160,1,'2025-02-06 00:40:24'),(223,'booking_payment','Booking ID 160 for Shaira Nicole Villanueva is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',160,1,'2025-02-10 00:46:13'),(224,'booking_request','New booking request from Rafael Lorenzo Garcia',161,1,'2025-05-06 00:52:59'),(225,'booking_request','New booking request from Rafael Lorenzo Garcia',161,1,'2025-01-06 00:54:29'),(226,'booking_request','New booking request from Rafael Lorenzo Garcia',162,1,'2025-05-06 01:03:23'),(227,'booking_request','New booking request from Rafael Lorenzo Garcia',163,1,'2025-05-06 01:08:39'),(228,'booking_request','New booking request from Rafael Lorenzo Garcia',163,1,'2025-01-06 01:10:01'),(229,'booking_payment','Booking ID 163 for Rafael Lorenzo Garcia is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',163,1,'2025-05-06 01:11:52'),(230,'booking_request','New booking request from Rafael Lorenzo Garcia',164,1,'2025-05-06 01:14:46'),(231,'booking_request','New booking request from Rafael Lorenzo Garcia',164,1,'2025-02-06 01:16:02'),(232,'booking_payment','Booking ID 164 for Rafael Lorenzo Garcia is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',164,1,'2025-05-06 01:18:07'),(233,'booking_request','New booking request from James Benedict Ramirez',165,1,'2025-05-06 01:21:08'),(234,'booking_request','New booking request from James Benedict Ramirez',165,1,'2025-03-06 01:22:59'),(235,'booking_request','New booking request from James Benedict Ramirez',166,1,'2025-05-06 01:30:10'),(236,'booking_request','New booking request from James Benedict Ramirez',166,1,'2025-02-06 01:30:53'),(237,'booking_payment','Booking ID 166 for James Benedict Ramirez is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',166,1,'2025-05-06 01:33:02'),(238,'booking_request','New booking request from Juan Dela Cruz',167,1,'2025-05-06 11:19:17'),(239,'booking_request','New booking request from Juan Dela Cruz',168,1,'2025-05-07 09:18:43'),(240,'booking_request','New booking request from Juan Dela Cruz',169,1,'2025-05-07 09:45:10'),(241,'rebooking_confirmed','Rebooking confirmed for Juan Dela Cruz to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',169,1,'2025-05-07 09:45:33'),(242,'booking_request','New booking request from Juan Dela Cruz',170,1,'2025-05-08 18:16:23'),(243,'rebooking_confirmed','Rebooking confirmed for Juan Dela Cruz to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',170,1,'2025-05-08 18:22:12'),(244,'booking_canceled','Booking ID 168 for Juan Dela Cruz has been automatically canceled due to the client not making payment within 2 days. Please review.',168,1,'2025-05-10 14:11:01'),(245,'booking_canceled','Booking ID 170 for Juan Dela Cruz has been automatically canceled due to the client not making payment within 2 days. Please review.',170,1,'2025-05-11 22:51:57'),(246,'booking_payment','Booking ID 162 for Rafael Lorenzo Garcia is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',162,1,'2025-05-11 22:51:57'),(247,'booking_payment','Booking ID 167 for Juan Dela Cruz is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',167,1,'2025-05-11 22:51:57'),(248,'booking_request','New booking request from Juan Dela Cruz',171,1,'2025-05-11 23:19:44'),(249,'booking_request','New booking request from Juan Dela Cruz',172,1,'2025-05-13 08:03:24'),(250,'booking_canceled','Booking ID 172 for Juan Dela Cruz has been automatically canceled due to the client not making payment within 2 days. Please review.',172,1,'2025-05-20 03:24:35'),(251,'booking_cancelled_by_client','Booking #171 to San Pedro, Laguna, Philippines cancelled by Juan Dela Cruz. Reason: Ayoko nalang pala',171,1,'2025-05-20 03:26:29'),(252,'booking_request','New booking request from Juan Dela Cruz',173,1,'2025-05-20 03:36:06'),(253,'booking_request','New booking request from Maria Gomez',174,1,'2025-05-20 10:30:39'),(254,'booking_request','New booking request from Maria Gomez',175,1,'2025-05-20 10:42:04'),(255,'booking_payment','Booking ID 174 for Maria Gomez is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',174,1,'2025-05-28 02:27:33'),(256,'booking_request','New booking request from Juan Dela Cruz',176,1,'2025-06-08 15:51:11'),(257,'booking_request','New booking request from Juan Dela Cruz',177,1,'2025-06-14 01:26:38'),(258,'booking_request','New booking request from Juan Dela Cruz',178,1,'2025-06-14 01:31:00'),(259,'booking_request','New booking request from Juan Dela Cruz',179,1,'2025-06-14 01:40:29'),(260,'booking_request','New booking request from Juan Dela Cruz',180,1,'2025-06-14 12:07:23'),(261,'booking_request','New booking request from Juan Dela Cruz',181,1,'2025-06-15 16:33:46'),(262,'booking_request','New booking request from Juan Dela Cruz',182,1,'2025-06-15 16:39:37'),(263,'booking_request','New booking request from Juan Dela Cruz',183,1,'2025-06-15 16:54:10'),(264,'booking_canceled','Booking ID 179 for Juan Dela Cruz has been automatically canceled due to the client not making payment within 2 days. Please review.',179,1,'2025-06-19 07:06:54'),(265,'booking_canceled','Booking ID 180 for Juan Dela Cruz has been automatically canceled due to the client not making payment within 2 days. Please review.',180,1,'2025-06-19 07:06:54'),(266,'booking_canceled','Booking ID 181 for Juan Dela Cruz has been automatically canceled due to the client not making payment within 2 days. Please review.',181,1,'2025-06-19 07:06:54'),(267,'booking_canceled','Booking ID 182 for Juan Dela Cruz has been automatically canceled due to the client not making payment within 2 days. Please review.',182,1,'2025-06-19 07:06:54'),(268,'booking_canceled','Booking ID 183 for Juan Dela Cruz has been automatically canceled due to the client not making payment within 2 days. Please review.',183,1,'2025-06-19 07:06:54'),(269,'booking_request','New booking request from Jeric Ken Verano',184,1,'2025-06-26 07:10:07'),(270,'booking_request','New booking request from Jeric Ken Verano',185,1,'2025-07-12 07:50:20'),(271,'booking_request','New booking request from Shogo Kai',186,1,'2025-07-12 08:31:12'),(272,'booking_canceled','Booking ID 185 for Jeric Ken Verano has been automatically canceled due to the client not making payment within 2 days. Please review.',185,1,'2025-07-16 08:35:19'),(273,'booking_auto_canceled','Booking ID 155 for Clarisse Anne Mercado has been automatically cancelled due to lack of review by the tour date.',155,1,'2025-07-16 10:49:44'),(274,'booking_auto_canceled','Booking ID 175 for Maria Gomez has been automatically cancelled due to lack of review by the tour date.',175,1,'2025-07-16 10:49:44'),(275,'booking_auto_canceled','Booking ID 176 for Juan Dela Cruz has been automatically cancelled due to lack of review by the tour date.',176,1,'2025-07-16 10:49:44'),(276,'booking_auto_canceled','Booking ID 178 for Juan Dela Cruz has been automatically cancelled due to lack of review by the tour date.',178,1,'2025-07-16 10:49:44'),(277,'booking_auto_canceled','Booking ID 186 for Shogo Kai has been automatically cancelled due to lack of review by the tour date.',186,1,'2025-07-16 10:49:44'),(278,'booking_auto_canceled','Booking ID 155 for Clarisse Anne Mercado has been automatically cancelled due to lack of review by the tour date.',155,1,'2025-07-12 12:25:44'),(279,'booking_auto_canceled','Booking ID 155 for Clarisse Anne Mercado has been automatically cancelled due to lack of review by the tour date.',155,1,'2025-07-12 12:28:00'),(280,'booking_request','New booking request from Jeric Ken Verano',187,1,'2025-07-13 12:18:28'),(281,'booking_request','New booking request from Jeric Ken Verano',188,1,'2025-07-13 13:05:48'),(282,'booking_cancelled_by_client','Booking #187 to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines cancelled by Jeric Ken Verano. Reason: hehe',187,1,'2025-07-13 13:28:24'),(283,'booking_request','New booking request from Juan Dela Cruz',189,1,'2025-07-16 07:07:12'),(284,'payment_submitted','New payment of PHP 26,981.09 submitted by   for booking #189',189,1,'2025-07-16 07:30:15'),(285,'payment_submitted','New payment of PHP 26,981.09 submitted by Juan Dela Cruz for booking #189',189,1,'2025-07-16 07:33:16'),(286,'booking_cancelled_by_client','Booking #189 to Batangas Beach Resorts, Batangas, Philippines cancelled by Juan Dela Cruz. Reason: yaw ko na pala',189,1,'2025-07-16 07:59:41'),(287,'booking_request','New booking request from Juan Dela Cruz',190,1,'2024-09-16 13:27:00'),(288,'booking_request','New booking request from Juan Dela Cruz',191,1,'2024-09-16 13:27:57'),(289,'payment_submitted','New payment of PHP 24,431.42 submitted by Juan Dela Cruz for booking #191',191,1,'2024-09-16 13:29:04'),(290,'payment_submitted','New payment of PHP 33,726.36 submitted by Juan Dela Cruz for booking #190',190,1,'2024-09-16 13:29:20'),(291,'booking_request','New booking request from Juan Dela Cruz',192,1,'2025-07-16 13:32:47'),(292,'booking_request','New booking request from Juan Dela Cruz',193,1,'2025-07-16 13:41:00'),(293,'booking_request','New booking request from Juan Dela Cruz',194,1,'2025-07-16 13:41:00'),(294,'booking_cancelled_by_client','Booking #193 to Caliraya Resort Club, Lumban, Laguna, Philippines cancelled by Juan Dela Cruz. Reason: yaw ko na',193,1,'2025-07-16 13:41:33'),(295,'booking_request','New booking request from Shogo Kai',195,1,'2025-07-16 13:52:54');
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
) ENGINE=InnoDB AUTO_INCREMENT=396 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_buses`
--

LOCK TABLES `booking_buses` WRITE;
/*!40000 ALTER TABLE `booking_buses` DISABLE KEYS */;
INSERT INTO `booking_buses` VALUES (307,155,1),(308,155,2),(309,156,1),(312,157,1),(313,157,2),(317,158,1),(318,158,2),(319,159,1),(321,160,1),(323,161,1),(324,162,1),(325,162,2),(327,163,1),(329,164,1),(332,165,1),(333,165,2),(335,166,1),(336,167,3),(337,167,4),(338,168,5),(339,168,6),(340,168,7),(341,168,8),(342,169,9),(343,169,10),(344,169,11),(345,169,12),(346,170,1),(347,170,2),(348,170,3),(349,170,4),(350,171,1),(351,172,1),(352,172,2),(353,172,3),(354,172,4),(355,173,1),(356,174,1),(357,175,1),(358,175,2),(359,176,1),(360,176,2),(361,177,2),(362,177,3),(363,177,4),(364,177,5),(365,177,6),(366,178,2),(367,178,3),(368,178,4),(369,179,2),(370,179,3),(371,180,7),(372,180,8),(373,181,9),(374,181,10),(375,182,11),(376,183,2),(377,184,1),(378,184,2),(379,185,1),(380,185,2),(381,185,3),(382,186,1),(383,186,2),(384,186,3),(385,187,1),(386,188,1),(387,188,2),(388,189,1),(389,189,2),(390,190,1),(391,191,1),(392,192,1),(393,193,2),(394,194,2),(395,195,3);
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
  `discount` decimal(10,2) DEFAULT NULL,
  `discount_type` varchar(20) DEFAULT 'percentage',
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `gross_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_costs`
--

LOCK TABLES `booking_costs` WRITE;
/*!40000 ALTER TABLE `booking_costs` DISABLE KEYS */;
INSERT INTO `booking_costs` VALUES (73,42138.02,19560.00,30.06,155,50.20,3018.02,39120.00,NULL,'percentage',NULL,NULL),(74,37675.43,20772.00,122.14,156,50.20,6131.43,41544.00,20.98,'flat',10000.00,47675.43),(75,39707.03,19560.00,49.79,157,50.20,4998.92,39120.00,10.00,'percentage',NULL,44118.92),(76,39276.01,19560.00,45.02,158,50.20,4520.01,39120.00,10.00,'percentage',NULL,43640.01),(77,26084.16,20772.00,105.82,159,50.20,5312.16,20772.00,NULL,'percentage',NULL,NULL),(78,26628.83,20772.00,116.67,160,50.20,5856.83,20772.00,NULL,'percentage',NULL,NULL),(79,45182.42,20772.00,172.08,161,50.20,8638.42,41544.00,9.96,'flat',5000.00,50182.42),(80,57002.69,20772.00,295.91,162,50.20,29709.36,41544.00,20.00,'percentage',NULL,71253.36),(81,21839.58,19560.00,45.41,163,50.20,2279.58,19560.00,NULL,'percentage',NULL,NULL),(82,32368.70,20772.00,231.01,164,50.20,11596.70,20772.00,NULL,'percentage',NULL,NULL),(83,58134.10,20772.00,165.24,165,50.20,16590.10,41544.00,NULL,'percentage',NULL,NULL),(84,27973.19,20772.00,143.45,166,50.20,7201.19,20772.00,NULL,'percentage',NULL,NULL),(85,355906.08,71040.00,481.09,167,50.20,48301.44,426240.00,25.00,'percentage',NULL,474541.44),(86,72636.47,20772.00,102.98,168,50.20,20678.38,83088.00,30.00,'percentage',NULL,103766.38),(87,72357.86,20772.00,102.98,169,60.47,24908.80,83088.00,33.00,'percentage',NULL,107996.80),(88,113982.00,20772.00,102.98,170,75.00,30894.00,83088.00,NULL,'percentage',NULL,NULL),(89,29193.75,20772.00,112.29,171,75.00,8421.75,20772.00,NULL,'percentage',NULL,NULL),(90,76175.86,20772.00,124.42,172,61.50,30607.32,83088.00,33.00,'percentage',NULL,113695.32),(91,156508.98,71040.00,517.38,173,61.50,31818.87,142080.00,10.00,'percentage',NULL,173898.87),(92,18877.61,19560.00,23.01,174,61.50,1415.12,19560.00,10.00,'percentage',NULL,20975.12),(93,41950.23,19560.00,23.01,175,61.50,2830.23,39120.00,NULL,'percentage',NULL,NULL),(94,43229.43,19560.00,33.41,176,61.50,4109.43,39120.00,NULL,'percentage',NULL,NULL),(95,73412.91,19560.00,23.01,177,61.50,7075.58,97800.00,30.00,'percentage',NULL,104875.58),(96,62925.35,19560.00,23.01,178,61.50,4245.35,58680.00,NULL,'percentage',NULL,NULL),(97,34253.90,19560.00,30.06,179,61.50,3697.38,39120.00,20.00,'percentage',NULL,42817.38),(98,105390.40,45020.00,124.80,180,61.50,15350.40,90040.00,NULL,'percentage',NULL,NULL),(99,87982.83,19560.00,79.21,181,61.50,9742.83,78240.00,NULL,'percentage',NULL,NULL),(100,54498.36,20772.00,210.64,182,61.50,12954.36,41544.00,NULL,'percentage',NULL,NULL),(101,23016.92,19560.00,56.21,183,61.50,3456.92,19560.00,NULL,'percentage',NULL,NULL),(102,45881.69,19560.00,96.42,184,61.50,11859.66,39120.00,10.00,'percentage',NULL,50979.66),(103,109444.82,19560.00,23.01,185,61.50,4245.35,117360.00,10.00,'percentage',NULL,121605.35),(104,85123.75,19560.00,23.01,186,61.50,4245.35,117360.00,30.00,'percentage',NULL,121605.35),(105,36481.61,19560.00,23.01,187,61.50,1415.12,39120.00,10.00,'percentage',NULL,40535.12),(106,20975.12,19560.00,23.01,188,61.50,2830.23,39120.00,50.00,'percentage',NULL,41950.23),(107,53962.18,20772.00,210.64,189,61.50,25908.72,41544.00,20.00,'percentage',NULL,67452.72),(108,33726.36,20772.00,210.64,190,61.50,12954.36,20772.00,NULL,'percentage',NULL,NULL),(109,24431.42,19560.00,79.21,191,61.50,4871.42,19560.00,NULL,'percentage',NULL,NULL),(110,24431.42,19560.00,79.21,192,61.50,4871.42,19560.00,NULL,'percentage',NULL,NULL),(111,34979.12,20772.00,231.01,193,61.50,14207.12,20772.00,NULL,'percentage',NULL,NULL),(112,34979.12,20772.00,231.01,194,61.50,14207.12,20772.00,NULL,'percentage',NULL,NULL),(113,25184.97,20772.00,231.01,195,61.50,14207.12,20772.00,10.00,'percentage',NULL,27983.30);
/*!40000 ALTER TABLE `booking_costs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_driver`
--

DROP TABLE IF EXISTS `booking_driver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_driver` (
  `booking_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_driver`
--

LOCK TABLES `booking_driver` WRITE;
/*!40000 ALTER TABLE `booking_driver` DISABLE KEYS */;
INSERT INTO `booking_driver` VALUES (181,2),(181,3),(182,4),(183,4),(184,2),(184,3),(185,2),(185,3),(185,4),(186,2),(186,3),(186,4),(187,2),(188,2),(188,3),(189,2),(189,3),(190,2),(191,2),(192,2),(193,3),(194,3),(195,4);
/*!40000 ALTER TABLE `booking_driver` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_stops`
--

LOCK TABLES `booking_stops` WRITE;
/*!40000 ALTER TABLE `booking_stops` DISABLE KEYS */;
INSERT INTO `booking_stops` VALUES (59,1,'BGC Arts Center, 26th Street, Taguig, Metro Manila, Philippines',157),(60,2,'Terra 28th, 28th Street, Taguig, Metro Manila, Philippines',157),(63,1,'National Museum Complex, Padre Burgos Avenue, Ermita, Manila, Metro Manila, Philippines',158),(64,2,'Riverbanks Center, Riverbanks Avenue, Marikina, Metro Manila, Philippines',158),(67,1,'REPTILAND ADVENTURE REPTILAND ADVENTURE, Alfonso, Cavite, Philippines',161),(68,2,'Tagaytay Ridge, Tagaytay, Cavite, Philippines',161),(69,1,'Japanese Garden, Lumban - Caliraya - Cavinti Road, Cavinti, Laguna, Philippines',162),(70,2,'Pagsanjan Falls Lodge and Summer Resort, Pagsanjan, Laguna, Philippines',162),(73,1,'Marikina Shoe Museum, J. P. Rizal Street, Marikina, Metro Manila, Philippines',163),(74,2,'Riverbanks Center, Riverbanks Avenue, Marikina, Metro Manila, Philippines',163),(75,1,'GBR Museum, General Trias, Cavite, Philippines',168),(76,2,'Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines',168),(77,1,'GBR Museum, General Trias, Cavite, Philippines',169),(78,2,'Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines',169),(79,1,'GBR Museum, General Trias, Cavite, Philippines',170),(80,2,'Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines',170),(81,1,'GBR Museum, General Trias, Cavite, Philippines',172),(82,2,'Paradizoo Theme Park, Mendez, Cavite, Philippines',172),(83,1,'National Museum of Natural History, Teodoro F. Valencia Circle, Ermita, Manila, Metro Manila, Philippines',173);
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
  `payment_deadline` datetime DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_by` enum('Client','Admin','Super Admin') DEFAULT 'Client',
  PRIMARY KEY (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (155,'The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','2025-05-16','2025-05-16',1,2,42138.02,'Canceled','Unpaid',43,0,0,'2025-05-05 23:17:40','04:30:00',NULL,NULL,NULL,'Client'),(156,'Camp Benjamin, Alfonso-Maragondon Road, Alfonso, Cavite, Philippines','SM Mall of Asia, Seaside Boulevard, Pasay, Metro Manila, Philippines','2025-04-30','2025-05-01',2,1,0.00,'Completed','Paid',43,0,0,'2025-04-14 23:24:35','04:00:00','2025-04-14 23:25:45','2025-04-17 07:25:45','2025-05-01 23:32:59','Client'),(157,'Philippine Stock Exchange Centre, Pearl Drive, Ortigas Center, Pasig, Metro Manila, Philippines','Alabang Town Center, Madrigal Avenue, Muntinlupa, Metro Manila, Philippines','2025-03-04','2025-03-04',1,2,0.00,'Completed','Paid',42,0,0,'2025-02-28 23:52:21','04:30:00','2025-02-28 23:56:32','2025-03-03 07:56:32','2025-03-04 23:58:29','Client'),(158,'Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines','SM Megamall, Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong, Metro Manila, Philippines','2025-04-09','2025-04-09',1,2,0.00,'Completed','Paid',42,0,0,'2025-05-06 00:03:24','04:00:00','2025-04-06 00:08:57','2025-04-08 08:08:57','2025-04-10 00:11:03','Client'),(159,'Tanay Adventure Camp, Tanay, Rizal, Philippines','Greenhills Shopping Center, San Juan, Metro Manila, Philippines','2025-04-23','2025-04-23',1,1,0.00,'Completed','Paid',41,0,0,'2025-04-10 00:19:20','04:00:00','2025-04-10 00:20:11','2025-04-12 08:20:11','2025-05-06 00:25:49','Client'),(160,'Tanay Adventure Camp, Tanay, Rizal, Philippines','Bonifacio Global City, Forbestown Road, Bonifacio Global City, Taguig, Metro Manila, Philippines','2025-02-09','2025-02-09',1,1,0.00,'Completed','Paid',41,0,0,'2025-02-06 00:39:46','04:00:00','2025-02-06 00:42:38','2025-02-08 08:42:38','2025-02-10 00:46:13','Client'),(161,'Puzzle Mansion, I. Cuadra, Tagaytay, Cavite, Philippines','Manila City Hall, Padre Burgos Avenue, Ermita, Manila, Metro Manila, Philippines','2025-01-09','2025-01-10',2,1,0.00,'Completed','Paid',40,0,0,'2025-05-06 00:52:59','04:30:00','2025-01-06 00:55:16','2025-01-08 08:55:16',NULL,'Client'),(162,'Cavinti Underground River and Cave Complex, Cavinti, Laguna, Philippines','Philippine Science High School - Main Campus, Agham, Quezon City, Metro Manila, Philippines','2025-05-10','2025-05-10',1,2,0.00,'Completed','Paid',40,0,0,'2025-05-06 01:03:23','04:00:00','2025-05-06 01:27:16','2025-05-08 09:27:16','2025-05-11 22:51:57','Client'),(163,'Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines','Luneta Park, Ermita, Manila, Metro Manila, Philippines','2025-01-10','2025-01-10',1,1,0.00,'Completed','Paid',40,0,0,'2025-05-06 01:08:39','04:30:00','2025-01-06 01:10:31','2025-01-08 09:10:31','2025-05-06 01:11:52','Client'),(164,'Caliraya Resort Club, Lumban, Laguna, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','2025-02-20','2025-02-20',1,1,0.00,'Completed','Paid',40,0,0,'2025-05-06 01:14:46','04:30:00','2025-02-06 01:16:24','2025-02-08 09:16:24','2025-05-06 01:18:07','Client'),(165,'Villa Escudero Plantations and Resort, Tiaong, Quezon, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','2025-03-12','2025-03-12',1,2,0.00,'Completed','Paid',38,0,0,'2025-05-06 01:21:08','05:00:00','2025-03-06 01:23:16','2025-03-08 09:23:16',NULL,'Client'),(166,'Forest Club Eco Resort, F.T. San Luis Avenue, Bay, Laguna, Philippines','SM Megamall, Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong, Metro Manila, Philippines','2025-02-24','2025-02-24',1,1,0.00,'Completed','Paid',38,0,0,'2025-05-06 01:30:10','04:30:00','2025-02-06 01:31:05','2025-02-08 09:31:05','2025-05-06 01:33:02','Client'),(167,'Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','2025-05-09','2025-05-11',3,2,0.00,'Completed','Paid',33,0,0,'2025-05-06 11:19:17','04:00:00','2025-05-06 11:25:50','2025-05-08 19:25:50','2025-05-11 22:51:57','Client'),(168,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','2025-05-10','2025-05-10',1,4,72636.47,'Confirmed','Unpaid',33,0,1,'2025-05-07 09:18:43','04:00:00','2025-05-07 09:20:01','2025-05-09 17:20:01',NULL,'Client'),(169,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','2025-05-10','2025-05-10',1,4,72357.86,'Confirmed','Unpaid',33,0,1,'2025-05-07 09:45:10','04:00:00','2025-05-07 09:45:33',NULL,NULL,'Client'),(170,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','2025-05-12','2025-05-12',1,4,113982.00,'Canceled','Unpaid',33,0,0,'2025-05-08 18:16:23','04:30:00','2025-05-08 18:22:12','2025-05-11 02:22:12',NULL,'Client'),(171,'San Pedro, Laguna, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-05-15','2025-05-15',1,1,29193.75,'Canceled','Unpaid',33,0,0,'2025-05-11 23:19:44','04:00:00',NULL,NULL,NULL,'Client'),(172,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori College-Bacoor, Inc., Bacoor, Cavite, Philippines','2025-05-17','2025-05-17',1,4,76175.86,'Canceled','Unpaid',33,0,0,'2025-05-13 08:03:24','04:00:00','2025-05-13 08:04:26','2025-05-15 16:04:26',NULL,'Client'),(173,'Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','2025-05-31','2025-06-01',2,1,0.00,'Completed','Paid',33,0,0,'2025-05-20 03:36:06','07:30:00','2025-05-20 03:43:24','2025-05-22 11:43:24',NULL,'Client'),(174,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','2025-05-23','2025-05-23',1,1,0.00,'Completed','Paid',47,0,0,'2025-05-20 10:30:39','04:00:00','2025-05-20 10:33:18','2025-05-22 18:33:18','2025-05-28 02:27:33','Client'),(175,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','2025-05-26','2025-05-26',1,2,41950.23,'Canceled','Unpaid',47,1,0,'2025-05-20 10:42:04','04:00:00',NULL,NULL,NULL,'Client'),(176,'Maligaya Street, Quezon City, Metro Manila, Philippines','SM North EDSA, Epifanio de los Santos Avenue, Quezon City, Metro Manila, Philippines','2025-06-18','2025-06-18',1,2,43229.43,'Canceled','Unpaid',33,0,0,'2025-06-08 15:51:11','04:00:00',NULL,NULL,NULL,'Client'),(177,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','2025-06-20','2025-06-20',1,5,0.00,'Completed','Paid',33,0,0,'2025-06-14 01:26:38','04:00:00','2025-06-14 01:28:37','2025-06-16 09:28:37',NULL,'Client'),(178,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','2025-06-27','2025-06-27',1,3,62925.35,'Canceled','Unpaid',33,0,0,'2025-06-14 01:31:00','04:00:00',NULL,NULL,NULL,'Client'),(179,'The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','2025-06-24','2025-06-24',1,2,34253.90,'Canceled','Unpaid',33,0,0,'2025-06-14 01:40:29','04:00:00','2025-06-14 02:10:30','2025-06-16 10:10:30',NULL,'Client'),(180,'Pampanga Provincial Capitol, Capitol Boulevard, San Fernando City, Pampanga, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-06-20','2025-06-20',1,2,105390.40,'Canceled','Unpaid',33,0,0,'2025-06-14 12:07:23','04:00:00','2025-06-14 12:13:05','2025-06-16 20:13:05',NULL,'Client'),(181,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-06-20','2025-06-21',2,2,87982.83,'Canceled','Unpaid',33,0,0,'2025-06-15 16:33:46','04:00:00','2025-06-15 16:34:04','2025-06-18 00:34:04',NULL,'Client'),(182,'Batangas Beach Resorts, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-06-20','2025-06-21',2,1,54498.36,'Canceled','Unpaid',33,0,0,'2025-06-15 16:39:37','04:30:00','2025-06-15 17:08:18','2025-06-18 01:08:18',NULL,'Client'),(183,'Makati, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-06-21','2025-06-21',1,1,23016.92,'Canceled','Unpaid',33,0,0,'2025-06-15 16:54:10','04:00:00','2025-06-15 17:05:49','2025-06-18 01:05:49',NULL,'Client'),(184,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines','SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','2025-06-30','2025-06-30',1,2,0.00,'Completed','Paid',45,0,0,'2025-06-26 07:10:07','04:00:00','2025-06-26 07:10:33','2025-06-28 15:10:33',NULL,'Client'),(185,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','2025-07-17','2025-07-18',2,3,109444.82,'Canceled','Unpaid',45,0,0,'2025-07-12 07:50:20','04:30:00','2025-07-12 07:51:05','2025-07-14 15:51:05',NULL,'Client'),(186,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','2025-07-15','2025-07-16',2,3,0.00,'Confirmed','Paid',53,0,0,'2025-07-12 08:31:12','04:00:00','2025-07-12 12:30:56','2025-07-14 20:30:56',NULL,'Client'),(187,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','2025-08-01','2025-08-02',2,1,36481.61,'Canceled','Unpaid',45,0,0,'2025-07-13 12:18:28','04:30:00','2025-07-13 13:01:58','2025-07-15 21:01:58',NULL,'Client'),(188,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','2025-08-13','2025-08-13',1,2,0.00,'Confirmed','Paid',45,0,0,'2025-07-13 13:05:48','04:00:00','2025-07-13 13:06:20','2025-07-15 21:06:20',NULL,'Client'),(189,'Batangas Beach Resorts, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-07-19','2025-07-19',1,2,0.00,'Canceled','Paid',33,0,0,'2025-07-16 07:07:12','04:30:00','2025-07-16 07:08:41','2025-07-18 15:08:41',NULL,'Client'),(190,'Batangas Beach Resorts, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2024-09-19','2024-09-19',1,1,0.00,'Completed','Paid',33,0,0,'2024-09-16 13:27:00','04:00:00','2024-09-16 13:27:11','2024-09-18 21:27:11',NULL,'Client'),(191,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2024-09-20','2024-09-20',1,1,0.00,'Completed','Paid',33,0,0,'2024-09-16 13:27:57','04:30:00','2024-09-16 13:28:04','2024-09-18 21:28:04',NULL,'Client'),(192,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines','2025-07-19','2025-07-19',1,1,24431.42,'Confirmed','Unpaid',33,0,0,'2025-07-16 13:32:47','04:00:00','2025-07-16 13:32:55','2025-07-18 21:32:55',NULL,'Client'),(193,'Caliraya Resort Club, Lumban, Laguna, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','2025-07-19','2025-07-19',1,1,34979.12,'Canceled','Unpaid',33,0,0,'2025-07-16 13:41:00','04:00:00',NULL,NULL,NULL,'Client'),(194,'Caliraya Resort Club, Lumban, Laguna, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','2025-07-19','2025-07-19',1,1,34979.12,'Confirmed','Unpaid',33,0,0,'2025-07-16 13:41:00','04:00:00','2025-07-16 13:41:43','2025-07-18 21:41:43',NULL,'Client'),(195,'Caliraya Resort Club, Lumban, Laguna, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','2025-07-19','2025-07-19',1,1,25184.97,'Confirmed','Unpaid',53,0,0,'2025-07-16 13:52:54','04:00:00','2025-07-16 14:22:22','2025-07-18 22:22:22',NULL,'Client');
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
  `license_plate` varchar(20) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `last_maintenance` date DEFAULT NULL,
  PRIMARY KEY (`bus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buses`
--

LOCK TABLES `buses` WRITE;
/*!40000 ALTER TABLE `buses` DISABLE KEYS */;
INSERT INTO `buses` VALUES (1,'KingLang01','49','Active','LP-1','Standard Coach',2025,'2025-06-12'),(2,'KingLang02','50','Active','LP-2','Standard Coach',2025,'2025-06-12'),(3,'KingLang03','49','Active','LP-3','Standard Coach',2025,'2025-06-12'),(4,'KingLang04','49','Active','LP-4','Standard Coach',2025,'2025-06-12'),(5,'KingLang05','49','Active','LP-5','Standard Coach',2025,'2025-06-12'),(6,'KingLang06','49','Active','LP-6','Standard Coach',2025,'2025-06-12'),(7,'KingLang07','49','Active','LP-7','Standard Coach',2025,'2025-06-12'),(8,'KingLang08','49','Active','LP-8','Standard Coach',2025,'2025-06-12'),(9,'KingLang09','49','Active','LP-9','Standard Coach',2025,'2025-06-12'),(10,'KingLang10','49','Active','LP-10','Standard Coach',2025,'2025-06-12'),(11,'KingLang11','49','Active','LP-11','Standard Coach',2025,'2025-06-12'),(12,'KingLang12','49','Active','LP-12','Standard Coach',2025,'2025-06-12'),(13,'KingLang13','49','Active','LP-13','Standard Coach',2025,'2025-06-12'),(14,'KingLang14','49','Active','LP-14','Standard Coach',2025,'2025-06-14'),(15,'Kinglang15','49','Active','LP-15','Standard Coach',2025,'2025-06-12');
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
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `canceled_trips`
--

LOCK TABLES `canceled_trips` WRITE;
/*!40000 ALTER TABLE `canceled_trips` DISABLE KEYS */;
INSERT INTO `canceled_trips` VALUES (46,'Automatic cancellation due to payment deadline expiration','2025-05-10 14:11:01',168,33,0.00,''),(47,'Automatic cancellation due to payment deadline expiration','2025-05-11 22:51:57',170,33,0.00,''),(48,'Automatic cancellation due to payment deadline expiration','2025-05-20 03:24:35',172,33,0.00,''),(49,'Ayoko nalang pala','2025-05-20 03:26:29',171,33,0.00,'Client'),(50,'Automatic cancellation due to payment deadline expiration','2025-06-19 07:06:54',179,33,0.00,''),(51,'Automatic cancellation due to payment deadline expiration','2025-06-19 07:06:54',180,33,0.00,''),(52,'Automatic cancellation due to payment deadline expiration','2025-06-19 07:06:54',181,33,0.00,''),(53,'Automatic cancellation due to payment deadline expiration','2025-06-19 07:06:54',182,33,0.00,''),(54,'Automatic cancellation due to payment deadline expiration','2025-06-19 07:06:54',183,33,0.00,''),(55,'Automatic cancellation due to payment deadline expiration','2025-07-16 08:35:19',185,45,0.00,''),(57,'Automatic cancellation due to lack of review by tour date','2025-07-16 10:49:44',175,47,0.00,''),(58,'Automatic cancellation due to lack of review by tour date','2025-07-16 10:49:44',176,33,0.00,''),(59,'Automatic cancellation due to lack of review by tour date','2025-07-16 10:49:44',178,33,0.00,''),(62,'Automatic cancellation due to lack of review by tour date','2025-07-12 12:28:00',155,43,0.00,''),(63,'hehe','2025-07-13 13:28:24',187,45,0.00,'Client'),(64,'yaw ko na pala','2025-07-16 07:59:41',189,33,43169.74,'Client'),(65,'yaw ko na','2025-07-16 13:41:33',193,33,0.00,'Client');
/*!40000 ALTER TABLE `canceled_trips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chatbot_bot_responses`
--

DROP TABLE IF EXISTS `chatbot_bot_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chatbot_bot_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(100) NOT NULL,
  `response` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chatbot_bot_responses`
--

LOCK TABLES `chatbot_bot_responses` WRITE;
/*!40000 ALTER TABLE `chatbot_bot_responses` DISABLE KEYS */;
INSERT INTO `chatbot_bot_responses` VALUES (1,'pricing','Our bus rental pricing starts at ₱5,000 for a standard bus for a day. For premium coaches, prices start at ₱8,000 per day. For longer trips or customized packages, we offer special discounts. Would you like a detailed quote based on your needs?','2025-06-24 08:39:50'),(2,'booking','To book a bus with KingLang Transport, you can: 1) Call our reservation line, 2) Fill out the booking form on our website, or 3) Chat with our customer service team. We need details like date, time, number of passengers, pickup/dropoff locations, and any special requirements.','2025-06-24 08:39:50'),(4,'cancellation','Our cancellation policy is as follows: Full refund if cancelled 14+ days before the reservation; 50% refund if cancelled 7-13 days before; 25% refund if cancelled 3-6 days before; No refund for cancellations less than 3 days before the scheduled date.','2025-06-24 08:42:52'),(5,'contact','You can reach our customer service team through the contact form on our website or through this chat. For emergencies during an ongoing rental, call our 24/7 support line.','2025-06-24 08:42:52'),(6,'fleet','We offer various types of buses: Standard buses (up to 50 passengers), Luxury coaches (up to 40 passengers with premium amenities), Mini buses (up to 25 passengers), and Shuttle vans (up to 15 passengers). All vehicles are regularly maintained and include professional drivers.','2025-06-24 08:42:52');
/*!40000 ALTER TABLE `chatbot_bot_responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chatbot_conversations`
--

DROP TABLE IF EXISTS `chatbot_conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chatbot_conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `status` enum('bot','human_requested','human_assigned','ended','closed') NOT NULL DEFAULT 'bot',
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ended_by` enum('client','admin','system') DEFAULT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chatbot_conversations`
--

LOCK TABLES `chatbot_conversations` WRITE;
/*!40000 ALTER TABLE `chatbot_conversations` DISABLE KEYS */;
/*!40000 ALTER TABLE `chatbot_conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chatbot_messages`
--

DROP TABLE IF EXISTS `chatbot_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chatbot_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `sender_type` enum('bot','client','admin','system') NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `conversation_id` (`conversation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chatbot_messages`
--

LOCK TABLES `chatbot_messages` WRITE;
/*!40000 ALTER TABLE `chatbot_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `chatbot_messages` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_notifications`
--

LOCK TABLES `client_notifications` WRITE;
/*!40000 ALTER TABLE `client_notifications` DISABLE KEYS */;
INSERT INTO `client_notifications` VALUES (86,43,'payment_confirmed','Your payment of 18,837.72 for booking to Camp Benjamin, Alfonso-Maragondon Road, Alfonso, Cavite, Philippines has been confirmed.',156,1,'2025-04-14 23:30:53'),(87,43,'payment_recorded','Your payment of PHP 18,837.71 for booking to Camp Benjamin, Alfonso-Maragondon Road, Alfonso, Cavite, Philippines has been recorded.',156,1,'2025-05-01 23:35:14'),(88,42,'payment_confirmed','Your payment of 19,853.52 for booking to Philippine Stock Exchange Centre, Pearl Drive, Ortigas Center, Pasig, Metro Manila, Philippines has been confirmed.',157,1,'2025-02-28 23:57:13'),(89,42,'payment_recorded','Your payment of PHP 19,853.51 for booking to Philippine Stock Exchange Centre, Pearl Drive, Ortigas Center, Pasig, Metro Manila, Philippines has been recorded.',157,1,'2025-03-05 00:00:01'),(90,42,'payment_confirmed','Your payment of 19,638.01 for booking to Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines has been confirmed.',158,1,'2025-04-06 00:10:12'),(91,42,'payment_recorded','Your payment of PHP 19,638.00 for booking to Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines has been recorded.',158,1,'2025-04-10 00:13:25'),(92,41,'payment_confirmed','Your payment of 13,042.08 for booking to Tanay Adventure Camp, Tanay, Rizal, Philippines has been confirmed.',159,1,'2025-04-10 00:21:45'),(93,41,'payment_rejected','Your payment of 13,042.08 for booking to Tanay Adventure Camp, Tanay, Rizal, Philippines has been rejected. Reason: it is not valid. I didn\'t receive it',159,1,'2025-04-10 00:22:51'),(94,41,'payment_recorded','Your payment of PHP 13,042.08 for booking to Tanay Adventure Camp, Tanay, Rizal, Philippines has been recorded.',159,1,'2025-05-06 00:27:32'),(95,41,'payment_confirmed','Your payment of 13,314.42 for booking to Tanay Adventure Camp, Tanay, Rizal, Philippines has been confirmed.',160,1,'2025-02-06 00:43:06'),(96,41,'payment_recorded','Your payment of PHP 13,314.41 for booking to Tanay Adventure Camp, Tanay, Rizal, Philippines has been recorded.',160,1,'2025-02-10 00:46:52'),(97,40,'payment_recorded','Your payment of PHP 45,182.42 for booking to Puzzle Mansion, I. Cuadra, Tagaytay, Cavite, Philippines has been recorded.',161,1,'2025-01-06 00:57:27'),(98,40,'payment_confirmed','Your payment of 10,919.79 for booking to Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines has been confirmed.',163,1,'2025-01-06 01:11:19'),(99,40,'payment_recorded','Your payment of PHP 10,919.79 for booking to Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines has been recorded.',163,1,'2025-05-06 01:12:36'),(100,40,'payment_confirmed','Your payment of 16,184.35 for booking to Caliraya Resort Club, Lumban, Laguna, Philippines has been confirmed.',164,1,'2025-05-06 01:18:05'),(101,40,'payment_recorded','Your payment of PHP 16,184.35 for booking to Caliraya Resort Club, Lumban, Laguna, Philippines has been recorded.',164,1,'2025-05-06 01:19:17'),(102,38,'payment_recorded','Your payment of PHP 58,134.10 for booking to Villa Escudero Plantations and Resort, Tiaong, Quezon, Philippines has been recorded.',165,1,'2025-03-06 01:23:51'),(103,38,'payment_confirmed','Your payment of 13,986.60 for booking to Forest Club Eco Resort, F.T. San Luis Avenue, Bay, Laguna, Philippines has been confirmed.',166,1,'2025-02-06 01:31:42'),(104,38,'payment_recorded','Your payment of PHP 13,986.59 for booking to Forest Club Eco Resort, F.T. San Luis Avenue, Bay, Laguna, Philippines has been recorded.',166,1,'2025-05-06 01:33:31'),(105,40,'payment_confirmed','Your payment of 28,501.35 for booking to Cavinti Underground River and Cave Complex, Cavinti, Laguna, Philippines has been confirmed.',162,1,'2025-05-06 01:36:24'),(106,33,'rebooking_confirmed','Your rebooking request for the trip to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines has been confirmed.',169,1,'2025-05-07 09:45:33'),(107,33,'payment_confirmed','Your payment of 177,953.04 for booking to Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines has been confirmed.',167,1,'2025-05-07 10:26:44'),(108,33,'rebooking_confirmed','Your rebooking request for the trip to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines has been confirmed.',170,1,'2025-05-08 18:22:12'),(109,33,'booking_canceled','Your booking for trip to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines on 2025-05-10 has been canceled due to non-payment. Please contact us if you need further assistance.',168,1,'2025-05-10 14:11:01'),(110,33,'booking_canceled','Your booking for trip to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines on 2025-05-12 has been canceled due to non-payment. Please contact us if you need further assistance.',170,1,'2025-05-11 22:51:57'),(111,33,'payment_recorded','Your payment of PHP 177,953.04 for booking to Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines has been recorded.',167,1,'2025-05-11 22:58:44'),(112,40,'payment_recorded','Your payment of PHP 28,501.34 for booking to Cavinti Underground River and Cave Complex, Cavinti, Laguna, Philippines has been recorded.',162,1,'2025-05-11 22:59:34'),(113,33,'booking_canceled','Your booking for trip to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines on 2025-05-17 has been canceled due to non-payment. Please contact us if you need further assistance.',172,1,'2025-05-20 03:24:35'),(114,33,'payment_confirmed','Your payment of 78,254.49 for booking to Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines has been confirmed.',173,1,'2025-05-20 03:46:49'),(115,47,'payment_confirmed','Your payment of 9,438.81 for booking to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines has been confirmed.',174,1,'2025-05-20 10:39:36'),(116,33,'payment_confirmed','Your payment of 78,254.49 for booking to Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines has been confirmed.',173,1,'2025-05-28 02:29:09'),(117,47,'payment_recorded','Your payment of PHP 9,438.80 for booking to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines has been recorded.',174,1,'2025-05-28 02:32:37'),(118,33,'payment_confirmed','Your payment of 36,706.46 for booking to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines has been confirmed.',177,1,'2025-06-14 12:36:48'),(119,33,'booking_canceled','Your booking for trip to The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines on 2025-06-24 has been canceled due to non-payment. Please contact us if you need further assistance.',179,1,'2025-06-19 07:06:54'),(120,33,'booking_canceled','Your booking for trip to Pampanga Provincial Capitol, Capitol Boulevard, San Fernando City, Pampanga, Philippines on 2025-06-20 has been canceled due to non-payment. Please contact us if you need further assistance.',180,1,'2025-06-19 07:06:54'),(121,33,'booking_canceled','Your booking for trip to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines on 2025-06-20 has been canceled due to non-payment. Please contact us if you need further assistance.',181,1,'2025-06-19 07:06:54'),(122,33,'booking_canceled','Your booking for trip to Batangas Beach Resorts, Batangas, Philippines on 2025-06-20 has been canceled due to non-payment. Please contact us if you need further assistance.',182,1,'2025-06-19 07:06:54'),(123,33,'booking_canceled','Your booking for trip to Makati, Metro Manila, Philippines on 2025-06-21 has been canceled due to non-payment. Please contact us if you need further assistance.',183,1,'2025-06-19 07:06:54'),(124,33,'payment_confirmed','Your payment of 36,706.45 for booking to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines has been confirmed.',177,1,'2025-06-19 07:08:35'),(125,45,'payment_confirmed','Your payment of 22,940.85 for booking to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines has been confirmed.',184,1,'2025-06-26 07:15:45'),(126,45,'payment_confirmed','Your payment of 22,940.84 for booking to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines has been confirmed.',184,1,'2025-06-28 01:55:56'),(127,45,'booking_canceled','Your booking for trip to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines on 2025-07-17 has been canceled due to non-payment. Please contact us if you need further assistance.',185,1,'2025-07-16 08:35:19'),(128,43,'booking_canceled','Your booking request for trip to The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines on May 16, 2025 has been automatically cancelled due to lack of confirmation. Please contact us if you wish to rebook.',155,1,'2025-07-16 10:49:44'),(129,47,'booking_canceled','Your booking request for trip to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines on May 26, 2025 has been automatically cancelled due to lack of confirmation. Please contact us if you wish to rebook.',175,1,'2025-07-16 10:49:44'),(130,33,'booking_canceled','Your booking request for trip to Maligaya Street, Quezon City, Metro Manila, Philippines on June 18, 2025 has been automatically cancelled due to lack of confirmation. Please contact us if you wish to rebook.',176,1,'2025-07-16 10:49:44'),(131,33,'booking_canceled','Your booking request for trip to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines on June 27, 2025 has been automatically cancelled due to lack of confirmation. Please contact us if you wish to rebook.',178,1,'2025-07-16 10:49:44'),(132,53,'booking_canceled','Your booking request for trip to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines on July 15, 2025 has been automatically cancelled due to lack of confirmation. Please contact us if you wish to rebook.',186,1,'2025-07-16 10:49:44'),(133,43,'booking_canceled','Your booking request for trip to The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines on May 16, 2025 has been automatically cancelled due to lack of confirmation. Please contact us if you wish to rebook.',155,1,'2025-07-12 12:25:44'),(134,43,'booking_canceled','Your booking request for trip to The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines on May 16, 2025 has been automatically cancelled due to lack of confirmation. Please contact us if you wish to rebook.',155,1,'2025-07-12 12:28:00'),(135,53,'payment_confirmed','Your payment of 85,123.75 for booking to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines has been confirmed.',186,1,'2025-07-15 13:01:02'),(136,45,'booking_confirmed','Your booking to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines has been confirmed.',188,1,'2025-07-13 13:06:20'),(137,45,'payment_confirmed','Your payment of 10,487.56 for booking to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines has been confirmed.',188,1,'2025-07-14 15:57:09'),(138,33,'booking_confirmed','Your booking to Batangas Beach Resorts, Batangas, Philippines has been confirmed.',189,1,'2025-07-16 07:08:41'),(139,33,'payment_confirmed','Your payment of 26,981.09 for booking to Batangas Beach Resorts, Batangas, Philippines has been confirmed.',189,1,'2025-07-16 07:12:37'),(140,33,'payment_rejected','Your payment of 26,981.09 for booking to Batangas Beach Resorts, Batangas, Philippines has been rejected. Reason: fake',189,1,'2025-07-16 07:26:18'),(141,33,'payment_rejected','Your payment of 26,981.09 for booking to Batangas Beach Resorts, Batangas, Philippines has been rejected. Reason: fake',189,1,'2025-07-16 07:29:28'),(142,33,'payment_rejected','Your payment of 26,981.09 for booking to Batangas Beach Resorts, Batangas, Philippines has been rejected. Reason: fake',189,1,'2025-07-16 07:32:50'),(143,33,'payment_confirmed','Your payment of 26,981.09 for booking to Batangas Beach Resorts, Batangas, Philippines has been confirmed.',189,1,'2025-07-16 07:37:47'),(144,45,'payment_recorded','Your payment of PHP 10,487.56 for booking to SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines has been recorded.',188,1,'2025-07-16 08:08:52'),(145,33,'booking_confirmed','Your booking to Batangas Beach Resorts, Batangas, Philippines has been confirmed.',190,1,'2024-09-16 13:27:11'),(146,33,'booking_confirmed','Your booking to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines has been confirmed.',191,1,'2024-09-16 13:28:04'),(147,33,'payment_confirmed','Your payment of 33,726.36 for booking to Batangas Beach Resorts, Batangas, Philippines has been confirmed.',190,1,'2024-09-16 13:29:49'),(148,33,'payment_confirmed','Your payment of 24,431.42 for booking to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines has been confirmed.',191,1,'2024-09-16 13:29:56'),(149,33,'booking_confirmed','Your booking to KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines has been confirmed.',192,1,'2025-07-16 13:32:55'),(150,33,'booking_confirmed','Your booking to Caliraya Resort Club, Lumban, Laguna, Philippines has been confirmed.',194,1,'2025-07-16 13:41:43'),(151,53,'booking_confirmed','Your booking to Caliraya Resort Club, Lumban, Laguna, Philippines has been confirmed.',195,1,'2025-07-16 13:53:02'),(152,53,'booking_confirmed','Your booking to Caliraya Resort Club, Lumban, Laguna, Philippines has been confirmed.',195,1,'2025-07-16 13:59:43'),(153,53,'booking_confirmed','Your booking to Caliraya Resort Club, Lumban, Laguna, Philippines has been confirmed.',195,1,'2025-07-16 14:00:33'),(154,53,'booking_confirmed','Your booking to Caliraya Resort Club, Lumban, Laguna, Philippines has been confirmed.',195,1,'2025-07-16 14:08:04'),(155,53,'booking_confirmed','Your booking to Caliraya Resort Club, Lumban, Laguna, Philippines has been confirmed.',195,1,'2025-07-16 14:17:18'),(156,53,'booking_confirmed','Your booking to Caliraya Resort Club, Lumban, Laguna, Philippines has been confirmed.',195,1,'2025-07-16 14:22:22');
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
/*!40000 ALTER TABLE `diesel_per_liter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drivers` (
  `driver_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `license_number` varchar(50) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Active','Inactive','On Leave') DEFAULT 'Active',
  `availability` enum('Available','Assigned') DEFAULT 'Available',
  `date_hired` date DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`driver_id`),
  UNIQUE KEY `license_number` (`license_number`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drivers`
--

LOCK TABLES `drivers` WRITE;
/*!40000 ALTER TABLE `drivers` DISABLE KEYS */;
INSERT INTO `drivers` VALUES (2,'Leo Santos','DL-2022-00456','09179876543','Pasig City','Active','Available','2025-05-16','2025-06-30','/app/uploads/drivers/driver_2_1750004096.jpg','Clean record'),(3,'Mark Reyes','DL-2023-00123','09171234567','Taguig City','Active','Available','2025-01-09','2025-10-31','/app/uploads/drivers/driver_3_1750004134.jpg','Passed drug test'),(4,'Carlos Dela Cruz','DL-2021-00987','09175551234','Makati City','Active','Available','2025-02-10','2025-07-26','/app/uploads/drivers/driver_4_1750004239.jpg','Professional driver'),(5,'Jervis  Verano','DL-2024-000448','09584958345','Maligaya','Active','Available','2025-07-01','2025-07-31','/app/uploads/drivers/driver_5_1752653759.jpg','Medyo tanga');
/*!40000 ALTER TABLE `drivers` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (90,18837.72,'Bank Transfer',156,43,0,'payment_156_1744673377.png','Confirmed','2025-04-15 07:29:37','2025-04-15 07:30:53',NULL),(91,18837.71,'Cash',156,43,0,NULL,'Confirmed','2025-05-02 07:35:14',NULL,''),(92,19853.52,'Bank Transfer',157,42,0,'payment_157_1740787022.jpg','Confirmed','2025-03-01 07:57:02','2025-03-01 07:57:13',NULL),(93,19853.51,'Cash',157,42,0,NULL,'Confirmed','2025-03-05 08:00:01',NULL,''),(94,19638.01,'Bank Transfer',158,42,0,'payment_158_1743898172.png','Confirmed','2025-04-06 08:09:32','2025-04-06 08:10:12',NULL),(95,19638.00,'Cash',158,42,0,NULL,'Confirmed','2025-04-10 08:13:25',NULL,''),(96,13042.08,'Bank Transfer',159,41,0,'payment_159_1744244449.png','Confirmed','2025-04-10 08:20:49','2025-04-10 08:21:45',NULL),(97,13042.08,'Bank Transfer',159,41,0,'payment_159_1744244531.pdf','Rejected','2025-04-10 08:22:11','2025-04-10 08:22:51',NULL),(98,13042.08,'Cash',159,41,0,NULL,'Confirmed','2025-05-06 08:27:32',NULL,''),(99,13314.42,'Bank Transfer',160,41,0,'payment_160_1738802571.jpg','Confirmed','2025-02-06 08:42:51','2025-02-06 08:43:06',NULL),(100,13314.41,'Cash',160,41,0,NULL,'Confirmed','2025-02-10 08:46:52',NULL,''),(101,45182.42,'Cash',161,40,0,NULL,'Confirmed','2025-01-06 08:57:27',NULL,''),(102,10919.79,'Bank Transfer',163,40,0,'payment_163_1736125864.png','Confirmed','2025-01-06 09:11:04','2025-01-06 09:11:19',NULL),(103,10919.79,'Cash',163,40,0,NULL,'Confirmed','2025-05-06 09:12:36',NULL,''),(104,16184.35,'Bank Transfer',164,40,0,'payment_164_1738804606.jpg','Confirmed','2025-02-06 09:16:46','2025-05-06 09:18:05',NULL),(105,16184.35,'Cash',164,40,0,NULL,'Confirmed','2025-05-06 09:19:17',NULL,''),(106,58134.10,'Cash',165,38,0,NULL,'Confirmed','2025-03-06 09:23:51',NULL,''),(107,13986.60,'Bank Transfer',166,38,0,'payment_166_1738805493.jpg','Confirmed','2025-02-06 09:31:33','2025-02-06 09:31:42',NULL),(108,13986.59,'Cash',166,38,0,NULL,'Confirmed','2025-05-06 09:33:31',NULL,''),(109,28501.35,'Bank Transfer',162,40,0,'payment_162_1746495375.png','Confirmed','2025-05-06 09:36:15','2025-05-06 09:36:24',NULL),(110,177953.04,'Bank Transfer',167,33,0,'payment_167_1746530917.jpg','Confirmed','2025-05-06 19:28:37','2025-05-07 18:26:44',NULL),(111,177953.04,'Cash',167,33,0,NULL,'Confirmed','2025-05-12 06:58:44',NULL,''),(112,28501.34,'Cash',162,40,0,NULL,'Confirmed','2025-05-12 06:59:34',NULL,''),(113,78254.49,'Bank Transfer',173,33,0,'payment_173_1747712733.jpg','Confirmed','2025-05-20 11:45:33','2025-05-20 11:46:49',NULL),(114,78254.49,'Bank Transfer',173,33,0,'payment_173_1747712903.jpg','Confirmed','2025-05-20 11:48:23','2025-05-28 10:29:09',NULL),(115,9438.81,'Bank Transfer',174,47,0,'payment_174_1747737522.jpg','Confirmed','2025-05-20 18:38:42','2025-05-20 18:39:36',NULL),(116,9438.80,'Cash',174,47,0,NULL,'Confirmed','2025-05-28 10:32:37',NULL,''),(117,36706.46,'Bank Transfer',177,33,0,'payment_177_1749904587.jpg','Confirmed','2025-06-14 20:36:27','2025-06-14 20:36:48',NULL),(118,36706.45,'Bank Transfer',177,33,0,'payment_177_1750316901.jpg','Confirmed','2025-06-19 15:08:21','2025-06-19 15:08:35',NULL),(119,22940.85,'Bank Transfer',184,45,0,'payment_184_1750922130.jpg','Confirmed','2025-06-26 15:15:30','2025-06-26 15:15:45',NULL),(120,22940.84,'Bank Transfer',184,45,0,'payment_184_1751075728.jpeg','Confirmed','2025-06-28 09:55:28','2025-06-28 09:55:56',NULL),(121,85123.75,'Bank Transfer',186,53,0,'payment_186_1752323488.png','Confirmed','2025-07-12 20:31:28','2025-07-15 21:01:02',NULL),(122,10487.56,'Bank Transfer',188,45,0,'payment_188_1752508617.png','Confirmed','2025-07-14 23:56:57','2025-07-14 23:57:09',NULL),(123,26981.09,'Bank Transfer',189,33,1,'payment_189_1752649921.png','Confirmed','2025-07-16 15:12:01','2025-07-16 15:12:37',NULL),(124,26981.09,'Bank Transfer',189,33,1,'payment_189_1752650456.png','Rejected','2025-07-16 15:20:56','2025-07-16 15:26:18',NULL),(125,26981.09,'Bank Transfer',189,33,1,'payment_189_1752650810.png','Rejected','2025-07-16 15:26:50','2025-07-16 15:29:28',NULL),(126,26981.09,'Bank Transfer',189,33,1,'payment_189_1752651015.png','Rejected','2025-07-16 15:30:15','2025-07-16 15:32:50',NULL),(127,26981.09,'Bank Transfer',189,33,1,'payment_189_1752651196.png','Confirmed','2025-07-16 15:33:16','2025-07-16 15:37:47',NULL),(128,10487.56,'Cash',188,45,0,NULL,'Confirmed','2025-07-16 16:08:52',NULL,''),(129,24431.42,'Bank Transfer',191,33,0,'payment_191_1726493344.png','Confirmed','2024-09-16 21:29:04','2024-09-16 21:29:56',NULL),(130,33726.36,'Bank Transfer',190,33,0,'payment_190_1726493360.png','Confirmed','2024-09-16 21:29:20','2024-09-16 21:29:49',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rebooking_request`
--

LOCK TABLES `rebooking_request` WRITE;
/*!40000 ALTER TABLE `rebooking_request` DISABLE KEYS */;
INSERT INTO `rebooking_request` VALUES (50,168,169,'Confirmed',33),(51,169,170,'Confirmed',33),(52,174,175,'Pending',47);
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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rejected_trips`
--

LOCK TABLES `rejected_trips` WRITE;
/*!40000 ALTER TABLE `rejected_trips` DISABLE KEYS */;
INSERT INTO `rejected_trips` VALUES (31,'it is not valid. I didn\'t receive it','','2025-04-10 00:22:51',159,41),(32,'fake','','2025-07-16 07:26:18',189,33),(33,'fake','','2025-07-16 07:29:28',189,33),(34,'fake','','2025-07-16 07:32:50',189,33);
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
) ENGINE=InnoDB AUTO_INCREMENT=1234973 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (833635,'site_name','Kinglang Transport','general',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833636,'company_name','KINGLANG TOURS AND TRANSPORT SERVICES INC.','company',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833637,'company_address','Block 1 Lot 13 Phase 3 Egypt St. Ecotrend Subd. San Nicholas 1, Bacoor, Cavite','company',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833638,'company_contact','0923-0810061 / 0977-3721958','company',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833639,'company_email','bsmillamina@yahoo.com','company',1,'2025-05-05 22:47:05','2025-05-20 03:40:34'),(833640,'bank_name','BPI Cainta Ortigas Extension Branch','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833641,'bank_account_name','KINGLANG TOURS AND TRANSPORT SERVICES INC.','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833642,'bank_account_number','4091-0050-05','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833643,'bank_swift_code','BPOIPHMM','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833644,'allow_rebooking','1','booking',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833645,'diesel_price','61.5','booking',1,'2025-05-05 22:47:05','2025-05-13 08:01:33'),(833646,'payment_methods','Bank Transfer','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833647,'currency','PHP','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833648,'tax_rate','12','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05');
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
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `terms_agreements`
--

LOCK TABLES `terms_agreements` WRITE;
/*!40000 ALTER TABLE `terms_agreements` DISABLE KEYS */;
INSERT INTO `terms_agreements` VALUES (19,155,43,1,'2025-05-05 23:17:40','::1'),(20,156,43,1,'2025-04-14 23:24:35','::1'),(21,157,42,1,'2025-02-28 23:52:21','::1'),(22,157,42,1,'2025-02-28 23:54:23','::1'),(23,158,42,1,'2025-05-06 00:03:24','::1'),(24,158,42,1,'2025-05-06 00:06:48','::1'),(25,158,42,1,'2025-04-06 00:08:10','::1'),(26,159,41,1,'2025-04-10 00:19:20','::1'),(27,160,41,1,'2025-02-06 00:39:46','::1'),(28,160,41,1,'2025-02-06 00:40:24','::1'),(29,161,40,1,'2025-05-06 00:52:59','::1'),(30,161,40,1,'2025-01-06 00:54:29','::1'),(31,162,40,1,'2025-05-06 01:03:23','::1'),(32,163,40,1,'2025-05-06 01:08:39','::1'),(33,163,40,1,'2025-01-06 01:10:01','::1'),(34,164,40,1,'2025-05-06 01:14:46','::1'),(35,164,40,1,'2025-02-06 01:16:02','::1'),(36,165,38,1,'2025-05-06 01:21:08','::1'),(37,165,38,1,'2025-03-06 01:22:59','::1'),(38,166,38,1,'2025-05-06 01:30:10','::1'),(39,166,38,1,'2025-02-06 01:30:53','::1'),(40,167,33,1,'2025-05-06 11:19:17','::1'),(41,168,33,1,'2025-05-07 09:18:43','::1'),(42,169,33,1,'2025-05-07 09:45:10','::1'),(43,170,33,1,'2025-05-08 18:16:23','::1'),(44,171,33,1,'2025-05-11 23:19:44','::1'),(45,172,33,1,'2025-05-13 08:03:24','::1'),(46,173,33,1,'2025-05-20 03:36:06','::1'),(47,174,47,1,'2025-05-20 10:30:39','::1'),(48,175,47,1,'2025-05-20 10:42:04','::1'),(49,176,33,1,'2025-06-08 15:51:11','::1'),(50,177,33,1,'2025-06-14 01:26:38','::1'),(51,178,33,1,'2025-06-14 01:31:00','::1'),(52,179,33,1,'2025-06-14 01:40:29','::1'),(53,180,33,1,'2025-06-14 12:07:23','::1'),(54,181,33,1,'2025-06-15 16:33:46','::1'),(55,182,33,1,'2025-06-15 16:39:37','::1'),(56,183,33,1,'2025-06-15 16:54:10','::1'),(57,184,45,1,'2025-06-26 07:10:07','::1'),(58,185,45,1,'2025-07-12 07:50:20','::1'),(59,186,53,1,'2025-07-12 08:31:12','::1'),(60,187,45,1,'2025-07-13 12:18:28','::1'),(61,188,45,1,'2025-07-13 13:05:48','::1'),(62,189,33,1,'2025-07-16 07:07:12','::1'),(63,190,33,1,'2024-09-16 13:27:00','::1'),(64,191,33,1,'2024-09-16 13:27:57','::1'),(65,192,33,1,'2025-07-16 13:32:47','::1'),(66,193,33,1,'2025-07-16 13:41:00','::1'),(67,194,33,1,'2025-07-16 13:41:00','::1'),(68,195,53,1,'2025-07-16 13:52:54','::1');
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
) ENGINE=InnoDB AUTO_INCREMENT=524 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trip_distances`
--

LOCK TABLES `trip_distances` WRITE;
/*!40000 ALTER TABLE `trip_distances` DISABLE KEYS */;
INSERT INTO `trip_distances` VALUES (397,'Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines',16574.00,155),(398,'The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines',13483.00,155),(399,'SM Mall of Asia, Seaside Boulevard, Pasay, Metro Manila, Philippines','Camp Benjamin, Alfonso-Maragondon Road, Alfonso, Cavite, Philippines',61161.00,156),(400,'Camp Benjamin, Alfonso-Maragondon Road, Alfonso, Cavite, Philippines','SM Mall of Asia, Seaside Boulevard, Pasay, Metro Manila, Philippines',60983.00,156),(405,'Alabang Town Center, Madrigal Avenue, Muntinlupa, Metro Manila, Philippines','BGC Arts Center, 26th Street, Taguig, Metro Manila, Philippines',20792.00,157),(406,'BGC Arts Center, 26th Street, Taguig, Metro Manila, Philippines','Terra 28th, 28th Street, Taguig, Metro Manila, Philippines',1000.00,157),(407,'Terra 28th, 28th Street, Taguig, Metro Manila, Philippines','Philippine Stock Exchange Centre, Pearl Drive, Ortigas Center, Pasig, Metro Manila, Philippines',4640.00,157),(408,'Philippine Stock Exchange Centre, Pearl Drive, Ortigas Center, Pasig, Metro Manila, Philippines','Alabang Town Center, Madrigal Avenue, Muntinlupa, Metro Manila, Philippines',23362.00,157),(415,'SM Megamall, Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong, Metro Manila, Philippines','National Museum Complex, Padre Burgos Avenue, Ermita, Manila, Metro Manila, Philippines',10725.00,158),(416,'National Museum Complex, Padre Burgos Avenue, Ermita, Manila, Metro Manila, Philippines','Riverbanks Center, Riverbanks Avenue, Marikina, Metro Manila, Philippines',14650.00,158),(417,'Riverbanks Center, Riverbanks Avenue, Marikina, Metro Manila, Philippines','Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines',5740.00,158),(418,'Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines','SM Megamall, Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong, Metro Manila, Philippines',13904.00,158),(419,'Greenhills Shopping Center, San Juan, Metro Manila, Philippines','Tanay Adventure Camp, Tanay, Rizal, Philippines',53314.00,159),(420,'Tanay Adventure Camp, Tanay, Rizal, Philippines','Greenhills Shopping Center, San Juan, Metro Manila, Philippines',52506.00,159),(423,'Bonifacio Global City, Forbestown Road, Bonifacio Global City, Taguig, Metro Manila, Philippines','Tanay Adventure Camp, Tanay, Rizal, Philippines',58280.00,160),(424,'Tanay Adventure Camp, Tanay, Rizal, Philippines','Bonifacio Global City, Forbestown Road, Bonifacio Global City, Taguig, Metro Manila, Philippines',58385.00,160),(429,'Manila City Hall, Padre Burgos Avenue, Ermita, Manila, Metro Manila, Philippines','REPTILAND ADVENTURE REPTILAND ADVENTURE, Alfonso, Cavite, Philippines',83419.00,161),(430,'REPTILAND ADVENTURE REPTILAND ADVENTURE, Alfonso, Cavite, Philippines','Tagaytay Ridge, Tagaytay, Cavite, Philippines',10251.00,161),(431,'Tagaytay Ridge, Tagaytay, Cavite, Philippines','Puzzle Mansion, I. Cuadra, Tagaytay, Cavite, Philippines',5212.00,161),(432,'Puzzle Mansion, I. Cuadra, Tagaytay, Cavite, Philippines','Manila City Hall, Padre Burgos Avenue, Ermita, Manila, Metro Manila, Philippines',73195.00,161),(433,'Philippine Science High School - Main Campus, Agham, Quezon City, Metro Manila, Philippines','Japanese Garden, Lumban - Caliraya - Cavinti Road, Cavinti, Laguna, Philippines',117024.00,162),(434,'Japanese Garden, Lumban - Caliraya - Cavinti Road, Cavinti, Laguna, Philippines','Pagsanjan Falls Lodge and Summer Resort, Pagsanjan, Laguna, Philippines',13588.00,162),(435,'Pagsanjan Falls Lodge and Summer Resort, Pagsanjan, Laguna, Philippines','Cavinti Underground River and Cave Complex, Cavinti, Laguna, Philippines',29674.00,162),(436,'Cavinti Underground River and Cave Complex, Cavinti, Laguna, Philippines','Philippine Science High School - Main Campus, Agham, Quezon City, Metro Manila, Philippines',135619.00,162),(441,'Luneta Park, Ermita, Manila, Metro Manila, Philippines','Marikina Shoe Museum, J. P. Rizal Street, Marikina, Metro Manila, Philippines',17134.00,163),(442,'Marikina Shoe Museum, J. P. Rizal Street, Marikina, Metro Manila, Philippines','Riverbanks Center, Riverbanks Avenue, Marikina, Metro Manila, Philippines',3262.00,163),(443,'Riverbanks Center, Riverbanks Avenue, Marikina, Metro Manila, Philippines','Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines',5740.00,163),(444,'Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines','Luneta Park, Ermita, Manila, Metro Manila, Philippines',19276.00,163),(447,'Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','Caliraya Resort Club, Lumban, Laguna, Philippines',115910.00,164),(448,'Caliraya Resort Club, Lumban, Laguna, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines',115104.00,164),(451,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Villa Escudero Plantations and Resort, Tiaong, Quezon, Philippines',84795.00,165),(452,'Villa Escudero Plantations and Resort, Tiaong, Quezon, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',80442.00,165),(455,'SM Megamall, Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong, Metro Manila, Philippines','Forest Club Eco Resort, F.T. San Luis Avenue, Bay, Laguna, Philippines',71589.00,166),(456,'Forest Club Eco Resort, F.T. San Luis Avenue, Bay, Laguna, Philippines','SM Megamall, Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong, Metro Manila, Philippines',71862.00,166),(457,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines',239203.00,167),(458,'Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',241884.00,167),(459,'Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','GBR Museum, General Trias, Cavite, Philippines',15522.00,168),(460,'GBR Museum, General Trias, Cavite, Philippines','Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines',22667.00,168),(461,'Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines','Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',47327.00,168),(462,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines',17461.00,168),(463,'Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','GBR Museum, General Trias, Cavite, Philippines',15522.00,169),(464,'GBR Museum, General Trias, Cavite, Philippines','Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines',22667.00,169),(465,'Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines','Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',47327.00,169),(466,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines',17461.00,169),(467,'Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','GBR Museum, General Trias, Cavite, Philippines',15522.00,170),(468,'GBR Museum, General Trias, Cavite, Philippines','Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines',22667.00,170),(469,'Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines','Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',47327.00,170),(470,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines',17461.00,170),(471,'Maligaya Street, Quezon City, Metro Manila, Philippines','San Pedro, Laguna, Philippines',55994.00,171),(472,'San Pedro, Laguna, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',56296.00,171),(473,'Victorious Christian Montessori College-Bacoor, Inc., Bacoor, Cavite, Philippines','GBR Museum, General Trias, Cavite, Philippines',24254.00,172),(474,'GBR Museum, General Trias, Cavite, Philippines','Paradizoo Theme Park, Mendez, Cavite, Philippines',22743.00,172),(475,'Paradizoo Theme Park, Mendez, Cavite, Philippines','Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',47404.00,172),(476,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori College-Bacoor, Inc., Bacoor, Cavite, Philippines',30017.00,172),(477,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','National Museum of Natural History, Teodoro F. Valencia Circle, Ermita, Manila, Metro Manila, Philippines',28840.00,173),(478,'National Museum of Natural History, Teodoro F. Valencia Circle, Ermita, Manila, Metro Manila, Philippines','Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines',246654.00,173),(479,'Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',241884.00,173),(480,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines',12203.00,174),(481,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',10811.00,174),(482,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines',12203.00,175),(483,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',10811.00,175),(484,'SM North EDSA, Epifanio de los Santos Avenue, Quezon City, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',16899.00,176),(485,'Maligaya Street, Quezon City, Metro Manila, Philippines','SM North EDSA, Epifanio de los Santos Avenue, Quezon City, Metro Manila, Philippines',16514.00,176),(486,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines',12203.00,177),(487,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',10811.00,177),(488,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines',12203.00,178),(489,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',10811.00,178),(490,'Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines',16574.00,179),(491,'The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines',13483.00,179),(492,'Maligaya Street, Quezon City, Metro Manila, Philippines','Pampanga Provincial Capitol, Capitol Boulevard, San Fernando City, Pampanga, Philippines',59382.00,180),(493,'Pampanga Provincial Capitol, Capitol Boulevard, San Fernando City, Pampanga, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',65420.00,180),(494,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines',41714.00,181),(495,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',37492.00,181),(496,'Maligaya Street, Quezon City, Metro Manila, Philippines','Batangas Beach Resorts, Batangas, Philippines',105758.00,182),(497,'Batangas Beach Resorts, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',104880.00,182),(498,'Maligaya Street, Quezon City, Metro Manila, Philippines','Makati, Metro Manila, Philippines',28495.00,183),(499,'Makati, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',27710.00,183),(500,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines',49965.00,184),(501,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines','SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines',46451.00,184),(502,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines',12203.00,185),(503,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',10811.00,185),(504,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines',12203.00,186),(505,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',10811.00,186),(506,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines',12203.00,187),(507,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',10811.00,187),(508,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines',12203.00,188),(509,'SM Fairview, Quirino Highway, Novaliches, Quezon City, Metro Manila, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',10811.00,188),(510,'Maligaya Street, Quezon City, Metro Manila, Philippines','Batangas Beach Resorts, Batangas, Philippines',105758.00,189),(511,'Batangas Beach Resorts, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',104880.00,189),(512,'Maligaya Street, Quezon City, Metro Manila, Philippines','Batangas Beach Resorts, Batangas, Philippines',105758.00,190),(513,'Batangas Beach Resorts, Batangas, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',104880.00,190),(514,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines',41714.00,191),(515,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',37492.00,191),(516,'Maligaya Street, Quezon City, Metro Manila, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines',41714.00,192),(517,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig City, Metro Manila, Philippines','Maligaya Street, Quezon City, Metro Manila, Philippines',37492.00,192),(518,'Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','Caliraya Resort Club, Lumban, Laguna, Philippines',115910.00,193),(519,'Caliraya Resort Club, Lumban, Laguna, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines',115104.00,193),(520,'Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','Caliraya Resort Club, Lumban, Laguna, Philippines',115910.00,194),(521,'Caliraya Resort Club, Lumban, Laguna, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines',115104.00,194),(522,'Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','Caliraya Resort Club, Lumban, Laguna, Philippines',115910.00,195),(523,'Caliraya Resort Club, Lumban, Laguna, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines',115104.00,195);
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
  `contact_number` varchar(16) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Client','Admin','Super Admin') NOT NULL DEFAULT 'Client',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `company_name` varchar(50) DEFAULT NULL,
  `google_id` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `contact_number` (`contact_number`),
  KEY `google_id_index` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (33,'Juan','Dela Cruz','juan@gmail.com','+63 989 234 8239','$2y$10$MqrG8RiJJtoBp7mlYZ.IiuhjHlS4ZcetHHdcq2IUhAH1qOpVzbXTe','Client','2025-05-05 22:48:41',NULL,NULL,'',NULL,NULL),(34,'Benjamin','Millamina','bsmillamina@yahoo.com','+63 933 862 4323','$2y$10$ZMkj1zcwNm1/yviz320aZuH5.QNxXWNiSj9yv6NtlJtQRPa6AAksu','Super Admin','2025-05-05 22:50:27',NULL,NULL,'',NULL,NULL),(35,'Maria Angelica','Reyes','angelica.reyes@yahoo.com','+63 928 598 4353','$2y$10$XCsNkjb9OW9mb2yBNSjD1e8T8.0.vrwjWqLSprj5c/TDx5xNXSSe6','Client','2025-05-05 22:57:31',NULL,NULL,'',NULL,NULL),(36,'John Carlo','Mendoza','jc.mendoza88@gmail.com','+63 948 534 7578','$2y$10$pHRT2Eu8XwOhg2E.bkrDPeFdQ1UNsGCyz2rghrrjhC0hb1S7rBzty','Client','2025-05-05 22:58:23',NULL,NULL,'',NULL,NULL),(37,'Kritine Joy','Santos','kristinejoysantos@yahoo.com','+63 934 578 5102','$2y$10$z/QL1jLj0wOowQ5WpChJf.pTtl1RKc0FM/1BERHZGC9BMkDySyarq','Client','2025-05-05 22:59:27',NULL,NULL,'',NULL,NULL),(38,'James Benedict','Ramirez','james.ramirez21@gmail.com','+63 984 395 4758','$2y$10$/JAaNSHEVsIe8hUjuSsnUe6nwk6gxYWBTJrtXbESpUV1Rgy3OwcFC','Client','2025-05-05 23:00:56',NULL,NULL,'Lakbay Aral',NULL,NULL),(39,'Angel Mae','Torres','angelmae.torres@yahoo.com','+63 938 439 2353','$2y$10$w.osaEHEflr1yDQtKQM0ju4jylhaSUGqI2GCQToyvMk2BNoL4fyEm','Client','2025-05-05 23:02:49',NULL,NULL,'',NULL,NULL),(40,'Rafael Lorenzo','Garcia','rafael.l.garcia.ph@gmail.com','+63 984 387 5498','$2y$10$3Pv2VZff8Z1QbrmyyhfVueFMhXxw3bWSpfbrJuKq3I6wAy8CVNlCC','Client','2025-05-05 23:05:39',NULL,NULL,'',NULL,NULL),(41,'Shaira Nicole','Villanueva','shaira.villanueva@yahoo.com','+63 984 932 5745','$2y$10$NbwwfH0lQ1qpe6Bqi7lKgugcyYjAVGybsFjRVgdzC3GOYxbL4xJS6','Client','2025-05-05 23:06:13',NULL,NULL,'',NULL,NULL),(42,'Miguel Andres','Bautista','miguel.bautista1999@gmail.com','+63 985 394 7682','$2y$10$SRZ32eDEWvPDggtOUvUlku43QCF/0CQHRRAiBlsHfvEPFuhdRyV0q','Client','2025-05-05 23:07:10',NULL,NULL,'',NULL,NULL),(43,'Clarisse Anne','Mercado','clarisse.mercado@gmail.com','+63 948 534 9588','$2y$10$TbKwu8EE8bowk1fnZLnM4e2ltarjo/KyzwBzUzCYiSxVldBLqf3K6','Client','2025-05-05 23:08:14',NULL,NULL,'',NULL,NULL),(44,'Rolando','Balucan','rolando.balucan@gmail.com','+63 958 938 5204','$2y$10$rncjAknkLdmDX8a9K7/pE.2lnWBBddeSGU5ZfZ.cDgu08v6frXiZG','Admin','2025-05-05 23:09:27',NULL,NULL,'',NULL,NULL),(45,'Jeric Ken','Verano','vjericken@gmail.com','+63 934 939 8939','$2y$10$.YfIOrWamntOTxkwvN.4tuAJiOsBrJ6hzLeRvDQgkhr44Pt2i1c7K','Client','2025-05-13 08:09:44',NULL,NULL,'','google_aea9c3941018c5832c5b1b489755a8b3','https://lh3.googleusercontent.com/a/ACg8ocLhAllHuhkfvqouwuJSNO8iH4TrZpBWfxzwicxup1Ot1nQ0zMcn=s96-c'),(46,'John','Doe','john@gmail.com','+63 958 389 5838','$2y$10$G4G1K9gif16REUKQXBGfBOWwgLKSV.4G/nat8DeACUX08evE00Chm','Client','2025-05-20 10:23:23',NULL,NULL,'',NULL,NULL),(47,'Maria','Gomez','maria@gmail.com','+63 948 384 5738','$2y$10$1j8Rf.jNXEbkiS9YXdXwbu8wof5NDTe.DlJX7MRthakQ/aEUk9q/a','Client','2025-05-20 10:28:15',NULL,NULL,'',NULL,NULL),(48,'Kenichi','Shirahama','kenichishirahama369@gmail.com','+63 948 934 2395','$2y$10$suuxRs6kbchh0hPcfmAbdu2KlGLkQs39UaYW7TXxEFc9pFI.4qzga','Client','2025-06-12 03:32:54',NULL,NULL,'','google_4efe411d967f9e09afe6f22a4cfcfd7d','https://lh3.googleusercontent.com/a/ACg8ocLp0ZVbeLKuZwIIk3Bbv96IzDz7DVxK389mwHnf3NTz_XQxp-8=s96-c'),(53,'Shogo','Kai','shogokai31@gmail.com','+63 912 230 9590','$2y$10$pRWlY5aBxPpgVGN2n/Eo4u.7RQvyqFMe7mnUT3pdV2gLkM7CIAiti','Client','2025-06-21 01:31:42',NULL,NULL,'','google_67ae42bba6334595613849ecf0208b85','https://lh3.googleusercontent.com/a/ACg8ocJKUtfuMewezQJO-ykEysv3kdfQ6_PRDaMLqr3Je2p59wX_s-17=s96-c'),(54,'Jervis','Verano','verano.136535121003@depedqc.ph','+63 943 903 4923','$2y$10$o3ghjM0nPAROlaegjeS4n.RwOIzkMLh.r40yt38fWSw9MzXqY67im','Client','2025-06-26 15:47:04',NULL,NULL,'Lakbay Aral','google_4e1b4142a5267e5c3eeef8ef5b873247','https://lh3.googleusercontent.com/a/ACg8ocIy0kxuEhHNhcLc6s7fImvo1DbCC2Phkj1Mx9yh7M8o2mMpj0ZK=s96-c'),(55,'Tom','Sawyer','tomsawyerhuckfinn246@gmail.com','+63 393 283 8483','$2y$10$6tb5qsyjxnhenyhoGdqgre..BF25d92ynpE.4q8J6HcitlgALNCJ.','Client','2025-07-15 08:21:12',NULL,NULL,'','google_209c1e438e5a0089cfcab26da910bc86','https://lh3.googleusercontent.com/a/ACg8ocLY5A4q944nXsoErvAT5Qnd6k5haodTxbkJt8qYmJ8TMbnMTw=s96-c'),(56,'test','test','test@gmail.com','+63 997 869 7896','$2y$10$4RSYgSucLlGGJWfAN4zktuc7kMankzCe7RMXxvKMtPlU8eGDQs.eW','Admin','2025-07-15 08:54:18',NULL,NULL,'',NULL,NULL),(57,'testing','testing','testing@gmail.com','+63 912 345 6789','$2y$10$8CMWMFfyUDF1cZmZYIsbsO7vVhuBjoe9J.iBiRyQ6RW1y6PHgPiQK','Admin','2025-07-15 09:48:34',NULL,NULL,'',NULL,NULL),(58,'tests','tests','tests@gmail.com','+63 394 239 4238','$2y$10$WpGjbg1RyRKGuDj12z8csOAD6D4w5Fwte.QKb9WVK8TZVNpNVSkcm','Client','2025-07-16 05:46:02',NULL,NULL,'',NULL,NULL);
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

-- Dump completed on 2025-07-17 10:53:40
