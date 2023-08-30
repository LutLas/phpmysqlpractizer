-- MySQL dump 10.13  Distrib 8.0.31, for macos12 (x86_64)
--
-- Host: 127.0.0.1    Database: practizer
-- ------------------------------------------------------
-- Server version	5.5.5-10.10.2-MariaDB-1:10.10.2+maria~ubu2204

--
-- Table structure for table `author`
--

DROP TABLE IF EXISTS `author`;
CREATE TABLE `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `permissions` int(64) DEFAULT NULL,
  `verified` tinyint(4) DEFAULT NULL,
  `archived` tinyint(4) NOT NULL DEFAULT 0,
  `acceptedprivacypolicy` tinyint(4) DEFAULT NULL,
  `acceptedeula` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `archived` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Table structure for table `joke`
--

DROP TABLE IF EXISTS `joke`;
CREATE TABLE `joke` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `joketext` text DEFAULT NULL,
  `jokedate` datetime NOT NULL,
  `authorid` int(11) DEFAULT NULL,
  `joketitle` varchar(45) DEFAULT NULL,
  `artistname` varchar(45) DEFAULT NULL,
  `producername` varchar(45) DEFAULT NULL,
  `albumcover` varchar(255) DEFAULT NULL,
  `albumname` varchar(45) DEFAULT NULL,
  `song` varchar(255) DEFAULT NULL,
  `datetimepublished` datetime NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT 0,
  `archived` tinyint(4) NOT NULL DEFAULT 0,
  `tracknumber` varchar(45) DEFAULT 'N/A',
  `datetimeapproved` datetime DEFAULT NULL,
  `datetimearchived` datetime DEFAULT NULL,
  `approvedby` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Table structure for table `jokecategory`
--

DROP TABLE IF EXISTS `jokecategory`;
CREATE TABLE `jokecategory` (
  `jokeid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  PRIMARY KEY (`jokeid`,`categoryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dump completed on 2023-08-30 15:31:41
