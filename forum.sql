-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2023 at 10:04 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forum`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `poster` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `title`, `poster`, `comment`, `created_at`) VALUES
(5, 'PHP', 'Frano Jurković', 'Nije baš ima dosta stvari.', '2023-08-12 13:03:14'),
(6, 'PHP', '11', 'Nisam baš siguran.', '2023-08-12 13:03:39'),
(8, 'PHP', 'Frano Jurković', 'oaphapwiihd', '2023-08-13 06:25:55'),
(9, 'PHP', 'Frano', 'fhiwapfhawp', '2023-09-20 09:35:06'),
(10, 'PHP', 'Frano Jurković', '', '2023-09-30 16:03:07'),
(15, 'Css 2024', 'Frano Jurković', 'a i nije', '2023-09-30 16:51:04'),
(21, 'novo', 'ovan', 'o je', '2023-09-30 18:46:01'),
(24, 'HTML', 'fran', 'Mene zanima koja je zadnja verzija HTML programskog jezika.', '2023-10-01 07:48:11'),
(25, 'HTML', 'Frano Jurković 1', 'Zadnja verzija HTML je 5.', '2023-10-01 07:50:01'),
(26, 'Node.js', 'Frano Jurković 1', 'Evo jednog primjera od node.js:\r\nconst http = require(\'node:http\');\r\n\r\nconst hostname = \'127.0.0.1\';\r\nconst port = 3000;\r\n\r\nconst server = http.createServer((req, res) => {\r\n  res.statusCode = 200;\r\n  res.setHeader(\'Content-Type\', \'text/plain\');\r\n  res.end(\'Hello, World!\\n\');\r\n});\r\n\r\nserver.listen(port, hostname, () => {\r\n  console.log(`Server running at http://${hostname}:${port}/`);\r\n});\r\nKako ispisati pozdrav svijetu preko node.js', '2023-10-01 07:56:24'),
(30, 'HTML', 'Ivan', 'html Označava početak i kraj HTML dokumenta, pa se tako početna oznaka nalazi na početku, a završna na kraju dokumenta.', '2023-10-01 08:06:52'),
(31, 'ads', 'Ivan', 'adnaifpwafibwiapf', '2023-10-01 08:22:59'),
(32, 'ads', 'fran', 'idbaiwdbwaibdawoi', '2023-10-01 18:12:10');

-- --------------------------------------------------------

--
-- Table structure for table `posting`
--

CREATE TABLE `posting` (
  `poster` varchar(30) NOT NULL,
  `title` varchar(30) NOT NULL,
  `post_desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posting`
--

INSERT INTO `posting` (`poster`, `title`, `post_desc`) VALUES
('Frano Jurković 1', 'HTML', 'HTML je kratica za HyperText Markup Language, što znači prezentacijski jezik za izradu web stranica. Hipertekst dokument stvara se pomoću HTML jezika. HTML jezikom oblikuje se sadržaj i stvaraju se hiperveze hipertekst dokumenta.'),
('fran', 'Node.js', 'Prevedeno s engleskog jezika-Node.js je višeplatformsko serversko okruženje otvorenog koda koje može raditi na Windows, Linux, Unix, macOS i više. Node.js je back-end JavaScript runtime okruženje, radi na V8 JavaScript motoru i izvršava JavaScript kod izvan web pretraživača.'),
('ivan', 'Javascript', 'Javascript i java nije isto.');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(6) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'Frano Jurković 1', 'frano@gmail.com', '$2y$10$jHAsa0ZcZpHu64J8qKz.Gu6l8E/pgjrP196z9sCoCUoiL/WfEli6.', 'admin'),
(2, 'Fran', 'fran@gmail.com', '$2y$10$YRUyspmaKZVW7Xm/qc4/ju6e9Hiy1t7Hg6oVV/80CahE..SwR6pQ6', 'user'),
(4, 'Ivan', 'ivan@gmail.com', '$2y$10$gtXR.pHUIYsrJnuDm/KB6eKgNNJAnaqxSKUSs1YoB1wJXobMlzQ6y', 'user'),
(5, 'Krešo', 'kreso@gmail.com', '$2y$10$n5LhqV8ESImxZcJfVavOGu5fQ/5TyCd/E9Bx/JZ6e5APUGtC2nYJC', 'admin'),
(6, 'Marko', 'marko@gmail.com', '$2y$10$fFslIXqFyjp806K7zHnNZe3Q.jzT4UrtGv3gqocdlB04to3uOfKr.', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
