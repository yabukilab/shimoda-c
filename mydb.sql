CREATE DATABASE IF NOT EXISTS yoyakudb;

USE yoyakudb;

-- `list`テーブルの作成
CREATE TABLE `list` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `name1` varchar(50) NOT NULL,
  `name2` varchar(30) NOT NULL,
  `price` varchar(5) NOT NULL,
  `stock` int(11) NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- `list`テーブルへのデータ挿入
INSERT INTO `list` (`number`, `name1`, `name2`, `price`, `stock`) VALUES
(1, '教科書1', 'A社', '500', 25);

-- `yoyaku`テーブルの作成
CREATE TABLE `yoyaku` (
  `day` date NOT NULL,
  `code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
