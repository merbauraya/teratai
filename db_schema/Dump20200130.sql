-- MySQL dump 10.13  Distrib 5.7.29, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: sc
-- ------------------------------------------------------
-- Server version	5.7.29-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('createOrder','1',1566114093),('dataAdmin','1',1559395632),('manager','2',1566127261),('manager','3',1557253696),('orderOperator','1',1557749044),('siteAdmin','1',1557236658);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('approveOrder',2,'Approve an order',NULL,NULL,1559332060,1559332060),('createOrder',2,'Can Create Order',NULL,NULL,1557227271,1557227271),('dataAdmin',1,'Cafe operator',NULL,NULL,1559395474,1559395525),('manager',1,'Staff Manager',NULL,NULL,1557237173,1559332077),('orderOperator',1,'User belong to this role can create new order',NULL,NULL,1557543484,1557543512),('orderSummary',2,'Able to view order Summary',NULL,NULL,1559395515,1559395515),('siteAdmin',1,'Site Administrator',NULL,NULL,1557236643,1557236643);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auto_number`
--

DROP TABLE IF EXISTS `auto_number`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auto_number` (
  `group` varchar(32) NOT NULL,
  `number` int(11) DEFAULT NULL,
  `optimistic_lock` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auto_number`
--

LOCK TABLES `auto_number` WRITE;
/*!40000 ALTER TABLE `auto_number` DISABLE KEYS */;
INSERT INTO `auto_number` VALUES ('INV/2019/12/????',337,1,1576776900);
/*!40000 ALTER TABLE `auto_number` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banquet_order`
--

DROP TABLE IF EXISTS `banquet_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banquet_order` (
  `orderId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `orderDate` datetime DEFAULT NULL,
  `orderPurpose` varchar(200) DEFAULT NULL,
  `serviceTypeId` int(11) DEFAULT NULL,
  `orderNote` mediumtext,
  `orderStatus` tinyint(2) DEFAULT NULL,
  `approvedBy` int(11) DEFAULT NULL,
  `latestEventDate` date DEFAULT NULL,
  `approvedDate` datetime DEFAULT NULL,
  `notificationSent` tinyint(1) DEFAULT NULL,
  `invoiceSent` tinyint(1) DEFAULT NULL,
  `approvalRequestSent` tinyint(1) DEFAULT NULL,
  `invoiceNumber` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`orderId`),
  KEY `index2` (`orderStatus`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banquet_order`
--

LOCK TABLES `banquet_order` WRITE;
/*!40000 ALTER TABLE `banquet_order` DISABLE KEYS */;
INSERT INTO `banquet_order` VALUES (1,1,'2019-08-18 19:23:50','2019-08-18 11:23:00','FMB Meeting',NULL,NULL,1,NULL,NULL,NULL,0,0,0,NULL),(2,1,'2019-08-18 19:24:29','2019-08-18 11:24:00','FMB Meeting',NULL,NULL,2,1,NULL,'2019-08-18 19:26:44',1,0,0,NULL),(3,1,'2019-08-18 19:48:51','2019-08-18 11:48:00','IT Audit',NULL,NULL,7,1,NULL,'2019-08-18 19:49:02',1,0,0,'INV/2019/12/0337'),(4,1,'2019-09-24 15:51:05','2019-09-24 07:51:00','zzzz',NULL,NULL,7,NULL,NULL,NULL,1,0,0,'INV/2019/12/0337'),(5,1,'2019-10-03 14:36:16','2019-10-03 06:36:00','aaaa',NULL,NULL,7,NULL,NULL,NULL,1,0,0,NULL),(6,1,'2019-10-18 17:10:47','2019-10-18 09:10:00','Jamuan Alhi',NULL,NULL,7,1,'2019-10-18','2019-10-19 13:59:16',1,0,0,NULL),(7,1,'2019-10-19 14:02:35','2019-10-19 06:02:00','STL 2019',NULL,NULL,1,NULL,'2019-10-19',NULL,0,0,0,NULL),(8,1,'2019-10-19 14:15:29','2019-10-19 06:15:00','Ansara',NULL,NULL,0,NULL,'2019-10-24',NULL,0,0,0,NULL),(9,1,'2019-12-16 19:19:44','2019-12-16 11:19:00','jamuan nasi',NULL,NULL,1,NULL,'2019-12-19',NULL,0,0,0,NULL);
/*!40000 ALTER TABLE `banquet_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banquet_order_detail`
--

DROP TABLE IF EXISTS `banquet_order_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banquet_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) DEFAULT NULL,
  `orderDateTime` datetime DEFAULT NULL,
  `bod_time` datetime DEFAULT NULL,
  `locationId` int(11) DEFAULT NULL,
  `paxCount` int(11) DEFAULT NULL,
  `pricePerPax` decimal(6,2) DEFAULT NULL,
  `serveTypeId` int(11) DEFAULT NULL,
  `note` mediumtext,
  `orderStatus` tinyint(2) DEFAULT NULL,
  `autoStatusDate` datetime DEFAULT NULL,
  `verified` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index2` (`orderDateTime`,`orderStatus`),
  KEY `index3` (`autoStatusDate`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banquet_order_detail`
--

LOCK TABLES `banquet_order_detail` WRITE;
/*!40000 ALTER TABLE `banquet_order_detail` DISABLE KEYS */;
INSERT INTO `banquet_order_detail` VALUES (2,2,'2019-08-22 10:10:00',NULL,1,20,NULL,1,'',4,'2019-12-18 00:00:00',NULL),(3,3,'2019-08-22 08:15:00',NULL,1,30,NULL,1,'',4,'2019-12-18 00:00:00',NULL),(4,4,'2019-10-02 11:10:00',NULL,3,10,NULL,3,'zzz',4,'2019-12-18 00:00:00',NULL),(5,5,'2019-10-06 06:36:00',NULL,2,22,NULL,3,'',4,'2019-12-18 00:00:00',NULL),(6,6,'2019-10-21 09:30:00',NULL,2,20,NULL,3,'xxx',4,'2019-12-18 00:00:00',NULL),(7,6,'2019-10-22 10:30:00',NULL,2,20,NULL,3,'aaa',4,'2019-12-18 00:00:00',NULL),(8,7,'2019-10-21 10:30:00',NULL,2,10,NULL,1,'',1,NULL,NULL),(9,7,'2019-10-22 06:02:00',NULL,3,15,NULL,2,'zzzz',1,NULL,NULL),(10,7,'2019-10-25 14:30:00',NULL,4,100,NULL,3,'zzz',1,NULL,NULL),(11,8,'2019-10-24 10:30:00',NULL,3,2,NULL,4,'',1,NULL,NULL),(12,8,'2019-10-23 10:25:00',NULL,4,2,NULL,3,'',1,NULL,NULL),(13,9,'2019-12-19 11:15:00',NULL,3,20,NULL,1,'',1,NULL,NULL);
/*!40000 ALTER TABLE `banquet_order_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banquet_order_food`
--

DROP TABLE IF EXISTS `banquet_order_food`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banquet_order_food` (
  `bofId` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) DEFAULT NULL,
  `foodId` int(11) DEFAULT NULL,
  `note` mediumtext,
  `orderDetailId` int(11) DEFAULT NULL,
  `paxCount` int(11) DEFAULT NULL,
  `serveTypeId` int(11) DEFAULT NULL,
  PRIMARY KEY (`bofId`),
  UNIQUE KEY `index2` (`foodId`,`orderDetailId`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banquet_order_food`
--

LOCK TABLES `banquet_order_food` WRITE;
/*!40000 ALTER TABLE `banquet_order_food` DISABLE KEYS */;
INSERT INTO `banquet_order_food` VALUES (2,2,2,NULL,2,20,1),(3,3,3,NULL,3,30,1),(4,4,2,NULL,4,10,1),(5,5,1,NULL,5,22,1),(6,6,1,NULL,6,20,1),(7,6,1,NULL,7,20,1),(8,7,1,NULL,8,10,1),(9,7,7,NULL,8,10,1),(10,7,1,NULL,9,15,1),(11,7,2,NULL,9,15,1),(12,7,3,NULL,10,100,1),(14,8,3,NULL,12,2,1),(15,8,2,NULL,11,2,1),(16,9,7,NULL,13,20,1);
/*!40000 ALTER TABLE `banquet_order_food` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `name` varchar(100) NOT NULL,
  `value` mediumtext,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES ('approvalEmailFrom','order@sc.com'),('approvalEmailSubject','Please approve order'),('dayOrderGetVerified','3'),('invoiceGrouping','1'),('invoiceNoDigitSize',''),('invoiceNoPrefix',''),('invoiceNumberFormat','1'),('invoiceRequireVerification','1'),('orderRequireApproval','1'),('orderRequireVerification',NULL),('orderVerificationEmailSubject','Order requires your verification');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departmentName` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES (1,'FMS'),(2,'Global Market');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `food`
--

DROP TABLE IF EXISTS `food`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `food` (
  `foodId` int(11) NOT NULL AUTO_INCREMENT,
  `foodCategoryId` int(11) NOT NULL,
  `foodName` varchar(125) NOT NULL,
  `price` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`foodId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `food`
--

LOCK TABLES `food` WRITE;
/*!40000 ALTER TABLE `food` DISABLE KEYS */;
INSERT INTO `food` VALUES (1,1,'Karipap Pusar Kentang',0.80),(2,7,'Teh Tarik/Nescafe 2 Kuih',5.00),(3,7,'Black Tea/Coffe - Curry Puff',3.50),(4,5,'Nasi Ayam',8.00),(5,5,'Nasi Beriani - Ayam Masak Merah',12.00),(6,5,'Nasi Beriani - Kambing',15.00),(7,3,'Teh Tarik',2.00);
/*!40000 ALTER TABLE `food` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `food_category`
--

DROP TABLE IF EXISTS `food_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `food_category` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(45) NOT NULL,
  PRIMARY KEY (`categoryId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `food_category`
--

LOCK TABLES `food_category` WRITE;
/*!40000 ALTER TABLE `food_category` DISABLE KEYS */;
INSERT INTO `food_category` VALUES (1,'Kuih Melayu'),(2,'Pastries'),(3,'Drinks'),(4,'Dessert'),(5,'Nasi'),(6,'Western Food'),(7,'Package');
/*!40000 ALTER TABLE `food_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) DEFAULT NULL,
  `invoiceDate` date DEFAULT NULL,
  `customerId` int(11) DEFAULT NULL,
  `invoiceNo` varchar(20) NOT NULL,
  `note` text,
  `totalAmount` decimal(8,2) DEFAULT NULL,
  `discount` decimal(6,2) DEFAULT NULL,
  `netAmount` decimal(8,2) DEFAULT NULL,
  `invoiceStatus` smallint(2) DEFAULT NULL,
  `dueDate` date DEFAULT NULL,
  `amountReceived` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index2` (`invoiceNo`)
) ENGINE=InnoDB AUTO_INCREMENT=361 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice`
--

LOCK TABLES `invoice` WRITE;
/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
INSERT INTO `invoice` VALUES (360,NULL,'2019-12-20',1,'INV/2019/12/0337',NULL,120.00,NULL,120.00,0,'2020-01-02',NULL);
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_detail`
--

DROP TABLE IF EXISTS `invoice_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceNumber` varchar(20) DEFAULT NULL,
  `invoiceId` int(11) DEFAULT NULL,
  `itemId` int(11) DEFAULT NULL,
  `itemDescription` varchar(120) DEFAULT NULL,
  `itemDescription2` varchar(120) DEFAULT NULL,
  `unitPrice` decimal(7,2) DEFAULT NULL,
  `quantity` smallint(6) DEFAULT NULL,
  `totalAmount` decimal(8,2) DEFAULT NULL,
  `note` text,
  `itemSort` smallint(6) DEFAULT NULL,
  `orderDetailId` int(11) DEFAULT NULL,
  `itemDate` date DEFAULT NULL,
  `orderId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invoice_detail_2_idx` (`invoiceNumber`),
  KEY `index3` (`invoiceNumber`),
  CONSTRAINT `fk_invoice_detail_1` FOREIGN KEY (`invoiceNumber`) REFERENCES `invoice` (`invoiceNo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_detail`
--

LOCK TABLES `invoice_detail` WRITE;
/*!40000 ALTER TABLE `invoice_detail` DISABLE KEYS */;
INSERT INTO `invoice_detail` VALUES (1,'INV/2019/12/0337',360,NULL,'zzzz','11:10 AM',NULL,10,0.00,NULL,NULL,4,'2019-10-02',NULL),(2,'INV/2019/12/0337',360,NULL,'aaaa','6:36 AM',NULL,22,0.00,NULL,NULL,5,'2019-10-06',NULL),(3,'INV/2019/12/0337',360,NULL,'Jamuan Alhi','9:30 AM',NULL,20,0.00,NULL,NULL,6,'2019-10-21',NULL),(4,'INV/2019/12/0337',360,NULL,'Jamuan Alhi','10:30 AM',NULL,20,0.00,NULL,NULL,7,'2019-10-22',NULL);
/*!40000 ALTER TABLE `invoice_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location` (
  `locationId` int(11) NOT NULL AUTO_INCREMENT,
  `locationName` varchar(125) NOT NULL,
  PRIMARY KEY (`locationId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` VALUES (1,'Cafe Pavillion'),(2,'MR1, L1'),(3,'MR2, L1'),(4,'MR2, L2');
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('Da\\User\\Migration\\m000000_000001_create_user_table',1566010791),('Da\\User\\Migration\\m000000_000002_create_profile_table',1566010792),('Da\\User\\Migration\\m000000_000003_create_social_account_table',1566010794),('Da\\User\\Migration\\m000000_000004_create_token_table',1566010795),('Da\\User\\Migration\\m000000_000005_add_last_login_at',1566010796),('Da\\User\\Migration\\m000000_000006_add_two_factor_fields',1566010797),('Da\\User\\Migration\\m000000_000007_enable_password_expiration',1566010797),('Da\\User\\Migration\\m000000_000008_add_last_login_ip',1566010798),('Da\\User\\Migration\\m000000_000009_add_gdpr_consent_fields',1566010800),('m000000_000000_base',1566010778),('m140506_102106_rbac_init',1566011343),('m170907_052038_rbac_add_index_on_auth_assignment_user_id',1566011343),('m180523_151638_rbac_updates_indexes_without_prefix',1566011344);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status`
--

DROP TABLE IF EXISTS `order_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_status` (
  `orderId` int(11) NOT NULL,
  `orderStatus` tinyint(2) NOT NULL,
  `status_date` datetime DEFAULT NULL,
  `status_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`orderId`,`orderStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status`
--

LOCK TABLES `order_status` WRITE;
/*!40000 ALTER TABLE `order_status` DISABLE KEYS */;
INSERT INTO `order_status` VALUES (1,1,'2019-08-18 19:23:50',1),(2,1,'2019-08-18 19:24:29',1),(2,2,'2019-08-18 19:26:44',1),(3,1,'2019-08-18 19:48:51',1),(3,2,'2019-08-18 19:49:02',1),(4,1,'2019-09-24 15:51:05',1),(5,1,'2019-10-03 14:36:16',1),(6,1,'2019-10-18 17:10:47',1),(6,2,'2019-10-19 13:59:16',1),(7,1,'2019-10-19 14:02:35',1),(8,1,'2019-10-19 14:10:29',1),(9,1,'2019-12-16 19:19:44',1);
/*!40000 ALTER TABLE `order_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravatar_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravatar_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8_unicode_ci,
  `managerId` int(11) DEFAULT NULL,
  `departmentId` int(11) DEFAULT NULL,
  `officeNo` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobileNo` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_profile_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
INSERT INTO `profile` VALUES (1,'Mazlan Mat','','','d41d8cd98f00b204e9800998ecf8427e',NULL,'',NULL,'',2,1,'',''),(2,'Nor Aini Abdul Rahman','','','d41d8cd98f00b204e9800998ecf8427e',NULL,'',NULL,'',NULL,1,'','');
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `serving_type`
--

DROP TABLE IF EXISTS `serving_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `serving_type` (
  `typeId` int(11) NOT NULL AUTO_INCREMENT,
  `typeName` varchar(75) NOT NULL,
  PRIMARY KEY (`typeId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `serving_type`
--

LOCK TABLES `serving_type` WRITE;
/*!40000 ALTER TABLE `serving_type` DISABLE KEYS */;
INSERT INTO `serving_type` VALUES (1,'Buffet'),(2,'Served'),(3,'Fine Dining'),(4,'Pack Food');
/*!40000 ALTER TABLE `serving_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_account`
--

DROP TABLE IF EXISTS `social_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `social_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_social_account_provider_client_id` (`provider`,`client_id`),
  UNIQUE KEY `idx_social_account_code` (`code`),
  KEY `fk_social_account_user` (`user_id`),
  CONSTRAINT `fk_social_account_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_account`
--

LOCK TABLES `social_account` WRITE;
/*!40000 ALTER TABLE `social_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token`
--

DROP TABLE IF EXISTS `token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `token` (
  `user_id` int(11) DEFAULT NULL,
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `created_at` int(11) NOT NULL,
  UNIQUE KEY `idx_token_user_id_code_type` (`user_id`,`code`,`type`),
  CONSTRAINT `fk_token_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token`
--

LOCK TABLES `token` WRITE;
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
/*!40000 ALTER TABLE `token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `unconfirmed_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flags` int(11) NOT NULL DEFAULT '0',
  `confirmed_at` int(11) DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `updated_at` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `last_login_at` int(11) DEFAULT NULL,
  `last_login_ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_tf_key` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_tf_enabled` tinyint(1) DEFAULT '0',
  `password_changed_at` int(11) DEFAULT NULL,
  `gdpr_consent` tinyint(1) DEFAULT '0',
  `gdpr_consent_date` int(11) DEFAULT NULL,
  `gdpr_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_username` (`username`),
  UNIQUE KEY `idx_user_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'matle','mm@mm.com','$2y$10$lwE0NNL.jbqUFxVvLF0qle6FUIGQMBGWlM6J2NcPP8IZhsYZbm7Ha','C0lAQSmauH01MF0CRdkFQKCgFrPesTUd',NULL,'127.0.0.1',0,1566032089,NULL,1566031978,1566031978,1580115037,'127.0.0.1','',0,1566031978,0,NULL,0),(2,'ani','zz@kk.com','$2y$10$bUEizIouTED5CtsN/kufEOvArc/l2US67wb5eG2zz3285qmuUH0bC','tkZcoffQ1ZniAc6Upso15SEwcG53O9vu',NULL,'127.0.0.1',0,1566127056,NULL,1566127030,1566127030,1566127070,'127.0.0.1','',0,1566127030,0,NULL,0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-30 16:57:39
