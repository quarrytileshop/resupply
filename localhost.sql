-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 08, 2026 at 12:03 PM
-- Server version: 10.6.24-MariaDB-cll-lve
-- PHP Version: 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resupplyrocket_db`
--
CREATE DATABASE IF NOT EXISTS `resupplyrocket_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `resupplyrocket_db`;

-- --------------------------------------------------------

--
-- Table structure for table `catalog_items`
--

CREATE TABLE `catalog_items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `order_multiple` int(11) NOT NULL DEFAULT 1,
  `vendor_id` int(11) DEFAULT NULL,
  `item_type` enum('general','paint','propane') DEFAULT 'general',
  `requires_quote` tinyint(1) DEFAULT 0,
  `category` varchar(255) DEFAULT 'Uncategorized'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `catalog_items`
--

INSERT INTO `catalog_items` (`id`, `item_name`, `description`, `sku`, `price`, `image_url`, `order_multiple`, `vendor_id`, `item_type`, `requires_quote`, `category`) VALUES
(3, '1 Inch EMT', '1\" Electrical metalic tubing in 10ft sticks', '30281', 0.00, 'product-images/30281.PNG', 1, NULL, 'general', 0, 'electrical'),
(4, '1 1/2 Inch IMC (Rigid)', '1 1/2\" Rigid galvanized electrical conduit (IMC) in 10 ft sticks', '30288', 0.00, 'product-images/30288.PNG', 1, NULL, 'general', 0, 'electrical'),
(5, '4 Square metal box', ' 2-1/8\" Deep electrical box. Metal with 1/2-3/4KO', '32962', 0.00, 'product-images/32962.PNG', 1, NULL, 'general', 0, 'electrical'),
(6, '4 Square metal blank', 'Metal 4 square box cover', '3016087', 0.00, 'product-images/3016087.PNG', 1, NULL, 'general', 0, 'electrical'),
(7, 'Metal Duplex Plate', 'Metal Duplex Single gang plate 4\"x2\"', '3016037', 0.00, 'product-images/3016037.PNG', 1, NULL, 'general', 0, 'electrical'),
(8, '4x4 Duplex plate', '4 Square Metal Box Cover with duplex opening', '30307', 0.00, 'product-images/30307.PNG', 1, NULL, 'general', 0, 'electrical'),
(9, 'Handibox', 'Single Gang Metal Box', '30337', 0.00, 'product-images/30337.PNG', 1, NULL, 'general', 0, 'electrical'),
(10, 'Cut in box', 'Plastic Old work cut in box', '30733', 0.00, 'product-images/30733.PNG', 1, NULL, 'general', 0, 'electrical'),
(11, 'Appliance cord', 'Light duty appliance cord extension', '31459', 0.00, 'product-images/31459.PNG', 1, NULL, 'general', 0, 'electrical'),
(12, 'Appliance cord', 'appliance cord 9 ft', '31471', 0.00, 'product-images/31471.PNG', 1, NULL, 'general', 0, 'electrical'),
(13, '#12 Black THHN', '#12 THHN stranded BLACK 500ft roll', '32353', 0.00, 'product-images/32353.PNG', 1, NULL, 'general', 0, 'electrical'),
(14, '#12 White THHN', '#12 THHN stranded WHITE 500ft roll', '32354', 0.00, 'product-images/32354.PNG', 1, NULL, 'general', 0, 'electrical'),
(15, '#12 Red THHN', '#12 THHN stranded RED 500ft roll', '32357', 0.00, 'product-images/32357.PNG', 1, NULL, 'general', 0, 'electrical'),
(16, '#12 Green THHN', '#12 THHN Stranded GREEN 500ft roll', '34784', 0.00, 'product-images/34784.PNG', 1, NULL, 'general', 0, 'electrical'),
(17, '#12 Gray THHN', '#12 THHN stranded GRAY 500ft roll', 'IES001', 0.00, 'product-images/IES001.PNG', 1, NULL, 'general', 0, 'electrical'),
(18, 'Red Plenum Alarm cable', 'RED 18/2 SHIELD PLNM ALARM CBL/M 1000ft', 'GRB0002', 0.00, 'product-images/GRB0002.PNG', 1, NULL, 'general', 0, 'electrical'),
(19, '1/2\" sealtite straight connector', '1/2\" staight sealtite connector', '33050', 0.00, 'product-images/33050.PNG', 1, NULL, 'general', 0, 'electrical'),
(20, '1/2\" Sealtite 90 connector', '1/2\" Sealtite 90 degree connector', '33053', 0.00, 'product-images/33053.PNG', 1, NULL, 'general', 0, 'electrical'),
(21, '1/2\" Liquid tight flex', '1/2\"Sealtite flex conduit (metal) 100\'', '3296852', 0.00, 'product-images/3296852.PNG', 1, NULL, 'general', 0, 'electrical'),
(22, '3/4\" pvc conduit sched 40', '3/4\" PVC electrical conduit schedule 40 10ft', '33201', 0.00, 'product-images/33201.PNG', 1, NULL, 'general', 0, 'electrical'),
(23, '1\" PVC Female adapter', '1\" PVC electrical female adapter', '33214', 0.00, 'product-images/33214.PNG', 1, NULL, 'general', 0, 'electrical'),
(24, '1\" to 3/4\" PVC reducer', '1\" to 3/4\" adapter PVC electrical', '3006442', 0.00, 'product-images/3006442.PNG', 1, NULL, 'general', 0, 'electrical'),
(25, '1\" PVC Male adapter', '1\" PVC Male adapter electrical', '33220', 0.00, 'product-images/33220.PNG', 1, NULL, 'general', 0, 'electrical'),
(26, '2\" PVC Male adapter', '2\" PVC Male adapter electrical', '33223', 0.00, 'product-images/33223.PNG', 1, NULL, 'general', 0, 'electrical'),
(27, '3\" PVC Male adapter', '3\" PVC Male adapter electrical', '33583', 0.00, 'product-images/33583.PNG', 1, NULL, 'general', 0, 'electrical'),
(28, '1\" Lock nut', '1\" Metal lock nut', '3182433', 0.00, 'product-images/3182433.PNG', 1, NULL, 'general', 0, 'electrical'),
(29, '2\" Lock nut', '2\" Metal locknut', '3182540', 0.00, 'product-images/3182540.PNG', 1, NULL, 'general', 0, 'electrical'),
(30, '1/2\" Lock nut', '1/2\" Metal locknut', '3182417', 0.00, 'product-images/3182417.PNG', 3, NULL, 'general', 0, 'electrical'),
(31, '3\" Lock nut', '3\" LOCKNUT', '3101367', 0.00, 'product-images/3101367.PNG', 1, NULL, 'general', 0, 'electrical'),
(32, 'PVC 2 gang Box', 'PVC 2 Gang box 1 x 3/4\" opening', '3020856', 0.00, 'product-images/3020856.PNG', 1, NULL, 'general', 0, 'electrical'),
(33, '7 Hole plastic Box', 'WP outlet box 7x1/2\" holes', '3291374', 0.00, 'product-images/3291374.PNG', 1, NULL, 'general', 0, 'electrical'),
(34, 'Small cable ties 4\"', '4\" zip ties (PACK 100ea.)', '3004711', 0.00, 'product-images/3004711.PNG', 1, NULL, 'general', 0, 'hardware'),
(35, 'Grounding pig tail', 'grounding pig tails  (pack of 2)', '3013588', 0.00, 'product-images/3013588.PNG', 1, NULL, 'general', 0, 'electrical'),
(36, 'Conduit grounding clamp 2\"', '2\" Gounding clamp for conduits', '3172749', 0.00, 'product-images/3172749.PNG', 1, NULL, 'general', 0, 'electrical'),
(37, '1\" Weatherproof coupling', '1\" WP Compression coupling for metal conduit', '3179959', 0.00, 'product-images/3179959.PNG', 1, NULL, 'general', 0, 'electrical'),
(38, '3/8 1 hole straps', '3/8\" One hols straps (pack of 3)', '3180494', 0.00, 'product-images/3180494.PNG', 1, NULL, 'general', 0, 'fasteners'),
(39, '1/2 Rigid 1 hole strap', '1/2\" Rigid 1 hole strap (pack of 3)', '3181153', 0.00, 'product-images/3181153.PNG', 1, NULL, 'general', 0, 'electrical'),
(40, 'Black electrical tape', 'Scotch 3/4\" electrical tape Black', '33305', 0.00, 'product-images/33305.PNG', 1, NULL, 'general', 0, 'electrical'),
(41, 'Red electrical tape', '3M Red electrical tape', '3309929', 0.00, 'product-images/3309929.PNG', 1, NULL, 'general', 0, 'electrical'),
(42, 'White electrical tape', '3M White electrical tape', '3309937', 0.00, 'product-images/3309937.PNG', 1, NULL, 'general', 0, 'electrical'),
(43, 'Green electrical tape', '3M Green electrical tape', '3311263', 0.00, 'product-images/3311263.PNG', 1, NULL, 'general', 0, 'electrical'),
(44, 'Violet electrical tape', '3M Violet electrical tape', '3311461', 0.00, 'product-images/3311461.PNG', 1, NULL, 'general', 0, 'electrical'),
(45, 'Brown electrical tape', '3M Brown electrical tape', '3311610', 0.00, 'product-images/3311610.PNG', 1, NULL, 'general', 0, 'electrical'),
(46, 'Orange electrical tape', '3M Orange electrical tape', '3311644', 0.00, 'product-images/3311644.PNG', 1, NULL, 'general', 0, 'electrical'),
(47, 'Yellow electrical tape', '3M Yellow electrical tape', '3311719', 0.00, 'product-images/3311719.PNG', 1, NULL, 'general', 0, 'electrical'),
(48, 'Blue electrical tape', '3M Blue electrical tape', '3312303', 0.00, 'product-images/3312303.PNG', 1, NULL, 'general', 0, 'electrical'),
(49, 'Unistrut 10 ft Galvanized', '1 5/8x10ft Unistrut galvanized', '3227568', 0.00, 'product-images/3227568.PNG', 1, NULL, 'general', 0, 'electrical'),
(50, 'Unistrut pipe straps 1 1/2\"', '1 1/2\" Strut strap for IMC', '3407475', 0.00, 'product-images/3407475.PNG', 1, NULL, 'general', 0, 'electrical'),
(51, '1/2\" cone nuts', '1/2\" CONE NUT PACK OF 5** DISCONTINUED', '3407632', 0.00, 'product-images/3407632.PNG', 1, NULL, 'general', 0, 'electrical'),
(52, '3/8\" cone nuts', '3/8\" Cone nuts (pack of 5)', '3017978', 0.00, 'product-images/3017978.PNG', 1, NULL, 'general', 0, 'fasteners'),
(53, 'Backwire 20 amp receptacle', '20 AMP Duplex receptacle backwire', '3500543', 0.00, 'product-images/3500543.PNG', 1, NULL, 'general', 0, 'electrical'),
(54, 'Screw anchor kit', 'Plastic screw anchor kit', '5326038', 0.00, 'product-images/5326038.PNG', 1, NULL, 'general', 0, 'fasteners'),
(55, 'Pack of 24  Ace water', 'Ace water. Pallets are 84 packs', '9602780', 0.00, 'product-images/9602780.PNG', 1, NULL, 'general', 0, 'Uncategorized'),
(57, 'Caster with lock 900# rating', 'RED DURAPLY CASTER WITH LOCK 900#', 'DH003', 0.00, 'product-images/DH003.PNG', 1, NULL, 'general', 0, 'hardware'),
(58, 'Nylon cord connector', 'CABLE GLAND (CORD GRIP)', 'DSC001', 0.00, 'product-images/DSC001.PNG', 50, NULL, 'general', 0, 'hardware'),
(59, 'Butterfly fitting', '#14 BUTTERFLY', 'ELD001', 0.00, 'product-images/ELD001.PNG', 500, NULL, 'general', 0, 'hardware'),
(60, 'Large cable ties', '11\" Black zip ties (pack of 100)', '3004688', 0.00, 'product-images/3004688.PNG', 1, NULL, 'general', 0, 'hardware'),
(61, 'Red Ranger Wirenuts', '3M Red Ranger wire nuts', 'IES002', 0.00, 'product-images/IES002.PNG', 1, NULL, 'general', 0, 'electrical'),
(62, '1 1/4\" sheetrock screws', '1-1/4\" SHEETROCK SCREWS', 'JM0011', 0.00, 'product-images/JM0011.PNG', 1, NULL, 'general', 0, 'fasteners'),
(63, '2 1/2\" self tapping screws', '2-1/2\" SELF TAPPING SCREWS', 'JM0012', 0.00, 'product-images/JM0012.PNG', 1, NULL, 'general', 0, 'fasteners'),
(64, '1 3/4\" Hex head tapcon', '1-3/4\" HEX HEAD TAPCON', 'JM0013', 0.00, 'product-images/JM0013.PNG', 1, NULL, 'general', 0, 'fasteners'),
(65, '3/4\" self tap self seal screws', '3/4\" SELF TAPPING SELF SEALING SCREWS', 'JM0014', 0.00, 'product-images/JM0014.PNG', 1, NULL, 'general', 0, 'fasteners'),
(66, '1/2\"x 4 1/4 Wedge anchor', '1/2\" X 4 1/4 WEDGE ANCHORS', 'JM0015', 0.00, 'product-images/JM0015.PNG', 1, NULL, 'general', 0, 'fasteners'),
(67, '1/4 x 1 1/4\" SS Hex Bolt', '1/4\" X 1-1/4\" STAINLESS BOLT', 'JM0003', 0.00, 'product-images/JM0003.PNG', 1, NULL, 'general', 0, 'fasteners'),
(68, '1/4 SS Washer', '1/4\" STAINLESS WASHERS', 'JM0004', 0.00, 'product-images/JM0004.PNG', 1, NULL, 'general', 0, 'fasteners'),
(69, '1/4\" SS lock washer', '1/4\" STAINLESS LOCK WASHERS', 'JM0005', 0.00, 'product-images/JM0005.PNG', 1, NULL, 'general', 0, 'fasteners'),
(70, '1/4 SS Fender washer', '1/4\" STAINLESS FENDER WASHERS', 'JM0006', 0.00, 'product-images/JM0006.PNG', 1, NULL, 'general', 0, 'fasteners'),
(71, '3/8\" x 1 1/4\" SS Hex bolt', '3/8\" X 1-1/4\" STAINLESS BOLT', 'JM0007', 0.00, 'product-images/JM0007.PNG', 1, NULL, 'general', 0, 'fasteners'),
(72, '3/8\" SS washer', '3/8\" STAINLESS FLAT WASHERS', 'JM0008', 0.00, 'product-images/JM0008.PNG', 1, NULL, 'general', 0, 'fasteners'),
(73, '3/8\" SS lock washer', '3/8\" STAINLESS LOCK WASHERS', 'JM0009', 0.00, 'product-images/JM0009.PNG', 1, NULL, 'general', 0, 'fasteners'),
(74, '3/8\" SS Fender washer', '3/8\" STAINLESS FENDER WASHERS', 'JM0010', 0.00, 'product-images/JM0010.PNG', 1, NULL, 'general', 0, 'fasteners'),
(75, '3/8\" x 1\" SS Hex bolt', '3/8\" x 1\" Stainless steel hex bolt', 'JM0016', 0.00, 'product-images/JM0016.PNG', 1, NULL, 'general', 0, 'fasteners'),
(76, '1/2\" x 1 Hex bolt', '1/2\" X 1\" HEX BOLT', 'JM0020', 0.00, 'product-images/JM0020.PNG', 1, NULL, 'general', 0, 'fasteners'),
(77, '1/2\" washer', '1/2\" FLAT WASHER', 'JM0021', 0.00, 'product-images/JM0021.PNG', 1, NULL, 'general', 0, 'fasteners'),
(78, '3/8 Lag', '3/8\" LAG BOLT', 'JM0022', 0.00, 'product-images/JM0022.PNG', 1, NULL, 'general', 0, 'fasteners'),
(79, '3/8 washer', '3/8\" FLAT WASHER', 'JM0023', 0.00, 'product-images/JM0023.PNG', 1, NULL, 'general', 0, 'fasteners'),
(80, '3/8\" SS Flange nut', '3/8\" SS SERRATED FLANGE NUT', 'JM0017', 0.00, 'product-images/JM0017.PNG', 1, NULL, 'general', 0, 'fasteners'),
(81, '1/2\" SS Flange nut', '1/2\" SS SERRATED FLANGE NUT', 'JM0018', 0.00, 'product-images/JM0018.PNG', 1, NULL, 'general', 0, 'fasteners'),
(82, '8/32 SS flange nut', '8/32 SS SERRATED FLANGE NUT', 'JM0019', 0.00, 'product-images/JM0019.PNG', 1, NULL, 'general', 0, 'fasteners'),
(83, '1/4\" SS flange nut', '1/4\" SERRATED FLANGE NUT', 'JM0024', 0.00, 'product-images/JM0024.PNG', 1, NULL, 'general', 0, 'fasteners'),
(84, '3/8 SS hex nut', '3/8\" STAINLESS HEX NUT', 'JM0025', 0.00, 'product-images/JM0025.PNG', 1, NULL, 'general', 0, 'fasteners'),
(85, 'Plastic 8 thru 12 anchor', 'Plastic anchor for #8 thru #12 screws', 'JM0026', 0.00, 'product-images/JM0026.PNG', 1, NULL, 'general', 0, 'fasteners'),
(86, '#8 x 1 1/4\" pan head tapper', '#8 1-1/4\" PAN HEAD TAPPING SCS \"A\" STL ZP', 'JM0027', 0.00, 'product-images/JM0027.PNG', 1, NULL, 'general', 0, 'fasteners'),
(87, '1/4\" SS hex nut', '1/4\" STAINLESS HEX NUT', 'JM0028', 0.00, 'product-images/JM0028.PNG', 1, NULL, 'general', 0, 'fasteners'),
(88, '#8 auger anchor', '#8 AUGER (WALLBOARD) ANCHOR', 'JM0029', 0.00, 'product-images/JM0029.PNG', 1, NULL, 'general', 0, 'fasteners'),
(89, 'Philips wall dog', '1/4X1-1/4 PHL PAN BULK WALLDOG', 'JM0030', 0.00, 'product-images/JM0030.PNG', 1, NULL, 'general', 0, 'fasteners'),
(90, '3/8\"-16 10ft threaded rod', '3/8-16 THREADED ROD ZINC PLATED 10 FT', 'JM0031', 0.00, 'product-images/JM0031.PNG', 1, NULL, 'general', 0, 'hardware'),
(91, '3/8-16 x1 SS Hex bolt', '3/8-16 X1\" STAINLESS HEX BOLT', 'JM0035', 0.00, 'product-images/JM0035.PNG', 1, NULL, 'general', 0, 'fasteners'),
(92, '3/8-16 x1\" SS flange nut', '3/8-16 X1\" STAINLESS HEX FLANGE NUT', 'JM0036', 0.00, 'product-images/JM0036.PNG', 1, NULL, 'general', 0, 'fasteners'),
(93, '3/8\" x 1\" SS lag', '3/8 X1\" STAINLESS HEX HEAD LAG', 'JM0037', 0.00, 'product-images/JM0037.PNG', 1, NULL, 'general', 0, 'fasteners'),
(94, '5/16-18 x1 SS Hex bolt', '5/16-18 X1\" STAINLESS HEX BOLT', 'JM0038', 0.00, 'product-images/JM0038.PNG', 1, NULL, 'general', 0, 'fasteners'),
(95, '5/16\" SS Lockwasher', '5/16 SPLIT LOCK WASHER STAINLESS', 'JM0039', 0.00, 'product-images/JM0039.PNG', 1, NULL, 'general', 0, 'fasteners'),
(96, '5/16-8 SS Hex nut', '5/16-18 HEX FINISH NUT STAINLESS', 'JM0040', 0.00, 'product-images/JM0040.PNG', 1, NULL, 'general', 0, 'fasteners'),
(97, '#8 x 1\" Philips wood screw', '#8 X 1 PHL ROUND WOOD SCREW SS', 'JM0041', 0.00, 'product-images/JM0041.PNG', 1, NULL, 'general', 0, 'fasteners'),
(98, '1/4\" x 1\" SS Hex lag', '1/4\"X1 HEX HD LAG STAINLESS STEE', 'JM0052', 0.00, 'product-images/JM0052.PNG', 1, NULL, 'general', 0, 'fasteners'),
(99, '#8 x 1/2\" washer head', '8-18 x 1/2 Hex washer head self drilling', 'JM0053', 0.00, 'product-images/JM0053.PNG', 1, NULL, 'general', 0, 'fasteners'),
(100, '1/4-20 x 3/4 SiBrz Hex bolt', '1/4-20 X 3/4 HEX HD CAP SCS Silicone Bronze', 'JM0032', 0.00, 'product-images/JM0032.PNG', 1, NULL, 'general', 0, 'fasteners'),
(101, '1/4\" Hex nut SiBrz', '1/4 HEX NUT Silicone Bronze', 'JM0033', 0.00, 'product-images/JM0033.PNG', 1, NULL, 'general', 0, 'fasteners'),
(102, '1/4\" lock washer SiBrz', '1/4 SPLIT LOCK WASHER Silicone Bronze', 'JM0034', 0.00, 'product-images/JM0034.PNG', 1, NULL, 'general', 0, 'fasteners'),
(103, '3/8\" x 3/4\" Hex bolt SiBrz', '3/8 X 3/4 HEX HD CAP SCS Silicone Bronze', 'JM0105', 0.00, 'product-images/JM0105.PNG', 1, NULL, 'general', 0, 'fasteners'),
(104, '3/8 lock washer SiBrz', '3/8 SPLIT LOCK WASHER Silicone Bronze', 'JM0106', 0.00, 'product-images/JM0106.PNG', 1, NULL, 'general', 0, 'fasteners'),
(105, '3/8\" Hex nut SiBrz', '3/8 HEX NUT Silicone Bronze', 'JM0107', 0.00, 'product-images/JM0107.PNG', 1, NULL, 'general', 0, 'fasteners'),
(107, 'Butyl Tape', '3\" BLACK BUTYL TAPE X24FT', '5482732', 0.00, 'product-images/5482732.PNG', 1, NULL, 'general', 0, 'hardware'),
(108, 'Cinder block', 'CINDERBLOCK', 'QR099852', 0.00, 'product-images/QR099852.PNG', 1, NULL, 'general', 0, 'garden'),
(109, 'Plug', 'Hubbel 520SP', 'ROY0001', 0.00, 'product-images/ROY0001.PNG', 1, NULL, 'general', 0, 'electrical'),
(110, 'Propane Exchange  (B2B)', 'Fresh propane tank exchanged for B2B customer\'s empty propane tank (any brand). For customers with a B2B account and a valid credit card on file.', 'PROPANEB2BEXCHANGEDELIVERED', 23.99, 'product-images/PROPANEB2BEXCHANGEDELIVERED.PNG', 1, NULL, 'propane', 0, 'patio'),
(111, 'Propane Spare (B2B)', 'New full propane tank where there is no empty tank to exchange for it. Delivered to B2B customers with an account and a valid credit card on file\r\n', 'PROPANEB2BSPARE', 67.99, 'product-images/PROPANEB2BSPARE.PNG', 1, NULL, 'propane', 0, 'patio');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `mailing_address` text DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `type` enum('retail','wholesale') DEFAULT 'retail',
  `resale_number` varchar(50) DEFAULT NULL,
  `authorized_people` text DEFAULT NULL,
  `credit_card_contact` tinyint(1) DEFAULT 0,
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `suspended` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `address`, `contact_name`, `contact_email`, `mailing_address`, `account_number`, `type`, `resale_number`, `authorized_people`, `credit_card_contact`, `approval_status`, `created_at`, `suspended`) VALUES
(1, 'Holstein Inc', 'apt 14', 'Russ', '', 'Apt 14', '1677', 'retail', '', 'me, myself,I', 1, 'approved', '2025-07-22 16:43:58', 0),
(2, 'Hshs', 'Gaha', 'Hshs', 'moontontlol321@gmail.com', 'Gaha', NULL, 'retail', '', 'Vsjsjsn', 1, 'pending', '2025-11-27 14:14:27', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'draft',
  `fulfillment_type` varchar(50) DEFAULT NULL,
  `po_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `internal_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `fulfillment_type`, `po_number`, `created_at`, `internal_notes`) VALUES
(12, 7, 'pending', 'delivery', NULL, '2025-07-21 21:48:15', ''),
(13, 7, 'pending', 'delivery', NULL, '2025-07-21 21:56:26', ''),
(14, 7, 'pending', 'delivery', NULL, '2025-07-21 21:56:59', ''),
(15, 7, 'pending', 'delivery', NULL, '2025-07-21 22:02:23', 'Please'),
(16, 7, 'pending', 'delivery', NULL, '2025-07-21 22:05:54', ''),
(17, 7, 'pending', 'delivery', NULL, '2025-07-21 22:10:17', ''),
(18, 7, 'pending', 'delivery', NULL, '2025-07-21 22:34:57', '2'),
(19, 7, 'pending', 'delivery', NULL, '2025-07-21 22:49:05', ''),
(20, 7, 'pending', 'delivery', NULL, '2025-07-21 22:51:20', ''),
(21, 7, 'pending', 'delivery', NULL, '2025-07-21 22:55:41', ''),
(22, 7, 'pending', 'delivery', NULL, '2025-07-22 00:00:57', ''),
(23, 7, 'pending', 'delivery', NULL, '2025-07-22 02:17:09', 'Test order from Foster'),
(26, 11, 'draft', NULL, NULL, '2025-07-25 17:08:56', NULL),
(28, 11, 'archived', NULL, NULL, '2025-07-25 18:44:24', NULL),
(29, 11, 'archived', NULL, NULL, '2025-07-25 19:04:52', NULL),
(30, 11, 'archived', 'delivery', '', '2025-07-25 20:33:26', ''),
(31, 11, 'archived', 'delivery', '', '2025-07-25 20:33:39', ''),
(32, 11, 'archived', 'delivery', '6667', '2025-07-25 20:33:52', ''),
(33, 11, 'sent', 'delivery', '6654', '2025-07-25 21:03:32', ''),
(34, 11, 'sent', 'delivery', '', '2025-07-26 16:10:14', ''),
(35, 11, 'sent', 'delivery', '', '2025-07-26 16:31:04', ''),
(36, 11, 'sent', 'delivery', '', '2025-07-26 16:41:07', ''),
(37, 11, 'sent', 'delivery', '', '2025-07-28 14:07:57', ''),
(38, 11, 'sent', 'delivery', 'ST-#44', '2025-07-28 14:28:07', ''),
(39, 11, 'sent', 'delivery', 'ST#-300', '2025-07-28 16:52:12', ''),
(40, 11, 'sent', 'delivery', '745X', '2025-07-28 17:30:10', 'Deliver to shop'),
(41, 11, 'draft', 'delivery', 'STORE-2', '2025-07-28 20:27:05', 'fresh'),
(42, 11, 'sent', 'delivery', 'STORE-2', '2025-07-28 21:25:53', 'fresh'),
(43, 7, 'sent', 'delivery', NULL, '2025-07-29 21:15:40', '');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `catalog_item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `paint_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`paint_details`)),
  `quote_status` varchar(50) DEFAULT NULL,
  `quoted_price` decimal(10,2) DEFAULT NULL,
  `quote_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `catalog_item_id`, `quantity`, `paint_details`, `quote_status`, `quoted_price`, `quote_notes`) VALUES
(12, 12, 110, 12, NULL, NULL, NULL, NULL),
(13, 13, 110, 5, NULL, NULL, NULL, NULL),
(14, 13, 111, 1, NULL, NULL, NULL, NULL),
(15, 14, 110, 2, NULL, NULL, NULL, NULL),
(16, 14, 111, 1, NULL, NULL, NULL, NULL),
(17, 15, 110, 8, NULL, NULL, NULL, NULL),
(18, 15, 111, 2, NULL, NULL, NULL, NULL),
(19, 16, 110, 7, NULL, NULL, NULL, NULL),
(20, 16, 111, 7, NULL, NULL, NULL, NULL),
(21, 17, 110, 333, NULL, NULL, NULL, NULL),
(22, 17, 111, 2, NULL, NULL, NULL, NULL),
(23, 18, 110, 9, NULL, NULL, NULL, NULL),
(24, 19, 110, 6, NULL, NULL, NULL, NULL),
(25, 19, 111, 2, NULL, NULL, NULL, NULL),
(26, 20, 110, 7, NULL, NULL, NULL, NULL),
(27, 21, 110, 71, NULL, NULL, NULL, NULL),
(28, 22, 110, 7, NULL, NULL, NULL, NULL),
(29, 23, 110, 1, NULL, NULL, NULL, NULL),
(34, 26, 19, 12, NULL, NULL, NULL, NULL),
(35, 26, 20, 2, NULL, NULL, NULL, NULL),
(36, 26, 11, 1, NULL, NULL, NULL, NULL),
(39, 28, 3, 21, NULL, NULL, NULL, NULL),
(40, 28, 21, 2, NULL, NULL, NULL, NULL),
(41, 28, 15, 5, NULL, NULL, NULL, NULL),
(42, 29, 11, 1, NULL, NULL, NULL, NULL),
(43, 30, 19, 5, NULL, NULL, NULL, NULL),
(44, 30, 53, 1, NULL, NULL, NULL, NULL),
(45, 33, 19, 50, NULL, NULL, NULL, NULL),
(46, 33, 20, 50, NULL, NULL, NULL, NULL),
(47, 34, 34, 2, NULL, NULL, NULL, NULL),
(48, 35, 12, 1, NULL, NULL, NULL, NULL),
(49, 36, 40, 2, NULL, NULL, NULL, NULL),
(50, 36, 3, 10, NULL, NULL, NULL, NULL),
(51, 36, 53, 10, NULL, NULL, NULL, NULL),
(52, 37, 19, 2, NULL, NULL, NULL, NULL),
(53, 37, 40, 1, NULL, NULL, NULL, NULL),
(54, 38, 20, 5, NULL, NULL, NULL, NULL),
(55, 38, 40, 10, NULL, NULL, NULL, NULL),
(56, 39, 11, 1, NULL, NULL, NULL, NULL),
(57, 39, 12, 1, NULL, NULL, NULL, NULL),
(58, 39, 19, 1, NULL, NULL, NULL, NULL),
(59, 39, 20, 1, NULL, NULL, NULL, NULL),
(60, 40, 40, 5, NULL, NULL, NULL, NULL),
(61, 40, 4, 5, NULL, NULL, NULL, NULL),
(62, 40, 14, 1, NULL, NULL, NULL, NULL),
(63, 41, 3, 23, NULL, NULL, NULL, NULL),
(64, 41, 15, 1, NULL, NULL, NULL, NULL),
(65, 41, 44, 1, NULL, NULL, NULL, NULL),
(66, 42, 3, 23, NULL, NULL, NULL, NULL),
(67, 42, 15, 1, NULL, NULL, NULL, NULL),
(68, 42, 44, 1, NULL, NULL, NULL, NULL),
(69, 43, 110, 2, NULL, NULL, NULL, NULL),
(70, 43, 111, 2, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shopping_lists`
--

CREATE TABLE `shopping_lists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `catalog_item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `is_wholesale` tinyint(1) DEFAULT 0,
  `resale_number` varchar(50) DEFAULT NULL,
  `certificate_url` varchar(255) DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `phone_number` varchar(20) DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `is_propane` tinyint(1) DEFAULT 0,
  `account_number` varchar(50) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `access_notes` text DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `suspended` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `company_id`, `username`, `first_name`, `last_name`, `email`, `password`, `is_admin`, `is_wholesale`, `resale_number`, `certificate_url`, `approval_status`, `phone_number`, `business_name`, `is_propane`, `account_number`, `delivery_address`, `access_notes`, `password_hash`, `suspended`) VALUES
(7, NULL, 'Rooster', NULL, NULL, 'trenchantman@gmail.com', '', 0, 0, NULL, NULL, 'approved', '9252861666', 'Rooster Propane', 1, '1666', '2044 Mt Diablo Walnut Creek', 'after 4pm', '$2y$10$bizL95wKYdmukL84S/9BKeuHllNRsoL2sorp42VPzdCW1qNFKiBCS', 0),
(9, NULL, 'admin', NULL, NULL, 'russellhb2b@gmail.com', '', 1, 0, NULL, NULL, 'approved', '925-286-5252', 'resupplyrocket', 0, NULL, '1195 Saranap Ave, Walnut Creek', NULL, '$2y$10$N/w72C4hCV8ZWmmon3SM7.5miPUR5KqFI8UIVvznAt65aO94ZF.jq', 0),
(11, 1, 'Russ Holstein', 'Russ', 'Holstein', 'rhoulston@aol.com', '', 0, 0, NULL, NULL, 'approved', '9252865251', 'Holstein Inc', 0, '1667', '1195 Saranap Ave. #14', '', '$2y$10$aHfRtLzGxGTyGfhiE2ET/e6FQauMcz94lJAwBUkNxWbQh6xaBwQ4K', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_catalog`
--

CREATE TABLE `user_catalog` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `catalog_item_id` int(11) NOT NULL,
  `custom_folder` varchar(255) DEFAULT NULL,
  `flatten` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_catalog`
--

INSERT INTO `user_catalog` (`id`, `user_id`, `catalog_item_id`, `custom_folder`, `flatten`) VALUES
(26, 7, 110, NULL, 0),
(27, 7, 111, NULL, 0),
(30, 11, 16, '', 0),
(31, 11, 15, '', 0),
(32, 11, 14, '', 0),
(33, 11, 11, '', 0),
(34, 11, 12, '', 0),
(35, 11, 53, '', 0),
(36, 11, 40, '', 0),
(37, 11, 48, '', 0),
(38, 11, 45, '', 0),
(39, 11, 36, '', 0),
(40, 11, 43, '', 0),
(41, 11, 35, '', 0),
(42, 11, 46, '', 0),
(43, 11, 109, '', 0),
(44, 11, 61, '', 0),
(45, 11, 44, '', 0),
(46, 11, 42, '', 0),
(47, 11, 47, '', 0),
(48, 11, 60, '', 0),
(49, 11, 34, '', 0),
(50, 11, 3, NULL, 0),
(51, 11, 4, NULL, 0),
(52, 11, 19, NULL, 0),
(53, 11, 20, NULL, 0),
(54, 11, 21, NULL, 0),
(55, 11, 66, NULL, 0),
(56, 11, 67, NULL, 0),
(57, 11, 68, NULL, 0),
(58, 11, 70, NULL, 0),
(59, 11, 76, NULL, 0),
(60, 11, 77, NULL, 0),
(61, 11, 81, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_folders`
--

CREATE TABLE `user_folders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `folder_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_folders`
--

INSERT INTO `user_folders` (`id`, `user_id`, `folder_name`, `created_at`) VALUES
(1, 11, 'Electrical', '2025-07-24 17:18:53'),
(3, 11, 'Conduit', '2025-07-24 19:06:18'),
(4, 11, 'Fasteners', '2025-07-29 18:10:46');

-- --------------------------------------------------------

--
-- Table structure for table `user_folder_items`
--

CREATE TABLE `user_folder_items` (
  `folder_id` int(11) NOT NULL,
  `catalog_item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_folder_items`
--

INSERT INTO `user_folder_items` (`folder_id`, `catalog_item_id`) VALUES
(1, 11),
(1, 12),
(1, 14),
(1, 15),
(1, 16),
(1, 53),
(3, 3),
(3, 4),
(3, 19),
(3, 20),
(3, 21),
(4, 66),
(4, 68),
(4, 70),
(4, 76),
(4, 77),
(4, 81);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `vendor_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `vendor_name`, `email`) VALUES
(1, 'Walnut Creek Hardware', 'orders@walnutcreek.com'),
(2, 'Propane Supplier', 'propane@supplier.com'),
(3, 'Walnut Creek Hardware', 'orders@walnutcreek.com'),
(4, 'Propane Supplier', 'propane@supplier.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `catalog_items`
--
ALTER TABLE `catalog_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `catalog_item_id` (`catalog_item_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `shopping_lists`
--
ALTER TABLE `shopping_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `catalog_item_id` (`catalog_item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `user_catalog`
--
ALTER TABLE `user_catalog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`catalog_item_id`),
  ADD KEY `catalog_item_id` (`catalog_item_id`);

--
-- Indexes for table `user_folders`
--
ALTER TABLE `user_folders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_folder` (`user_id`,`folder_name`);

--
-- Indexes for table `user_folder_items`
--
ALTER TABLE `user_folder_items`
  ADD PRIMARY KEY (`folder_id`,`catalog_item_id`),
  ADD KEY `catalog_item_id` (`catalog_item_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catalog_items`
--
ALTER TABLE `catalog_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shopping_lists`
--
ALTER TABLE `shopping_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_catalog`
--
ALTER TABLE `user_catalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `user_folders`
--
ALTER TABLE `user_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `catalog_items`
--
ALTER TABLE `catalog_items`
  ADD CONSTRAINT `catalog_items_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`catalog_item_id`) REFERENCES `catalog_items` (`id`);

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shopping_lists`
--
ALTER TABLE `shopping_lists`
  ADD CONSTRAINT `shopping_lists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shopping_lists_ibfk_2` FOREIGN KEY (`catalog_item_id`) REFERENCES `catalog_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `user_catalog`
--
ALTER TABLE `user_catalog`
  ADD CONSTRAINT `user_catalog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_catalog_ibfk_2` FOREIGN KEY (`catalog_item_id`) REFERENCES `catalog_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_folders`
--
ALTER TABLE `user_folders`
  ADD CONSTRAINT `user_folders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_folder_items`
--
ALTER TABLE `user_folder_items`
  ADD CONSTRAINT `user_folder_items_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `user_folders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_folder_items_ibfk_2` FOREIGN KEY (`catalog_item_id`) REFERENCES `catalog_items` (`id`) ON DELETE CASCADE;
--
-- Database: `resupply_test`
--
CREATE DATABASE IF NOT EXISTS `resupply_test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `resupply_test`;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `catalog_items`
--

CREATE TABLE `catalog_items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `order_multiple` int(11) NOT NULL DEFAULT 1,
  `vendor_id` int(11) DEFAULT NULL,
  `item_type` enum('general','paint','propane') DEFAULT 'general',
  `requires_quote` tinyint(1) DEFAULT 0,
  `category` varchar(255) DEFAULT 'Uncategorized'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `catalog_items`
--

INSERT INTO `catalog_items` (`id`, `item_name`, `description`, `sku`, `price`, `image_url`, `order_multiple`, `vendor_id`, `item_type`, `requires_quote`, `category`) VALUES
(3, '1 Inch EMT', '1\" Electrical metalic tubing in 10ft sticks', '30281', 0.00, 'product_images/30281.PNG', 1, NULL, 'general', 0, 'electrical'),
(4, '1 1/2 Inch IMC (Rigid)', '1 1/2\" Rigid galvanized electrical conduit (IMC) in 10 ft sticks', '30288', 0.00, 'product_images/30288.PNG', 1, NULL, 'general', 0, 'electrical'),
(5, '4 Square metal box', '2-1/8\" Deep electrical box. Metal with 1/2-3/4KO', '32962', 0.00, 'product_images/32962.PNG', 1, NULL, 'general', 0, 'electrical'),
(6, '4 Square metal blank', 'Metal 4 square box cover', '3016087', 0.00, 'product_images/3016087.PNG', 1, NULL, 'general', 0, 'electrical'),
(7, 'Metal Duplex Plate', 'Metal Duplex Single gang plate 4\"x2\"', '3016037', 0.00, 'product_images/3016037.PNG', 1, NULL, 'general', 0, 'electrical'),
(8, '4x4 Duplex plate', '4 Square Metal Box Cover with duplex opening', '30307', 0.00, 'product_images/30307.PNG', 1, NULL, 'general', 0, 'electrical'),
(9, 'Handibox', 'Single Gang Metal Box', '30337', 0.00, 'product_images/30337.PNG', 1, NULL, 'general', 0, 'electrical'),
(10, 'Cut in box', 'Plastic Old work cut in box', '30733', 0.00, 'product_images/30733.PNG', 1, NULL, 'general', 0, 'electrical'),
(11, 'Appliance cord', 'Light duty appliance cord extension', '31459', 0.00, 'product_images/31459.PNG', 1, NULL, 'general', 0, 'electrical'),
(12, 'Appliance cord', 'appliance cord 9 ft', '31471', 0.00, 'product_images/31471.PNG', 1, NULL, 'general', 0, 'electrical'),
(13, '#12 Black THHN', NULL, NULL, 0.00, '', 1, NULL, 'general', 0, NULL),
(14, '#12 White THHN', '#12 THHN stranded WHITE 500ft roll', '32354', 0.00, 'product_images/32354.PNG', 1, NULL, 'general', 0, 'electrical'),
(15, '#12 Red THHN', '#12 THHN stranded RED 500ft roll', '32357', 0.00, 'product_images/32357.PNG', 1, NULL, 'general', 0, 'electrical'),
(16, '#12 Green THHN', '#12 THHN Stranded GREEN 500ft roll', '34784', 0.00, 'product_images/34784.PNG', 1, NULL, 'general', 0, 'electrical'),
(17, '#12 Gray THHN', '#12 THHN stranded GRAY 500ft roll', 'IES001', 0.00, 'product_images/IES001.PNG', 1, NULL, 'general', 0, 'electrical'),
(18, 'Red Plenum Alarm cable', 'RED 18/2 SHIELD PLNM ALARM CBL/M 1000ft', 'GRB0002', 0.00, 'product_images/GRB0002.PNG', 1, NULL, 'general', 0, 'electrical'),
(19, '1/2\" sealtite straight connector', '1/2\" staight sealtite connector', '33050', 0.00, 'product_images/33050.PNG', 1, NULL, 'general', 0, 'electrical'),
(20, '1/2\" Sealtite 90 connector', '1/2\" Sealtite 90 degree connector', '33053', 0.00, 'product_images/33053.PNG', 1, NULL, 'general', 0, 'electrical'),
(21, '1/2\" Liquid tight flex', '1/2\"Sealtite flex conduit (metal) 100\'', '3296852', 0.00, 'product_images/3296852.PNG', 1, NULL, 'general', 0, 'electrical'),
(22, '3/4\" pvc conduit sched 40', '3/4\" PVC electrical conduit schedule 40 10ft', '33201', 0.00, 'product_images/33201.PNG', 1, NULL, 'general', 0, 'electrical'),
(23, '1\" PVC Female adapter', '1\" PVC electrical female adapter', '33214', 0.00, 'product_images/33214.PNG', 1, NULL, 'general', 0, 'electrical'),
(24, '1\" to 3/4\" PVC reducer', '1\" to 3/4\" adapter PVC electrical', '3006442', 0.00, 'product_images/3006442.PNG', 1, NULL, 'general', 0, 'electrical'),
(25, '1\" PVC Male adapter', '1\" PVC Male adapter electrical', '33220', 0.00, 'product_images/33220.PNG', 1, NULL, 'general', 0, 'electrical'),
(26, '2\" PVC Male adapter', '2\" PVC Male adapter electrical', '33223', 0.00, 'product_images/33223.PNG', 1, NULL, 'general', 0, 'electrical'),
(27, '3\" PVC Male adapter', '3\" PVC Male adapter electrical', '33583', 0.00, 'product_images/33583.PNG', 1, NULL, 'general', 0, 'electrical'),
(28, '1\" Lock nut', '1\" Metal lock nut', '3182433', 0.00, 'product_images/3182433.PNG', 1, NULL, 'general', 0, 'electrical'),
(29, '2\" Lock nut', '2\" Metal locknut', '3182540', 0.00, 'product_images/3182540.PNG', 1, NULL, 'general', 0, 'electrical'),
(30, '1/2\" Lock nut', '1/2\" Metal locknut', '3182417', 0.00, 'product_images/3182417.PNG', 3, NULL, 'general', 0, 'electrical'),
(31, '3\" Lock nut', '3\" LOCKNUT', '3101367', 0.00, 'product_images/3101367.PNG', 1, NULL, 'general', 0, 'electrical'),
(32, 'PVC 2 gang Box', 'PVC 2 Gang box 1 x 3/4\" opening', '3020856', 0.00, 'product_images/3020856.PNG', 1, NULL, 'general', 0, 'electrical'),
(33, '7 Hole plastic Box', 'WP outlet box 7x1/2\" holes', '3291374', 0.00, 'product_images/3291374.PNG', 1, NULL, 'general', 0, 'electrical'),
(34, 'Small cable ties 4\"', '4\" zip ties (PACK 100ea.)', '3004711', 0.00, 'product_images/3004711.PNG', 1, NULL, 'general', 0, 'hardware'),
(35, 'Grounding pig tail', 'grounding pig tails  (pack of 2)', '3013588', 0.00, 'product_images/3013588.PNG', 1, NULL, 'general', 0, 'electrical'),
(36, 'Conduit grounding clamp 2\"', '2\" Gounding clamp for conduits', '3172749', 0.00, 'product_images/3172749.PNG', 1, NULL, 'general', 0, 'electrical'),
(37, '1\" Weatherproof coupling', '1\" WP Compression coupling for metal conduit', '3179959', 0.00, 'product_images/3179959.PNG', 1, NULL, 'general', 0, 'electrical'),
(38, '3/8 1 hole straps', '3/8\" One hols straps (pack of 3)', '3180494', 0.00, 'product_images/3180494.PNG', 1, NULL, 'general', 0, 'fasteners'),
(39, '1/2 Rigid 1 hole strap', '1/2\" Rigid 1 hole strap (pack of 3)', '3181153', 0.00, 'product_images/3181153.PNG', 1, NULL, 'general', 0, 'electrical'),
(40, 'Black electrical tape', 'Scotch 3/4\" electrical tape Black', '33305', 0.00, 'product_images/33305.PNG', 1, NULL, 'general', 0, 'electrical'),
(41, 'Red electrical tape', '3M Red electrical tape', '3309929', 0.00, 'product_images/3309929.PNG', 1, NULL, 'general', 0, 'electrical'),
(42, 'White electrical tape', '3M White electrical tape', '3309937', 0.00, 'product_images/3309937.PNG', 1, NULL, 'general', 0, 'electrical'),
(43, 'Green electrical tape', '3M Green electrical tape', '3311263', 0.00, 'product_images/3311263.PNG', 1, NULL, 'general', 0, 'electrical'),
(44, 'Violet electrical tape', '3M Violet electrical tape', '3311461', 0.00, 'product_images/3311461.PNG', 1, NULL, 'general', 0, 'electrical'),
(45, 'Brown electrical tape', '3M Brown electrical tape', '3311610', 0.00, 'product_images/3311610.PNG', 1, NULL, 'general', 0, 'electrical'),
(46, 'Orange electrical tape', '3M Orange electrical tape', '3311644', 0.00, 'product_images/3311644.PNG', 1, NULL, 'general', 0, 'electrical'),
(47, 'Yellow electrical tape', '3M Yellow electrical tape', '3311719', 0.00, 'product_images/3311719.PNG', 1, NULL, 'general', 0, 'electrical'),
(48, 'Blue electrical tape', '3M Blue electrical tape', '3312303', 0.00, 'product_images/3312303.PNG', 1, NULL, 'general', 0, 'electrical'),
(49, 'Unistrut 10 ft Galvanized', '1 5/8x10ft Unistrut galvanized', '3227568', 0.00, 'product_images/3227568.PNG', 1, NULL, 'general', 0, 'electrical'),
(50, 'Unistrut pipe straps 1 1/2\"', '1 1/2\" Strut strap for IMC', '3407475', 0.00, 'product_images/3407475.PNG', 1, NULL, 'general', 0, 'electrical'),
(51, '1/2\" cone nuts', '1/2\" CONE NUT PACK OF 5** DISCONTINUED', '3407632', 0.00, 'product_images/3407632.PNG', 1, NULL, 'general', 0, 'electrical'),
(52, '3/8\" cone nuts', '3/8\" Cone nuts (pack of 5)', '3017978', 0.00, 'product_images/3017978.PNG', 1, NULL, 'general', 0, 'fasteners'),
(53, 'Backwire 20 amp receptacle', '20 AMP Duplex receptacle backwire', '3500543', 0.00, 'product_images/3500543.PNG', 1, NULL, 'general', 0, 'electrical'),
(54, 'Screw anchor kit', 'Plastic screw anchor kit', '5326038', 0.00, 'product_images/5326038.PNG', 1, NULL, 'general', 0, 'fasteners'),
(55, 'Pack of 24  Ace water', 'Ace water. Pallets are 84 packs', '9602780', 0.00, 'product_images/9602780.PNG', 1, NULL, 'general', 0, 'Uncategorized'),
(57, 'Caster with lock 900# rating', 'RED DURAPLY CASTER WITH LOCK 900#', 'DH003', 0.00, 'product_images/DH003.PNG', 1, NULL, 'general', 0, 'hardware'),
(58, 'Nylon cord connector', 'CABLE GLAND (CORD GRIP)', 'DSC001', 0.00, 'product_images/DSC001.PNG', 50, NULL, 'general', 0, 'hardware'),
(59, 'Butterfly fitting', '#14 BUTTERFLY', 'ELD001', 0.00, 'product_images/ELD001.PNG', 500, NULL, 'general', 0, 'hardware'),
(60, 'Large cable ties', '11\" Black zip ties (pack of 100)', '3004688', 0.00, 'product_images/3004688.PNG', 1, NULL, 'general', 0, 'hardware'),
(61, 'Red Ranger Wirenuts', '3M Red Ranger wire nuts', 'IES002', 0.00, 'product_images/IES002.PNG', 1, NULL, 'general', 0, 'electrical'),
(62, '1 1/4\" sheetrock screws', '1-1/4\" SHEETROCK SCREWS', 'JM0011', 0.00, 'product_images/JM0011.PNG', 1, NULL, 'general', 0, 'fasteners'),
(63, '2 1/2\" self tapping screws', '2-1/2\" SELF TAPPING SCREWS', 'JM0012', 0.00, 'product_images/JM0012.PNG', 1, NULL, 'general', 0, 'fasteners'),
(64, '1 3/4\" Hex head tapcon', '1-3/4\" HEX HEAD TAPCON', 'JM0013', 0.00, 'product_images/JM0013.PNG', 1, NULL, 'general', 0, 'fasteners'),
(65, '3/4\" self tap self seal screws', '3/4\" SELF TAPPING SELF SEALING SCREWS', 'JM0014', 0.00, 'product_images/JM0014.PNG', 1, NULL, 'general', 0, 'fasteners'),
(66, '1/2\"x 4 1/4 Wedge anchor', '1/2\" X 4 1/4 WEDGE ANCHORS', 'JM0015', 0.00, 'product_images/JM0015.PNG', 1, NULL, 'general', 0, 'fasteners'),
(67, '1/4 x 1 1/4\" SS Hex Bolt', '1/4\" X 1-1/4\" STAINLESS BOLT', 'JM0003', 0.00, 'product_images/JM0003.PNG', 1, NULL, 'general', 0, 'fasteners'),
(68, '1/4 SS Washer', '1/4\" STAINLESS WASHERS', 'JM0004', 0.00, 'product_images/JM0004.PNG', 1, NULL, 'general', 0, 'fasteners'),
(69, '1/4\" SS lock washer', '1/4\" STAINLESS LOCK WASHERS', 'JM0005', 0.00, 'product_images/JM0005.PNG', 1, NULL, 'general', 0, 'fasteners'),
(70, '1/4 SS Fender washer', '1/4\" STAINLESS FENDER WASHERS', 'JM0006', 0.00, 'product_images/JM0006.PNG', 1, NULL, 'general', 0, 'fasteners'),
(71, '3/8\" x 1 1/4\" SS Hex bolt', '3/8\" X 1-1/4\" STAINLESS BOLT', 'JM0007', 0.00, 'product_images/JM0007.PNG', 1, NULL, 'general', 0, 'fasteners'),
(72, '3/8\" SS washer', '3/8\" STAINLESS FLAT WASHERS', 'JM0008', 0.00, 'product_images/JM0008.PNG', 1, NULL, 'general', 0, 'fasteners'),
(73, '3/8\" SS lock washer', '3/8\" STAINLESS LOCK WASHERS', 'JM0009', 0.00, 'product_images/JM0009.PNG', 1, NULL, 'general', 0, 'fasteners'),
(74, '3/8\" SS Fender washer', '3/8\" STAINLESS FENDER WASHERS', 'JM0010', 0.00, 'product_images/JM0010.PNG', 1, NULL, 'general', 0, 'fasteners'),
(75, '3/8\" x 1\" SS Hex bolt', '3/8\" x 1\" Stainless steel hex bolt', 'JM0016', 0.00, 'product_images/JM0016.PNG', 1, NULL, 'general', 0, 'fasteners'),
(76, '1/2\" x 1 Hex bolt', '1/2\" X 1\" HEX BOLT', 'JM0020', 0.00, 'product_images/JM0020.PNG', 1, NULL, 'general', 0, 'fasteners'),
(77, '1/2\" washer', '1/2\" FLAT WASHER', 'JM0021', 0.00, 'product_images/JM0021.PNG', 1, NULL, 'general', 0, 'fasteners'),
(78, '3/8 Lag', '3/8\" LAG BOLT', 'JM0022', 0.00, 'product_images/JM0022.PNG', 1, NULL, 'general', 0, 'fasteners'),
(79, '3/8 washer', '3/8\" FLAT WASHER', 'JM0023', 0.00, 'product_images/JM0023.PNG', 1, NULL, 'general', 0, 'fasteners'),
(80, '3/8\" SS Flange nut', '3/8\" SS SERRATED FLANGE NUT', 'JM0017', 0.00, 'product_images/JM0017.PNG', 1, NULL, 'general', 0, 'fasteners'),
(81, '1/2\" SS Flange nut', '1/2\" SS SERRATED FLANGE NUT', 'JM0018', 0.00, 'product_images/JM0018.PNG', 1, NULL, 'general', 0, 'fasteners'),
(82, '8/32 SS flange nut', '8/32 SS SERRATED FLANGE NUT', 'JM0019', 0.00, 'product_images/JM0019.PNG', 1, NULL, 'general', 0, 'fasteners'),
(83, '1/4\" SS flange nut', '1/4\" SERRATED FLANGE NUT', 'JM0024', 0.00, 'product_images/JM0024.PNG', 1, NULL, 'general', 0, 'fasteners'),
(84, '3/8 SS hex nut', '3/8\" STAINLESS HEX NUT', 'JM0025', 0.00, 'product_images/JM0025.PNG', 1, NULL, 'general', 0, 'fasteners'),
(85, 'Plastic 8 thru 12 anchor', 'Plastic anchor for #8 thru #12 screws', 'JM0026', 0.00, 'product_images/JM0026.PNG', 1, NULL, 'general', 0, 'fasteners'),
(86, '#8 x 1 1/4\" pan head tapper', '#8 1-1/4\" PAN HEAD TAPPING SCS \"A\" STL ZP', 'JM0027', 0.00, 'product_images/JM0027.PNG', 1, NULL, 'general', 0, 'fasteners'),
(87, '1/4\" SS hex nut', '1/4\" STAINLESS HEX NUT', 'JM0028', 0.00, 'product_images/JM0028.PNG', 1, NULL, 'general', 0, 'fasteners'),
(88, '#8 auger anchor', '#8 AUGER (WALLBOARD) ANCHOR', 'JM0029', 0.00, 'product_images/JM0029.PNG', 1, NULL, 'general', 0, 'fasteners'),
(89, 'Philips wall dog', '1/4X1-1/4 PHL PAN BULK WALLDOG', 'JM0030', 0.00, 'product_images/JM0030.PNG', 1, NULL, 'general', 0, 'fasteners'),
(90, '3/8\"-16 10ft threaded rod', '3/8-16 THREADED ROD ZINC PLATED 10 FT', 'JM0031', 0.00, 'product_images/JM0031.PNG', 1, NULL, 'general', 0, 'hardware'),
(91, '3/8-16 x1 SS Hex bolt', '3/8-16 X1\" STAINLESS HEX BOLT', 'JM0035', 0.00, 'product_images/JM0035.PNG', 1, NULL, 'general', 0, 'fasteners'),
(92, '3/8-16 x1\" SS flange nut', '3/8-16 X1\" STAINLESS HEX FLANGE NUT', 'JM0036', 0.00, 'product_images/JM0036.PNG', 1, NULL, 'general', 0, 'fasteners'),
(93, '3/8\" x 1\" SS lag', '3/8 X1\" STAINLESS HEX HEAD LAG', 'JM0037', 0.00, 'product_images/JM0037.PNG', 1, NULL, 'general', 0, 'fasteners'),
(94, '5/16-18 x1 SS Hex bolt', '5/16-18 X1\" STAINLESS HEX BOLT', 'JM0038', 0.00, 'product_images/JM0038.PNG', 1, NULL, 'general', 0, 'fasteners'),
(95, '5/16\" SS Lockwasher', '5/16 SPLIT LOCK WASHER STAINLESS', 'JM0039', 0.00, 'product_images/JM0039.PNG', 1, NULL, 'general', 0, 'fasteners'),
(96, '5/16-8 SS Hex nut', '5/16-18 HEX FINISH NUT STAINLESS', 'JM0040', 0.00, 'product_images/JM0040.PNG', 1, NULL, 'general', 0, 'fasteners'),
(97, '#8 x 1\" Philips wood screw', '#8 X 1 PHL ROUND WOOD SCREW SS', 'JM0041', 0.00, 'product_images/JM0041.PNG', 1, NULL, 'general', 0, 'fasteners'),
(98, '1/4\" x 1\" SS Hex lag', '1/4\"X1 HEX HD LAG STAINLESS STEE', 'JM0052', 0.00, 'product_images/JM0052.PNG', 1, NULL, 'general', 0, 'fasteners'),
(99, '#8 x 1/2\" washer head', '8-18 x 1/2 Hex washer head self drilling', 'JM0053', 0.00, 'product_images/JM0053.PNG', 1, NULL, 'general', 0, 'fasteners'),
(100, '1/4-20 x 3/4 SiBrz Hex bolt', '1/4-20 X 3/4 HEX HD CAP SCS Silicone Bronze', 'JM0032', 0.00, 'product_images/JM0032.PNG', 1, NULL, 'general', 0, 'fasteners'),
(101, '1/4\" Hex nut SiBrz', '1/4 HEX NUT Silicone Bronze', 'JM0033', 0.00, 'product_images/JM0033.PNG', 1, NULL, 'general', 0, 'fasteners'),
(102, '1/4\" lock washer SiBrz', '1/4 SPLIT LOCK WASHER Silicone Bronze', 'JM0034', 0.00, 'product_images/JM0034.PNG', 1, NULL, 'general', 0, 'fasteners'),
(103, '3/8\" x 3/4\" Hex bolt SiBrz', '3/8 X 3/4 HEX HD CAP SCS Silicone Bronze', 'JM0105', 0.00, 'product_images/JM0105.PNG', 1, NULL, 'general', 0, 'fasteners'),
(104, '3/8 lock washer SiBrz', '3/8 SPLIT LOCK WASHER Silicone Bronze', 'JM0106', 0.00, 'product_images/JM0106.PNG', 1, NULL, 'general', 0, 'fasteners'),
(105, '3/8\" Hex nut SiBrz', '3/8 HEX NUT Silicone Bronze', 'JM0107', 0.00, 'product_images/JM0107.PNG', 1, NULL, 'general', 0, 'fasteners'),
(107, 'Butyl Tape', '3\" BLACK BUTYL TAPE X24FT', '5482732', 0.00, 'product_images/5482732.PNG', 1, NULL, 'general', 0, 'hardware'),
(108, 'Cinder block', 'CINDERBLOCK', 'QR099852', 0.00, 'product_images/QR099852.PNG', 1, NULL, 'general', 0, 'building materials'),
(109, 'Plug', 'Hubbel 520SP', 'ROY0001', 0.00, 'product_images/ROY0001.PNG', 1, NULL, 'general', 0, 'electrical'),
(110, 'Propane Exchange  (B2B)', 'Fresh propane tank exchanged for B2B customer\'s empty propane tank (any brand). For customers with a B2B account and a valid credit card on file.', 'PROPANEB2BEXCHANGEDELIVERED', 23.99, 'product_images/PROPANEB2BEXCHANGEDELIVERED.PNG', 1, NULL, 'propane', 0, 'patio'),
(111, 'Propane Spare (B2B)', 'New full propane tank where there is no empty tank to exchange for it. Delivered to B2B customers with an account and a valid credit card on file\r', 'PROPANEB2BSPARE', 67.99, 'product_images/PROPANEB2BSPARE.PNG', 1, NULL, 'propane', 0, 'patio'),
(112, 'Nikon Z8', 'Mirrorless SLR', 'NIKONZ8', 2999.00, 'product_images/NIKONZ8.PNG', 1, NULL, 'general', 0, 'Photography'),
(113, 'Swivel caster', 'Red Duraply 900# rated caster', 'DH001', 0.00, 'product_images/DH001.PNG', 1, NULL, 'general', 0, 'hardware'),
(115, '2G Plastic pail', 'ACE white plastic pail 2 gallon', '1218346', 0.00, 'product_images/1218346.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(116, 'Scotch contractor grade masking tape', 'Scotch Contractor Grade 1.41 in. W X 60.1 yd L Beige Medium Strength Masking Tape 1 pk\r', '1669910', 0.00, 'product_images/1669910.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(117, '5G Plastic Bucket', 'ACE white plastic 5 gallon bucket', '1147461', 0.00, 'product_images/1147461.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(118, 'Masking paper', 'Trimaco 12 in. W X 180 ft. L Paper Masking Paper 1 pk\r', '17883', 0.00, 'product_images/17883.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(119, '1 1/4\" Roller', 'Purdy Golden Eagle Polyester 9 in. W X 1-1/4 in. Regular Paint Roller Cover 1 pk\r', '1329424', 0.00, 'product_images/1329424.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(120, 'Jumbo mini rollers', 'Purdy White Dove Woven Fabric 6.5 in. W X 3/8 in. Jumbo Mini Paint Roller Cover 2 pk', '1495126', 0.00, 'product_images/1495126.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(121, '6 pk Blue masking tape', 'ScotchBlue 1.41 in. W X 60 yd L Blue Medium Strength Original Painter\'s Tape 6 pk\r', '1564772', 0.00, 'product_images/1564772.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(122, '3 Pk Blue masking tape 1.41\"x 60 yds', 'ScotchBlue 1.41 in. W X 60 yd L Blue Medium Strength Original Painter\'s Tape 3 pk\r', '1666460', 0.00, 'product_images/1666460.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(123, 'XL paint brush', 'Purdy XL Sprig 2-1/2 in. Medium Stiff Flat Trim Paint Brush', '1800291', 0.00, 'product_images/1800291.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(124, 'Spackle', 'DAP DryDex Ready to Use White Spackling Compound 0.5 pt', '1149103', 0.00, 'product_images/1149103.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(125, '3\" paintbrush', 'Linzer Home Decor 3 in. Flat Paint Brush\r', '1060094', 0.00, 'product_images/1060094.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(127, 'Rollers 2 pk', 'Purdy White Dove Woven Fabric 4.5 in. W X 3/8 in. Jumbo Mini Paint Roller Cover 2 pk\r', '1495035', 0.00, 'product_images/1495035.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(129, 'Gray duct tape', 'Ace 1.88 in. W X 60 yd L Gray Duct Tape', '40402', 0.00, 'product_images/40402.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(130, 'Paint brush cover', 'Likwid Concepts 3 in. W X 9.25 in. L Clear Plastic Paint Brush Cover', '9301862', 0.00, 'product_images/9301862.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(131, '9\"x1/2\" roller', 'Purdy Golden Eagle Polyester 9 in. W X 1/2 in. Regular Paint Roller Cover 1 pk\r', '1197326', 0.00, 'product_images/1197326.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(132, 'Caulk', 'DAP Alex White Acrylic Latex Painter\'s Caulk 10.1 oz', '11438', 0.00, 'product_images/11438.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(133, 'Paint pad', 'Ace 9 in. W Paint Pad For Rough Surfaces\r', '1028695', 0.00, 'product_images/1028695.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(134, 'Floor protect paper', 'Trimaco X-Paper Floor Protector Paper 36 in. W X 120 ft. L Paper Brown 1 pk\r', '1018530', 0.00, 'product_images/1018530.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(135, 'Painters plastic', 'Film-Gard 12 ft. W x 400 ft. L x 0.35 mil Professional Grade Painter\'s Plastic 1 pk\r', '1108224', 0.00, 'product_images/1108224.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(136, '3/4\" roller', 'Purdy Golden Eagle Polyester 9 in. W X 3/4 in. Regular Paint Roller Cover 1 pk\r', '1197367', 0.00, 'product_images/1197367.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(137, 'Caulk Alex Plus', 'DAP Alex Plus White Acrylic Latex All Purpose Caulk 10.1 oz\r', '12044', 0.00, 'product_images/12044.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(138, '1/2\" Roller white dove', 'Purdy White Dove Woven Fabric 9 in. W X 1/2 in. Paint Roller Cover 1 pk\r', '19906', 0.00, 'product_images/19906.PNG', 1, NULL, 'general', 0, 'paint supplies'),
(139, 'Zinsser Bullseye 123', 'Zinsser Bulls Eye 123 White Water-Based Styrenated Acrylic Primer and Sealer 1 gal\r', '16890', 0.00, 'product_images/16890.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(140, 'Kilz All Purpose Primer', 'KILZ White Flat Water-Based Acrylic Stain Blocking Primer 1 gal', '17937', 0.00, 'product_images/17937.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(141, 'Benjamin Moore Fresh Start', 'Benjamin Moore Fresh Start White Low Luster Acrylic Latex Primer 1 gal', '1411511', 0.00, 'product_images/1411511.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(142, 'Kilz 3 Premium White Blocking Primer', 'KILZ 3 Premium White Flat Water-Based Stain Blocking Primer 1 gal', '1000330', 0.00, 'product_images/1000330.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(143, 'Benjamin Moore Aura Paint and Primer', 'Benjamin Moore Aura Eggshell Base 1 Paint and Primer Interior 1 gal', '1018875', 0.00, 'product_images/1018875.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(144, 'C&K Eggshell Paint + Primer', 'Clark+Kensington Eggshell Tint Base Ultra White Base Paint + Primer Interior 1 gal', '1020463', 0.00, 'product_images/1020463.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(145, 'Kilz 2 5 Gallon', 'KILZ White Flat Water-Based Acrylic Stain Blocking Primer 5 gal', '19793', 0.00, 'product_images/19793.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(146, 'C&K Satin Paint + Primer 1 Gallon', 'Clark+Kensington Satin Tint Base Ultra White Base Paint + Primer Interior 1 gal', '1020533', 0.00, 'product_images/1020533.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(147, 'Zinsser BIN Shellac Primer', 'Zinsser B-I-N White Shellac-Based Primer and Sealer 1 gal', '11325', 0.00, 'product_images/11325.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(148, 'Zinsser Bulls-eye 123 primer and sealer', 'Zinsser Bulls-Eye 1-2-3 White Water-Based Styrenated Acrylic Primer and Sealer 1 qt', '16889', 0.00, 'product_images/16889.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(149, 'Zinsser Bulls eye 123 primer 5 gallon', 'Zinsser Bulls Eye 123 White Primer and Sealer 5 gal', '1085315', 0.00, 'product_images/1085315.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(150, 'Benjamin Moore Insl-X Urethane Bonding Primer', 'Insl-X Stix White Flat Water-Based Acrylic Urethane Bonding Primer 1 gal', '1422039', 0.00, 'product_images/1422039.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(151, 'Zinsser Mold Killing Primer 1 Gallon', 'Zinsser White Water-Based Acrylic Mold Killing Primer 1 gal', '1534122', 0.00, 'product_images/1534122.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(152, 'Benjamin Moore Regal Select Paint and Primer', 'Benjamin Moore Regal Select Flat Base 1 Paint and Primer Interior 1 gal', '1016780', 0.00, 'product_images/1016780.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(153, 'Benjamin Moore Satin/Pearl Paint and Primer', 'Benjamin Moore Ben Satin/Pearl Base 1 Paint and Primer Interior 1 gal', '1020582', 0.00, 'product_images/1020582.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(154, 'Kilz 2 Stain Blocking Primer', 'KILZ White Flat Water-Based Acrylic Stain Blocking Primer 1 qt', '17936', 0.00, 'product_images/17936.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(155, 'Benjamin Moore Eggshell Paint and Primer', 'Benjamin Moore Ben Eggshell Base 1 Paint and Primer Interior 1 gal', '1020315', 0.00, 'product_images/1020315.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(156, 'Kilz Oil Based Aerosol Primer/Sealer', 'KILZ Original White Flat Oil-Based Aerosol Primer/Sealer 13 oz', '12577', 0.00, 'product_images/12577.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(157, 'Zinsser Smart Prime', 'Zinsser Smart Prime White Smooth Water-Based Acrylic Primer 1 gal', '1896463', 0.00, 'product_images/1896463.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(158, 'Zinsser Bulls Eye 123 Gray', 'Zinsser Bulls Eye 123 Gray Water-Based Acrylic Copolymer Primer 1 gal', '1565712', 0.00, 'product_images/1565712.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(159, 'Ben Eggshell Paint and Primer', 'Benjamin Moore Ben Eggshell Base 1 Paint and Primer Interior 1 qt', '1020323', 0.00, 'product_images/1020323.PNG', 1, NULL, 'general', 0, 'Paint + Primer'),
(160, 'Zinsser Mold Killing Primer', 'Zinsser White Water-Based Acrylic Mold Killing Primer 1 qt', '1534114', 0.00, 'product_images/1534114.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(161, 'C&K Eggshell Paint+Primer', 'Clark+Kensington Eggshell Tint Base Mid-Tone Base Paint + Primer Interior 1 gal', '1020466', 0.00, 'product_images/1020466.PNG', 1, NULL, 'paint', 0, 'liquid paint'),
(162, 'C&K Neutral Paint + Primer', 'Clark+Kensington Eggshell Tint Base Neutral Base Paint + Primer Interior 1 gal', '1020472', 0.00, 'product_images/1020472.PNG', 1, NULL, 'paint', 0, 'liquid paint');

-- --------------------------------------------------------

--
-- Table structure for table `checkbox_lists`
--

CREATE TABLE `checkbox_lists` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `impersonation_logs`
--

CREATE TABLE `impersonation_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `type` enum('dismissable','persistent') NOT NULL DEFAULT 'dismissable',
  `is_dismissable` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'draft',
  `fulfillment_type` varchar(50) DEFAULT NULL,
  `po_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `internal_notes` text DEFAULT NULL,
  `delivery_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `catalog_item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `paint_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`paint_details`)),
  `quote_status` varchar(50) DEFAULT NULL,
  `quoted_price` decimal(10,2) DEFAULT NULL,
  `quote_notes` text DEFAULT NULL,
  `paint_size` enum('5 Gallon','1 Gallon','Quart','Sample') DEFAULT NULL,
  `paint_type` enum('Interior','Exterior') DEFAULT NULL,
  `paint_sheen` enum('Flat','Matte','Eggshell','Pearl','Satin','Soft Gloss','Semi Gloss','Hi Gloss') DEFAULT NULL,
  `paint_brand` enum('Ben','Regal','Aura','Element Guard','Ceiling','Advance','C&K','Contractor Pro') DEFAULT NULL,
  `paint_color` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `mailing_address` text DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `type` enum('retail','wholesale') DEFAULT 'retail',
  `resale_number` varchar(50) DEFAULT NULL,
  `authorized_people` text DEFAULT NULL,
  `credit_card_contact` tinyint(1) DEFAULT 0,
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `suspended` tinyint(1) DEFAULT 0,
  `wholesale` tinyint(1) DEFAULT 0,
  `is_propane` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_item_overrides`
--

CREATE TABLE `organization_item_overrides` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `catalog_item_id` int(11) NOT NULL,
  `custom_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`, `used`, `created_at`) VALUES
(10, 30, '897bccfd23dea715ea815f63b21fe370311f002719cbbf9ad97efe8992d87380', '2026-03-11 07:04:03', 1, '2026-03-11 06:04:03');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_lists`
--

CREATE TABLE `shopping_lists` (
  `id` int(11) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shopping_list_items`
--

CREATE TABLE `shopping_list_items` (
  `list_id` int(11) NOT NULL,
  `catalog_item_id` int(11) NOT NULL,
  `custom_price` decimal(10,2) DEFAULT NULL,
  `item_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `organization_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `is_wholesale` tinyint(1) DEFAULT 0,
  `resale_number` varchar(50) DEFAULT NULL,
  `certificate_url` varchar(255) DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `registration_type` enum('new_company','join_company','pre_reg') DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `is_propane` tinyint(1) DEFAULT 0,
  `account_number` varchar(50) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `access_notes` text DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `suspended` tinyint(1) DEFAULT 0,
  `resale_certificate_url` varchar(255) DEFAULT NULL,
  `is_organization_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `organization_id`, `username`, `first_name`, `last_name`, `email`, `password`, `is_admin`, `is_wholesale`, `resale_number`, `certificate_url`, `approval_status`, `registration_type`, `phone_number`, `business_name`, `is_propane`, `account_number`, `delivery_address`, `access_notes`, `password_hash`, `suspended`, `resale_certificate_url`, `is_organization_admin`) VALUES
(30, NULL, 'Russell Admin', 'Russell', 'Houlston', 'russellhb2b@gmail.com', '4809d4c3becd777da963e06f1d5f2a74', 1, 0, '', NULL, 'approved', NULL, NULL, NULL, 0, NULL, NULL, NULL, '$2y$10$5p5e7B3K0R385mOe.5so.e4T1zujauivgc76Pdrj5O4hooRIHUFAe', 0, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_folders`
--

CREATE TABLE `user_folders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `folder_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_folder_items`
--

CREATE TABLE `user_folder_items` (
  `folder_id` int(11) NOT NULL,
  `catalog_item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_messages`
--

CREATE TABLE `user_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `dismissed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `vendor_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `catalog_items`
--
ALTER TABLE `catalog_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `checkbox_lists`
--
ALTER TABLE `checkbox_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `company_id` (`organization_id`);

--
-- Indexes for table `impersonation_logs`
--
ALTER TABLE `impersonation_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `catalog_item_id` (`catalog_item_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_item_overrides`
--
ALTER TABLE `organization_item_overrides`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `company_id` (`company_id`,`catalog_item_id`),
  ADD KEY `catalog_item_id` (`catalog_item_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `shopping_lists`
--
ALTER TABLE `shopping_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_shopping_lists_organization` (`organization_id`);

--
-- Indexes for table `shopping_list_items`
--
ALTER TABLE `shopping_list_items`
  ADD PRIMARY KEY (`list_id`,`catalog_item_id`),
  ADD KEY `catalog_item_id` (`catalog_item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `company_id` (`organization_id`);

--
-- Indexes for table `user_folders`
--
ALTER TABLE `user_folders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_folder` (`user_id`,`folder_name`);

--
-- Indexes for table `user_folder_items`
--
ALTER TABLE `user_folder_items`
  ADD PRIMARY KEY (`folder_id`,`catalog_item_id`),
  ADD KEY `catalog_item_id` (`catalog_item_id`);

--
-- Indexes for table `user_messages`
--
ALTER TABLE `user_messages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`message_id`),
  ADD KEY `message_id` (`message_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `catalog_items`
--
ALTER TABLE `catalog_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `checkbox_lists`
--
ALTER TABLE `checkbox_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `impersonation_logs`
--
ALTER TABLE `impersonation_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=273;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1951;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `organization_item_overrides`
--
ALTER TABLE `organization_item_overrides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `shopping_lists`
--
ALTER TABLE `shopping_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `user_folders`
--
ALTER TABLE `user_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_messages`
--
ALTER TABLE `user_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `catalog_items`
--
ALTER TABLE `catalog_items`
  ADD CONSTRAINT `catalog_items_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `checkbox_lists`
--
ALTER TABLE `checkbox_lists`
  ADD CONSTRAINT `fk_checkbox_lists_organization` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `impersonation_logs`
--
ALTER TABLE `impersonation_logs`
  ADD CONSTRAINT `impersonation_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `impersonation_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`catalog_item_id`) REFERENCES `catalog_items` (`id`);

--
-- Constraints for table `organization_item_overrides`
--
ALTER TABLE `organization_item_overrides`
  ADD CONSTRAINT `organization_item_overrides_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `organization_item_overrides_ibfk_2` FOREIGN KEY (`catalog_item_id`) REFERENCES `catalog_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shopping_lists`
--
ALTER TABLE `shopping_lists`
  ADD CONSTRAINT `fk_shopping_lists_organization` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shopping_list_items`
--
ALTER TABLE `shopping_list_items`
  ADD CONSTRAINT `shopping_list_items_ibfk_1` FOREIGN KEY (`list_id`) REFERENCES `shopping_lists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shopping_list_items_ibfk_2` FOREIGN KEY (`catalog_item_id`) REFERENCES `catalog_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_organization` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_folders`
--
ALTER TABLE `user_folders`
  ADD CONSTRAINT `user_folders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_folder_items`
--
ALTER TABLE `user_folder_items`
  ADD CONSTRAINT `user_folder_items_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `user_folders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_folder_items_ibfk_2` FOREIGN KEY (`catalog_item_id`) REFERENCES `catalog_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_messages`
--
ALTER TABLE `user_messages`
  ADD CONSTRAINT `user_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_messages_ibfk_2` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
