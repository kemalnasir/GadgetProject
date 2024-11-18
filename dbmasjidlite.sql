-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2024 at 03:50 PM
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
-- Database: `dbmasjidlite`
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
(36, 'Sosial dan Kebajikan.', '2024-06-19 15:23:29'),
(39, 'Pendidikan Kanak-Kanak.', '2024-06-19 17:15:44'),
(40, 'Aktiviti Kebudayaan dan Sosial.', '2024-06-13 17:13:37'),
(42, 'Kepimpinan dan Pembangunan Diri.', '2024-06-19 15:22:28'),
(43, 'Aktiviti Remaja dan Belia.', '2024-06-19 15:26:30'),
(45, 'Pendidikan dan Pembelajaran.', '2024-06-19 15:22:15'),
(46, 'Ibadah dan Kerohanian.', '2024-06-19 17:14:40'),
(47, 'Kesihatan dan Kesejahteraan.', '2024-06-19 17:16:10'),
(48, 'Wanita dan Keluarga.', '2024-06-19 17:16:47'),
(49, 'sukan dan rekreasi', '2024-06-20 01:55:31'),
(50, 'Clothing', '2024-06-22 05:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

CREATE TABLE `donation` (
  `D_ID` int(11) NOT NULL,
  `D_Amount` varchar(6) NOT NULL,
  `D_Description` varchar(100) NOT NULL,
  `D_Date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `D_MethodPay` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `donation`
--

INSERT INTO `donation` (`D_ID`, `D_Amount`, `D_Description`, `D_Date`, `D_MethodPay`) VALUES
(44, '20', 'hamba allah', '2024-06-19 17:57:58', 'E-wallet/Bank QR'),
(45, '44', 'untuk  kemajuan program', '2024-06-19 19:59:40', 'E-wallet/Bank QR'),
(46, '10', '-', '2024-06-19 20:04:33', 'Tunai'),
(47, '12', '-', '2024-06-19 20:06:59', 'Pembayaran Online'),
(48, '50', '-', '2024-06-19 20:09:44', 'Tunai'),
(49, '10', 'hamba allah', '2024-06-19 20:11:06', 'Pembayaran Online'),
(50, '10', '-', '2024-06-19 20:13:09', 'E-wallet/Bank QR'),
(51, '20', '-', '2024-06-19 20:16:52', 'Tunai'),
(52, '32', 'hamba allah', '2024-06-19 20:19:01', 'E-wallet/Bank QR'),
(53, '50', 'untuk  kemajuan program', '2024-06-19 20:19:59', 'Pembayaran Online'),
(54, '12', '-', '2024-06-19 20:20:43', 'E-wallet/Bank QR'),
(55, '25', '-', '2024-06-20 01:03:07', 'Tunai'),
(56, '56', 'hamba allah', '2024-06-20 01:03:37', 'E-wallet/Bank QR'),
(57, '56', 'hamba allah', '2024-06-20 02:13:31', 'Pembayaran Online'),
(58, '20', 'untuk  kemajuan program', '2024-06-22 07:40:24', 'E-wallet/Bank QR'),
(59, '20', 'untuk  kemajuan program', '2024-06-22 07:41:18', 'Pembayaran Online');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `FB_ID` int(11) NOT NULL,
  `FB_Comment` varchar(100) NOT NULL,
  `FB_DateTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `UP_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`FB_ID`, `FB_Comment`, `FB_DateTime`, `UP_ID`) VALUES
(20, 'memberikan kefahaman mengikuti program ini.', '2024-06-19 17:58:30', 379),
(21, 'memberikan ruang untuk belajar khusus pengurusan jenazah', '2024-06-19 20:00:27', 378),
(22, 'program yang sangat bagus', '2024-06-19 20:05:12', 370),
(23, 'program yang manyalurkan pendidikan tentang ilmu kematian.', '2024-06-19 20:07:26', 376),
(24, 'program yang sangat baik.', '2024-06-19 20:09:30', 372),
(25, 'program yang berkesan untuk rohani diri', '2024-06-19 20:11:28', 377),
(26, 'sangat berkesan pada diri', '2024-06-19 20:13:31', 373),
(27, 'sangat menyeronokkan', '2024-06-19 20:17:08', 390),
(28, 'best', '2024-06-20 02:14:29', 394),
(30, 'program yang memberikan manfaat kepada masyrakat.', '2024-06-22 07:45:54', 385);

-- --------------------------------------------------------

--
-- Table structure for table `prayertime`
--

CREATE TABLE `prayertime` (
  `PT_ID` int(11) NOT NULL,
  `PT_Name` varchar(50) NOT NULL,
  `PT_Time` time NOT NULL,
  `U_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prayertime`
--

INSERT INTO `prayertime` (`PT_ID`, `PT_Name`, `PT_Time`, `U_ID`) VALUES
(7, 'Subuh', '05:51:00', NULL),
(8, 'Syuruk', '07:05:00', NULL),
(14, 'Dhuha', '07:30:00', NULL),
(15, 'Zuhur', '13:15:00', NULL),
(16, 'Asar', '16:41:00', NULL),
(17, 'Maghrib', '19:22:00', NULL),
(18, 'Isyak', '20:37:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `P_ID` int(11) NOT NULL,
  `P_Name` varchar(50) NOT NULL,
  `P_Description` varchar(100) NOT NULL,
  `P_Image` blob NOT NULL,
  `P_File` varchar(255) NOT NULL,
  `P_DateTime` datetime NOT NULL,
  `P_Capacity` int(10) NOT NULL,
  `P_Location` varchar(100) NOT NULL,
  `P_Person` varchar(100) NOT NULL,
  `P_Cert` varchar(255) NOT NULL,
  `P_Status` varchar(50) NOT NULL,
  `P_ApprovedDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `P_Comment` varchar(100) NOT NULL,
  `P_Remark` varchar(100) NOT NULL,
  `P_RegDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `P_CommentUpdate` varchar(255) NOT NULL,
  `C_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`P_ID`, `P_Name`, `P_Description`, `P_Image`, `P_File`, `P_DateTime`, `P_Capacity`, `P_Location`, `P_Person`, `P_Cert`, `P_Status`, `P_ApprovedDate`, `P_Comment`, `P_Remark`, `P_RegDate`, `P_CommentUpdate`, `C_ID`) VALUES
(201, 'Program Pengurusan Jenazah', 'Program tentang perkara-perkara berkaitan pengurusan jenazah.', 0x696d6167652f313731383831323138352e6a7067, 'file/1718812185.pdf', '2024-06-19 08:00:00', 0, 'Masjid Sayidina Abu Bakar, UTeM', 'Encik Alif Awang', 'cert/1718812185.pdf', 'Diluluskan', '2024-06-16 17:55:22', 'program amat disokong', 'Published', '2024-06-19 19:42:29', '', 39),
(202, 'Program Semarak Siswa Madani 2.0', 'Program yang melibatkan pelajar dan juga masyarakat setempat.', 0x696d6167652f313731383831333035302e6a706567, 'file/1718812679.pdf', '2024-06-25 10:30:00', 27, 'Dewan Muafakat Durian Tunggal, Melaka', 'Encik Zarif dan Puan Atikah', 'cert/1718812679.pdf', 'Ditolak', '2024-06-22 08:21:04', '', 'Published', '2024-06-22 08:21:04', '', 36),
(204, 'Program Kursus Haji Untuk Tahun 2025', 'Melibatkan bakal jemaah haji pada tahun 2025', 0x696d6167652f313731383831343837342e6a7067, 'file/1718814874.pdf', '2024-07-20 08:30:00', 25, 'Masjid Sayidina Abu Bakar, UTeM', 'Encik Abu Bakar', 'cert/1718814874.pdf', 'Diluluskan', '2024-06-19 16:41:16', 'program amat disokong', 'Published', '2024-06-19 16:41:16', '', 39),
(205, 'Program Mengaji 10 minit', 'Memupuk pembacaan  Al-Quran selama 10 minit ', 0x696d6167652f313731383831353732302e6a7067, 'file/1718815720.pdf', '2024-08-28 00:46:00', 1000, 'Sekitar Masjid Utem', 'Encik Nabil Mahir', '', 'Diluluskan', '2024-06-19 16:49:37', 'diluluskan', 'Published', '2024-06-19 16:49:37', '', 39),
(206, 'Program Korban 2024', 'pelaksanaan korban melibatkan Ngo negeri Melaka', 0x696d6167652f313731383831363332332e6a7067, 'file/1718816323.pdf', '2024-06-20 10:00:00', 192, 'Perkarangan masjid utem', 'Tuan Hj Mohd Taufiq', 'cert/1718816323.pdf', 'Diluluskan', '2024-06-19 20:16:06', 'program disokong', 'Published', '2024-06-19 20:16:06', '', 36),
(207, 'Program Kempen Derma Darah', 'menjalankan program derma darah di UTeM', 0x696d6167652f313731383831373635322e6a7067, 'file/1718817652.pdf', '2024-07-05 10:30:00', 100, 'Perkarangan masjid utem', 'Encik Muhammad Razzi', '', 'Diluluskan', '2024-06-19 17:21:56', 'disokong', 'Published', '2024-06-19 17:21:56', '', 47),
(208, 'Program Wanita Hari Ini', 'membincangkan isu berkaitan wanita', 0x696d6167652f313731383834313935372e6a7067, 'file/1718841957.pdf', '2024-07-31 10:00:00', 100, 'Masjid Sayidina Abu Bakar, UTeM', 'Ustazah Syarifah', 'cert/1718841957.pdf', 'Diluluskan', '2024-08-06 15:48:04', '', 'Not Published', '2024-08-06 15:48:04', '', 48),
(209, 'Program Larian Amal Palestin', 'melibatkan pelajar  dan staff untuk kehadiran semua', 0x696d6167652f313731383834383831302e6a7067, 'file/1718848810.pdf', '2024-06-25 09:58:00', 99, 'Perkarangan masjid utem', 'Ustazah Syarifah', 'cert/1718848810.pdf', 'Diluluskan', '2024-08-10 05:45:47', 'program diluluskan', 'Published', '2024-08-10 05:45:47', '', 49);

--
-- Triggers `program`
--
DELIMITER $$
CREATE TRIGGER `send_approved_program_email` AFTER INSERT ON `program` FOR EACH ROW BEGIN
                     IF NEW.P_Status = 'Diluluskan' THEN
                         CALL sendNotificationEmail('syakkemam@gmail.com', NEW.P_Name, NEW.P_ApprovedDate);
                     END IF;
                 END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_notification_count` AFTER INSERT ON `program` FOR EACH ROW BEGIN
                     IF NEW.P_Status = 'approved' THEN
                         UPDATE notification_count SET count = count + 1;
                     END IF;
                 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `surah`
--

CREATE TABLE `surah` (
  `S_ID` int(11) NOT NULL,
  `S_Name` varchar(50) NOT NULL,
  `S_Verse` int(11) NOT NULL,
  `S_File` varchar(255) NOT NULL,
  `S_Audio` blob NOT NULL,
  `S_RegDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `U_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `surah`
--

INSERT INTO `surah` (`S_ID`, `S_Name`, `S_Verse`, `S_File`, `S_Audio`, `S_RegDate`, `U_ID`) VALUES
(1, 'Al - Fatihah', 7, 'file/1718412488.pdf', 0x617564696f2f313731383431323438382e6d7033, '2024-06-15 00:48:07', NULL),
(3, 'Ad-Dhuha', 11, 'file/1718414305.pdf', 0x617564696f2f313731383431343330352e6d7033, '2024-06-15 01:18:25', NULL),
(5, 'Al-Inshirah', 8, 'file/1718414725.pdf', 0x617564696f2f313731383431343732352e6d7033, '2024-06-15 01:25:25', NULL),
(7, 'At-Tin', 8, 'file/1718549152.pdf', 0x617564696f2f313731383534393135322e6d7033, '2024-06-16 14:45:51', NULL),
(8, 'Al-Alaq', 19, 'file/1718549359.pdf', 0x617564696f2f313731383534393335392e6d7033, '2024-06-16 14:49:18', NULL),
(9, 'Al-Qadr', 5, 'file/1718549495.pdf', 0x617564696f2f313731383534393439352e6d7033, '2024-06-16 14:51:35', NULL),
(11, 'Al-Baiyinah', 8, 'file/1718808566.pdf', 0x617564696f2f313731383830383536362e6d7033, '2024-06-19 14:49:26', NULL),
(12, 'Az-Zalzalah', 8, 'file/1718808720.pdf', 0x617564696f2f313731383830383732302e6d7033, '2024-06-19 14:51:59', NULL),
(13, 'Al-Adiyat', 11, 'file/1718808812.pdf', 0x617564696f2f313731383830383831322e6d7033, '2024-06-19 14:53:31', NULL),
(14, 'Al-Qariah', 11, 'file/1718808869.pdf', 0x617564696f2f313731383830383836392e6d7033, '2024-06-19 14:54:29', NULL),
(15, 'At-Takathur', 8, 'file/1718808989.pdf', 0x617564696f2f313731383830383938392e6d7033, '2024-06-19 14:56:28', NULL),
(16, 'Al-Asr', 3, 'file/1718809063.pdf', 0x617564696f2f313731383830393036332e6d7033, '2024-06-19 14:57:42', NULL),
(17, 'Al-Humazah', 9, 'file/1718809182.pdf', 0x617564696f2f313731383830393138322e6d7033, '2024-06-19 14:59:41', NULL),
(18, 'Al-Fil', 5, 'file/1718809266.pdf', 0x617564696f2f313731383830393236362e6d7033, '2024-06-19 15:01:05', NULL),
(19, 'Quraysh', 4, 'file/1718809346.pdf', 0x617564696f2f313731383830393334362e6d7033, '2024-06-19 15:02:25', NULL),
(20, 'Al-Maun', 7, 'file/1718809383.pdf', 0x617564696f2f313731383830393338332e6d7033, '2024-06-19 15:03:02', NULL),
(21, 'Al-Kauthar', 3, 'file/1718809427.pdf', 0x617564696f2f313731383830393432372e6d7033, '2024-06-19 15:03:47', NULL),
(22, 'Al-Kafirun', 6, 'file/1718809467.pdf', 0x617564696f2f313731383830393436372e6d7033, '2024-06-19 15:04:27', NULL),
(23, 'An-Nasr', 3, 'file/1718809532.pdf', 0x617564696f2f313731383830393533322e6d7033, '2024-06-19 15:05:32', NULL),
(24, 'Al-Masad', 5, 'file/1718809578.pdf', 0x617564696f2f313731383830393537382e6d7033, '2024-06-19 15:06:18', NULL),
(25, 'Al-Ikhlas', 4, 'file/1718809642.pdf', 0x617564696f2f313731383830393634322e6d7033, '2024-06-19 15:07:22', NULL),
(26, 'Al-Falaq', 5, 'file/1718809707.pdf', 0x617564696f2f313731383830393730372e6d7033, '2024-06-19 15:08:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `U_ID` int(11) NOT NULL,
  `U_IC` varchar(12) NOT NULL,
  `U_FName` varchar(20) NOT NULL,
  `U_LName` varchar(20) NOT NULL,
  `U_Email` varchar(50) NOT NULL,
  `U_PhoneNo` varchar(11) NOT NULL,
  `U_Gender` varchar(10) NOT NULL,
  `U_Password` varchar(100) NOT NULL,
  `U_Roles` varchar(20) NOT NULL,
  `U_RegDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`U_ID`, `U_IC`, `U_FName`, `U_LName`, `U_Email`, `U_PhoneNo`, `U_Gender`, `U_Password`, `U_Roles`, `U_RegDate`) VALUES
(16, '020812076543', 'Syakir', 'Aiman', 'syakiraimn2@gmail.com', '0134567897', 'Lelaki', 'e66055e8e308770492a44bf16e875127', 'admin', '2024-06-19 17:01:16'),
(17, '021118010363', 'Akmal', 'Nasir', 'kemal4966@gmail.com', '0143295850', 'Lelaki', '751cb3f4aa17c36186f4856c8982bf27', 'staff', '2024-08-06 15:01:24'),
(18, '020205078954', 'Mohd', 'Muaz', 'mhdxmuaz88@gmail.com', '01639363434', 'Lelaki', '525fd21716567c06c86d51f9df2ce0e7', 'ptj', '2024-06-19 17:01:32'),
(19, '03474838434', 'Mira', 'Delisha', 'mira@gmail.com', '0173539323', 'Perempuan', 'fa3bb543489b57b8d92f13c279550ad8', 'user', '2024-06-19 17:02:05'),
(30, '0211101657', 'Muhammad Danial', 'Ilham', 'danialilham5@gmail.com', '0108905617', 'Lelaki', '325a2cc052914ceeb8c19016c091d2ac', 'user', '2024-06-19 15:37:13'),
(31, '000203011820', 'Muiz', 'Azizi', 'amrabbani26@gmail.com', '0193262966', 'Lelaki', 'e64b78fc3bc91bcbc7dc232ba8ec59e0', 'user', '2024-06-19 16:24:30'),
(32, '030416098767', 'Najwa', 'Latif', 'najwa@gmail.com', '0154376897', 'Perempuan', '100464233c919f5a1c431be34d2debf5', 'user', '2024-06-19 17:28:00'),
(33, '980709045678', 'Ahmad', 'Nizam', 'Ahmad@gmail.com', '01567386238', 'Lelaki', '488c2dcdd548d25f891d867e5de192e7', 'user', '2024-06-19 17:31:39'),
(34, '980818017867', 'Anis', 'Sofia', 'anis@gmail.com', '01127368979', 'Perempuan', 'edfa9d13fd3310a17a0439f68cc42416', 'user', '2024-06-19 17:35:29'),
(35, '040306078943', 'Aiman', 'Nazeem', 'aimannaz@gmail.com', '01246328343', 'Lelaki', '7684f1e21bdcb18c4107ba8586178678', 'user', '2024-06-19 17:36:46'),
(36, '030908016787', 'Siti', 'Hajar', 'jar@gmail.com', '01217789854', 'Perempuan', '3e1d0bb527f1f008323e64494006dccc', 'user', '2024-06-19 17:39:01'),
(37, '020406019874', 'Adam', 'Rizami', 'adamriz@gmail.com', '01378439874', 'Lelaki', 'b311a5e58735a9a23ba6064f2d1c0f55', 'user', '2024-06-19 17:40:53'),
(38, '020324057689', 'Nabil', 'Nazri', 'nabil@gmail.com', '01987865432', 'Lelaki', '0f606821e668dd67c57dce1a203d4121', 'user', '2024-06-19 17:41:59'),
(39, '020405017693', 'Ahmad', 'Uwais', 'ahmaduwais@gmail.com', '01543289765', 'Lelaki', '8a4b01942d7bbc87b95ba22811a7863d', 'user', '2024-06-19 17:43:36'),
(40, '000203011818', 'Muhammad ', 'Akmal', 'mal@gmail.com', '0173539323', 'Lelaki', '1752efe3d40c9d492d567f7954a31ad5', 'user', '2024-06-22 08:06:17');

-- --------------------------------------------------------

--
-- Table structure for table `user_donation`
--

CREATE TABLE `user_donation` (
  `UD_ID` int(11) NOT NULL,
  `D_ID` int(11) NOT NULL,
  `UP_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_donation`
--

INSERT INTO `user_donation` (`UD_ID`, `D_ID`, `UP_ID`) VALUES
(22, 44, 379),
(23, 45, 378),
(24, 46, 370),
(25, 47, 376),
(26, 48, 372),
(27, 49, 377),
(28, 50, 373),
(29, 51, 390),
(30, 52, 389),
(31, 53, 384),
(32, 54, 388),
(33, 55, 392),
(34, 56, 393),
(35, 57, 384),
(36, 58, 385),
(37, 59, 385);

-- --------------------------------------------------------

--
-- Table structure for table `user_program`
--

CREATE TABLE `user_program` (
  `UP_ID` int(11) NOT NULL,
  `UP_Status` varchar(20) NOT NULL,
  `UP_RegDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `U_ID` int(11) NOT NULL,
  `P_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_program`
--

INSERT INTO `user_program` (`UP_ID`, `UP_Status`, `UP_RegDate`, `U_ID`, `P_ID`) VALUES
(370, 'Hadir', '2024-06-19 17:56:07', 19, 201),
(371, 'Hadir', '2024-06-19 17:56:07', 31, 201),
(372, 'Hadir', '2024-06-19 17:56:07', 30, 201),
(373, 'Hadir', '2024-06-19 17:56:07', 32, 201),
(374, 'Hadir', '2024-06-19 17:56:07', 33, 201),
(375, 'Hadir', '2024-06-19 17:56:07', 34, 201),
(376, 'Hadir', '2024-06-19 17:56:07', 35, 201),
(377, 'Hadir', '2024-06-19 17:56:07', 36, 201),
(378, 'Hadir', '2024-06-19 17:56:07', 37, 201),
(379, 'Hadir', '2024-06-19 17:56:07', 38, 201),
(380, 'Hadir', '2024-06-19 20:14:47', 38, 206),
(384, 'Hadir', '2024-06-19 20:14:47', 39, 206),
(385, 'Hadir', '2024-06-19 20:14:47', 19, 206),
(386, 'Hadir', '2024-06-19 20:14:47', 34, 206),
(387, 'Hadir', '2024-06-19 20:14:47', 35, 206),
(388, 'Hadir', '2024-06-19 20:14:47', 30, 206),
(389, 'Hadir', '2024-06-19 20:14:47', 36, 206),
(390, 'Hadir', '2024-06-19 20:14:47', 32, 206),
(391, 'Hadir', '2024-06-20 01:02:33', 32, 202),
(392, 'Hadir', '2024-06-20 01:02:33', 30, 202),
(393, 'Hadir', '2024-06-20 01:02:33', 39, 202),
(394, 'Hadir', '2024-08-08 11:24:08', 39, 209);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`C_ID`);

--
-- Indexes for table `donation`
--
ALTER TABLE `donation`
  ADD PRIMARY KEY (`D_ID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FB_ID`),
  ADD UNIQUE KEY `UP_ID` (`UP_ID`);

--
-- Indexes for table `prayertime`
--
ALTER TABLE `prayertime`
  ADD PRIMARY KEY (`PT_ID`),
  ADD UNIQUE KEY `U_ID` (`U_ID`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`P_ID`),
  ADD KEY `C_ID` (`C_ID`);

--
-- Indexes for table `surah`
--
ALTER TABLE `surah`
  ADD PRIMARY KEY (`S_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`U_ID`);

--
-- Indexes for table `user_donation`
--
ALTER TABLE `user_donation`
  ADD PRIMARY KEY (`UD_ID`),
  ADD UNIQUE KEY `D_ID` (`D_ID`,`UP_ID`),
  ADD KEY `UP_ID` (`UP_ID`);

--
-- Indexes for table `user_program`
--
ALTER TABLE `user_program`
  ADD PRIMARY KEY (`UP_ID`),
  ADD UNIQUE KEY `U_ID` (`U_ID`,`P_ID`),
  ADD KEY `P_ID` (`P_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `C_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `donation`
--
ALTER TABLE `donation`
  MODIFY `D_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FB_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `prayertime`
--
ALTER TABLE `prayertime`
  MODIFY `PT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `P_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT for table `surah`
--
ALTER TABLE `surah`
  MODIFY `S_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `U_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `user_donation`
--
ALTER TABLE `user_donation`
  MODIFY `UD_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user_program`
--
ALTER TABLE `user_program`
  MODIFY `UP_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=398;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`UP_ID`) REFERENCES `user_program` (`UP_ID`);

--
-- Constraints for table `prayertime`
--
ALTER TABLE `prayertime`
  ADD CONSTRAINT `prayertime_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `user` (`U_ID`);

--
-- Constraints for table `program`
--
ALTER TABLE `program`
  ADD CONSTRAINT `program_ibfk_1` FOREIGN KEY (`C_ID`) REFERENCES `category` (`C_ID`);

--
-- Constraints for table `user_donation`
--
ALTER TABLE `user_donation`
  ADD CONSTRAINT `user_donation_ibfk_1` FOREIGN KEY (`UP_ID`) REFERENCES `user_program` (`UP_ID`),
  ADD CONSTRAINT `user_donation_ibfk_2` FOREIGN KEY (`D_ID`) REFERENCES `donation` (`D_ID`);

--
-- Constraints for table `user_program`
--
ALTER TABLE `user_program`
  ADD CONSTRAINT `user_program_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `user` (`U_ID`),
  ADD CONSTRAINT `user_program_ibfk_2` FOREIGN KEY (`P_ID`) REFERENCES `program` (`P_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
