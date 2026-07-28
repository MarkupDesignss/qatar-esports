-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 28, 2026 at 02:54 AM
-- Server version: 11.4.12-MariaDB-cll-lve-log
-- PHP Version: 8.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `markcawm_qataresports`
--

-- --------------------------------------------------------

--
-- Table structure for table `abouts`
--

CREATE TABLE `abouts` (
  `id` int(11) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `badge` varchar(100) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `abouts`
--

INSERT INTO `abouts` (`id`, `heading`, `description`, `badge`, `image`, `created_at`, `updated_at`) VALUES
(1, 'About QEC Esports', '<p><strong>Qatar Esports Community (QEC)</strong> is a premier organization powered by Gama Esports and Darkcube Esports, dedicated to hosting world-class events, scrims, and tournaments. With a skilled team driving engaging content and community growth, we set the standard for esports in Qatar and aim to expand into new gaming genres, shaping the future of competitive gaming in the region.</p><p>&nbsp;</p><p><strong>Darkcube Esports</strong>, founded by YouTuber Jack Albushi, is a top PUBG Mobile organizer in the Middle East with 27 successful tournaments. Based in Qatar and the GCC, we’re now expanding into Valorant to further elevate the regional esports scene.</p><p>&nbsp;</p><p><strong>Gama Esports</strong>, founded by content creator Smartgama, is a Qatar-based organization with over 40 members, including players and content creators. Our mission is to become a leading esports team and event organizer in the Middle East.</p>', 'Since 2015', 'about/FLHAfoiLi0c07n0bhVOhnfZ2dnNA3vTkTpZAGbm6.png', '2026-06-29 10:20:00', '2026-06-29 20:58:27');

-- --------------------------------------------------------

--
-- Table structure for table `about_sections`
--

CREATE TABLE `about_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('mission','vision','goals') NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '1=Active, 0=Inactive',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `about_sections`
--

INSERT INTO `about_sections` (`id`, `type`, `title`, `description`, `video_url`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'mission', 'Mission', '<p>QEC Drives The Growth Of Esports In Qatar And The GCC By Delivering Premier Tournaments, Connecting With Local Communities, And Empowering Players To Achieve Their Full Potential.</p><p>&nbsp;</p><p>QEC Drives The Growth Of Esports In Qatar And The GCC By Delivering Premier Tournaments, Connecting With Local Communities, And Empowering Players To Achieve Their Full Potential.</p>', 'https://www.youtube.com/shorts/qOdOCMqV06A', 'about/WAs2FMftmbphzOvxAEFiZZvvb4Z9a9RcK3hw6vQS.jpg', 1, '2026-01-15 15:36:29', '2026-06-03 20:37:00'),
(4, 'vision', 'Vision', 'QEC Drives The Growth Of Esports In Qatar And The GCC By Delivering Premier Tournaments, Connecting With Local Communities, And Empowering Players To Achieve Their Full Potential.', 'https://www.youtube.com/shorts/qOdOCMqV06A', 'about/cGRYK9PhT0AzIs6bvbR744RUj6CNa2JcUgSEWUaD.jpg', 1, '2026-02-12 18:38:09', '2026-05-11 22:00:31'),
(3, 'goals', 'Goals', 'Build Sustainable Esports Platforms, Support Local Talent, And Host World-Class Competitive Events.', 'https://www.youtube.com/shorts/qOdOCMqV06A', 'about/qLzEoECvABCrykzaMccHrZUsZNv3gsLDxtWxAouK.jpg', 1, '2026-01-15 15:37:56', '2026-05-11 22:00:41');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `role` enum('admin','moderator') NOT NULL DEFAULT 'moderator',
  `password` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `role`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', 'admin', '$2y$12$c3qbasaEpj4uTTg0e2fkeeehpPx0uhJrGEYJO8TR.bJ4rMMZbX0pO', NULL, '2026-07-01 20:48:22'),
(6, 'Moderator', 'moderator@gmail.com', 'moderator', '$2y$12$AnhIFVNW0WGAyagizLn4me0iVLP7ZyOgAvY8Q72YI9G34qIEIEJ06', '2026-07-01 19:53:17', '2026-07-01 21:46:00'),
(7, 'testing', 'jabor5151@gmail.com', 'moderator', '$2y$12$oDgmvvvZ/5N.lPpMLgxDr.4JLHHjoEcpdSbPZow515SrF3Sj/SL.m', '2026-07-03 20:27:20', '2026-07-03 20:27:20'),
(8, 'QA', 'qatest04md@gmail.com', 'moderator', '$2y$12$O9zbSiM.V./rar5wgMa.5e5esE6tgelT9WRy8NEgqCtIuHt/4FEAS', '2026-07-15 21:55:51', '2026-07-15 21:55:51'),
(9, 'Shekhar Saini', 'shekhar@yopmail.com', 'moderator', '$2y$12$xCGrSh8jBqc6SgwYuUEW6eNdiD/tJ2rvnDfnxx/Nu4Nn5QAMX/Uf2', '2026-07-23 16:37:42', '2026-07-23 16:37:42');

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_otps`
--

CREATE TABLE `admin_password_otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) NOT NULL,
  `otp` varchar(191) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_password_otps`
--

INSERT INTO `admin_password_otps` (`id`, `email`, `otp`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'admin@gmail.com', '265671', '2026-07-20 23:14:26', NULL, NULL),
(2, 'abhay.chauhan.markup@gmail.com', '163742', '2026-07-02 19:50:37', NULL, NULL),
(3, 'qatest02md@gmail.com', '551140', '2026-07-20 18:51:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `heading` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `heading`, `description`, `image`, `created_at`, `updated_at`) VALUES
(3, 'Building the Global Frontier of Gaming', 'We are redefining the competitive landscape through elite tournaments, next-gen talent scouting, and industry-leading broadcast production', 'banners/JzLtwmc0Q0VZkJtirjrPF2QCi3DcMJal8KeDAv9g.mp4', '2026-01-06 12:25:55', '2026-07-08 20:53:23');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `challenges`
--

CREATE TABLE `challenges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `welcome_heading` varchar(255) DEFAULT NULL,
  `heading` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `challenges`
--

INSERT INTO `challenges` (`id`, `welcome_heading`, `heading`, `content`, `thumbnail`, `image`, `video_url`, `created_at`, `updated_at`) VALUES
(2, 'Welcome To Qatar Esports Community', 'Are You Ready For Our Next Challenge?', '<p>The stage is set, the servers are live, and the next chapter of Qatari excellence is about to be written. We are calling on the boldest competitors and the most visionary creators to step into the arena and prove their mettle on a global stage. At Qatar Esports, we don\'t just host games; we build legacies. Our mission is to transform the regional gaming landscape into a world-class powerhouse. By bridging the gap between raw local talent and international standards, we ensure that every player has the platform, the production, and the path to professional greatness. Whether you are a veteran of the keyboard or a rising star in the making, your journey starts here.&nbsp;</p><p>&nbsp;</p><p>Join a community fueled by passion and powered by innovation. The future of competitive play isn’t just coming—it’s happening in Doha.</p>', 'challenges/thumbnails/syO38fg1soRL9IJNNAWOawQhcz99v6ArX2g0Z8Zk.png', 'challenges/wBzikpUFrTUSdfzf2VwuiclU55EBrQS4jN5l9fsI.png', 'https://youtu.be/ljMsur43eRU', '2026-01-14 18:07:27', '2026-07-18 04:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `contact_requests`
--

CREATE TABLE `contact_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `subject` varchar(191) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','in_progress','resolved') NOT NULL DEFAULT 'new',
  `resolution` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_requests`
--

INSERT INTO `contact_requests` (`id`, `full_name`, `email`, `subject`, `message`, `status`, `resolution`, `created_at`, `updated_at`) VALUES
(23, 'Shekhar Saini', 'shekhar.saini36@gmail.com', 'Support Required', 'Please help with my account', 'new', NULL, '2026-05-26 21:25:16', '2026-05-26 21:25:16'),
(39, 'QA', 'qatest02md@gmail.com', 'Test', 'We are always ready to help you with tournaments, partnerships, technical support, payments and collaborations.', 'new', NULL, '2026-07-17 16:58:33', '2026-07-17 16:58:33');

-- --------------------------------------------------------

--
-- Table structure for table `contact_settings`
--

CREATE TABLE `contact_settings` (
  `id` int(11) NOT NULL,
  `partnership_title` varchar(255) DEFAULT NULL,
  `get_in_touch_title` varchar(555) DEFAULT NULL,
  `get_in_touch_desc` text DEFAULT NULL,
  `partnership_description` text DEFAULT NULL,
  `partnership_email` varchar(255) DEFAULT NULL,
  `sales_title` varchar(255) DEFAULT NULL,
  `sales_description` text DEFAULT NULL,
  `sales_email` varchar(255) DEFAULT NULL,
  `technical_title` varchar(255) DEFAULT NULL,
  `technical_description` text DEFAULT NULL,
  `technical_email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact_settings`
--

INSERT INTO `contact_settings` (`id`, `partnership_title`, `get_in_touch_title`, `get_in_touch_desc`, `partnership_description`, `partnership_email`, `sales_title`, `sales_description`, `sales_email`, `technical_title`, `technical_description`, `technical_email`, `created_at`, `updated_at`) VALUES
(1, 'Partnership', 'Get In Touch', 'We are always ready to help you with tournaments, partnerships, technical support, payments and collaborations.', 'Cooperation and partnership opportunities for brands and esports communities.1', 'jackalblushi1@qecgg.com', 'Sales Department', 'Consulting, reports and premium esports data solutions.1', 'smartgama1@qecgg.com', 'Technical Support', 'Help related 1to payments, tournaments and technical', 'info1@qecgg.com', '2026-06-29 13:38:29', '2026-07-17 16:57:41');

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_images`
--

CREATE TABLE `dashboard_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dashboard_images`
--

INSERT INTO `dashboard_images` (`id`, `image1`, `image2`, `created_at`, `updated_at`) VALUES
(1, 'dashboard_images/r2IfbOPh9mJtKs2PO4o1e9UKxOeegm7AHAKLoKgQ.png', 'dashboard_images/3hLNXmeB6YzhqIhP9TNbZRQufC0LDn2bRoAkeu0B.png', '2026-02-13 12:54:46', '2026-02-13 12:54:46');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `featured_events`
--

CREATE TABLE `featured_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_second` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `featured_events`
--

INSERT INTO `featured_events` (`id`, `title`, `description`, `image`, `image_second`, `status`, `created_at`, `updated_at`) VALUES
(2, 'FEATURED EVENTS', 'Qatar Esports Community (QEC) is a premier organization powered by Gama Esports and Darkcube Esports, dedicated to hosting world-class events, scrims, and tournaments. With a skilled team driving engaging content and community growth, we set the standard for esports in Qatar and aim to expand into new gaming genres, shaping the future of competitive gaming in the region.\r\n\r\nDarkcube Esports, founded by YouTuber Jack Albushi, is a top PUBG Mobile organizer in the Middle East with 27 successful tournaments. Based in Qatar and the GCC, we’re now expanding into Valorant to further elevate the regional esports scene.', 'events/zgWgmlrwJe8eAAq0dfjxnsyX0dwBmpVdcHkfC6Ve.png', 'events/mcJ6GEMF1kUYaLOLNHAlPWNFAJJNRO0QR1sMosWX.png', 1, '2026-01-14 17:41:47', '2026-01-14 17:41:47'),
(3, 'Holi Events', 'Qatar Esports Community (QEC) is a premier organization powered by Gama Esports and Darkcube Esports, dedicated to hosting world-class events, scrims, and tournaments. With a skilled team driving engaging content and community growth, we set the standard for esports in Qatar and aim to expand into new gaming genres, shaping the future of competitive gaming in the region.\r\n\r\nDarkcube Esports, founded by YouTuber Jack Albushi, is a top PUBG Mobile organizer in the Middle East with 27 successful tournaments. Based in Qatar and the GCC, we’re now expanding into Valorant to further elevate the regional esports scene.', 'events/vbAVzJ8HdLnL7Wc3nJn8IEp09ptrsS2x4p7lZfTF.jpg', 'events/mgGghsTh9H0AQ9AI3COZXPGFWQTqMACsU3B5XDuM.jpg', 1, '2026-01-19 10:42:13', '2026-01-19 18:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `footer_settings`
--

CREATE TABLE `footer_settings` (
  `id` int(11) NOT NULL,
  `tagline` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `copyright_text` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `discord_url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `whatsapp_number` varchar(50) DEFAULT NULL,
  `contact_address` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `footer_settings`
--

INSERT INTO `footer_settings` (`id`, `tagline`, `description`, `copyright_text`, `youtube_url`, `instagram_url`, `twitter_url`, `discord_url`, `email`, `contact_phone`, `whatsapp_number`, `contact_address`, `created_at`, `updated_at`) VALUES
(1, 'The Future Of Competitive Gaming. Forging Legends, One Tournament At A Time.', '<p>We Aim To Cultivate Excellence In Esports By Supporting Athletes, Teams, And Organizations, Providing The Resources And Infrastructure Needed To Achieve Their Fullest Potential.</p>', '© 2025 Qatar Esports Community (QECGG) All Rights Reserved.', 'https://www.youtube.com/@qecgg', 'https://www.instagram.com/qecgg_/', 'https://x.com/qecgg_', 'https://www.discord.com/@qecgg', 'info@qecgg.com', '608448997', '608448997', 'Doha, Qatar', '2026-06-29 11:36:28', '2026-07-13 17:31:55');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `logo` varchar(191) DEFAULT NULL,
  `banner` varchar(191) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = Inactive, 1 = Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `slug`, `logo`, `banner`, `platform`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Valorant', 'valorant', 'games/WlLK108I6t8btdRjc87LP4WM1JGe8rtXvZcpsaTA.jpg', NULL, 'PC', 1, NULL, '2026-01-20 14:35:41'),
(2, 'Free Fire', 'free-fire', 'games/YHKcg6nGnbe9pYJkSsXVmRqVqvn30ti9iiyrvmdI.jpg', 'games/XVtXnQGUinq9uilAU62IBlwxr6TAlAzntwp3CTFF.png', 'Mobile', 1, '2025-12-25 09:44:42', '2026-01-20 14:35:21'),
(3, 'Counter-Strike 2', 'counter-strike-2', 'games/jOciMHIcrllcvnWNT2Sl1Haqas1uxdxQ1qmrnzuN.jpg', 'games/oZdmjdyYVU55sGmarR3iYVXTiPeGUAYX32pdTOEn.png', 'PC', 1, '2026-01-06 12:52:52', '2026-01-20 18:03:50'),
(4, 'Racing', 'racing', 'games/FHN71pFV4uGHPhxb95pmppcAIFKuVYWspx0yyDk4.webp', 'games/grIkAHNiDXGuWUNPl0HQQc0NE2uSQPew0rZhhs44.webp', 'PC', 1, '2026-02-12 11:31:15', '2026-02-12 11:31:15'),
(5, 'Callofduty', 'callofduty', NULL, NULL, 'PC', 1, '2026-05-07 07:11:29', '2026-05-07 07:11:29'),
(6, 'Fortnite', 'fortnite', 'games/4DcFBA4yfiBzBRe3updkzrCL5iOfnbpCptivJHeo.png', 'games/uplNWLgbtSYhBnRj5ep7z8AzSLxfMkrITeHOSCox.png', 'PC & Console', 1, '2026-05-29 02:16:38', '2026-07-21 23:13:09');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"6f94077c-35e3-456b-8634-e792c0864d0b\",\"displayName\":\"App\\\\Mail\\\\ContactUsAdminMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\ContactUsAdminMail\\\":2:{s:4:\\\"data\\\";a:4:{s:9:\\\"full_name\\\";s:13:\\\"Shekhar Saini\\\";s:5:\\\"email\\\";s:17:\\\"shekhar@gmail.com\\\";s:7:\\\"subject\\\";s:16:\\\"Support Required\\\";s:7:\\\"message\\\";s:27:\\\"Please help with my account\\\";}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1767594200,\"delay\":null}', 0, NULL, 1767594200, 1767594200),
(2, 'default', '{\"uuid\":\"c9d3e384-12b1-4f41-88a1-3c402d22513b\",\"displayName\":\"App\\\\Mail\\\\ContactUsAdminMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\ContactUsAdminMail\\\":2:{s:4:\\\"data\\\";a:4:{s:9:\\\"full_name\\\";s:5:\\\"Abhay\\\";s:5:\\\"email\\\";s:15:\\\"abhay@gmail.com\\\";s:7:\\\"subject\\\";s:11:\\\"I want info\\\";s:7:\\\"message\\\";s:11:\\\"I want info\\\";}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1767599191,\"delay\":null}', 0, NULL, 1767599191, 1767599191),
(3, 'default', '{\"uuid\":\"6e929511-1b72-48b4-bd41-73f137187696\",\"displayName\":\"App\\\\Mail\\\\ContactUsAdminMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\ContactUsAdminMail\\\":2:{s:4:\\\"data\\\";a:4:{s:9:\\\"full_name\\\";s:13:\\\"Shekhar Saini\\\";s:5:\\\"email\\\";s:25:\\\"shekhar.saini36@gmail.com\\\";s:7:\\\"subject\\\";s:16:\\\"Support Required\\\";s:7:\\\"message\\\";s:27:\\\"Please help with my account\\\";}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1767617579,\"delay\":null}', 0, NULL, 1767617579, 1767617579),
(4, 'default', '{\"uuid\":\"6fe7051d-68e2-44d6-b324-f213188ee958\",\"displayName\":\"App\\\\Mail\\\\ContactUsAdminMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\ContactUsAdminMail\\\":2:{s:4:\\\"data\\\";a:4:{s:9:\\\"full_name\\\";s:13:\\\"Shekhar Saini\\\";s:5:\\\"email\\\";s:25:\\\"shekhar.saini36@gmail.com\\\";s:7:\\\"subject\\\";s:16:\\\"Support Required\\\";s:7:\\\"message\\\";s:27:\\\"Please help with my account\\\";}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1767617616,\"delay\":null}', 0, NULL, 1767617616, 1767617616),
(5, 'default', '{\"uuid\":\"92e1a2f1-6e9d-44ca-b7cb-c293b896f317\",\"displayName\":\"App\\\\Mail\\\\ContactUsAdminMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\ContactUsAdminMail\\\":2:{s:4:\\\"data\\\";a:4:{s:9:\\\"full_name\\\";s:2:\\\"ff\\\";s:5:\\\"email\\\";s:16:\\\"dsre@dwgmail.com\\\";s:7:\\\"subject\\\";s:2:\\\"ce\\\";s:7:\\\"message\\\";s:17:\\\"ewcdcdsccdscdscds\\\";}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1768371931,\"delay\":null}', 0, NULL, 1768371931, 1768371931),
(6, 'default', '{\"uuid\":\"48779523-7194-476c-b9ec-c539029275d6\",\"displayName\":\"App\\\\Mail\\\\ContactUsAdminMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\ContactUsAdminMail\\\":2:{s:4:\\\"data\\\";a:4:{s:9:\\\"full_name\\\";s:5:\\\"Abhay\\\";s:5:\\\"email\\\";s:15:\\\"abhay@gmail.com\\\";s:7:\\\"subject\\\";s:11:\\\"I want info\\\";s:7:\\\"message\\\";s:27:\\\"rgrgregrgregegegfggtrtgtgtr\\\";}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769003243,\"delay\":null}', 0, NULL, 1769003243, 1769003243);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_streams`
--

CREATE TABLE `live_streams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tournament_id` bigint(20) UNSIGNED DEFAULT NULL,
  `game_id` bigint(20) UNSIGNED DEFAULT NULL,
  `platform` varchar(50) NOT NULL,
  `channel_name` varchar(191) NOT NULL,
  `language` varchar(191) DEFAULT NULL,
  `video_url` varchar(191) NOT NULL,
  `is_live` tinyint(1) NOT NULL DEFAULT 0,
  `viewer_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `started_at` timestamp NULL DEFAULT NULL,
  `last_synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `live_streams`
--

INSERT INTO `live_streams` (`id`, `tournament_id`, `game_id`, `platform`, `channel_name`, `language`, `video_url`, `is_live`, `viewer_count`, `started_at`, `last_synced_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'twitch', 'lpl_english', 'English', 'https://youtu.be/MVq8UJU-e_o', 1, 0, '2026-01-05 07:41:05', '2026-01-05 09:41:05', '2026-01-05 09:41:05', '2026-01-20 17:28:12'),
(2, 2, 2, 'twitch', 'valorantesports', 'English', 'https://youtu.be/Jdk3JNl4Yf4', 1, 0, '2026-01-05 08:41:05', '2026-01-05 09:41:05', '2026-01-05 09:41:05', '2026-01-20 17:28:55'),
(3, 3, 2, 'youtube', 'UCBY4N4fK0v4k6Z8', 'English', 'https://youtu.be/Jdk3JNl4Yf4', 1, 0, '2026-01-05 06:41:05', '2026-01-05 09:41:05', '2026-01-05 09:41:05', '2026-01-20 17:29:02'),
(4, 4, 1, 'twitch', 'lpl_chinese', 'Chinese', 'https://youtu.be/MVq8UJU-e_o', 1, 0, NULL, '2026-01-05 09:41:05', '2026-01-05 09:41:05', '2026-01-21 16:32:16'),
(5, 3, 4, 'youtube', 'smartgama', 'English and Arabic', 'https://youtu.be/boZk2bK4YLA', 0, 500, NULL, NULL, '2026-05-23 15:33:00', '2026-05-26 18:41:37'),
(6, 4, 3, 'twitch', 'lpl_chinese', 'English', 'https://www.youtube.com/shorts/vEK0luBS_uE', 1, 0, NULL, NULL, '2026-05-26 18:42:00', '2026-05-26 18:56:39'),
(7, 11, 6, 'YouTube', 'Smartgama', 'Arabic and English', 'https://youtu.be/N1bBjdqXmvs', 1, 0, NULL, NULL, '2026-05-29 02:28:22', '2026-05-29 02:28:47'),
(8, 13, 3, 'youtube', 'NA', 'English', 'https://youtu.be/N1bBjdqXmvs', 0, 0, NULL, NULL, '2026-06-05 15:51:04', '2026-06-05 15:54:26'),
(9, NULL, 2, 'twitch', 'lpl_english', 'English', 'https://www.twitch.tv/pupsker', 1, 0, NULL, NULL, '2026-07-01 17:53:45', '2026-07-01 18:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `logos`
--

CREATE TABLE `logos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `image` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logos`
--

INSERT INTO `logos` (`id`, `title`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Website Logo', 'logos/awJLFqb6XwpyePqGBOFS3TtXO1rPCQm2VsYXl3wm.png', '2026-01-05 04:56:32', '2026-01-06 13:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `maps`
--

CREATE TABLE `maps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `game_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maps`
--

INSERT INTO `maps` (`id`, `game_id`, `name`, `slug`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(7, 1, 'Map-1', 'map-1', NULL, 1, '2026-07-23 18:08:19', '2026-07-23 18:08:19');

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tournament_id` bigint(20) UNSIGNED NOT NULL,
  `team1_id` bigint(20) UNSIGNED DEFAULT NULL,
  `team2_id` bigint(20) UNSIGNED DEFAULT NULL,
  `winner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `round` enum('Round 1','Quarterfinal','Semifinal','Final') NOT NULL,
  `match_date` date DEFAULT NULL,
  `match_time` time DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `match_order` int(11) NOT NULL DEFAULT 1,
  `match_video` varchar(255) DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `best_of` varchar(255) DEFAULT '1',
  `played_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `team1_name` varchar(255) DEFAULT NULL,
  `team2_name` varchar(255) DEFAULT NULL,
  `winner_team_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `tournament_id`, `team1_id`, `team2_id`, `winner_id`, `round`, `match_date`, `match_time`, `banner`, `match_order`, `match_video`, `status`, `best_of`, `played_at`, `created_at`, `updated_at`, `team1_name`, `team2_name`, `winner_team_name`) VALUES
(44, 24, NULL, NULL, NULL, 'Final', '2026-07-20', '13:54:00', NULL, 1, NULL, 'pending', NULL, NULL, '2026-07-23 17:54:43', '2026-07-23 17:54:43', NULL, NULL, NULL),
(45, 24, 1, 2, NULL, 'Final', '2026-07-20', NULL, NULL, 2, NULL, 'pending', NULL, NULL, '2026-07-23 17:55:11', '2026-07-23 17:55:11', 'Kushank Rajput', 'Test 1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `match_highlights`
--

CREATE TABLE `match_highlights` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('all','match_highlights','press_release','media','blogs') NOT NULL DEFAULT 'all',
  `video_url` varchar(255) DEFAULT NULL,
  `video_title` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `match_highlights`
--

INSERT INTO `match_highlights` (`id`, `title`, `thumbnail`, `description`, `type`, `video_url`, `video_title`, `created_at`, `updated_at`) VALUES
(2, 'Valorant: The Art Of Execution - The Review', 'matches/thumbnail/HDvWzsRKI1BgR24FltviMfHX8jvj598vIRNnAbjp.jpg', 'A curated list of the most insane plays that left the audience stunned throughout the tournament.', 'media', NULL, NULL, '2026-01-14 17:51:16', '2026-01-19 18:50:01'),
(3, 'Gunsight-Seeking Hunting Simulator 2 - The Preview', 'matches/thumbnail/oBqYe3gKTgUoLkgSrMtdDWedZrD5lBRJoT2Mcrjw.jpg', 'A curated list of the most insane plays that left the audience stunned throughout the tournament.', 'match_highlights', NULL, NULL, '2026-01-14 17:52:39', '2026-01-19 18:49:47'),
(4, 'Replay #19', 'matches/thumbnail/pSoahnDOQjEh05gTW98rXdyhR4oMNkyRfW3OPbdk.jpg', 'A curated list of the most insane plays that left the audience stunned throughout the tournament.', 'blogs', NULL, NULL, '2026-01-14 17:52:57', '2026-01-19 18:49:31'),
(5, 'Valorant: The Art Of Execution - The Review', 'matches/thumbnail/JjAaQ60LFmd7ftxqp4FRDqT1CVcJQYzSPHiKTKRH.jpg', 'A curated list of the most insane plays that left the audience stunned throughout the tournament.', 'blogs', NULL, NULL, '2026-01-14 17:53:07', '2026-01-19 18:49:02'),
(7, 'Valorant: The Art Of Execution - The Review', 'matches/thumbnail/bUGZVihjfTCS8ok5JTS51HNM2KtWl80eABoamjSc.jpg', 'A curated list of the most insane plays that left the audience stunned throughout the tournament.', 'media', NULL, NULL, '2026-01-14 18:12:16', '2026-01-19 18:48:46'),
(8, 'Valorant: The Art Of Execution - The Review', 'matches/thumbnail/sQDqmG9qhlBRnWnLHVIFebF5TyXX72OcCQGTGZPf.jpg', 'An intense grand finals clash where strategy and execution defined the champions of the season.', 'match_highlights', 'https://www.youtube.com/shorts/vEK0luBS_uE', 'none', '2026-01-15 10:31:46', '2026-01-19 18:48:00'),
(9, 'Valorant: The Art Of Execution - The Review', 'matches/thumbnail/ZMqtkDJmZJlCXDxwbp63F3rnZutffGgXkC79BVW7.jpg', 'An intense grand finals clash where strategy and execution defined the champions of the season.', 'match_highlights', NULL, NULL, '2026-01-20 13:16:12', '2026-01-20 13:16:12'),
(10, 'Valorant: The Art Of Execution - The Review', 'matches/thumbnail/F8cZ9Ts3W2Lz8bp9HHZ18PmxPwaeVW7dry4z7qLI.jpg', 'An intense grand finals clash where strategy and execution defined the champions of the season.', 'match_highlights', NULL, NULL, '2026-01-20 13:16:44', '2026-01-20 13:16:44'),
(11, 'ABC', 'matches/thumbnail/v3DQpldNKHwcCUBmMNCcF0gx0V3Dac4gp9mO09DB.jpg', '<p>An intense grand finals clash where strategy and execution defined the champions of the season.</p><p>&nbsp;</p><p>An intense grand finals clash where strategy and execution defined the champions of the season.</p>', 'match_highlights', 'https://youtu.be/4Q4TuAzkz4k?si=MLD5REiYFxG1_H6u', NULL, '2026-01-20 13:16:59', '2026-07-15 10:39:46');

-- --------------------------------------------------------

--
-- Table structure for table `match_highlight_contents`
--

CREATE TABLE `match_highlight_contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `match_highlight_id` bigint(20) UNSIGNED NOT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `match_highlight_contents`
--

INSERT INTO `match_highlight_contents` (`id`, `match_highlight_id`, `heading`, `content`, `sort_order`, `created_at`, `updated_at`) VALUES
(3, 8, 'heading', 'mckjdsckjd', 0, '2026-01-19 18:48:00', '2026-01-19 18:48:00'),
(7, 9, NULL, 'Valorant: The Art Of Execution - The Review', 0, '2026-05-07 21:58:15', '2026-05-07 21:58:15'),
(5, 10, NULL, 'Valorant: The Art Of Execution - The Review', 0, '2026-01-20 13:16:44', '2026-01-20 13:16:44'),
(19, 12, 'heading', '<p>hjjhkljl</p>', 0, '2026-06-03 19:47:57', '2026-06-03 19:47:57');

-- --------------------------------------------------------

--
-- Table structure for table `match_highlight_images`
--

CREATE TABLE `match_highlight_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `match_highlight_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `match_highlight_images`
--

INSERT INTO `match_highlight_images` (`id`, `match_highlight_id`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 8, 'matches/gallery/S72lcyghTCxZQgvYo1juZLxBLAuS7D6jLNmyDHG3.png', 0, '2026-01-19 14:45:42', '2026-01-19 14:45:42'),
(2, 8, 'matches/gallery/6GkYKHvA9m3K40cTBQxe8LgMgb70YsDHUaudyULF.png', 1, '2026-01-19 14:45:42', '2026-01-19 14:45:42'),
(3, 8, 'matches/gallery/2LMDw9avhAwFlOvfIBgnsusASmXHZ1xqsDJlYYXg.png', 2, '2026-01-19 14:45:42', '2026-01-19 14:45:42'),
(4, 8, 'matches/gallery/fP8F1YbgEckmLMYSZiEyDQFmDV2GP7F3ZIPCSjiN.png', 3, '2026-01-19 14:45:42', '2026-01-19 14:45:42'),
(12, 9, 'matches/gallery/2TzBfeLZrBFjEs0dRiKBLjn6Rh9nt1JpGoCE7Awe.png', 0, '2026-05-07 21:58:15', '2026-05-07 21:58:15'),
(11, 11, 'matches/gallery/kLMiTcM7BPv8caIKDlecdY5tvUPeFxacoYkRrFvK.jpg', 0, '2026-01-20 13:16:59', '2026-01-20 13:16:59'),
(10, 10, 'matches/gallery/9JYAGohTqHmyvV2kUMeGTHL6zcXf6Dv5n28TOyU6.jpg', 0, '2026-01-20 13:16:44', '2026-01-20 13:16:44'),
(9, 9, 'matches/gallery/ei1aGGd4lsgOCmErhqXPpkBIK2w9JIgVFzOrBUrb.jpg', 0, '2026-01-20 13:16:12', '2026-01-20 13:16:12'),
(13, 11, 'matches/gallery/1oOYxqVg2v4yZwxttNZGwg35vBhdY5xynhMxt2zv.png', 0, '2026-05-29 02:48:53', '2026-05-29 02:48:53'),
(14, 12, 'matches/gallery/P7CB7tpBqbzz3l2afPy99gy0UZkN9woJOcdgmcBT.png', 0, '2026-06-03 19:46:36', '2026-06-03 19:46:36'),
(15, 12, 'matches/gallery/TrVDZhaw8z75gOUJqR3vRrDT36oLG0yN3J60W8Eq.png', 1, '2026-06-03 19:46:36', '2026-06-03 19:46:36'),
(16, 13, 'matches/gallery/wD2YPagHcnVnJ4axFe9GQ93q3Ev0rLlK1o4ZuYV5.png', 0, '2026-06-03 20:41:29', '2026-06-03 20:41:29'),
(17, 13, 'matches/gallery/sG8nXbsJ5YIWFcTOzxaqvf32nXctAqkkIFRhgkiZ.png', 1, '2026-06-03 20:41:29', '2026-06-03 20:41:29'),
(18, 14, 'matches/gallery/nIVPLuYnX0NZrCU8gY9qlzQXZFjEWW8ibr5iNrJE.png', 0, '2026-06-03 20:43:20', '2026-06-03 20:43:20'),
(19, 14, 'matches/gallery/tmipsJQYzwZX58VV0OH5kzHefmRGw3WZ9feo61ym.png', 1, '2026-06-03 20:43:20', '2026-06-03 20:43:20'),
(20, 15, 'matches/gallery/4Npjxk15qsxtJNt8OPcJZwF1jm4rsQ7kT1Yz23S7.png', 0, '2026-06-03 20:49:53', '2026-06-03 20:49:53'),
(21, 15, 'matches/gallery/uA6mgXmOw7JlM8NrgSSSFIcDrLJz2pnKIQJnbQ3S.png', 1, '2026-06-03 20:49:53', '2026-06-03 20:49:53');

-- --------------------------------------------------------

--
-- Table structure for table `match_maps`
--

CREATE TABLE `match_maps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `match_id` bigint(20) UNSIGNED NOT NULL,
  `map_id` bigint(20) UNSIGNED NOT NULL,
  `map_order` int(11) NOT NULL,
  `action` varchar(255) DEFAULT NULL,
  `team1_side` enum('attacker','defender') DEFAULT NULL,
  `team2_side` enum('attacker','defender') DEFAULT NULL,
  `winner_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `match_map_vetoes`
--

CREATE TABLE `match_map_vetoes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `match_id` bigint(20) UNSIGNED DEFAULT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `map_id` bigint(20) UNSIGNED NOT NULL,
  `action` enum('ban','pick') NOT NULL,
  `sequence_no` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tournament_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_12_10_122910_create_users_table', 1),
(4, '2025_12_10_131551_create_personal_access_tokens_table', 1),
(5, '2025_12_11_131108_create_admins_table', 1),
(6, '2025_12_15_103622_create_admin_password_otps_table', 1),
(7, '2025_12_22_113524_create_games_table', 2),
(9, '2025_12_22_113857_create_tournaments_table', 3),
(10, '2025_12_25_111759_create_tournament_registrations', 4);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tournament_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `like_count` int(11) DEFAULT 0,
  `bookmark_count` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `tournament_id`, `title`, `description`, `type_id`, `thumbnail`, `like_count`, `bookmark_count`, `status`, `created_at`, `updated_at`) VALUES
(2, 4, 'Cricket', '<p>Cricket</p>', 2, 'news/MDmp10ZgUyNbBxpKi7RCB0ih6tdDXuZMjkP2quCA.png', 0, 0, 1, '2026-01-15 14:17:12', '2026-07-20 21:00:58'),
(3, 1, 'first', '<p>first</p>', 3, 'news/XbrAZju6z4mhQw5fOtKxlN6Fc1IUSiBsZQjBKONa.png', 0, 0, 1, '2026-01-15 14:19:08', '2026-07-20 21:00:25'),
(4, 2, 'second', '<p>second</p>', 4, 'news/eYFhGZTNZlVbWvAfgNdTorgDlh1OUA4twU7ZkcjK.png', 0, 0, 1, '2026-01-15 14:19:40', '2026-07-20 20:59:23'),
(5, 4, 'fourth', '<p>fourth</p>', 6, 'news/xA6PixwMzmG8ztblVkvymQ3IsoQejFdIZup5EK9a.png', 0, 0, 1, '2026-01-15 14:20:50', '2026-07-20 20:58:51'),
(6, 3, 'third', '<p>third</p>', 5, 'news/Z0fnxztSUFiDNqrbKw1kZSdilrNvSV0FPPypiG8K.png', 0, 0, 1, '2026-01-15 14:20:52', '2026-07-20 20:58:21'),
(9, 2, 'seventh', '<p>seventh</p>', 4, 'news/NHdp64g167AZ0yKJZK0cDyz9yvxPn8yFDtGGY9xG.png', 0, 0, 1, '2026-01-15 14:21:39', '2026-07-20 20:57:48'),
(11, 2, 'neww', '<p>newww</p>', 5, 'news/xFqzgbSYBTKgyOLl83x5ne4vKvjp06cFeCTRiJCe.png', 0, 0, 1, '2026-01-15 16:54:17', '2026-07-20 20:56:32'),
(12, 1, 'ninth', '<p>ninth</p>', 3, 'news/ERISFbfr3uEmk0D3cl00Einm5cowc8LtvT49UJ5W.png', 0, 0, 1, '2026-01-15 16:54:45', '2026-07-20 20:56:07'),
(13, 11, 'Gamers Station Cup by QEC Draws 128 Players in EAFC25 Showdown', '<p>Doha, Qatar – May 2025 — The Gamers Station Cup, an EAFC25 tournament organized by the Qatar Esports Community (QEC), delivered three days of nonstop action and competition, further cementing QEC’s position as a leader in the regional esports scene. Hosted at Gamers Station — one of Qatar’s premier gaming centers equipped with high-end PC builds — the event welcomed over 128 players who battled it out for supremacy. Demand for the tournament was overwhelming, with 128 player slots filled in under three days, and over 170 registrations submitted through the official tournament website — a clear sign of the surging interest in esports across the country. This wasn’t just another tournament — it was a statement. The Gamers Station Cup proved that QEC has moved beyond its development stage, emerging as a dynamic force ready to lead the next phase of Qatar’s esports evolution. “We’ve always focused on building a strong foundation. Now, we’re ready to scale and lead,” said Smartgama, Head of Operations and Co-Founder of QEC. “This tournament is proof of what’s coming next.” From the state-of-the-art venue to the exceptional turnout and energy, the May 2025 Gamers Station Cup stands as yet another milestone in QEC’s journey — reaffirming its commitment to delivering world-class esports experiences in Qatar and the wider region. Event Duration: 27.5.2025 till 31.5.2025</p>', 1, 'news/93wCEDUgyoOGKS9fU4TUgXqMlotrMfhsg1e9cOo5.png', 0, 0, 1, '2026-01-15 16:55:06', '2026-07-20 20:55:40'),
(14, 1, 'GGs', '<p>Doha, Qatar – May 2025 — The Gamers Station Cup, an EAFC25 tournament organized by the Qatar Esports Community (QEC), delivered three days of nonstop action and competition, further cementing QEC’s position as a leader in the regional esports scene. Hosted at Gamers Station — one of Qatar’s premier gaming centers equipped with high-end PC builds — the event welcomed over 128 players who battled it out for supremacy. Demand for the tournament was overwhelming, with 128 player slots filled in under three days, and over 170 registrations submitted through the official tournament website — a clear sign of the surging interest in esports across the country.&nbsp;</p><p>&nbsp;</p><p>This wasn’t just another tournament — it was a statement. The Gamers Station Cup proved that QEC has moved beyond its development stage, emerging as a dynamic force ready to lead the next phase of Qatar’s esports evolution. “We’ve always focused on building a strong foundation. Now, we’re ready to scale and lead,” said Smartgama, Head of Operations and Co-Founder of QEC. “This tournament is proof of what’s coming next.” From the state-of-the-art venue to the exceptional turnout and energy, the May 2025 Gamers Station Cup stands as yet another milestone in QEC’s journey — reaffirming its commitment to delivering world-class esports experiences in Qatar and the wider region. Event Duration: 27.5.2025 till 31.5.2025</p>', 2, 'news/DxQHNSZqqktEf8CCLztudx9tEut07HCb8rq6IEYk.png', 0, 0, 1, '2026-05-29 03:03:07', '2026-07-20 20:55:03');

-- --------------------------------------------------------

--
-- Table structure for table `news_types`
--

CREATE TABLE `news_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_types`
--

INSERT INTO `news_types` (`id`, `slug`, `name`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'all_news', 'All News', 1, 1, '2026-05-26 10:47:02', '2026-05-26 10:47:02'),
(2, 'tournaments', 'Tournaments', 1, 2, '2026-05-26 10:47:02', '2026-05-26 10:47:02'),
(3, 'reports', 'Reports', 1, 3, '2026-05-26 10:47:02', '2026-05-26 10:47:02'),
(4, 'team_stats', 'Team Stats', 1, 4, '2026-05-26 10:47:02', '2026-05-26 10:47:02'),
(5, 'insights', 'Insights', 1, 5, '2026-05-26 10:47:02', '2026-05-26 10:47:02'),
(6, 'mobile_esports', 'Mobile Esports', 1, 6, '2026-05-26 10:47:02', '2026-05-26 10:47:02'),
(7, 'company_news', 'Company News', 0, 7, '2026-05-26 10:47:02', '2026-07-01 05:41:40'),
(8, 'games', 'Games', 1, 9, '2026-05-27 00:48:57', '2026-05-27 00:48:57'),
(9, 'streaming', 'Streaming', 1, 2, '2026-06-05 16:13:59', '2026-06-05 16:13:59'),
(10, 'geeks', 'Geeks', 1, 10, '2026-07-01 05:42:10', '2026-07-01 05:42:10');

-- --------------------------------------------------------

--
-- Table structure for table `news_user_actions`
--

CREATE TABLE `news_user_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `news_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_liked` tinyint(4) DEFAULT 0,
  `is_bookmarked` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `news_user_actions`
--

INSERT INTO `news_user_actions` (`id`, `user_id`, `news_id`, `is_liked`, `is_bookmarked`, `created_at`, `updated_at`) VALUES
(1, 6, 8, 0, 1, '2026-01-15 14:27:33', '2026-01-15 14:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `page_settings`
--

CREATE TABLE `page_settings` (
  `id` int(11) NOT NULL,
  `type` enum('privacy','terms','cookie') NOT NULL,
  `slug` varchar(191) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_settings`
--

INSERT INTO `page_settings` (`id`, `type`, `slug`, `title`, `content`, `created_at`, `updated_at`) VALUES
(1, 'privacy', 'privacy-policy', 'Privacy Policy', '<p>Last updated: Feb 21th 2019</p><p>&nbsp;</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam sed bibendum dolor, non condimentum diam. Sed non odio placerat, tempus erat id, hendrerit justo. Morbi orci dui, facilisis nec consectetur maximus, semper at leo. Nunc vel efficitur ipsum, at commodo erat. In eu condimentum enim. Nulla nisl leo, mattis in iaculis ac, tristique non felis.</p><p>Cras ornare, arcu ut molestie vehicula, nisl tortor tempor eros, id eleifend velit leo ac sem. Suspendisse potenti. Maecenas quis arcu nunc.</p><h3>Sed a enim diam</h3><p>Morbi vestibulum consectetur metus, at lacinia ipsum ullamcorper sit amet. In interdum risus in sagittis consectetur. Ut sollicitudin congue mauris, quis vulputate metus accumsan et. Nunc ut tortor magna. Sed a enim diam. Suspendisse fringilla quam vitae sollicitudin rhoncus. Maecenas eu dignissim neque. Proin eu dolor purus. Class aptent taciti sociosqu ad litora torquent.</p><h3>Morbi orci dui</h3><p>Ut at tellus ac sapien tincidunt mattis interdum et elit. Sed ac tempor risus, at volutpat nunc. Pellentesque venenatis, arcu a hendrerit volutpat, ligula est condimentum magna, non varius tellus mi eu urna. Donec vel fringilla urna. Vestibulum lobortis elit in posuere fermentum. Vestibulum.</p>', '2026-06-30 06:47:45', '2026-06-30 16:44:21'),
(2, 'terms', 'terms-of-service', 'Terms of Service', '<p>Last Updated: January 21, 2025</p><p>&nbsp;</p><p>Welcome to Curefy! These Legal (\"Legal\") govern your use of our website, services, and any related content. By accessing or using Curefy, you agree to comply with these Terms. If you do not agree, please refrain from using our services.</p><h2>Acceptance of Terms</h2><p>By using Curefy, you confirm that you are at least 18 years old or have parental/ legal guardian consent. You also agree to abide by all applicable laws and regulations while using our services.</p><h2>Use of Our Services</h2><p>You may use Curefy for personal and non-commercial purposes related to healthcare. You agree not to:</p><ul><li>Provide false or misleading medical information.</li><li>Use our platform for illegal, fraudulent, or harmful activities.</li><li>Attempt to access or manipulate our systems without authorization.</li><li>Violate any applicable laws or third-party rights.</li></ul><p>We reserve the right to suspend or terminate access to our services if these Terms are violated.</p><h2>Medical Disclaimer</h2><p>Curefy provides healthcare services but does not replace professional medical advice. While our medical professionals offer guidance, always consult your primary care physician before making health-related decisions. We do not guarantee specific medical outcomes.</p><p><strong>Emergency Notice:</strong> If you have a medical emergency, call 911 or seek immediate medical attention.</p><h2>Appointments &amp; Cancellations</h2><ul><li><strong>• Booking:</strong> You can schedule appointments through our website or by phone.</li><li><strong>• Cancellations:</strong> Cancellations must be made at least 24 hours in advance to avoid fees.</li><li><strong>• No-Show Policy:</strong> Missed appointments may result in service charges.</li><li><strong>• Rescheduling:</strong> Subject to availability, appointments may be rescheduled upon request.</li></ul><h2>Privacy &amp; Data Protection</h2><p>We collect and process personal data in accordance with our <strong>Privacy Policy</strong>. Key terms:</p><ul><li><strong>• Data Collection:</strong> We gather personal and medical information to provide better care.</li><li><strong>• Data Security:</strong> We implement strong security measures to protect your</li></ul>', '2026-06-30 06:47:45', '2026-06-30 16:44:41'),
(3, 'cookie', 'cookie-policy', 'cookie-policy', '<p>Last updated: Feb 21th 2019</p><p>&nbsp;</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam sed bibendum dolor, non condimentum diam. Sed non odio placerat, tempus erat id, hendrerit justo. Morbi orci dui, facilisis nec consectetur maximus, semper at leo. Nunc vel efficitur ipsum, at commodo erat. In eu condimentum enim. Nulla nisl leo, mattis in iaculis ac, tristique non felis.</p><p>Cras ornare, arcu ut molestie vehicula, nisl tortor tempor eros, id eleifend velit leo ac sem. Suspendisse potenti. Maecenas quis arcu nunc.</p><h3>Sed a enim diam</h3><p>Morbi vestibulum consectetur metus, at lacinia ipsum ullamcorper sit amet. In interdum risus in sagittis consectetur. Ut sollicitudin congue mauris, quis vulputate metus accumsan et. Nunc ut tortor magna. Sed a enim diam. Suspendisse fringilla quam vitae sollicitudin rhoncus. Maecenas eu dignissim neque. Proin eu dolor purus. Class aptent taciti sociosqu ad litora torquent.</p><h3>Morbi orci dui</h3><p>Ut at tellus ac sapien tincidunt mattis interdum et elit. Sed ac tempor risus, at volutpat nunc. Pellentesque venenatis, arcu a hendrerit volutpat, ligula est condimentum magna, non varius tellus mi eu urna. Donec vel fringilla urna. Vestibulum lobortis elit in posuere fermentum. Vestibulum.</p>', '2026-07-17 13:30:41', '2026-07-20 23:14:28');

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `name`, `logo`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Our partner', 'partners/kcAidrmeL4I608wiq9tRGK3Jp7gulfghcobg7e4p.png', 1, 1, '2026-01-14 17:35:44', '2026-06-26 06:59:45'),
(4, 'Our partner', 'partners/uVBrplegLU7oScZZ8CuKaWeRADwajdKVtpIwGoVS.png', 2, 1, '2026-01-14 17:36:02', '2026-01-14 17:36:02'),
(5, 'Our partner', 'partners/4HdDjK5NXEAmGvu54MOHALL6XQywMDFloC7uroYD.png', 3, 1, '2026-01-14 17:36:31', '2026-01-14 17:36:31'),
(6, 'Our partner', 'partners/HJUcrCB3WopraBzRtUhI7ag3pWvqW3leqLWSywbh.png', 4, 1, '2026-01-14 17:37:52', '2026-01-14 17:37:52'),
(7, 'Our partner', 'partners/UCNWigUi2o5deH2ijHa9DvrLg80m1Pwiw9Yqubgo.png', 5, 1, '2026-01-14 17:38:07', '2026-01-14 17:38:07'),
(8, 'Our partner', 'partners/Wm4LcqGebkujlRoWYPh3jS8fREwvs2sRsh49kwJp.png', 6, 1, '2026-01-14 17:38:23', '2026-01-14 17:38:23'),
(9, 'GG test', 'partners/ZilTaa5yAQLiWjGnjbIyDOsUy6zyhWzQf93LheXe.png', 0, 1, '2026-05-23 15:47:16', '2026-05-23 15:47:16');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `module` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `created_at`, `updated_at`) VALUES
(1, 'View Tournament', 'tournament.view', 'Tournament', NULL, NULL),
(2, 'Create Tournament', 'tournament.create', 'Tournament', NULL, NULL),
(3, 'Edit Tournament', 'tournament.edit', 'Tournament', NULL, NULL),
(4, 'Delete Tournament', 'tournament.delete', 'Tournament', NULL, NULL),
(5, 'View Contact Requests', 'contact.view', 'Contact', NULL, NULL),
(6, 'Delete Contact Requests', 'contact.delete', 'Contact', NULL, NULL),
(8, 'View Participants', 'participants.view', 'Participants', NULL, NULL),
(9, 'Approve Participants', 'participants.approve', 'Participants', NULL, NULL),
(10, 'Delete Participants', 'participants.delete', 'Participants', NULL, NULL),
(11, 'View Maps', 'maps.view', 'Maps', NULL, NULL),
(12, 'Create Maps', 'maps.create', 'Maps', NULL, NULL),
(13, 'Edit Maps', 'maps.edit', 'Maps', NULL, NULL),
(14, 'Delete Maps', 'maps.delete', 'Maps', NULL, NULL),
(15, 'View Games', 'games.view', 'Games', NULL, NULL),
(16, 'Create Games', 'games.create', 'Games', NULL, NULL),
(17, 'Edit Games', 'games.edit', 'Games', NULL, NULL),
(18, 'Delete Games', 'games.delete', 'Games', NULL, NULL),
(19, 'View Live Streams', 'livestream.view', 'Live Stream', NULL, NULL),
(20, 'Create Live Streams', 'livestream.create', 'Live Stream', NULL, NULL),
(21, 'Edit Live Streams', 'livestream.edit', 'Live Stream', NULL, NULL),
(22, 'Delete Live Streams', 'livestream.delete', 'Live Stream', NULL, NULL),
(23, 'View Logo', 'logo.view', 'Logo', NULL, NULL),
(24, 'Update Logo', 'logo.update', 'Logo', NULL, NULL),
(25, 'View Banner', 'banner.view', 'Banner', NULL, NULL),
(26, 'Create Banner', 'banner.create', 'Banner', NULL, NULL),
(27, 'Edit Banner', 'banner.edit', 'Banner', NULL, NULL),
(28, 'Delete Banner', 'banner.delete', 'Banner', NULL, NULL),
(29, 'Export Participants', 'participants.export', 'Participants', NULL, NULL),
(30, 'View About Page', 'about.view', 'About', NULL, NULL),
(31, 'Update About Page', 'about.update', 'About', NULL, NULL),
(32, 'View Footer', 'footer.view', 'Footer', NULL, NULL),
(33, 'Update Footer', 'footer.update', 'Footer', NULL, NULL),
(34, 'View Legal Pages', 'legal.view', 'Legal', NULL, NULL),
(35, 'Update Legal Pages', 'legal.update', 'Legal', NULL, NULL),
(36, 'View News', 'news.view', 'News', NULL, NULL),
(37, 'Create News', 'news.create', 'News', NULL, NULL),
(38, 'Edit News', 'news.edit', 'News', NULL, NULL),
(39, 'Delete News', 'news.delete', 'News', NULL, NULL),
(40, 'View Users', 'users.view', 'Users', NULL, NULL),
(41, 'Create Users', 'users.create', 'Users', NULL, NULL),
(42, 'Edit Users', 'users.edit', 'Users', NULL, NULL),
(43, 'Delete Users', 'users.delete', 'Users', NULL, NULL),
(44, 'View Roles', 'roles.view', 'Roles', NULL, NULL),
(45, 'Create Roles', 'roles.create', 'Roles', NULL, NULL),
(46, 'Edit Roles', 'roles.edit', 'Roles', NULL, NULL),
(47, 'Delete Roles', 'roles.delete', 'Roles', NULL, NULL),
(48, 'View Settings', 'settings.view', 'Settings', NULL, NULL),
(49, 'Update Settings', 'settings.update', 'Settings', NULL, NULL),
(50, 'Export Tournament', 'tournament.export', 'Tournament', '2026-07-01 12:22:21', '2026-07-01 12:22:21'),
(52, 'Featured Tournament', 'tournament.freatured', 'Tournament', '2026-07-01 12:22:21', '2026-07-01 12:22:21');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', '23fb4c6fd0a40a4bedbf31fe1dc8e29ef2e2765ec55b52bb6d2c08067b99f8ea', '[\"*\"]', '2025-12-22 03:05:45', NULL, '2025-12-22 02:54:21', '2025-12-22 03:05:45'),
(2, 'App\\Models\\User', 2, 'auth_token', 'f0d326cbff9133326f3aeb90cfbc7c88313f691a6383afb3530b58ff4293f2d9', '[\"*\"]', '2025-12-22 03:44:01', NULL, '2025-12-22 03:05:28', '2025-12-22 03:44:01'),
(4, 'App\\Models\\User', 6, 'auth_token', '2936de29db89c4e7eba06766ce26bc44052353e9d729afc616a084ba197ba47b', '[\"*\"]', NULL, NULL, '2025-12-23 09:41:44', '2025-12-23 09:41:44'),
(5, 'App\\Models\\User', 6, 'auth_token', '5360cef0771093c596a86d2dc3f5e0c383995c780d6a3fa90bc8906c6ecfc38e', '[\"*\"]', '2025-12-23 10:08:54', NULL, '2025-12-23 09:44:15', '2025-12-23 10:08:54'),
(6, 'App\\Models\\User', 6, 'auth_token', '77f252e52f3a0f4f0d5cfc579931c076722322e4dccb81b4db0a371bfff06b5b', '[\"*\"]', NULL, NULL, '2025-12-23 10:01:18', '2025-12-23 10:01:18'),
(7, 'App\\Models\\User', 6, 'auth_token', '17a0d8b9db773471a5ff6409fbc950d434be16ad596812855737700fe0ee5674', '[\"*\"]', '2025-12-24 02:02:55', NULL, '2025-12-24 02:00:28', '2025-12-24 02:02:55'),
(8, 'App\\Models\\User', 6, 'auth_token', '03e460d9e88d749b239f7389d42ab7ce5bbb9cf04327d7c85a55a245c8da5c0a', '[\"*\"]', NULL, NULL, '2025-12-24 02:31:16', '2025-12-24 02:31:16'),
(9, 'App\\Models\\User', 8, 'auth_token', 'e4b27c464ab7f2ed23bf15938cd707fc5d015fa78f2d7c78cc2191a8ed41aa23', '[\"*\"]', '2025-12-26 16:14:23', NULL, '2025-12-26 15:35:53', '2025-12-26 16:14:23'),
(10, 'App\\Models\\User', 2, 'auth_token', 'ff5dc7d292ea561eeca01b3510bf93790e0f82eab5c63a9696a5b348528148cb', '[\"*\"]', '2025-12-26 16:36:39', NULL, '2025-12-26 16:19:46', '2025-12-26 16:36:39'),
(11, 'App\\Models\\User', 8, 'auth_token', '0706039a3746daf8f4df97d554c4fac117bf33e17a556b2d49f1f3191a1072c5', '[\"*\"]', '2025-12-26 19:09:07', NULL, '2025-12-26 16:58:03', '2025-12-26 19:09:07'),
(12, 'App\\Models\\User', 6, 'auth_token', 'df7a6ebb9922910a1ed9e662767d3f0da102ea3a390cc1a87b57c9a77f284d47', '[\"*\"]', '2025-12-29 11:42:50', NULL, '2025-12-26 18:29:03', '2025-12-29 11:42:50'),
(13, 'App\\Models\\User', 8, 'auth_token', 'cdd4ca7ffd7fd109c7282ad6e7e651837be8871a6a96818cdfdea87c704311e4', '[\"*\"]', '2026-01-06 19:29:13', NULL, '2025-12-26 19:27:30', '2026-01-06 19:29:13'),
(14, 'App\\Models\\User', 2, 'auth_token', '900d992c21656209c25f20acae2c2ddef225da6d63214c13f93df634f4c76ea0', '[\"*\"]', '2025-12-30 13:48:39', NULL, '2025-12-27 21:31:31', '2025-12-30 13:48:39'),
(15, 'App\\Models\\User', 6, 'auth_token', '27a44654aba9ec7c682fdfd512a51262931fe56ef16fc55ea51cb71c2002ce6c', '[\"*\"]', '2025-12-29 15:06:34', NULL, '2025-12-29 11:51:19', '2025-12-29 15:06:34'),
(16, 'App\\Models\\User', 6, 'auth_token', 'ed49c08a36a7d2a9776461d8e43e9c2f1e928d92de00df962fc0617181b21176', '[\"*\"]', '2025-12-30 12:19:21', NULL, '2025-12-29 15:54:29', '2025-12-30 12:19:21'),
(17, 'App\\Models\\User', 6, 'auth_token', '3a98e1f7f3e88a5002b113cbc458b6ef6f806d74167b7a3049e230a06ce95873', '[\"*\"]', '2025-12-30 12:58:15', NULL, '2025-12-30 12:27:01', '2025-12-30 12:58:15'),
(18, 'App\\Models\\User', 6, 'auth_token', 'd14e9c785097aa3243c55f54d949241bc28d4ab8a883efa523faefc60e8c731e', '[\"*\"]', '2025-12-30 13:00:31', NULL, '2025-12-30 12:27:38', '2025-12-30 13:00:31'),
(19, 'App\\Models\\User', 6, 'auth_token', '642a8e20816cde97778de8f5403f4841b775106a82032e509bc252efee3ded40', '[\"*\"]', '2025-12-30 13:46:55', NULL, '2025-12-30 12:58:19', '2025-12-30 13:46:55'),
(20, 'App\\Models\\User', 6, 'auth_token', '7ce9db271e3c5d69530f215c7a194da43d7ca2b841f07746f50573f31fc0fd9d', '[\"*\"]', '2025-12-30 15:56:37', NULL, '2025-12-30 13:01:11', '2025-12-30 15:56:37'),
(21, 'App\\Models\\User', 2, 'auth_token', '8c2c44c527c086177a333f52be7be44445eeff73ea1fef2cc9cf99e980ee3944', '[\"*\"]', '2026-01-05 18:46:35', NULL, '2025-12-30 13:53:10', '2026-01-05 18:46:35'),
(22, 'App\\Models\\User', 6, 'auth_token', '90aeff39d5b408c09befdf9edbfed4ace29b6e4edf05987b743bc27de038fda0', '[\"*\"]', '2025-12-30 16:02:01', NULL, '2025-12-30 16:01:48', '2025-12-30 16:02:01'),
(23, 'App\\Models\\User', 6, 'auth_token', '80c2db79364f52a193dea3848dcb6895a246fd1761e719dfc529fc7ca802ad5a', '[\"*\"]', '2025-12-30 16:09:27', NULL, '2025-12-30 16:02:56', '2025-12-30 16:09:27'),
(24, 'App\\Models\\User', 6, 'auth_token', '2d8ec4199c3a5e09a9c5da321911beaad79415feb397729cd8641179bba12ecf', '[\"*\"]', '2025-12-30 16:22:36', NULL, '2025-12-30 16:13:53', '2025-12-30 16:22:36'),
(25, 'App\\Models\\User', 6, 'auth_token', 'c344f8428b45a3d34ef4aec2964294675df26ac97f2a5aa3b8c4f353e7097a82', '[\"*\"]', '2025-12-30 16:49:58', NULL, '2025-12-30 16:49:32', '2025-12-30 16:49:58'),
(26, 'App\\Models\\User', 6, 'auth_token', 'c3e4f5b76ef09b479f54ab437f583ed4cef2bfc62992e5311dc386c9d3125d67', '[\"*\"]', '2025-12-30 16:50:27', NULL, '2025-12-30 16:50:26', '2025-12-30 16:50:27'),
(27, 'App\\Models\\User', 6, 'auth_token', '388003297a5f8af074904cd04bf84bc2187dc2c06273b8656e10fa0ea9b69f50', '[\"*\"]', '2025-12-30 16:58:18', NULL, '2025-12-30 16:51:08', '2025-12-30 16:58:18'),
(28, 'App\\Models\\User', 6, 'auth_token', '9e6772c5fa551de1f6bf122eb407bb3b6860da15a49404c26188558ce05d9364', '[\"*\"]', '2025-12-30 17:03:46', NULL, '2025-12-30 16:58:55', '2025-12-30 17:03:46'),
(29, 'App\\Models\\User', 6, 'auth_token', '5b784cd9a4b64395b7c497f4e86bf7938e725688d96ca2ffcf097491c8445e09', '[\"*\"]', '2025-12-30 17:11:07', NULL, '2025-12-30 17:04:18', '2025-12-30 17:11:07'),
(30, 'App\\Models\\User', 6, 'auth_token', '88a71978b1872765b2a63da0a673d5bd0b64d77cf86cd49499dead22fb9f9d3a', '[\"*\"]', '2025-12-30 19:06:14', NULL, '2025-12-30 17:11:31', '2025-12-30 19:06:14'),
(31, 'App\\Models\\User', 2, 'auth_token', '934824bbc0ce070d5459a3b1f071255734436bc8240e3de82703db1adf342ce7', '[\"*\"]', '2025-12-30 19:42:28', NULL, '2025-12-30 17:35:34', '2025-12-30 19:42:28'),
(32, 'App\\Models\\User', 2, 'auth_token', '1e97e79ee05a88dc79078f8c3eeff406bcce98f641b091c8de46103c50d84162', '[\"*\"]', NULL, NULL, '2025-12-30 18:54:36', '2025-12-30 18:54:36'),
(33, 'App\\Models\\User', 2, 'auth_token', '3cfd136d82bb9e7bbb3a02320a5d6d66dbf8cafb9ca370b3b57027ac2b32d608', '[\"*\"]', NULL, NULL, '2025-12-30 18:55:25', '2025-12-30 18:55:25'),
(34, 'App\\Models\\User', 2, 'auth_token', '48f38b0e9725edb46a250d48435dacea8b232d8abe6e92505c63af4cbc749753', '[\"*\"]', '2025-12-30 19:24:20', NULL, '2025-12-30 19:13:29', '2025-12-30 19:24:20'),
(35, 'App\\Models\\User', 6, 'auth_token', '8ead2d1e5e0933f6a72c89c8e498aedc17b6a8f96216db1fb9a87fb019c5c954', '[\"*\"]', '2025-12-31 11:41:08', NULL, '2025-12-30 19:17:12', '2025-12-31 11:41:08'),
(36, 'App\\Models\\User', 2, 'auth_token', '17dd89870b7664b3418b9ed5a24633620a6c8a19fee6fca749b9c45543910056', '[\"*\"]', '2025-12-30 19:52:22', NULL, '2025-12-30 19:24:48', '2025-12-30 19:52:22'),
(37, 'App\\Models\\User', 2, 'auth_token', '3918063d643b9262fce5477aeac0d77558096d796b64d4bcc5830add7108ebc8', '[\"*\"]', NULL, NULL, '2025-12-30 19:49:26', '2025-12-30 19:49:26'),
(38, 'App\\Models\\User', 2, 'auth_token', 'dbf9970a90a03acacff5721bc9b68ec5163c3b6c9ac89c85e7179063b8bfbf18', '[\"*\"]', '2025-12-30 19:52:46', NULL, '2025-12-30 19:49:58', '2025-12-30 19:52:46'),
(39, 'App\\Models\\User', 6, 'auth_token', '4664670c40c280c1b8c8b8408d6dea60f54687f88a7b68aa402a1eeb3c6e13b1', '[\"*\"]', '2026-01-07 11:44:50', NULL, '2025-12-31 11:42:27', '2026-01-07 11:44:50'),
(40, 'App\\Models\\User', 2, 'auth_token', 'd66b4f8d3073fcd27eb1c3e728c5bbe31c9c79581c08afa5ced92562d14439f9', '[\"*\"]', '2026-01-14 15:24:16', NULL, '2025-12-31 12:49:31', '2026-01-14 15:24:16'),
(41, 'App\\Models\\User', 6, 'auth_token', '23351147e189ac80ca276e32d059b05ef5b19105d761d2c6b4c62cdf7c74b7c7', '[\"*\"]', '2026-01-15 19:38:19', NULL, '2026-01-05 16:27:29', '2026-01-15 19:38:19'),
(42, 'App\\Models\\User', 9, 'auth_token', 'b6165d07b495d82537533d584f03b9174825499ee8af8a4564676b81dc7700cf', '[\"*\"]', NULL, NULL, '2026-01-05 16:55:57', '2026-01-05 16:55:57'),
(43, 'App\\Models\\User', 8, 'auth_token', '1092c45ca907bc79ff1210e74365482a923d871012cc9fe89b31a48813e2a88f', '[\"*\"]', '2026-01-05 18:51:05', NULL, '2026-01-05 17:01:53', '2026-01-05 18:51:05'),
(44, 'App\\Models\\User', 2, 'auth_token', '4bc721c8fc61ccbcaa63c203e4c067b2fa19f3e862017f380fb3adcc1c8ee998', '[\"*\"]', NULL, NULL, '2026-01-05 18:34:43', '2026-01-05 18:34:43'),
(45, 'App\\Models\\User', 8, 'auth_token', '60e69154b3d1699f6fb94c51aa4d1747bf3d1964a2b1e869c2521e5d9e41a665', '[\"*\"]', '2026-01-06 17:44:55', NULL, '2026-01-05 18:53:11', '2026-01-06 17:44:55'),
(46, 'App\\Models\\User', 8, 'auth_token', '4457f4a7d6db311e899550ab15bf1b8df0348f35491aa8a35fccdbf3108c1518', '[\"*\"]', NULL, NULL, '2026-01-05 20:43:17', '2026-01-05 20:43:17'),
(47, 'App\\Models\\User', 8, 'auth_token', 'ae86ddfdbfe244c55aa85a2f7e5f9404548d1137a9728d21f60194041ee79d13', '[\"*\"]', NULL, NULL, '2026-01-05 20:45:22', '2026-01-05 20:45:22'),
(48, 'App\\Models\\User', 8, 'auth_token', '002e326ec14f685362be592dba7201864cb0c0f292b7d2fb58924545f3837715', '[\"*\"]', NULL, NULL, '2026-01-05 21:02:57', '2026-01-05 21:02:57'),
(49, 'App\\Models\\User', 8, 'auth_token', '0a6d3a42b4eb88a7bea01a68fbf82a2bd9848593c0e87754c92417dead682ddc', '[\"*\"]', '2026-01-05 21:05:04', NULL, '2026-01-05 21:03:33', '2026-01-05 21:05:04'),
(50, 'App\\Models\\User', 10, 'auth_token', '9655c41dcf28c1b78dcbaf5562262af503bc4b075b5f43832ef31f1e4e3fac6e', '[\"*\"]', NULL, NULL, '2026-01-05 22:38:27', '2026-01-05 22:38:27'),
(51, 'App\\Models\\User', 10, 'auth_token', '6c2e5b794f7525ac4a5dbd4caad4549e0f924666e069f90b4a0df62949a7ba62', '[\"*\"]', '2026-01-06 12:48:13', NULL, '2026-01-05 22:38:38', '2026-01-06 12:48:13'),
(52, 'App\\Models\\User', 8, 'auth_token', '2ebbb6e948939b6d3e44f512d5af9310755a8c2ca6dd4ddf2b83b7fef7dc7be9', '[\"*\"]', '2026-01-12 11:30:00', NULL, '2026-01-06 19:30:11', '2026-01-12 11:30:00'),
(53, 'App\\Models\\User', 2, 'auth_token', 'ca7aa96525b1515e6a61788a36536eba7f24b70ec811f37c82db1b6d85628d77', '[\"*\"]', NULL, NULL, '2026-01-07 12:11:43', '2026-01-07 12:11:43'),
(54, 'App\\Models\\User', 6, 'auth_token', 'ad5956e27d1649e67213e0d0c92c6ea1438e3dd55cf7214365aa5dc010d7912c', '[\"*\"]', NULL, NULL, '2026-01-07 12:11:59', '2026-01-07 12:11:59'),
(55, 'App\\Models\\User', 6, 'auth_token', '7702a0d455c5b175d96634226debbe07cf2f8d02dc5e6b37bc586320bb4cd38b', '[\"*\"]', NULL, NULL, '2026-01-07 12:32:22', '2026-01-07 12:32:22'),
(56, 'App\\Models\\User', 6, 'auth_token', '39c31095ffb7c35a93907c1f6f2e6bb2bd50447e1ee6f03f2b9440e7094c4ff6', '[\"*\"]', NULL, NULL, '2026-01-07 12:33:27', '2026-01-07 12:33:27'),
(57, 'App\\Models\\User', 6, 'auth_token', 'f5b77b49531447a6f34e7abe0f513cc161494cefd934a5878f6fcd8decf133fc', '[\"*\"]', NULL, NULL, '2026-01-07 12:33:35', '2026-01-07 12:33:35'),
(58, 'App\\Models\\User', 6, 'auth_token', 'bd08dac869ff9d21ba92ee69d4ccd93efdac547089dc14a4c1eaa6f0d4df52d1', '[\"*\"]', NULL, NULL, '2026-01-07 12:35:25', '2026-01-07 12:35:25'),
(59, 'App\\Models\\User', 6, 'auth_token', '20812267d399e248f13a6f0ef9ca3cd438445ca2d209784882414b82af7c6db6', '[\"*\"]', NULL, NULL, '2026-01-07 12:37:47', '2026-01-07 12:37:47'),
(60, 'App\\Models\\User', 6, 'auth_token', '3dd50ffd1a32d8a27ff959855356a5c03a34f477d0280c5630319b028328dcd2', '[\"*\"]', NULL, NULL, '2026-01-07 12:38:37', '2026-01-07 12:38:37'),
(61, 'App\\Models\\User', 6, 'auth_token', '82cb1213cc78e2a1287130038b4961a65759123ab6ca99a48e02478c2f51e918', '[\"*\"]', NULL, NULL, '2026-01-07 12:39:11', '2026-01-07 12:39:11'),
(62, 'App\\Models\\User', 6, 'auth_token', 'b4fa9ac5646c60e8c23f1f862f92efe421e658b4c5d9eeaa441f94d3e4232281', '[\"*\"]', NULL, NULL, '2026-01-07 12:42:13', '2026-01-07 12:42:13'),
(63, 'App\\Models\\User', 6, 'auth_token', '0b36eb66d5769bb67b4118f7dbd10b8c870a35948b5170fd514b421458e57c8a', '[\"*\"]', NULL, NULL, '2026-01-07 12:42:39', '2026-01-07 12:42:39'),
(64, 'App\\Models\\User', 6, 'auth_token', 'f949ee545d9d861cf6d777869a984e519f771c04bc886190ab882435b2ac480f', '[\"*\"]', NULL, NULL, '2026-01-07 12:44:10', '2026-01-07 12:44:10'),
(65, 'App\\Models\\User', 6, 'auth_token', '0dc6dc28016f51e9ebac30a04443ce1adc943b5839a5b07e1abcf23c05b6e14f', '[\"*\"]', NULL, NULL, '2026-01-07 12:46:41', '2026-01-07 12:46:41'),
(66, 'App\\Models\\User', 6, 'auth_token', '923c6492803d582d5676774ab729e280cda0197a7786c47c11f9071dde20928d', '[\"*\"]', NULL, NULL, '2026-01-07 12:47:11', '2026-01-07 12:47:11'),
(67, 'App\\Models\\User', 6, 'auth_token', 'ea6161d8b014344e08f2c1923c66d501084c96bf86e06d6842e201ad7b3212c8', '[\"*\"]', NULL, NULL, '2026-01-07 12:47:51', '2026-01-07 12:47:51'),
(68, 'App\\Models\\User', 6, 'auth_token', 'e5056e72f8e7e11402f37e5a2e2ba9f16b67ffe5e3dd8d073050bbba258d32b3', '[\"*\"]', NULL, NULL, '2026-01-07 12:49:04', '2026-01-07 12:49:04'),
(69, 'App\\Models\\User', 6, 'auth_token', '4b5b965815b1310a2307d033d88821b48bf762b7a33a6fe0e5787bb77d78e2d5', '[\"*\"]', '2026-01-07 15:03:38', NULL, '2026-01-07 12:53:09', '2026-01-07 15:03:38'),
(70, 'App\\Models\\User', 6, 'auth_token', 'ddffd088d6711e88be89130000e970f52571263ff45cd5e148be514dc115826d', '[\"*\"]', '2026-01-07 15:41:49', NULL, '2026-01-07 15:04:07', '2026-01-07 15:41:49'),
(71, 'App\\Models\\User', 6, 'auth_token', 'd3febe660cb6d33358c79351c329f11b147f47c5afca97bc43de1cb1593cf8fd', '[\"*\"]', '2026-01-08 11:05:50', NULL, '2026-01-07 15:42:16', '2026-01-08 11:05:50'),
(72, 'App\\Models\\User', 2, 'auth_token', 'c65da7a251f1cd681f53ff001ec92bc3020c0b8a62ebfd81a5b21378b032bd14', '[\"*\"]', '2026-01-07 15:52:19', NULL, '2026-01-07 15:51:42', '2026-01-07 15:52:19'),
(73, 'App\\Models\\User', 6, 'auth_token', '25710de45a7ad9c148cc648e4e1a3f2ba2c7a720fe2bf1e2330886122fd6f2fc', '[\"*\"]', '2026-01-08 11:46:26', NULL, '2026-01-08 11:46:04', '2026-01-08 11:46:26'),
(74, 'App\\Models\\User', 6, 'auth_token', '81e25b22a9a14a573303e40ab9765499f77672505256e5d333d6296ca35b164e', '[\"*\"]', '2026-01-08 11:51:00', NULL, '2026-01-08 11:50:54', '2026-01-08 11:51:00'),
(75, 'App\\Models\\User', 6, 'auth_token', '3ac5cf60c5dadd26212b962b73da13aa93752e06ca56e850256d88eae2823303', '[\"*\"]', '2026-01-13 10:50:17', NULL, '2026-01-13 10:40:11', '2026-01-13 10:50:17'),
(76, 'App\\Models\\User', 6, 'auth_token', 'e9ccd1922c89bd35c72ccfeccb014202e98f62da3eed3b1d49c565017f58edeb', '[\"*\"]', '2026-01-13 10:50:15', NULL, '2026-01-13 10:50:13', '2026-01-13 10:50:15'),
(77, 'App\\Models\\User', 6, 'auth_token', 'e8abe31c3bc71f76d75a1c0b3d21991a747169cdeb93ab8a64b1f30548d1bb03', '[\"*\"]', NULL, NULL, '2026-01-13 11:19:18', '2026-01-13 11:19:18'),
(78, 'App\\Models\\User', 6, 'auth_token', 'b1b169e95ecf4bba7a5ae805490329707f3df5602dc9ef9103b7586f05ab4a25', '[\"*\"]', NULL, NULL, '2026-01-13 11:19:25', '2026-01-13 11:19:25'),
(79, 'App\\Models\\User', 6, 'auth_token', 'de9f5d98bcbf0215059ded161ddc25b199a0c4f2b365c4bddc86d5dc1378cc47', '[\"*\"]', NULL, NULL, '2026-01-13 11:20:08', '2026-01-13 11:20:08'),
(80, 'App\\Models\\User', 6, 'auth_token', '6766d808018ed35c89a15d1b5790d9fe0c3d9d96df9ccfa0968a2d967a446127', '[\"*\"]', '2026-01-13 11:23:27', NULL, '2026-01-13 11:20:38', '2026-01-13 11:23:27'),
(81, 'App\\Models\\User', 6, 'auth_token', 'fe4010b48f4064ef7cb15abac2101732cc2968990b99763c90b3352e96f20d9f', '[\"*\"]', '2026-01-13 11:35:55', NULL, '2026-01-13 11:35:54', '2026-01-13 11:35:55'),
(82, 'App\\Models\\User', 6, 'auth_token', '937c0d5fcfd8ac5ad5c9e93bbab5401224730a3fb30e3547c95dd13492fb302d', '[\"*\"]', '2026-01-14 15:24:29', NULL, '2026-01-14 10:29:07', '2026-01-14 15:24:29'),
(83, 'App\\Models\\User', 6, 'auth_token', 'bd0c47d7a1849973097a02cab1ff6d08e6821d0705cb1acff9e06d51893e01de', '[\"*\"]', '2026-01-14 16:46:46', NULL, '2026-01-14 15:45:32', '2026-01-14 16:46:46'),
(84, 'App\\Models\\User', 6, 'auth_token', 'ca78e064d0cc8203b68ef4307d1979c4966524ec36d73f98c4223b7abceccba8', '[\"*\"]', '2026-01-14 17:18:18', NULL, '2026-01-14 16:47:13', '2026-01-14 17:18:18'),
(85, 'App\\Models\\User', 6, 'auth_token', 'b4edbb6271522adb7c6b04bf7cc1e8a50496cecc29b5c2246b5ffbfdb9cd0620', '[\"*\"]', '2026-01-14 17:27:29', NULL, '2026-01-14 17:19:03', '2026-01-14 17:27:29'),
(86, 'App\\Models\\User', 6, 'auth_token', '4abf08c2a77afc94af63b04f62dc96fa9f6fc1279da0f08bbb4c301748cb5ff4', '[\"*\"]', '2026-01-14 17:30:22', NULL, '2026-01-14 17:28:09', '2026-01-14 17:30:22'),
(87, 'App\\Models\\User', 6, 'auth_token', '04b5f1a7b9f5f3a27a8659123cd07fa4f8011d2d77d5704a6613859f73fc82a2', '[\"*\"]', '2026-01-14 17:30:46', NULL, '2026-01-14 17:30:46', '2026-01-14 17:30:46'),
(88, 'App\\Models\\User', 6, 'auth_token', '62eb6a81ba96e2b5ecddfb53df9a172b382ec2b3314f7559b6ae5f6a2f796e48', '[\"*\"]', '2026-01-14 17:33:38', NULL, '2026-01-14 17:31:17', '2026-01-14 17:33:38'),
(89, 'App\\Models\\User', 6, 'auth_token', '29a91754a781906bb1ccf64510421c8aeaf93505babb0e53d814efd0552c8936', '[\"*\"]', '2026-01-15 16:42:25', NULL, '2026-01-14 17:35:50', '2026-01-15 16:42:25'),
(90, 'App\\Models\\User', 6, 'auth_token', '118327758349898edcfb23fbd83de9a52fc7d5239ee0c47b78cadf4eb7003c16', '[\"*\"]', '2026-01-15 14:35:22', NULL, '2026-01-14 18:29:11', '2026-01-15 14:35:22'),
(91, 'App\\Models\\User', 9, 'auth_token', 'fb3b75d5f41da67dfff00c0e667735610bd12857e5404e86318ca84f4d7da93f', '[\"*\"]', NULL, NULL, '2026-01-15 11:42:12', '2026-01-15 11:42:12'),
(92, 'App\\Models\\User', 6, 'auth_token', '10c943a4844c8dbab1900344f74a0a09487a6ca72eb97c145a566407f40cdc3d', '[\"*\"]', '2026-05-26 21:10:52', NULL, '2026-01-15 14:42:37', '2026-05-26 21:10:52'),
(93, 'App\\Models\\User', 12, 'auth_token', '6b99778ba5c4924fccf4ae1cd1b5a1d17a4dc399dfabec11d079c9ba2c21d0b5', '[\"*\"]', '2026-01-15 16:35:39', NULL, '2026-01-15 16:35:18', '2026-01-15 16:35:39'),
(94, 'App\\Models\\User', 6, 'auth_token', 'b34ce83965c7fd9126a288a959c01ad60ba5552e1c52e1fad68ebb14937c5ac6', '[\"*\"]', '2026-01-15 16:37:16', NULL, '2026-01-15 16:36:17', '2026-01-15 16:37:16'),
(95, 'App\\Models\\User', 6, 'auth_token', '8dfb7b10d3b96796ca01b344bfabfd277b7c6364fe0773e51f9a02ea7948b89b', '[\"*\"]', '2026-01-15 19:33:44', NULL, '2026-01-15 16:37:41', '2026-01-15 19:33:44'),
(96, 'App\\Models\\User', 12, 'auth_token', '6a2727f50e53189a6d561e8e350f417ecd2f43e6fa777d51ec1fed50375a7d29', '[\"*\"]', '2026-01-15 19:34:30', NULL, '2026-01-15 19:33:55', '2026-01-15 19:34:30'),
(97, 'App\\Models\\User', 6, 'auth_token', '9c741e584d478bcb12bc88c7923c881259a112b3d37403958aa7836c345c95c3', '[\"*\"]', '2026-01-16 11:23:55', NULL, '2026-01-15 19:35:01', '2026-01-16 11:23:55'),
(98, 'App\\Models\\User', 6, 'auth_token', 'b466e919588b3e9cc60ce8fce316625aea641b3fba8226c9b5aa966475ef3cf3', '[\"*\"]', '2026-01-15 20:29:27', NULL, '2026-01-15 19:44:56', '2026-01-15 20:29:27'),
(99, 'App\\Models\\User', 6, 'auth_token', 'a06352f86abe8e376b955bc325bed9775216ef308501a9b8e07aa2a433de0716', '[\"*\"]', '2026-01-16 10:45:48', NULL, '2026-01-16 10:44:43', '2026-01-16 10:45:48'),
(100, 'App\\Models\\User', 6, 'auth_token', '4bce4e98dfa39fe187ab038f34bd905b0ad70f331a847e8f67b6891fd413f266', '[\"*\"]', '2026-01-16 11:18:44', NULL, '2026-01-16 11:14:39', '2026-01-16 11:18:44'),
(101, 'App\\Models\\User', 6, 'auth_token', '7310213a467275865faecfb06e5bc6926ff309bd74695e59faa896d59d7700dc', '[\"*\"]', '2026-01-16 11:22:09', NULL, '2026-01-16 11:19:39', '2026-01-16 11:22:09'),
(102, 'App\\Models\\User', 6, 'auth_token', 'b3d026e93193868c2598aab7e562d49de835adfce5a0fc98ba29b7d6911b57ca', '[\"*\"]', '2026-01-16 11:33:01', NULL, '2026-01-16 11:29:36', '2026-01-16 11:33:01'),
(103, 'App\\Models\\User', 6, 'auth_token', '4a48dd64b0671c9c14b3fe093c09f64ce4a24c8f6217c3b3a128a8b0b8a82651', '[\"*\"]', '2026-01-16 11:35:50', NULL, '2026-01-16 11:33:48', '2026-01-16 11:35:50'),
(104, 'App\\Models\\User', 6, 'auth_token', '207cf0adb7ab37c5ba5cd6f7fdd32f1c9660d71239757bb1a3cec9d380510d88', '[\"*\"]', '2026-01-16 11:38:34', NULL, '2026-01-16 11:38:23', '2026-01-16 11:38:34'),
(105, 'App\\Models\\User', 6, 'auth_token', '708e4a5f1e5dadac7f3b5c5d6fa4d9f29d673648e0ed07daa4db3758262b55e2', '[\"*\"]', '2026-01-16 11:54:16', NULL, '2026-01-16 11:50:55', '2026-01-16 11:54:16'),
(106, 'App\\Models\\User', 6, 'auth_token', '5eaadcb7dc16a0b209c9eca527df1dc13ee172e7dc05845ba0982520d3c8a840', '[\"*\"]', '2026-01-16 11:55:17', NULL, '2026-01-16 11:55:10', '2026-01-16 11:55:17'),
(107, 'App\\Models\\User', 6, 'auth_token', 'efdde20341b71cd8372452f780284ef8a1ab06f43a2875d80c774b9a07f2c969', '[\"*\"]', '2026-01-16 11:59:38', NULL, '2026-01-16 11:56:15', '2026-01-16 11:59:38'),
(108, 'App\\Models\\User', 6, 'auth_token', 'b614bd960b4a1e7f5c50ec6d29e4081353443c7d6775fee13e0f79ccb2b2854b', '[\"*\"]', '2026-01-16 12:19:45', NULL, '2026-01-16 12:13:13', '2026-01-16 12:19:45'),
(109, 'App\\Models\\User', 6, 'auth_token', '87241c744ac358f42491ab3247d1e3ac3e48f58cdb109426fc41d523d503cf5e', '[\"*\"]', '2026-01-16 12:52:37', NULL, '2026-01-16 12:51:27', '2026-01-16 12:52:37'),
(110, 'App\\Models\\User', 6, 'auth_token', 'a7f64fbb08ae4b59014c231df05b458052f32aee102fa0078e4a2b0b214ce7ef', '[\"*\"]', '2026-01-16 12:53:43', NULL, '2026-01-16 12:53:08', '2026-01-16 12:53:43'),
(111, 'App\\Models\\User', 6, 'auth_token', 'dd2e8529add29a010abce4b14dfcd2208719df7fa3cf65373289f79d82be1204', '[\"*\"]', '2026-01-16 13:08:03', NULL, '2026-01-16 12:54:01', '2026-01-16 13:08:03'),
(112, 'App\\Models\\User', 6, 'auth_token', 'eaf18be559dc7690c4458c0030ea21454e0bc2009ecbfa1c99c129fae16d9075', '[\"*\"]', '2026-01-16 12:55:41', NULL, '2026-01-16 12:55:31', '2026-01-16 12:55:41'),
(113, 'App\\Models\\User', 6, 'auth_token', '098e7416d5c25efbaa29a8b8eeeb1df18b434321f40ce6a9321d4cc644333c22', '[\"*\"]', '2026-01-16 13:14:17', NULL, '2026-01-16 13:09:23', '2026-01-16 13:14:17'),
(114, 'App\\Models\\User', 6, 'auth_token', '16376cb33513da7c269b443ec3496d986192da0ff68bb8b0ad2801ef0e5e01ee', '[\"*\"]', '2026-01-16 13:18:29', NULL, '2026-01-16 13:14:49', '2026-01-16 13:18:29'),
(115, 'App\\Models\\User', 6, 'auth_token', '1305cd86d1c31876f6e4749be0843530cf226010f886640c8f9fb9126ca43329', '[\"*\"]', '2026-01-16 13:20:08', NULL, '2026-01-16 13:19:27', '2026-01-16 13:20:08'),
(116, 'App\\Models\\User', 6, 'auth_token', 'aa137feae602600c408c15d10f3596bfb5d440eaf8137c703589e37e72f7170c', '[\"*\"]', '2026-01-16 13:21:04', NULL, '2026-01-16 13:20:31', '2026-01-16 13:21:04'),
(117, 'App\\Models\\User', 6, 'auth_token', '1ddc5f3bf1c5a43dc81a1eb13978dd8e2ff84b16de3bfe403a688f27478f42b5', '[\"*\"]', '2026-01-20 16:22:14', NULL, '2026-01-16 13:21:31', '2026-01-20 16:22:14'),
(118, 'App\\Models\\User', 6, 'auth_token', 'b86ee2788035b39c9a47d7c13e3a47ae4c04a573cb3c10588cc5e81dd492d2dc', '[\"*\"]', '2026-01-16 14:13:39', NULL, '2026-01-16 14:13:22', '2026-01-16 14:13:39'),
(119, 'App\\Models\\User', 6, 'auth_token', '9a63fa3ea83436d7dac7f4df5a54dc70db0ac9cf16a07cc8e1ee116c7556b7f3', '[\"*\"]', '2026-01-16 14:14:10', NULL, '2026-01-16 14:14:07', '2026-01-16 14:14:10'),
(120, 'App\\Models\\User', 6, 'auth_token', 'a06b3eaa7f379c8795e259829d247d31ee312b5d379d77219d99c7098e776c83', '[\"*\"]', '2026-01-16 14:15:23', NULL, '2026-01-16 14:15:21', '2026-01-16 14:15:23'),
(121, 'App\\Models\\User', 6, 'auth_token', 'ac61d66a03efa58c8b7aeb6905a7df67af5a305a1a30ce4ea8f9b7a2c14b67c8', '[\"*\"]', '2026-01-16 18:23:23', NULL, '2026-01-16 18:18:42', '2026-01-16 18:23:23'),
(122, 'App\\Models\\User', 13, 'auth_token', 'f3453aaf1e8f4e977694c155c7cc910c4203927e640c664d01d039b689171059', '[\"*\"]', '2026-01-21 16:46:50', NULL, '2026-01-16 18:25:22', '2026-01-21 16:46:50'),
(123, 'App\\Models\\User', 6, 'auth_token', '8a272314cd541650d3884bca7964b12e10a3d2e478a2c84100ffd14f6bf4c50a', '[\"*\"]', '2026-01-20 13:13:59', NULL, '2026-01-19 10:30:47', '2026-01-20 13:13:59'),
(124, 'App\\Models\\User', 9, 'auth_token', 'b271a1236843b873e6f2673ae35315caa731a2209222ed5b07fb596f7422950e', '[\"*\"]', NULL, NULL, '2026-01-20 12:25:57', '2026-01-20 12:25:57'),
(125, 'App\\Models\\User', 6, 'auth_token', '26ff8c8a9cde7761432b79e4145a41c8212faab4c9220a345e07e8cfe2f9dd88', '[\"*\"]', '2026-01-20 17:31:14', NULL, '2026-01-20 17:23:42', '2026-01-20 17:31:14'),
(126, 'App\\Models\\User', 6, 'auth_token', '410edf8c94800ff2da3aed234165fd7d91173bbfa8427124dc934a989451c5cd', '[\"*\"]', '2026-01-21 11:45:27', NULL, '2026-01-20 17:26:04', '2026-01-21 11:45:27'),
(127, 'App\\Models\\User', 6, 'auth_token', '3d51849add1a9021e5dcb7970925ab39bf6659bc2a3b3582c08e3b3330bf5451', '[\"*\"]', '2026-01-20 17:47:24', NULL, '2026-01-20 17:31:32', '2026-01-20 17:47:24'),
(128, 'App\\Models\\User', 6, 'auth_token', 'ab7115137f43ec902da918ec095e8067a8bc8a0f9bc74c578e6a11d27a130e15', '[\"*\"]', '2026-01-20 17:52:13', NULL, '2026-01-20 17:49:44', '2026-01-20 17:52:13'),
(129, 'App\\Models\\User', 6, 'auth_token', 'e0e642083fad89cea8ccd797215ebab04d2f42740f70e4f87395021461fb0da4', '[\"*\"]', '2026-01-21 12:30:32', NULL, '2026-01-20 17:52:48', '2026-01-21 12:30:32'),
(130, 'App\\Models\\User', 6, 'auth_token', 'a64d9d975317cb5f85609d52b7c84f6f5ee6aaa278316e71b4f6cd51e9194ae3', '[\"*\"]', '2026-01-21 11:55:29', NULL, '2026-01-21 11:47:54', '2026-01-21 11:55:30'),
(131, 'App\\Models\\User', 9, 'auth_token', '361d6a3bfc1c4b653064ea4b121049ba911eb3da9748b664932d0b39c9e51624', '[\"*\"]', NULL, NULL, '2026-01-21 12:14:54', '2026-01-21 12:14:54'),
(132, 'App\\Models\\User', 6, 'auth_token', 'fe0b1b33ee081747ddeb8854681d710e6118aecef9313ec39ce27fc822aa0efc', '[\"*\"]', '2026-01-21 16:01:33', NULL, '2026-01-21 12:25:11', '2026-01-21 16:01:33'),
(133, 'App\\Models\\User', 12, 'auth_token', 'df0e49df91ddd96d6ff46498ed23da9d67db20dca3e8a47d8a1c4d55743fc7d6', '[\"*\"]', '2026-01-21 14:08:20', NULL, '2026-01-21 14:08:10', '2026-01-21 14:08:20'),
(134, 'App\\Models\\User', 9, 'auth_token', 'c1a56c5c5219b9bf7eb38837740d06e1222e6fcce69b087382d834f2ca98642f', '[\"*\"]', NULL, NULL, '2026-01-21 14:18:46', '2026-01-21 14:18:46'),
(135, 'App\\Models\\User', 9, 'auth_token', '5deb0c17eef47094b94dbe7ef1c0510eed0dd860c9db52ae078397bb255d7ce2', '[\"*\"]', NULL, NULL, '2026-01-21 14:27:09', '2026-01-21 14:27:09'),
(136, 'App\\Models\\User', 6, 'auth_token', 'c64a47c46a799decb8bfad97751c57b8b421f53bf070c777cc02963d05dbf46d', '[\"*\"]', '2026-01-21 14:32:51', NULL, '2026-01-21 14:32:43', '2026-01-21 14:32:51'),
(137, 'App\\Models\\User', 6, 'auth_token', '239704f21183207ee7d1085daea083d477b202bf56ebd25b86ad643ef4bb4874', '[\"*\"]', '2026-01-21 15:16:11', NULL, '2026-01-21 14:34:06', '2026-01-21 15:16:11'),
(138, 'App\\Models\\User', 6, 'auth_token', '4ec75e34633d15235b47c9c7a4b72ee72d711353b2240d4a77ba89a4486009f8', '[\"*\"]', '2026-01-21 18:53:53', NULL, '2026-01-21 15:16:47', '2026-01-21 18:53:53'),
(139, 'App\\Models\\User', 6, 'auth_token', 'e1cf335440a6ad3b9f91e563e037fd9d8e1c35d31b6cafa2e1a71e135244bf67', '[\"*\"]', '2026-01-21 15:51:59', NULL, '2026-01-21 15:46:53', '2026-01-21 15:51:59'),
(140, 'App\\Models\\User', 6, 'auth_token', '4d980695f037c35a93009c8ef6b984597a3397a3c19b6081c11157a99aa3a082', '[\"*\"]', '2026-01-21 16:02:43', NULL, '2026-01-21 16:02:41', '2026-01-21 16:02:43'),
(141, 'App\\Models\\User', 12, 'auth_token', '4212c2dbcf1ae17f2d191682c3e21c672a2963d294c1e7fd46026f9cbaeb112a', '[\"*\"]', '2026-01-21 16:03:29', NULL, '2026-01-21 16:03:27', '2026-01-21 16:03:29'),
(142, 'App\\Models\\User', 12, 'auth_token', '03d8b9d1b2fd14e5c54e7487100214e9698e9cf95ebadd15018d63974274b3b0', '[\"*\"]', '2026-01-21 18:56:37', NULL, '2026-01-21 18:56:30', '2026-01-21 18:56:37'),
(143, 'App\\Models\\User', 12, 'auth_token', '8f091faacd1d0338dffa85527c28ba22aa6205ac0ae8f2e92f253b4658d58ef8', '[\"*\"]', '2026-01-21 18:56:54', NULL, '2026-01-21 18:56:51', '2026-01-21 18:56:54'),
(144, 'App\\Models\\User', 6, 'auth_token', 'fbfc2d0c5f20af5b91007728e325abf537f2b103e01a6b0a0763020e7660486c', '[\"*\"]', '2026-01-22 18:18:46', NULL, '2026-01-22 18:18:19', '2026-01-22 18:18:46'),
(145, 'App\\Models\\User', 6, 'auth_token', '8e6fa76126140fe5fad29f34dc8a472a089b47466aace963c7cdc44e1333946c', '[\"*\"]', '2026-01-22 19:09:45', NULL, '2026-01-22 18:59:35', '2026-01-22 19:09:45'),
(146, 'App\\Models\\User', 2, 'auth_token', 'b16c849a678250b4971b685ad8372babe22e960c9ae22c29843abf8cb30d1115', '[\"*\"]', '2026-07-15 19:38:50', NULL, '2026-01-22 19:08:44', '2026-07-15 19:38:50'),
(147, 'App\\Models\\User', 15, 'auth_token', 'ba17e5b82f16333a7e315dd31a05631013c38f535b2f938e17fc8458b062235f', '[\"*\"]', '2026-02-10 14:24:11', NULL, '2026-02-10 14:20:23', '2026-02-10 14:24:11'),
(148, 'App\\Models\\User', 6, 'auth_token', '4e14e8edca444196155373004e13bf729f6cea077d8beefab2c848eb36611a80', '[\"*\"]', '2026-02-12 14:20:07', NULL, '2026-02-10 14:24:52', '2026-02-12 14:20:07'),
(149, 'App\\Models\\User', 6, 'auth_token', '46e8b459e699b9cacdcf29e2e3f028be834c0f94fa9fd46288f776dcece6b269', '[\"*\"]', '2026-02-11 12:08:24', NULL, '2026-02-10 15:11:42', '2026-02-11 12:08:24'),
(150, 'App\\Models\\User', 6, 'auth_token', 'f563d3c566d0c4b33cfa2818d35b5fcb99f3cdcfc558c92bf40b01c10c57747d', '[\"*\"]', '2026-02-11 18:36:33', NULL, '2026-02-11 11:35:12', '2026-02-11 18:36:33'),
(151, 'App\\Models\\User', 16, 'auth_token', '25ada6d9aa3ddb829730311f05e7231f8c45d34714dc392ade95a589c8eb477d', '[\"*\"]', '2026-02-11 11:37:17', NULL, '2026-02-11 11:37:09', '2026-02-11 11:37:17'),
(152, 'App\\Models\\User', 6, 'auth_token', 'dbce46e9e0ac62b20535cda8ec5740fa8879a80451f4c231db8156744c937f03', '[\"*\"]', '2026-02-11 12:27:38', NULL, '2026-02-11 12:21:46', '2026-02-11 12:27:38'),
(153, 'App\\Models\\User', 17, 'auth_token', 'bd62a5250b78c2e4fba4dea39515b61a805314b9438b9be3a64c1ce169715908', '[\"*\"]', '2026-02-11 12:35:44', NULL, '2026-02-11 12:34:21', '2026-02-11 12:35:44'),
(154, 'App\\Models\\User', 6, 'auth_token', '4e33519e3f5059ea48cdd5d6bb7242fb59fda50bfe818c99cd45064455e0aef1', '[\"*\"]', '2026-02-11 16:18:18', NULL, '2026-02-11 16:16:44', '2026-02-11 16:18:18'),
(155, 'App\\Models\\User', 6, 'auth_token', '3ecd1a0e3b5237be7e5c730746cfbd8dd944d7db96d345198b347e6abde405ba', '[\"*\"]', '2026-02-11 17:41:39', NULL, '2026-02-11 16:18:36', '2026-02-11 17:41:39'),
(156, 'App\\Models\\User', 17, 'auth_token', '3272aece574e9883de4de76069edfe81f434a34582e3d3e4b46db0069bb4c6ab', '[\"*\"]', '2026-02-11 17:42:09', NULL, '2026-02-11 17:41:54', '2026-02-11 17:42:09'),
(157, 'App\\Models\\User', 6, 'auth_token', '786150dc2614db45667ab8aa394ffded86975b66652a7064eb4a520b2563e609', '[\"*\"]', '2026-02-11 18:08:04', NULL, '2026-02-11 17:42:20', '2026-02-11 18:08:04'),
(158, 'App\\Models\\User', 18, 'auth_token', '31a547977e09727116af7d0f44ff08f5fc7157254a5339bf713d38d9dc18566f', '[\"*\"]', '2026-02-13 17:48:55', NULL, '2026-02-11 18:04:14', '2026-02-13 17:48:55'),
(159, 'App\\Models\\User', 6, 'auth_token', 'dd7e94d567346e99b76699b5a380d51b72564f9f0b8cf34d7672e58db0263cec', '[\"*\"]', '2026-02-11 18:09:20', NULL, '2026-02-11 18:09:01', '2026-02-11 18:09:20'),
(160, 'App\\Models\\User', 6, 'auth_token', '32f3c407fa64dfaa20b75ed35ecacbc265340e37010fc35e9fbf4ef74d2e77cc', '[\"*\"]', '2026-02-11 18:35:47', NULL, '2026-02-11 18:10:32', '2026-02-11 18:35:47'),
(161, 'App\\Models\\User', 6, 'auth_token', '6d4cd5211145707a2ff4e976cb2c66a7cae8353eda6d2abe29182599e40ca7ae', '[\"*\"]', '2026-02-11 18:47:33', NULL, '2026-02-11 18:47:28', '2026-02-11 18:47:33'),
(162, 'App\\Models\\User', 6, 'auth_token', 'b6dea2e9baf8261a0d213f84e9230a55fff405286a90a026d073fcba7b5b41c7', '[\"*\"]', '2026-02-13 14:54:11', NULL, '2026-02-11 18:53:00', '2026-02-13 14:54:11'),
(163, 'App\\Models\\User', 6, 'auth_token', '6f20f82bf403c3a2491994badfd18afd75c7035bb4bb3520991b9137c5c3e3fa', '[\"*\"]', '2026-02-12 10:48:51', NULL, '2026-02-12 10:47:33', '2026-02-12 10:48:51'),
(164, 'App\\Models\\User', 17, 'auth_token', 'adcfa9a136b292c4a390c3ab67d1be0a5a209ad6d07fad0fa66923404a31cda8', '[\"*\"]', '2026-02-16 12:08:32', NULL, '2026-02-12 10:49:34', '2026-02-16 12:08:32'),
(165, 'App\\Models\\User', 19, 'auth_token', '57c26636e95f2a87f529a09fccb1fb2ab5cb6c2b1627ffe8184bce4544c11f64', '[\"*\"]', '2026-02-12 10:50:33', NULL, '2026-02-12 10:49:52', '2026-02-12 10:50:33'),
(166, 'App\\Models\\User', 20, 'auth_token', '62eab09002965f046e4950f4602d048f87dc173ec5f7658a8ddcc4dc34abf4f1', '[\"*\"]', '2026-02-16 12:48:47', NULL, '2026-02-12 10:51:31', '2026-02-16 12:48:47'),
(167, 'App\\Models\\User', 6, 'auth_token', '652ffd2ad716d79e31abc62c4c1028a354bfb5083de01b9dc40b7a631389bdf6', '[\"*\"]', '2026-02-16 11:53:28', NULL, '2026-02-12 12:43:51', '2026-02-16 11:53:28'),
(168, 'App\\Models\\User', 6, 'auth_token', '9edcdb2ce41cd26291e54e6acb387f9ba9fdf6afac365b93c5311729b3193bd3', '[\"*\"]', '2026-02-13 14:44:33', NULL, '2026-02-13 13:21:05', '2026-02-13 14:44:33'),
(169, 'App\\Models\\User', 6, 'auth_token', '687c22e6bfb02c390b6e4992cbdc6b235abbbfa786e67fedce7970264602307b', '[\"*\"]', '2026-02-13 15:54:36', NULL, '2026-02-13 14:44:51', '2026-02-13 15:54:36'),
(170, 'App\\Models\\User', 6, 'auth_token', '34e3d4f785e7a86561c74e440e60798c6e885eb8913afd4d2abc5512410710d4', '[\"*\"]', '2026-02-16 14:17:11', NULL, '2026-02-13 15:54:47', '2026-02-16 14:17:11'),
(171, 'App\\Models\\User', 18, 'auth_token', 'dcfabd3fed003ed7bbdd51bfe8666ed1e4ca54a0ce07de9aa184658b364721bc', '[\"*\"]', '2026-02-16 19:11:33', NULL, '2026-02-13 19:40:24', '2026-02-16 19:11:33'),
(172, 'App\\Models\\User', 6, 'auth_token', '671e7072f1c3daf7ae1343e5d99143afe93cdd7434c83e601a807d5bbf8156ed', '[\"*\"]', '2026-02-17 20:15:32', NULL, '2026-02-17 17:15:40', '2026-02-17 20:15:32'),
(173, 'App\\Models\\User', 6, 'auth_token', 'bb0ec18c69f58ebe8df2455576762ba04f9427ebe5a8fe2b82a53c7f0573ad3c', '[\"*\"]', '2026-02-17 20:16:24', NULL, '2026-02-17 20:16:22', '2026-02-17 20:16:24'),
(174, 'App\\Models\\User', 6, 'auth_token', 'fd47e0759c9726d8d0fde3f29f44bb8ebf40e640de15920bafff1c6bb7bc0e78', '[\"*\"]', '2026-02-23 11:58:35', NULL, '2026-02-19 18:46:22', '2026-02-23 11:58:35'),
(175, 'App\\Models\\User', 6, 'auth_token', 'dc705697967a3661a8d61dc25ae936e12d876ca368ac5cf5942fa8790f749ce1', '[\"*\"]', '2026-03-05 14:39:50', NULL, '2026-02-23 18:24:42', '2026-03-05 14:39:50'),
(176, 'App\\Models\\User', 6, 'auth_token', '0e1f8ed3ace0bea12b9ab9eb65c13b8966d446c6cf46580c6e99727f1e03732d', '[\"*\"]', '2026-03-05 15:02:36', NULL, '2026-02-23 18:58:22', '2026-03-05 15:02:36'),
(177, 'App\\Models\\User', 6, 'auth_token', '9af0fbf97b8fe0c13531d43827e0ca61d212a43daa59dac27988ed6cc0fb450c', '[\"*\"]', '2026-02-24 12:35:36', NULL, '2026-02-24 10:44:30', '2026-02-24 12:35:36'),
(178, 'App\\Models\\User', 6, 'auth_token', '8b48b755c9cd751b38a3373292c5e91b69fcc7f0e3de2a059d3e909613fbc993', '[\"*\"]', '2026-03-10 15:26:25', NULL, '2026-03-05 15:04:10', '2026-03-10 15:26:25'),
(179, 'App\\Models\\User', 6, 'auth_token', 'c9517042bbe52c0345ff32a4a1e05cd120e3b2326423203f647f09a00f3c4926', '[\"*\"]', '2026-03-05 18:13:28', NULL, '2026-03-05 18:13:15', '2026-03-05 18:13:28'),
(180, 'App\\Models\\User', 12, 'auth_token', 'd8d36406e75e2f39dd6280958682d8da27656a2bbd1ed5270047b4ac85b1e298', '[\"*\"]', '2026-03-11 12:21:00', NULL, '2026-03-11 12:20:48', '2026-03-11 12:21:00'),
(181, 'App\\Models\\User', 6, 'auth_token', '5ea2743934a838c62e27d0ff5529375a024521c3f5411d7680c0de3f0adf205b', '[\"*\"]', '2026-03-11 13:47:01', NULL, '2026-03-11 13:40:55', '2026-03-11 13:47:01'),
(182, 'App\\Models\\User', 12, 'auth_token', 'df9033b2efa469441fdc9c09f1af2c1c3c103227ef74e254371331e8006de070', '[\"*\"]', '2026-03-11 13:48:56', NULL, '2026-03-11 13:48:54', '2026-03-11 13:48:56'),
(183, 'App\\Models\\User', 12, 'auth_token', '529c2b850309f2352a01275d38c55ae5580c7cc97624b590b60d052bc7385ab1', '[\"*\"]', '2026-03-12 09:16:57', NULL, '2026-03-11 15:12:48', '2026-03-12 09:16:57'),
(184, 'App\\Models\\User', 6, 'auth_token', 'e6626eb09984289ede442f7bc951e227af13c2b85eedb0b58679dbeaf4a02b71', '[\"*\"]', '2026-03-11 15:46:59', NULL, '2026-03-11 15:26:36', '2026-03-11 15:46:59'),
(185, 'App\\Models\\User', 12, 'auth_token', '4e0e45ac32a4332096c633ca808b57937beb3213ae01becaffd9c88f6831bbfe', '[\"*\"]', '2026-03-13 11:33:49', NULL, '2026-03-12 09:18:33', '2026-03-13 11:33:49'),
(186, 'App\\Models\\User', 6, 'auth_token', '84829e5f662bdc0a87f0f37575823227bab203f07ee9cf1e2cb3b3c341867ae1', '[\"*\"]', '2026-03-12 17:17:26', NULL, '2026-03-12 09:56:59', '2026-03-12 17:17:26'),
(187, 'App\\Models\\User', 6, 'auth_token', '17b2d5d7d25b3f2e30a244ac4a596fcd1b92865620db2b6ce7379d510b5b6aa5', '[\"*\"]', '2026-03-13 13:09:45', NULL, '2026-03-12 13:30:51', '2026-03-13 13:09:45'),
(188, 'App\\Models\\User', 1, 'auth_token', 'e869bfcca9cb6aea6ea81236388cffd4f747ba99b82fe6cd5fa6a8c04e6b3c98', '[\"*\"]', '2026-03-12 13:51:41', NULL, '2026-03-12 13:45:47', '2026-03-12 13:51:41'),
(189, 'App\\Models\\User', 6, 'auth_token', '5ab4d18fc5497a0ce9d109e1416109f974b626962f0d4ac8fa33b54720f92ce8', '[\"*\"]', '2026-03-12 17:34:00', NULL, '2026-03-12 16:34:48', '2026-03-12 17:34:00'),
(190, 'App\\Models\\User', 17, 'auth_token', 'd220727d0b378638e6d654d942a65c4867f7d8d61925ad64138d903d9413999a', '[\"*\"]', '2026-03-12 16:46:14', NULL, '2026-03-12 16:36:41', '2026-03-12 16:46:14'),
(191, 'App\\Models\\User', 18, 'auth_token', '757fbbb30f93890ff18122f31aadf70b8b83c5baa2a688723f257928b6317e57', '[\"*\"]', '2026-03-13 10:15:23', NULL, '2026-03-12 16:49:22', '2026-03-13 10:15:23'),
(192, 'App\\Models\\User', 6, 'auth_token', '856af6ac7e23fc5b7cd77f6d9d2d9e24329dd07f0049606ed529fda7a766f43c', '[\"*\"]', '2026-03-13 10:55:47', NULL, '2026-03-13 10:16:25', '2026-03-13 10:55:47'),
(193, 'App\\Models\\User', 6, 'auth_token', 'feb21814ba73fd5b65ed44ffe1ff216d29007fb3186ae770444d1913b616a4d4', '[\"*\"]', '2026-03-13 13:08:51', NULL, '2026-03-13 11:00:51', '2026-03-13 13:08:51'),
(194, 'App\\Models\\User', 12, 'auth_token', '617fcd0771e17688d4dd5d6a3ff5b4667547d8f0e1e224bd87e954c41636fae6', '[\"*\"]', '2026-03-13 11:47:54', NULL, '2026-03-13 11:34:16', '2026-03-13 11:47:54'),
(195, 'App\\Models\\User', 6, 'auth_token', '11cc841223167cac226eade58503cd84a510a9914ebea110e2c4187dbd2436bf', '[\"*\"]', '2026-03-13 11:50:31', NULL, '2026-03-13 11:48:14', '2026-03-13 11:50:31'),
(196, 'App\\Models\\User', 18, 'auth_token', 'fe9affe539f9be0efbdd72dd8e9c281edb5ba314ca1b33403b36b4bdc80349e5', '[\"*\"]', '2026-03-13 12:16:29', NULL, '2026-03-13 11:50:00', '2026-03-13 12:16:29'),
(197, 'App\\Models\\User', 6, 'auth_token', '86f39f793fd43fb751962393e8ad0027c5710f04a12ffec84ebd0dd6dd591f11', '[\"*\"]', '2026-03-13 11:51:00', NULL, '2026-03-13 11:50:44', '2026-03-13 11:51:00'),
(198, 'App\\Models\\User', 6, 'auth_token', 'a64214780ea35e148618efc920925344eea1b4d4a9b3553b6fc23cd0ab801dc8', '[\"*\"]', '2026-03-13 11:52:38', NULL, '2026-03-13 11:52:16', '2026-03-13 11:52:38'),
(199, 'App\\Models\\User', 6, 'auth_token', 'f16aeb54f811048601a3cd5948381468589f8e5d8ee0593d2f23903363d9e8ab', '[\"*\"]', '2026-03-13 13:38:25', NULL, '2026-03-13 11:53:23', '2026-03-13 13:38:25'),
(200, 'App\\Models\\User', 3, 'auth_token', '96b9f0ce4d33f58f3229cd91370967c3b19e07a0eed0f1078dcf51a11244dbc6', '[\"*\"]', '2026-03-13 12:27:06', NULL, '2026-03-13 12:26:54', '2026-03-13 12:27:06'),
(201, 'App\\Models\\User', 1, 'auth_token', '62ea5c63aa397e43c93bfc3b19025a7d8dfd019ae099793b6c69d0e6dbae9687', '[\"*\"]', '2026-03-16 10:27:03', NULL, '2026-03-13 13:45:37', '2026-03-16 10:27:03'),
(202, 'App\\Models\\User', 2, 'auth_token', '33685d04f70a8aa57a64810337c2ec727e1319f837d9a3b4016fb0ab4b9a714e', '[\"*\"]', '2026-03-13 13:47:20', NULL, '2026-03-13 13:46:44', '2026-03-13 13:47:20'),
(203, 'App\\Models\\User', 2, 'auth_token', 'e7a833ae6deb1dbc2671cd46c7d9869b5f290f24a07dd57276250d1bd0a9a838', '[\"*\"]', '2026-03-13 13:48:09', NULL, '2026-03-13 13:47:46', '2026-03-13 13:48:09'),
(204, 'App\\Models\\User', 2, 'auth_token', '8d55685c825ccf3b9028696c855348b7350647057b53bae2a68175ae405c4e26', '[\"*\"]', '2026-03-16 15:37:38', NULL, '2026-03-13 13:49:26', '2026-03-16 15:37:38'),
(205, 'App\\Models\\User', 1, 'auth_token', '12e5f9e2528e2f3ab7dc83d15df29da52b3c0c5f5acc638ab0d43e3fb8452516', '[\"*\"]', '2026-03-13 14:02:29', NULL, '2026-03-13 14:02:17', '2026-03-13 14:02:29'),
(206, 'App\\Models\\User', 1, 'auth_token', '6399b5a8527b3edf7974b2c17b6bca659220f1d342e5dd616e2c2085d4dbe5ed', '[\"*\"]', '2026-03-13 16:32:21', NULL, '2026-03-13 14:21:24', '2026-03-13 16:32:21'),
(207, 'App\\Models\\User', 2, 'auth_token', 'b7447e9e5d58f1fcbb255aca0192075e480406cb2a8e3f7848c5854fb48eff47', '[\"*\"]', '2026-03-13 17:18:19', NULL, '2026-03-13 17:18:05', '2026-03-13 17:18:19'),
(208, 'App\\Models\\User', 1, 'auth_token', 'eb49d12c34156b91145e73727813070ed692824f1e66be60decb2540763fed34', '[\"*\"]', '2026-03-16 15:01:17', NULL, '2026-03-14 11:47:44', '2026-03-16 15:01:17'),
(209, 'App\\Models\\User', 2, 'auth_token', 'fcd766d50b26604f8bbb22ecaa8b8a04e4e307a3817f713495553fc4b86ae933', '[\"*\"]', '2026-03-14 13:28:19', NULL, '2026-03-14 11:48:24', '2026-03-14 13:28:19'),
(210, 'App\\Models\\User', 2, 'auth_token', '089ae07446c36156951d9e9b8c95db2c6a15427b8675bad263d864ca893e574c', '[\"*\"]', '2026-03-15 13:41:43', NULL, '2026-03-15 13:07:57', '2026-03-15 13:41:43'),
(211, 'App\\Models\\User', 1, 'auth_token', 'c578fc984ee1a4cecc2b93c3e157f8142258b9e431f0c82264d528b7eced1178', '[\"*\"]', '2026-03-15 19:51:21', NULL, '2026-03-15 19:48:58', '2026-03-15 19:51:21'),
(212, 'App\\Models\\User', 2, 'auth_token', '7cc54495e7ab5ce69241ea6a27cab95e5ec24863daac97d06bc99c6f7198daf1', '[\"*\"]', '2026-03-16 10:26:33', NULL, '2026-03-16 09:18:12', '2026-03-16 10:26:33'),
(213, 'App\\Models\\User', 1, 'auth_token', '50d00847273e5ebb7164e052bde1c877490f3c605edbd728dfb6ed70686a010a', '[\"*\"]', '2026-03-16 16:55:39', NULL, '2026-03-16 13:56:44', '2026-03-16 16:55:39'),
(214, 'App\\Models\\User', 1, 'auth_token', '52f4cb2345b039425b3cb948b998b8f4253963fe2822046ff39503f1c5181909', '[\"*\"]', '2026-03-16 15:16:14', NULL, '2026-03-16 15:01:29', '2026-03-16 15:16:14'),
(215, 'App\\Models\\User', 1, 'auth_token', '07aeb7716ccb6ada3677bf99769a397498034158dae52137e1c82674e4d0ed3b', '[\"*\"]', '2026-03-16 15:03:32', NULL, '2026-03-16 15:01:46', '2026-03-16 15:03:32'),
(216, 'App\\Models\\User', 2, 'auth_token', '49d80398752127313c7604881e2d92378412406503f11473fa3c7c784d4cf5f1', '[\"*\"]', '2026-03-17 13:10:03', NULL, '2026-03-16 15:38:20', '2026-03-17 13:10:03'),
(217, 'App\\Models\\User', 1, 'auth_token', '2d3b863c1c230cd700ccb33e64b8457b6f3ba4715d4d2a037d3af2025f4546c0', '[\"*\"]', '2026-03-16 19:18:28', NULL, '2026-03-16 17:04:05', '2026-03-16 19:18:28'),
(218, 'App\\Models\\User', 2, 'auth_token', 'fd9c3f7d41469cb161506cbb59298187e7018743e441d3651cf30f8e97889a93', '[\"*\"]', '2026-03-16 19:20:18', NULL, '2026-03-16 17:05:33', '2026-03-16 19:20:18'),
(219, 'App\\Models\\User', 2, 'auth_token', 'c0d0eda00d175455865804ffa108e4385c18c58207e129795711fba7852015f6', '[\"*\"]', '2026-03-16 19:19:58', NULL, '2026-03-16 19:19:56', '2026-03-16 19:19:58'),
(220, 'App\\Models\\User', 2, 'auth_token', '82d62f1a82d245adcf87528c9a39a604605a6f301557b4a644217fd50fb6a340', '[\"*\"]', '2026-03-20 15:10:14', NULL, '2026-03-20 14:47:48', '2026-03-20 15:10:14'),
(221, 'App\\Models\\User', 1, 'auth_token', '6252a223373cae75b480aac640387364a21643db47b0d114a1d274791b9e0805', '[\"*\"]', '2026-03-20 15:10:12', NULL, '2026-03-20 14:48:35', '2026-03-20 15:10:12'),
(222, 'App\\Models\\User', 2, 'auth_token', 'b80b4cd524bf72092bf93a6086b0d23a144086ac74935355827d66273568b572', '[\"*\"]', '2026-03-23 15:55:57', NULL, '2026-03-23 15:18:48', '2026-03-23 15:55:57'),
(223, 'App\\Models\\User', 1, 'auth_token', '5f0009007d326c0cd6eed5ac14e96fce0d3104b6fd1d20a8aa7a2583d1948173', '[\"*\"]', '2026-03-23 18:09:53', NULL, '2026-03-23 15:20:35', '2026-03-23 18:09:53'),
(224, 'App\\Models\\User', 2, 'auth_token', 'f565ce8281c563df3c063c1487d556ca697b75e61acc2aca66bac9629deb48bc', '[\"*\"]', '2026-03-26 16:09:04', NULL, '2026-03-25 13:10:22', '2026-03-26 16:09:04'),
(225, 'App\\Models\\User', 2, 'auth_token', '382d6004f47ddf1b3a0ce777daa52ed2cc1371e223a0bdcdbde9808bfdbbb667', '[\"*\"]', '2026-04-01 21:51:38', NULL, '2026-04-01 21:12:37', '2026-04-01 21:51:38'),
(226, 'App\\Models\\User', 1, 'auth_token', 'bcf96b55a1459b4b63311b18147dc9d0baaca29ba957140e4e13f9e0d6101050', '[\"*\"]', '2026-04-01 21:14:07', NULL, '2026-04-01 21:13:41', '2026-04-01 21:14:07'),
(227, 'App\\Models\\User', 1, 'auth_token', '47fba293c05796fe8fd7631db0ccb1339c2f2c63f0ea7b367b45160ce549d594', '[\"*\"]', '2026-04-01 23:40:51', NULL, '2026-04-01 21:13:56', '2026-04-01 23:40:51'),
(228, 'App\\Models\\User', 2, 'auth_token', '7833bbee1b2d24bfefd46109ad323bf347d63d16f37ea4839bb009da3d9636ba', '[\"*\"]', '2026-04-07 00:50:52', NULL, '2026-04-06 17:36:04', '2026-04-07 00:50:52'),
(229, 'App\\Models\\User', 1, 'auth_token', '935a009ce07c113af1e8d96d097a61d51ed1686a53ff97452123e7e63ccb94ea', '[\"*\"]', '2026-04-06 22:48:14', NULL, '2026-04-06 17:37:06', '2026-04-06 22:48:14'),
(230, 'App\\Models\\User', 1, 'auth_token', 'fa2804fdef0ca6f6f0fae86188030496b37c2426f576d1fc24048879abeba134', '[\"*\"]', '2026-04-06 21:19:36', NULL, '2026-04-06 17:50:03', '2026-04-06 21:19:36'),
(231, 'App\\Models\\User', 1, 'auth_token', 'db9f24d0ed54cf1e8af2292300cdbd69711671b9189c71359469a7432960834e', '[\"*\"]', '2026-04-07 00:53:37', NULL, '2026-04-06 23:55:44', '2026-04-07 00:53:37'),
(232, 'App\\Models\\User', 2, 'auth_token', '7591d0ec5c6813176581e032f349b77a92804cfd6ba69064a71d7aa0145bdb87', '[\"*\"]', '2026-04-29 16:02:00', NULL, '2026-04-29 16:01:47', '2026-04-29 16:02:00'),
(233, 'App\\Models\\User', 2, 'auth_token', 'efee0c41f0722b2ea45e9f79d9b80a8aad5ea21378768a4269dfd56052b21d59', '[\"*\"]', '2026-04-29 16:08:27', NULL, '2026-04-29 16:02:19', '2026-04-29 16:08:27'),
(234, 'App\\Models\\User', 2, 'auth_token', '12c3cd87b89ae80b422e173f0f14999f13bdc3f91338d5bb32707f49373744eb', '[\"*\"]', '2026-04-29 16:20:22', NULL, '2026-04-29 16:08:41', '2026-04-29 16:20:22'),
(235, 'App\\Models\\User', 1, 'auth_token', '9d193d787e6010598b1f4edfea8ff254f05b9ec097962f61d55cf208258e7365', '[\"*\"]', '2026-04-29 18:35:49', NULL, '2026-04-29 16:22:53', '2026-04-29 18:35:49'),
(236, 'App\\Models\\User', 2, 'auth_token', '666a4358f712fd34f59769d7aa16cf3b890aa82625da16efb5c0f6af2d1bdfaf', '[\"*\"]', NULL, NULL, '2026-04-29 18:31:30', '2026-04-29 18:31:30'),
(237, 'App\\Models\\User', 1, 'auth_token', '910aab8c632647a65cd6cf41e85258d601a3facb73b0cdd01110bf316d3edbff', '[\"*\"]', '2026-04-29 18:36:39', NULL, '2026-04-29 18:36:15', '2026-04-29 18:36:39'),
(238, 'App\\Models\\User', 1, 'auth_token', 'aacb1e0b1e8fb99a80433432af907c0eb9f3e248224da4eef56d78128c8f1161', '[\"*\"]', '2026-04-29 18:39:29', NULL, '2026-04-29 18:39:27', '2026-04-29 18:39:29'),
(239, 'App\\Models\\User', 2, 'auth_token', 'e748969d6b1f708ef4d3193b1c01063139a508bdfeda0754762224a273572169', '[\"*\"]', '2026-04-29 21:14:06', NULL, '2026-04-29 18:53:18', '2026-04-29 21:14:06'),
(240, 'App\\Models\\User', 1, 'auth_token', '036c720bdca6d44758be1b7065b5842590448a5f45e9991326970bdc4a1226f7', '[\"*\"]', '2026-04-29 22:16:10', NULL, '2026-04-29 19:00:43', '2026-04-29 22:16:10'),
(241, 'App\\Models\\User', 2, 'auth_token', '18ad8f9825ccd7ad4621974560cde6c6ee8f7e59e6550ae4d193ceef66e1f92e', '[\"*\"]', '2026-05-01 22:59:25', NULL, '2026-05-01 22:59:23', '2026-05-01 22:59:25'),
(242, 'App\\Models\\User', 4, 'auth_token', 'b2c832c155924fd03e47ffd57634709dec3a9973798511c51f0d6895e227388c', '[\"*\"]', '2026-07-25 11:46:43', NULL, '2026-05-05 02:35:12', '2026-07-25 11:46:43'),
(243, 'App\\Models\\User', 5, 'auth_token', '9ad4e7e850638608781bf8fa315289e55d1754499e06fa376ff2a23bf2eecb96', '[\"*\"]', '2026-05-08 00:32:54', NULL, '2026-05-07 19:03:33', '2026-05-08 00:32:54'),
(244, 'App\\Models\\User', 2, 'auth_token', '197f52d21edb4c77d4319130c1f6525dacbaf8ad902b0574357f38a7eafd5e96', '[\"*\"]', '2026-05-08 19:57:37', NULL, '2026-05-07 19:36:34', '2026-05-08 19:57:37'),
(245, 'App\\Models\\User', 8, 'auth_token', '88ea4fa150ed41c92002b08978c0d01d2fd6ea18939ce8656893ae8aa8775cbd', '[\"*\"]', '2026-05-11 16:01:24', NULL, '2026-05-07 20:36:25', '2026-05-11 16:01:24'),
(246, 'App\\Models\\User', 5, 'auth_token', '6fdfa732104620f1d52fdfa5a97a281ccbe5901fb171f3435daf92698a7cad9b', '[\"*\"]', '2026-05-11 22:03:07', NULL, '2026-05-08 00:33:07', '2026-05-11 22:03:07'),
(247, 'App\\Models\\User', 2, 'auth_token', 'd7ca5d6e3dc4b05378ef0d17664185a82e89e4197c15a1bc49553954aae1062e', '[\"*\"]', '2026-05-08 23:34:10', NULL, '2026-05-08 20:12:41', '2026-05-08 23:34:10'),
(248, 'App\\Models\\User', 5, 'auth_token', '020b9760ece9df5b18a740ef0eee72c6d7f6f906c64681cbadb150589fc9c27c', '[\"*\"]', '2026-05-08 21:43:47', NULL, '2026-05-08 21:27:54', '2026-05-08 21:43:47'),
(249, 'App\\Models\\User', 8, 'auth_token', '1de43c55c7706e99812634b4a8364530617b216f94136dbd0d7bc51976917a5f', '[\"*\"]', '2026-05-11 22:03:44', NULL, '2026-05-11 22:03:38', '2026-05-11 22:03:44'),
(250, 'App\\Models\\User', 8, 'auth_token', '939d750cf8280109b782af3dd24ebbaf7bb4bcb83f826ab5369570c38c7ea30d', '[\"*\"]', '2026-05-11 22:30:05', NULL, '2026-05-11 22:07:41', '2026-05-11 22:30:05'),
(251, 'App\\Models\\User', 2, 'auth_token', '29c567d2cdb3e5557ff89a78514782e50c13e159d13fe33953b9a49f14e8bbd9', '[\"*\"]', '2026-05-26 22:07:23', NULL, '2026-05-26 18:38:43', '2026-05-26 22:07:23'),
(252, 'App\\Models\\User', 2, 'auth_token', 'af9a06e1ad76e81d6514b275b0c15e951cc7f6d4601168235bd532216c71cf54', '[\"*\"]', '2026-05-26 19:43:40', NULL, '2026-05-26 19:37:08', '2026-05-26 19:43:40'),
(253, 'App\\Models\\User', 5, 'auth_token', '4ee1ceba949cead196bb3d54472da20e6d23e812bf5494b32a8b8951da09b545', '[\"*\"]', '2026-05-26 20:03:28', NULL, '2026-05-26 19:58:55', '2026-05-26 20:03:28'),
(254, 'App\\Models\\User', 2, 'auth_token', '31881cd06c37862015e369ffb4471cd228564d5e1035635fdf34f9fb268abe5d', '[\"*\"]', '2026-05-28 16:50:49', NULL, '2026-05-28 16:39:59', '2026-05-28 16:50:49'),
(255, 'App\\Models\\User', 2, 'auth_token', '67dbf99ec78f65f847119948e120fb72f97926201e9869181c65d72acfc5ee71', '[\"*\"]', '2026-05-29 16:37:49', NULL, '2026-05-29 16:34:21', '2026-05-29 16:37:49'),
(256, 'App\\Models\\User', 2, 'auth_token', '73fdc864a255516e0a4f6bc4f65f765ccf9a46cf1b15618b467d0922a9a0374e', '[\"*\"]', '2026-05-29 19:27:44', NULL, '2026-05-29 17:11:13', '2026-05-29 19:27:44'),
(257, 'App\\Models\\User', 2, 'auth_token', '1cbf87ab29080f56efe419c50a215f0eb6f8846e1ef4c92a05cf9951ac339aa5', '[\"*\"]', '2026-05-29 19:35:00', NULL, '2026-05-29 19:34:47', '2026-05-29 19:35:00');
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(258, 'App\\Models\\User', 2, 'auth_token', '933d8c6a5a295780eef28888859ea21ccbb31432df7ad88c72d10a0e34273c8d', '[\"*\"]', '2026-06-01 21:08:06', NULL, '2026-06-01 20:59:51', '2026-06-01 21:08:06'),
(259, 'App\\Models\\User', 2, 'auth_token', 'fe6d94749a4d322a82f89ac41dce8f40ee843c64d2bf334faf795a12b18e60fb', '[\"*\"]', '2026-06-03 00:47:21', NULL, '2026-06-03 00:47:11', '2026-06-03 00:47:21'),
(260, 'App\\Models\\User', 2, 'auth_token', '20ccdc27eca2c9c0d633458469d3ca705bbdb18f98abc09e1a2047b3cb7595f6', '[\"*\"]', '2026-06-03 00:47:40', NULL, '2026-06-03 00:47:19', '2026-06-03 00:47:40'),
(261, 'App\\Models\\User', 2, 'auth_token', '8d87ea638c38269e8a89b170637a1321d5eecb41ebe7e4375a175484085d970f', '[\"*\"]', '2026-06-03 22:54:42', NULL, '2026-06-03 22:54:35', '2026-06-03 22:54:42'),
(262, 'App\\Models\\User', 2, 'auth_token', '03d1196f2821ad0f6fd5ce0264d9d642a9fae65b9642e8a9a26bbf7e763559e9', '[\"*\"]', '2026-06-04 20:50:08', NULL, '2026-06-04 20:49:32', '2026-06-04 20:50:08'),
(263, 'App\\Models\\User', 8, 'auth_token', '13d0f2f07cbb4cd462096cde6cba7ea9497cfd71e3c69f97b51bfb02458b63b1', '[\"*\"]', '2026-06-04 21:15:48', NULL, '2026-06-04 20:54:43', '2026-06-04 21:15:48'),
(264, 'App\\Models\\User', 8, 'auth_token', 'f8dbc9373eac67084d30f230568c77d6155ead6c0bff4954679639141adad0fd', '[\"*\"]', '2026-06-04 21:12:35', NULL, '2026-06-04 21:06:23', '2026-06-04 21:12:35'),
(265, 'App\\Models\\User', 8, 'auth_token', '062b48561c5f09054912034a583d07ae37d9905937aff6e8c8394d904792dd4c', '[\"*\"]', '2026-06-04 21:25:20', NULL, '2026-06-04 21:16:23', '2026-06-04 21:25:20'),
(266, 'App\\Models\\User', 8, 'auth_token', 'c4a47249cc5cc0ec5956f34a0b30b24e8ec4b9312bc7aeb7387638c91659a98c', '[\"*\"]', '2026-06-05 17:56:49', NULL, '2026-06-05 15:30:58', '2026-06-05 17:56:49'),
(267, 'App\\Models\\User', 8, 'auth_token', '25ad85d1136a335614d9d55ac5d6ec2199857a9594fbafde95dbfe2167f57038', '[\"*\"]', '2026-06-05 17:59:23', NULL, '2026-06-05 17:59:21', '2026-06-05 17:59:23'),
(268, 'App\\Models\\User', 8, 'auth_token', '4f67614aee4cdf25770828799fa8506850d1aba7a6c032bbf8d5b9fc1e52d1c3', '[\"*\"]', '2026-06-05 18:46:10', NULL, '2026-06-05 18:34:18', '2026-06-05 18:46:10'),
(269, 'App\\Models\\User', 2, 'auth_token', '8434543c482a83b7104cf6eab79d320559c999160835b8ac6f6c77c4b8dbf354', '[\"*\"]', '2026-06-30 21:29:06', NULL, '2026-06-29 22:21:03', '2026-06-30 21:29:06'),
(270, 'App\\Models\\User', 1, 'auth_token', '6c2d349335047892e1ac425ea36b2e2c1d77ef8553debf7589e23576cde6b84e', '[\"*\"]', '2026-06-30 18:40:56', NULL, '2026-06-30 18:40:54', '2026-06-30 18:40:56'),
(271, 'App\\Models\\User', 2, 'auth_token', 'ebd32bcfa342fbba60b42b257892150077df8242e5b170c4c0d10ba3ed39a5bf', '[\"*\"]', '2026-06-30 21:29:33', NULL, '2026-06-30 19:06:31', '2026-06-30 21:29:33'),
(272, 'App\\Models\\User', 9, 'auth_token', 'c9b924cdf1ca332b5541aa4a1ca63ba17fc25b24684d0fea44aeb662f4a60241', '[\"*\"]', '2026-06-30 19:33:13', NULL, '2026-06-30 19:20:05', '2026-06-30 19:33:13'),
(273, 'App\\Models\\User', 2, 'auth_token', 'e0b94043747d160bb84994d99e240b0a28d04d22b47d7b7810377a06b73bce85', '[\"*\"]', '2026-07-01 20:47:54', NULL, '2026-07-01 18:46:25', '2026-07-01 20:47:54'),
(274, 'App\\Models\\User', 2, 'auth_token', '57784a5cf96504c95607127d3b2c0c201af5fa2d34f9bdb3e683489c184d73d2', '[\"*\"]', '2026-07-01 22:31:47', NULL, '2026-07-01 22:28:43', '2026-07-01 22:31:47'),
(275, 'App\\Models\\User', 2, 'auth_token', 'f8f4c798111c63595e740d710f2893f2265dc6821bd83f485605f95f556a00bc', '[\"*\"]', '2026-07-01 22:53:20', NULL, '2026-07-01 22:32:33', '2026-07-01 22:53:20'),
(276, 'App\\Models\\User', 2, 'auth_token', '6bd0c363bcad46a7e2f264086fede24c0fec0eddb8656ffd1c0748806c0d6fbb', '[\"*\"]', '2026-07-01 23:07:41', NULL, '2026-07-01 23:00:00', '2026-07-01 23:07:41'),
(277, 'App\\Models\\User', 2, 'auth_token', '0d90075b3d287aa5086bdc786c6800157a1deaf70e5e7c49b99aa7b16d6353c2', '[\"*\"]', '2026-07-01 23:22:32', NULL, '2026-07-01 23:21:06', '2026-07-01 23:22:32'),
(278, 'App\\Models\\User', 2, 'auth_token', 'c96ae18833c58f48c59d90289eccb4389503e914f8166ebfbbfd137426d41553', '[\"*\"]', '2026-07-03 19:35:38', NULL, '2026-07-02 15:07:57', '2026-07-03 19:35:38'),
(279, 'App\\Models\\User', 1, 'auth_token', '0f414e76ca6178b362fb5d5d548414126ab5426338c27c038ebe8963060cbda8', '[\"*\"]', '2026-07-02 18:55:41', NULL, '2026-07-02 17:46:29', '2026-07-02 18:55:41'),
(280, 'App\\Models\\User', 1, 'auth_token', 'aeefe74f09d094725b2931c1f19c85a7de4b0ed583a8556adbf93c294a5f22c2', '[\"*\"]', '2026-07-02 19:35:37', NULL, '2026-07-02 19:34:47', '2026-07-02 19:35:37'),
(281, 'App\\Models\\User', 2, 'auth_token', '1dbb77a942439b3a681ac330c30a8144f6de405df88615535f456401892606a9', '[\"*\"]', '2026-07-02 19:37:32', NULL, '2026-07-02 19:35:57', '2026-07-02 19:37:32'),
(282, 'App\\Models\\User', 2, 'auth_token', '286ffd0af0f578cc77ecd4fe2ed6d35d9591eb77e9ebf7a91782cd13de195642', '[\"*\"]', '2026-07-02 20:32:31', NULL, '2026-07-02 19:44:02', '2026-07-02 20:32:31'),
(283, 'App\\Models\\User', 1, 'auth_token', '36f15414659cac550824fa909658ee6e1322a3d47c7e14f1c80d0721543aa603', '[\"*\"]', '2026-07-03 16:16:03', NULL, '2026-07-02 22:46:35', '2026-07-03 16:16:03'),
(284, 'App\\Models\\User', 2, 'auth_token', '21e328f05cfca3963bf6d9f9c0272576201c6fa7ad4025efd4b6dc4e522aaf64', '[\"*\"]', '2026-07-08 23:19:17', NULL, '2026-07-08 22:13:51', '2026-07-08 23:19:17'),
(285, 'App\\Models\\User', 2, 'auth_token', '9cc2ecfa47d5f455ebe3af1a4bd48eea686a2e45feea98ee1df911b8a96f2f72', '[\"*\"]', '2026-07-09 22:28:26', NULL, '2026-07-09 19:00:36', '2026-07-09 22:28:26'),
(286, 'App\\Models\\User', 2, 'auth_token', 'f4c8bf90e85d1d6211727e11d86ecfa99efabc6405c1f9b9f4df5d2eee86f6d9', '[\"*\"]', '2026-07-09 20:00:47', NULL, '2026-07-09 20:00:44', '2026-07-09 20:00:47'),
(287, 'App\\Models\\User', 2, 'auth_token', '376ab6af611e4c78117389b30d930cad66f931777e018fe3981e5ab38602303e', '[\"*\"]', NULL, NULL, '2026-07-09 20:00:56', '2026-07-09 20:00:56'),
(288, 'App\\Models\\User', 2, 'auth_token', '1f1d7beac9405f21768ea8e1b1e858ebb206a0a2551698c536ed18d3702594af', '[\"*\"]', '2026-07-09 20:02:28', NULL, '2026-07-09 20:01:46', '2026-07-09 20:02:28'),
(289, 'App\\Models\\User', 2, 'auth_token', '5f09e04504f5dbc422c9bc5334bb16711c612760858b1f02d06916a6e38ec9bf', '[\"*\"]', '2026-07-10 23:34:04', NULL, '2026-07-10 21:56:19', '2026-07-10 23:34:04'),
(290, 'App\\Models\\User', 10, 'auth_token', '9f668cd96e126fe1733b7bb5916073cc65a96cf82115f37c5198fd6683f22047', '[\"*\"]', '2026-07-16 03:13:42', NULL, '2026-07-15 10:33:02', '2026-07-16 03:13:42'),
(291, 'App\\Models\\User', 2, 'auth_token', 'f7ca960a7dfca73ed57c900f5924233d5859bc3da8adb06cc38780119f74cead', '[\"*\"]', '2026-07-15 17:48:19', NULL, '2026-07-15 15:51:08', '2026-07-15 17:48:19'),
(292, 'App\\Models\\User', 2, 'auth_token', '096a219bc6dffe75b4263b97f161c627f53075a65c5379e3a5adb04b3c27bcc7', '[\"*\"]', '2026-07-15 20:32:49', NULL, '2026-07-15 17:48:03', '2026-07-15 20:32:49'),
(293, 'App\\Models\\User', 2, 'auth_token', '471e6fed2793b45ec876cf7ea1f31c6cd78da11dd7c8c02775219cab70e5d606', '[\"*\"]', '2026-07-15 18:01:01', NULL, '2026-07-15 17:58:01', '2026-07-15 18:01:01'),
(294, 'App\\Models\\User', 2, 'auth_token', '661dd6d25687124ebe4ba2efaf30806c2bf3071ba502f7bac281f3604b176a3f', '[\"*\"]', '2026-07-15 19:42:53', NULL, '2026-07-15 19:39:19', '2026-07-15 19:42:53'),
(295, 'App\\Models\\User', 2, 'auth_token', '82a04353f343aa1f9f376902f53b5a0df41c16cb394da80dfde387294af86c43', '[\"*\"]', '2026-07-15 20:42:41', NULL, '2026-07-15 20:34:11', '2026-07-15 20:42:41'),
(296, 'App\\Models\\User', 11, 'auth_token', 'e87cea25862e54824c25b3509b0b20620261dfcf65d1a603a555c60869dba9f3', '[\"*\"]', '2026-07-15 20:54:19', NULL, '2026-07-15 20:49:12', '2026-07-15 20:54:19'),
(297, 'App\\Models\\User', 2, 'auth_token', '402e7918e9988ff563a4f8da084352dbb521a740382e6211a574973f466f8d49', '[\"*\"]', '2026-07-15 21:08:15', NULL, '2026-07-15 20:54:44', '2026-07-15 21:08:15'),
(298, 'App\\Models\\User', 12, 'auth_token', '048f4a8739d206470b696275c36d3852254e935df0a7c66b7f741ff7a1ec1929', '[\"*\"]', '2026-07-15 21:49:43', NULL, '2026-07-15 21:49:38', '2026-07-15 21:49:43'),
(299, 'App\\Models\\User', 12, 'auth_token', 'f5e53857b4f9cfecee6e8380a102a45a91ac1626a792b931ffc7c9d7f1f7b6df', '[\"*\"]', '2026-07-15 23:04:02', NULL, '2026-07-15 21:53:41', '2026-07-15 23:04:02'),
(300, 'App\\Models\\User', 12, 'auth_token', '43a80f9647557e5e6ee982da67c5bd92356df6fc1109a028817a0fb969b1e40a', '[\"*\"]', '2026-07-15 23:12:26', NULL, '2026-07-15 23:07:14', '2026-07-15 23:12:26'),
(301, 'App\\Models\\User', 12, 'auth_token', 'c13575378af14acacbaac68acd6174eafea48e08ade1464e1c3323ec5dd652c1', '[\"*\"]', '2026-07-17 16:34:44', NULL, '2026-07-16 14:53:48', '2026-07-17 16:34:44'),
(302, 'App\\Models\\User', 12, 'auth_token', '02bb4ec939f2f17e4fc079f24be4da204fb03c89a384f4b74983dddda1c21748', '[\"*\"]', '2026-07-17 18:45:08', NULL, '2026-07-17 17:05:49', '2026-07-17 18:45:08'),
(303, 'App\\Models\\User', 12, 'auth_token', '85b59005dffa95ca422a2cdac86b22cb777fea1743b8d8c24c405a8bc58e4b35', '[\"*\"]', '2026-07-17 21:00:09', NULL, '2026-07-17 18:55:14', '2026-07-17 21:00:09'),
(304, 'App\\Models\\User', 8, 'auth_token', 'bf4ca71858612a81842efa78532e0f40e6c64f81b0588cd1ef8abcfbb2038880', '[\"*\"]', '2026-07-17 21:00:18', NULL, '2026-07-17 18:57:38', '2026-07-17 21:00:18'),
(305, 'App\\Models\\User', 7, 'auth_token', 'aba625bae58492db7a60b8bf5df7537451ff69d5e2cb4b9c531a5b58e851db2e', '[\"*\"]', '2026-07-17 21:19:02', NULL, '2026-07-17 19:41:08', '2026-07-17 21:19:02'),
(306, 'App\\Models\\User', 6, 'auth_token', 'f19f24f8540db3aba4dd8dd9cc969def07b63162f6460ed7ad31d9764c631d28', '[\"*\"]', '2026-07-27 19:22:28', NULL, '2026-07-17 19:55:51', '2026-07-27 19:22:28'),
(307, 'App\\Models\\User', 2, 'auth_token', '55cec218705569f5b6621e39d270f8d414eabb4fe8c6756df8f5d3d47e32cffd', '[\"*\"]', '2026-07-17 23:11:36', NULL, '2026-07-17 22:49:14', '2026-07-17 23:11:36'),
(308, 'App\\Models\\User', 12, 'auth_token', '5419cad8d57b79116b556af46e73e3facbf6b93e2708ed225208b9ae9215dd3d', '[\"*\"]', '2026-07-17 23:43:52', NULL, '2026-07-17 23:11:50', '2026-07-17 23:43:52'),
(309, 'App\\Models\\User', 12, 'auth_token', '4e43903b25113ed1df446306d5697e7c41544adbb27882668ecc850547aecc7a', '[\"*\"]', '2026-07-20 19:43:32', NULL, '2026-07-20 15:52:07', '2026-07-20 19:43:32'),
(310, 'App\\Models\\User', 8, 'auth_token', 'eb7d9efd23744dae0bbbff329d94d4ffda3384245e7fbd35603d7c8af8c62972', '[\"*\"]', '2026-07-20 21:28:32', NULL, '2026-07-20 16:18:19', '2026-07-20 21:28:32'),
(311, 'App\\Models\\User', 7, 'auth_token', '51c7af32b0c2c2663586944bf79103f2e44f8682f4faca3dcd80643827f98249', '[\"*\"]', '2026-07-27 19:21:39', NULL, '2026-07-20 16:25:53', '2026-07-27 19:21:39'),
(312, 'App\\Models\\User', 12, 'auth_token', 'f8168e4c07cf84f61d59805b6d65c325bd6320f5db9e1945b742b8ce336d4d24', '[\"*\"]', '2026-07-20 17:22:23', NULL, '2026-07-20 16:34:24', '2026-07-20 17:22:23'),
(313, 'App\\Models\\User', 1, 'auth_token', 'd0ee3efe05d813674baca10660122c3dc01b55bea405a13383e7a5c7c73f13b6', '[\"*\"]', '2026-07-20 22:35:18', NULL, '2026-07-20 17:23:21', '2026-07-20 22:35:18'),
(314, 'App\\Models\\User', 12, 'auth_token', 'c90c9e749d085f5922a9bd5850c183d946af6e4a46b4b6c72a9e528e8ce17e65', '[\"*\"]', '2026-07-20 19:44:43', NULL, '2026-07-20 19:44:34', '2026-07-20 19:44:43'),
(315, 'App\\Models\\User', 12, 'auth_token', '08500807b9a3b5f2ce2a8202cedab7230c6e7a1110d4e117ef74ec4e2a53293d', '[\"*\"]', '2026-07-20 19:47:30', NULL, '2026-07-20 19:44:40', '2026-07-20 19:47:30'),
(316, 'App\\Models\\User', 12, 'auth_token', '872461601eeac03b6fc39c61e0d804ba7a93d1ca47826c73a288f07af5429c96', '[\"*\"]', '2026-07-20 19:55:10', NULL, '2026-07-20 19:55:02', '2026-07-20 19:55:10'),
(317, 'App\\Models\\User', 12, 'auth_token', '46882f615b6adb9bc0412b22e39a5e6ee48f0067c6780cde6c1d708be014687e', '[\"*\"]', '2026-07-20 22:09:51', NULL, '2026-07-20 19:55:13', '2026-07-20 22:09:51'),
(318, 'App\\Models\\User', 2, 'auth_token', '198701685816e44279229cc7874bb3a2d490745afc213046df449726b585c8a4', '[\"*\"]', '2026-07-20 22:34:04', NULL, '2026-07-20 19:57:47', '2026-07-20 22:34:04'),
(319, 'App\\Models\\User', 2, 'auth_token', 'e150c1873981753fb5e7788fc85a48c76798fce75b5ec4f9e44d4452f740c645', '[\"*\"]', '2026-07-20 21:52:19', NULL, '2026-07-20 21:13:37', '2026-07-20 21:52:19'),
(320, 'App\\Models\\User', 1, 'auth_token', '363b77ae533be2e4f883dc8d2a3d51a40456eba8426f5adbc650ffc8a64bdc85', '[\"*\"]', '2026-07-20 21:55:26', NULL, '2026-07-20 21:52:34', '2026-07-20 21:55:26'),
(321, 'App\\Models\\User', 2, 'auth_token', '1b3a552960d16454cff171354a666ea8685c1b3bb3fa98ca8aa56d1649de57df', '[\"*\"]', '2026-07-20 22:40:45', NULL, '2026-07-20 21:58:44', '2026-07-20 22:40:45'),
(322, 'App\\Models\\User', 2, 'auth_token', '35573bed1ef541badc9192df38fddaa50d366f4a4bf968f15943a6787757243e', '[\"*\"]', '2026-07-20 23:06:31', NULL, '2026-07-20 22:08:13', '2026-07-20 23:06:31'),
(323, 'App\\Models\\User', 12, 'auth_token', 'e365f3de40782f0fa61edaa7b199e88b8aef574c334eee499fd9cc1b8bd80b32', '[\"*\"]', '2026-07-20 22:19:54', NULL, '2026-07-20 22:17:43', '2026-07-20 22:19:54'),
(324, 'App\\Models\\User', 12, 'auth_token', '743b575ff9af1e777ea8034203728fd3f1fc6400356c620b8320c57d3ddd20bd', '[\"*\"]', '2026-07-20 22:28:42', NULL, '2026-07-20 22:28:40', '2026-07-20 22:28:42'),
(325, 'App\\Models\\User', 2, 'auth_token', 'fa5a92203fec9642b5dc026e78ee57a1d354f4a3df90e7967dafc5c1a4b99276', '[\"*\"]', '2026-07-21 15:18:54', NULL, '2026-07-20 23:07:44', '2026-07-21 15:18:54'),
(326, 'App\\Models\\User', 2, 'auth_token', 'a898c813e1aa490e37aeabdc148085981bde687e8923c8ea054a6a7033d2354f', '[\"*\"]', '2026-07-20 23:20:00', NULL, '2026-07-20 23:19:57', '2026-07-20 23:20:00'),
(327, 'App\\Models\\User', 12, 'auth_token', '36b413508f1982fd574cf357ef1f58b457f406000472a97fee7ae34a1c7f7072', '[\"*\"]', '2026-07-20 23:21:28', NULL, '2026-07-20 23:20:51', '2026-07-20 23:21:28'),
(328, 'App\\Models\\User', 12, 'auth_token', 'c6a208c1b127cf30b2ef101caecb8047909cb972772242bb957584c63da2a22d', '[\"*\"]', '2026-07-21 20:58:17', NULL, '2026-07-20 23:22:15', '2026-07-21 20:58:17'),
(329, 'App\\Models\\User', 2, 'auth_token', 'beacd9528aa9830a76414aabba4f17e4d3aae2212ea070993413a1df80df59d0', '[\"*\"]', '2026-07-23 18:29:02', NULL, '2026-07-23 16:02:30', '2026-07-23 18:29:02'),
(330, 'App\\Models\\User', 2, 'auth_token', '06888a4263e54948fffb9970486b6f9b04161e73ecb396df2825691112504fb8', '[\"*\"]', '2026-07-28 15:36:57', NULL, '2026-07-23 19:01:30', '2026-07-28 15:36:57'),
(331, 'App\\Models\\User', 2, 'auth_token', 'b1647c089783748df7d4a0083d5f5a43e44c5111f2ed0ff1dd39696a59c821a3', '[\"*\"]', NULL, NULL, '2026-07-23 22:40:04', '2026-07-23 22:40:04'),
(332, 'App\\Models\\User', 2, 'auth_token', '3d869702a09198b3d23848aca954b391ddfc136e7467a3ae1b4f8b40b2e2f0da', '[\"*\"]', '2026-07-24 20:45:49', NULL, '2026-07-24 19:03:41', '2026-07-24 20:45:49'),
(333, 'App\\Models\\User', 2, 'auth_token', '863913a09a50b406f5d2089451aa7e19017c4d369d53c3c5fe3b873f0cc3e12a', '[\"*\"]', '2026-07-24 20:01:19', NULL, '2026-07-24 19:20:04', '2026-07-24 20:01:19'),
(334, 'App\\Models\\User', 12, 'auth_token', '013db4b746d3bb9b7d32141683bade99a746f2cbbec557eb197e2ec4fb47983c', '[\"*\"]', '2026-07-27 16:58:23', NULL, '2026-07-24 20:11:25', '2026-07-27 16:58:23'),
(335, 'App\\Models\\User', 2, 'auth_token', '7892e791da73947db82dad060b4a31b916602fb878511e57c219f537b1d53be1', '[\"*\"]', '2026-07-24 20:14:12', NULL, '2026-07-24 20:13:56', '2026-07-24 20:14:12'),
(336, 'App\\Models\\User', 12, 'auth_token', '5e93491fc6808b90240713f4f6bd19a3cb457e2862c7118626f3b9e76fc09f70', '[\"*\"]', '2026-07-27 20:12:56', NULL, '2026-07-24 22:33:11', '2026-07-27 20:12:56'),
(337, 'App\\Models\\User', 2, 'auth_token', 'c1222cafb618d7996286289b4cc584adc15d0a43adfff8d5993dfc35e0fd5fd9', '[\"*\"]', '2026-07-24 23:13:09', NULL, '2026-07-24 23:11:59', '2026-07-24 23:13:09'),
(338, 'App\\Models\\User', 2, 'auth_token', '5783139f93eb4bde345f8cf63b4a0fb84cdacca8479d1a8e7f2bf13082aa9f86', '[\"*\"]', '2026-07-27 14:41:34', NULL, '2026-07-27 14:36:14', '2026-07-27 14:41:34'),
(339, 'App\\Models\\User', 2, 'auth_token', '9426042f4c544fc49946ad088ccc9833004ed2ec145517e3ea36140e9c5d59c7', '[\"*\"]', '2026-07-27 17:14:17', NULL, '2026-07-27 15:28:43', '2026-07-27 17:14:17'),
(340, 'App\\Models\\User', 8, 'auth_token', 'b88110a13b5feace35af59370295d48b173f8373fed2206fe4cce219a5ab6628', '[\"*\"]', '2026-07-27 16:58:45', NULL, '2026-07-27 16:58:02', '2026-07-27 16:58:45'),
(341, 'App\\Models\\User', 8, 'auth_token', '5988677c6ed75dc6d456dd7f1c3abfb8ae5ebd79219370bfc4b8a2d644fd865e', '[\"*\"]', '2026-07-27 17:01:40', NULL, '2026-07-27 16:58:22', '2026-07-27 17:01:40'),
(342, 'App\\Models\\User', 2, 'auth_token', '180ae27ba686bdac26c9fb9b2ba69988da2269e1f37091109382799092c7d692', '[\"*\"]', '2026-07-27 16:59:20', NULL, '2026-07-27 16:58:50', '2026-07-27 16:59:20'),
(343, 'App\\Models\\User', 2, 'auth_token', '955880759b21fb4faefa1c10a22b9e91738543d368706b65c1bed41dd53ec8d3', '[\"*\"]', '2026-07-28 15:02:43', NULL, '2026-07-27 16:59:05', '2026-07-28 15:02:43'),
(344, 'App\\Models\\User', 12, 'auth_token', 'c1e7e77f43f7154e1f7deba67efbfda63d8ae5ab9880c533066fd08fa4238cf4', '[\"*\"]', '2026-07-28 15:50:05', NULL, '2026-07-27 17:15:10', '2026-07-28 15:50:05');

-- --------------------------------------------------------

--
-- Table structure for table `previous_works`
--

CREATE TABLE `previous_works` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(191) DEFAULT NULL,
  `title` varchar(191) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `previous_works`
--

INSERT INTO `previous_works` (`id`, `category`, `title`, `event_date`, `description`, `image`, `video_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Valorant Seasons', 'Our Previous Work', '2026-01-16', 'QEC Announces Partnership With Vortex Gaming Gear', 'previous-works/dltEx28CdxVNbbGWkHbFhs5LYW2iHfvQTlBPyD9J.png', 'https://www.youtube.com/shorts/qOdOCMqV06A', 1, '2026-01-14 18:12:43', '2026-05-11 21:48:27'),
(2, 'Valorant Seasons', 'Our Previous Work', '2026-01-15', 'QEC Announces Partnership With Vortex Gaming Gear', 'previous-works/3yVr3KJmca7fobgKIBOmH84YtsWEAWbI7JNKo4GI.png', 'https://www.youtube.com/shorts/qOdOCMqV06A', 1, '2026-01-14 18:17:06', '2026-05-11 21:48:19'),
(3, 'Valorant Seasons', 'Our Previous Work', '2026-01-16', 'QEC Announces Partnership With Vortex Gaming Gear', 'previous-works/c0r8AZ9eDieyR0xZfKLw3JKTULXLoc9NUHeRKN1y.png', 'https://www.youtube.com/shorts/qOdOCMqV06A', 1, '2026-01-14 18:17:29', '2026-05-11 21:48:10'),
(4, 'Valorant Seasons', 'Our Previous Work-2', '2026-01-20', '<p>QEC Announces Partnership With Vortex Gaming Gear</p><p>&nbsp;</p><p>QEC Announces Partnership With Vortex Gaming Gear</p>', 'previous-works/cO3dhKFVA1Arn3oGinGEEJwBDEIiKEkHl50PqYkt.png', 'https://www.youtube.com/shorts/qOdOCMqV06A', 1, '2026-01-14 18:17:48', '2026-06-03 22:53:40'),
(5, 'Valorant Seasons', 'Our Previous Work-1', '2026-01-30', '<p>QEC Announces Partnership With Vortex Gaming Gear</p><p>&nbsp;</p><p>QEC Announces Partnership With Vortex Gaming Gear</p>', 'previous-works/iTaVj7N9RiaA5FdE2r44B72dTTawZTXl65kyTv3h.png', 'https://www.youtube.com/shorts/qOdOCMqV06A', 1, '2026-01-14 18:18:08', '2026-06-03 22:53:12');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('admin','moderator') NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 'admin', 31, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(2, 'admin', 30, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(3, 'admin', 26, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(4, 'admin', 28, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(5, 'admin', 27, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(6, 'admin', 25, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(7, 'admin', 6, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(8, 'admin', 7, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(9, 'admin', 5, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(10, 'admin', 33, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(11, 'admin', 32, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(12, 'admin', 16, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(13, 'admin', 18, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(14, 'admin', 17, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(15, 'admin', 15, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(16, 'admin', 35, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(17, 'admin', 34, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(18, 'admin', 20, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(19, 'admin', 22, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(20, 'admin', 21, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(21, 'admin', 19, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(22, 'admin', 24, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(23, 'admin', 23, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(24, 'admin', 12, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(25, 'admin', 14, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(26, 'admin', 13, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(27, 'admin', 11, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(28, 'admin', 37, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(29, 'admin', 39, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(30, 'admin', 38, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(31, 'admin', 36, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(32, 'admin', 9, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(33, 'admin', 10, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(34, 'admin', 29, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(35, 'admin', 8, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(36, 'admin', 45, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(37, 'admin', 47, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(38, 'admin', 46, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(39, 'admin', 44, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(40, 'admin', 49, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(41, 'admin', 48, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(42, 'admin', 2, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(43, 'admin', 4, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(44, 'admin', 3, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(45, 'admin', 1, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(46, 'admin', 41, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(47, 'admin', 43, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(48, 'admin', 42, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(49, 'admin', 40, '2026-07-01 08:03:38', '2026-07-01 08:03:38'),
(209, 'admin', 50, '2026-07-01 12:22:44', '2026-07-01 12:22:44'),
(239, 'admin', 51, '2026-07-01 12:39:42', '2026-07-01 12:39:42'),
(437, 'moderator', 31, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(438, 'moderator', 30, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(439, 'moderator', 25, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(440, 'moderator', 5, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(441, 'moderator', 32, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(442, 'moderator', 15, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(443, 'moderator', 34, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(444, 'moderator', 19, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(445, 'moderator', 23, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(446, 'moderator', 11, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(447, 'moderator', 36, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(448, 'moderator', 8, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(449, 'moderator', 3, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(450, 'moderator', 1, '2026-07-15 22:18:17', '2026-07-15 22:18:17'),
(451, 'admin', 52, '2026-07-17 10:01:03', '2026-07-17 10:01:03');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `image`, `button_text`, `button_link`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(6, 'Table', 'On the Table', 'services/RmOeYaW0rDJP9mLtBGjQmswGutGToER8tfrCVlFG.jpg', NULL, NULL, 1, 5, '2026-05-29 02:52:49', '2026-05-29 02:53:14'),
(2, 'Tournament Organizing', '<p>We specialize in creating seamless and engaging esports tournaments, offering professional formats, high-quality production, and unparalleled audience engagement. Our expertise ensures exciting competitions that attract players, sponsors, and viewers alike.</p><p>&nbsp;</p><p>We specialize in creating seamless and engaging esports tournaments, offering professional formats, high-quality production, and unparalleled audience engagement. Our expertise ensures exciting competitions that attract players, sponsors, and viewers alike.</p>', 'services/9DUinnRBAiKk7Rgp1cFx4BzZxCdouOck4OrQfV5L.png', NULL, NULL, 1, 1, '2026-01-14 18:24:57', '2026-06-03 20:35:16'),
(3, 'Broadcast Production', 'Our production team specializes in delivering professional broadcast services for video \r\nstreaming. Whether it\'s live streaming events or producing high-quality content, we ensure a \r\nseamless and engaging experience customized to meet your specific needs.', 'services/gQy4ZHgmiVA0UUOfXJNoOUmLgsVZt5bWi5TduXSj.png', NULL, NULL, 1, 3, '2026-01-14 18:25:01', '2026-05-29 02:52:15'),
(4, 'Talent Management', 'We scout, nurture, and manage gaming talents, providing personalized coaching, brand-building \r\nopportunities, and sponsorships to help players and content creators achieve their full potential \r\nin the esports industry', 'services/TPacBpTXdc6m6zodHHxUM145glVvaLb7TM8JH4iz.png', NULL, NULL, 1, 2, '2026-01-14 18:25:01', '2026-05-29 02:51:55'),
(5, 'Consultation', 'With over 6 years of experience in esports, we offer tailored solutions to meet your needs. From \r\nevent planning to market analysis, we guide businesses through every step to ensure successful \r\nentry and growth in the esports sector.', 'services/8L1v1Do9HGF6Hir84gXDsdjtKHRUYyIcf06x5F5C.png', NULL, NULL, 1, 4, '2026-01-14 18:41:36', '2026-05-29 02:52:34');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('iKZ9p4RA2UQRNYAv1sbXdBFdznvLWbNlSWiEwZX9', 1, '180.151.25.88', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN2pmdmJnc29OTFdPdHdNWHNXOFFidnZCVWZqbWhPa2J1bllVY2xKeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTQ6Imh0dHBzOi8vd3d3Lm1hcmt1cGRlc2lnbnMubmV0L3FhdGFyLWVzcG9ydHMvYWRtaW4vbWFwcyI7czo1OiJyb3V0ZSI7czoxNjoiYWRtaW4ubWFwcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1785149137),
('pAJv1VugI1qaqftSBRaC3E4p8FJImZi9fYk9p8uf', 1, '180.151.25.88', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWXhjNng0TUpmVGhDb2o1U2ppU1p4TVFIMUR6d2cyMkJjdm9ONk5OMCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo4MjoiaHR0cHM6Ly93d3cubWFya3VwZGVzaWducy5uZXQvcWF0YXItZXNwb3J0cy9hZG1pbi90b3VybmFtZW50LXJlZ2lzdHJhdGlvbnMvc29sby8xNCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjc5OiJodHRwczovL3d3dy5tYXJrdXBkZXNpZ25zLm5ldC9xYXRhci1lc3BvcnRzL2FkbWluL3RlYW0tcmVnaXN0cmF0aW9ucy8xMS9tZW1iZXJzIjtzOjU6InJvdXRlIjtzOjMyOiJhZG1pbi50ZWFtLXJlZ2lzdHJhdGlvbnMubWVtYmVycyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1785151094);

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `game_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(191) DEFAULT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `logo` varchar(191) DEFAULT NULL,
  `banner` varchar(191) DEFAULT NULL,
  `social_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_links`)),
  `stream_url` varchar(255) DEFAULT NULL,
  `location` varchar(191) DEFAULT NULL,
  `format` enum('solo','team') DEFAULT NULL,
  `team_size` int(11) DEFAULT NULL,
  `status` enum('upcoming','live','completed') DEFAULT NULL,
  `visibility` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `allow_pdf_download` tinyint(1) NOT NULL DEFAULT 0,
  `is_registration_open` tinyint(1) DEFAULT NULL,
  `registration_start` datetime DEFAULT NULL,
  `registration_end` datetime DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `timezone` varchar(191) DEFAULT NULL,
  `entry_fee` decimal(10,2) DEFAULT NULL,
  `prize_pool` decimal(12,2) DEFAULT NULL,
  `winner_team_id` bigint(20) DEFAULT NULL,
  `winner_team_name` varchar(255) DEFAULT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `registered_participants` int(11) NOT NULL DEFAULT 0,
  `description` longtext DEFAULT NULL,
  `rules` longtext DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`id`, `game_id`, `title`, `slug`, `logo`, `banner`, `social_links`, `stream_url`, `location`, `format`, `team_size`, `status`, `visibility`, `is_featured`, `allow_pdf_download`, `is_registration_open`, `registration_start`, `registration_end`, `start_date`, `end_date`, `start_time`, `timezone`, `entry_fee`, `prize_pool`, `winner_team_id`, `winner_team_name`, `max_participants`, `registered_participants`, `description`, `rules`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2, 'Clash squad', 'clash-squad', 'tournaments/zCX3Rv0YUapSba6F1bRAurZH3J7GAzGJDwMyaKpF.jpg', 'tournaments/ep8TH17ds4omoRXAXwxGT4JuV79iPbw0HPZaVq7X.jpg', NULL, NULL, NULL, 'team', 10, NULL, 'published', 0, 0, NULL, '2026-03-08 00:00:00', '2026-07-29 23:59:59', '2026-07-30 00:00:00', '2026-07-31 00:00:00', '18:12:00', NULL, 500.00, 10000.00, 2, 'Kushank Rajput', 20, 5, 'Lets play clash squad.', 'None', 1, '2026-03-13 13:43:10', '2026-07-27 19:34:22'),
(2, 4, 'Racing car', 'racing-car', 'tournaments/TF00tLQlMNgpYDBKIqq8cTgxMfgtdg5DpG1ZpeAl.webp', 'tournaments/k9ZgsVzi0XHmOlTpJbUWwq0Eh9tz2WZnMIai4gmM.webp', NULL, NULL, NULL, 'solo', NULL, NULL, 'published', 1, 0, NULL, '2026-05-07 00:00:00', '2026-05-08 23:59:59', '2026-05-07 00:00:00', '2026-05-08 00:00:00', '16:20:00', NULL, 19.00, 200.00, NULL, NULL, 2, 0, 'Testing purpose', 'No rules', 1, '2026-05-07 19:20:56', '2026-05-08 21:43:47'),
(3, 4, 'Testing', 'testing', 'tournaments/DJl5euYeMv3KwCEJ1zFZgB77OPZrROq2CduO21s1.webp', 'tournaments/FbbeRv7iUlXSWasY4uNj6vu092n2FkuMZc2xg3yv.webp', NULL, NULL, NULL, 'team', 2, NULL, 'published', 1, 0, NULL, '2026-05-06 00:00:00', '2026-05-07 23:59:00', '2026-05-07 00:00:00', '2026-05-08 00:00:00', '16:57:00', NULL, 29.00, 200.00, NULL, NULL, 2, 0, '<p>Qatar Esports Community (QEC) is a premier organization powered by Gama Esports and Darkcube Esports, dedicated to hosting world-class events, scrims, and tournaments. With a skilled team driving engaging content and community growth, we set the standard for esports in Qatar and aim to expand into new gaming genres, shaping the future of competitive gaming in the region. Darkcube Esports, founded by YouTuber Jack Albushi, is a top PUBG Mobile organizer in the Middle East with 27 successful tournaments. Based in Qatar and the GCC, we’re now expanding into Valorant to further elevate the regional esports scene.</p>', '<p>Player Eligibility: This includes requirements for participation, such as being a minimum age. For example, cash prize tournaments often have a minimum age of 16+ and require participants to be registered for the event. Some tournaments may also have rules about players competing for only one team throughout the event. Gameplay &amp; Format: This defines the specific way the sport or game is played, including match formats (e.g., best-of-three, single-elimination), round lengths, scoring systems, and victory conditions. The match format can vary; single-elimination where a single loss eliminates a team, double-elimination featuring winners’ and losers’ brackets, round-robin group stages, or a regular season with playoffs are all common. Code of Conduct &amp; Sportsmanship: These rules prohibit unsportsmanlike, dishonest, or toxic behavior, ensuring that all competitors adhere to principles of fair play. Violations can lead to penalties like point deductions, temporary suspensions, or permanent bans. Technical &amp; Administrative Rules: This covers logistical details such as approved equipment (e.g., specific types of balls for tennis tournaments), time controls (e.g., chess can be rated with a 45+30 time control), and procedures for reporting match results</p>', 1, '2026-05-07 19:24:29', '2026-07-09 21:02:48'),
(4, 3, 'Football', 'football', 'tournaments/gs1dZnYYwuCAgWRB9wuBDSwWv91sKhrxOW197b2a.jpg', 'tournaments/uolQWht4XSLsMs7HjuNsXiINgjOSgrwslf5gaTc4.jpg', NULL, NULL, NULL, 'solo', NULL, NULL, 'published', 0, 0, NULL, '2026-05-07 16:27:00', '2026-05-10 22:39:00', '2026-05-11 04:39:00', '2026-05-11 19:30:00', NULL, NULL, 20.00, 200.00, NULL, NULL, 2, 0, NULL, NULL, 1, '2026-05-08 20:30:56', '2026-05-11 02:21:37'),
(5, 5, 'Football 1', 'football-1', 'tournaments/066c6SCN8JddwfgQsjfvYF2J3Fgeuy5jQzwzgNs7.jpg', 'tournaments/zS3D0wYdKwtUi4wVs4ok8lJ2oIBkygbn706U8FBu.jpg', NULL, NULL, NULL, 'team', 2, NULL, 'published', 0, 0, NULL, '2026-05-07 16:40:00', '2026-05-09 16:40:00', '2026-05-09 19:40:00', '2026-05-10 22:46:00', NULL, NULL, 20.00, 220.00, NULL, NULL, 2, 0, NULL, NULL, 1, '2026-05-08 20:42:36', '2026-06-05 16:33:05'),
(6, 3, 'Fighting game', 'fighting-game', 'tournaments/HoMe7klXt6ConFF0SwFjYF8BGBjisoWUQqUYAIxN.jpg', 'tournaments/TPAuZULBxmGErDLgMWWlSYQOOJCGj0sFnFYfV2yv.jpg', NULL, NULL, NULL, 'team', 2, NULL, 'published', 0, 0, NULL, '2026-05-10 12:07:00', '2026-05-11 13:07:00', '2026-05-11 12:08:00', '2026-05-11 14:10:00', NULL, NULL, 20.00, 200.00, NULL, NULL, 2, 0, NULL, 'Teste the rules', 1, '2026-05-11 16:08:29', '2026-07-17 19:28:51'),
(7, 3, 'Fighting game', 'fighting-game-2', 'tournaments/VDWcllDp4CWcfqkXkzuFeA5om1cwgJtp4q1V8KWr.jpg', 'tournaments/39DNti3zX4dWlOEhdWmAN0VSjQnsPWe3LOVR4b9M.jpg', '{\"youtube\":null,\"twitch\":null,\"instagram\":null,\"facebook\":null,\"discord\":null,\"tiktok\":null,\"twitter\":null}', NULL, NULL, 'solo', NULL, NULL, 'published', 0, 1, NULL, '2026-07-08 18:20:00', '2026-07-10 20:20:00', '2026-07-11 21:20:00', '2026-07-12 12:27:00', NULL, NULL, 2000.00, 124.00, NULL, NULL, NULL, 0, '<p>A fighting game is a genre of video games centered on close-combat, head-to-head battles between a limited number of characters in a confined, often side-view, arena. The primary goal is to deplete an opponent\'s health bar within a time limit, typically focusing on technical execution, precise timing, and player-vs-player (PvP)</p>', '<p>Characters and Combat: Players choose from a diverse roster of characters, each with unique move sets, special abilities, and strengths. Mechanics: Gameplay focuses on intricate mechanics like combos, blocking, throwing, and utilizing \"special moves\" triggered by specific button sequences. Core Concepts: Matches emphasize \"footsies\" (spacing and distancing), \"whiff punishing\" (attacking an opponent after they miss a move), and \"reading\" the opponent’s next move. Structure: Often divided into rounds, where the first player to win a set number of rounds wins the match</p>', 1, '2026-05-11 22:23:28', '2026-07-09 19:26:03'),
(8, 5, 'Clash cup test', 'T', 'tournaments/7YFzgivCIbup16785gj65ENJ0YYNHUUjyPMDrHZZ.png', 'tournaments/9kuqIuzVd7ZX6ejMVIgKBWKLu0YHTVY0SNZtzzV9.png', NULL, NULL, NULL, 'solo', NULL, NULL, 'published', 1, 1, NULL, '2026-05-18 19:00:00', '2026-05-19 21:18:00', '2026-05-20 21:21:00', '2026-05-22 16:21:00', NULL, NULL, 0.00, 2000.00, NULL, NULL, 128, 0, '<p>Qatar Esports Community (QEC) is a premier organization powered by Gama Esports and Darkcube Esports, dedicated to hosting world-class events, scrims, and tournaments. With a skilled team driving engaging content and community growth, we set the standard for esports in Qatar and aim to expand into new gaming genres, shaping the future of competitive gaming in the region. Darkcube Esports, founded by YouTuber Jack Albushi, is a top PUBG Mobile organizer in the Middle East with 27 successful tournaments. Based in Qatar and the GCC, we’re now expanding into Valorant to further elevate the regional esports scene. dfdfdfd</p>', '<p>Tournament Dates &amp; Eligibility 1. Eligibility The participants should be resident or visitors of Qatar. (Should be physically present for the LAN tournament) All ranks are valid. 2. Dates &amp; Schedule Tournament Format Group Stage – Week 1 Day 1 - [13/8/2024] Group A (4 Matches) All teams will play Online (schedule shared by the organizers) Day 2 - [14/8/2024] Group B (4 Matches) a- Best of 1. b- All teams will play Online (schedule shared by the organizers) Day 3 - [17/8/2024] Group A &amp; B (4 Matches) a- Best of 1. b- All teams will play LAN at QZONE (schedule shared by the organizers) Group Stage – Week 2 Day 4 - [19/8/2024] Group C (4 Matches) All teams will play Online (schedule shared by the organizers) Day 5 - [20/8/2024] Group D (4 Matches) a- Best of 1. b- All teams will play Online (schedule shared by the organizers) Day 6 - [21/8/2024] Group C &amp; D (4 Matches) a- Best of 1. b- All teams will play LAN at QZONE (schedule shared by the organizers) Double Elimination Bracket – Week 3 &amp; 4 TBA - Will be announced after the group stage is completed (Note): In Case of any changes in the schedule tournament organizers will send advanced message to the participants regarding the changes in playoffs dates. Tournament General Details 1- There will be 16 teams upon making the QEC Season 4 (Valorant) tournament. Teams will consist of 5 players and 1 sub player, one of whom will be designated captain and will be the main point of contact with the Organizers. 2- The Tournament is a 14 to 16 days over 1 month depending on number of teams registered where all ranks are valid. 3- Players should be resident or visitors of Qatar to compete in the tournament. 4- Lobby Settings and Map/Agent Selections Agent Pool: All agents are allowed Map Pool: Ascent, Bind, Breeze, Icebox, Lotus, Split, Sunset Cheats: Off Tournament Mode: On Play Out All Rounds: Off Overtime Mode: Win by 2 rounds (No draws allowed) Server: Bahrain 5- Map veto can be done manually by both parties. 6- The tournament will be based on a BO1 during the Group stage. BO3 during the Double Elimination bracket &amp; B05 in the Upper Final, Lower Final and Grand Finals of Season 4. 7- All participating teams should join the QEC discord server (Click Here!) for all updates regarding the Season 4 changes. 8- Follow QEC Instagram is mandatory to accept the registration of the team. (Click Here!) Stream Schedules The tournament will be streamed at QEC YouTube Channel: @qecgg (Click Here!) All the matches in the Group Stage &amp; Double Elimination Bracket will be covered Please Check the rest of the rules by clicking on this (Link to Main Rulebook Page) Additional Rules Backseat coaching is not allowed. All external parties, including coaches, are not allowed to communicate with any of the players once the pick phase is completed. Only Tournament Officials are allowed to communicate with the players. Every participant must always have the newest version of VALORANT installed and has to check for updates in time before each match. Unless a new version comes out right before the match, patching is not a valid reason to delay it. Prize Pool can be Vouchers, Cash, items, etc. Depending on the tournament structure. (Note: Forfeiting a match that would secure the prize pool will result in the forfeiting team\'s disqualification from receiving the prize) If requested, individuals are required to cooperate with interviews, reporting, video shoots and other activities planned and organized by the organizers. Players also agree to allow the organizers to use their team logos or pictures for promotional purposes. A Team may not make available the same player for media for 4 consecutive Match days. By registering for this tournament, the player allows the organizer to use (not limited to) their likeness, video, audio and visual materials, royalty-free and in perpetuity. Every participant acknowledges the right for the tournament administration to modify the rules and regulations for adjustments at any time without notice. Every participant has to show the needed respect towards officials and other participants. Insults and unfair or disrespectful behavior towards anyone is not tolerated and will be punished. Disrespectful behavior occurring during a game will result in the following: Warnings a. First Warning that the team have to acknowledge b. Second Warning will lead to forfeiting the match c. Third Warning will lead to disqualification of the team from the tournament In case of extraordinary circumstances, the official may decide to abstain from the punishment. Players have 15 minutes after the match concludes to message any tournament official on Discord and protest the match. If a protest is not brought to the attention of the broadcast tournament official in the allotted time frame, it will not be considered an official protest, and all match results will stay as is. Match protests must include screenshots clearly showing the results of the match/series. Players are responsible for providing proof of match results in case of disputes. Schedule and Punctuality a. Every player accepts the official schedule of the competition and declares its ability to be available during these times. Failure to turn up for matches results in a forfeiture of that match. b. The official schedule of the competition will be announced on the official QEC Social medias and/or on Discord. The players will also be informed about it via email/discord chat. This schedule includes the time of each match which represents the start time of the match. c. The change of a match time is only possible under exceptional circumstances and has to be confirmed by the tournament administration. d. If a delayed end of a previous match prohibits a match to start on schedule (due to either one of the involved teams still playing or the official broadcaster still streaming the previous match within the same tournament), the match time is changed to 15 minutes after the end of the previous match. e. unexpected issues for one single player are not a valid reason to postpone matches.</p>', 1, '2026-05-19 03:51:25', '2026-06-05 15:33:15'),
(9, 4, 'Test', 'test', NULL, NULL, NULL, NULL, NULL, 'team', 10, NULL, 'published', 0, 1, NULL, '2026-05-26 15:14:00', '2026-05-26 16:15:00', '2026-05-26 18:17:00', '2026-05-28 15:14:00', NULL, NULL, 1000.00, 109.00, NULL, NULL, 100, 0, 'testing', 'testing', 1, '2026-05-26 19:15:11', '2026-05-26 20:10:38'),
(26, 3, 'Best football shot', 'abcd', 'tournaments/23CLTThjqLMV31RRTpeuVmREW1gsmmbovKBtlZSA.png', 'tournaments/CjGGPbUypiX5uJFQHtlBtHPAlm6z9j0LWPNHfXdO.png', '{\"youtube\":null,\"twitch\":null,\"instagram\":null,\"facebook\":null,\"discord\":null,\"tiktok\":null,\"twitter\":null}', NULL, NULL, 'team', 11, NULL, 'published', 0, 0, NULL, '2026-07-27 15:17:00', '2026-07-27 15:43:00', '2026-07-27 15:44:00', '2026-07-27 15:50:00', NULL, NULL, 200.00, 2000.00, 11, 'QA', 10, 2, NULL, NULL, 1, '2026-07-27 16:44:11', '2026-07-27 19:43:42'),
(11, 6, 'Fortnite cup', 'fortnite-cup', 'tournaments/9s6038lWvY5v3WNSJbgjqM42caQcQPxVhUS0m1rc.png', 'tournaments/teNarrvk8cOalLUQ24sasomrgLzBCkGcu7LMkErM.png', NULL, NULL, NULL, 'team', 2, NULL, 'published', 0, 1, NULL, '2026-05-28 19:30:00', '2026-05-31 20:49:00', '2026-06-02 04:00:00', '2026-06-04 20:53:00', NULL, NULL, 0.00, 5000.00, NULL, NULL, 128, 0, '<p>Welcome to QVC (Qatar Valorant Community), a brand under QEC—Qatar’s premium esports organization.&nbsp;</p><p>&nbsp;</p><p>Powered by Gama Esports &amp; DarkCube Esports, QVC proudly presents Season 5 of the Valorant Tournament: 4 weeks of action, fierce competition, and the chance to be crowned QEC Valorant Champion. Follow QEC socials for updates &amp; future events! Registration: Team of 5 players + 1 sub (optional) / Register as a solo (we will create team for them) Details of Season 5 • From 25.7.2025 – 14.8.2025 • New tournament structure • Compete and secure your rank to win the title of season 4 alongside with the prizes! • All details are in QEC Discord server (Click Me!) Subscribe to never miss your Ultimate power!</p>', '<p>Tournament Dates &amp; Eligibility&nbsp;</p><p>&nbsp;</p><p>1. Eligibility The participants should be resident or visitors of Qatar. (Should be physically present for the LAN tournament) All ranks are valid.&nbsp;</p><p>&nbsp;</p><p>2. Dates &amp; Schedule Tournament Format Group Stage – Week 1 Day 1 - [13/8/2024] Group A (4 Matches) All teams will play Online (schedule shared by the organizers) Day 2 - [14/8/2024] Group B (4 Matches) a- Best of 1.&nbsp;</p><p>&nbsp;</p><p>b- All teams will play Online (schedule shared by the organizers) Day 3 - [17/8/2024] Group A &amp; B (4 Matches) a- Best of&nbsp;</p><p>&nbsp;</p><p>1. b- All teams will play LAN at QZONE (schedule shared by the organizers) Group Stage – Week 2 Day 4 - [19/8/2024] Group C (4 Matches) All teams will play Online (schedule shared by the organizers) Day 5 - [20/8/2024] Group D (4 Matches) a- Best of 1. b- All teams will play Online (schedule shared by the organizers) Day 6 - [21/8/2024] Group C &amp; D (4 Matches) a- Best of 1. b- All teams will play LAN at QZONE (schedule shared by the organizers) Double Elimination Bracket – Week 3 &amp; 4 TBA - Will be announced after the group stage is completed (Note):&nbsp;</p><p>&nbsp;</p><p>In Case of any changes in the schedule tournament organizers will send advanced message to the participants regarding the changes in playoffs dates. Tournament General Details 1- There will be 16 teams upon making the QEC Season 4 (Valorant) tournament. Teams will consist of 5 players and 1 sub player, one of whom will be designated captain and will be the main point of contact with the Organizers.&nbsp;</p><p>&nbsp;</p><p>2- The Tournament is a 14 to 16 days over 1 month depending on number of teams registered where all ranks are valid.&nbsp;</p><p>&nbsp;</p><p>3- Players should be resident or visitors of Qatar to compete in the tournament.</p><p>&nbsp;</p><p>&nbsp;4- Lobby Settings and Map/Agent Selections Agent Pool: All agents are allowed Map Pool: Ascent, Bind, Breeze, Icebox, Lotus, Split, Sunset Cheats: Off Tournament Mode: On Play Out All Rounds: Off Overtime Mode: Win by 2 rounds (No draws allowed) Server: Bahrain&nbsp;</p><p>&nbsp;</p><p>5- Map veto can be done manually by both parties. 6- The tournament will be based on a BO1 during the Group stage. BO3 during the Double Elimination bracket &amp; B05 in the Upper Final, Lower Final and Grand Finals of Season 4. 7- All participating teams should join the QEC discord server (Click Here!) for all updates regarding the Season 4 changes. 8- Follow QEC Instagram is mandatory to accept the registration of the team. (Click Here!) Stream Schedules The tournament will be streamed at QEC YouTube Channel: @qecgg (Click Here!) All the matches in the Group Stage &amp; Double Elimination Bracket will be covered Please Check the rest of the rules by clicking on this (Link to Main Rulebook Page) Additional Rules Backseat coaching is not allowed. All external parties, including coaches, are not allowed to communicate with any of the players once the pick phase is completed. Only Tournament Officials are allowed to communicate with the players. Every participant must always have the newest version of VALORANT installed and has to check for updates in time before each match. Unless a new version comes out right before the match, patching is not a valid reason to delay it. Prize Pool can be Vouchers, Cash, items, etc. Depending on the tournament structure. (Note: Forfeiting a match that would secure the prize pool will result in the forfeiting team\'s disqualification from receiving the prize) If requested, individuals are required to cooperate with interviews, reporting, video shoots and other activities planned and organized by the organizers. Players also agree to allow the organizers to use their team logos or pictures for promotional purposes. A Team may not make available the same player for media for 4 consecutive Match days. By registering for this tournament, the player allows the organizer to use (not limited to) their likeness, video, audio and visual materials, royalty-free and in perpetuity. Every participant acknowledges the right for the tournament administration to modify the rules and regulations for adjustments at any time without notice. Every participant has to show the needed respect towards officials and other participants. Insults and unfair or disrespectful behavior towards anyone is not tolerated and will be punished. Disrespectful behavior occurring during a game will result in the following: Warnings a. First Warning that the team have to acknowledge b. Second Warning will lead to forfeiting the match c. Third Warning will lead to disqualification of the team from the tournament In case of extraordinary circumstances, the official may decide to abstain from the punishment. Players have 15 minutes after the match concludes to message any tournament official on Discord and protest the match. If a protest is not brought to the attention of the broadcast tournament official in the allotted time frame, it will not be considered an official protest, and all match results will stay as is. Match protests must include screenshots clearly showing the results of the match/series. Players are responsible for providing proof of match results in case of disputes. Schedule and Punctuality a. Every player accepts the official schedule of the competition and declares its ability to be available during these times. Failure to turn up for matches results in a forfeiture of that match. b. The official schedule of the competition will be announced on the official QEC Social medias and/or on Discord. The players will also be informed about it via email/discord chat. This schedule includes the time of each match which represents the start time of the match. c. The change of a match time is only possible under exceptional circumstances and has to be confirmed by the tournament administration. d. If a delayed end of a previous match prohibits a match to start on schedule (due to either one of the involved teams still playing or the official broadcaster still streaming the previous match within the same tournament), the match time is changed to 15 minutes after the end of the previous match. e. unexpected issues for one single player are not a valid reason to postpone matches.</p>', 1, '2026-05-29 02:19:52', '2026-07-01 22:02:50'),
(13, 3, 'FiFi Cup', 'fifi-cup', 'tournaments/3zhI30zDFt9WARn2CxF9NjRiRboDJjJEM8JloULA.jpg', 'tournaments/hUoKZr6IYUp7xNZmKtnF4WXa7XMHmXt71JUhV4Lk.jpg', '{\"youtube\":\"https:\\/\\/youtube.com\",\"twitch\":\"https:\\/\\/youtube.com\",\"instagram\":\"https:\\/\\/youtube.com\",\"facebook\":\"https:\\/\\/youtube.com\",\"discord\":\"https:\\/\\/youtube.com\",\"tiktok\":\"https:\\/\\/youtube.com\",\"twitter\":\"https:\\/\\/youtube.com\"}', 'https://youtube.com', NULL, 'team', 5, NULL, 'published', 1, 0, NULL, '2026-06-05 11:38:00', '2026-07-15 17:44:00', '2026-07-21 10:00:00', '2026-07-21 12:00:00', NULL, NULL, 20.00, 200.00, NULL, NULL, 6, 0, NULL, NULL, 1, '2026-06-05 15:38:56', '2026-07-21 15:18:44'),
(14, 5, 'FIFA World Cup', 'fifa-world-cup', 'tournaments/2hf6dMawZBThohpPRR0BoGdJSlMBIOGubuQQ7Pnw.png', 'tournaments/89A57iIvTzjTEhXizaWFpHYQswGQMpZIqXumzTmY.png', '{\"youtube\":\"https:\\/\\/youtube.com\",\"twitch\":\"https:\\/\\/youtube.com\",\"instagram\":\"https:\\/\\/youtube.com\",\"facebook\":\"https:\\/\\/youtube.com\",\"discord\":\"https:\\/\\/youtube.com\",\"tiktok\":\"https:\\/\\/youtube.com\",\"twitter\":\"https:\\/\\/youtube.com\"}', 'https://youtube.com', NULL, 'solo', NULL, NULL, 'published', 1, 0, NULL, '2026-06-25 16:00:00', '2026-07-25 22:00:00', '2026-07-26 16:00:00', '2026-07-28 23:00:00', NULL, NULL, 0.00, 5000.00, 4, 'Abhay Chauhan', 128, 7, '<h1><strong>Introduction</strong></h1><p><strong>Gamers Station Cup!</strong></p><p>Welcome to the Gamers Station Cup – an exciting EA FC 25 tournament bringing together Qatar’s football gaming community for a competitive 1v1 showdown. Open to players of all skill levels, the tournament features fast-paced knockout rounds, building up to a high-intensity grand final. With a trophy, exclusive prizes, and bragging rights on the line, this is your chance to showcase your skills and compete like a champion.</p><p>The Gamers Station Cup delivers the ultimate mix of competitive skill, passion for the game, and a strong sense of community. Whether you\'re chasing the championship title or joining for the sheer love of football, this tournament is your moment to shine and make your mark in the world of EA FC 25.</p><p>&nbsp;</p><p><strong>Tournament Details</strong></p><ol><li>Playoffs: From 27.5.2025 till 28.5.2025 from 6:00 PM till 10:00 PM</li><li>Finals: 31.5.2025 (Time TBA)</li><li>Game Mode: Kickoff 1v1</li><li>Entry Fee: Free</li><li>Tournament Format: Single Elimination Bracket</li><li>Location: Gamers Station - Al Jazeera St, Doha, Qatar</li></ol><p>&nbsp;</p><p><a href=\"https://www.start.gg/tournament/gamers-station-cup/event/gamers-station-cup/overview/rules\"><strong>Tournament Rulebook (Click here!)</strong></a></p><p>NOTE: Players are expected to read and understand the rules and regulation. (If there are any confusions you should approach an official QEC admin for further concerns)</p>', '<p><strong>Tournament General Details1. Teams &amp; Format</strong></p><p>128 teams, 1v1 Kickoff - Single Elimination format</p><p><strong>2. Tournament Duration</strong></p><p>Spanning 3 days , depending on the number of registered players.</p><p><strong>3. Player Eligibility</strong></p><p>a. The participants should be resident or visitors of Qatar. (Should be physically present for the LAN tournament)</p><p>b. Age restriction 13+</p><p><strong>4. Game Settings</strong></p><p>Preset: Custom or Competitive</p><p>Game Tactics: Custom tactics allowed</p><p>Formations: In-house Default formation (no player dragging allowed)</p><p>Camera Settings: Tele Broadcast (unless agreed upon by both parties)</p><p>Half Length: 6 minutes</p><p>Time/Score display: On</p><p>HUD: Player Name Bar</p><p>Player Indicator Size: Default</p><p>Player Indicator Fade: On</p><p>Attributes: Default</p><p>Score Clock Dropdown: On</p><p>Match Conditions: summer</p><p>Time of Day: 8 PM</p><p>Day: Clear</p><p>Trainer: Off</p><p>Game Difficulty: Legendary</p><p>Graphics Mode: Favor Resolution</p><p><strong>5. Overtime &amp; penalty shootouts</strong></p><p>In case that a winner is not determined after two played halves, two overtime periods will be played, each lasting 3 minutes. If a winner is still not decided at that point, penalty shootouts will be played to determine the advancement in the tournament or the overall winner.</p><p><strong>6. Formations</strong></p><p>5 and 3 at the back formations are restricted from competitive use, including the following formations:</p><p>(Update: TBA)</p><ul><li>5-2-2-1</li><li>5-2-3</li><li>5-4-1</li><li>5-1-2-2</li><li>5-2-1-2</li><li>5-3-2</li><li>3-5-2</li><li>3-4-1-2</li><li>3-4-2-1</li><li>3-1-4-2</li><li>3-4-3</li></ul><p>Competitors found to be using a restricted formation at any point during a match will be given an immediate loss. In case of BO3 or BO5 matches will count 1 game win for the other player.</p><p><strong>7. Teams Selections</strong></p><p>a. All club teams are allowed. (Except Legendary teams are not allowed)</p><p>b. Both participants in the same match can select the same club.</p><p><strong>8. Match Format</strong></p><ul><li>Initial rounds: Best of 1 (BO1).</li><li>One round before the Quarter Finals: Best of 3 (BO3).</li><li>Semi Finals &amp; 3rd place: Best of 5 (BO5).</li><li>Grand Finals: TBA</li></ul><p><strong>9. Discord &amp; Instagram</strong></p><ul><li>Players can join the QEC Discord server for support <a href=\"https://discord.gg/eSPGCFvjGF\">Click here</a> for updates.</li><li>Follow <a href=\"https://www.instagram.com/gamersstationqtr/\">Gamers Station</a> and <a href=\"https://www.instagram.com/qecgg_/\">QEC</a> on Instagram is mandatory for registration.</li></ul><p><strong>Game Pauses RulesBreaks &amp; Interruptions</strong></p><p>During the competition, a competitor may only pause the game once each half (maximum of 2 times per match) and in the event of a stopped game (\"offside,\" \"touchline,\" \"fouls\").</p><p>An exception for an additional pause can be made in cases where the competitor\'s player is sent off the field (yellow, red card, or injuries), and a change in the game strategy is required due to a substitution.</p><p><strong>Note: Once the match has started, Players may not pause the game except in the case of an emergency or a defective controller.</strong></p><p><strong>Defective Controller</strong></p><p>If a controller is defective, the participant can pause the game and notify the tournament referee. Once the participant agrees that the problem has been rectified, they are NOT allowed to pause the game a second time.</p><p>The tournament referee will determine the final decision regarding the controller’s status. If a participant chooses to play with a defective controller, all end results are valid and final. Disputes will not be entertained.</p><p><strong>Additional Rules</strong></p><p>Every participant will have <strong>10 minutes maximum</strong> of allowance time to show up when calling-out for their match. Players are allowed to select preset formations &amp; may choose Tactical defending mode or Legacy defending mode before the match begins. Failure to show up within the allocated time frame will result in forfeiting the match.</p><ul><li>Prize Pool can be Vouchers, Cash, items, etc. Depending on the tournament structure &amp; will take from 5 up to 30 days work maximum.</li></ul><p>If requested, individuals are required to cooperate with interviews, reporting, video shoots and other activities planned and organized by the organizers. Players also agree to allow the organizers to use their team logos or pictures for promotional purposes. A Team may not make available the same player for media for 4 consecutive Match days.</p><p>By registering for this tournament, the player allows the organizer to use (not limited to) their likeness, video, audio and visual materials, royalty-free and in perpetuity.</p><p>Every participant acknowledges the right for the tournament administration to modify the rules and regulations for adjustments at any time without notice.</p><p>Every participant has to show the needed respect towards officials and other participants. Insults and unfair or disrespectful behavior towards anyone is not tolerated and will be punished. Disrespectful behavior occurring during a game will result in the following:</p><p><strong>Warnings</strong></p><ol><li>First Warning that the player has to acknowledge</li><li>Second Warning will lead to forfeiting the match</li><li>Third Warning will lead to disqualification from the tournament</li></ol><p><strong>In case of extraordinary circumstances, the official may decide to abstain from the punishment.</strong></p><p>Players have 15 minutes after the match concludes to message any tournament official on Discord or WhatsApp and protest the match. If a protest is not brought to the attention of the broadcast tournament official in the allotted time frame, it will not be considered an official protest, and all match results will stay as is. Match protests must include screenshots clearly showing the results of the match/series. Players are responsible for providing proof of match results in case of disputes.</p><p><strong>THIS TOURNAMENT IS NOT AFFILIATED WITH OR SPONSORED BY ELECTRONIC ARTS INC. OR ITS LICENSORS</strong></p><p><strong>NOTE 1: CHANGES MAY HAPPEN, AND PLAYERS ARE EXPECTED TO READ AND UNDERSTAND THE RULES AND REGULATIONS (IF THERE ARE ANY CONFUSIONS, PLEASE APPROACH AN OFFICIAL ADMIN FOR FURTHER CONCERNS)</strong></p><p><strong>NOTE 2: PLAYERS ARE EXPECTED TO READ AND UNDERSTAND THE RULES AND REGULATIONS (IF THERE ARE ANY CONFUSIONS SHOULD YOU APPROACH AN OFFICIAL ADMIN FOR FURTHER CONCERNS)</strong></p>', 1, '2026-06-26 07:17:25', '2026-07-25 11:46:43'),
(15, 1, 'GG testing', 'gg-testing', NULL, NULL, '{\"youtube\":\"https:\\/\\/youtu.be\\/4Q4TuAzkz4k?si=nd0hmOTbZS3qusuw\",\"twitch\":\"https:\\/\\/www.twitch.tv\\/gamaesports_\",\"instagram\":null,\"facebook\":null,\"discord\":null,\"tiktok\":null,\"twitter\":null}', 'https://www.twitch.tv/gamaesports_', NULL, 'team', 2, NULL, 'published', 0, 0, NULL, '2026-07-01 20:32:00', '2026-07-01 20:33:00', '2026-07-01 16:00:00', '2026-07-01 20:40:00', NULL, NULL, 0.00, 5000.00, NULL, NULL, 128, 0, NULL, NULL, 1, '2026-07-15 10:05:12', '2026-07-17 20:52:00'),
(16, 3, 'Spain vs France', 'spain-vs-france', 'tournaments/GtTj7pHth31uCrnmjlUhXZWWN8C1eEckNaf7S4Te.png', 'tournaments/9x20lXgZcoh624693fTzgdZPCMV6a8qjkiqcpVUZ.png', '{\"youtube\":null,\"twitch\":null,\"instagram\":null,\"facebook\":null,\"discord\":null,\"tiktok\":null,\"twitter\":null}', NULL, NULL, 'team', 4, NULL, 'draft', 1, 0, NULL, '2026-07-15 18:52:00', '2026-07-18 15:00:00', '2026-07-19 15:10:00', '2026-07-19 18:53:00', NULL, NULL, 200.00, 2000.00, NULL, NULL, 200, 0, NULL, NULL, 8, '2026-07-15 22:57:41', '2026-07-20 17:23:14'),
(17, 3, 'FIFA worldcup 2026', 'fifa-worldcup-2026', 'tournaments/9WjfeCZufP2ytiv1iLXBr9WwKSdj5LeBzwWLnwgK.png', 'tournaments/Epa6UMNLsFIWGusutyU8v4kx6wKvPTvIIZQNYGXk.png', '{\"youtube\":null,\"twitch\":null,\"instagram\":null,\"facebook\":null,\"discord\":null,\"tiktok\":null,\"twitter\":null}', NULL, NULL, 'solo', 1, NULL, 'draft', 0, 1, NULL, '2026-07-17 15:31:00', '2026-07-20 17:26:00', '2026-07-20 17:41:00', '2026-07-20 17:51:00', NULL, NULL, 200.00, 2000.00, NULL, NULL, 4, 0, '<p>Test</p>', '<ol><li>test</li><li>test 2</li><li>test 3</li></ol>', 1, '2026-07-17 19:33:18', '2026-07-20 21:15:56'),
(21, 3, 'Spain Win', 'spain-win-2', 'tournaments/hUQnTNGbyfYewwBZgQuicPppqnHCT3y7MMGrnNVs.png', 'tournaments/TAt66f9JyT6BwzbEvBaBE4tRXGG5BaKH87JgNpj1.png', '{\"youtube\":null,\"twitch\":null,\"instagram\":null,\"facebook\":null,\"discord\":null,\"tiktok\":null,\"twitter\":null}', NULL, NULL, 'team', 2, NULL, 'published', 0, 0, NULL, '2026-07-20 13:35:00', '2026-07-24 11:35:00', '2026-07-24 11:40:00', '2026-07-25 11:45:00', NULL, NULL, 100.00, 1500.00, NULL, NULL, 2, 0, NULL, NULL, 8, '2026-07-20 17:37:25', '2026-07-23 21:04:21'),
(19, 3, 'Spain win 2026', 'spain-win-2026', 'tournaments/tlCQR8PH1HJ6FGEbmVwgnvELKb1ByZvgTV1XSrp5.png', 'tournaments/ha3i29NDFp2ybxdxMbNhBQaALWaFz8a8HyiLA2pN.png', '{\"youtube\":null,\"twitch\":null,\"instagram\":null,\"facebook\":null,\"discord\":null,\"tiktok\":null,\"twitter\":null}', NULL, NULL, 'team', 2, NULL, 'draft', 0, 0, NULL, '2026-07-20 12:30:00', '2026-07-20 13:20:00', '2026-07-20 13:30:00', '2026-07-20 13:50:00', NULL, NULL, 200.00, 2500.00, NULL, NULL, 2, 0, NULL, NULL, 1, '2026-07-20 16:30:36', '2026-07-20 17:35:12'),
(25, 3, 'FIFA 2026', 'fifa-2026', 'tournaments/iFNuZJHW0BAu5D9u1f9tUHGtH9C2vpQCUz1hTYrV.png', 'tournaments/BUX1BhizyR2IhJerW14d5itBk9e3diFys2YdFwOp.png', '{\"youtube\":null,\"twitch\":null,\"instagram\":null,\"facebook\":null,\"discord\":null,\"tiktok\":null,\"twitter\":null}', NULL, NULL, 'team', 4, NULL, 'published', 0, 0, NULL, '2026-07-27 12:50:00', '2026-07-27 13:06:00', '2026-07-27 13:07:00', '2026-07-27 13:14:00', NULL, NULL, 100.00, 2000.00, 2, 'QA Tester', 4, 4, NULL, NULL, 1, '2026-07-27 16:31:51', '2026-07-27 17:12:03');

-- --------------------------------------------------------

--
-- Table structure for table `tournament_registrations`
--

CREATE TABLE `tournament_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tournament_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('solo','team') NOT NULL DEFAULT 'solo',
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `team_name` varchar(191) DEFAULT NULL,
  `team_tag` varchar(191) DEFAULT NULL,
  `team_logo` varchar(191) DEFAULT NULL,
  `is_captain` tinyint(1) NOT NULL DEFAULT 0,
  `invite_link` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = InActive, 1 = Active',
  `prize_amount` decimal(10,2) DEFAULT NULL,
  `prize_rank` varchar(255) DEFAULT NULL,
  `prize_distributed_at` timestamp NULL DEFAULT NULL,
  `is_prize_claimed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tournament_registrations`
--

INSERT INTO `tournament_registrations` (`id`, `tournament_id`, `user_id`, `type`, `name`, `email`, `phone`, `team_name`, `team_tag`, `team_logo`, `is_captain`, `invite_link`, `status`, `prize_amount`, `prize_rank`, `prize_distributed_at`, `is_prize_claimed`, `created_at`, `updated_at`) VALUES
(1, 25, 12, 'team', 'QA Tester', 'qatest02md@gmail.com', '1234562222', 'QA Tester', '#QAA', NULL, 1, 'MSS3HmYdYiUp6yB1', 1, NULL, NULL, NULL, 0, '2026-07-27 17:00:38', '2026-07-27 17:19:41'),
(6, 26, 12, 'team', 'QA Tester', 'qatest02md@gmail.com', '1234562222', 'QA', '#QA', NULL, 0, 'IIPnTdHhR5MIm7qH', 1, 500.00, '1', '2026-07-27 20:12:45', 0, '2026-07-27 19:03:11', '2026-07-27 20:12:45'),
(7, 26, 6, 'team', 'peter ', 'peter004@yopmail.com', '1231231234', 'QA', '#QA', NULL, 0, 'IIPnTdHhR5MIm7qH', 1, 500.00, '2', '2026-07-27 20:12:45', 0, '2026-07-27 19:21:26', '2026-07-27 20:12:45'),
(5, 25, 3, 'team', 'Shekhar Saini', 'shekhar.saini38@gmail.com', '9876543789', 'QA Tester', '#QAA', NULL, 0, 'MSS3HmYdYiUp6yB1', 1, NULL, NULL, NULL, 0, '2026-07-27 17:06:54', '2026-07-27 17:19:41'),
(8, 26, 7, 'team', 'John ', 'john004@yopmail.com', '1231231235', 'QA', '#QA', NULL, 0, 'IIPnTdHhR5MIm7qH', 1, 200.00, '3', '2026-07-27 20:12:45', 0, '2026-07-27 19:21:39', '2026-07-27 20:12:45'),
(9, 1, 2, 'team', 'Abhay Chauhan', 'abhay.chauhan.markup@gmail.com', '9528102824', 'QA Tester1', '#QAB', NULL, 1, 'ByVHiWjIFRhKz5FL', 1, NULL, NULL, NULL, 0, '2026-07-27 19:34:22', '2026-07-27 19:34:22'),
(10, 26, 2, 'team', 'Abhay Chauhan', 'abhay.chauhan.markup@gmail.com', '9528102824', 'QA', '#QA', NULL, 0, 'IIPnTdHhR5MIm7qH', 1, 300.00, '4', '2026-07-27 20:12:45', 0, '2026-07-27 19:40:01', '2026-07-27 20:12:45'),
(11, 26, 8, 'team', 'Kling ', 'kling004@yopmail.com', '1231231236', 'QA', '#QA', NULL, 1, 'IIPnTdHhR5MIm7qH', 1, 500.00, '5', '2026-07-27 20:12:45', 0, '2026-07-27 19:40:11', '2026-07-27 20:12:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) DEFAULT NULL,
  `username` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(191) NOT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = Inactive, 1 = Active',
  `api_token` varchar(80) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `country_code`, `mobile`, `password`, `otp`, `otp_expires_at`, `status`, `api_token`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Kushank', 'Rajput', 'kush', 'kushankrajput16@gmail.com', NULL, '9632587410', '$2y$12$CWByFCxMbhq64WrxuSNL4ul9/Cc/5qn2R4RVSdKrN0IkvCVczKIaC', NULL, NULL, 1, NULL, NULL, '2026-03-13 13:45:25', '2026-07-20 18:58:06'),
(2, 'Abhay', 'Chauhan', 'don', 'abhay.chauhan.markup@gmail.com', '+1', '9528102824', '$2y$12$9vwoAclJ1IiYYSzPTiJ/COCI6K3EBrnLQPn4CUdtz5GDwG0nn5Oq6', NULL, NULL, 1, NULL, NULL, '2026-03-13 13:46:27', '2026-07-15 20:05:54'),
(3, 'Shekhar', 'Saini', 'shekhar020', 'shekhar.saini38@gmail.com', NULL, '9876543789', '$2y$12$c3qbasaEpj4uTTg0e2fkeeehpPx0uhJrGEYJO8TR.bJ4rMMZbX0pO', NULL, NULL, 1, NULL, NULL, '2026-05-01 22:47:20', '2026-05-07 07:00:32'),
(4, 'jabor', 'm', 'smarttest', 'jabor5151@gmail.com', '+974', '55004411', '$2y$12$ftPcnRh.FbDQkWtmcyCiC.vFooT.066OMytOm2PBRhFwFbQKYUViC', NULL, NULL, 1, NULL, NULL, '2026-05-05 02:34:54', '2026-07-16 08:13:13'),
(12, 'QA', 'Tester', 'QA', 'qatest02md@gmail.com', '+974', '1234562222', '$2y$12$abvKfKOEt8vckQztKAC/dORS8uRyZXqsrQ0H8Tfz4n9oiUM6TAPCG', NULL, NULL, 1, NULL, NULL, '2026-07-15 21:49:25', '2026-07-22 16:37:08'),
(6, 'peter', NULL, 'peter_004', 'peter004@yopmail.com', NULL, '1231231234', '$2y$12$/ADhjaH0tLu9H0WqSDZcGuJOl9zMAEHMU2hgF2B2FD5qWaH.ajAuy', NULL, NULL, 1, NULL, NULL, '2026-05-07 20:32:15', '2026-05-07 20:32:15'),
(7, 'John', NULL, 'John_004', 'john004@yopmail.com', NULL, '1231231235', '$2y$12$uBrHEBMawApmxnQB5US1UeYmqHzgD4ii1mZN5E3hlLwBlhM/2Wb8e', NULL, NULL, 1, NULL, NULL, '2026-05-07 20:33:54', '2026-05-07 20:33:54'),
(8, 'Kling', NULL, 'kling_004', 'kling004@yopmail.com', NULL, '1231231236', '$2y$12$5kugu165o0h7IxQr39q/qOHSiNOU/eZJZ7Om2i9NJok8SrMGfPIQG', NULL, NULL, 1, NULL, NULL, '2026-05-07 20:35:44', '2026-06-30 17:58:00'),
(10, 'Jack', 'AlBlushi', 'JackAlBlushi', 'jackalblushilive@gmail.com', NULL, '70080347', '$2y$12$PhLGcGlarP0PxT2xYe5oreMTFGO.9aVsslfpI/nl6auszQVID3qdu', NULL, NULL, 1, NULL, NULL, '2026-07-15 10:31:13', '2026-07-15 10:32:49'),
(11, 'abhay', 'chauhan', 'Abhi123', 'abhay@gmail.com', '+1', '12334322333', '$2y$12$KdoTS6J62Xp.5EAzYpelFOdGCFouyG7CyHXWJb87GO8MyHqourJIi', NULL, NULL, 1, NULL, NULL, '2026-07-15 20:48:54', '2026-07-15 20:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `id_proof` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `profile_image`, `id_proof`, `created_at`, `updated_at`) VALUES
(1, 4, 'profiles/dPmFBSR1VWJJjtGflyx1MB9mjw9dOMXmZ3QlQvC0.png', NULL, '2026-05-05 02:36:26', '2026-05-05 02:36:26'),
(2, 5, 'profiles/R5TToWniomkti3X3ArO7VrZGnKHAIR2KJCru2KHc.png', NULL, '2026-05-07 19:04:02', '2026-05-26 20:01:20'),
(3, 2, 'profiles/gF7eHZXYrMsDiLOm7BfhgPMzqEdpZJeQg8mwujz9.webp', 'id_proofs/aTqjgOLoGrCD18aCCQx6Iv5fJ7B1n03qF1H4Ewfg.jpg', '2026-05-26 20:32:39', '2026-07-09 20:10:57'),
(5, 11, NULL, NULL, '2026-07-15 20:51:28', '2026-07-15 20:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_social_links`
--

CREATE TABLE `user_social_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `discord` varchar(255) DEFAULT NULL,
  `twitch` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_social_links`
--

INSERT INTO `user_social_links` (`id`, `user_id`, `facebook`, `instagram`, `twitter`, `youtube`, `discord`, `twitch`, `created_at`, `updated_at`) VALUES
(1, 4, NULL, 'https://www.instagram.com/smart_gama/', NULL, NULL, NULL, NULL, '2026-05-05 02:36:47', '2026-05-05 02:36:47'),
(2, 5, 'https://facebook.com/johndoe', 'https://instagram.com/johndoe', NULL, NULL, NULL, NULL, '2026-05-26 20:01:20', '2026-05-26 20:01:20'),
(3, 2, 'https://www.markupdesigns.net/qec-web/', 'https://www.youtube.com/shorts/dH-3EB6XX3I', 'https://www.youtube.com/shorts/dH-3EB6XX3', 'https://www.youtube.com/shorts/dH-3EB6XX3I', 'https://www.youtube.com/shorts/dH-3EB6XX3I', 'https://www.youtube.com/shorts/dH-3EB6XX3I', '2026-05-26 20:32:39', '2026-05-26 20:32:39'),
(5, 11, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-15 20:51:28', '2026-07-15 20:51:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abouts`
--
ALTER TABLE `abouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `about_sections`
--
ALTER TABLE `about_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `admin_password_otps`
--
ALTER TABLE `admin_password_otps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `challenges`
--
ALTER TABLE `challenges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_requests`
--
ALTER TABLE `contact_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_settings`
--
ALTER TABLE `contact_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dashboard_images`
--
ALTER TABLE `dashboard_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `featured_events`
--
ALTER TABLE `featured_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_settings`
--
ALTER TABLE `footer_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `games_slug_unique` (`slug`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `live_streams`
--
ALTER TABLE `live_streams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `live_streams_tournament_id_foreign` (`tournament_id`),
  ADD KEY `live_streams_game_id_foreign` (`game_id`),
  ADD KEY `live_streams_platform_channel_name_index` (`platform`,`channel_name`),
  ADD KEY `live_streams_is_live_index` (`is_live`);

--
-- Indexes for table `logos`
--
ALTER TABLE `logos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maps`
--
ALTER TABLE `maps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `maps_game_id_name_unique` (`game_id`,`name`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `match_highlights`
--
ALTER TABLE `match_highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `match_highlight_contents`
--
ALTER TABLE `match_highlight_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mh_contents` (`match_highlight_id`);

--
-- Indexes for table `match_highlight_images`
--
ALTER TABLE `match_highlight_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mh_images` (`match_highlight_id`);

--
-- Indexes for table `match_maps`
--
ALTER TABLE `match_maps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `match_map_vetoes`
--
ALTER TABLE `match_map_vetoes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `fk_news_type_id` (`type_id`);

--
-- Indexes for table `news_types`
--
ALTER TABLE `news_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `news_user_actions`
--
ALTER TABLE `news_user_actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_news_unique` (`user_id`,`news_id`),
  ADD KEY `news_id` (`news_id`);

--
-- Indexes for table `page_settings`
--
ALTER TABLE `page_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `previous_works`
--
ALTER TABLE `previous_works`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permission_unique` (`role`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tournaments_slug_unique` (`slug`),
  ADD KEY `tournaments_game_id_foreign` (`game_id`),
  ADD KEY `tournaments_created_by_foreign` (`created_by`);

--
-- Indexes for table `tournament_registrations`
--
ALTER TABLE `tournament_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invite_user_unique` (`invite_link`,`user_id`),
  ADD KEY `tournament_registrations_tournament_id_foreign` (`tournament_id`),
  ADD KEY `tournament_registrations_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_mobile_unique` (`mobile`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`),
  ADD KEY `users_email_otp_index` (`email`,`otp`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_social_links`
--
ALTER TABLE `user_social_links`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abouts`
--
ALTER TABLE `abouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `about_sections`
--
ALTER TABLE `about_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admin_password_otps`
--
ALTER TABLE `admin_password_otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `challenges`
--
ALTER TABLE `challenges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_requests`
--
ALTER TABLE `contact_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `contact_settings`
--
ALTER TABLE `contact_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dashboard_images`
--
ALTER TABLE `dashboard_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `featured_events`
--
ALTER TABLE `featured_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `footer_settings`
--
ALTER TABLE `footer_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `live_streams`
--
ALTER TABLE `live_streams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `logos`
--
ALTER TABLE `logos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `maps`
--
ALTER TABLE `maps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `match_highlights`
--
ALTER TABLE `match_highlights`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `match_highlight_contents`
--
ALTER TABLE `match_highlight_contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `match_highlight_images`
--
ALTER TABLE `match_highlight_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `match_maps`
--
ALTER TABLE `match_maps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `match_map_vetoes`
--
ALTER TABLE `match_map_vetoes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `news_types`
--
ALTER TABLE `news_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `news_user_actions`
--
ALTER TABLE `news_user_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `page_settings`
--
ALTER TABLE `page_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=345;

--
-- AUTO_INCREMENT for table `previous_works`
--
ALTER TABLE `previous_works`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=452;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tournament_registrations`
--
ALTER TABLE `tournament_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_social_links`
--
ALTER TABLE `user_social_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
