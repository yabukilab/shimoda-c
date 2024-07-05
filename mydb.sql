SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `shimodac` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shimodac`;

CREATE TABLE `list` (
  `number` int(11) NOT NULL,
  `name1` varchar(50) NOT NULL,
  `name2` varchar(30) NOT NULL,
  `price` varchar(5) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `list` (`number`, `name1`, `name2`, `price`, `stock`) VALUES
(1, '教科書1', 'A社', '1000', 122),
(2, '教科書2', 'B社', '1500', 77),
(3, '教科書3', 'B社', '2400', 94),
(4, '教科書4', 'C社', '6000', 139);

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

INSERT INTO `yoyaku` (`day`, `code`, `number1`, `number2`, `number3`, `number4`, `number5`, `hidden`) VALUES
('2024-07-04', 1000000, 1, 2, 3, 4, 0, 0),
('2024-07-03', 1111111, 2, 3, 4, 0, 0, 1),
('2024-07-02', 9999999, 1, 2, 3, 4, 0, 1);


ALTER TABLE `list`
  ADD PRIMARY KEY (`number`);

ALTER TABLE `yoyaku`
  ADD PRIMARY KEY (`code`);


ALTER TABLE `list`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;