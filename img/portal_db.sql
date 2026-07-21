-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 21, 2026 at 06:45 PM
-- Server version: 8.4.7
-- PHP Version: 8.1.33

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

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `A_id` int NOT NULL AUTO_INCREMENT,
  `A_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`A_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`A_id`, `A_name`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

DROP TABLE IF EXISTS `application`;
CREATE TABLE IF NOT EXISTS `application` (
  `application_id` int NOT NULL AUTO_INCREMENT,
  `job_id` int DEFAULT NULL,
  `Seeker_id` int DEFAULT NULL,
  `employer_id` int DEFAULT NULL,
  `date_applied` date DEFAULT NULL,
  `status` enum('applied','accepted','rejected') COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`application_id`),
  KEY `job_id` (`job_id`),
  KEY `seeker_id` (`Seeker_id`),
  KEY `employer_id` (`employer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application`
--

INSERT INTO `application` (`application_id`, `job_id`, `Seeker_id`, `employer_id`, `date_applied`, `status`) VALUES
(28, 47, 22, 8, '2026-07-12', 'applied'),
(29, 56, 22, 8, '2026-07-12', 'applied'),
(31, 56, 31, 8, '2026-07-12', 'applied'),
(32, 69, 45, 8, '2026-07-12', 'applied'),
(33, 41, 45, 8, '2026-07-12', 'applied'),
(34, 56, 45, 8, '2026-07-12', 'applied'),
(35, 46, 43, 8, '2026-07-12', 'applied'),
(36, 53, 43, 8, '2026-07-12', 'applied'),
(37, 47, 43, 8, '2026-07-12', 'applied'),
(38, 56, 43, 8, '2026-07-12', 'applied'),
(39, 66, 44, 8, '2026-07-12', 'applied'),
(40, 56, 44, 8, '2026-07-12', 'applied'),
(41, 46, 40, 8, '2026-07-12', 'applied'),
(42, 45, 40, 8, '2026-07-12', 'applied'),
(43, 56, 40, 8, '2026-07-12', 'applied'),
(44, 56, 30, 8, '2026-07-12', 'applied'),
(45, 70, 56, 8, '2026-07-13', 'applied'),
(47, 43, 55, 8, '2026-07-13', 'applied'),
(48, 42, 55, 8, '2026-07-13', 'applied'),
(49, 45, 55, 8, '2026-07-13', 'applied'),
(50, 59, 22, 8, '2026-07-18', 'applied');

-- --------------------------------------------------------

--
-- Table structure for table `employerlogin`
--

DROP TABLE IF EXISTS `employerlogin`;
CREATE TABLE IF NOT EXISTS `employerlogin` (
  `employer_id` int NOT NULL AUTO_INCREMENT,
  `Fullname_E` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email_Address_E` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Password_E` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Contact` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`employer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employerlogin`
--

INSERT INTO `employerlogin` (`employer_id`, `Fullname_E`, `Email_Address_E`, `Password_E`, `Location`, `Contact`) VALUES
(8, 'Work', 'ajitneupane33@gmail.com', '$2y$10$6Xv4LB0HnAC55LbDbbcZ4urrJQMHi/zjPANVQCEDTsD79rekDQww6', 'Kathmandu', '9840183410'),
(9, 'ROBO KNOT', 'ajitneupane44@gmail.com', '$2y$10$kPt8AIOFA3cb0zXWMyYnROv50YEVFF21Hdwa98CuScZ8n1ZEx7uEu', 'Kathmandu', '9840183410');

-- --------------------------------------------------------

--
-- Table structure for table `job_postings`
--

DROP TABLE IF EXISTS `job_postings`;
CREATE TABLE IF NOT EXISTS `job_postings` (
  `job_id` int NOT NULL AUTO_INCREMENT,
  `employer_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `requirements` text COLLATE utf8mb4_general_ci,
  `location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `date_posted` date DEFAULT NULL,
  `skills` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('open','close') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `workexperience` int NOT NULL,
  PRIMARY KEY (`job_id`),
  KEY `employer_id` (`employer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_postings`
--

INSERT INTO `job_postings` (`job_id`, `employer_id`, `title`, `description`, `requirements`, `location`, `salary`, `date_posted`, `skills`, `status`, `workexperience`) VALUES
(41, 8, 'Senior Frontend Developer', 'Design and implement user-facing features.', 'Bachelor\'s in IT, Strong communication', 'Kathmandu', 85000.00, '2026-07-12', 'React, JavaScript, Tailwind CSS', 'open', 3),
(42, 8, 'Backend Node.js Developer', 'Develop robust server-side logic and APIs.', 'Experience with microservices', 'Lalitpur', 75000.00, '2026-07-12', 'Node.js, Express, MongoDB', 'open', 2),
(43, 8, 'Full Stack Engineer', 'Handle both frontend and backend development tasks.', 'Proven track record of deploying full apps', 'Remote', 120000.00, '2026-07-12', 'React, Node.js, PostgreSQL', 'open', 4),
(44, 8, 'QA Automation Tester', 'Create and execute automated testing scripts.', 'Knowledge of CI/CD pipelines', 'Bhaktapur', 55000.00, '2026-07-12', 'Selenium, Cypress, Python', 'open', 2),
(45, 8, 'Cloud Solutions Architect', 'Design and migrate applications to the cloud.', 'AWS or Azure Certification required', 'Remote', 150000.00, '2026-07-12', 'AWS, Azure, Docker, Kubernetes', 'open', 5),
(46, 8, 'DevOps Engineer', 'Streamline deployment and infrastructure management.', 'Strong scripting skills', 'Kathmandu', 90000.00, '2026-07-12', 'Linux, Jenkins, Terraform', 'open', 3),
(47, 8, 'Data Scientist', 'Analyze large datasets to extract actionable insights.', 'Master\'s Degree in Data Science or Math', 'Lalitpur', 110000.00, '2026-07-12', 'Python, R, Machine Learning', 'open', 3),
(48, 8, 'UI/UX Designer', 'Create intuitive and engaging user interfaces.', 'Strong portfolio required', 'Kathmandu', 60000.00, '2026-07-12', 'Figma, Adobe XD, Sketch', 'open', 2),
(49, 8, 'Android Developer', 'Build and maintain advanced applications for Android.', 'Published apps on Google Play', 'Bhaktapur', 70000.00, '2026-07-12', 'Kotlin, Java, Android SDK', 'open', 2),
(50, 8, 'iOS Developer', 'Design and build applications for the iOS platform.', 'Understanding of Apple design principles', 'Remote', 80000.00, '2026-07-12', 'Swift, Objective-C, Xcode', 'open', 3),
(51, 8, 'Database Administrator', 'Maintain database performance and security.', 'Experience with database tuning', 'Kathmandu', 85000.00, '2026-07-12', 'MySQL, Oracle, SQL Server', 'open', 4),
(52, 8, 'Cybersecurity Analyst', 'Monitor networks for security breaches and investigate.', 'CEH or CompTIA Security+ preferred', 'Lalitpur', 95000.00, '2026-07-12', 'Network Security, Firewalls, Penetration Testing', 'open', 3),
(53, 8, 'System Administrator', 'Manage local IT infrastructure and servers.', 'Excellent troubleshooting skills', 'Bhaktapur', 50000.00, '2026-07-12', 'Windows Server, Linux, Active Directory', 'open', 2),
(54, 8, 'IT Project Manager', 'Lead IT projects from conception to deployment.', 'PMP Certification', 'Kathmandu', 105000.00, '2026-07-12', 'Agile, Jira, Leadership', 'open', 5),
(55, 8, 'Scrum Master', 'Facilitate daily scrums and agile planning.', 'Certified Scrum Master (CSM)', 'Remote', 90000.00, '2026-07-12', 'Agile, Kanban, Conflict Resolution', 'open', 3),
(56, 8, 'Python Django Developer', 'Develop fast and secure web applications.', 'Knowledge of RESTful APIs', 'Lalitpur', 70000.00, '2026-07-12', 'Python, Django, PostgreSQL', 'open', 2),
(57, 8, 'Java Software Engineer', 'Design scalable enterprise-level applications.', 'Experience with Spring Boot framework', 'Kathmandu', 85000.00, '2026-07-12', 'Java, Spring, Hibernate', 'open', 3),
(58, 8, 'PHP Laravel Developer', 'Build custom web portals and CMS systems.', 'Strong understanding of MVC architecture', 'Bhaktapur', 60000.00, '2026-07-12', 'PHP, Laravel, Vue.js', 'open', 2),
(59, 8, 'Machine Learning Engineer', 'Develop and train AI models.', 'Experience with TensorFlow or PyTorch', 'Remote', 130000.00, '2026-07-12', 'Python, AI, Deep Learning', 'open', 4),
(60, 8, 'Network Engineer', 'Design and implement local and wide-area networks.', 'CCNA or CCNP certification', 'Kathmandu', 75000.00, '2026-07-12', 'Cisco, Routing, Switching', 'open', 3),
(61, 8, 'Blockchain Developer', 'Develop smart contracts and decentralized apps.', 'Understanding of cryptography', 'Remote', 140000.00, '2026-07-12', 'Solidity, Ethereum, Web3.js', 'open', 2),
(62, 8, 'Game Developer', 'Create immersive 2D and 3D games.', 'Passion for gaming and optimization', 'Lalitpur', 80000.00, '2026-07-12', 'Unity, C#, Unreal Engine', 'open', 2),
(63, 8, 'IT Support Specialist', 'Provide technical assistance to end-users.', 'Good communication and patience', 'Kathmandu', 35000.00, '2026-07-12', 'Hardware Repair, Troubleshooting, Windows', 'open', 1),
(64, 8, 'Data Engineer', 'Build and optimize data pipelines.', 'Experience with big data tools', 'Remote', 115000.00, '2026-07-12', 'Hadoop, Spark, ETL', 'open', 3),
(65, 8, 'Software Architect', 'Define the architecture for software products.', 'Extensive system design experience', 'Kathmandu', 160000.00, '2026-07-12', 'System Design, Microservices, Cloud', 'open', 7),
(66, 8, 'Business Intelligence Analyst', 'Transform data into readable reports and dashboards.', 'Strong analytical skills', 'Lalitpur', 70000.00, '2026-07-12', 'Tableau, PowerBI, SQL', 'open', 2),
(67, 8, 'Site Reliability Engineer (SRE)', 'Ensure high availability of software systems.', 'Experience with incident management', 'Remote', 125000.00, '2026-07-12', 'Go, Python, Kubernetes, Monitoring', 'open', 4),
(68, 8, 'Ruby on Rails Developer', 'Maintain and develop features for web applications.', 'Experience with TDD/BDD', 'Kathmandu', 75000.00, '2026-07-12', 'Ruby, Rails, RSpec', 'open', 3),
(69, 8, 'Angular Developer', 'Create scalable single-page applications.', 'Familiarity with RxJS', 'Bhaktapur', 65000.00, '2026-07-12', 'Angular, TypeScript, HTML/CSS', 'open', 2),
(70, 8, 'Technical Writer', 'Write documentation for software products and APIs.', 'Excellent English writing skills', 'Remote', 45000.00, '2026-07-12', 'Documentation, Markdown, API Design', 'open', 1);

-- --------------------------------------------------------

--
-- Table structure for table `seekerlogin`
--

DROP TABLE IF EXISTS `seekerlogin`;
CREATE TABLE IF NOT EXISTS `seekerlogin` (
  `Seeker_id` int NOT NULL AUTO_INCREMENT,
  `Fullname_S` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email_S` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Password_S` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`Seeker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seekerlogin`
--

INSERT INTO `seekerlogin` (`Seeker_id`, `Fullname_S`, `Email_S`, `Password_S`) VALUES
(22, 'Ajit Neupane', 'ajitneupane33@gmail.com', '$2y$10$0x3RX5PtggPnnIzXopg2p.Yg5NKAfaZNWM1544OqFJ5YBzQTJgfCa'),
(26, 'Sakshyam Karki', 'sakshyam@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(27, 'Bikram Gharti', 'bikram@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(28, 'Tanish Upadhyay', 'tanish@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(29, 'Alex Shrestha', 'alex@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(30, 'Binamra Kafle', 'binamra@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(31, 'Niraj Bohora', 'niraj@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(32, 'Aayush Prasai', 'aayush@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(33, 'Prajjwal Thagunna', 'prajjwal@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(34, 'Sangam Begha', 'sangam@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(35, 'Sujan Duwal', 'sujan.d@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(36, 'Subesh Gumanju', 'subesh@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(37, 'Sabin Neupane', 'sabin@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(38, 'Prayag Raj Karki', 'prayag@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(39, 'Bibash Poudel', 'bibash@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(40, 'Abiral Chalise', 'abiral@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(41, 'Pukar Ojha', 'pukar@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(42, 'Suprim Ghimire', 'suprim@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(43, 'Dipa Lama', 'dipa@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(44, 'Aakriti Dhungel', 'aakriti@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(45, 'Prabina Kathayat', 'prabina@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(46, 'Kushal Shrestha', 'kushal@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(47, 'Sujan Timalsina', 'sujan.t@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(48, 'Bishwas Thapa Magar', 'bishwas@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(49, 'Prashis Khadka', 'prashis@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(50, 'Rawoj Karmacharya', 'rawoj@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(51, 'Ronish Thapa', 'ronish@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(52, 'Binay Rajbanshi', 'binay@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(53, 'Rochak Gurung', 'rochak@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(54, 'Buddhi Raj Bhandari', 'buddhi@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(55, 'Ajit Neupane', 'ajit@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(56, 'Abhishek Ghimire', 'abhishek@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy'),
(57, 'Rohan Timalshina', 'rohan@gmail.com', '$2y$10$8mNOnsos8qo4qHLcd32zrOg7gmyvfZ6/o9.2nsP/u6TRbrANdLREy');

-- --------------------------------------------------------

--
-- Table structure for table `seekerresume`
--

DROP TABLE IF EXISTS `seekerresume`;
CREATE TABLE IF NOT EXISTS `seekerresume` (
  `sk_id` int NOT NULL AUTO_INCREMENT,
  `FullName` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `EmailAddress` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Contact` bigint DEFAULT NULL,
  `Country` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Provience` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `City` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pdffile` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `seeker_id` int DEFAULT NULL,
  `Education` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Workexp` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `skill` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`sk_id`),
  KEY `seeker_id` (`seeker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seekerresume`
--

INSERT INTO `seekerresume` (`sk_id`, `FullName`, `EmailAddress`, `Contact`, `Country`, `Provience`, `City`, `Address`, `pdffile`, `image`, `seeker_id`, `Education`, `Workexp`, `skill`, `description`) VALUES
(10, 'Ajit Neupane', 'ajitneupane33@gmail.com', 9840183410, 'Nepal', 'bagmati', 'kathmandu', 'Test', '../img/Career_Recommendation (3) (1).pdf', '../img/ajit.jpg', 22, 'BIT', '5', ' Python\r\n Django\r\n PostgreSQL iOS\r\n Swift\r\n Objective-C', NULL),
(11, 'Sakshyam Karki', 'sakshyam@gmail.com', 9841000001, 'Nepal', 'Bagmati', 'Kathmandu', 'Baneshwor', '../img/resume.pdf', '../img/sakshyam.png', 26, 'BSc CSIT', '2', 'React, JavaScript, HTML, CSS', 'Creative Frontend Developer with 2 years of experience crafting responsive and interactive web applications. With a solid foundation in BSc CSIT, I specialize in modern web technologies including React, JavaScript, HTML, and CSS. Dedicated to writing clean, maintainable code and enhancing user experience.'),
(12, 'Bikram Gharti', 'bikram@gmail.com', 9841000002, 'Nepal', 'Bagmati', 'Lalitpur', 'Patan', '../img/resume.pdf', '../img/bikram.png', 27, 'BIT', '3', 'Node.js, Express, MongoDB', 'Backend-focused Software Engineer with 3 years of hands-on experience in building scalable RESTful APIs. Holding a BIT degree, I am highly proficient in the JavaScript ecosystem, specifically Node.js, Express, and MongoDB. Passionate about database optimization and robust server-side logic.'),
(13, 'Tanish Upadhyay', 'tanish@gmail.com', 9841000003, 'Nepal', 'Bagmati', 'Bhaktapur', 'Suryabinayak', '../img/resume.pdf', '../img/tanish.png', 28, 'BCA', '1', 'Python, Django, PostgreSQL', 'Enthusiastic Python Developer with a year of experience developing secure web applications. Armed with a BCA degree, my core stack includes Python, Django, and PostgreSQL. Eager to tackle complex backend challenges and contribute to innovative development teams.'),
(14, 'Alex Shrestha', 'alex@gmail.com', 9841000004, 'Nepal', 'Bagmati', 'Kathmandu', 'Thamel', '../img/resume.pdf', '../img/alex.png', 29, 'BFA', '2', 'Figma, Adobe XD, UI/UX', 'Detail-oriented UI/UX Designer with 2 years of professional experience in digital product design. Leveraging a BFA background, I transform complex requirements into intuitive, user-centric interfaces using Figma and Adobe XD. Strongly focused on accessibility and modern design trends.'),
(15, 'Binamra Kafle', 'binamra@gmail.com', 9841000005, 'Nepal', 'Bagmati', 'Kathmandu', 'Koteshwor', '../img/resume.pdf', '../img/binamra.png', 30, 'BSc CSIT', '4', 'Java, Spring Boot, MySQL', 'Seasoned Java Developer bringing 4 years of experience in enterprise software development. With a BSc CSIT, my expertise lies in building robust backends using Java, Spring Boot, and MySQL. Proven track record of delivering high-performance, secure applications.'),
(16, 'Niraj Bohora', 'niraj@gmail.com', 9841000006, 'Nepal', 'Bagmati', 'Lalitpur', 'Kupondole', '../img/resume.pdf', '../img/niraj.png', 31, 'BIT', '2', 'QA, Selenium, Cypress, Manual Testing', 'Quality Assurance Automation Engineer with 2 years of experience ensuring software reliability. Holding a BIT degree, I am skilled in both manual and automated testing utilizing Selenium and Cypress. Committed to identifying bugs early and streamlining the CI/CD pipeline.'),
(17, 'Aayush Prasai', 'aayush@gmail.com', 9841000007, 'Nepal', 'Bagmati', 'Kathmandu', 'Chabahil', '../img/resume.pdf', '../img/aayush.png', 32, 'BE Computer', '5', 'AWS, Docker, Kubernetes, Terraform', 'Experienced Cloud & DevOps Engineer with 5 years of industry expertise in infrastructure as code. Graduated with a BE in Computer Engineering, specializing in AWS, Docker, Kubernetes, and Terraform. Highly capable of automating deployments and scaling cloud architectures.'),
(18, 'Prajjwal Thagunna', 'prajjwal@gmail.com', 9841000008, 'Nepal', 'Bagmati', 'Kathmandu', 'Putalisadak', '../img/resume.pdf', '../img/prajjwal.png', 33, 'BCA', '3', 'PHP, Laravel, Vue.js, MySQL', 'Full Stack PHP Developer with 3 years of experience building dynamic web applications. Combining a BCA background with practical expertise in Laravel, Vue.js, and MySQL, I seamlessly bridge the gap between robust backend logic and interactive frontend interfaces.'),
(19, 'Sangam Begha', 'sangam@gmail.com', 9841000009, 'Nepal', 'Bagmati', 'Bhaktapur', 'Kamalbinayak', '../img/resume.pdf', '../img/sangam.png', 34, 'MSc Data Science', '2', 'Python, TensorFlow, Machine Learning', 'Data Scientist with an MSc in Data Science and 2 years of applied experience in predictive modeling. Proficient in Python, TensorFlow, and advanced Machine Learning algorithms. Passionate about extracting actionable insights from complex datasets to drive business value.'),
(20, 'Sujan Duwal', 'sujan.d@gmail.com', 9841000010, 'Nepal', 'Bagmati', 'Kathmandu', 'Gongabu', '../img/resume.pdf', '../img/sujan.png', 35, 'BIM', '4', 'C#, .NET Core, SQL Server', 'Dedicated .NET Developer with 4 years of professional experience engineering scalable software solutions. Equipped with a BIM degree, my primary technical stack includes C#, .NET Core, and SQL Server. Strong background in enterprise application architecture.'),
(21, 'Subesh Gumanju', 'subesh@gmail.com', 9841000011, 'Nepal', 'Bagmati', 'Lalitpur', 'Imadol', '../img/resume.pdf', '../img/subesh.png', 36, 'BIT', '1', 'React Native, JavaScript, Mobile Dev', 'Up-and-coming Mobile Application Developer with a year of specialized experience in cross-platform development. Holding a BIT degree, I build fast and responsive mobile apps using React Native and JavaScript. Continuously exploring the latest trends in mobile UI/UX.'),
(22, 'Sabin Neupane', 'sabin@gmail.com', 9841000012, 'Nepal', 'Bagmati', 'Kathmandu', 'Kalanki', '../img/resume.pdf', '../img/sabin.png', 37, 'BE IT', '3', 'Cybersecurity, Penetration Testing, Linux', 'Proactive Cybersecurity Analyst with 3 years of experience safeguarding digital assets. With a BE in IT, I specialize in vulnerability assessments, penetration testing, and Linux system administration. Dedicated to mitigating risks and ensuring robust network security.'),
(23, 'Prayag Raj Karki', 'prayag@gmail.com', 9841000013, 'Nepal', 'Bagmati', 'Kathmandu', 'Baluwatar', '../img/resume.pdf', '../img/prayag.png', 38, 'BSc CSIT', '2', 'Android SDK, Kotlin, Java', 'Passionate Android Developer with 2 years of experience creating feature-rich mobile applications. Leveraging a BSc CSIT, I am highly proficient in Kotlin, Java, and the Android SDK. Focused on writing efficient code to deliver optimal mobile experiences.'),
(24, 'Bibash Poudel', 'bibash@gmail.com', 9841000014, 'Nepal', 'Bagmati', 'Kathmandu', 'Maharajgunj', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Bibash+Poudel&background=random&color=fff', 39, 'BIT', '3', 'iOS, Swift, Objective-C', 'Innovative iOS Developer with 3 years of experience in the Apple ecosystem. Armed with a BIT degree, I specialize in building sleek, high-performing applications using Swift and Objective-C. Deep understanding of mobile architecture and App Store deployment.'),
(25, 'Abiral Chalise', 'abiral@gmail.com', 9841000015, 'Nepal', 'Bagmati', 'Lalitpur', 'Jawalakhel', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Abiral+Chalise&background=random&color=fff', 40, 'BE Computer', '4', 'DevOps, Jenkins, CI/CD, AWS', 'Results-oriented DevOps Engineer with 4 years of experience optimizing software delivery pipelines. Holding a BE in Computer Engineering, my core competencies include Jenkins, CI/CD automation, and AWS cloud management. Driven by efficiency and system reliability.'),
(26, 'Pukar Ojha', 'pukar@gmail.com', 9841000016, 'Nepal', 'Bagmati', 'Kathmandu', 'Sinamangal', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Pukar+Ojha&background=random&color=fff', 41, 'MSc CSIT', '3', 'Data Science, R, Python, SQL', 'Analytical Data Scientist with an MSc CSIT and 3 years of experience translating raw data into strategic insights. Highly skilled in statistical analysis using R, Python, and SQL. Adept at building data pipelines and communicating findings to stakeholders.'),
(27, 'Suprim Ghimire', 'suprim@gmail.com', 9841000017, 'Nepal', 'Bagmati', 'Bhaktapur', 'Thimi', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Suprim+Ghimire&background=random&color=fff', 42, 'BCA', '2', 'Ruby on Rails, HTML, CSS', 'Agile Web Developer with 2 years of experience in rapid application development. Holding a BCA degree, my primary expertise lies in the Ruby on Rails framework, complemented by strong HTML and CSS skills. Passionate about writing clean, DRY code.'),
(28, 'Dipa Lama', 'dipa@gmail.com', 9841000018, 'Nepal', 'Bagmati', 'Kathmandu', 'Boudha', '../img/resume.pdf', '../img/dipa.png', 43, 'BSc CSIT', '4', 'System Administrator, Linux, Windows Server', 'Reliable System Administrator with 4 years of experience managing complex IT infrastructures. With a BSc CSIT foundation, I excel in Linux and Windows Server administration, network configuration, and system troubleshooting to ensure maximum uptime.'),
(29, 'Aakriti Dhungel', 'aakriti@gmail.com', 9841000019, 'Nepal', 'Bagmati', 'Lalitpur', 'Sanepa', '../img/resume.pdf', '../img/aakriti.png', 44, 'BIM', '2', 'Tableau, PowerBI, Business Intelligence', 'Detail-oriented Business Intelligence Analyst with 2 years of experience in data visualization. Equipped with a BIM degree, I leverage Tableau and PowerBI to design interactive dashboards that empower data-driven decision-making across organizations.'),
(30, 'Prabina Kathayat', 'prabina@gmail.com', 9841000020, 'Nepal', 'Bagmati', 'Kathmandu', 'Kapan', '../img/resume.pdf', '../img/prabina.png', 45, 'BIT', '1', 'Angular, TypeScript, Tailwind CSS', 'Motivated Frontend Developer with a year of hands-on experience building dynamic web interfaces. Holding a BIT degree, my core stack focuses on Angular, TypeScript, and Tailwind CSS. Eager to contribute to modern, component-driven web architectures.'),
(31, 'Kushal Shrestha', 'kushal@gmail.com', 9841000021, 'Nepal', 'Bagmati', 'Bhaktapur', 'Suryabinayak', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Kushal+Shrestha&background=random&color=fff', 46, 'BSc CSIT', '3', 'Unity, C#, Game Development', 'Creative Game Developer with 3 years of experience bringing digital worlds to life. Leveraging a BSc CSIT, I specialize in Unity and C# programming. Passionate about game mechanics, performance optimization, and delivering engaging player experiences.'),
(32, 'Sujan Timalsina', 'sujan.t@gmail.com', 9841000022, 'Nepal', 'Bagmati', 'Kathmandu', 'Jorpati', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Sujan+Timalsina&background=random&color=fff', 47, 'BE IT', '2', 'Solidity, Ethereum, Blockchain, Web3.js', 'Forward-thinking Blockchain Developer with 2 years of experience in decentralized applications. Armed with a BE in IT, I build secure smart contracts using Solidity, Ethereum, and Web3.js. Passionate about the future of Web3 and cryptographic technologies.'),
(33, 'Bishwas Thapa Magar', 'bishwas@gmail.com', 9841000023, 'Nepal', 'Bagmati', 'Lalitpur', 'Patan', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Bishwas+Thapa+Magar&background=random&color=fff', 48, 'BCA', '4', 'Network Engineer, CCNA, Routing', 'Certified Network Engineer with 4 years of hands-on experience designing and maintaining secure corporate networks. Holding a BCA degree and deep expertise in routing, switching, and CCNA protocols. Committed to ensuring seamless connectivity and network security.'),
(34, 'Prashis Khadka', 'prashis@gmail.com', 9841000024, 'Nepal', 'Bagmati', 'Kathmandu', 'Baneshwor', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Prashis+Khadka&background=random&color=fff', 49, 'BIM', '1', 'IT Support, Hardware Repair, Windows', 'Customer-focused IT Support Specialist with a year of practical experience in troubleshooting hardware and software issues. Holding a BIM degree, I excel in Windows environments and end-user support, dedicated to resolving technical problems swiftly.'),
(35, 'Rawoj Karmacharya', 'rawoj@gmail.com', 9841000025, 'Nepal', 'Bagmati', 'Bhaktapur', 'Dattatreya', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Rawoj+Karmacharya&background=random&color=fff', 50, 'BE Computer', '3', 'Data Engineer, Hadoop, Spark, ETL', 'Data Engineer with 3 years of experience designing scalable data architectures. With a BE in Computer Engineering, I specialize in Big Data technologies including Hadoop, Spark, and advanced ETL processes. Passionate about building resilient data pipelines.'),
(36, 'Ronish Thapa', 'ronish@gmail.com', 9841000026, 'Nepal', 'Bagmati', 'Kathmandu', 'Samakhusi', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Ronish+Thapa&background=random&color=fff', 51, 'MBA', '5', 'Scrum Master, Agile, Jira, Leadership', 'Strategic Scrum Master and Agile Coach with 5 years of leadership experience driving project delivery. Holding an MBA, I excel in facilitating Agile ceremonies, managing Jira workflows, and fostering cross-functional team collaboration to maximize productivity.'),
(37, 'Binay Rajbanshi', 'binay@gmail.com', 9841000027, 'Nepal', 'Bagmati', 'Lalitpur', 'Balkumari', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Binay+Rajbanshi&background=random&color=fff', 52, 'BSc CSIT', '4', 'Go, Python, SRE, Kubernetes', 'Proactive Site Reliability Engineer (SRE) with 4 years of experience maintaining highly available systems. Armed with a BSc CSIT, I leverage Go, Python, and Kubernetes to build scalable, fault-tolerant infrastructures. Passionate about automation and monitoring.'),
(38, 'Rochak Gurung', 'rochak@gmail.com', 9841000028, 'Nepal', 'Bagmati', 'Kathmandu', 'Naxal', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Rochak+Gurung&background=random&color=fff', 53, 'MSc IT', '7', 'Software Architect, Microservices, System Design', 'Visionary Software Architect with 7 years of deep industry experience in designing enterprise-scale applications. Holding an MSc in IT, I specialize in microservices architecture, complex system design, and leading engineering teams to technical excellence.'),
(39, 'Buddhi Raj Bhandari', 'buddhi@gmail.com', 9841000029, 'Nepal', 'Bagmati', 'Kathmandu', 'Lazimpat', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=BuddhiRaj+Bhandari&background=random&color=fff', 54, 'BIM', '6', 'Project Management, PMP, Confluence', 'Accomplished IT Project Manager with 6 years of experience overseeing the full software development lifecycle. With a BIM background and strong PMP methodologies, I utilize tools like Confluence to ensure projects are delivered on time, within scope, and under budget.'),
(40, 'Ajit Neupane', 'ajit@gmail.com', 9841000030, 'Nepal', 'Bagmati', 'Kathmandu', 'Mid Baneshwor', '../img/resume.pdf', '../img/ajit.png', 55, 'BIT', '4', 'React \r\nNode.js\r\nExpress\r\nMongoDB\r\nFull Stack', 'Results-driven Full Stack Developer with a BIT degree and 4 years of experience specializing in the MERN stack. Expert in building scalable web applications using React, Node.js, Express, and MongoDB. Proven ability to optimize application performance and deliver clean, efficient code.'),
(41, 'Abhishek Ghimire', 'abhishek@gmail.com', 9841000031, 'Nepal', 'Bagmati', 'Lalitpur', 'Ekantakuna', '../img/resume.pdf', '../img/unnamed.jpg', 56, 'BA English', '2', 'Technical Writing, Documentation, Markdown', 'Clear and concise Technical Writer with 2 years of experience translating complex technical concepts into accessible documentation. Leveraging a BA in English, I excel in creating developer guides, API documentation, and Markdown files to enhance product usability.'),
(42, 'Rohan Timalshina', 'rohan@gmail.com', 9841000032, 'Nepal', 'Bagmati', 'Kathmandu', 'Maitidevi', '../img/resume.pdf', 'https://ui-avatars.com/api/?name=Rohan+Timalshina&background=random&color=fff', 57, 'BSc CSIT', '5', 'Database Administrator, MySQL, Oracle, Tuning', 'Expert Database Administrator with 5 years of experience managing mission-critical data systems. Holding a BSc CSIT, I specialize in MySQL and Oracle environments, focusing on database tuning, disaster recovery, and ensuring maximum data integrity and performance.');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `application_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_postings` (`job_id`),
  ADD CONSTRAINT `application_ibfk_2` FOREIGN KEY (`Seeker_id`) REFERENCES `seekerlogin` (`Seeker_id`),
  ADD CONSTRAINT `application_ibfk_3` FOREIGN KEY (`employer_id`) REFERENCES `employerlogin` (`Employer_id`);

--
-- Constraints for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD CONSTRAINT `job_postings_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `employerlogin` (`Employer_id`);

--
-- Constraints for table `seekerresume`
--
ALTER TABLE `seekerresume`
  ADD CONSTRAINT `seekerresume_ibfk_1` FOREIGN KEY (`seeker_id`) REFERENCES `seekerlogin` (`Seeker_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
