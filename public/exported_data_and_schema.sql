-- MySQL dump 10.13  Distrib 5.7.39, for osx11.0 (x86_64)
--
-- Host: localhost    Database: swift_new
-- ------------------------------------------------------
-- Server version	5.7.39

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
-- Table structure for table `airtelcgdata_plan`
--

DROP TABLE IF EXISTS `airtelcgdata_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `airtelcgdata_plan` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `airtelcg_100` int(20) NOT NULL,
  `airtelcg_300` int(20) NOT NULL,
  `airtelcg_500` int(20) NOT NULL,
  `airtelcg_1` int(20) NOT NULL,
  `airtelcg_2` int(20) NOT NULL,
  `airtelcg_5` int(20) NOT NULL,
  `easy_access104` int(11) DEFAULT '0',
  `easy_access105` int(11) DEFAULT '0',
  `easy_access106` int(11) DEFAULT '0',
  `easy_access107` int(11) DEFAULT '0',
  `easy_access108` int(11) DEFAULT '0',
  `easy_access109` int(11) DEFAULT '0',
  `easy_access124` int(11) DEFAULT '0',
  `easy_access139` int(11) DEFAULT '0',
  `easy_access140` int(11) DEFAULT '0',
  `gongzo_data312` int(11) DEFAULT NULL,
  `gongzo_data313` int(11) DEFAULT NULL,
  `gongzo_data314` int(11) DEFAULT NULL,
  `gongzo_data315` int(11) DEFAULT NULL,
  `gongzo_data317` int(11) DEFAULT NULL,
  `gongzo_data318` int(11) DEFAULT NULL,
  `gongzo_data319` int(11) DEFAULT NULL,
  `app_version` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `airtelcgdata_plan`
--
-- WHERE:  id < 3

LOCK TABLES `airtelcgdata_plan` WRITE;
/*!40000 ALTER TABLE `airtelcgdata_plan` DISABLE KEYS */;
INSERT INTO `airtelcgdata_plan` VALUES (1,145,180,240,300,600,1500,439,92,18,215,430,1075,2150,3225,4300,116,231,461,1151,2301,85,29,NULL,NULL,NULL),(2,140,170,210,280,560,1400,439,92,18,0,0,0,0,0,0,116,231,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `airtelcgdata_plan` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-07 10:04:29
