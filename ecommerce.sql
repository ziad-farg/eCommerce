-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 23, 2024 at 12:48 AM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL COMMENT 'name of categories',
  `Description` text NOT NULL COMMENT 'description categories',
  `parent` int NOT NULL,
  `Ordring` int DEFAULT NULL COMMENT 'ordering of me for items',
  `Visiblity` tinyint NOT NULL DEFAULT '0' COMMENT 'hidden / show',
  `Comment` tinyint NOT NULL DEFAULT '0' COMMENT 'allow comment',
  `Ads` int NOT NULL DEFAULT '0' COMMENT 'allow Ads',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `parent`, `Ordring`, `Visiblity`, `Comment`, `Ads`) VALUES
(15, 'Computers', 'computers items', 0, 1, 0, 0, 0),
(16, 'Cell Phones', 'Cell Phones', 0, 2, 0, 0, 0),
(17, 'Clothing', 'Clothing And Fashon', 0, 3, 0, 0, 0),
(18, 'Hand Made', 'Hand Made Item', 0, 4, 0, 0, 0),
(19, 'Tools', 'Home Tools', 0, 0, 1, 1, 1),
(21, 'T-shirt', 'T-shirt for sale', 17, 1, 0, 0, 0),
(22, 'IPhone', 'IPhone Cell Phone', 16, 2, 0, 0, 0),
(23, 'Nokia', 'Nokia Products', 16, 2, 0, 0, 0),
(24, 'Games', 'this is sub gategory of computer', 15, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `C_ID` int NOT NULL AUTO_INCREMENT,
  `Comments` text NOT NULL,
  `Status` tinyint NOT NULL DEFAULT '0',
  `C_Date` date NOT NULL,
  `Item_ID` int NOT NULL,
  `User_ID` int NOT NULL,
  PRIMARY KEY (`C_ID`),
  KEY `User_Comment` (`User_ID`),
  KEY `Item_Comment` (`Item_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `Item_ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Price` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Status` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Rating` tinyint NOT NULL,
  `Approved` tinyint NOT NULL DEFAULT '0' COMMENT 'Approved Items',
  `Cat_ID` int NOT NULL,
  `Member_ID` int NOT NULL,
  `tags` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`Item_ID`),
  KEY `cat_1` (`Cat_ID`),
  KEY `member_1` (`Member_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int NOT NULL AUTO_INCREMENT COMMENT 'ID User',
  `UserName` varchar(255) NOT NULL COMMENT 'User Name',
  `Pass` varchar(255) NOT NULL COMMENT 'Password',
  `Email` varchar(255) NOT NULL COMMENT 'E-mail',
  `FullName` varchar(255) NOT NULL COMMENT 'Full Name',
  `avatar` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `GroupID` int NOT NULL DEFAULT '0' COMMENT 'Permition',
  `TrustStatus` int NOT NULL DEFAULT '0' COMMENT 'Rank',
  `RegStatus` int NOT NULL DEFAULT '0' COMMENT 'Approve',
  `DateEntry` date NOT NULL COMMENT 'Date Of Entry',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `UserName` (`UserName`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `Pass`, `Email`, `FullName`, `avatar`, `GroupID`, `TrustStatus`, `RegStatus`, `DateEntry`) VALUES
(1, 'ziad', '601f1889667efaebb33b8c12572835da3f027f78', 'ziad@gmail.com', 'ziad farg', '', 1, 0, 1, '2021-10-22'),
(45, 'sara', '601f1889667efaebb33b8c12572835da3f027f78', 'sara@gmail.com', 'sara farag', '', 0, 0, 0, '2021-12-13'),
(46, 'naser', '601f1889667efaebb33b8c12572835da3f027f78', 'naser@gmail.com', 'naser shata', '305030_30739148_2056337244584579_5129823927510400713_n.jpg', 0, 0, 1, '2021-12-14'),
(47, 'ali', '601f1889667efaebb33b8c12572835da3f027f78', 'ali@gmail.com', 'ali shara', '', 0, 0, 1, '2021-12-14'),
(48, 'ahmed', '601f1889667efaebb33b8c12572835da3f027f78', 'ahmed@gmail.com', 'ahmed gamal', '', 0, 0, 1, '2021-12-14'),
(49, 'medhat', '601f1889667efaebb33b8c12572835da3f027f78', 'medhat@gmail.com', 'mohamed medht', '', 0, 0, 1, '2021-12-14'),
(50, 'mando', '601f1889667efaebb33b8c12572835da3f027f78', 'mando@gmail.com', 'mando elmansory', '', 0, 0, 1, '2021-12-14'),
(51, 'amr', '601f1889667efaebb33b8c12572835da3f027f78', 'amr@gmail.com', 'amr ziad', '', 0, 0, 1, '2021-12-01'),
(53, 'noha', '601f1889667efaebb33b8c12572835da3f027f78', 'noha@gmail.com', '', '', 0, 0, 0, '2021-12-23'),
(54, 'zeze', '601f1889667efaebb33b8c12572835da3f027f78', 'zeze@gmail.com', '', '', 0, 0, 0, '2021-12-23'),
(55, 'lila', '601f1889667efaebb33b8c12572835da3f027f78', 'lila@gmial.com', '', '', 0, 0, 0, '2021-12-23'),
(56, 'noor', '601f1889667efaebb33b8c12572835da3f027f78', 'noor@gmail.com', '', '', 0, 0, 0, '2021-12-23'),
(57, 'alaa', '601f1889667efaebb33b8c12572835da3f027f78', 'alaa@gmial.com', '', '', 0, 0, 1, '2021-12-23'),
(58, 'yahia', '601f1889667efaebb33b8c12572835da3f027f78', 'yahia@gmial.com', 'yahia yasser', '623439_Jellyfish.jpg', 0, 0, 1, '2022-01-19'),
(61, 'admin', '7af2d10b73ab7cd8f603937f7697cb5fe432c7ff', 'admin@gmail.com', '', '', 1, 1, 1, '2024-01-22');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `Item_Comment` FOREIGN KEY (`Item_ID`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `User_Comment` FOREIGN KEY (`User_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
