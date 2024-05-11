-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 11, 2024 lúc 02:32 PM
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
-- Cơ sở dữ liệu: `mathang`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `kichthuoc`
--

CREATE TABLE `kichthuoc` (
  `makt` varchar(10) NOT NULL,
  `tenkt` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `kichthuoc`
--

INSERT INTO `kichthuoc` (`makt`, `tenkt`) VALUES
('2XL', 'Cỡ 2XL'),
('L', 'Cỡ L'),
('M', 'Cỡ M'),
('S', 'Cỡ S'),
('XL', 'Cỡ XL');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ktsanpham`
--

CREATE TABLE `ktsanpham` (
  `masp` varchar(10) NOT NULL,
  `makt` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaisanpham`
--

CREATE TABLE `loaisanpham` (
  `maloai` varchar(10) NOT NULL,
  `tenloai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loaisanpham`
--

INSERT INTO `loaisanpham` (`maloai`, `tenloai`) VALUES
('L01', 'Áo phông'),
('L02', 'Áo sơ mi'),
('L03', 'Áo khoác'),
('L04', 'Quần âu'),
('L05', 'Quần kaki'),
('L06', 'Váy'),
('L07', 'Quần jean');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mausac`
--

CREATE TABLE `mausac` (
  `mamau` varchar(10) NOT NULL,
  `tenmau` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `mausac`
--

INSERT INTO `mausac` (`mamau`, `tenmau`) VALUES
('CBEIGE', 'Be'),
('CBLACK', 'Đen'),
('CBLUE', 'Xanh dương'),
('CBROWN', 'Nâu'),
('CGRAY', 'Xám'),
('CGREEN', 'Xanh lá'),
('CORANGE', 'Cam'),
('CPINK', 'Hồng'),
('CWHITE', 'Trắng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mausanpham`
--

CREATE TABLE `mausanpham` (
  `masp` varchar(10) NOT NULL,
  `mamau` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `masp` varchar(10) NOT NULL,
  `tensp` varchar(50) NOT NULL,
  `gia` int(11) NOT NULL,
  `maloai` varchar(10) NOT NULL,
  `soluong` int(11) NOT NULL,
  `hinhanh` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `priv` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`username`, `password`, `priv`) VALUES
('admin', 'admin', 1),
('user', 'user', 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `kichthuoc`
--
ALTER TABLE `kichthuoc`
  ADD PRIMARY KEY (`makt`);

--
-- Chỉ mục cho bảng `ktsanpham`
--
ALTER TABLE `ktsanpham`
  ADD PRIMARY KEY (`masp`,`makt`),
  ADD KEY `chk_makt` (`makt`);

--
-- Chỉ mục cho bảng `loaisanpham`
--
ALTER TABLE `loaisanpham`
  ADD PRIMARY KEY (`maloai`);

--
-- Chỉ mục cho bảng `mausac`
--
ALTER TABLE `mausac`
  ADD PRIMARY KEY (`mamau`);

--
-- Chỉ mục cho bảng `mausanpham`
--
ALTER TABLE `mausanpham`
  ADD PRIMARY KEY (`masp`,`mamau`),
  ADD KEY `chk_mamau` (`mamau`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`masp`),
  ADD KEY `chk_maloai` (`maloai`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`username`);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `ktsanpham`
--
ALTER TABLE `ktsanpham`
  ADD CONSTRAINT `chk_makt` FOREIGN KEY (`makt`) REFERENCES `kichthuoc` (`makt`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chk_masp1` FOREIGN KEY (`masp`) REFERENCES `sanpham` (`masp`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `mausanpham`
--
ALTER TABLE `mausanpham`
  ADD CONSTRAINT `chk_mamau` FOREIGN KEY (`mamau`) REFERENCES `mausac` (`mamau`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chk_masp2` FOREIGN KEY (`masp`) REFERENCES `sanpham` (`masp`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `chk_maloai` FOREIGN KEY (`maloai`) REFERENCES `loaisanpham` (`maloai`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
