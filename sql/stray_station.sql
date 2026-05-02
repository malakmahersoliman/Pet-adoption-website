-- Single relational database for The Stray Station (import once):
--   mysql -u root -p < sql/stray_station.sql
--
-- Tables: adopters (auth), pets (CRUD), adoption_submissions (FK → pets)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS `stray_station`;
CREATE DATABASE `stray_station` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `stray_station`;

CREATE TABLE `adopters` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `adopters_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `type` enum('dogs','cats','diff') NOT NULL DEFAULT 'cats',
  `description` varchar(2000) NOT NULL DEFAULT '',
  `image_url` varchar(512) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `pets_type_idx` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `adoption_submissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `adopter_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `age` int unsigned NOT NULL,
  `adoption_interest` text NOT NULL,
  `pet_id` int unsigned DEFAULT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `submissions_pet_fk` (`pet_id`),
  CONSTRAINT `submissions_pet_fk` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- Seed sample pets (optional; comment out if empty DB preferred)
INSERT INTO `pets` (`name`, `type`, `description`, `image_url`) VALUES
('Meshmesh', 'cats', 'Fuzzy and fluffy male cat.', 'https://i.pinimg.com/736x/0d/88/c6/0d88c617ac8262a35a0f56c6933e66d1.jpg'),
('Max', 'dogs', '1-year-old male, rescue obedient and well-behaved guard dog.', 'https://i.pinimg.com/474x/5c/a1/ba/5ca1ba5c8904a08d3895f20ad87072b6.jpg'),
('Zatoona', 'diff', 'Loves running on her wheel and is very cuddly and curious.', 'https://i.pinimg.com/736x/ed/e0/b6/ede0b66dd85ae0fd802f77d56459aa27.jpg');

COMMIT;
