-- phpMyAdmin SQL Dump
-- version 4.4.13.1deb1
-- http://www.phpmyadmin.net
--
-- Hostiteľ: localhost
-- Čas generovania: Št 17.Mar 2016, 19:44
-- Verzia serveru: 5.6.28-0ubuntu0.15.10.1
-- Verzia PHP: 5.6.11-1ubuntu3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `diplomka`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Comment`
--

CREATE TABLE IF NOT EXISTS `Comment` (
  `commentId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Post`
--

CREATE TABLE IF NOT EXISTS `Post` (
  `postId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text,
  `subjectId` int(11) NOT NULL,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Subject`
--

CREATE TABLE IF NOT EXISTS `Subject` (
  `subjectId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Teacher_Subject`
--

CREATE TABLE IF NOT EXISTS `Teacher_Subject` (
  `userId` int(11) NOT NULL DEFAULT '0',
  `subjectId` int(11) NOT NULL DEFAULT '0',
  `aboutSubject` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `userId` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `userlastname` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `gender` char(1) NOT NULL,
  `birthdate` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `is_teacher` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `User_to_Post_Like`
--

CREATE TABLE IF NOT EXISTS `User_to_Post_Like` (
  `userId` int(11) NOT NULL DEFAULT '0',
  `postId` int(11) NOT NULL DEFAULT '0',
  `liked_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `User_to_Post_Share`
--

CREATE TABLE IF NOT EXISTS `User_to_Post_Share` (
  `userId` int(11) NOT NULL DEFAULT '0',
  `postId` int(11) NOT NULL DEFAULT '0',
  `shared_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `User_to_User`
--

CREATE TABLE IF NOT EXISTS `User_to_User` (
  `userId1` int(11) NOT NULL DEFAULT '0',
  `userId2` int(11) NOT NULL DEFAULT '0',
  `accepted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `Comment`
--
ALTER TABLE `Comment`
  ADD PRIMARY KEY (`commentId`);

--
-- Indexy pre tabuľku `Post`
--
ALTER TABLE `Post`
  ADD PRIMARY KEY (`postId`);

--
-- Indexy pre tabuľku `Subject`
--
ALTER TABLE `Subject`
  ADD PRIMARY KEY (`subjectId`);

--
-- Indexy pre tabuľku `Teacher_Subject`
--
ALTER TABLE `Teacher_Subject`
  ADD PRIMARY KEY (`userId`,`subjectId`);

--
-- Indexy pre tabuľku `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`userId`);

--
-- Indexy pre tabuľku `User_to_Post_Like`
--
ALTER TABLE `User_to_Post_Like`
  ADD PRIMARY KEY (`userId`,`postId`);

--
-- Indexy pre tabuľku `User_to_Post_Share`
--
ALTER TABLE `User_to_Post_Share`
  ADD PRIMARY KEY (`userId`,`postId`);

--
-- Indexy pre tabuľku `User_to_User`
--
ALTER TABLE `User_to_User`
  ADD PRIMARY KEY (`userId1`,`userId2`),
  ADD KEY `userId2` (`userId2`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `Comment`
--
ALTER TABLE `Comment`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pre tabuľku `Post`
--
ALTER TABLE `Post`
  MODIFY `postId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pre tabuľku `Subject`
--
ALTER TABLE `Subject`
  MODIFY `subjectId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pre tabuľku `User`
--
ALTER TABLE `User`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT;
--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `User_to_User`
--
ALTER TABLE `User_to_User`
  ADD CONSTRAINT `User_to_User_ibfk_1` FOREIGN KEY (`userId1`) REFERENCES `User` (`userId`),
  ADD CONSTRAINT `User_to_User_ibfk_2` FOREIGN KEY (`userId2`) REFERENCES `User` (`userId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
