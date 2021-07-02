-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2021-07-02 10:47:06
-- サーバのバージョン： 10.4.19-MariaDB
-- PHP のバージョン: 8.0.6

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
CREATE DATABASE IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mydb`;

-- --------------------------------------------------------

--
-- テーブルの構造 `keihindata`
--

CREATE TABLE `keihindata` (
  `ID` int(7) NOT NULL,
  `景品名` varchar(100) NOT NULL,
  `ジャンル` varchar(50) DEFAULT NULL,
  `作品名` varchar(50) DEFAULT NULL,
  `詳細` varchar(300) DEFAULT NULL,
  `店舗` varchar(100) DEFAULT NULL,
  `在庫` int(3) NOT NULL,
  `画像` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `keihindata`
--

INSERT INTO `keihindata` (`ID`, `景品名`, `ジャンル`, `作品名`, `詳細`, `店舗`, `在庫`, `画像`) VALUES
(2010105, '鬼滅の刃 でっかいぽふっとぬいぐるみ～竈門炭治郎･竈門禰豆子～', 'ぬいぐるみ', '鬼滅の刃', 'ぬいぐるみ\r\n高さ約20センチ\r\n2021/6/8より随時出荷予定', 'シルクハット津田沼', 100, '2010105.jpg'),
(5103569, 'HGUC 191 機動戦士ガンダム RX-78-2ガンダム 1/144スケール 色分け済みプラモデル', '動画クリエイター', 'ガンダム', '商品寸法 (長さx幅x高さ)	76.2 x 14.7 x 48.3 cm 材質	プラスチック　(C)創通・サンライズ 対象年齢 :8才以上', 'アミューズメントエース津田沼', 4, '5103569.jpg'),
(6250235, 'ヱヴァンゲリヲン新劇場版　LPMフィギュア“式波・アスカ・ラングレー”', 'フィギュア', 'ヱヴァンゲリヲン', '', 'モーリーファンタジー', 5, '');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `keihindata`
--
ALTER TABLE `keihindata`
  ADD PRIMARY KEY (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
