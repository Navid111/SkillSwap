-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 07:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skill_swap`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `tutorial_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message`, `sent_at`) VALUES
(1, 6, 1, 'Hello, please terminate my account.', '2025-04-28 23:08:22');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `tutorial_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutorials`
--

CREATE TABLE `tutorials` (
  `tutorial_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutorials`
--

INSERT INTO `tutorials` (`tutorial_id`, `user_id`, `title`, `description`, `created_at`) VALUES
(1, 2, 'Building Your First Website with HTML and CSS', 'Learn the basics of creating a simple and responsive website using only HTML and CSS. This tutorial will cover structure, styling, and simple layout techniques.', '2025-04-28 23:00:32'),
(2, 2, 'Introduction to Python Programming for Beginners', 'Start coding with Python! This tutorial walks you through setting up Python, writing your first script, and understanding fundamental concepts like variables, loops, and functions.', '2025-04-28 23:00:52'),
(3, 3, 'Getting Started with Adobe Photoshop: A Beginner\'s Guide', 'Explore Photoshop’s basic tools and features. This tutorial will help you perform simple edits, manage layers, and create your first digital artwork.\r\n\r\n', '2025-04-28 23:01:58'),
(4, 3, 'How to Create a Mobile App with Flutter', 'Discover how to build a cross-platform mobile app using Google\'s Flutter framework. This guide will take you through setting up your environment and building a simple app.', '2025-04-28 23:02:36'),
(5, 4, 'Mastering the Basics of Microsoft Excel', 'Learn how to navigate Microsoft Excel, use formulas, create charts, and manage simple data sets for personal or professional use.', '2025-04-28 23:03:45'),
(6, 4, 'Starting a Blog with WordPress: Step-by-Step', 'This tutorial guides you through choosing a domain, setting up WordPress, selecting a theme, and publishing your first blog post.', '2025-04-28 23:04:04'),
(7, 5, 'Basic Photography Tips: How to Take Better Photos Today', 'Improve your photography skills with simple tips on composition, lighting, and camera settings, whether you’re using a smartphone or DSLR.', '2025-04-28 23:05:09'),
(8, 5, 'Creating Your First Game with Unity 3D', 'Get an introduction to Unity 3D by developing a basic 2D platformer. Learn about scenes, objects, scripting, and basic physics.\r\n\r\n', '2025-04-28 23:05:31'),
(9, 6, 'How to Set Up a Home Network for Beginners', 'Learn the steps to set up your own home network, including selecting the right hardware, securing your connection, and optimizing your Wi-Fi.', '2025-04-28 23:06:12'),
(10, 6, 'Crafting Your First Email Marketing Campaign with Mailchimp', 'A beginner-friendly guide to setting up an account, designing an email, creating a mailing list, and launching your first email marketing campaign.', '2025-04-28 23:06:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('teacher','student','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin', 'admin@user.com', '$2y$10$upnc87Pa1pquj4bJqawC3OgCXrSNC0e3ROiEw3mZgwU56ovuahojC', 'admin'),
(2, 'Talal', 'talal@user.com', '$2y$10$KquaMeOystUX9R8h0roD6uZxn7BKBAk6y/xMuqcTLaUbEcukCxrKW', 'teacher'),
(3, 'Navid', 'navid@user.com', '$2y$10$rXkFxR5CAJejdpmjVBk0WeHANun.IDUz80NXnZqKobCUaobX1OzAq', 'teacher'),
(4, 'Synthia', 'synthia@user.com', '$2y$10$87Tml5RH49sNrrGoO1MhuOwGuj0cWUaC5CEieylw8ZMhYZDt4jMxG', 'teacher'),
(5, 'Joti', 'joti@user.com', '$2y$10$kIt68JEpmR19ly0oRuXMTe6wMF2DFCjIGJrtKCl6iVIRUu1s2rTAS', 'teacher'),
(6, 'Test', 'test@user.com', '$2y$10$vfHWeGcs2Yl/XiTyM7262uh2v5KlfYt0J3i8HIL5qDpoiI7GCIoEC', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `tutorial_id` (`tutorial_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `tutorial_id` (`tutorial_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tutorials`
--
ALTER TABLE `tutorials`
  ADD PRIMARY KEY (`tutorial_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tutorials`
--
ALTER TABLE `tutorials`
  MODIFY `tutorial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`tutorial_id`) REFERENCES `tutorials` (`tutorial_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`tutorial_id`) REFERENCES `tutorials` (`tutorial_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tutorials`
--
ALTER TABLE `tutorials`
  ADD CONSTRAINT `tutorials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
