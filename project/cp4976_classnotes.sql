-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 17, 2020 at 06:39 AM
-- Server version: 10.3.24-MariaDB-log
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cp4976_classnotes`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `classid` int(11) NOT NULL,
  `classname` varchar(100) NOT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `year` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `body` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`classid`, `classname`, `semester`, `year`, `userid`, `level`, `body`) VALUES
(3, 'How to make a class', NULL, 0, 3, 0, '<p><em><strong>This Class is Easy!</strong></em></p>'),
(5, 'Spring Cleaning 101', 'Spring', 2222, 3, 0, '<p>In this class we learn about cleaning out your home</p>'),
(6, 'Testing Tests', 'Fall', 2020, 1, 0, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque vulputate dapibus nulla vitae imperdiet. Donec maximus augue risus, tincidunt lacinia lorem sollicitudin sit amet. Donec non auctor justo. Integer et dapibus dolor. Vivamus nisi magna, ultrices id justo venenatis, congue sollicitudin turpis. Curabitur hendrerit maximus neque a rutrum. Quisque faucibus lorem eget eleifend accumsan. Nunc tristique nisl non iaculis ullamcorper. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nunc vel ultrices massa.</p>\r\n<p>&nbsp;</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque vulputate dapibus nulla vitae imperdiet. Donec maximus augue risus, tincidunt lacinia lorem sollicitudin sit amet. Donec non auctor justo. Integer et dapibus dolor. Vivamus nisi magna, ultrices id justo venenatis, congue sollicitudin turpis. Curabitur hendrerit maximus neque a rutrum. Quisque faucibus lorem eget eleifend accumsan. Nunc tristique nisl non iaculis ulla'),
(7, 'Intro to Computers, Internet, and the Web', 'Spring', 0, 1, 0, '<p><strong>Taught By Dr. Daou</strong></p>\r\n<p>This class introduces computer components, IO, and the basics of the web and internet</p>'),
(11, 'Cat\'s Hideout 3', 'Fall', -142, 4, 0, '<p>Cats are fine. &trade;</p>\r\n<p>Dogs are better. &Acirc;</p>\r\n<p>Here we find out why...</p>'),
(13, 'The comedy of timing', 'NULL', 2020, 1, 0, '<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late!</p>\r\n<p>&nbsp;</p>\r\n<p>Don\'t be late!</p>\r\n<p>Don\'t be late'),
(14, 'Reality and VR', 'Fall', 2222, 1, 0, '<p>What even <em>is</em> <strong>real</strong>, man?</p>'),
(16, 'PHP', 'NULL', 2020, 1, 0, '<p>This is PHP</p>'),
(17, 'HTML', 'NULL', 2020, 1, 0, '<p>HTML</p>'),
(18, 'Class of 2008', 'NULL', 2008, 3, 0, '<p>What up?</p>'),
(19, 'PHP', 'Summer', 2020, 1, 0, '<p>Taught by Gregory</p>');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentid` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `body` varchar(1000) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `userid` int(11) NOT NULL,
  `filepathid` int(11) DEFAULT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commentid`, `articleid`, `body`, `date`, `userid`, `filepathid`, `level`) VALUES
(4, 6, 'Test Comment', '2020-07-29', 1, NULL, 0),
(5, 6, 'Test Comment', '2020-07-30', 1, NULL, 0),
(6, 5, 'Clean your shoes!', '2020-08-01', 1, NULL, 0),
(7, 6, 'Test2', '2020-08-01', 1, NULL, 0),
(8, 6, 'Test3', '2020-08-01', 3, NULL, 0),
(9, 6, 'Test3', '2020-08-01', 3, NULL, 0),
(10, 6, 'New Comment', '2020-08-03', 1, NULL, 0),
(12, 6, ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque vulputate dapibus nulla vitae imperdiet. Donec maximus augue risus, tincidunt lacinia lorem sollicitudin sit amet. Donec non auctor justo. Integer et dapibus dolor. Vivamus nisi magna, ultrices id justo venenatis, congue sollicitudin turpis. Curabitur hendrerit maximus neque a rutrum. Quisque faucibus lorem eget eleifend accumsan. Nunc tristique nisl non iaculis ullamcorper. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nunc vel ultrices massa.\r\n\r\nDuis luctus accumsan porta. Donec eget nisi nunc. Duis sit amet nibh ac nunc vulputate volutpat sed rhoncus eros. Integer et euismod turpis, non placerat dui. Ut ac blandit lacus, et accumsan sem. Nullam semper sapien ut ligula sodales convallis a at ex. Phasellus aliquet efficitur rhoncus. Nulla facilisi. Nunc a justo sed ante tempus tempus. Etiam accumsan velit nisl, at accumsan tellus imperdiet suscipit. Fusce mauris leo, imperdiet eu tincidunt at, moles', '2020-08-03', 1, NULL, 0),
(13, 6, '        <p><a href=\"/\">Back to Home</a></p>', '2020-08-03', 1, NULL, 0),
(14, 7, 'This was the class where I met Norman', '2020-08-04', 1, NULL, 0),
(15, 7, 'Test Comments', '2020-08-05', 4, NULL, 0),
(16, 10, 'When it\'s raining dogs and cats, it sounds like a good time but you\'re just left sad and moist.', '2020-08-05', 4, NULL, 0),
(17, 11, 'When it\'s raining dogs and cats, it sounds like a good time but instead you\'re just left sad and wet.', '2020-08-05', 4, NULL, 0),
(18, 17, 'Looking good ', '2020-08-09', 6, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `filepaths`
--

CREATE TABLE `filepaths` (
  `filepathid` int(11) NOT NULL,
  `filepath` varchar(100) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `joinedclasses`
--

CREATE TABLE `joinedclasses` (
  `userid` int(11) NOT NULL,
  `classid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `lessonid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `body` varchar(10000) NOT NULL,
  `classid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `filepathid` int(11) DEFAULT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`lessonid`, `title`, `body`, `classid`, `userid`, `filepathid`, `date`, `level`) VALUES
(1, 'Test Lesson', '<p>Learn this fool!</p>', 6, 3, NULL, '2020-08-04', 0),
(3, 'True Cats', '<p>But wait it\'s a bait because this is actually about DOGS</p>', 11, 4, NULL, '2020-08-05', 0),
(6, 'who', '<p>what?</p>', 14, 1, NULL, '2020-08-06', 0),
(7, 'where', '<p>when?</p>', 14, 1, NULL, '2020-08-06', 0),
(9, 'how', '<p><em><strong>Who knows?</strong></em></p>', 14, 1, NULL, '2020-08-06', 0),
(11, '\"H\"', '<p>Stands for Hyerlink</p>', 17, 1, NULL, '2020-08-08', 0),
(12, '\"T\"', '<p>Stands for Text</p>', 17, 1, NULL, '2020-08-08', 0),
(13, '\"M\"', '<p>Stands for Markup</p>', 17, 1, NULL, '2020-08-08', 0),
(14, '\"L\"', '<p>Stands for Language</p>', 17, 1, NULL, '2020-08-08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `moderators`
--

CREATE TABLE `moderators` (
  `userid` int(11) NOT NULL,
  `classid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` varchar(16) NOT NULL,
  `bio` varchar(1000) DEFAULT NULL,
  `filepath` int(11) DEFAULT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `email`, `password`, `bio`, `filepath`, `level`) VALUES
(1, 'henz90', 'henz90@hotmail.com', 'Password2', '<p>Live by the code, die by the code</p>', NULL, 0),
(3, 'henry', 'henz90@gmail.com', 'Password1', 'Who? What? Where? When? How?', NULL, 0),
(4, 'Cats', 'Cats@Cats.cats', 'Cats123', NULL, NULL, 0),
(6, 'gailzakay', 'gsilverstein@sympatico.ca', 'Password1', NULL, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`classid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `filepathid` (`filepathid`);

--
-- Indexes for table `filepaths`
--
ALTER TABLE `filepaths`
  ADD PRIMARY KEY (`filepathid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `joinedclasses`
--
ALTER TABLE `joinedclasses`
  ADD PRIMARY KEY (`userid`,`classid`),
  ADD KEY `classid` (`classid`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lessonid`),
  ADD KEY `classid` (`classid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `filepathid` (`filepathid`);

--
-- Indexes for table `moderators`
--
ALTER TABLE `moderators`
  ADD PRIMARY KEY (`userid`,`classid`),
  ADD KEY `classid` (`classid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `name` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `classid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `filepaths`
--
ALTER TABLE `filepaths`
  MODIFY `filepathid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `lessonid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`filepathid`) REFERENCES `filepaths` (`filepathid`);

--
-- Constraints for table `filepaths`
--
ALTER TABLE `filepaths`
  ADD CONSTRAINT `filepaths_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `joinedclasses`
--
ALTER TABLE `joinedclasses`
  ADD CONSTRAINT `joinedclasses_ibfk_1` FOREIGN KEY (`classid`) REFERENCES `classes` (`classid`),
  ADD CONSTRAINT `joinedclasses_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`classid`) REFERENCES `classes` (`classid`),
  ADD CONSTRAINT `lessons_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `lessons_ibfk_3` FOREIGN KEY (`filepathid`) REFERENCES `filepaths` (`filepathid`);

--
-- Constraints for table `moderators`
--
ALTER TABLE `moderators`
  ADD CONSTRAINT `moderators_ibfk_1` FOREIGN KEY (`classid`) REFERENCES `classes` (`classid`),
  ADD CONSTRAINT `moderators_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
