-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 17, 2025 lúc 11:28 AM
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
-- Cơ sở dữ liệu: `qlcf`
--

DELIMITER $$
--
-- Thủ tục
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddNewProduct` (IN `p_ProductName` VARCHAR(100) COLLATE utf8mb4_unicode_ci, IN `p_Price` DECIMAL(10,2), IN `p_QuantityInStock` INT)   BEGIN
    INSERT INTO AddProducts (ProductName, Price, QuantityInStock)
    VALUES (p_ProductName, p_Price, p_QuantityInStock);
    SELECT 'Đã thêm sản phẩm mới' AS Message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddNewUser` (IN `p_Username` VARCHAR(50) COLLATE utf8mb4_unicode_ci, IN `p_Password` VARCHAR(255) COLLATE utf8mb4_unicode_ci)   BEGIN
    INSERT INTO NewUsers (Username, Password)
    VALUES (p_Username, p_Password);
    SELECT 'Đã thêm người dùng mới' AS Message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddOrder` (IN `p_UserID` INT, IN `p_ProductID` INT, IN `p_Quantity` INT, IN `p_TotalAmount` DECIMAL(10,2))   BEGIN
    -- Thêm đơn hàng vào bảng Orders
    INSERT INTO AddOrders (UserID, ProductID, Quantity, TotalAmount)
    VALUES (p_UserID, p_ProductID, p_Quantity, p_TotalAmount);

    -- Cập nhật số lượng còn lại trong kho
    UPDATE AddProducts
    SET QuantityInStock = QuantityInStock - p_Quantity
    WHERE ProductID = p_ProductID;

    -- Cập nhật hoặc thêm thông tin thống kê cho sản phẩm
    IF EXISTS (SELECT 1 FROM ProductStatistics WHERE ProductID = p_ProductID) THEN
        -- Cập nhật thống kê nếu đã có dữ liệu cho sản phẩm này
        UPDATE ProductStatistics
        SET TotalQuantitySold = TotalQuantitySold + p_Quantity,
            TotalRevenue = TotalRevenue + p_TotalAmount
        WHERE ProductID = p_ProductID;
    ELSE
        -- Thêm mới một bản ghi thống kê cho sản phẩm này nếu chưa có
        INSERT INTO ProductStatistics (ProductID, TotalQuantitySold, TotalRevenue)
        VALUES (p_ProductID, p_Quantity, p_TotalAmount);
    END IF;

    SELECT 'Đã thêm đơn hàng mới' AS Message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteProductFromStore` (IN `p_ProductID` INT)   BEGIN
    DELETE FROM AddProducts WHERE ProductID = p_ProductID;
    SELECT 'Đã xóa sản phẩm' AS Message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteUserAccount` (IN `p_UserID` INT)   BEGIN
    DELETE FROM NewUsers WHERE UserID = p_UserID;
    SELECT 'Đã xóa người dùng' AS Message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetSalesStatisticsReport` ()   BEGIN
    SELECT p.ProductName,
           SUM(o.Quantity) AS TotalQuantitySold,
           SUM(o.TotalAmount) AS TotalRevenue
    FROM AddOrders o
    JOIN AddProducts p ON o.ProductID = p.ProductID
    GROUP BY p.ProductName;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SearchProductName` (IN `p_ProductName` VARCHAR(100) COLLATE utf8mb4_unicode_ci)   BEGIN
    SELECT *
    FROM AddProducts
    WHERE ProductName LIKE CONCAT('%', p_ProductName, '%');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateNewUser` (IN `p_UserID` INT, IN `p_NewUsername` VARCHAR(50) COLLATE utf8mb4_unicode_ci, IN `p_NewPassword` VARCHAR(255) COLLATE utf8mb4_unicode_ci)   BEGIN
    UPDATE NewUsers
    SET Username = p_NewUsername,
        Password = p_NewPassword
    WHERE UserID = p_UserID;
    SELECT 'Thông tin người dùng đã được cập nhật' AS Message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateProductDetails` (IN `p_ProductID` INT, IN `p_NewProductName` VARCHAR(100) COLLATE utf8mb4_unicode_ci, IN `p_NewPrice` DECIMAL(10,2), IN `p_NewQuantityInStock` INT)   BEGIN
    UPDATE AddProducts
    SET ProductName = p_NewProductName,
        Price = p_NewPrice,
        QuantityInStock = p_NewQuantityInStock
    WHERE ProductID = p_ProductID;
    SELECT 'Thông tin sản phẩm đã được cập nhật' AS Message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UserLogin` (IN `p_Username` VARCHAR(50) COLLATE utf8mb4_unicode_ci, IN `p_Password` VARCHAR(255) COLLATE utf8mb4_unicode_ci)   BEGIN
    IF EXISTS (
        SELECT 1 FROM NewUsers
        WHERE Username = p_Username
          AND Password = p_Password
    ) THEN
        SELECT 'Đăng nhập thành công' AS Message;
    ELSE
        SELECT 'Tên người dùng hoặc mật khẩu không đúng' AS Message;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `addorders`
--

CREATE TABLE `addorders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `OrderDate` datetime DEFAULT current_timestamp(),
  `Quantity` int(11) DEFAULT NULL,
  `TotalAmount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `addorders`
--

INSERT INTO `addorders` (`OrderID`, `UserID`, `ProductID`, `OrderDate`, `Quantity`, `TotalAmount`) VALUES
(1, 1, 1, '2025-11-16 14:43:04', 2, 60.00),
(2, 1, 2, '2025-11-16 14:43:04', 3, 75.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `addproducts`
--

CREATE TABLE `addproducts` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(100) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `QuantityInStock` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 1,
  `Size` varchar(50) DEFAULT 'M',
  `Topping` varchar(255) DEFAULT '',
  `Edit` varchar(255) DEFAULT NULL,
  `Sold` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `addproducts`
--

INSERT INTO `addproducts` (`ProductID`, `ProductName`, `Price`, `QuantityInStock`, `Quantity`, `Size`, `Topping`, `Edit`, `Sold`) VALUES
(1, 'Cà phê Sữa', 30.00, 98, 100, 'M', '', NULL, 0),
(2, 'Cà phê Đen', 25.00, 47, 100, 'M', '', NULL, 0),
(12, 'Bạc xỉu', 25.00, NULL, 100, 'M', '', NULL, 0),
(14, 'Cà phê sữa', 25.00, NULL, 100, 'M', '', NULL, 0),
(16, 'Trà dưa lưới nhiệt đới', 45.00, NULL, 50, 'M', '', NULL, 0),
(28, 'Trà sữa bạc hà', 45.00, NULL, 16, 'M', '', NULL, 0),
(30, 'Trà sữa olong', 50.00, NULL, 19, 'M', '', NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `newusers`
--

CREATE TABLE `newusers` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `newusers`
--

INSERT INTO `newusers` (`UserID`, `Username`, `Password`) VALUES
(1, 'admin', 'admin123'),
(2, 'user1', 'password1');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `productstatistics`
--

CREATE TABLE `productstatistics` (
  `StatID` int(11) NOT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `TotalQuantitySold` int(11) DEFAULT NULL,
  `TotalRevenue` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `productstatistics`
--

INSERT INTO `productstatistics` (`StatID`, `ProductID`, `TotalQuantitySold`, `TotalRevenue`) VALUES
(1, 1, 2, 60.00),
(2, 2, 3, 75.00);

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `thongkesp`
-- (See below for the actual view)
--
CREATE TABLE `thongkesp` (
`TongSanPham` bigint(21)
,`TongHangTon` decimal(32,0)
,`SanPhamTonNhieuNhat` varchar(100)
,`SoLuongTonNhieuNhat` int(11)
,`SanPhamBanChayNhat` varchar(100)
,`SoLuongBanChayNhat` int(11)
);

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `thongke_tonghop`
-- (See below for the actual view)
--
CREATE TABLE `thongke_tonghop` (
`TongSanPham` bigint(21)
,`TongHangTon` decimal(32,0)
,`TongGiaTriTon` decimal(42,2)
,`TonKhoNhieuNhat` varchar(100)
,`SoLuongTonNhieuNhat` int(11)
,`BanChayNhat` varchar(100)
,`SoLuongBanChay` int(11)
);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'đình vinh', '$2y$10$tyEeTMb6LIQiCbHhRqkFv./eIVq1BsXDvSsiT0JUUfUpxFm8DAj8e'),
(2, 'dinhvinh', '$2y$10$Jelgyy51NjaiQTKPAGuBRenKWU38EvKX5t4zo3g.g5SpWLl1GjQmS'),
(3, 'vinh', '123'),
(4, 'tuanngu', '123'),
(5, 'nhat', '123'),
(6, 'admin', '$2y$10$beQZTWGm0Z2sdUiU6zxrBOdm/2FRva6uINw.YeobCRzDT/YehyMBO'),
(7, 'cu', '$2y$10$./4.TQayXSYj/HKDEmrcqeOFnYdRqWHQlc2EvauyAAgcRVBVB0ZlO');

-- --------------------------------------------------------

--
-- Cấu trúc cho view `thongkesp`
--
DROP TABLE IF EXISTS `thongkesp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `thongkesp`  AS SELECT (select count(0) from `addproducts`) AS `TongSanPham`, (select sum(`addproducts`.`Quantity`) from `addproducts`) AS `TongHangTon`, (select `addproducts`.`ProductName` from `addproducts` order by `addproducts`.`Quantity` desc limit 1) AS `SanPhamTonNhieuNhat`, (select `addproducts`.`Quantity` from `addproducts` order by `addproducts`.`Quantity` desc limit 1) AS `SoLuongTonNhieuNhat`, (select `addproducts`.`ProductName` from `addproducts` order by `addproducts`.`Sold` desc limit 1) AS `SanPhamBanChayNhat`, (select `addproducts`.`Sold` from `addproducts` order by `addproducts`.`Sold` desc limit 1) AS `SoLuongBanChayNhat` ;

-- --------------------------------------------------------

--
-- Cấu trúc cho view `thongke_tonghop`
--
DROP TABLE IF EXISTS `thongke_tonghop`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `thongke_tonghop`  AS SELECT (select count(0) from `addproducts`) AS `TongSanPham`, (select sum(`addproducts`.`Quantity`) from `addproducts`) AS `TongHangTon`, (select sum(`addproducts`.`Quantity` * `addproducts`.`Price`) from `addproducts`) AS `TongGiaTriTon`, (select `addproducts`.`ProductName` from `addproducts` order by `addproducts`.`Quantity` desc limit 1) AS `TonKhoNhieuNhat`, (select `addproducts`.`Quantity` from `addproducts` order by `addproducts`.`Quantity` desc limit 1) AS `SoLuongTonNhieuNhat`, (select `addproducts`.`ProductName` from `addproducts` order by `addproducts`.`Sold` desc limit 1) AS `BanChayNhat`, (select `addproducts`.`Sold` from `addproducts` order by `addproducts`.`Sold` desc limit 1) AS `SoLuongBanChay` ;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `addorders`
--
ALTER TABLE `addorders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Chỉ mục cho bảng `addproducts`
--
ALTER TABLE `addproducts`
  ADD PRIMARY KEY (`ProductID`);

--
-- Chỉ mục cho bảng `newusers`
--
ALTER TABLE `newusers`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Chỉ mục cho bảng `productstatistics`
--
ALTER TABLE `productstatistics`
  ADD PRIMARY KEY (`StatID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `addorders`
--
ALTER TABLE `addorders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `addproducts`
--
ALTER TABLE `addproducts`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT cho bảng `newusers`
--
ALTER TABLE `newusers`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `productstatistics`
--
ALTER TABLE `productstatistics`
  MODIFY `StatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `addorders`
--
ALTER TABLE `addorders`
  ADD CONSTRAINT `addorders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `newusers` (`UserID`),
  ADD CONSTRAINT `addorders_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `addproducts` (`ProductID`);

--
-- Các ràng buộc cho bảng `productstatistics`
--
ALTER TABLE `productstatistics`
  ADD CONSTRAINT `productstatistics_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `addproducts` (`ProductID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
