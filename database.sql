-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: August 02, 2021 at 07:48 PM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moviedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

DROP TABLE IF EXISTS `movies`;
CREATE TABLE IF NOT EXISTS `movies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imdb` varchar(100) NOT NULL,
  `trailer` varchar(100) NOT NULL,
  `youtube` varchar(100) NOT NULL,
  `amazon` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `imdb`, `trailer`, `youtube`, `amazon`) VALUES
(20, 'tt0407887', 'SGWvwjZ0eDc', 'O2JZdU3QFuU', 'B0723HHYCY'),
(19, 'tt0206634', '2VT2apoX90o', 'u6PgCr3ks7U', 'B00LHZLY8Y'),
(18, 'tt0120737', 'V75dMMIW2B4', 'o9ypA0QEYjc', 'B00QH7T20Y'),
(17, 'tt0468569', 'EXeTwQWrcwY', 'TQfcgaNdBCA', 'B00I9M41AG'),
(21, 'tt0371746', '8hYlB38asDY', 'VAHpUp0JrDM', 'B01M71YZHC'),
(22, 'tt0499549', '5PSNL1qE6VY', 'teyhlo_cDGo', 'B00HI3LXLW'),
(23, 'tt0126029', 'W37DlG1i61s', '_xOdEnKcmQ4', 'B079HR5QJV'),
(24, 'tt0241527', 'VyHV0BRtdxo', 'ro3fGYkgvKo', 'B00FFMG4VM'),
(25, 'tt1375666', 'YoHD9XEInc0', 'zTKvq1jp6Is', 'B00G3ED5V8'),
(26, 'tt0848228', 'eOrNdBpGMv8', 'pyeC8Fg1j9o', 'B07PF12M64'),
(27, 'tt0816692', 'zSWdZVtXT7E', '_h7oKEinDkk', 'B00UI9XSRA'),
(28, 'tt0993846', 'iszwuX1AK6A', 'Bbn43IhaiqQ', 'B00JXG57QE'),
(29, 'tt1825683', 'xjDjIWPwcPU', 'Dd_TqLTGxpM', 'B07NDJ4TVG'),
(30, 'tt5463162', 'D86RtevtfrA', 'GsmPaN8HYpQ', 'B07CYZY5GS'),
(31, 'tt2024544', 'z02Ie8wKKRg', 'POuXqZxCZyk', 'B00J9G65K0'),
(32, 'tt7668870', '3Ro9ebQxEOY', 'aKuBqxZUuY8', 'B07GZ499CP'),
(33, 'tt1853728', 'eUdM9vrCbow', 'ATorWWxru9U', 'B00FZN6YSE'),
(34, 'tt2106476', 'ieLIOBkMgAQ', 'jzZ7Ys9RQxQ', 'B07GBH7WJS'),
(35, 'tt3315342', 'Div0iP65aZo', 'yrNfQx5Plk8', 'B06X96QF44'),
(36, 'tt6966692', 'QkZxoko_HC0', '#', 'B07N6HWK39'),
(37, 'tt3783958', '0pdqf4P9MB8', 'E8tfq2fKFvY', 'B01MR4SPMB'),
(38, 'tt1517451', 'nSbzyEJ8X9E', 'dQAiCCihe4s', 'B07Q5B1X6P');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL DEFAULT '',
  `password` varchar(200) NOT NULL DEFAULT '',
  `email` varchar(80) NOT NULL DEFAULT '',
  `class` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `genres` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `class`, `genres`) VALUES
(1, 'admin', '$2y$10$syvwvpolgdjqsjnpgbacoePvXl0dKfRDOejkBJZSHmZJmtuOlI2RG', 'admin@localhost.com', 1, 'action,adventure'),
(2, 'test', '$2y$10$syvwvpolgdjqsjnpgbacoe8x/gUF4GfpdwKHhONrMIoocoKqzWk92', 'test@localhost.com', 0, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
