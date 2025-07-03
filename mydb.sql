-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2025-07-03 03:55:58
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
-- データベース: `mydb`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `dishes`
--

DROP TABLE IF EXISTS `dishes`;
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
(1, '彼のカレー', 500, '洋食', 'bbbbbbb', 1),
(4, 'ラーメン', 600, '中華', 'ddddddd', 1),
(5, 'チャーシュー', 200, '中華', 'nnnnnnn', 1),
(6, '天津飯', 500, '中華', 'mmmmmmm', 1),
(7, 'ちゃんぽん', 500, '中華', 'ttttttt', 1),
(8, 'たこやき', 600, '洋食', 'aaaaa', 1),
(9, 'かつ丼', 700, '和食', 'bbbbb', 1),
(10, 'うどん', 600, '和食', 'wawawa', 1),
(11, 'そば', 600, '和食', 'wawawawa', 1),
(12, 'スタ丼', 2000, '和食', 'wawawawawa', 1),
(13, '天ぷらうどん', 800, '和食', 'http://localhost/shimoda-c/htdocs/add_menu.php', 2),
(15, 'ビビンバ丼', 800, 'その他', 'http://localhost/shimoda-c/htdocs/add_menu.php', 3),
(16, '唐揚げ', 800, '和食', 'http://localhost/shimoda-c/htdocs/add_menu.php', 3),
(17, 'とり天', 800, '和食', 'http://localhost/shimoda-c/htdocs/add_menu.php', 3),
(18, 'ドーナツ', 200, '洋食', 'http://localhost/shimoda-c/htdocs/add_menu.php', 3),
(19, 'テストメニュー', 100, '和食', 'https://example.com', 3),
(20, 'うんこ', 5000, 'デザート', 'http://localhost/shimoda-c/htdocs/add_menu.php', 3);

-- --------------------------------------------------------

--
-- テーブルの構造 `dish_ingredients`
--

DROP TABLE IF EXISTS `dish_ingredients`;
CREATE TABLE `dish_ingredients` (
  `dish_ingredient_id` int(11) NOT NULL,
  `dish_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `himozukeshounin_umu` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `dish_ingredients`
--

INSERT INTO `dish_ingredients` (`dish_ingredient_id`, `dish_id`, `ingredient_id`, `himozukeshounin_umu`) VALUES
(4, 1, 2, 1),
(6, 13, 1, 0),
(7, 15, 1, 0),
(8, 16, 3, 0),
(9, 17, 3, 0),
(10, 18, 2, 0),
(11, 20, 3, 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `infomation`
--

DROP TABLE IF EXISTS `infomation`;
CREATE TABLE `infomation` (
  `user_id` varchar(30) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_hanbetu` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `infomation`
--

INSERT INTO `infomation` (`user_id`, `user_pass`, `user_hanbetu`) VALUES
('kami', '$2y$10$EL7qoijSUFQYMp1.1xWoLuQNRQuKhRBuYCEAENuD0QjWFH6xkFPiC', 0),
('mituhasi', '$2y$10$9TiBhRaFZH9J2.Fh.b/6guMNY6ZlVX08O6j5kFm266HjmI.TmJp9.', 0),
('takumi', '$2y$10$yFIaCnZS1.JGjxFjGY7xEuhEfXvIcsofufFndSLQEkZsjAGoC9RkK', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `ingredient_name` varchar(255) NOT NULL,
  `shounin_umu` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `ingredients`
--

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
  MODIFY `dish_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- テーブルの AUTO_INCREMENT `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  MODIFY `dish_ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
