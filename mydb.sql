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

-- `yoyaku`テーブルの作成
CREATE TABLE `yoyaku` (
  `day` date NOT NULL,
  `code` int(7) NOT NULL,
  `number1` int(7) NOT NULL,
  `number2` int(7) NOT NULL,
  `number3` int(7) NOT NULL,
  `number4` int(7) NOT NULL,
  `number5` int(7) NOT NULL,
  `hidden` int(1) NOT NULL,
  PRIMARY KEY (`code`)
);
