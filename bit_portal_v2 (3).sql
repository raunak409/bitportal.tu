-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2026 at 08:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bit_portal_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `mock_questions`
--

CREATE TABLE `mock_questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `opt_a` varchar(300) NOT NULL,
  `opt_b` varchar(300) NOT NULL,
  `opt_c` varchar(300) NOT NULL,
  `opt_d` varchar(300) NOT NULL,
  `correct_opt` char(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mock_tests`
--

CREATE TABLE `mock_tests` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `duration_mins` int(11) NOT NULL DEFAULT 10,
  `semester` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mock_test_questions`
--

CREATE TABLE `mock_test_questions` (
  `id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `opt_a` varchar(300) NOT NULL,
  `opt_b` varchar(300) NOT NULL,
  `opt_c` varchar(300) NOT NULL,
  `opt_d` varchar(300) NOT NULL,
  `correct_option` char(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `semester`, `title`, `filename`, `created_at`) VALUES
(1, 1, 'Introduction to Information Technology', '1775652382_iit_book.pdf', '2026-04-08 12:46:22'),
(2, 1, 'C Programming', '1775652400_c_pro.pdf', '2026-04-08 12:46:40'),
(3, 1, 'Digital Logic', '1775652426_digital_logic_book.pdf', '2026-04-08 12:47:06'),
(4, 1, 'Basic Mathematics', '1775652443_Thomas_Calculus_Basic_mathematics.pdf', '2026-04-08 12:47:23'),
(5, 1, 'Sociology', '1775652466_sociology.pdf', '2026-04-08 12:47:46'),
(6, 2, 'Microprocessor and Computer Architecture', '1775652509_MICROPROCESSOR.pdf', '2026-04-08 12:48:29'),
(7, 2, 'Discrete Structure', '1775652543_Discrete_Mathematical_Structures-Kolman.pdf', '2026-04-08 12:49:03'),
(8, 2, 'Object Oriented Programming', '1775652571_oops.pdf', '2026-04-08 12:49:31'),
(9, 2, 'Basic Statistics', '1775652870_Basic_Statistics.pdf', '2026-04-08 12:54:30'),
(10, 2, 'Economics', '1775652897_Economics.pdf', '2026-04-08 12:54:57'),
(11, 3, 'Data Structure and Algorithms', '1775658039_Data-Structures-and-Algorithms-BIT201__1_.pdf', '2026-04-08 14:20:39'),
(12, 3, 'Database Management System', '1775658143_database_1.pdf', '2026-04-08 14:22:23'),
(13, 3, 'Numerical Methods', '1775658213_numerical_method.pdf', '2026-04-08 14:23:33'),
(14, 3, 'Operating System', '1775658233_Modern.Operating.Systems.2nd.Ed_.by_.Tanenbaum-not-scanned-1-1.pdf', '2026-04-08 14:23:53'),
(15, 4, 'Web Technology I', '1775658347_web_tech_1.pdf', '2026-04-08 14:25:47'),
(16, 4, 'Artificial Intelligence', '1775658371_AI.pdf', '2026-04-08 14:26:11'),
(17, 4, 'Network and Data Communications', '1775658432__McGraw-Hill_Forouzan_Networking__Behrouz_A._Forouzan_-_Data_Communications_and_Networking_-McGraw-Hill_Higher_Education__2007_.pdf', '2026-04-08 14:27:12'),
(18, 5, 'Web Technology II', '1775658487_web_tech_2.pdf', '2026-04-08 14:28:07'),
(19, 5, 'Software Engineering', '1775658508_Software_Engineering_-_Ian_Sommerville.pdf', '2026-04-08 14:28:28'),
(20, 5, 'Information Security', '1775658527_information_security_Security_pdf_DONE.pdf', '2026-04-08 14:28:47'),
(21, 5, 'Computer Graphics', '1775658543_Computer_Graphics_C_Version_by_Donald_Hearn___M_Pauline_Baker_II_Edition.pdf', '2026-04-08 14:29:03'),
(22, 5, 'Technical Writing', '1775658610_Handbook-of-Technical-Writing-9th-Edition.pdf', '2026-04-08 14:30:10'),
(23, 4, 'Operations Research', '1775658627_9-Operations-Research-An-Introduction-10th-Ed.-Hamdy-A-Taha.pdf', '2026-04-08 14:30:27'),
(24, 6, 'Net Centric Computing', '1775709903_net_centric.pdf', '2026-04-09 04:45:03'),
(25, 6, 'Database Administration', '1775709932_Oracle_19c_Database_Administration_Oracle_Simplified_by_Tanveer_A.__A.__Tanveer___z-lib.org_.epub.pdf', '2026-04-09 04:45:32');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `badge` varchar(100) DEFAULT 'Notice',
  `filename` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `badge`, `filename`, `created_at`) VALUES
(1, 'BIT 3rd Semester Exam Centre Notice', 'Exam Centre', '1775659065_centre.jpg', '2026-04-08 14:37:45');

-- --------------------------------------------------------

--
-- Table structure for table `past_questions`
--

CREATE TABLE `past_questions` (
  `id` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `subject_name` varchar(200) NOT NULL,
  `year` varchar(20) DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `syllabus`
--

CREATE TABLE `syllabus` (
  `id` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `subject_name` varchar(200) NOT NULL,
  `credit` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `filename` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `syllabus`
--

INSERT INTO `syllabus` (`id`, `semester`, `subject_name`, `credit`, `created_at`, `filename`) VALUES
(6, 1, 'Introduction to Information Technology', '3', '2026-04-08 09:35:14', '1775640914_BIT101_-_Introduction_to_Information_Technology_-_Microsyllabus_-_bitportal.pdf'),
(7, 1, 'Basic Mathematics', '3', '2026-04-08 09:42:44', '1775641364_MTH104_-_Basic_Mathematics_-_Mircosyllabus_-_bitportal.pdf'),
(8, 1, 'C Programming', '3', '2026-04-08 09:43:01', '1775641381_BIT102_-_C_Programming_-_Microsyllabus_-_bitportal.pdf'),
(9, 1, 'Digital Logic', '3', '2026-04-08 09:43:28', '1775641408_BIT103_-_Digital_Logic_-_Microsyllabus_-_bitportal.pdf'),
(10, 1, 'Sociology', '3', '2026-04-08 09:44:10', '1775641450_SCO105_-_Sociology_-_Microsyllabus_-_bitportal.pdf'),
(11, 2, 'Microprocessor and Computer Architecture', '3', '2026-04-08 09:45:57', '1775641557_Microprocessor-and-Computer-Architecture-BIT151.pdf'),
(12, 2, 'Discrete Structure', '3', '2026-04-08 09:47:01', '1775641621_Discrete-Structure-BIT152_bitportal.pdf'),
(13, 2, 'Object Oriented Programming', '3', '2026-04-08 09:48:42', '1775641722_O-O-P_Syllabus-BIT153_bitportal.pdf'),
(14, 2, 'Basic Statistics', '3', '2026-04-08 09:50:03', '1775641803_Basic-Statistics-STA154_bit_portal.pdf'),
(15, 2, 'Economics', '3', '2026-04-08 09:50:53', '1775641853_Economics-ECO155_bitportal.pdf'),
(16, 3, 'Data Structure and Algorithms', '3', '2026-04-08 09:53:16', '1775641996_Data-Structures-and-Algorithms-BIT201__1_.pdf'),
(17, 3, 'Database Management System', '3', '2026-04-08 09:54:05', '1775642045_Database_Administration_BIT352.pdf'),
(18, 3, 'Numerical Methods', '3', '2026-04-08 09:55:32', '1775642132_Numerical-Methods-BIT203_bitportal.pdf'),
(19, 3, 'Operating System', '3', '2026-04-08 09:56:05', '1775642165_Operating-Systems-BIT204_bitportal.pdf'),
(20, 3, 'Principles of Management', '3', '2026-04-08 09:57:25', '1775642245_Principles-of-Management-MGT205.pdf'),
(21, 4, 'Web Technology I', '3', '2026-04-08 09:58:07', '1775642287_BIT251_Web_Technology_I.pdf'),
(22, 4, 'Artificial Intelligence', '3', '2026-04-08 09:58:59', '1775642339_BIT252_Artificial_Intelligence.pdf'),
(23, 4, 'System Analysis and Design', '3', '2026-04-08 09:59:40', '1775642380_BIT253_Systems_Analysis_and_Design.pdf'),
(24, 4, 'Network and Data Communications', '3', '2026-04-08 10:00:47', '1775642447_BIT_451_-_Network_System_Administration.pdf'),
(25, 4, 'Operations Research', '3', '2026-04-08 10:01:22', '1775642482_ORS255_Operations_Research.pdf'),
(26, 5, 'Web Technology II', '3', '2026-04-08 10:02:29', '1775642549_Web_Technology_II_BIT301.pdf'),
(27, 5, 'Software Engineering', '3', '2026-04-08 10:55:57', '1775645757_Software_Engineering_BIT302.pdf'),
(28, 5, 'Information Security', '3', '2026-04-08 10:56:28', '1775645788_Information_Security_BIT303.pdf'),
(29, 5, 'Computer Graphics', '3', '2026-04-08 10:57:01', '1775645821_Computer_Graphics_BIT304.pdf'),
(30, 5, 'Technical Writing', '3', '2026-04-08 10:57:31', '1775645851_Technical_Writing_ENG305.pdf'),
(31, 5, 'Net-Centric Computing', '3', '2026-04-08 11:00:53', '1775646053_NET_Centric_Computing_BIT351.pdf'),
(32, 5, 'Database Administration', '3', '2026-04-08 11:02:05', '1775646125_Database-Management-System-BIT202_bitportal.pdf'),
(33, 5, 'Management Information System', '3', '2026-04-08 11:02:32', '1775646152_Management_Information_System_BIT353.pdf'),
(34, 5, 'Research Methodology', '3', '2026-04-08 11:02:55', '1775646175_Research_Methodology_RSM354.pdf'),
(35, 5, 'Geographical Information System', '3', '2026-04-08 11:03:27', '1775646207_Geographical_Information_System_BIT355.pdf'),
(36, 5, 'Multimedia Computing', '3', '2026-04-08 11:04:40', '1775646280_Multimedia_Computing_BIT356.pdf'),
(37, 5, 'Wireless Networking', '3', '2026-04-08 11:05:27', '1775646327_Wireless_Networking_BIT357.pdf'),
(38, 5, 'Society And Ethics In IT', '3', '2026-04-08 11:09:47', '1775646587_Society_and_Ethics_in_IT_BIT358.pdf'),
(39, 7, 'Advance Java Programming', '3', '2026-04-08 11:38:46', '1775648326_Advanced-Java-Programming-BIT401.pdf'),
(40, 7, 'Software Project Management', '3', '2026-04-08 12:39:48', '1775651988_BIT_402_-_Software_Project_Management.pdf'),
(41, 7, 'E-Commerce', '3', '2026-04-08 12:40:53', '1775652053_BIT_403_-_E-Commerce.pdf'),
(42, 7, 'Cloud Computing', '3', '2026-04-08 12:41:18', '1775652078_BIT_408_-_Cloud_Computing.pdf'),
(43, 7, 'Mobile Application Development', '3', '2026-04-08 12:41:56', '1775652116_BIT_406_-_Mobile_Application_Development.pdf'),
(44, 8, 'Network System Administration', '3', '2026-04-08 12:42:50', '1775652170_BIT_451_-_Network_System_Administration.pdf'),
(45, 8, 'E-Governance', '3', '2026-04-08 12:43:09', '1775652189_BIT_452_-_E-Governance.pdf'),
(46, 8, 'Data Warehousing And Data Mining', '3', '2026-04-08 12:43:34', '1775652214_BIT_454_-_Data_Warehousing_And_Data_Mining.pdf'),
(47, 8, 'Network System Administration', '3', '2026-04-08 12:44:02', '1775652242_BIT_451_-_Network_System_Administration.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@bit.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-04-07 03:29:17'),
(2, 'raunak123', 'raunak123@gmail.com', '$2y$10$C336qiuVv3zAJZAdQZ3nXufrBMSka0Uhqwbr5EjTwA.u9kUw/m4jO', 'user', '2026-04-07 03:46:35'),
(3, 'prince', 'prince123@gmail.com', '$2y$10$.Ijm1of3l.xbkKXJTeASzu6ZAi49aZFVbNAJ86NeXZ/E.QM/xb6GW', 'user', '2026-04-07 03:57:31'),
(4, 'raunakhubroh', 'raunak123456@gmail.com', '$2y$10$4FCfE4vLjyu3l3P6vjHRgOPHfxTOen7lIhXi9cxdRpy6VjBQEV6Qi', 'user', '2026-05-10 19:23:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mock_questions`
--
ALTER TABLE `mock_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mock_tests`
--
ALTER TABLE `mock_tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mock_test_questions`
--
ALTER TABLE `mock_test_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `past_questions`
--
ALTER TABLE `past_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `syllabus`
--
ALTER TABLE `syllabus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mock_questions`
--
ALTER TABLE `mock_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mock_tests`
--
ALTER TABLE `mock_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mock_test_questions`
--
ALTER TABLE `mock_test_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `past_questions`
--
ALTER TABLE `past_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `syllabus`
--
ALTER TABLE `syllabus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mock_test_questions`
--
ALTER TABLE `mock_test_questions`
  ADD CONSTRAINT `mock_test_questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `mock_tests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
