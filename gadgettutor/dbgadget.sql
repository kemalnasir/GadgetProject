-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2024 at 10:36 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbgadget`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `C_ID` int(11) NOT NULL,
  `C_Name` varchar(50) NOT NULL,
  `C_RegDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`C_ID`, `C_Name`, `C_RegDate`) VALUES
(17, 'Smartphones and Accessories', '2024-06-26 09:17:12'),
(18, 'Smart Home Devices', '2024-06-26 09:19:32'),
(19, 'Computers and Tablets', '2024-06-26 09:19:45'),
(20, 'Gaming Devices', '2024-06-26 09:20:00'),
(21, 'Cameras and Photography', '2024-06-26 09:20:12');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `F_ID` int(11) NOT NULL,
  `F_Desc` varchar(255) NOT NULL,
  `F_Date` timestamp NOT NULL DEFAULT current_timestamp(),
  `U_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`F_ID`, `F_Desc`, `F_Date`, `U_ID`) VALUES
(1, 'Good information :)', '2024-06-26 14:19:52', 14),
(2, 'Helpful details provided.', '2024-06-26 15:37:29', 15),
(3, 'The website itself is great and helpful, offering a wide range of knowledge on gadget', '2024-06-26 15:43:03', 16),
(4, 'The website has potential, but there are a few areas that need improvement', '2024-06-26 15:44:58', 17);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `P_ID` int(11) NOT NULL,
  `P_Name` varchar(50) NOT NULL,
  `P_Desc` varchar(255) NOT NULL,
  `P_Image` blob NOT NULL,
  `P_Video` varchar(255) NOT NULL,
  `P_Audio` blob NOT NULL,
  `P_RegDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `C_ID` int(11) NOT NULL,
  `U_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`P_ID`, `P_Name`, `P_Desc`, `P_Image`, `P_Video`, `P_Audio`, `P_RegDate`, `C_ID`, `U_ID`) VALUES
(2, 'PlayStation 5 Digital Edition', 'Play Has No Limits. Experience lightning-fast loading with an ultra-high-speed SSD, delivering immersive gameplay and seamless performance for the ultimate gaming adventure.', 0x696d6167652f696d6167655f313731393339353439312e6a7067, 'video/video_1719561527.mp4', 0x617564696f2f617564696f5f313731393438353633332e776176, '2024-06-28 08:27:11', 20, NULL),
(4, 'Laptop ASUS ROG Strix G16 (2024) ', 'Power on, customize settings with ROG software, and play games with high performance. Experience seamless gaming with optimized settings and enhanced capabilities for the ultimate gaming.', 0x696d6167652f696d6167655f313731393339353735312e6a7067, 'video/video_1719561202.mp4', 0x617564696f2f617564696f5f313731393438353736322e776176, '2024-06-28 08:31:50', 19, NULL),
(5, 'Xiomi Smart Camera C200', 'Monitor remotely, communicate via two-way audio, and ensure home security with ease. Experience peace of mind with seamless connectivity and advanced features for a secure home.', 0x696d6167652f696d6167655f313731393339353832372e6a7067, 'video/video_1719561736.mp4', 0x617564696f2f617564696f5f313731393438353837312e776176, '2024-06-28 08:31:20', 18, NULL),
(8, 'Alienware Wireless Gaming Headset', 'Power on, adjust settings via software, and enjoy immersive audio and communication features. Experience seamless integration and high-quality sound for an exceptional auditory experience.', 0x696d6167652f696d6167655f313731393339383530302e706e67, 'video/video_1719562124.mp4', 0x617564696f2f617564696f5f313731393438363333372e776176, '2024-06-28 08:22:40', 17, NULL),
(9, 'Canon PowerShot V10 (Black)', 'Charge via USB-C, insert memory card, adjust settings, record videos, and transfer files wirelessly. Enjoy seamless functionality and convenience with advanced features.', 0x696d6167652f696d6167655f313731393431333237302e706e67, 'video/video_1719561448.mp4', 0x617564696f2f617564696f5f313731393438353335352e776176, '2024-06-28 08:35:23', 21, NULL),
(11, 'Galaxy Tab S9', 'Enjoy clear, vivid visuals and smooth touch interactions on the Galaxy Tab S9\'s screen, enhancing your experience with stunning display quality and responsive performance.', 0x696d6167652f696d6167655f313731393431343036352e706e67, 'video/video_1719560745.mp4', 0x617564696f2f617564696f5f313731393438353037322e776176, '2024-06-28 08:32:30', 19, NULL),
(12, 'Apple Watch Series 9 Sport Band', 'Apple Watch Series 9 keeps you connected, active, healthy, and safe. Features include double tap, a magical interaction method, enhancing user experience and functionality effortlessly.', 0x696d6167652f696d6167655f313731393431343737302e706e67, 'video/video_1719561848.mp4', 0x617564696f2f617564696f5f313731393438343934312e776176, '2024-06-28 08:25:53', 17, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `U_ID` int(11) NOT NULL,
  `U_Name` varchar(50) NOT NULL,
  `U_Email` varchar(50) NOT NULL,
  `U_PhoneNo` varchar(12) NOT NULL,
  `U_Password` varchar(50) NOT NULL,
  `U_RegDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `U_Roles` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`U_ID`, `U_Name`, `U_Email`, `U_PhoneNo`, `U_Password`, `U_RegDate`, `U_Roles`) VALUES
(13, 'akmal', 'akmal@gmail.com', '0123456789', '1752efe3d40c9d492d567f7954a31ad5', '2024-06-26 04:28:41', 'admin'),
(14, 'adam', 'adam@gmail.com', '0178763542', 'b311a5e58735a9a23ba6064f2d1c0f55', '2024-06-26 04:28:41', 'user'),
(15, 'Tasya', 'tasya01@gmail.com', '0132549998', '97343b03329acf305e7fae4c20b84671', '2024-06-26 15:36:56', 'user'),
(16, 'Sandra', 'SandraMo@gmail.com', '0133793300', '6fb7bf91e81ee66d0b64eac4abbf03a7', '2024-06-26 15:39:19', 'user'),
(17, 'Faiz Daniel', 'FaizDan@gmail.com', '0176899200', 'fe7a3c531e70908a9b79edd4f4febb08', '2024-06-26 15:43:58', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`C_ID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`F_ID`),
  ADD KEY `P_ID` (`U_ID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`P_ID`),
  ADD UNIQUE KEY `U_ID` (`U_ID`),
  ADD KEY `fk_category` (`C_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`U_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `C_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `F_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `P_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `U_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `user` (`U_ID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`C_ID`) REFERENCES `category` (`C_ID`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`U_ID`) REFERENCES `user` (`U_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
