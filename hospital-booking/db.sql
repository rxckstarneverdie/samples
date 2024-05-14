-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 10 May 2024, 19:37:58
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `hastanesistemi`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `doktorlar`
--

CREATE TABLE `doktorlar` (
  `id` int(11) NOT NULL,
  `tcno` varchar(11) DEFAULT NULL,
  `sifre` varchar(50) DEFAULT NULL,
  `ad` varchar(50) DEFAULT NULL,
  `soyad` varchar(50) DEFAULT NULL,
  `uzmanlikalani` varchar(100) DEFAULT NULL,
  `calistigihastane` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hastalar`
--

CREATE TABLE `hastalar` (
  `id` int(11) NOT NULL,
  `tcno` varchar(11) DEFAULT NULL,
  `sifre` varchar(50) DEFAULT NULL,
  `ad` varchar(50) DEFAULT NULL,
  `soyad` varchar(50) DEFAULT NULL,
  `dogumtarihi` date DEFAULT NULL,
  `cinsiyet` varchar(10) DEFAULT NULL,
  `telefonnumarasi` varchar(20) DEFAULT NULL,
  `adres` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `randevular`
--

CREATE TABLE `randevular` (
  `id` int(11) NOT NULL,
  `hastaid` int(11) DEFAULT NULL,
  `doktorid` int(11) DEFAULT NULL,
  `tarih` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tibbiraporlar`
--

CREATE TABLE `tibbiraporlar` (
  `id` int(11) NOT NULL,
  `hastaid` int(11) DEFAULT NULL,
  `doktorid` int(11) DEFAULT NULL,
  `tarih` date DEFAULT NULL,
  `rapor` text DEFAULT NULL,
  `isadmin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yonetici`
--

CREATE TABLE `yonetici` (
  `id` int(11) NOT NULL,
  `tcno` varchar(11) DEFAULT NULL,
  `ad` varchar(50) DEFAULT NULL,
  `soyad` varchar(50) DEFAULT NULL,
  `sifre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `yonetici`
--

INSERT INTO `yonetici` (`id`, `tcno`, `ad`, `soyad`, `sifre`) VALUES
(2, '11111111110', 'Admin', 'Demo', 'demo');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `doktorlar`
--
ALTER TABLE `doktorlar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `hastalar`
--
ALTER TABLE `hastalar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `randevular`
--
ALTER TABLE `randevular`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `tibbiraporlar`
--
ALTER TABLE `tibbiraporlar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `yonetici`
--
ALTER TABLE `yonetici`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `doktorlar`
--
ALTER TABLE `doktorlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `hastalar`
--
ALTER TABLE `hastalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `randevular`
--
ALTER TABLE `randevular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `tibbiraporlar`
--
ALTER TABLE `tibbiraporlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Tablo için AUTO_INCREMENT değeri `yonetici`
--
ALTER TABLE `yonetici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
