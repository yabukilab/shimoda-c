-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: mydb
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
-- Table structure for table `dish_ingredients`
--

DROP TABLE IF EXISTS `dish_ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dish_ingredients` (
  `dish_ingredient_id` int(11) NOT NULL AUTO_INCREMENT,
  `dish_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `himozukeshounin_umu` int(1) NOT NULL,
  PRIMARY KEY (`dish_ingredient_id`),
  UNIQUE KEY `dish_id` (`dish_id`,`ingredient_id`),
  KEY `ingredient_id` (`ingredient_id`),
  CONSTRAINT `dish_ingredients_ibfk_1` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`dish_id`) ON DELETE CASCADE,
  CONSTRAINT `dish_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dish_ingredients`
--

LOCK TABLES `dish_ingredients` WRITE;
/*!40000 ALTER TABLE `dish_ingredients` DISABLE KEYS */;
INSERT INTO `dish_ingredients` VALUES (1,1,1,1),(4,1,2,1),(6,13,1,0),(7,15,1,0),(8,16,3,0),(9,17,3,0),(10,18,2,0),(11,20,3,0);
/*!40000 ALTER TABLE `dish_ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dishes`
--

DROP TABLE IF EXISTS `dishes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dishes` (
  `dish_id` int(11) NOT NULL AUTO_INCREMENT,
  `dish_name` varchar(255) NOT NULL,
  `calories` int(11) DEFAULT NULL,
  `dish_category` varchar(255) NOT NULL,
  `menu_url` varchar(255) NOT NULL,
  `Shounin_umu` int(1) NOT NULL,
  PRIMARY KEY (`dish_id`),
  UNIQUE KEY `dish_name` (`dish_name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dishes`
--

LOCK TABLES `dishes` WRITE;
/*!40000 ALTER TABLE `dishes` DISABLE KEYS */;
INSERT INTO `dishes` VALUES (1,'彼のカレー',500,'洋食','bbbbbbb',1),(4,'ラーメン',600,'中華','ddddddd',1),(5,'チャーシュー',200,'中華','nnnnnnn',1),(6,'天津飯',500,'中華','mmmmmmm',1),(7,'ちゃんぽん',500,'中華','ttttttt',1),(8,'たこやき',600,'洋食','aaaaa',1),(9,'かつ丼',700,'和食','bbbbb',1),(10,'うどん',600,'和食','wawawa',1),(11,'そば',600,'和食','wawawawa',1),(12,'スタ丼',2000,'和食','wawawawawa',1),(13,'天ぷらうどん',800,'和食','http://localhost/shimoda-c/htdocs/add_menu.php',2),(15,'ビビンバ丼',800,'その他','http://localhost/shimoda-c/htdocs/add_menu.php',3),(16,'唐揚げ',800,'和食','http://localhost/shimoda-c/htdocs/add_menu.php',3),(17,'とり天',800,'和食','http://localhost/shimoda-c/htdocs/add_menu.php',3),(18,'ドーナツ',200,'洋食','http://localhost/shimoda-c/htdocs/add_menu.php',3),(19,'テストメニュー',100,'和食','https://example.com',3),(20,'うんこ',5000,'デザート','http://localhost/shimoda-c/htdocs/add_menu.php',3);
/*!40000 ALTER TABLE `dishes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `infomation`
--

DROP TABLE IF EXISTS `infomation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `infomation` (
  `user_id` varchar(30) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_hanbetu` int(1) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `infomation`
--

LOCK TABLES `infomation` WRITE;
/*!40000 ALTER TABLE `infomation` DISABLE KEYS */;
INSERT INTO `infomation` VALUES ('kami','$2y$10$EL7qoijSUFQYMp1.1xWoLuQNRQuKhRBuYCEAENuD0QjWFH6xkFPiC',1),('takumi','$2y$10$yFIaCnZS1.JGjxFjGY7xEuhEfXvIcsofufFndSLQEkZsjAGoC9RkK',0),('tuki','$2y$10$CbkU2yLy9uMEJ7KLjWn0Pu3jqx2HS4rFN6vGVdbEWMvV4gWBpeRtG',1);
/*!40000 ALTER TABLE `infomation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL AUTO_INCREMENT,
  `ingredient_name` varchar(255) NOT NULL,
  `shounin_umu` int(1) NOT NULL,
  PRIMARY KEY (`ingredient_id`),
  UNIQUE KEY `ingredient_name` (`ingredient_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredients`
--

LOCK TABLES `ingredients` WRITE;
/*!40000 ALTER TABLE `ingredients` DISABLE KEYS */;
INSERT INTO `ingredients` VALUES (1,'玉ねぎ',1),(2,'ルー',1),(3,'鶏肉',1);
/*!40000 ALTER TABLE `ingredients` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-03  0:15:15
