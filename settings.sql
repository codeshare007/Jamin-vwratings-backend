-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 29, 2022 at 04:53 PM
-- Server version: 8.0.27
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vwratings-backend`
--

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `description`) VALUES
(1, 'ads_hits', '4', 'Amount of hits to see promo page'),
(2, 'announcement_html', '<h2 style=\"text-align: center\">AND THE WINNER IS...... ID 1691</h2>\n<h6 style=\"text-align: center\">Since names don\'t match up to anything, we had to draw ID numbers.  You can find your ID in your profile. You may need to refresh your profile page to see it, due to caching.</h6>\n<h5 style=\"text-align: center\">If your ID# is 1691 then message us with the 100gems worth of goodies you would like and the avi name (exactly) that you want it to go to... you or a friend.  We discreetly send the items. </h5>', 'Content of announcement'),
(3, 'announcement_enabled', '0', 'Announcement is enabled'),
(4, 'announcement_timeout', '10', 'Announcement period in seconds'),
(5, 'timer_start', '2022-07-29 11:48:09', 'Timer start datetime'),
(6, 'timer_period_minutes', '2', 'Timer period in minutes');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
