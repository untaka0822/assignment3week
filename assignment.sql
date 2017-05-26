-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017 年 5 月 26 日 14:59
-- サーバのバージョン： 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assignment`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `diary`
--

CREATE TABLE `diary` (
  `diary_id` int(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `contents` text NOT NULL,
  `picture_path` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `diary`
--

INSERT INTO `diary` (`diary_id`, `user_id`, `title`, `contents`, `picture_path`, `created`, `modified`) VALUES
(1, 1, 'スタバ', '鈴木さんと真哉さんに遭遇', '20170526143100Hayato.jpg', '2017-05-26 20:31:00', '2017-05-26 12:31:00'),
(2, 2, 'カジノ', '70万勝ち', '20170526143612seatatle.jpg', '2017-05-26 20:36:16', '2017-05-26 12:36:16');

-- --------------------------------------------------------

--
-- テーブルの構造 `likes`
--

CREATE TABLE `likes` (
  `member_id` int(11) NOT NULL,
  `diary_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `nick_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(100) CHARACTER SET utf8 NOT NULL,
  `picture_path` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- テーブルのデータのダンプ `members`
--

INSERT INTO `members` (`member_id`, `nick_name`, `email`, `password`, `picture_path`, `created`, `modified`) VALUES
(1, 'untaka', 'untaka0822@gmail.com', '989bb98d81d3af9547ba4831dad0851e01ec1233', '20170526142141Hayato.jpg', '2017-05-26 20:21:45', '2017-05-26 12:21:45'),
(2, 'shun', 'shun@gmail.com', '989bb98d81d3af9547ba4831dad0851e01ec1233', '20170526143507shun.jpg', '2017-05-26 20:35:08', '2017-05-26 12:35:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diary`
--
ALTER TABLE `diary`
  ADD PRIMARY KEY (`diary_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `diary`
--
ALTER TABLE `diary`
  MODIFY `diary_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
