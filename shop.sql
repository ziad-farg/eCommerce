-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2022 at 01:27 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.1.2

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

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL COMMENT 'name of categories',
  `Description` text NOT NULL COMMENT 'description categories',
  `parent` int(11) NOT NULL,
  `Ordring` int(11) DEFAULT NULL COMMENT 'ordering of me for items',
  `Visiblity` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'hidden / show',
  `Comment` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'allow comment',
  `Ads` int(11) NOT NULL DEFAULT 0 COMMENT 'allow Ads'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `comments` (
  `C_ID` int(11) NOT NULL,
  `Comments` text NOT NULL,
  `Status` tinyint(4) NOT NULL DEFAULT 0,
  `C_Date` date NOT NULL,
  `Item_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`C_ID`, `Comments`, `Status`, `C_Date`, `Item_ID`, `User_ID`) VALUES
(1, 'this is the good game', 1, '2022-01-25', 2, 46),
(3, 'this is the best game in 2021\r\n', 1, '2022-01-26', 2, 58);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Description` text CHARACTER SET utf8 NOT NULL,
  `Price` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Image` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Status` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Rating` tinyint(4) NOT NULL,
  `Approved` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Approved Items',
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `tags` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approved`, `Cat_ID`, `Member_ID`, `tags`) VALUES
(2, 'Mortal Compate', 'This Is The Best Game Saller In 2021', '20', '2022-01-25', 'USA', '920351_MV5BY2ZlNWIxODMtN2YwZi00ZjNmLWIyN2UtZTFkYmZkNDQyNTAyXkEyXkFqcGdeQXVyODkzNTgxMDg@._V1_.jpg', '1', 0, 1, 24, 60, 'discount, RBG');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'ID User',
  `UserName` varchar(255) NOT NULL COMMENT 'User Name',
  `Pass` varchar(255) NOT NULL COMMENT 'Password',
  `Email` varchar(255) NOT NULL COMMENT 'E-mail',
  `FullName` varchar(255) NOT NULL COMMENT 'Full Name',
  `avatar` varchar(255) CHARACTER SET utf8 NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT 0 COMMENT 'Permition',
  `TrustStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'Rank',
  `RegStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'Approve',
  `DateEntry` date NOT NULL COMMENT 'Date Of Entry'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(60, 'soso', '601f1889667efaebb33b8c12572835da3f027f78', 'soso@gmail.com', '', '', 0, 0, 1, '2022-01-24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`C_ID`),
  ADD KEY `User_Comment` (`User_ID`),
  ADD KEY `Item_Comment` (`Item_ID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `cat_1` (`Cat_ID`),
  ADD KEY `member_1` (`Member_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `C_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID User', AUTO_INCREMENT=61;

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
