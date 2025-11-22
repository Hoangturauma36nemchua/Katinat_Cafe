-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 22, 2025 lúc 05:57 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `katinat_coffe_shop`
--

DELIMITER $$
--
-- Thủ tục
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `use_discount` (IN `input_code` VARCHAR(50))   BEGIN
    UPDATE discounts
    SET quantity = quantity - 1,
        used = used + 1
    WHERE code = input_code AND quantity > 0;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `activity_log`
--

CREATE TABLE `activity_log` (
  `LogID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Role` varchar(50) NOT NULL,
  `Date` date NOT NULL DEFAULT curdate(),
  `TimeIn` datetime DEFAULT NULL,
  `TimeOut` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `activity_log`
--

INSERT INTO `activity_log` (`LogID`, `Username`, `Role`, `Date`, `TimeIn`, `TimeOut`) VALUES
(1, 'vinh', 'Nhân viên', '2025-11-22', '2025-11-22 11:07:07', '2025-11-22 11:28:29'),
(2, 'admin', 'Admin', '2025-11-22', '2025-11-22 11:29:08', NULL),
(4, 'tuanngu', 'Nhân viên', '2025-11-22', '2025-11-22 11:50:43', '2025-11-22 11:50:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `discounts`
--

CREATE TABLE `discounts` (
  `DiscountID` int(11) NOT NULL,
  `DiscountName` varchar(50) NOT NULL,
  `DiscountPercent` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 0,
  `Used` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `discounts`
--

INSERT INTO `discounts` (`DiscountID`, `DiscountName`, `DiscountPercent`, `Quantity`, `Used`) VALUES
(1, 'Ma1', 10, 45, 5),
(5, 'Ma2', 20, 3, 2),
(6, 'Ma3', 100, 10, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menu`
--

CREATE TABLE `menu` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Price` int(11) NOT NULL DEFAULT 0,
  `Size` varchar(50) DEFAULT NULL,
  `Topping` varchar(255) DEFAULT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `menu`
--

INSERT INTO `menu` (`ProductID`, `ProductName`, `Price`, `Size`, `Topping`, `Quantity`) VALUES
(1, 'Cà phê đen', 21, 'M', '', 5),
(2, 'Bạc xỉu', 25, 'M', '', 29),
(3, 'Trà sữa olong', 45, 'L', 'Trân châu trắng', 21),
(5, 'Trà nhiệt đới', 40, 'M', 'trái cây tươi', 8);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `CustomerName` varchar(255) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Note` text DEFAULT NULL,
  `OrderedItems` text DEFAULT NULL,
  `DiscountID` int(11) DEFAULT NULL,
  `TotalPrice` double NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`OrderID`, `CustomerName`, `Phone`, `Email`, `Address`, `Note`, `OrderedItems`, `DiscountID`, `TotalPrice`, `CreatedAt`) VALUES
(1, 'vinh', '23443', '13131@gmail.com', '13131', '', NULL, 1, 18.900000000000002, '2025-11-22 02:56:52'),
(4, 'đức ngu', '1111', '131311@gmail.com', '123', '', 'Cà phê đen x5', 1, 94.5, '2025-11-22 03:14:20'),
(6, 'tuấn ngu', '12', '12@gmail.com', '12', '', 'Trà nhiệt đới x5', 5, 160, '2025-11-22 04:49:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_stats`
--

CREATE TABLE `order_stats` (
  `StatID` int(11) NOT NULL,
  `CustomerName` varchar(255) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Status` varchar(50) NOT NULL DEFAULT 'Đang chờ',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_stats`
--

INSERT INTO `order_stats` (`StatID`, `CustomerName`, `Phone`, `Status`, `CreatedAt`) VALUES
(1, 'vinh', '23443', 'Đang chờ', '2025-11-22 03:51:42'),
(2, 'đức ngu', '1111', 'Đang xử lý', '2025-11-22 03:51:47'),
(4, 'tuấn ngu', '12', 'Hủy', '2025-11-22 04:50:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Size` varchar(50) DEFAULT NULL,
  `Topping` varchar(255) DEFAULT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `Price`, `Size`, `Topping`, `Quantity`) VALUES
(3, 'Trà sữa olong', 45.00, 'L', 'Trân châu trắng', 21),
(4, 'Bạc xỉu', 25.00, 'M', '', 30),
(5, 'Trà sữa olong', 45.00, 'L', 'Trân châu trắng', 23),
(6, 'Trà đá Văn Lang', 1.00, 'M', '', 1),
(7, 'Trà nhiệt đới', 40.00, 'M', 'trái cây tươi', 13);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `password`) VALUES
(4, 'vinh', '123'),
(5, 'admin', '123456'),
(7, 'tuanngu', '123');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`LogID`);

--
-- Chỉ mục cho bảng `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`DiscountID`);

--
-- Chỉ mục cho bảng `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`ProductID`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`);

--
-- Chỉ mục cho bảng `order_stats`
--
ALTER TABLE `order_stats`
  ADD PRIMARY KEY (`StatID`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `discounts`
--
ALTER TABLE `discounts`
  MODIFY `DiscountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `menu`
--
ALTER TABLE `menu`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `order_stats`
--
ALTER TABLE `order_stats`
  MODIFY `StatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
