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
INSERT INTO `list` (`number`,`name1`, `name2`, `price`, `stock`) VALUES
(1,'教科書1', 'A社', '1000', 15),
(2,'教科書2', 'B社', '1500', 50),
(3,'教科書3', 'B社', '2400', 100);

-- `yoyaku`テーブルの作成
CREATE TABLE `yoyaku` (
  `day` date NOT NULL,
  `code` int(7) NOT NULL,
  `number1` int(7) NOT NULL,
  `number2` int(7) NOT NULL,
  `number3` int(7) NOT NULL,
  `number4` int(7) NOT NULL,
  `number5` int(7) NOT NULL,
  PRIMARY KEY (`code`)
);
