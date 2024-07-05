SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- データベース: `shimodac`
--
CREATE DATABASE IF NOT EXISTS `shimodac`;
USE `shimodac`;

-- テーブルの構造 `list`

CREATE TABLE `list` (
  `number` int(11) NOT NULL,
  `name1` varchar(50) NOT NULL,
  `name2` varchar(30) NOT NULL,
  `price` varchar(5) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- テーブルのデータのダンプ `list`

INSERT INTO `list` (`number`, `name1`, `name2`, `price`, `stock`) VALUES
(1, '教科書1', 'A社', '1000', 120),
(2, '教科書2', 'B社', '1500', 75),
(3, '教科書3', 'B社', '2400', 100),
(4, '教科書4', 'C社', '600', 140),
(5, '教科書5', 'C社', '10000', 160),
(6, '教科書6', 'D社', '3000', 130);

CREATE TABLE `yoyaku` (
  `day` date NOT NULL,
  `code` int(7) NOT NULL,
  `number1` int(7) NOT NULL,
  `number2` int(7) NOT NULL,
  `number3` int(7) NOT NULL,
  `number4` int(7) NOT NULL,
  `number5` int(7) NOT NULL,
  `hidden` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- テーブルのインデックス `list`

ALTER TABLE `list`
  ADD PRIMARY KEY (`number`);

-- テーブルのインデックス `yoyaku`

ALTER TABLE `yoyaku`
  ADD PRIMARY KEY (`code`);

-- テーブルの AUTO_INCREMENT `list`

ALTER TABLE `list`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;
