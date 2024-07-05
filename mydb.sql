SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `shimodac`
--
CREATE DATABASE IF NOT EXISTS `shimodac` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shimodac`;

-- --------------------------------------------------------

--
-- テーブルの構造 `list`
--

DROP TABLE IF EXISTS `list`;
CREATE TABLE IF NOT EXISTS `list` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `name1` varchar(50) NOT NULL,
  `name2` varchar(30) NOT NULL,
  `price` varchar(5) NOT NULL,
  `stock` int(11) NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `list`
--

INSERT INTO `list` (`number`, `name1`, `name2`, `price`, `stock`) VALUES
(1, '教科書1', 'A社', '1000', 120),
(2, '教科書2', 'B社', '1500', 80),
(3, '教科書3', 'B社', '2200', 90),
(4, '教科書4', 'C社', '1300', 140),
(5, '教科書5', 'B社', '800', 160);

-- --------------------------------------------------------

--
-- テーブルの構造 `yoyaku`
--

DROP TABLE IF EXISTS `yoyaku`;
CREATE TABLE IF NOT EXISTS `yoyaku` (
  `day` date NOT NULL,
  `code` int(7) NOT NULL,
  `number1` int(7) NOT NULL DEFAULT 0,
  `number2` int(7) NOT NULL DEFAULT 0,
  `number3` int(7) NOT NULL DEFAULT 0,
  `number4` int(7) NOT NULL DEFAULT 0,
  `number5` int(7) NOT NULL DEFAULT 0,
  `hidden` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `yoyaku`
--

INSERT INTO `yoyaku` (`day`, `code`, `number1`, `number2`, `number3`, `number4`, `number5`, `hidden`) VALUES
('2024-07-04', 1000000, 1, 2, 3, 4, 0, 0),
('2024-07-04', 2200000, 3, 4, 0, 0, 0, 1),
('2024-07-04', 9999999, 1, 2, 3, 4, 0, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
