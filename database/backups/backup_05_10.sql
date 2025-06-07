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
) ENGINE=InnoDB AUTO_INCREMENT=245 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_notifications`
--

LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
INSERT INTO `admin_notifications` VALUES (209,'booking_request','New booking request from Clarisse Anne Mercado',155,1,'2025-05-05 23:17:40'),(210,'booking_request','New booking request from Clarisse Anne Mercado',156,1,'2025-04-14 23:24:35'),(211,'booking_payment','Booking ID 156 for Clarisse Anne Mercado is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',156,1,'2025-05-01 23:32:59'),(212,'booking_request','New booking request from Miguel Andres Bautista',157,1,'2025-02-28 23:52:21'),(213,'booking_request','New booking request from Miguel Andres Bautista',157,1,'2025-02-28 23:54:23'),(214,'booking_payment','Booking ID 157 for Miguel Andres Bautista is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',157,1,'2025-03-04 23:58:29'),(215,'booking_request','New booking request from Miguel Andres Bautista',158,1,'2025-05-06 00:03:24'),(216,'booking_request','New booking request from Miguel Andres Bautista',158,1,'2025-05-06 00:06:49'),(217,'booking_request','New booking request from Miguel Andres Bautista',158,1,'2025-04-06 00:08:10'),(218,'booking_payment','Booking ID 158 for Miguel Andres Bautista is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',158,1,'2025-04-10 00:11:03'),(219,'booking_request','New booking request from Shaira Nicole Villanueva',159,1,'2025-04-10 00:19:20'),(220,'booking_payment','Booking ID 159 for Shaira Nicole Villanueva is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',159,1,'2025-05-06 00:25:49'),(221,'booking_request','New booking request from Shaira Nicole Villanueva',160,1,'2025-02-06 00:39:46'),(222,'booking_request','New booking request from Shaira Nicole Villanueva',160,1,'2025-02-06 00:40:24'),(223,'booking_payment','Booking ID 160 for Shaira Nicole Villanueva is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',160,1,'2025-02-10 00:46:13'),(224,'booking_request','New booking request from Rafael Lorenzo Garcia',161,1,'2025-05-06 00:52:59'),(225,'booking_request','New booking request from Rafael Lorenzo Garcia',161,1,'2025-01-06 00:54:29'),(226,'booking_request','New booking request from Rafael Lorenzo Garcia',162,1,'2025-05-06 01:03:23'),(227,'booking_request','New booking request from Rafael Lorenzo Garcia',163,1,'2025-05-06 01:08:39'),(228,'booking_request','New booking request from Rafael Lorenzo Garcia',163,1,'2025-01-06 01:10:01'),(229,'booking_payment','Booking ID 163 for Rafael Lorenzo Garcia is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',163,1,'2025-05-06 01:11:52'),(230,'booking_request','New booking request from Rafael Lorenzo Garcia',164,1,'2025-05-06 01:14:46'),(231,'booking_request','New booking request from Rafael Lorenzo Garcia',164,1,'2025-02-06 01:16:02'),(232,'booking_payment','Booking ID 164 for Rafael Lorenzo Garcia is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',164,1,'2025-05-06 01:18:07'),(233,'booking_request','New booking request from James Benedict Ramirez',165,1,'2025-05-06 01:21:08'),(234,'booking_request','New booking request from James Benedict Ramirez',165,1,'2025-03-06 01:22:59'),(235,'booking_request','New booking request from James Benedict Ramirez',166,1,'2025-05-06 01:30:10'),(236,'booking_request','New booking request from James Benedict Ramirez',166,1,'2025-02-06 01:30:53'),(237,'booking_payment','Booking ID 166 for James Benedict Ramirez is completed but marked as partially paid. Please confirm if full payment was collected in cash on the trip day.',166,1,'2025-05-06 01:33:02'),(238,'booking_request','New booking request from Juan Dela Cruz',167,1,'2025-05-06 11:19:17'),(239,'booking_request','New booking request from Juan Dela Cruz',168,1,'2025-05-07 09:18:43'),(240,'booking_request','New booking request from Juan Dela Cruz',169,1,'2025-05-07 09:45:10'),(241,'rebooking_confirmed','Rebooking confirmed for Juan Dela Cruz to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',169,1,'2025-05-07 09:45:33'),(242,'booking_request','New booking request from Juan Dela Cruz',170,1,'2025-05-08 18:16:23'),(243,'rebooking_confirmed','Rebooking confirmed for Juan Dela Cruz to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',170,1,'2025-05-08 18:22:12'),(244,'booking_canceled','Booking ID 168 for Juan Dela Cruz has been automatically canceled due to the client not making payment within 2 days. Please review.',168,1,'2025-05-10 14:11:01');
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
) ENGINE=InnoDB AUTO_INCREMENT=350 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_buses`
--

LOCK TABLES `booking_buses` WRITE;
/*!40000 ALTER TABLE `booking_buses` DISABLE KEYS */;
INSERT INTO `booking_buses` VALUES (307,155,1),(308,155,2),(309,156,1),(312,157,1),(313,157,2),(317,158,1),(318,158,2),(319,159,1),(321,160,1),(323,161,1),(324,162,1),(325,162,2),(327,163,1),(329,164,1),(332,165,1),(333,165,2),(335,166,1),(336,167,3),(337,167,4),(338,168,5),(339,168,6),(340,168,7),(341,168,8),(342,169,9),(343,169,10),(344,169,11),(345,169,12),(346,170,1),(347,170,2),(348,170,3),(349,170,4);
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
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_costs`
--

LOCK TABLES `booking_costs` WRITE;
/*!40000 ALTER TABLE `booking_costs` DISABLE KEYS */;
INSERT INTO `booking_costs` VALUES (73,42138.02,19560.00,30.06,155,50.20,3018.02,39120.00,NULL,'percentage',NULL,NULL),(74,37675.43,20772.00,122.14,156,50.20,6131.43,41544.00,20.98,'flat',10000.00,47675.43),(75,39707.03,19560.00,49.79,157,50.20,4998.92,39120.00,10.00,'percentage',NULL,44118.92),(76,39276.01,19560.00,45.02,158,50.20,4520.01,39120.00,10.00,'percentage',NULL,43640.01),(77,26084.16,20772.00,105.82,159,50.20,5312.16,20772.00,NULL,'percentage',NULL,NULL),(78,26628.83,20772.00,116.67,160,50.20,5856.83,20772.00,NULL,'percentage',NULL,NULL),(79,45182.42,20772.00,172.08,161,50.20,8638.42,41544.00,9.96,'flat',5000.00,50182.42),(80,57002.69,20772.00,295.91,162,50.20,29709.36,41544.00,20.00,'percentage',NULL,71253.36),(81,21839.58,19560.00,45.41,163,50.20,2279.58,19560.00,NULL,'percentage',NULL,NULL),(82,32368.70,20772.00,231.01,164,50.20,11596.70,20772.00,NULL,'percentage',NULL,NULL),(83,58134.10,20772.00,165.24,165,50.20,16590.10,41544.00,NULL,'percentage',NULL,NULL),(84,27973.19,20772.00,143.45,166,50.20,7201.19,20772.00,NULL,'percentage',NULL,NULL),(85,355906.08,71040.00,481.09,167,50.20,48301.44,426240.00,25.00,'percentage',NULL,474541.44),(86,72636.47,20772.00,102.98,168,50.20,20678.38,83088.00,30.00,'percentage',NULL,103766.38),(87,72357.86,20772.00,102.98,169,60.47,24908.80,83088.00,33.00,'percentage',NULL,107996.80),(88,113982.00,20772.00,102.98,170,75.00,30894.00,83088.00,NULL,'percentage',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_stops`
--

LOCK TABLES `booking_stops` WRITE;
/*!40000 ALTER TABLE `booking_stops` DISABLE KEYS */;
INSERT INTO `booking_stops` VALUES (59,1,'BGC Arts Center, 26th Street, Taguig, Metro Manila, Philippines',157),(60,2,'Terra 28th, 28th Street, Taguig, Metro Manila, Philippines',157),(63,1,'National Museum Complex, Padre Burgos Avenue, Ermita, Manila, Metro Manila, Philippines',158),(64,2,'Riverbanks Center, Riverbanks Avenue, Marikina, Metro Manila, Philippines',158),(67,1,'REPTILAND ADVENTURE REPTILAND ADVENTURE, Alfonso, Cavite, Philippines',161),(68,2,'Tagaytay Ridge, Tagaytay, Cavite, Philippines',161),(69,1,'Japanese Garden, Lumban - Caliraya - Cavinti Road, Cavinti, Laguna, Philippines',162),(70,2,'Pagsanjan Falls Lodge and Summer Resort, Pagsanjan, Laguna, Philippines',162),(73,1,'Marikina Shoe Museum, J. P. Rizal Street, Marikina, Metro Manila, Philippines',163),(74,2,'Riverbanks Center, Riverbanks Avenue, Marikina, Metro Manila, Philippines',163),(75,1,'GBR Museum, General Trias, Cavite, Philippines',168),(76,2,'Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines',168),(77,1,'GBR Museum, General Trias, Cavite, Philippines',169),(78,2,'Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines',169),(79,1,'GBR Museum, General Trias, Cavite, Philippines',170),(80,2,'Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines',170);
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
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (155,'The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','2025-05-16','2025-05-16',1,2,42138.02,'Pending','Unpaid',43,0,0,'2025-05-05 23:17:40','04:30:00',NULL,NULL,NULL,'Client'),(156,'Camp Benjamin, Alfonso-Maragondon Road, Alfonso, Cavite, Philippines','SM Mall of Asia, Seaside Boulevard, Pasay, Metro Manila, Philippines','2025-04-30','2025-05-01',2,1,0.00,'Completed','Paid',43,0,0,'2025-04-14 23:24:35','04:00:00','2025-04-14 23:25:45','2025-04-17 07:25:45','2025-05-01 23:32:59','Client'),(157,'Philippine Stock Exchange Centre, Pearl Drive, Ortigas Center, Pasig, Metro Manila, Philippines','Alabang Town Center, Madrigal Avenue, Muntinlupa, Metro Manila, Philippines','2025-03-04','2025-03-04',1,2,0.00,'Completed','Paid',42,0,0,'2025-02-28 23:52:21','04:30:00','2025-02-28 23:56:32','2025-03-03 07:56:32','2025-03-04 23:58:29','Client'),(158,'Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines','SM Megamall, Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong, Metro Manila, Philippines','2025-04-09','2025-04-09',1,2,0.00,'Completed','Paid',42,0,0,'2025-05-06 00:03:24','04:00:00','2025-04-06 00:08:57','2025-04-08 08:08:57','2025-04-10 00:11:03','Client'),(159,'Tanay Adventure Camp, Tanay, Rizal, Philippines','Greenhills Shopping Center, San Juan, Metro Manila, Philippines','2025-04-23','2025-04-23',1,1,0.00,'Completed','Paid',41,0,0,'2025-04-10 00:19:20','04:00:00','2025-04-10 00:20:11','2025-04-12 08:20:11','2025-05-06 00:25:49','Client'),(160,'Tanay Adventure Camp, Tanay, Rizal, Philippines','Bonifacio Global City, Forbestown Road, Bonifacio Global City, Taguig, Metro Manila, Philippines','2025-02-09','2025-02-09',1,1,0.00,'Completed','Paid',41,0,0,'2025-02-06 00:39:46','04:00:00','2025-02-06 00:42:38','2025-02-08 08:42:38','2025-02-10 00:46:13','Client'),(161,'Puzzle Mansion, I. Cuadra, Tagaytay, Cavite, Philippines','Manila City Hall, Padre Burgos Avenue, Ermita, Manila, Metro Manila, Philippines','2025-01-09','2025-01-10',2,1,0.00,'Completed','Paid',40,0,0,'2025-05-06 00:52:59','04:30:00','2025-01-06 00:55:16','2025-01-08 08:55:16',NULL,'Client'),(162,'Cavinti Underground River and Cave Complex, Cavinti, Laguna, Philippines','Philippine Science High School - Main Campus, Agham, Quezon City, Metro Manila, Philippines','2025-05-10','2025-05-10',1,2,28501.34,'Confirmed','Partially Paid',40,0,0,'2025-05-06 01:03:23','04:00:00','2025-05-06 01:27:16','2025-05-08 09:27:16',NULL,'Client'),(163,'Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines','Luneta Park, Ermita, Manila, Metro Manila, Philippines','2025-01-10','2025-01-10',1,1,0.00,'Completed','Paid',40,0,0,'2025-05-06 01:08:39','04:30:00','2025-01-06 01:10:31','2025-01-08 09:10:31','2025-05-06 01:11:52','Client'),(164,'Caliraya Resort Club, Lumban, Laguna, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','2025-02-20','2025-02-20',1,1,0.00,'Completed','Paid',40,0,0,'2025-05-06 01:14:46','04:30:00','2025-02-06 01:16:24','2025-02-08 09:16:24','2025-05-06 01:18:07','Client'),(165,'Villa Escudero Plantations and Resort, Tiaong, Quezon, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','2025-03-12','2025-03-12',1,2,0.00,'Completed','Paid',38,0,0,'2025-05-06 01:21:08','05:00:00','2025-03-06 01:23:16','2025-03-08 09:23:16',NULL,'Client'),(166,'Forest Club Eco Resort, F.T. San Luis Avenue, Bay, Laguna, Philippines','SM Megamall, Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong, Metro Manila, Philippines','2025-02-24','2025-02-24',1,1,0.00,'Completed','Paid',38,0,0,'2025-05-06 01:30:10','04:30:00','2025-02-06 01:31:05','2025-02-08 09:31:05','2025-05-06 01:33:02','Client'),(167,'Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','2025-05-09','2025-05-11',3,2,177953.04,'Confirmed','Partially Paid',33,0,0,'2025-05-06 11:19:17','04:00:00','2025-05-06 11:25:50','2025-05-08 19:25:50',NULL,'Client'),(168,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','2025-05-10','2025-05-10',1,4,72636.47,'Confirmed','Unpaid',33,0,1,'2025-05-07 09:18:43','04:00:00','2025-05-07 09:20:01','2025-05-09 17:20:01',NULL,'Client'),(169,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','2025-05-10','2025-05-10',1,4,72357.86,'Confirmed','Unpaid',33,0,1,'2025-05-07 09:45:10','04:00:00','2025-05-07 09:45:33',NULL,NULL,'Client'),(170,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','2025-05-12','2025-05-12',1,4,113982.00,'Confirmed','Unpaid',33,0,0,'2025-05-08 18:16:23','04:30:00','2025-05-08 18:22:12','2025-05-11 02:22:12',NULL,'Client');
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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `canceled_trips`
--

LOCK TABLES `canceled_trips` WRITE;
/*!40000 ALTER TABLE `canceled_trips` DISABLE KEYS */;
INSERT INTO `canceled_trips` VALUES (46,'Automatic cancellation due to payment deadline expiration','2025-05-10 14:11:01',168,33,0.00,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_notifications`
--

LOCK TABLES `client_notifications` WRITE;
/*!40000 ALTER TABLE `client_notifications` DISABLE KEYS */;
INSERT INTO `client_notifications` VALUES (86,43,'payment_confirmed','Your payment of 18,837.72 for booking to Camp Benjamin, Alfonso-Maragondon Road, Alfonso, Cavite, Philippines has been confirmed.',156,1,'2025-04-14 23:30:53'),(87,43,'payment_recorded','Your payment of PHP 18,837.71 for booking to Camp Benjamin, Alfonso-Maragondon Road, Alfonso, Cavite, Philippines has been recorded.',156,0,'2025-05-01 23:35:14'),(88,42,'payment_confirmed','Your payment of 19,853.52 for booking to Philippine Stock Exchange Centre, Pearl Drive, Ortigas Center, Pasig, Metro Manila, Philippines has been confirmed.',157,0,'2025-02-28 23:57:13'),(89,42,'payment_recorded','Your payment of PHP 19,853.51 for booking to Philippine Stock Exchange Centre, Pearl Drive, Ortigas Center, Pasig, Metro Manila, Philippines has been recorded.',157,0,'2025-03-05 00:00:01'),(90,42,'payment_confirmed','Your payment of 19,638.01 for booking to Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines has been confirmed.',158,0,'2025-04-06 00:10:12'),(91,42,'payment_recorded','Your payment of PHP 19,638.00 for booking to Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines has been recorded.',158,0,'2025-04-10 00:13:25'),(92,41,'payment_confirmed','Your payment of 13,042.08 for booking to Tanay Adventure Camp, Tanay, Rizal, Philippines has been confirmed.',159,0,'2025-04-10 00:21:45'),(93,41,'payment_rejected','Your payment of 13,042.08 for booking to Tanay Adventure Camp, Tanay, Rizal, Philippines has been rejected. Reason: it is not valid. I didn\'t receive it',159,1,'2025-04-10 00:22:51'),(94,41,'payment_recorded','Your payment of PHP 13,042.08 for booking to Tanay Adventure Camp, Tanay, Rizal, Philippines has been recorded.',159,0,'2025-05-06 00:27:32'),(95,41,'payment_confirmed','Your payment of 13,314.42 for booking to Tanay Adventure Camp, Tanay, Rizal, Philippines has been confirmed.',160,0,'2025-02-06 00:43:06'),(96,41,'payment_recorded','Your payment of PHP 13,314.41 for booking to Tanay Adventure Camp, Tanay, Rizal, Philippines has been recorded.',160,0,'2025-02-10 00:46:52'),(97,40,'payment_recorded','Your payment of PHP 45,182.42 for booking to Puzzle Mansion, I. Cuadra, Tagaytay, Cavite, Philippines has been recorded.',161,1,'2025-01-06 00:57:27'),(98,40,'payment_confirmed','Your payment of 10,919.79 for booking to Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines has been confirmed.',163,1,'2025-01-06 01:11:19'),(99,40,'payment_recorded','Your payment of PHP 10,919.79 for booking to Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines has been recorded.',163,1,'2025-05-06 01:12:36'),(100,40,'payment_confirmed','Your payment of 16,184.35 for booking to Caliraya Resort Club, Lumban, Laguna, Philippines has been confirmed.',164,0,'2025-05-06 01:18:05'),(101,40,'payment_recorded','Your payment of PHP 16,184.35 for booking to Caliraya Resort Club, Lumban, Laguna, Philippines has been recorded.',164,0,'2025-05-06 01:19:17'),(102,38,'payment_recorded','Your payment of PHP 58,134.10 for booking to Villa Escudero Plantations and Resort, Tiaong, Quezon, Philippines has been recorded.',165,0,'2025-03-06 01:23:51'),(103,38,'payment_confirmed','Your payment of 13,986.60 for booking to Forest Club Eco Resort, F.T. San Luis Avenue, Bay, Laguna, Philippines has been confirmed.',166,0,'2025-02-06 01:31:42'),(104,38,'payment_recorded','Your payment of PHP 13,986.59 for booking to Forest Club Eco Resort, F.T. San Luis Avenue, Bay, Laguna, Philippines has been recorded.',166,0,'2025-05-06 01:33:31'),(105,40,'payment_confirmed','Your payment of 28,501.35 for booking to Cavinti Underground River and Cave Complex, Cavinti, Laguna, Philippines has been confirmed.',162,0,'2025-05-06 01:36:24'),(106,33,'rebooking_confirmed','Your rebooking request for the trip to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines has been confirmed.',169,1,'2025-05-07 09:45:33'),(107,33,'payment_confirmed','Your payment of 177,953.04 for booking to Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines has been confirmed.',167,1,'2025-05-07 10:26:44'),(108,33,'rebooking_confirmed','Your rebooking request for the trip to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines has been confirmed.',170,1,'2025-05-08 18:22:12'),(109,33,'booking_canceled','Your booking for trip to Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines on 2025-05-10 has been canceled due to non-payment. Please contact us if you need further assistance.',168,1,'2025-05-10 14:11:01');
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
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (90,18837.72,'Bank Transfer',156,43,0,'payment_156_1744673377.png','Confirmed','2025-04-15 07:29:37','2025-04-15 07:30:53',NULL),(91,18837.71,'Cash',156,43,0,NULL,'Confirmed','2025-05-02 07:35:14',NULL,''),(92,19853.52,'Bank Transfer',157,42,0,'payment_157_1740787022.jpg','Confirmed','2025-03-01 07:57:02','2025-03-01 07:57:13',NULL),(93,19853.51,'Cash',157,42,0,NULL,'Confirmed','2025-03-05 08:00:01',NULL,''),(94,19638.01,'Bank Transfer',158,42,0,'payment_158_1743898172.png','Confirmed','2025-04-06 08:09:32','2025-04-06 08:10:12',NULL),(95,19638.00,'Cash',158,42,0,NULL,'Confirmed','2025-04-10 08:13:25',NULL,''),(96,13042.08,'Bank Transfer',159,41,0,'payment_159_1744244449.png','Confirmed','2025-04-10 08:20:49','2025-04-10 08:21:45',NULL),(97,13042.08,'Bank Transfer',159,41,0,'payment_159_1744244531.pdf','Rejected','2025-04-10 08:22:11','2025-04-10 08:22:51',NULL),(98,13042.08,'Cash',159,41,0,NULL,'Confirmed','2025-05-06 08:27:32',NULL,''),(99,13314.42,'Bank Transfer',160,41,0,'payment_160_1738802571.jpg','Confirmed','2025-02-06 08:42:51','2025-02-06 08:43:06',NULL),(100,13314.41,'Cash',160,41,0,NULL,'Confirmed','2025-02-10 08:46:52',NULL,''),(101,45182.42,'Cash',161,40,0,NULL,'Confirmed','2025-01-06 08:57:27',NULL,''),(102,10919.79,'Bank Transfer',163,40,0,'payment_163_1736125864.png','Confirmed','2025-01-06 09:11:04','2025-01-06 09:11:19',NULL),(103,10919.79,'Cash',163,40,0,NULL,'Confirmed','2025-05-06 09:12:36',NULL,''),(104,16184.35,'Bank Transfer',164,40,0,'payment_164_1738804606.jpg','Confirmed','2025-02-06 09:16:46','2025-05-06 09:18:05',NULL),(105,16184.35,'Cash',164,40,0,NULL,'Confirmed','2025-05-06 09:19:17',NULL,''),(106,58134.10,'Cash',165,38,0,NULL,'Confirmed','2025-03-06 09:23:51',NULL,''),(107,13986.60,'Bank Transfer',166,38,0,'payment_166_1738805493.jpg','Confirmed','2025-02-06 09:31:33','2025-02-06 09:31:42',NULL),(108,13986.59,'Cash',166,38,0,NULL,'Confirmed','2025-05-06 09:33:31',NULL,''),(109,28501.35,'Bank Transfer',162,40,0,'payment_162_1746495375.png','Confirmed','2025-05-06 09:36:15','2025-05-06 09:36:24',NULL),(110,177953.04,'Bank Transfer',167,33,0,'payment_167_1746530917.jpg','Confirmed','2025-05-06 19:28:37','2025-05-07 18:26:44',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rebooking_request`
--

LOCK TABLES `rebooking_request` WRITE;
/*!40000 ALTER TABLE `rebooking_request` DISABLE KEYS */;
INSERT INTO `rebooking_request` VALUES (50,168,169,'Confirmed',33),(51,169,170,'Confirmed',33);
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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rejected_trips`
--

LOCK TABLES `rejected_trips` WRITE;
/*!40000 ALTER TABLE `rejected_trips` DISABLE KEYS */;
INSERT INTO `rejected_trips` VALUES (31,'it is not valid. I didn\'t receive it','','2025-04-10 00:22:51',159,41);
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
) ENGINE=InnoDB AUTO_INCREMENT=919413 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (833635,'site_name','Kinglang Transport','general',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833636,'company_name','KINGLANG TOURS AND TRANSPORT SERVICES INC.','company',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833637,'company_address','Block 1 Lot 13 Phase 3 Egypt St. Ecotrend Subd. San Nicholas 1, Bacoor, Cavite','company',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833638,'company_contact','0923-0810061 / 0977-3721958','company',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833639,'company_email','jaycris.traveltours@gmail.com','company',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833640,'bank_name','BPI Cainta Ortigas Extension Branch','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833641,'bank_account_name','KINGLANG TOURS AND TRANSPORT SERVICES INC.','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833642,'bank_account_number','4091-0050-05','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833643,'bank_swift_code','BPOIPHMM','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833644,'allow_rebooking','1','booking',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833645,'diesel_price','75','booking',1,'2025-05-05 22:47:05','2025-05-07 09:48:36'),(833646,'payment_methods','Bank Transfer','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833647,'currency','PHP','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05'),(833648,'tax_rate','12','payment',1,'2025-05-05 22:47:05','2025-05-05 22:47:05');
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
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `terms_agreements`
--

LOCK TABLES `terms_agreements` WRITE;
/*!40000 ALTER TABLE `terms_agreements` DISABLE KEYS */;
INSERT INTO `terms_agreements` VALUES (19,155,43,1,'2025-05-05 23:17:40','::1'),(20,156,43,1,'2025-04-14 23:24:35','::1'),(21,157,42,1,'2025-02-28 23:52:21','::1'),(22,157,42,1,'2025-02-28 23:54:23','::1'),(23,158,42,1,'2025-05-06 00:03:24','::1'),(24,158,42,1,'2025-05-06 00:06:48','::1'),(25,158,42,1,'2025-04-06 00:08:10','::1'),(26,159,41,1,'2025-04-10 00:19:20','::1'),(27,160,41,1,'2025-02-06 00:39:46','::1'),(28,160,41,1,'2025-02-06 00:40:24','::1'),(29,161,40,1,'2025-05-06 00:52:59','::1'),(30,161,40,1,'2025-01-06 00:54:29','::1'),(31,162,40,1,'2025-05-06 01:03:23','::1'),(32,163,40,1,'2025-05-06 01:08:39','::1'),(33,163,40,1,'2025-01-06 01:10:01','::1'),(34,164,40,1,'2025-05-06 01:14:46','::1'),(35,164,40,1,'2025-02-06 01:16:02','::1'),(36,165,38,1,'2025-05-06 01:21:08','::1'),(37,165,38,1,'2025-03-06 01:22:59','::1'),(38,166,38,1,'2025-05-06 01:30:10','::1'),(39,166,38,1,'2025-02-06 01:30:53','::1'),(40,167,33,1,'2025-05-06 11:19:17','::1'),(41,168,33,1,'2025-05-07 09:18:43','::1'),(42,169,33,1,'2025-05-07 09:45:10','::1'),(43,170,33,1,'2025-05-08 18:16:23','::1');
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
) ENGINE=InnoDB AUTO_INCREMENT=471 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trip_distances`
--

LOCK TABLES `trip_distances` WRITE;
/*!40000 ALTER TABLE `trip_distances` DISABLE KEYS */;
INSERT INTO `trip_distances` VALUES (397,'Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines',16574.00,155),(398,'The Mind Museum, 3rd Avenue, Taguig, Metro Manila, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines',13483.00,155),(399,'SM Mall of Asia, Seaside Boulevard, Pasay, Metro Manila, Philippines','Camp Benjamin, Alfonso-Maragondon Road, Alfonso, Cavite, Philippines',61161.00,156),(400,'Camp Benjamin, Alfonso-Maragondon Road, Alfonso, Cavite, Philippines','SM Mall of Asia, Seaside Boulevard, Pasay, Metro Manila, Philippines',60983.00,156),(405,'Alabang Town Center, Madrigal Avenue, Muntinlupa, Metro Manila, Philippines','BGC Arts Center, 26th Street, Taguig, Metro Manila, Philippines',20792.00,157),(406,'BGC Arts Center, 26th Street, Taguig, Metro Manila, Philippines','Terra 28th, 28th Street, Taguig, Metro Manila, Philippines',1000.00,157),(407,'Terra 28th, 28th Street, Taguig, Metro Manila, Philippines','Philippine Stock Exchange Centre, Pearl Drive, Ortigas Center, Pasig, Metro Manila, Philippines',4640.00,157),(408,'Philippine Stock Exchange Centre, Pearl Drive, Ortigas Center, Pasig, Metro Manila, Philippines','Alabang Town Center, Madrigal Avenue, Muntinlupa, Metro Manila, Philippines',23362.00,157),(415,'SM Megamall, Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong, Metro Manila, Philippines','National Museum Complex, Padre Burgos Avenue, Ermita, Manila, Metro Manila, Philippines',10725.00,158),(416,'National Museum Complex, Padre Burgos Avenue, Ermita, Manila, Metro Manila, Philippines','Riverbanks Center, Riverbanks Avenue, Marikina, Metro Manila, Philippines',14650.00,158),(417,'Riverbanks Center, Riverbanks Avenue, Marikina, Metro Manila, Philippines','Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines',5740.00,158),(418,'Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines','SM Megamall, Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong, Metro Manila, Philippines',13904.00,158),(419,'Greenhills Shopping Center, San Juan, Metro Manila, Philippines','Tanay Adventure Camp, Tanay, Rizal, Philippines',53314.00,159),(420,'Tanay Adventure Camp, Tanay, Rizal, Philippines','Greenhills Shopping Center, San Juan, Metro Manila, Philippines',52506.00,159),(423,'Bonifacio Global City, Forbestown Road, Bonifacio Global City, Taguig, Metro Manila, Philippines','Tanay Adventure Camp, Tanay, Rizal, Philippines',58280.00,160),(424,'Tanay Adventure Camp, Tanay, Rizal, Philippines','Bonifacio Global City, Forbestown Road, Bonifacio Global City, Taguig, Metro Manila, Philippines',58385.00,160),(429,'Manila City Hall, Padre Burgos Avenue, Ermita, Manila, Metro Manila, Philippines','REPTILAND ADVENTURE REPTILAND ADVENTURE, Alfonso, Cavite, Philippines',83419.00,161),(430,'REPTILAND ADVENTURE REPTILAND ADVENTURE, Alfonso, Cavite, Philippines','Tagaytay Ridge, Tagaytay, Cavite, Philippines',10251.00,161),(431,'Tagaytay Ridge, Tagaytay, Cavite, Philippines','Puzzle Mansion, I. Cuadra, Tagaytay, Cavite, Philippines',5212.00,161),(432,'Puzzle Mansion, I. Cuadra, Tagaytay, Cavite, Philippines','Manila City Hall, Padre Burgos Avenue, Ermita, Manila, Metro Manila, Philippines',73195.00,161),(433,'Philippine Science High School - Main Campus, Agham, Quezon City, Metro Manila, Philippines','Japanese Garden, Lumban - Caliraya - Cavinti Road, Cavinti, Laguna, Philippines',117024.00,162),(434,'Japanese Garden, Lumban - Caliraya - Cavinti Road, Cavinti, Laguna, Philippines','Pagsanjan Falls Lodge and Summer Resort, Pagsanjan, Laguna, Philippines',13588.00,162),(435,'Pagsanjan Falls Lodge and Summer Resort, Pagsanjan, Laguna, Philippines','Cavinti Underground River and Cave Complex, Cavinti, Laguna, Philippines',29674.00,162),(436,'Cavinti Underground River and Cave Complex, Cavinti, Laguna, Philippines','Philippine Science High School - Main Campus, Agham, Quezon City, Metro Manila, Philippines',135619.00,162),(441,'Luneta Park, Ermita, Manila, Metro Manila, Philippines','Marikina Shoe Museum, J. P. Rizal Street, Marikina, Metro Manila, Philippines',17134.00,163),(442,'Marikina Shoe Museum, J. P. Rizal Street, Marikina, Metro Manila, Philippines','Riverbanks Center, Riverbanks Avenue, Marikina, Metro Manila, Philippines',3262.00,163),(443,'Riverbanks Center, Riverbanks Avenue, Marikina, Metro Manila, Philippines','Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines',5740.00,163),(444,'Book Museum cum Ethnology Center, Southeast Dao, Marikina, Metro Manila, Philippines','Luneta Park, Ermita, Manila, Metro Manila, Philippines',19276.00,163),(447,'Trinoma, North Avenue, Quezon City, Metro Manila, Philippines','Caliraya Resort Club, Lumban, Laguna, Philippines',115910.00,164),(448,'Caliraya Resort Club, Lumban, Laguna, Philippines','Trinoma, North Avenue, Quezon City, Metro Manila, Philippines',115104.00,164),(451,'KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines','Villa Escudero Plantations and Resort, Tiaong, Quezon, Philippines',84795.00,165),(452,'Villa Escudero Plantations and Resort, Tiaong, Quezon, Philippines','KingLang Tours and Transport Services Inc., M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines',80442.00,165),(455,'SM Megamall, Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong, Metro Manila, Philippines','Forest Club Eco Resort, F.T. San Luis Avenue, Bay, Laguna, Philippines',71589.00,166),(456,'Forest Club Eco Resort, F.T. San Luis Avenue, Bay, Laguna, Philippines','SM Megamall, Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong, Metro Manila, Philippines',71862.00,166),(457,'Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines','Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines',239203.00,167),(458,'Baguio Cathedral, Steps To Our Lady Of Atonement Cathedral, Baguio, Benguet, Philippines','Colegio De Sta. Teresa De Avila Foundation, Skylark, Novaliches, Quezon City, Metro Manila, Philippines',241884.00,167),(459,'Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','GBR Museum, General Trias, Cavite, Philippines',15522.00,168),(460,'GBR Museum, General Trias, Cavite, Philippines','Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines',22667.00,168),(461,'Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines','Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',47327.00,168),(462,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines',17461.00,168),(463,'Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','GBR Museum, General Trias, Cavite, Philippines',15522.00,169),(464,'GBR Museum, General Trias, Cavite, Philippines','Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines',22667.00,169),(465,'Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines','Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',47327.00,169),(466,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines',17461.00,169),(467,'Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines','GBR Museum, General Trias, Cavite, Philippines',15522.00,170),(468,'GBR Museum, General Trias, Cavite, Philippines','Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines',22667.00,170),(469,'Paradizoo, Maglabe Drive, Mendez, Cavite, Philippines','Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines',47327.00,170),(470,'Enchanted Kingdom, RSBS Boulevard, Santa Rosa, Laguna, Philippines','Victorious Christian Montessori, St Gabriel St, General Mariano Alvarez, Cavite, Philippines',17461.00,170);
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
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (33,'Juan','Dela Cruz','juan@gmail.com','0989-234-8239','$2y$10$MqrG8RiJJtoBp7mlYZ.IiuhjHlS4ZcetHHdcq2IUhAH1qOpVzbXTe','Client','2025-05-05 22:48:41',NULL,NULL,''),(34,'Benjamin','Millamina','bsmillamina@yahoo.com','0933-862-4323','$2y$10$ZMkj1zcwNm1/yviz320aZuH5.QNxXWNiSj9yv6NtlJtQRPa6AAksu','Super Admin','2025-05-05 22:50:27',NULL,NULL,''),(35,'Maria Angelica','Reyes','angelica.reyes@yahoo.com','0928-598-4353','$2y$10$XCsNkjb9OW9mb2yBNSjD1e8T8.0.vrwjWqLSprj5c/TDx5xNXSSe6','Client','2025-05-05 22:57:31',NULL,NULL,NULL),(36,'John Carlo','Mendoza','jc.mendoza88@gmail.com','0948-534-7578','$2y$10$pHRT2Eu8XwOhg2E.bkrDPeFdQ1UNsGCyz2rghrrjhC0hb1S7rBzty','Client','2025-05-05 22:58:23',NULL,NULL,NULL),(37,'Kritine Joy','Santos','kristinejoysantos@yahoo.com','0934-578-5102','$2y$10$z/QL1jLj0wOowQ5WpChJf.pTtl1RKc0FM/1BERHZGC9BMkDySyarq','Client','2025-05-05 22:59:27',NULL,NULL,NULL),(38,'James Benedict','Ramirez','james.ramirez21@gmail.com','0984-395-4758','$2y$10$/JAaNSHEVsIe8hUjuSsnUe6nwk6gxYWBTJrtXbESpUV1Rgy3OwcFC','Client','2025-05-05 23:00:56',NULL,NULL,'Lakbay Aral'),(39,'Angel Mae','Torres','angelmae.torres@yahoo.com','0938-439-2353','$2y$10$w.osaEHEflr1yDQtKQM0ju4jylhaSUGqI2GCQToyvMk2BNoL4fyEm','Client','2025-05-05 23:02:49',NULL,NULL,NULL),(40,'Rafael Lorenzo','Garcia','rafael.l.garcia.ph@gmail.com','0984-387-5498','$2y$10$3Pv2VZff8Z1QbrmyyhfVueFMhXxw3bWSpfbrJuKq3I6wAy8CVNlCC','Client','2025-05-05 23:05:39',NULL,NULL,NULL),(41,'Shaira Nicole','Villanueva','shaira.villanueva@yahoo.com','0984-932-5745','$2y$10$NbwwfH0lQ1qpe6Bqi7lKgugcyYjAVGybsFjRVgdzC3GOYxbL4xJS6','Client','2025-05-05 23:06:13',NULL,NULL,NULL),(42,'Miguel Andres','Bautista','miguel.bautista1999@gmail.com','0985-394-7682','$2y$10$SRZ32eDEWvPDggtOUvUlku43QCF/0CQHRRAiBlsHfvEPFuhdRyV0q','Client','2025-05-05 23:07:10',NULL,NULL,NULL),(43,'Clarisse Anne','Mercado','clarisse.mercado@gmail.com','0948-534-9588','$2y$10$TbKwu8EE8bowk1fnZLnM4e2ltarjo/KyzwBzUzCYiSxVldBLqf3K6','Client','2025-05-05 23:08:14',NULL,NULL,NULL),(44,'Rolando','Balucan','rolando.balucan@gmail.com','0958-938-5204','$2y$10$rncjAknkLdmDX8a9K7/pE.2lnWBBddeSGU5ZfZ.cDgu08v6frXiZG','Admin','2025-05-05 23:09:27',NULL,NULL,NULL);
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

-- Dump completed on 2025-05-10 22:46:02
