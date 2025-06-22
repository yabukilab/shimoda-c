-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
<<<<<<< HEAD
-- Host: localhost    Database: mydb
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB
=======
-- ホスト: 127.0.0.1
-- 生成日時: 2025-06-21 17:21:42
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

>>>>>>> 94986eaf8529501664ae5d22de73d5221a2c1342

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `mydb`
--

<<<<<<< HEAD
DROP TABLE IF EXISTS `dish_ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dish_ingredients` (
  `dish_ingredient_id` int(11) NOT NULL AUTO_INCREMENT,
  `dish_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `shounin_umu` int(1) NOT NULL,
  PRIMARY KEY (`dish_ingredient_id`),
  UNIQUE KEY `dish_id` (`dish_id`,`ingredient_id`),
  KEY `ingredient_id` (`ingredient_id`),
  CONSTRAINT `dish_ingredients_ibfk_1` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`dish_id`) ON DELETE CASCADE,
  CONSTRAINT `dish_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
=======
-- --------------------------------------------------------
>>>>>>> 94986eaf8529501664ae5d22de73d5221a2c1342

--
-- テーブルの構造 `dishes`
--

<<<<<<< HEAD
LOCK TABLES `dish_ingredients` WRITE;
/*!40000 ALTER TABLE `dish_ingredients` DISABLE KEYS */;
INSERT INTO `dish_ingredients` VALUES (1,1,1,1),(4,1,2,1);
/*!40000 ALTER TABLE `dish_ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dishes`
--

DROP TABLE IF EXISTS `dishes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
=======
>>>>>>> 94986eaf8529501664ae5d22de73d5221a2c1342
CREATE TABLE `dishes` (
  `dish_id` int(11) NOT NULL,
  `dish_name` varchar(255) NOT NULL,
  `calories` int(11) DEFAULT NULL,
  `dish_category` varchar(255) NOT NULL,
  `menu_url` varchar(255) NOT NULL,
<<<<<<< HEAD
  `Shounin_umu` int(1) NOT NULL,
  PRIMARY KEY (`dish_id`),
  UNIQUE KEY `dish_name` (`dish_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
=======
  `Shounin_umu` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
>>>>>>> 94986eaf8529501664ae5d22de73d5221a2c1342

--
-- テーブルのデータのダンプ `dishes`
--

<<<<<<< HEAD
LOCK TABLES `dishes` WRITE;
/*!40000 ALTER TABLE `dishes` DISABLE KEYS */;
INSERT INTO `dishes` VALUES (1,'彼のカレー',500,'洋食','bbbbbbb',1),(4,'ラーメン',600,'中華','ddddddd',1),(5,'チャーシュー',200,'中華','nnnnnnn',1),(6,'天津飯',500,'中華','mmmmmmm',1),(7,'ちゃんぽん',500,'中華','ttttttt',1),(8,'たこやき',600,'洋食','aaaaa',1),(9,'かつ丼',700,'和食','bbbbb',1);
/*!40000 ALTER TABLE `dishes` ENABLE KEYS */;
UNLOCK TABLES;
=======
INSERT INTO `dishes` (`dish_id`, `dish_name`, `calories`, `dish_category`, `menu_url`, `Shounin_umu`) VALUES
(1, '彼のカレー', 500, '洋食', 'bbbbbbb', 1),
(4, 'ラーメン', 600, '中華', 'ddddddd', 1),
(5, 'チャーシュー', 200, '中華', 'nnnnnnn', 1),
(6, '天津飯', 500, '中華', 'mmmmmmm', 1),
(7, 'ちゃんぽん', 500, '中華', 'ttttttt', 1),
(8, 'たこやき', 600, '洋食', 'aaaaa', 1),
(9, 'かつ丼', 700, '和食', 'bbbbb', 1);

-- --------------------------------------------------------
>>>>>>> 94986eaf8529501664ae5d22de73d5221a2c1342

--
-- テーブルの構造 `dish_ingredients`
--

CREATE TABLE `dish_ingredients` (
  `dish_ingredient_id` int(11) NOT NULL,
  `dish_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `shounin_umu` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `dish_ingredients`
--

INSERT INTO `dish_ingredients` (`dish_ingredient_id`, `dish_id`, `ingredient_id`, `shounin_umu`) VALUES
(1, 1, 1, 1),
(4, 1, 2, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `infomation`
--

CREATE TABLE `infomation` (
  `user_id` varchar(30) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
<<<<<<< HEAD
  `user_hanbetu` int(1) NOT NULL,
  PRIMARY KEY (`user_id`)
=======
  `user_hanbetu` int(1) NOT NULL
>>>>>>> 94986eaf8529501664ae5d22de73d5221a2c1342
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `ingredient_name` varchar(255) NOT NULL,
<<<<<<< HEAD
  `shounin_umu` int(1) NOT NULL,
  PRIMARY KEY (`ingredient_id`),
  UNIQUE KEY `ingredient_name` (`ingredient_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
=======
  `shounin_umu` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
>>>>>>> 94986eaf8529501664ae5d22de73d5221a2c1342

--
-- テーブルのデータのダンプ `ingredients`
--

<<<<<<< HEAD
LOCK TABLES `ingredients` WRITE;
/*!40000 ALTER TABLE `ingredients` DISABLE KEYS */;
INSERT INTO `ingredients` VALUES (1,'玉ねぎ',1),(2,'ルー',1),(3,'鶏肉',1);
/*!40000 ALTER TABLE `ingredients` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
=======
INSERT INTO `ingredients` (`ingredient_id`, `ingredient_name`, `shounin_umu`) VALUES
(1, '玉ねぎ', 1),
(2, 'ルー', 1),
(3, '鶏肉', 1);

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`dish_id`),
  ADD UNIQUE KEY `dish_name` (`dish_name`);

--
-- テーブルのインデックス `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  ADD PRIMARY KEY (`dish_ingredient_id`),
  ADD UNIQUE KEY `dish_id` (`dish_id`,`ingredient_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- テーブルのインデックス `infomation`
--
ALTER TABLE `infomation`
  ADD PRIMARY KEY (`user_id`);

--
-- テーブルのインデックス `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD UNIQUE KEY `ingredient_name` (`ingredient_name`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `dishes`
--
ALTER TABLE `dishes`
  MODIFY `dish_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- テーブルの AUTO_INCREMENT `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  MODIFY `dish_ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- テーブルの AUTO_INCREMENT `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  ADD CONSTRAINT `dish_ingredients_ibfk_1` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`dish_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dish_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`) ON DELETE CASCADE;
COMMIT;
>>>>>>> 94986eaf8529501664ae5d22de73d5221a2c1342

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
<<<<<<< HEAD
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-22  0:43:41
=======
>>>>>>> 94986eaf8529501664ae5d22de73d5221a2c1342
