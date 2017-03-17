-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 17, 2017 at 11:31 PM
-- Server version: 5.5.42
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `wechat`
--

-- --------------------------------------------------------

--
-- Table structure for table `power`
--

CREATE TABLE `power` (
  `id` int(10) NOT NULL,
  `title` varchar(200) DEFAULT NULL COMMENT '权限说明',
  `code` varchar(20) DEFAULT NULL COMMENT '权限编码'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='权限表';

--
-- Dumping data for table `power`
--

INSERT INTO `power` (`id`, `title`, `code`) VALUES
(1, '登陆', 'login'),
(2, '新增公众号', 'wechat'),
(3, '修改密码', 'passwd');

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE `usergroup` (
  `groupid` int(11) NOT NULL COMMENT '会员组id',
  `grouptitle` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`groupid`, `grouptitle`) VALUES
(1, '超级管理组'),
(2, '业务组'),
(3, '信息化组');

-- --------------------------------------------------------

--
-- Table structure for table `usergroupfield`
--

CREATE TABLE `usergroupfield` (
  `id` int(11) NOT NULL,
  `gid` int(10) DEFAULT NULL COMMENT '父组id',
  `groupid` int(10) DEFAULT NULL COMMENT '组id',
  `power` text COMMENT '权限代码'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户组权限表';

--
-- Dumping data for table `usergroupfield`
--

INSERT INTO `usergroupfield` (`id`, `gid`, `groupid`, `power`) VALUES
(1, 0, 1, '1,3'),
(2, 1, 2, NULL),
(3, 2, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) DEFAULT NULL COMMENT '用户名',
  `passwd` varchar(200) DEFAULT NULL COMMENT '密码',
  `realname` varchar(20) DEFAULT NULL COMMENT '昵称',
  `password_salt` varchar(33) DEFAULT NULL,
  `role` int(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户认证表';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `passwd`, `realname`, `password_salt`, `role`) VALUES
(1, 'yanchao', 'd90d573d745a45c9ebd605732004cb3f', 'yan', 'ftoul.com', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `power`
--
ALTER TABLE `power`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usergroup`
--
ALTER TABLE `usergroup`
  ADD PRIMARY KEY (`groupid`);

--
-- Indexes for table `usergroupfield`
--
ALTER TABLE `usergroupfield`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `power`
--
ALTER TABLE `power`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `usergroup`
--
ALTER TABLE `usergroup`
  MODIFY `groupid` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员组id',AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `usergroupfield`
--
ALTER TABLE `usergroupfield`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;