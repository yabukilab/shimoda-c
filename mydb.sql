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
INSERT INTO `dish_ingredients` VALUES (4,1,2,1),(6,13,1,0),(7,15,1,0),(8,16,3,0),(9,17,3,0),(10,18,2,0),(11,20,3,0);
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
INSERT INTO `infomation` VALUES ('kami','$2y$10$EL7qoijSUFQYMp1.1xWoLuQNRQuKhRBuYCEAENuD0QjWFH6xkFPiC',0),('mituhasi','$2y$10$9TiBhRaFZH9J2.Fh.b/6guMNY6ZlVX08O6j5kFm266HjmI.TmJp9.',0),('takumi','$2y$10$yFIaCnZS1.JGjxFjGY7xEuhEfXvIcsofufFndSLQEkZsjAGoC9RkK',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredients`
--

LOCK TABLES `ingredients` WRITE;
/*!40000 ALTER TABLE `ingredients` DISABLE KEYS */;
INSERT INTO `ingredients` VALUES (1,'玉ねぎ',1),(2,'ルー',1),(3,'鶏肉',1),(4,'キャベツ',1),(5,'トマト',1),(23,'きゅうり',1),(24,'豚肉',1),(26,'牛肉',1),(27,'牛乳',1),(28,'卵',1),(29,'豆腐',1),(30,'合い挽き肉',1),(31,'人参',1),(32,'大根',1),(33,'わかめ',1),(34,'ねぎ',1),(35,'サツマイモ',1),(36,'レンコン',1),(37,'ちくわ',1),(53,'ベーコン',1),(54,'ハム',1),(55,'生クリーム',1),(56,'チーズ',1),(57,'ニンニク',1),(58,'チンゲン菜',1),(59,'小松菜',1),(60,'ほうれん草',1),(61,'セロリ',1),(62,'ブロッコリー',1),(63,'カリフラワー',1),(64,'ピーマン',1),(65,'レタス',1),(66,'パプリカ',1),(67,'貝',1),(68,'油揚げ',1),(74,'なす',1),(75,'もやし',1),(76,'ニラ',1),(77,'イチゴ',1),(78,'餅',1),(79,'ぶどう',1),(80,'バナナ',1),(81,'りんご',1),(82,'ナッツ',1),(83,'魚',1),(84,'麵類',1),(85,'きのこ',1),(86,'ウインナー',1),(87,'ツナ',1),(88,'キムチ',1),(89,'白菜',1),(90,'チョコレート',1),(91,'マシュマロ',1),(92,'クッキー',1);
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

-- Dump completed on 2025-07-03 15:39:03
