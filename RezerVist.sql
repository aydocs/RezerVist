-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 09 Oca 2026, 11:56:18
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
-- Veritabanı: `RezerVist`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `activity_logs`
--
--
-- Table structure for table `categories`
--
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'place',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
--
-- Dumping data for table `categories`
--
INSERT INTO `categories` (`id`, `name`, `slug`, `type`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Restoran', 'restoran', 'place', 'restaurant', NOW(), NOW()),
(2, 'Kafe', 'kafe', 'place', 'cafe', NOW(), NOW()),
(3, 'Güzellik Salonu', 'guzellik-salonu', 'service', 'salon', NOW(), NOW()),
(4, 'Otel', 'otel', 'place', 'hotel', NOW(), NOW());
CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `activity_logs`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `audit_logs`
--
CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `audit_logs`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `businesses`
--
CREATE TABLE `businesses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'Restoran',
  `description` text DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `address` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `price_per_person` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `min_reservation_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deleted_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `businesses`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `business_applications`
--
CREATE TABLE `business_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `trade_registry_no` varchar(255) NOT NULL,
  `tax_id` varchar(255) NOT NULL,
  `trade_registry_document` varchar(255) NOT NULL,
  `tax_document` varchar(255) NOT NULL,
  `license_document` varchar(255) NOT NULL,
  `id_document` varchar(255) NOT NULL,
  `bank_document` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `business_applications`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `business_hours`
--
CREATE TABLE `business_hours` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `day_of_week` tinyint(4) NOT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `special_date` date DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `business_hours`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `business_images`
--
CREATE TABLE `business_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `business_images`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `business_tag`
--
CREATE TABLE `business_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `business_tag`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `cache`
--
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
--
-- Tablo döküm verisi `cache`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `cache_locks`
--
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `campaigns`
--
CREATE TABLE `campaigns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `discount_text` varchar(255) DEFAULT NULL,
  `discount_code` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) NOT NULL DEFAULT 'Hemen Keşfet',
  `button_link` varchar(255) NOT NULL DEFAULT '/search',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `campaigns`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `categories`
--
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'place',
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `categories`
--
INSERT INTO `categories` (`id`, `name`, `slug`, `type`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Restoran', 'restoran', 'place', 'restaurant', '2026-01-06 12:58:04', '2026-01-06 12:58:04'),
(2, 'Kafe', 'kafe', 'place', 'cafe', '2026-01-06 12:58:04', '2026-01-06 12:58:04'),
(3, 'Güzellik Salonu', 'guzellik-salonu', 'service', 'salon', '2026-01-06 12:58:04', '2026-01-06 12:58:04'),
(4, 'Otel', 'otel', 'place', 'hotel', '2026-01-06 12:58:04', '2026-01-06 12:58:04');
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `contact_messages`
--
CREATE TABLE `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `priority` varchar(255) NOT NULL DEFAULT 'normal',
  `message` text NOT NULL,
  `reply` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `contact_messages`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `failed_jobs`
--
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `favorites`
--
CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `favorites`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `jobs`
--
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `job_batches`
--
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `menus`
--
CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'Main',
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `is_vegetarian` tinyint(1) NOT NULL DEFAULT 0,
  `is_vegan` tinyint(1) NOT NULL DEFAULT 0,
  `is_gluten_free` tinyint(1) NOT NULL DEFAULT 0,
  `calories` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `menus`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `menu_reservation`
--
CREATE TABLE `menu_reservation` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `reservation_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `menu_reservation`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `migrations`
--
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
--
-- Tablo döküm verisi `migrations`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `notifications`
--
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `notifications`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `password_reset_tokens`
--
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `payments`
--
CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reservation_id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `conversation_id` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `paid_price` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `currency` varchar(255) NOT NULL DEFAULT 'TRY',
  `basket_id` varchar(255) DEFAULT NULL,
  `card_family` varchar(255) DEFAULT NULL,
  `card_type` varchar(255) DEFAULT NULL,
  `bin_number` varchar(255) DEFAULT NULL,
  `raw_result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_result`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `payments`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `personal_access_tokens`
--
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `phone_verifications`
--
CREATE TABLE `phone_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(20) NOT NULL,
  `code` varchar(6) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL DEFAULT 'registration',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `refund_requests`
--
CREATE TABLE `refund_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reservation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `iyzico_payment_id` varchar(255) DEFAULT NULL,
  `iyzico_conversation_id` varchar(255) DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `refund_requests`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `reservations`
--
CREATE TABLE `reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `resource_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_count` int(11) NOT NULL DEFAULT 1,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reminded_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `reservations`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `resources`
--
CREATE TABLE `resources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 1,
  `type` varchar(255) NOT NULL DEFAULT 'table',
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `resources`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `reviews`
--
CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `reviews`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `sessions`
--
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `settings`
--
CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `settings`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `tags`
--
CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `tags`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `users`
--
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `apple_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Turkey',
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `plain_password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL
--
-- Tablo döküm verisi `users`
--
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `wallet_transactions`
--
CREATE TABLE `wallet_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'success',
  `description` varchar(255) DEFAULT NULL,
  `reference_id` varchar(255) DEFAULT NULL,
  `meta_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
--
-- Tablo döküm verisi `wallet_transactions`
--
--
-- Dökümü yapılmış tablolar için indeksler
--
--
-- Tablo için indeksler `activity_logs`
--
ALTER TABLE `activity_logs`
--
-- Tablo için indeksler `audit_logs`
--
ALTER TABLE `audit_logs`
--
-- Tablo için indeksler `businesses`
--
ALTER TABLE `businesses`
--
-- Tablo için indeksler `business_applications`
--
ALTER TABLE `business_applications`
--
-- Tablo için indeksler `business_hours`
--
ALTER TABLE `business_hours`
--
-- Tablo için indeksler `business_images`
--
ALTER TABLE `business_images`
--
-- Tablo için indeksler `business_tag`
--
ALTER TABLE `business_tag`
--
-- Tablo için indeksler `cache`
--
ALTER TABLE `cache`
--
-- Tablo için indeksler `cache_locks`
--
ALTER TABLE `cache_locks`
--
-- Tablo için indeksler `campaigns`
--
ALTER TABLE `campaigns`
--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
--
-- Tablo için indeksler `contact_messages`
--
ALTER TABLE `contact_messages`
--
-- Tablo için indeksler `failed_jobs`
--
ALTER TABLE `failed_jobs`
--
-- Tablo için indeksler `favorites`
--
ALTER TABLE `favorites`
--
-- Tablo için indeksler `jobs`
--
ALTER TABLE `jobs`
--
-- Tablo için indeksler `job_batches`
--
ALTER TABLE `job_batches`
--
-- Tablo için indeksler `menus`
--
ALTER TABLE `menus`
--
-- Tablo için indeksler `menu_reservation`
--
ALTER TABLE `menu_reservation`
--
-- Tablo için indeksler `migrations`
--
ALTER TABLE `migrations`
--
-- Tablo için indeksler `notifications`
--
ALTER TABLE `notifications`
--
-- Tablo için indeksler `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
--
-- Tablo için indeksler `payments`
--
ALTER TABLE `payments`
--
-- Tablo için indeksler `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
--
-- Tablo için indeksler `phone_verifications`
--
ALTER TABLE `phone_verifications`
--
-- Tablo için indeksler `refund_requests`
--
ALTER TABLE `refund_requests`
--
-- Tablo için indeksler `reservations`
--
ALTER TABLE `reservations`
--
-- Tablo için indeksler `resources`
--
ALTER TABLE `resources`
--
-- Tablo için indeksler `reviews`
--
ALTER TABLE `reviews`
--
-- Tablo için indeksler `sessions`
--
ALTER TABLE `sessions`
--
-- Tablo için indeksler `settings`
--
ALTER TABLE `settings`
--
-- Tablo için indeksler `tags`
--
ALTER TABLE `tags`
--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
--
-- Tablo için indeksler `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--
--
-- Tablo için AUTO_INCREMENT değeri `activity_logs`
--
ALTER TABLE `activity_logs`
--
-- Tablo için AUTO_INCREMENT değeri `audit_logs`
--
ALTER TABLE `audit_logs`
--
-- Tablo için AUTO_INCREMENT değeri `businesses`
--
ALTER TABLE `businesses`
--
-- Tablo için AUTO_INCREMENT değeri `business_applications`
--
ALTER TABLE `business_applications`
--
-- Tablo için AUTO_INCREMENT değeri `business_hours`
--
ALTER TABLE `business_hours`
--
-- Tablo için AUTO_INCREMENT değeri `business_images`
--
ALTER TABLE `business_images`
--
-- Tablo için AUTO_INCREMENT değeri `business_tag`
--
ALTER TABLE `business_tag`
--
-- Tablo için AUTO_INCREMENT değeri `campaigns`
--
ALTER TABLE `campaigns`
--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
--
-- Tablo için AUTO_INCREMENT değeri `contact_messages`
--
ALTER TABLE `contact_messages`
--
-- Tablo için AUTO_INCREMENT değeri `failed_jobs`
--
ALTER TABLE `failed_jobs`
--
-- Tablo için AUTO_INCREMENT değeri `favorites`
--
ALTER TABLE `favorites`
--
-- Tablo için AUTO_INCREMENT değeri `jobs`
--
ALTER TABLE `jobs`
--
-- Tablo için AUTO_INCREMENT değeri `menus`
--
ALTER TABLE `menus`
--
-- Tablo için AUTO_INCREMENT değeri `menu_reservation`
--
ALTER TABLE `menu_reservation`
--
-- Tablo için AUTO_INCREMENT değeri `migrations`
--
ALTER TABLE `migrations`
--
-- Tablo için AUTO_INCREMENT değeri `payments`
--
ALTER TABLE `payments`
--
-- Tablo için AUTO_INCREMENT değeri `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
--
-- Tablo için AUTO_INCREMENT değeri `phone_verifications`
--
ALTER TABLE `phone_verifications`
--
-- Tablo için AUTO_INCREMENT değeri `refund_requests`
--
ALTER TABLE `refund_requests`
--
-- Tablo için AUTO_INCREMENT değeri `reservations`
--
ALTER TABLE `reservations`
--
-- Tablo için AUTO_INCREMENT değeri `resources`
--
ALTER TABLE `resources`
--
-- Tablo için AUTO_INCREMENT değeri `reviews`
--
ALTER TABLE `reviews`
--
-- Tablo için AUTO_INCREMENT değeri `settings`
--
ALTER TABLE `settings`
--
-- Tablo için AUTO_INCREMENT değeri `tags`
--
ALTER TABLE `tags`
--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
--
-- Tablo için AUTO_INCREMENT değeri `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
--
-- Dökümü yapılmış tablolar için kısıtlamalar
--
--
-- Tablo kısıtlamaları `activity_logs`
--
ALTER TABLE `activity_logs`
--
-- Tablo kısıtlamaları `audit_logs`
--
ALTER TABLE `audit_logs`
--
-- Tablo kısıtlamaları `businesses`
--
ALTER TABLE `businesses`
--
-- Tablo kısıtlamaları `business_applications`
--
ALTER TABLE `business_applications`
--
-- Tablo kısıtlamaları `business_hours`
--
ALTER TABLE `business_hours`
--
-- Tablo kısıtlamaları `business_images`
--
ALTER TABLE `business_images`
--
-- Tablo kısıtlamaları `business_tag`
--
ALTER TABLE `business_tag`
--
-- Tablo kısıtlamaları `contact_messages`
--
ALTER TABLE `contact_messages`
--
-- Tablo kısıtlamaları `favorites`
--
ALTER TABLE `favorites`
--
-- Tablo kısıtlamaları `menus`
--
ALTER TABLE `menus`
--
-- Tablo kısıtlamaları `menu_reservation`
--
ALTER TABLE `menu_reservation`
--
-- Tablo kısıtlamaları `payments`
--
ALTER TABLE `payments`
--
-- Tablo kısıtlamaları `refund_requests`
--
ALTER TABLE `refund_requests`
--
-- Tablo kısıtlamaları `reservations`
--
ALTER TABLE `reservations`
--
-- Tablo kısıtlamaları `resources`
--
ALTER TABLE `resources`
--
-- Tablo kısıtlamaları `reviews`
--
ALTER TABLE `reviews`
--
-- Tablo kısıtlamaları `users`
--
ALTER TABLE `users`
--
-- Tablo kısıtlamaları `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
--
-- Dumping data for table `users` (Admin)
--
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;