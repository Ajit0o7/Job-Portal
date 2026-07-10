-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2024 at 04:36 PM
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
-- Database: `portal_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `A_id` int(11) NOT NULL,
  `A_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`A_id`, `A_name`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

CREATE TABLE `application` (
  `application_id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `Seeker_id` int(11) DEFAULT NULL,
  `employer_id` int(11) DEFAULT NULL,
  `date_applied` date DEFAULT NULL,
  `status` enum('applied','accepted','rejected') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application`
--

INSERT INTO `application` (`application_id`, `job_id`, `Seeker_id`, `employer_id`, `date_applied`, `status`) VALUES
(4, 1, 3, 2, '2024-08-17', ''),
(10, 4, 3, 2, '2024-08-17', 'applied'),
(11, 8, 3, 2, '2024-08-17', 'applied'),
(12, 5, 3, 1, '2024-09-26', 'applied'),
(13, 7, 3, 1, '2024-10-22', 'applied'),
(14, 9, 3, 2, '2024-10-24', ''),
(15, 9, 3, 2, '2024-10-24', 'applied'),
(16, 7, 19, 1, '2024-10-24', 'applied'),
(17, 20, 19, 7, '2024-10-24', 'applied'),
(18, 20, 3, 7, '2024-10-25', 'applied');

-- --------------------------------------------------------

--
-- Table structure for table `employerlogin`
--

CREATE TABLE `employerlogin` (
  `employer_id` int(11) NOT NULL,
  `Fullname_E` varchar(255) DEFAULT NULL,
  `Email_Address_E` varchar(255) DEFAULT NULL,
  `Password_E` varchar(255) DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `Contact` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employerlogin`
--

INSERT INTO `employerlogin` (`employer_id`, `Fullname_E`, `Email_Address_E`, `Password_E`, `Location`, `Contact`) VALUES
(1, 'Nhujan', 'nhujanm@gmail.com', '75ed250302dac5cef5dda7722493c3d8', 'Kathmandu', '9876543210'),
(2, 'NM company', 'nhujanmaharjan58@gmail.com', '$2y$10$BO0SO9gUq/TeAHOlGsnuouBFk3T1Ouq8v86ycpe4APnzD5l0O2gD.', 'D marg', '9818213212'),
(3, 'ACHS', 'achs@gmail.com', '$2y$10$9tb3RiclX2FnBCEdj.jzB.l9vkSDqLBJb4ysqPWZHnhJ4o8fIjm.S', 'Ekantakuna', '876543210'),
(4, 'BasantaCo', 'basata@gmail.com', '$2y$10$gXbPoTHAIByUWvzTenLCf.yQW0IG8/fgLCmXyMSYVrNOizwECBXYG', 'asdas', '987654321'),
(6, 'Ohana Surf', 'info@ohanasurf.com', '$2y$10$vybYHkcDCKSEUxrNDE2mDurK..dk46Gx.dmBb93uz6Yq7a.Mf9ktK', 'New Road', '987654321'),
(7, 'RMA Engineering', 'rma@infonet.com', '$2y$10$im/VIgDAvrWG/8EC/kFapu0fSd10tCiUhlgqyxd497eAFKsjLFSjm', 'Baneshwor', '8521364790');

-- --------------------------------------------------------

--
-- Table structure for table `job_postings`
--

CREATE TABLE `job_postings` (
  `job_id` int(11) NOT NULL,
  `employer_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `date_posted` date DEFAULT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `status` enum('open','close') DEFAULT NULL,
  `workexperience` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_postings`
--

INSERT INTO `job_postings` (`job_id`, `employer_id`, `title`, `description`, `requirements`, `location`, `salary`, `date_posted`, `skills`, `status`, `workexperience`) VALUES
(1, 2, 'Software Engineer', 'A experienced enginer', 'bachelor passed', 'baneshowor', 50000.00, '2024-08-14', 'HTML, CSS, PHP', 'open', 5),
(3, 1, 'Software Engineer', 'Develop and maintain software applications.', 'Bachelor\'s in Computer Science, 2+ years experience.', 'New York, NY', 90000.00, '2024-09-01', 'PHP, JavaScript, MySQL', 'close', 2),
(4, 2, 'Data Analyst', 'Analyze and interpret complex data sets.', 'Bachelor\'s degree in a related field. Software Engineer,  Developer,  Manager, Consultant Analyst', 'Los Angeles, CA', 75000.00, '2024-09-02', 'QA', 'open', 1),
(5, 1, 'Project Manager', 'Oversee project development and ensure timely delivery.', 'PMP certification preferred, 5+ years experience.', 'Chicago, IL', 40000.00, '2024-09-03', 'HTML, CSS, PHP', 'open', 5),
(6, 2, 'Graphic Designer', 'Create visual concepts for marketing campaigns.', 'Portfolio required, experience with Adobe Suite.', 'San Francisco, CA', 65000.00, '2024-09-04', 'Photoshop, Illustrator', 'open', 3),
(7, 1, 'Web Developer', 'Build and maintain websites.', 'HTML, CSS, JavaScript experience required.', 'Austin, TX', 45000.00, '2024-09-05', 'HTML, CSS, PHP', 'open', 2),
(8, 2, 'Content Writers', 'Write and ed', ' 1+ years experience.', 'Remotes', 18000.00, '2024-09-06', 'HTML, CSS, PHP', 'close', 1),
(9, 2, 'Sales Associate', 'Assist customers and drive sales in the store.', 'High school diploma required, customer service skills.', 'Miami, FL', 40000.00, '2024-09-07', 'Sales, CustomerService, Communication', 'open', 0),
(10, 3, 'Computer Teacher', 'Required a eligible teacher for C++', 'Masters', 'Ekantakuna', 30000.00, '2024-09-23', 'C++', 'open', 2),
(18, 6, 'Senior WP Developer', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'New Road', 45000.00, '2024-10-23', 'HTML, CSS, JS PHP', 'close', 4),
(20, 7, 'Wordpress Developer', 'Specialize in building websites with WordPress', 'Bachelors Degree in Computer related field\r\nMasters ', 'Baneshwor', 45000.00, '2024-10-24', 'HTML, CSS, PHP, JavaScript', 'open', 2),
(21, 3, 'Front-End Web Developer', 'Develop and implement visual elements for websites.', 'Bachelor\'s in Computer Science or related field, 2+ years experience in front-end development.', 'San Francisco, CA', 40000.00, '2024-10-25', 'HTML, CSS, PHP', 'close', 2),
(22, 3, 'Back-End Web Developer', 'Build server-side functionality and database management for websites.', 'Bachelor\'s in Computer Science, experience with server-side programming.', 'Austin, TX', 41000.00, '2024-10-25', 'HTML, CSS, PHP', 'close', 3),
(23, 3, 'Full-Stack Web Developer', 'Work on both front-end and back-end components of web applications.', '3+ years experience in full-stack development.', 'Remote', 42000.00, '2024-10-25', 'HTML, CSS, PHP', 'close', 4),
(24, 3, 'WordPress Developer', 'Specialize in building and customizing WordPress websites.', 'Experience in WordPress theme and plugin development.', 'New York, NY', 36000.00, '2024-10-25', 'HTML, CSS, PHP', 'close', 2),
(25, 3, 'UI/UX Developer', 'Design and enhance user interfaces and user experience for web applications.', 'Experience in user-centered design, 2+ years experience in UI/UX development.', 'Los Angeles, CA', 68000.00, '2024-10-25', 'HTML, CSS', 'close', 3),
(26, 3, 'PHP Developer', 'Develop and maintain web applications using PHP.', 'Bachelor\'s degree in Computer Science, 3+ years of experience in PHP development.', 'Chicago, IL', 72000.00, '2024-10-25', 'HTML, CSS', 'close', 3),
(27, 3, 'Node.js Developer', 'Build server-side logic using Node.js for scalable applications.', 'Proven experience with Node.js, familiarity with front-end technologies.', 'Austin, TX', 78000.00, '2024-10-25', 'HTML, CSS', 'close', 4),
(28, 3, 'Full-Stack PHP Developer', 'Develop both client-side and server-side web applications.', '5+ years of experience in full-stack development.', 'Remote', 85000.00, '2024-10-25', 'HTML, CSS', 'close', 5),
(29, 3, 'Back-End Developer', 'Responsible for developing the back-end architecture for web applications.', 'Experience in back-end frameworks and database management.', 'San Francisco, CA', 79000.00, '2024-10-25', 'HTML', 'close', 4),
(30, 3, 'Senior PHP Developer', 'Lead development teams and manage large-scale PHP-based projects.', '7+ years of experience in PHP, Node.js development.', 'New York, NY', 90000.00, '2024-10-25', 'HTML', 'close', 7),
(31, 4, 'PHP Developer', 'Develop and maintain PHP-based web applications.', 'Bachelor\'s degree in Computer Science or related field, 3+ years of experience.', 'Seattle, WA', 5000.00, '2024-10-25', 'PHP', 'close', 0),
(32, 4, 'Backend PHP Developer', 'Focus on backend development with PHP.', '2+ years of experience in backend development.', 'Boston, MA', 2000.00, '2024-10-25', 'PHP', 'close', 0),
(33, 4, 'Junior PHP Developer', 'Assist in developing PHP applications and supporting existing projects.', 'Bachelor\'s degree in Computer Science, 1+ years of experience.', 'Remote', 3000.00, '2024-10-25', 'PHP', 'close', 0),
(34, 4, 'Senior PHP Engineer', 'Lead projects involving PHP development and mentor junior developers.', '5+ years of PHP development experience.', 'Denver, CO', 40000.00, '2024-10-25', 'PHP', 'close', 3),
(35, 4, 'PHP Programmer', 'Work on PHP programming tasks and troubleshoot backend issues.', 'Experience in PHP programming and debugging.', 'Los Angeles, CA', 42000.00, '2024-10-25', 'PHP', 'close', 3),
(36, 7, 'Frontend Developer', 'Need experienced Frontend Developer', 'Bachelors passed ', 'Mid Baneshor', 50000.00, '2024-10-25', 'HTML, CSS, SASS, Tailwind', 'open', 1);

-- --------------------------------------------------------

--
-- Table structure for table `seekerlogin`
--

CREATE TABLE `seekerlogin` (
  `Seeker_id` int(11) NOT NULL,
  `Fullname_S` varchar(255) DEFAULT NULL,
  `Email_S` varchar(255) DEFAULT NULL,
  `Password_S` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seekerlogin`
--

INSERT INTO `seekerlogin` (`Seeker_id`, `Fullname_S`, `Email_S`, `Password_S`) VALUES
(3, 'Nhujan Maharjan', 'mnhujan@gmail.com', '$2y$10$tlGy1YnKIELU3K1moan4F.NFoROsQec2RR3sF5iYne/maCm.FRQh.'),
(4, 'Krishna Maharjan', 'krishna@gmail.com', '$2y$10$sBheKHLoXWbcrWwhsv9JAelXiB1D7IY5AZ1L9iKvZNHuFXh1QQsbm'),
(5, 'Rajesh Kawan', 'rajesh@gmail.com', '$2y$10$qUsJwnZz4zWFXUAC0nhbruvj3KtGcoLRtdvFjXQQNHxsPZjakj8w6'),
(6, 'Sumit', 'sumitbd@gmail.com', '$2y$10$6Pw1dqGE5G57yt5u5oihou2sY0BFTf50gNAIzqL3Xi2jDiPmOG.fu'),
(19, 'Pradeep', 'pradeep@gmail.com', '$2y$10$Z3A5j1F.Z.nsA8N4SaMVZOGs7TnixhCQA399dPKzAUUZ4Mnq.4dvi'),
(21, 'Ram', 'ram@gmail.com', '$2y$10$Gn1WmHD16V4Zhk6qCzMyy.aJqSvP.ylNaqvJMKEyLc7D27IkWF9XW');

-- --------------------------------------------------------

--
-- Table structure for table `seekerresume`
--

CREATE TABLE `seekerresume` (
  `sk_id` int(11) NOT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `EmailAddress` varchar(255) DEFAULT NULL,
  `Contact` bigint(20) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `Provience` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `pdffile` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `seeker_id` int(11) DEFAULT NULL,
  `Education` varchar(255) DEFAULT NULL,
  `Workexp` varchar(255) DEFAULT NULL,
  `skill` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seekerresume`
--

INSERT INTO `seekerresume` (`sk_id`, `FullName`, `EmailAddress`, `Contact`, `Country`, `Provience`, `City`, `Address`, `pdffile`, `image`, `seeker_id`, `Education`, `Workexp`, `skill`) VALUES
(2, 'Nhujan Maharjan', 'mnhujan@gmail.com', 9818574266, 'Nepal', 'Bagmati', 'Kathmandu', 'Inbhal', '../img/JohnDoe.docx', '../img/profile image.jpg', 3, 'Ashirwad College - +2 in Computer science\r\nACHS - Bachelors in Computer Application', '2', 'HTML, CSS, PHP'),
(3, 'Nhujan Maharjan', 'mnhujan@gmail.com', 9818574266, 'Nepal', 'Bagmati', 'Kathmandu', 'Inbhal', '../img/JohnDoe2.pdf', '../img/pradeep.png', 5, 'Ashirwad College - +2 in Computer science\r\nACHS - Bachelors in Computer Application', '0', 'HTML, CSS, PHP'),
(4, 'Hari', 'hari@SS.com', 9876543210, 'Nepal', 'Bagmati', 'Kathmandu', 'Inbhal', 'resume.pdf', 'Dulce Galaviz.png', 6, 'Bachleor in Computer application\r\n+2 in Computer Science', '3', 'python'),
(7, 'Pradeep Raut', 'pradeep@gmail.com', 9632587410, 'Nepal', 'Bagmati', 'Kathmandu', 'Inbhal', '../img/JohnDoe2.pdf', '../img/pradeep.png', 19, 'Bachelor In Information technology\r\nMasters in Computer Science', '3', 'HTML, CSS, JS, PHP'),
(8, 'Nhujan Maharjan', 'mnhujan@gmail.com', 9632587410, 'Nepal', 'Bagmati', 'Kathmandu', 'Inbhal', '../img/JohnDoe4.pdf', '../img/pradeep.png', 21, 'Bachleor in Cmputer application', '1', 'HTML, CSS, PHP');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`A_id`);

--
-- Indexes for table `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `seeker_id` (`Seeker_id`),
  ADD KEY `employer_id` (`employer_id`);

--
-- Indexes for table `employerlogin`
--
ALTER TABLE `employerlogin`
  ADD PRIMARY KEY (`employer_id`);

--
-- Indexes for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `employer_id` (`employer_id`);

--
-- Indexes for table `seekerlogin`
--
ALTER TABLE `seekerlogin`
  ADD PRIMARY KEY (`Seeker_id`);

--
-- Indexes for table `seekerresume`
--
ALTER TABLE `seekerresume`
  ADD PRIMARY KEY (`sk_id`),
  ADD KEY `seeker_id` (`seeker_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `A_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `application`
--
ALTER TABLE `application`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `employerlogin`
--
ALTER TABLE `employerlogin`
  MODIFY `employer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `job_postings`
--
ALTER TABLE `job_postings`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `seekerlogin`
--
ALTER TABLE `seekerlogin`
  MODIFY `Seeker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `seekerresume`
--
ALTER TABLE `seekerresume`
  MODIFY `sk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `application_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_postings` (`job_id`),
  ADD CONSTRAINT `application_ibfk_2` FOREIGN KEY (`Seeker_id`) REFERENCES `seekerlogin` (`Seeker_id`),
  ADD CONSTRAINT `application_ibfk_3` FOREIGN KEY (`employer_id`) REFERENCES `employerlogin` (`employer_id`);

--
-- Constraints for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD CONSTRAINT `job_postings_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `employerlogin` (`employer_id`);

--
-- Constraints for table `seekerresume`
--
ALTER TABLE `seekerresume`
  ADD CONSTRAINT `seekerresume_ibfk_1` FOREIGN KEY (`seeker_id`) REFERENCES `seekerlogin` (`Seeker_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
