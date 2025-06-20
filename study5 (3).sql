-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2025-06-20 08:41:40
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `study5`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `dishes`
--

CREATE TABLE `dishes` (
  `dish_id` int(11) NOT NULL,
  `dish_name` varchar(255) NOT NULL,
  `calories` int(11) DEFAULT NULL,
  `dish_category` varchar(255) NOT NULL,
  `menu_url` varchar(255) NOT NULL,
  `Shounin_umu` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `dishes`
--

INSERT INTO `dishes` (`dish_id`, `dish_name`, `calories`, `dish_category`, `menu_url`, `Shounin_umu`) VALUES
(1, '彼のカレー', 500, '洋食', 'bbbbbbb', 0),
(3, '炒飯', 600, '中華', 'cccccc', 0),
(4, 'ラーメン', 600, '中華', 'ddddddd', 0),
(5, 'チャーシュー', 300, '中華', 'nnnnnnn', 0),
(6, '天津飯', 500, '中華', 'mmmmmmm', 0),
(7, 'ちゃんぽん', 500, '中華', 'ttttttt', 0),
(8, 'たこやき', 600, '日本食', 'aaaaa', 0),
(9, 'かつ丼', 700, '日本食', 'bbbbb', 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `dish_ingredients`
--

CREATE TABLE `dish_ingredients` (
  `dish_ingredient_id` int(11) NOT NULL,
  `dish_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `dish_ingredients`
--

INSERT INTO `dish_ingredients` (`dish_ingredient_id`, `dish_id`, `ingredient_id`) VALUES
(1, 1, 1),
(4, 1, 2);

-- --------------------------------------------------------

--
-- テーブルの構造 `infomation`
--

CREATE TABLE `infomation` (
  `user_id` varchar(30) NOT NULL,
  `user_pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `ingredient_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `ingredient_name`) VALUES
(2, 'ルー'),
(1, '玉ねぎ'),
(3, '鶏肉');

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
