-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: 2019 年 8 月 24 日 00:26
-- サーバのバージョン： 5.7.25
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE Imageboard;
USE Imageboard;

--
-- Database: `Imageboard`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `replay_posts`
--

CREATE TABLE `replay_posts` (
  `reply_co_id` int(11) NOT NULL,
  `reply_id` int(11) NOT NULL COMMENT '個別ページ番号',
  `reply_author_id` int(11) NOT NULL COMMENT 'ユーザーid',
  `reply_author_name` varchar(255) NOT NULL,
  `reply_content` text,
  `reply_img` varchar(255) DEFAULT NULL,
  `re_create_at` datetime NOT NULL,
  `re_modefied` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `tweets`
--

CREATE TABLE `tweets` (
  `author_id` int(11) NOT NULL,
  `tweets_id` int(11) NOT NULL,
  `uniq_id` varchar(255) NOT NULL,
  `content` text,
  `tweet_img` varchar(255) DEFAULT NULL,
  `maxpost` int(11) DEFAULT NULL,
  `create_at` datetime NOT NULL,
  `modefied` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `tweets`
--

INSERT INTO `tweets` (`author_id`, `tweets_id`, `uniq_id`, `content`, `tweet_img`, `maxpost`, `create_at`, `modefied`) VALUES
(86, 20, '3acbb466d632113d6b21d8d80666b37c', '', 'test156656607186.jpg', NULL, '2019-08-23 22:14:32', '2019-08-23 13:14:32'),
(88, 21, '4b0e4006f08474e8f3b1ffb0f7aac062', 'aaa', '0', NULL, '2019-08-23 22:52:31', '2019-08-23 13:52:31');

-- --------------------------------------------------------

--
-- テーブルの構造 `userinfo`
--

CREATE TABLE `userinfo` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `back_img` int(11) NOT NULL DEFAULT '0',
  `introduction` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `userinfo`
--

INSERT INTO `userinfo` (`user_id`, `name`, `password`, `created`, `icon`, `back_img`, `introduction`) VALUES
(85, 'andou', 'f7a04550b5a6936d99605993b3019e774da21304', '2019-08-11 11:36:41', '0.png', 0, NULL),
(88, 'aerga', '4e77fd001c67fad09d7a49003cd524ba38317843', '2019-08-23 22:41:51', '0.png', 0, NULL),
(89, 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', '2019-08-24 08:06:41', '0.png', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `replay_posts`
--
ALTER TABLE `replay_posts`
  ADD PRIMARY KEY (`reply_co_id`);

--
-- Indexes for table `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`tweets_id`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `replay_posts`
--
ALTER TABLE `replay_posts`
  MODIFY `reply_co_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tweets`
--
ALTER TABLE `tweets`
  MODIFY `tweets_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `userinfo`
--
ALTER TABLE `userinfo`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;
