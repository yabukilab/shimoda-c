CREATE DATABASE IF NOT EXISTS shimodac;

USE shimodac;

-- `list`テーブルの作成
CREATE TABLE `list` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `name1` varchar(50) NOT NULL,
  `name2` varchar(30) NOT NULL,
  `price` varchar(5) NOT NULL,
  `stock` int(11) NOT NULL,
  PRIMARY KEY (`number`)
);

-- `list`テーブルにサンプルデータを挿入
INSERT INTO `list` (`name1`, `name2`, `price`, `stock`) VALUES
('教科書1', 'A社', '1000', 15),
('教科書2', 'B社', '1500', 50),
('教科書3', 'B社', '2400', 100);

-- `yoyaku`テーブルの作成
CREATE TABLE `yoyaku` (
  `day` date NOT NULL,
  `code` int(11) NOT NULL
);
