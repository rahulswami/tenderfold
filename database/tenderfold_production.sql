-- MySQL dump 10.13  Distrib 9.7.1, for macos14.8 (x86_64)
--
-- Host: localhost    Database: tenderfold
-- ------------------------------------------------------
-- Server version	9.7.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_notifications`
--

LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
INSERT INTO `admin_notifications` VALUES (1,'error','System Exception: Undefined variable $navServices (View: /Users/apple/Documents/Webs/Tenderfold/resources/views/index.blade.php)','File: /Users/apple/Documents/Webs/Tenderfold/storage/framework/views/6c1f5fb401265ba21ff1536bd1f61656.php (Line 177)','System','http://localhost/admin/internal_notifications',0,'2026-09-25 21:36:37','2026-09-25 21:36:37'),(2,'error','System Exception: Undefined variable $navServices (View: /Users/apple/Documents/Webs/Tenderfold/resources/views/index.blade.php)','File: /Users/apple/Documents/Webs/Tenderfold/storage/framework/views/6c1f5fb401265ba21ff1536bd1f61656.php (Line 177)','System','http://localhost/admin/internal_notifications',0,'2026-09-25 21:36:44','2026-09-25 21:36:44'),(3,'error','System Exception: syntax error, unexpected end of file (View: /Users/apple/Documents/Webs/Tenderfold/resources/views/blog/show.blade.php)','File: /Users/apple/Documents/Webs/Tenderfold/storage/framework/views/fd16da04128fa513d8f8a60774682f10.php (Line 119)','System','http://localhost/admin/internal_notifications',0,'2026-09-25 21:37:40','2026-09-25 21:37:40'),(4,'error','System Exception: syntax error, unexpected end of file (View: /Users/apple/Documents/Webs/Tenderfold/resources/views/blog/show.blade.php)','File: /Users/apple/Documents/Webs/Tenderfold/storage/framework/views/fd16da04128fa513d8f8a60774682f10.php (Line 119)','System','http://localhost/admin/internal_notifications',0,'2026-09-25 21:37:41','2026-09-25 21:37:41'),(5,'error','System Exception: syntax error, unexpected end of file (View: /Users/apple/Documents/Webs/Tenderfold/resources/views/blog/show.blade.php)','File: /Users/apple/Documents/Webs/Tenderfold/storage/framework/views/fd16da04128fa513d8f8a60774682f10.php (Line 119)','System','http://localhost/admin/internal_notifications',0,'2026-09-25 21:37:41','2026-09-25 21:37:41'),(6,'error','System Exception: Uncaught ParseError: syntax error, unexpected end of file in /Users/apple/Documents/Webs/Tenderfold/storage/framework/vi...','File: /Users/apple/Documents/Webs/Tenderfold/storage/framework/views/fd16da04128fa513d8f8a60774682f10.php (Line 119)','System','https://tenderfold.in/admin/internal_notifications',0,'2026-09-25 21:38:28','2026-09-25 21:38:28'),(7,'error','System Exception: Uncaught RuntimeException: This password does not use the Bcrypt algorithm. in /Users/apple/Documents/Webs/Tenderfold/ve...','File: /Users/apple/Documents/Webs/Tenderfold/vendor/laravel/framework/src/Illuminate/Hashing/BcryptHasher.php (Line 89)','System','http://localhost/admin/internal_notifications',0,'2026-09-25 21:40:39','2026-09-25 21:40:39'),(8,'lead','New Patient Lead: Garima Malhotra','Phone: 9582466445 | Email: garima@example.com','System','https://tenderfold.in/admin/leads',0,'2026-09-25 21:43:57','2026-09-25 21:43:57'),(9,'lead','New Patient Lead: Garima Malhotra','Phone: 9582466445 | Email: garima@example.com','System','https://tenderfold.in/admin/leads',0,'2026-09-25 21:44:04','2026-09-25 21:44:04'),(10,'lead','New Patient Lead: Garima Malhotra','Phone: 9582466445 | Email: garima@example.com','System','https://tenderfold.in/admin/leads',0,'2026-09-25 21:44:41','2026-09-25 21:44:41'),(11,'error','System Exception: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'is_published\' in \'where clause\' (Connection: mysql, Host: 127.0....','File: /Users/apple/Documents/Webs/Tenderfold/vendor/laravel/framework/src/Illuminate/Database/Connection.php (Line 857)','System','https://tenderfold.in/admin/internal_notifications',0,'2026-09-26 01:02:49','2026-09-26 01:02:49'),(12,'error','System Exception: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'content\' in \'field list\' (Connection: mysql, Host: 127.0.0.1, Po...','File: /Users/apple/Documents/Webs/Tenderfold/vendor/laravel/framework/src/Illuminate/Database/Connection.php (Line 857)','System','https://tenderfold.in/admin/internal_notifications',0,'2026-10-06 05:56:37','2026-10-06 05:56:37');
/*!40000 ALTER TABLE `admin_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditable_id` bigint unsigned DEFAULT NULL,
  `details` json DEFAULT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,NULL,'theme_create','App\\Models\\Theme',2,'{\"name\": \"default red\"}','127.0.0.1','2026-07-23 21:06:54','2026-07-23 21:06:54'),(2,NULL,'theme_create','App\\Models\\Theme',3,'{\"name\": \"Default\"}','127.0.0.1','2026-07-23 21:07:21','2026-07-23 21:07:21'),(3,NULL,'page_create','App\\Models\\Page',8,'{\"slug\": \"home-page\", \"title\": \"Home Page\"}','127.0.0.1','2026-07-23 21:08:50','2026-07-23 21:08:50'),(4,NULL,'page_publish','App\\Models\\Page',7,'{\"title\": \"Contact Us\"}','127.0.0.1','2026-07-24 00:12:05','2026-07-24 00:12:05'),(5,NULL,'page_publish','App\\Models\\Page',8,'{\"title\": \"Home Page\"}','127.0.0.1','2026-07-24 00:13:14','2026-07-24 00:13:14'),(6,NULL,'page_update','App\\Models\\Page',8,'{\"title\": \"Home Page\"}','127.0.0.1','2026-07-24 00:14:22','2026-07-24 00:14:22'),(7,NULL,'page_publish','App\\Models\\Page',8,'{\"title\": \"Home Page\"}','127.0.0.1','2026-07-24 00:14:25','2026-07-24 00:14:25'),(8,NULL,'page_update','App\\Models\\Page',4,'{\"title\": \"Testimonials\"}','127.0.0.1','2026-07-24 00:22:05','2026-07-24 00:22:05'),(9,NULL,'page_publish','App\\Models\\Page',4,'{\"title\": \"Testimonials\"}','127.0.0.1','2026-07-24 00:22:08','2026-07-24 00:22:08');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `schema_markup` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metatitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadescription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metakeywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tags` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blogs_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,'delicate-silk-care-guide','The Complete Guide to Caring for Delicate Silks & Wedding Wear','Fabric Care','Discover how to store, protect, and safely dry clean pure silk sarees, embellished lehengas, and embroidered sherwanis to prevent fading and zari tarnishing.','<p>Pure silks, Banarasi weaves, and heavily embellished wedding wear require gentle handling. In this guide, our master dry cleaners share professional tips on preventing moisture damage, using muslin storage wraps, and choosing eco hydrocarbon dry cleaning over harsh industrial washing.</p><h3>1. Never Use Plastic Bags for Long-Term Storage</h3><p>Plastic traps humidity and accelerates zari oxidation. Always wrap silks in pure cotton or unbleached muslin fabric.</p><h3>2. Professional Spot-Cleaning</h3><p>Avoid rubbing water on silk stains at home, as this creates permanent water rings. Bring the garment to certified dry cleaners immediately.</p>','assets/shirts.jpg',NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55',NULL,NULL,NULL,NULL,NULL),(2,'vacuum-steam-pressing-benefits','Why Vacuum Steam Pressing is Superior to Traditional Flat Ironing','Garment Finishing','Learn how Italian vacuum steam tables prevent fabric shine, preserve delicate fibers, and deliver razor-sharp creases on trousers and shirts.','<p>Conventional domestic flat irons rely on direct metallic heat that crushes fabric fibers and creates an unsightly reflective shine on dark wool and cotton suits. Vacuum steam pressing uses continuous high-pressure steam that permeates the weave while suction removes residual moisture immediately, setting sharp creases without thermal stress.</p>','assets/whiteshirt.jpg',NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55',NULL,NULL,NULL,NULL,NULL),(3,'sneaker-cleaning-maintenance-guide','How to Keep White Sneakers & Suede Shoes Pristine: Tips from our Shoe Spa','Footwear Spa','Expert tips on preventing sole yellowing, maintaining suede texture, and keeping designer sneakers fresh throughout the monsoon and summer.','<p>Sneakers and designer footwear endure the harshest exposure to dust, grease, and rain. Our shoe spa artisans break down the essential steps for cleaning white mesh, re-napping suede, and applying hydrophobic water-repellent coatings.</p>','assets/hero.jpg',NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('tenderfold-cache-global_page_contents','a:4:{s:5:\"about\";a:5:{s:4:\"hero\";a:3:{s:9:\"pre_title\";s:10:\"WHO WE ARE\";s:8:\"subtitle\";N;s:5:\"title\";N;}s:7:\"mission\";a:2:{s:4:\"text\";N;s:5:\"title\";s:11:\"Our Mission\";}s:5:\"story\";a:5:{s:2:\"p1\";N;s:2:\"p2\";N;s:2:\"p3\";N;s:9:\"pre_title\";s:9:\"Our Story\";s:5:\"title\";N;}s:6:\"values\";a:2:{s:4:\"text\";N;s:5:\"title\";s:15:\"Our Core Values\";}s:6:\"vision\";a:2:{s:4:\"text\";N;s:5:\"title\";s:10:\"Our Vision\";}}s:6:\"footer\";a:3:{s:5:\"about\";a:1:{s:11:\"description\";N;}s:6:\"bottom\";a:2:{s:9:\"copyright\";s:20:\"All Rights Reserved.\";s:10:\"disclaimer\";N;}s:8:\"headings\";a:3:{s:7:\"contact\";s:12:\"Contact Info\";s:11:\"quick_links\";s:11:\"Quick Links\";s:8:\"services\";s:15:\"Clinic Services\";}}s:6:\"header\";a:1:{s:4:\"menu\";a:8:{s:5:\"about\";s:8:\"About Us\";s:4:\"blog\";s:4:\"Blog\";s:7:\"compare\";s:10:\"Treatments\";s:7:\"contact\";s:10:\"Contact Us\";s:3:\"cta\";s:8:\"Call Now\";s:4:\"home\";s:4:\"Home\";s:8:\"services\";s:8:\"Services\";s:12:\"testimonials\";s:12:\"Testimonials\";}}s:4:\"home\";a:13:{s:5:\"about\";a:4:{s:7:\"body_p1\";s:283:\"SumitZ Clinic is Pune\'s leading multidisciplinary healthcare center specializing in orthopaedic consultation, joint replacement, sports injury rehabilitation, and advanced physiotherapy. Led by Dr. Sumit, our team provides compassionate, evidence-based care tailored to each patient.\";s:7:\"body_p2\";s:207:\"With state-of-the-art digital diagnostics, modern physiotherapy equipment, and two convenient clinics in Baner and Hinjewadi, we empower our patients to regain pain-free mobility and return to active living.\";s:9:\"pre_title\";s:19:\"About SumitZ Clinic\";s:5:\"title\";s:49:\"Pune\'s Premier Orthopaedic & Physiotherapy Center\";}s:11:\"appointment\";a:2:{s:7:\"heading\";N;s:8:\"subtitle\";N;}s:3:\"cta\";a:3:{s:7:\"heading\";N;s:8:\"subtitle\";s:80:\"Book your specialist consultation or physiotherapy session with Dr. Sumit today.\";s:5:\"title\";s:44:\"Take the First Step Towards a Pain-Free Life\";}s:10:\"facilities\";a:3:{s:7:\"heading\";N;s:8:\"subtitle\";s:118:\"Equipped with high-precision digital X-ray, modern rehab equipment, and sterile procedure suites in Baner & Hinjewadi.\";s:5:\"title\";s:29:\"World-Class Clinic Facilities\";}s:3:\"faq\";a:1:{s:7:\"heading\";N;}s:6:\"finder\";a:2:{s:5:\"badge\";N;s:7:\"heading\";N;}s:4:\"hero\";a:9:{s:7:\"eyebrow\";N;s:8:\"subtitle\";s:177:\"Specialist consultations, advanced physiotherapy, digital diagnostics, and personalized rehabilitation programs designed to restore your natural mobility in Baner and Hinjewadi.\";s:5:\"title\";s:58:\"Move Better. Recover Stronger. Live Without Limiting Pain.\";s:13:\"trust_1_label\";N;s:11:\"trust_1_num\";N;s:13:\"trust_2_label\";N;s:11:\"trust_2_num\";N;s:13:\"trust_3_label\";N;s:11:\"trust_3_num\";N;}s:9:\"locations\";a:2:{s:7:\"heading\";N;s:8:\"subtitle\";N;}s:7:\"process\";a:2:{s:7:\"heading\";N;s:8:\"subtitle\";N;}s:8:\"services\";a:2:{s:8:\"subtitle\";s:177:\"We act as your dedicated healthcare partner to match you with expert orthopedic specialists and personalized treatment plans. Here are the tailor-made care solutions we provide:\";s:5:\"title\";s:47:\"Comprehensive Orthopedic Services in Baner Pune\";}s:12:\"testimonials\";a:2:{s:7:\"heading\";N;s:8:\"subtitle\";N;}s:10:\"treatments\";a:2:{s:7:\"heading\";N;s:8:\"subtitle\";N;}s:13:\"why_choose_us\";a:2:{s:8:\"subtitle\";s:107:\"Our systematic medical evaluation ensures accurate diagnosis, targeted therapy, and long-term joint health.\";s:5:\"title\";s:38:\"4-Step Fast Mobility Recovery Protocol\";}}}',1791290363),('tenderfold-cache-global_site_parameters','a:75:{s:7:\"address\";s:48:\"Sector 49 & Sector 83, Gurgaon, Haryana - 122018\";s:13:\"address_baner\";s:49:\"First Floor, Krishna Avenue, Baner, Pune - 411045\";s:17:\"address_hinjewadi\";s:41:\"Atlanta Society, Hinjewadi, Pune - 411057\";s:8:\"ai_model\";s:16:\"gemini-2.5-flash\";s:18:\"business_loan_rate\";s:5:\"12.0%\";s:13:\"car_loan_rate\";s:4:\"8.7%\";s:21:\"chat_assistant_avatar\";N;s:19:\"chat_assistant_name\";s:25:\"Tenderfold Care Assistant\";s:22:\"clinic_locations_short\";s:23:\"Baner & Hinjewadi, Pune\";s:14:\"clinic_timings\";s:50:\"Mon - Sat: 9:00 AM - 9:00 PM | Sun: By Appointment\";s:26:\"cloudflare_analytics_token\";N;s:20:\"cloudflare_api_token\";N;s:27:\"cloudflare_r2_access_key_id\";N;s:24:\"cloudflare_r2_account_id\";N;s:25:\"cloudflare_r2_bucket_name\";N;s:24:\"cloudflare_r2_public_url\";N;s:31:\"cloudflare_r2_secret_access_key\";N;s:18:\"cloudflare_zone_id\";N;s:14:\"copyright_text\";s:43:\"© 2026 SumitZ Clinic. All Rights Reserved.\";s:5:\"email\";s:18:\"info@tenderfold.in\";s:12:\"favicon_path\";s:15:\"assets/logo.png\";s:18:\"footer_description\";s:216:\"SumitZ Clinic is Pune\'s leading Orthopaedic and Physiotherapy recovery centre in Baner and Hinjewadi. Directed by Dr. Sumit, we offer patient-focused care for joint, spine, fracture, and sports injury rehabilitation.\";s:16:\"footer_helplines\";s:62:\"+91 8855950433, +91 9404963781, +91 9158143325, +91 9545559434\";s:14:\"gemini_api_key\";s:53:\"AQ.Ab8RN6Ibz3PhpB5IvZCJK4iiRWjt-RrZQ--WEMbwY6bA2Dgw2Q\";s:16:\"gmb_access_token\";N;s:15:\"gmb_location_id\";s:27:\"ChIJVVXZELW-wjsR55PO9Vgs1jU\";s:19:\"google_analytics_id\";N;s:28:\"google_analytics_property_id\";N;s:23:\"google_my_business_link\";N;s:22:\"google_oauth_client_id\";s:18:\"admin@drsumitz.com\";s:26:\"google_oauth_client_secret\";s:8:\"admin123\";s:30:\"google_search_console_site_url\";s:22:\"sc-domain:drsumitz.com\";s:13:\"google_tag_id\";N;s:14:\"header_scripts\";N;s:14:\"helpline_baner\";s:33:\"+91 95455 59434 / +91 88559 50433\";s:18:\"helpline_hinjewadi\";s:33:\"+91 94049 63781 / +91 91581 43325\";s:14:\"home_loan_rate\";s:4:\"8.4%\";s:14:\"index_about_bg\";s:7:\"#ffffff\";s:12:\"index_cta_bg\";s:49:\"linear-gradient(135deg, #0C2454 0%, #143572 100%)\";s:12:\"index_faq_bg\";s:7:\"#ffffff\";s:13:\"index_hero_bg\";s:7:\"#F4F7FB\";s:17:\"index_services_bg\";s:7:\"#ffffff\";s:21:\"index_testimonials_bg\";s:7:\"#F4F7FB\";s:12:\"index_why_bg\";s:7:\"#ffffff\";s:8:\"lap_rate\";s:4:\"9.0%\";s:13:\"live_site_url\";s:21:\"https://tenderfold.in\";s:9:\"logo_path\";s:15:\"assets/logo.png\";s:16:\"meta_description\";s:171:\"Tenderfold is a premium laundry and dry cleaner in Gurgaon. Doorstep pickup for wash & fold, steam ironing, dry cleaning and specialized fabric care. Call +91 95824 66445.\";s:13:\"meta_keywords\";s:133:\"laundry gurgaon, dry cleaning gurgaon, wash and fold, steam ironing, shoe cleaning, carpet cleaning, sector 49, sector 83, tenderfold\";s:13:\"og_image_path\";s:15:\"assets/logo.png\";s:18:\"personal_loan_rate\";s:5:\"10.5%\";s:5:\"phone\";s:15:\"+91 95824 66445\";s:13:\"phone_display\";s:15:\"+91 95824 66445\";s:17:\"recaptcha_enabled\";s:1:\"0\";s:20:\"recaptcha_secret_key\";N;s:18:\"recaptcha_site_key\";N;s:9:\"site_name\";s:10:\"Tenderfold\";s:12:\"site_tagline\";s:73:\"Freshness in Every Single Fold - Premium Laundry & Dry Cleaner in Gurgaon\";s:15:\"smtp_encryption\";s:3:\"tls\";s:17:\"smtp_from_address\";N;s:14:\"smtp_from_name\";N;s:9:\"smtp_host\";N;s:13:\"smtp_password\";N;s:9:\"smtp_port\";s:3:\"587\";s:13:\"smtp_to_email\";N;s:13:\"smtp_username\";N;s:15:\"social_facebook\";N;s:16:\"social_instagram\";s:35:\"https://www.instagram.com/drsumitz/\";s:15:\"social_linkedin\";N;s:13:\"social_practo\";N;s:14:\"social_twitter\";N;s:24:\"whatsapp_admin_recipient\";s:12:\"919582466445\";s:18:\"whatsapp_api_token\";N;s:16:\"whatsapp_api_url\";N;s:15:\"whatsapp_number\";s:12:\"919582466445\";}',1791290363);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender` enum('visitor','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visitor',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_session_id_index` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_rooms`
--

DROP TABLE IF EXISTS `chat_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `owner_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_rooms_owner_id_foreign` (`owner_id`),
  CONSTRAINT `chat_rooms_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_rooms`
--

LOCK TABLES `chat_rooms` WRITE;
/*!40000 ALTER TABLE `chat_rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_pages`
--

DROP TABLE IF EXISTS `cms_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cms_pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `metatitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadescription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metakeywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `schema_markup` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cms_pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_pages`
--

LOCK TABLES `cms_pages` WRITE;
/*!40000 ALTER TABLE `cms_pages` DISABLE KEYS */;
INSERT INTO `cms_pages` VALUES (1,'Tenderfold - Premium Laundry & Dry Cleaner in Gurgaon','home','Tenderfold | Freshness in Every Single Fold - Premium Laundry & Dry Cleaner in Gurgaon','Tenderfold is a premium laundry and dry cleaner in Gurgaon. Doorstep pickup for wash & fold, steam ironing, dry cleaning and specialized fabric care.',NULL,NULL,'<h1>Freshness in Every Single Fold</h1><p>Doorstep laundry, dry cleaning, steam pressing, shoe spa, and carpet care in Gurgaon.</p>',1,NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(2,'About Tenderfold Gurgaon','about-us','About Us | Tenderfold Gurgaon Fabric Care','Learn about Tenderfold laundry and dry cleaning philosophy, our plant facilities in Gurgaon, and our commitment to garment longevity.',NULL,NULL,'<h1>About Tenderfold</h1><p>We are a specialized fabric care service built around modern Italian machinery, skin-safe detergents, and prompt doorstep delivery across Gurgaon.</p>',1,NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(3,'All Laundry & Dry Cleaning Services','services','All Services | Tenderfold Gurgaon','Explore laundry, dry cleaning, steam pressing, shoe cleaning, curtain care, and carpet spa services in Gurgaon.',NULL,NULL,'<h1>Our Services</h1><p>From daily wash and fold to luxury couture dry cleaning, discover our full suite of fabric spa solutions.</p>',1,NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(4,'Pricing & Rate Card','pricing','Pricing & Rate Card | Tenderfold Gurgaon','View our complete transparent price list for wash & fold, steam pressing, dry cleaning, shoe cleaning, and carpet care.',NULL,NULL,'<h1>Transparent Laundry & Dry Cleaning Pricing</h1><p>Affordable per kg laundry and per piece dry cleaning rates with no hidden charges.</p>',1,NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(5,'Customer Reviews & Feedback','testimonials','Customer Reviews & Testimonials | Tenderfold Gurgaon','Read reviews and ratings from our customers in Sector 49, Sector 83, DLF, and across Gurgaon.',NULL,NULL,'<h1>What Our Gurgaon Customers Say</h1><p>Read genuine testimonials from satisfied households, professionals, and businesses across Gurgaon.</p>',1,NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(6,'Frequently Asked Questions','faqs','Frequently Asked Questions | Tenderfold Laundry Gurgaon','Find answers to common questions about Tenderfold doorstep pickup, dry cleaning turnaround, and specialized fabric care.',NULL,NULL,'<h1>Frequently Asked Questions</h1><p>Everything you need to know about pickup timings, turnaround, fabric safety, and order tracking.</p>',1,NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(7,'Contact Tenderfold Gurgaon','contact-us','Contact Tenderfold | Gurgaon Laundry & Dry Cleaning Counters','Call +91 95824 66445 or WhatsApp us for instant doorstep laundry collection in Gurgaon.',NULL,NULL,'<h1>Contact Us</h1><p>Visit our stores in Sector 49 & Sector 83 or call us for free doorstep collection.</p>',1,NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(8,'Privacy Policy','privacy-policy','Privacy Policy | Tenderfold Gurgaon','Tenderfold privacy policy regarding customer personal data and communication.',NULL,NULL,'<h1>Privacy Policy</h1><p>Tenderfold respects your personal privacy. We only use customer contact and address details for scheduling pickups, delivering orders, and sending service updates.</p>',1,NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(9,'Terms & Conditions','terms-and-conditions','Terms & Conditions | Tenderfold Gurgaon','Tenderfold terms and conditions of fabric care and doorstep delivery services.',NULL,NULL,'<h1>Terms & Conditions</h1><p>Standard service terms for garment inspection, pickup schedules, care instructions, and delivery turnaround.</p>',1,NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55');
/*!40000 ALTER TABLE `cms_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comparison_banks`
--

DROP TABLE IF EXISTS `comparison_banks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comparison_banks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `home_rate` double NOT NULL,
  `personal_rate` double NOT NULL,
  `business_rate` double NOT NULL,
  `fee_percent` double NOT NULL,
  `sector` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comparison_banks`
--

LOCK TABLES `comparison_banks` WRITE;
/*!40000 ALTER TABLE `comparison_banks` DISABLE KEYS */;
/*!40000 ALTER TABLE `comparison_banks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_fields`
--

DROP TABLE IF EXISTS `form_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_fields` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `form_id` bigint unsigned NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `placeholder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `validation_rules` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `options` json DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_fields_form_id_foreign` (`form_id`),
  CONSTRAINT `form_fields_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_fields`
--

LOCK TABLES `form_fields` WRITE;
/*!40000 ALTER TABLE `form_fields` DISABLE KEYS */;
INSERT INTO `form_fields` VALUES (1,1,'Email Address','email','email','name@company.com',0,NULL,'[]',0,'2026-07-23 23:56:16','2026-07-23 23:56:16'),(2,1,'Phone','phone','tel','98765-43210',1,NULL,'[]',1,'2026-07-23 23:56:16','2026-07-23 23:56:16');
/*!40000 ALTER TABLE `form_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_submissions`
--

DROP TABLE IF EXISTS `form_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `form_id` bigint unsigned NOT NULL,
  `data` json NOT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `utm_source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_medium` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_campaign` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_submissions_form_id_foreign` (`form_id`),
  CONSTRAINT `form_submissions_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_submissions`
--

LOCK TABLES `form_submissions` WRITE;
/*!40000 ALTER TABLE `form_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forms`
--

DROP TABLE IF EXISTS `forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submit_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Submit',
  `success_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `redirect_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_notifications` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spam_protection` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forms`
--

LOCK TABLES `forms` WRITE;
/*!40000 ALTER TABLE `forms` DISABLE KEYS */;
INSERT INTO `forms` VALUES (1,'Get Your Audit Today','Complete Digital Audit for your brand','Submit','Thank you! Your submission has been received.',NULL,'rahul@smacdigital.com',1,'2026-07-23 23:56:16','2026-07-23 23:56:16');
/*!40000 ALTER TABLE `forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `home_slides`
--

DROP TABLE IF EXISTS `home_slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `home_slides` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eyebrow` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_2_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_2_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trust_1_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trust_1_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trust_2_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trust_2_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trust_3_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trust_3_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `home_slides`
--

LOCK TABLES `home_slides` WRITE;
/*!40000 ALTER TABLE `home_slides` DISABLE KEYS */;
INSERT INTO `home_slides` VALUES (1,'assets/hero.jpg',NULL,NULL,'Freshness in Every Single Fold.','Gurgaon\'s trusted premium laundry, dry cleaning & specialized fabric spa. Doorstep pickup across all major sectors.','Book Free Pickup','#bookModal',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(2,'assets/shirts.jpg',NULL,NULL,'Eco-Friendly Dry Cleaning & Couture Care.','Zero harsh chemicals. Expert care for designer wear, silk, woolens, curtains, carpets & sneakers.','Explore Services','/services',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(3,'assets/whiteshirt.jpg',NULL,NULL,'Crisp Steam Pressing on Vacuum Tables.','Sharp creases without fabric stress or shine marks. Delivered fresh on hangers or in boutique packing.','View Rate Card','/pricing',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'2026-10-06 06:17:55','2026-10-06 06:17:55');
/*!40000 ALTER TABLE `home_slides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `landing_pages`
--

DROP TABLE IF EXISTS `landing_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `landing_pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `layout_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `content` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `schema_markup` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `landing_pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `landing_pages`
--

LOCK TABLES `landing_pages` WRITE;
/*!40000 ALTER TABLE `landing_pages` DISABLE KEYS */;
INSERT INTO `landing_pages` VALUES (1,'Doorstep Laundry Pickup in Gurgaon | 20% Off First Order','doorstep-laundry-pickup-gurgaon','home','Get fresh, hygienic wash & fold and steam ironing collected right from your doorstep in Gurgaon. Special 20% discount on first booking.','{\"faqs\": [{\"a\": \"Simply submit the form or click our WhatsApp link. Our delivery partner will arrive at your selected slot.\", \"q\": \"How do I schedule a pickup in Gurgaon?\"}, {\"a\": \"Our wash & fold service starts at just ₹80/kg, and wash & steam iron starts at ₹115/kg.\", \"q\": \"What is the rate for everyday laundry?\"}, {\"a\": \"There is a nominal 3 kg minimum order for free doorstep collection.\", \"q\": \"Is there a minimum order quantity?\"}], \"cta_text\": \"Book your doorstep laundry collection in Gurgaon today and enjoy 20% off your initial order.\", \"features\": [{\"desc\": \"Scheduled pickup and delivery across DLF, Golf Course Rd, Sohna Rd, and all Gurgaon sectors.\", \"icon\": \"truck\", \"title\": \"Free Doorstep Collection\"}, {\"desc\": \"Your garments are processed in their own dedicated washing and drying machines.\", \"icon\": \"layers\", \"title\": \"100% Segregated Batch Washing\"}, {\"desc\": \"Non-toxic, hypoallergenic German detergents that preserve fibers and colors.\", \"icon\": \"sparkles\", \"title\": \"Skin-Safe Eco Detergents\"}, {\"desc\": \"Italian vacuum suction tables ensure crisp, wrinkle-free finishing with zero fabric shine.\", \"icon\": \"award\", \"title\": \"Precision Vacuum Steam Ironing\"}], \"cta_title\": \"Experience Freshness in Every Single Fold\", \"about_text\": \"Tenderfold delivers 5-star garment care directly to your apartment or home in Gurgaon. We sort by fabric type, wash in 100% softened water with skin-safe eco detergents, and return your clothes folded, fresh, and ready for your wardrobe.\", \"about_title\": \"Gurgaon\'s Most Reliable Doorstep Laundry Service\"}','2026-10-06 06:17:55','2026-10-06 06:17:55',NULL),(2,'Eco Dry Cleaning & Couture Fabric Spa in Gurgaon','express-dry-cleaning-gurgaon','service','Premium dry cleaning for designer lehengas, silk sarees, bespoke suits, and winter cashmere in Gurgaon. Zero harsh chemicals.','{\"cta_text\": \"Schedule a free dry cleaning doorstep collection across Gurgaon today.\", \"cta_title\": \"Restore Your Wardrobe With Tenderfold\", \"process_steps\": [{\"desc\": \"We check care labels, colorfastness, and spot-clean food and oil stains by hand.\", \"title\": \"Pre-Inspection & Spot Testing\"}, {\"desc\": \"Delicate closed-loop wash cycle in temperature-controlled Italian machines.\", \"title\": \"Hydrocarbon Eco Cleaning\"}, {\"desc\": \"Padded vacuum suction tables restore sharp creases and natural garment volume.\", \"title\": \"Vacuum Steam Pressing\"}, {\"desc\": \"Delivered in premium breathable garment bags on contoured hangers.\", \"title\": \"Boutique Hanger Packaging\"}], \"process_title\": \"The 4-Step Couture Cleaning Protocol\", \"service_bullets\": [{\"desc\": \"Gentle solvent bath preserves sequins, stone work, and fine metallic threads.\", \"icon\": \"shield-check\", \"title\": \"Safe for Heavy Embellishments\"}, {\"desc\": \"Zero chemical smell or stiff fabric texture after cleaning.\", \"icon\": \"sparkles\", \"title\": \"Odorless Hydrocarbon Process\"}, {\"desc\": \"Prompt inspection and delivery in breathable fabric garment bags.\", \"icon\": \"clock\", \"title\": \"Express 48h Turnaround\"}], \"service_details_text\": \"Our closed-loop hydrocarbon dry cleaning process protects delicate zari work, bead embroidery, pure silks, and tailored woolens. Every item is hand-inspected for stains and finished with Italian dry steam.\", \"service_details_title\": \"Specialized Dry Cleaning for Delicate & Ethnic Couture\"}','2026-10-06 06:17:55','2026-10-06 06:17:55',NULL),(3,'Premium Shoe Laundry & Luxury Sneaker Spa in Gurgaon','designer-shoe-spa-gurgaon','specialist','Restore white sneakers, leather boots, and suede loafers. Professional cleaning, sole un-yellowing, and deodorization in Gurgaon.','{\"cta_text\": \"Schedule a sneaker & footwear spa pickup in Gurgaon now.\", \"cta_title\": \"Step Out With Confidence\", \"specialist_bio_text\": \"Our dedicated shoe spa craftsmen clean, condition, and deodorize every pair by hand using pH-neutral solutions, horsehair brushes, and UV ozone sterilization.\", \"specialist_bio_title\": \"Artisanal Footwear Care & Sneaker Restoration\", \"specialist_qualifications\": [{\"desc\": \"Dry foam cleaning and brass brushing to restore soft suede texture.\", \"title\": \"Suede & Nubuck Re-Napping Mastery\"}, {\"desc\": \"Safe chemical treatments to bring aged yellow boost soles back to pure white.\", \"title\": \"Sole Oxidation Reversal & De-Yellowing\"}, {\"desc\": \"Kills 99.9% of odor-causing bacteria inside athletic footwear and leather shoes.\", \"title\": \"UV Ozone Anti-Bacterial Sanitization\"}]}','2026-10-06 06:17:55','2026-10-06 06:17:55',NULL);
/*!40000 ALTER TABLE `landing_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lead_activities`
--

DROP TABLE IF EXISTS `lead_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lead_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint unsigned NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin',
  `action_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'status_update',
  `status_from` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lead_activities_lead_id_foreign` (`lead_id`),
  CONSTRAINT `lead_activities_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lead_activities`
--

LOCK TABLES `lead_activities` WRITE;
/*!40000 ALTER TABLE `lead_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `lead_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Contact Form',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'New',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads`
--

LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'India',
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `locality` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `geo_coordinates` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `market_summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `search_query_examples` json DEFAULT NULL,
  `priority_specialities` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `locations_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'India','Maharashtra','Pune',NULL,'Pune','pune',NULL,'Pune has a rapidly growing private medical sector, with prominent medical hubs in Baner, Kothrud, and Viman Nagar. Clinicians face competitive search battles to be in the local Google Map 3-pack.','[\"dermatologist near me\", \"best dental clinic in baner\", \"orthopedic doctor in kothrud\", \"physiotherapist open now in wakad\"]','[\"dentists\", \"dermatologists\", \"gynaecologists\", \"paediatricians\", \"physiotherapists\"]','2026-08-02 09:08:08','2026-08-02 09:08:08'),(2,'India','Rajasthan','Jaipur',NULL,'Jaipur','jaipur',NULL,'In Jaipur, search volume peaks for renowned specialist clinics in Vaishali Nagar, Malviya Nagar, and Mansarovar. Digital authority and Google review scores determine patient footfall.','[\"best pediatric doctor in vaishali nagar\", \"gynaecologist cost in jaipur\", \"eye clinic malviya nagar\"]','[\"dentists\", \"dermatologists\", \"gynaecologists\", \"ophthalmologists\"]','2026-08-02 09:08:08','2026-08-02 09:08:08'),(3,'India','Delhi NCR','Delhi',NULL,'Delhi','delhi',NULL,'Delhi operates as highly localized patient catchments (3-7 km radii). A clinic in Saket competes strictly with other Saket/GK clinics, making localized schema markup critical.','[\"fertility clinic in south delhi\", \"best dentist in dwarka\", \"diagnostic centre in rohini\"]','[\"dentists\", \"dermatologists\", \"gynaecologists\", \"paediatricians\", \"fertility-clinics\", \"diagnostic-centres\"]','2026-08-02 09:08:08','2026-08-02 09:08:08'),(4,'India','West Bengal','Kolkata',NULL,'Kolkata','kolkata',NULL,'Clinics in Salt Lake, New Town, and Ballygunge require strong localized treatment pages. Patients actively look for specific specialists, treatments, and localities concurrently.','[\"multispeciality clinic new town\", \"physiotherapist in salt lake\", \"best gynecologist in ballygunge\"]','[\"dentists\", \"gynaecologists\", \"physiotherapists\", \"multispeciality-clinics\"]','2026-08-02 09:08:08','2026-08-02 09:08:08');
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `microsite_pages`
--

DROP TABLE IF EXISTS `microsite_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `microsite_pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `microsite_id` bigint unsigned DEFAULT NULL,
  `page_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'custom',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_page_id` bigint unsigned DEFAULT NULL,
  `template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `sections` json DEFAULT NULL,
  `seo` json DEFAULT NULL,
  `schema_markup` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `canonical_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `noindex` tinyint(1) NOT NULL DEFAULT '0',
  `city_id` bigint unsigned DEFAULT NULL,
  `speciality_id` bigint unsigned DEFAULT NULL,
  `featured_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publish_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `microsite_pages_microsite_id_slug_unique` (`microsite_id`,`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `microsite_pages`
--

LOCK TABLES `microsite_pages` WRITE;
/*!40000 ALTER TABLE `microsite_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `microsite_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `microsites`
--

DROP TABLE IF EXISTS `microsites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `microsites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `domain_base_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/healthcare',
  `header_config` json DEFAULT NULL,
  `footer_config` json DEFAULT NULL,
  `theme` json DEFAULT NULL,
  `default_seo_settings` json DEFAULT NULL,
  `analytics_settings` json DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `microsites_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `microsites`
--

LOCK TABLES `microsites` WRITE;
/*!40000 ALTER TABLE `microsites` DISABLE KEYS */;
/*!40000 ALTER TABLE `microsites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_06_26_021700_create_site_tables',1),(5,'2026_06_26_023519_create_blogs_table',1),(6,'2026_06_26_130212_create_comparison_banks_table',1),(7,'2026_06_26_130212_create_services_table',1),(8,'2026_07_03_112913_create_leads_table',2),(9,'2026_07_08_144806_create_landing_pages_table',3),(10,'2026_07_09_141338_add_schema_markup_to_tables',3),(11,'2026_07_14_131858_create_treatments_and_team_members_tables',3),(12,'2026_07_14_132036_create_cms_pages_table',4),(13,'2026_07_14_132153_add_image_and_icon_to_services_table',5),(14,'2026_07_15_120000_create_chat_messages_table',6),(15,'2026_07_17_072513_add_role_and_permissions_to_users_table',7),(16,'2026_07_17_081045_create_notifications_tables',8),(17,'2026_08_12_000000_create_lead_activities_table',9),(18,'2026_08_13_000000_create_admin_notifications_table',10),(19,'2026_08_25_000000_create_social_links_table',11),(20,'2026_09_18_000000_add_mobile_image_path_to_home_slides_table',12),(21,'2026_09_18_100000_add_content_fields_to_home_slides_table',13);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offers`
--

DROP TABLE IF EXISTS `offers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `package_id` bigint unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `offers_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offers`
--

LOCK TABLES `offers` WRITE;
/*!40000 ALTER TABLE `offers` DISABLE KEYS */;
INSERT INTO `offers` VALUES (1,'FREE100',100.00,1,'100% discount on Self Managed System package','active','2026-08-02 10:13:50','2026-08-02 10:13:50');
/*!40000 ALTER TABLE `offers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_contents`
--

DROP TABLE IF EXISTS `page_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_contents` (
  `page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`page`,`section`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_contents`
--

LOCK TABLES `page_contents` WRITE;
/*!40000 ALTER TABLE `page_contents` DISABLE KEYS */;
INSERT INTO `page_contents` VALUES ('about','hero','pre_title','WHO WE ARE','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','hero','subtitle','We are passionate about fabric longevity, environmental safety, and convenient doorstep laundry in Gurgaon.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','hero','title','Crafting Freshness, Preserving Fabrics','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','mission','text','To provide eco-friendly, hypoallergenic garment cleaning with transparent per-kilo and per-piece pricing, prompt turnaround, and exceptional customer support.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','mission','title','Our Mission','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','story','p1','Tenderfold began with a simple belief: laundry should be more than a chore — it should be a reliable care routine that protects the investment you make in your wardrobe.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','story','p2','By replacing harsh industrial chemicals with gentle eco solvents, and domestic irons with professional vacuum steam tables, we ensure every garment returns with pristine freshness.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','story','p3','Today, our counters in Orchid Petals (Sector 49) and Sapphire 83 Mall (Sector 83) serve thousands of families and professionals with free doorstep pickup.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','story','pre_title','Our Story','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','story','title','Redefining Wardrobe Care in Gurgaon','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','values','text','Absolute hygiene, fiber-safe cleaning, environmental responsibility, and punctual service.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','values','title','Our Core Values','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','vision','text','To become the most trusted fabric care and laundry brand in Northern India by prioritizing hygiene, fabric safety, and customer convenience.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('about','vision','title','Our Vision','2026-10-06 06:17:55','2026-10-06 06:17:55'),('footer','about','description','Tenderfold is Gurgaon\'s trusted premium laundry, dry cleaning and specialized fabric spa. Punctual doorstep pickup and delivery across all major sectors.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('footer','bottom','copyright','All Rights Reserved.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('footer','bottom','disclaimer','Tenderfold provides professional textile and garment care under strict manufacturer guidelines.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('footer','headings','contact','Contact & Hubs','2026-10-06 06:17:55','2026-10-06 06:17:55'),('footer','headings','quick_links','Quick Links','2026-10-06 06:17:55','2026-10-06 06:17:55'),('footer','headings','services','Our Services','2026-10-06 06:17:55','2026-10-06 06:17:55'),('header','cta','button_text','Schedule a Pickup','2026-10-06 06:17:55','2026-10-06 06:17:55'),('header','menu','about','About Us','2026-10-06 06:17:55','2026-10-06 06:17:55'),('header','menu','blog','Care Journal','2026-10-06 06:17:55','2026-10-06 06:17:55'),('header','menu','contact','Contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('header','menu','cta','Call Now','2026-10-06 06:17:55','2026-10-06 06:17:55'),('header','menu','home','Home','2026-10-06 06:17:55','2026-10-06 06:17:55'),('header','menu','pricing','Pricing','2026-10-06 06:17:55','2026-10-06 06:17:55'),('header','menu','services','Services','2026-10-06 06:17:55','2026-10-06 06:17:55'),('header','menu','testimonials','Reviews','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','about','body_p1','Tenderfold is dedicated to elevating everyday laundry and dry cleaning into a meticulous garment care experience. Operating from physical stores in Sector 49 (Orchid Petals) and Sector 83 (Sapphire 83 Mall), we serve households across Gurgaon.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','about','body_p2','From everyday wash & fold to luxury silk preservation, sneaker renewal, and carpet shampooing, our team combines German eco-detergents, Italian vacuum steam finishing, and punctual doorstep pickup.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','about','pre_title','About Tenderfold','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','about','title','Gurgaon\'s Premier Fabric Care & Garment Spa','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','cta','heading','20% Off Your First Order','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','cta','subtitle','Schedule a free doorstep pickup in Gurgaon and enjoy pristine, fresh laundry delivered right to your wardrobe.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','cta','title','Experience Tenderfold Care Today','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','hero','eyebrow','Premium Laundry & Dry Cleaner in Gurgaon','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','hero','subtitle','Gurgaon\'s trusted premium laundry, dry cleaning & specialized fabric spa. Free doorstep pickup across all major sectors.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','hero','title','Freshness in Every Single Fold.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','hero','trust_1_label','Garments Delivered','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','hero','trust_1_num','50,000+','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','hero','trust_2_label','Eco-Friendly Solvents','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','hero','trust_2_num','100%','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','hero','trust_3_label','Gurgaon Hubs','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','hero','trust_3_num','Sector 49 & 83','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','services','subtitle','Explore our full spectrum of specialized laundry, couture dry cleaning, and home fabric care services.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','services','title','Comprehensive Garment & Home Fabric Care','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','why_choose_us','subtitle','Our systematic textile evaluation ensures hygienic washing, zero fiber shrinkage, and sharp steam finishing.','2026-10-06 06:17:55','2026-10-06 06:17:55'),('home','why_choose_us','title','4-Step Quality Assurance Routine','2026-10-06 06:17:55','2026-10-06 06:17:55');
/*!40000 ALTER TABLE `page_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_versions`
--

DROP TABLE IF EXISTS `page_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sections` json DEFAULT NULL,
  `seo` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_versions_page_id_foreign` (`page_id`),
  KEY `page_versions_user_id_foreign` (`user_id`),
  CONSTRAINT `page_versions_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `page_versions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_versions`
--

LOCK TABLES `page_versions` WRITE;
/*!40000 ALTER TABLE `page_versions` DISABLE KEYS */;
INSERT INTO `page_versions` VALUES (1,8,NULL,'Home Page','[]',NULL,'2026-07-24 00:14:22','2026-07-24 00:14:22'),(2,4,NULL,'Testimonials','[{\"id\": \"hero_init\", \"style\": {\"backgroundToken\": \"bg-dark\"}, \"content\": {\"eyebrow\": \"Testimonials\", \"heading\": \"Welcome to the Testimonials Page\", \"primaryCta\": {\"url\": \"/admin/pages\", \"label\": \"Customize Page Layout\"}, \"description\": \"Use the admin panel layout editor to generate custom copy, modify typography styling, or insert section modules.\"}, \"variant\": \"split_image\", \"settings\": {\"height\": \"large\"}, \"sectionType\": \"hero\"}]',NULL,'2026-07-24 00:22:05','2026-07-24 00:22:05');
/*!40000 ALTER TABLE `page_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'custom',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `theme_id` bigint unsigned DEFAULT NULL,
  `header_id` bigint unsigned DEFAULT NULL,
  `footer_id` bigint unsigned DEFAULT NULL,
  `seo` json DEFAULT NULL,
  `sections` json DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_theme_id_foreign` (`theme_id`),
  CONSTRAINT `pages_theme_id_foreign` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'homepage','Home Page','home','published',NULL,NULL,NULL,NULL,'[{\"id\": \"hero_default\", \"style\": {\"backgroundToken\": \"bg-dark\"}, \"content\": {\"eyebrow\": \"SaaS Layout Customizer\", \"heading\": \"Create Your Web Layout with AI\", \"primaryCta\": {\"url\": \"/admin\", \"label\": \"Go to Admin Dashboard\"}, \"description\": \"Go to the admin area to customize color tokens, generate landing pages using AI, and compose visual sections.\"}, \"variant\": \"split_image\", \"settings\": {\"height\": \"large\"}, \"sectionType\": \"hero\"}]',NULL,NULL,'2026-07-23 20:41:47','2026-07-23 20:41:47'),(2,'about','About Us','about-us','published',NULL,NULL,NULL,NULL,'[{\"id\": \"hero_init\", \"style\": {\"backgroundToken\": \"bg-dark\"}, \"content\": {\"eyebrow\": \"About Us\", \"heading\": \"Welcome to the About Us Page\", \"primaryCta\": {\"url\": \"/admin/pages\", \"label\": \"Customize Page Layout\"}, \"description\": \"Use the admin panel layout editor to generate custom copy, modify typography styling, or insert section modules.\"}, \"variant\": \"split_image\", \"settings\": {\"height\": \"large\"}, \"sectionType\": \"hero\"}]',NULL,NULL,'2026-07-23 20:49:22','2026-07-23 20:49:22'),(3,'case_study','Case Studies','case-studies','published',NULL,NULL,NULL,NULL,'[{\"id\": \"hero_init\", \"style\": {\"backgroundToken\": \"bg-dark\"}, \"content\": {\"eyebrow\": \"Case Studies\", \"heading\": \"Welcome to the Case Studies Page\", \"primaryCta\": {\"url\": \"/admin/pages\", \"label\": \"Customize Page Layout\"}, \"description\": \"Use the admin panel layout editor to generate custom copy, modify typography styling, or insert section modules.\"}, \"variant\": \"split_image\", \"settings\": {\"height\": \"large\"}, \"sectionType\": \"hero\"}]',NULL,NULL,'2026-07-23 20:49:30','2026-07-23 20:49:30'),(4,'portfolio','Testimonials','testimonials','published',2,NULL,NULL,'{\"seo_title\": null, \"meta_description\": null}','[{\"id\": \"hero_init\", \"style\": {\"backgroundToken\": \"bg-dark\"}, \"content\": {\"eyebrow\": \"Testimonials\", \"heading\": \"Welcome to the Testimonials Page\", \"primaryCta\": {\"url\": \"/admin/pages\", \"label\": \"Customize Page Layout\"}, \"description\": \"Use the admin panel layout editor to generate custom copy, modify typography styling, or insert section modules.\"}, \"variant\": \"split_image\", \"settings\": {\"height\": \"large\"}, \"sectionType\": \"hero\"}, {\"id\": \"sect_d900r1tzm\", \"name\": \"Client Testimonials\", \"style\": {\"textColorToken\": \"text-primary\", \"backgroundToken\": \"surface-primary\"}, \"content\": {\"heading\": \"What Our Clients Say\", \"subheading\": \"Trusted by founders and clinical directors globally.\"}, \"variant\": \"slider\", \"settings\": {\"dynamic_source\": \"latest\"}, \"description\": \"Carousel or grid of reviews and ratings.\", \"sectionType\": \"testimonials\"}, {\"id\": \"sect_buvgvfypg\", \"name\": \"Case Studies Showcase\", \"style\": {\"textColorToken\": \"text-primary\", \"backgroundToken\": \"surface-primary\"}, \"content\": {\"heading\": \"Featured Case Studies\", \"subheading\": \"Proven Results & Success Stories\"}, \"variant\": \"grid\", \"settings\": {\"limit\": 3, \"dynamic_source\": \"latest\"}, \"description\": \"Showcases featured work, clinical results, or client case studies.\", \"sectionType\": \"case_studies_showcase\"}]','2026-07-24 00:22:08',NULL,'2026-07-23 20:49:32','2026-07-24 00:22:08'),(5,'blog','Blogs','blogs','published',NULL,NULL,NULL,NULL,'[{\"id\": \"hero_init\", \"style\": {\"backgroundToken\": \"bg-dark\"}, \"content\": {\"eyebrow\": \"Blogs\", \"heading\": \"Welcome to the Blogs Page\", \"primaryCta\": {\"url\": \"/admin/pages\", \"label\": \"Customize Page Layout\"}, \"description\": \"Use the admin panel layout editor to generate custom copy, modify typography styling, or insert section modules.\"}, \"variant\": \"split_image\", \"settings\": {\"height\": \"large\"}, \"sectionType\": \"hero\"}]',NULL,NULL,'2026-07-23 20:49:34','2026-07-23 20:49:34'),(6,'custom','FAQs','faqs','published',NULL,NULL,NULL,NULL,'[{\"id\": \"hero_init\", \"style\": {\"backgroundToken\": \"bg-dark\"}, \"content\": {\"eyebrow\": \"FAQs\", \"heading\": \"Welcome to the FAQs Page\", \"primaryCta\": {\"url\": \"/admin/pages\", \"label\": \"Customize Page Layout\"}, \"description\": \"Use the admin panel layout editor to generate custom copy, modify typography styling, or insert section modules.\"}, \"variant\": \"split_image\", \"settings\": {\"height\": \"large\"}, \"sectionType\": \"hero\"}]',NULL,NULL,'2026-07-23 20:49:36','2026-07-23 20:49:36'),(7,'contact','Contact Us','contact-us','published',NULL,NULL,NULL,NULL,'[{\"id\": \"hero_init\", \"style\": {\"backgroundToken\": \"bg-dark\"}, \"content\": {\"eyebrow\": \"Contact Us\", \"heading\": \"Welcome to the Contact Us Page\", \"primaryCta\": {\"url\": \"/admin/pages\", \"label\": \"Customize Page Layout\"}, \"description\": \"Use the admin panel layout editor to generate custom copy, modify typography styling, or insert section modules.\"}, \"variant\": \"split_image\", \"settings\": {\"height\": \"large\"}, \"sectionType\": \"hero\"}]','2026-07-24 00:12:04',NULL,'2026-07-23 20:49:42','2026-07-24 00:12:04'),(8,'homepage','Home Page','home-page','published',NULL,NULL,NULL,'{\"seo_title\": null, \"meta_description\": null}','[{\"id\": \"sect_4wsyfpxhi\", \"name\": \"Hero Section\", \"style\": {\"textColorToken\": \"text-primary\", \"backgroundToken\": \"surface-primary\"}, \"content\": {\"eyebrow\": \"Leading Digital Agency\", \"heading\": \"Innovative Solutions for Modern Brands\", \"media_url\": \"/assets/images/placeholder.webp\", \"primaryCta\": {\"url\": \"/contact\", \"label\": \"Get Started\"}, \"description\": \"We build high-performance products, search engine strategies, and creative experiences that drive tangible business growth.\", \"secondaryCta\": {\"url\": \"/case-studies\", \"label\": \"Our Work\"}}, \"variant\": \"split_image\", \"settings\": {\"height\": \"large\"}, \"description\": \"Large header with heading, description, CTAs and media.\", \"sectionType\": \"hero\"}, {\"id\": \"sect_maebs143d\", \"name\": \"Services Showcase\", \"style\": {\"textColorToken\": \"text-primary\", \"backgroundToken\": \"surface-primary\"}, \"content\": {\"heading\": \"Our Expertise & Capabilities\", \"subheading\": \"Engineered for Performance\"}, \"variant\": \"icon_grid\", \"settings\": {\"limit\": 6, \"dynamic_source\": \"latest\"}, \"description\": \"Icons grid showing agency capabilities and core services.\", \"sectionType\": \"services_slider\"}, {\"id\": \"sect_hyq7ostjd\", \"name\": \"Case Studies Showcase\", \"style\": {\"textColorToken\": \"text-primary\", \"backgroundToken\": \"surface-primary\"}, \"content\": {\"heading\": \"Featured Case Studies\", \"subheading\": \"Proven Results & Success Stories\"}, \"variant\": \"grid\", \"settings\": {\"limit\": 3, \"dynamic_source\": \"latest\"}, \"description\": \"Showcases featured work, clinical results, or client case studies.\", \"sectionType\": \"case_studies_showcase\"}, {\"id\": \"sect_f4sndzaqa\", \"name\": \"Video & Reels Showcase\", \"style\": {\"textColorToken\": \"text-primary\", \"backgroundToken\": \"surface-primary\"}, \"content\": {\"heading\": \"Client Recovery Highlights\", \"subheading\": \"Real patients, real recoveries, real transformations.\", \"videos_list\": [{\"url\": \"https://www.youtube.com/watch?v=dQw4w9WgXcQ\", \"title\": \"Orthopedic Patient Interview\", \"thumbnail\": \"/assets/images/video-thumb.webp\"}]}, \"variant\": \"playlist\", \"settings\": [], \"description\": \"Video highlights, testimonials, or clinic tour reels.\", \"sectionType\": \"video_gallery\"}, {\"id\": \"sect_kz78yacbl\", \"name\": \"Call to Action Banner\", \"style\": {\"textColorToken\": \"text-primary\", \"backgroundToken\": \"surface-primary\"}, \"content\": {\"heading\": \"Ready to Transform Your Digital Strategy?\", \"primaryCta\": {\"url\": \"/get-a-quote\", \"label\": \"Book Assessment Now\"}, \"description\": \"Connect with our engineering specialists today for a free strategic consult.\"}, \"variant\": \"simple_banner\", \"settings\": [], \"description\": \"Banner with heading, button, and dynamic styling.\", \"sectionType\": \"cta\"}, {\"id\": \"sect_0g3yb5ska\", \"name\": \"Client Testimonials\", \"style\": {\"textColorToken\": \"text-primary\", \"backgroundToken\": \"surface-primary\"}, \"content\": {\"heading\": \"What Our Clients Say\", \"subheading\": \"Trusted by founders and clinical directors globally.\"}, \"variant\": \"slider\", \"settings\": {\"dynamic_source\": \"latest\"}, \"description\": \"Carousel or grid of reviews and ratings.\", \"sectionType\": \"testimonials\"}, {\"id\": \"sect_4jmvbi8do\", \"name\": \"Lead Capture Form\", \"style\": {\"textColorToken\": \"text-primary\", \"backgroundToken\": \"surface-primary\"}, \"content\": {\"heading\": \"Schedule an Assessment\", \"subheading\": \"Fill out the details below to request a callback.\"}, \"variant\": \"inline_form\", \"settings\": {\"form_id\": null}, \"description\": \"Custom integrated submission form widget.\", \"sectionType\": \"lead_form\"}]','2026-07-24 00:14:25',NULL,'2026-07-23 21:08:50','2026-07-24 00:14:25');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolios`
--

DROP TABLE IF EXISTS `portfolios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolios`
--

LOCK TABLES `portfolios` WRITE;
/*!40000 ALTER TABLE `portfolios` DISABLE KEYS */;
/*!40000 ALTER TABLE `portfolios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pricing_packages`
--

DROP TABLE IF EXISTS `pricing_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pricing_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `monthly_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `setup_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `features` json DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pricing_packages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pricing_packages`
--

LOCK TABLES `pricing_packages` WRITE;
/*!40000 ALTER TABLE `pricing_packages` DISABLE KEYS */;
INSERT INTO `pricing_packages` VALUES (1,'Self Managed System','self-managed',2500.00,0.00,'[\"AI Blog Generator (5 articles/mo)\", \"Google Reviews Sync\", \"Standard SEO schema templates\", \"Mobile-Optimized Clinic Design\"]','active','2026-08-02 10:13:50','2026-08-02 10:13:50'),(2,'Expert Onboarding','expert-onboarding',0.00,20000.00,'[\"Complete Website Setup\", \"Custom Branding & Icons mapping\", \"GMB listing integration & sync\", \"1-on-1 Admin Training Session\"]','active','2026-08-02 10:13:50','2026-08-02 10:13:50'),(3,'Developer Managed & Maintenance','developer-managed',5000.00,0.00,'[\"Monthly layout edits (up to 5 hours)\", \"R2 cloud storage optimization\", \"Priority security patching\", \"Monthly local search analytics report\"]','active','2026-08-02 10:13:50','2026-08-02 10:13:50');
/*!40000 ALTER TABLE `pricing_packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `push_subscriptions`
--

DROP TABLE IF EXISTS `push_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endpoint` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `auth_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `public_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `push_subscriptions_session_id_index` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push_subscriptions`
--

LOCK TABLES `push_subscriptions` WRITE;
/*!40000 ALTER TABLE `push_subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `push_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reusable_sections`
--

DROP TABLE IF EXISTS `reusable_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reusable_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` json NOT NULL,
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reusable_sections`
--

LOCK TABLES `reusable_sections` WRITE;
/*!40000 ALTER TABLE `reusable_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `reusable_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hero_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hero_subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_loan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenure` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `intro_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `intro_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `eligibility_criteria` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `documents_required` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tips_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tips_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `faqs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `badge` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `schema_markup` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metatitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadescription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metakeywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'laundry-services',NULL,NULL,'Laundry Services','DAILY ESSENTIALS','Everyday Wash, Fold & Steam Care','Hygienic batch washing, pH-neutral detergents, fabric softening, and neat folding delivered to your doorstep.','₹80 / kg (Wash & Fold)','Batch Washed · 100% Segregated','24 - 48 Hours','The Modern Routine for Daily Laundry','<p>At Tenderfold, we believe daily laundry deserves premium care. Your garments are never mixed with other orders. Each load is sorted by color and fabric type, washed in state-of-the-art Italian washers with skin-safe eco detergents, dried at gentle temperatures, and folded to perfection.</p>','Individual Customer Batch Washing\n100% Water Softened & Filtered\nSkin-Safe Allergen-Free Detergents','Barcoded Garment Tagging\nGentle Low-Heat Tumble Drying\nNeat Boutique Bundle Packaging','Fabric Care Instructions','<p>Separate whites from dark colors and check care labels for delicate wool or silk blend requirements before sending.</p>','[{\"question\":\"Is my laundry washed separately from other customers?\",\"answer\":\"Yes, absolutely. We practice 100% dedicated batch washing. Your clothes are never mixed with any other customer garments.\"},{\"question\":\"What is the turnaround time for wash & fold?\",\"answer\":\"Standard turnaround is 24 to 48 hours from doorstep collection.\"}]','Most Popular','Hygienic batch washing, gentle eco-detergents, and crisp folding for everyday clothes, linens, and bedsheets.',1,1,'2026-10-06 06:17:55','2026-10-06 06:17:55',NULL,NULL,NULL,NULL),(2,'dry-cleaning',NULL,NULL,'Dry Cleaning','COUTURE & DELICATES','Eco Dry Cleaning & Delicate Restoration','Gentle hydrocarbon solvent cleaning for suits, silks, sarees, designer couture, and winter woolens.','Suits from ₹350 | Sarees from ₹250','Silks, Woolens & Embellished Wear','48 - 72 Hours','Couture Fabric Preservation','<p>Our dry cleaning process uses gentle, odor-free hydrocarbon solvents in closed-loop Italian machines. Embellishments, delicate silk fibers, fine cashmere, and structured suits receive individual attention, targeted stain spotting, and zero color fading.</p>','Zero Harsh Perchloroethylene Chemicals\nIndividual Pre-Spotting Stain Treatment\nSafe for Sequins, Zari & Embroidery','Breathable Garment Bag Delivery\nCustom Hanger Shaping\nAnti-Moth Protective Storage Wrap','Storing Dry Cleaned Garments','<p>Remove plastic covers after delivery and store ethnic garments in breathable muslin bags in a cool, dry wardrobe.</p>','[{\"question\":\"Are delicate designer lehengas and wedding sherwanis safe?\",\"answer\":\"Yes. Our master fabric technicians test embellishments and use gentle solvent baths designed for delicate zari and embroidery.\"},{\"question\":\"Do garments have a chemical odor after dry cleaning?\",\"answer\":\"No. We use eco-friendly, odorless solvents and thorough aeration cycles so your garments return fresh.\"}]','Premium Care','Eco-friendly hydrocarbon dry cleaning for ethnic wear, formal suits, cashmere, silks, and heavy woolens.',1,2,'2026-10-06 06:17:55','2026-10-06 06:17:55',NULL,NULL,NULL,NULL),(3,'ironing',NULL,NULL,'Steam Pressing','PRECISION FINISH','Boutique Vacuum Steam Finishing','Professional temperature-controlled steam pressing on vacuum tables that protect fabric textures with zero shine marks.','₹115 / kg (Wash & Steam Iron)','Formals, Linens & Cottons','24 - 48 Hours','The Art of Sharp, Crease-Free Pressing','<p>Traditional flat irons squash fabric fibers and leave shiny burns. Our vacuum suction tables draw continuous dry steam through the fabric, relaxing threads from within to yield razor-sharp pleats and a smooth, luxurious hand feel.</p>','Padded Vacuum Suction Tables\nDry High-Pressure Steam Finish\nZero Fabric Shine or Scorch Marks','Collar & Cuff Precision Shaping\nDelivered on Sturdy Hangers\nProtective Shoulder Guards','Maintaining Creases','<p>Hang steam-pressed trousers and shirts immediately to preserve crisp pleats and maintain collar form.</p>','[{\"question\":\"Will steam pressing damage delicate buttons or zippers?\",\"answer\":\"No. We use specialized steam irons with Teflon shoe plates and padded tables that protect delicate trims.\"}]','Quick Turnaround','Razor-sharp creases on industrial vacuum steam tables with zero burn marks or shine.',1,3,'2026-10-06 06:17:55','2026-10-06 06:17:55',NULL,NULL,NULL,NULL),(4,'shoe-cleaning',NULL,NULL,'Shoe Cleaning','FOOTWEAR SPA','Deep Shoe Laundry & Restoration','Professional cleaning, sole de-yellowing, suede grooming, and odor removal for sneakers and luxury shoes.','From ₹350 / pair','Sneakers, Suede & Leather','3 - 5 Days','Handcrafted Footwear Spa & Renewal','<p>From premium white kicks to delicate suede loafers and leather boots, our shoe artisans use specialized pH-neutral cleaners, soft-bristle brushes, and UV ozone sanitization chambers.</p>','Material-Specific Brushes\nSole De-Oxidization\nOdor Neutralization','Lace Cleaning & Ironing\nSuede Re-Nap Grooming\nWater-Repellent Coating Option','Shoe Care Recommendations','<p>Never machine-wash leather or suede shoes at home as water causes structural deformation and adhesive failure.</p>','[{\"question\":\"Can you remove stubborn dirt from white boost soles?\",\"answer\":\"Yes, we apply specialized oxidation-reversal solutions to restore original white soles.\"},{\"question\":\"Is suede cleaning safe?\",\"answer\":\"Yes, we use dry foam and specialized brass\\/crepe brushes for safe suede restoration.\"}]','Specialist Care','Deep cleaning, sole de-yellowing, suede restoration, and deodorization for sneakers and footwear.',1,4,'2026-10-06 06:17:55','2026-10-06 06:17:55',NULL,NULL,NULL,NULL),(5,'carpet-cleaning',NULL,NULL,'Carpet Cleaning','HOME SPA','Deep Carpet & Rug Cleaning','Multi-stage dust extraction, shampooing, stain removal, and sanitization for home carpets.','From ₹25 / sq.ft','Rugs, Wool & Persian Carpets','3 - 5 Days','Revitalize Your Home Carpets','<p>Our carpet spa extracts trapped allergens, pet dander, and deep soil using rotary foam scrubbers and hot water extraction without soaking the backing or causing color bleeding.</p>','Industrial Dust Beating\nRotary Foam Extraction\nOdor & Mold Elimination','Stain Guard Coating\nMoisture-Controlled Drying\nSanitized Packaging','Carpet Maintenance Tips','<p>Vacuum carpets weekly and blot liquid spills immediately with a dry cloth instead of scrubbing.</p>','[{\"question\":\"Do you clean carpets on-site or at your facility?\",\"answer\":\"We provide both doorstep extraction and pickup & delivery to our specialized carpet washing facility.\"}]','Deep Clean','Multi-stage dust extraction, foam shampooing, and anti-bacterial sanitization for rugs and carpets.',1,5,'2026-10-06 06:17:55','2026-10-06 06:17:55',NULL,NULL,NULL,NULL),(6,'curtain-cleaning',NULL,NULL,'Curtain Cleaning','HOME CARE','Curtain & Drape Refreshing','Steam cleaning, dust extraction, and pleat preservation for sheer, blackout, and velvet curtains.','From ₹180 / panel','All Fabric Types','2 - 4 Days','Breathe Life Into Your Window Drapes','<p>Drapes collect dust, pollen, and airborne pollutants over time. We provide gentle dry cleaning and steam rejuvenation to restore drape volume and clean air quality in your home.</p>','Pleat Preservation\nBlackout Lining Care\nShrinkage Prevention','Free Take-Down & Re-Hanging\nAnti-Dust Repellent Finish\nWrinkle-Free Steam Pressing','Drape Longevity','<p>Regular steam cleaning extends curtain life and keeps indoor air allergen-free.</p>','[{\"question\":\"Do you assist in removing and rehanging heavy curtains?\",\"answer\":\"Yes, our team handles take-down and rehanging upon request for a completely hassle-free experience.\"}]','Home Care','On-hook or off-hook steam cleaning, dust removal, and pleat preservation for drapes and curtains.',1,6,'2026-10-06 06:17:55','2026-10-06 06:17:55',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('0WcXpnILhcDLnUZyUKP6m7jCghiG6ZjYifmmYVG0',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiI4QkdaS0FLRGlveGdQaTJENWl5dktiOFlMbklhclJJVkVJUzhpdlRTIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1789640249),('29kD2aLg14VRFuDCW3161AINTrLViEHgpkXOtba6',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJKNDM5Q1FHbjlScFVrMGdaTXc2WklLYlA5d0Z4YklXVFh1cmJHaDVWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvYmxvZ3MiLCJyb3V0ZSI6ImJsb2cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1789638370),('5AMh04OVRKIJSYQLexwbBC9p42KbYP7g7d3UyorK',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiI2b3hMRjZwZDlva085RHlSeWN3UXZnZlFUVEpLazlsNm04WEJzVm02IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1790392210),('5VilNbZqjxHVOpeWeyHCDCogk80IZnJWuL5qJtPX',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiI3N2pvV1N0dGZlSkpIRGJoTlg1eEswb2dxbEVRRm9TeE1yWHlubGpkIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790392601),('bcHHsiv4p4PqXRFerMY42lHEXQ2vCIJTTU7bAWxq',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJNamh0UVJCS2RRRFdIa2ZCZ1FRSW1HUW9aNk1hY0h2MVR6QkxuVmFGIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1789640245),('DEpC19op8594uwZ18uV7czz2sztplOMvYKYT3GXK',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiI0bWhLUUJseDRueTZPMDloMk1jakdGblh4WXRCZkxjZnliMThnd29XIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1790393520),('dgjNS39BXxB9xJ2D3aND5jw0IEIj5F8fCxbX6xd9',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJuZ3BZVllOMXlpZG5INExNOEtCOVd4NWN3SlJsVzA4THZhV1FkYVJIIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1790391997),('iWEyaV9VQAGI2fYSX1okTrINdMnynNzieFz5AYoW',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiIwOGxCRFhISFlyc0VwU1hMTVZBWnRjUVUzVjJDZEVkVUxYOGs3OTdHIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1789640249),('KxcjrIjYyqvPK02Q961INFDegjNuNIoJwsG8kvpJ',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiIwb25iOFBJM0ZYdTg2N3lZdHl5dEdqUEdoZTJBWXZTVlpTN05xZzJBIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvYmxvZ3NcL29ydGhvcGVkaWMtZHIiLCJyb3V0ZSI6ImJsb2cuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1789643357),('LGEg6f8NIEK6ZGGKZ6AoWoKmqjLeManuzrsPUoCp',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJMV0tBb2c0QVZMRjFkWVRWTFZyYk50UWFQbHNaemxCdlVLS1M3bDRnIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9zZXJ2aWNlc1wvbGF1bmRyeS1zZXJ2aWNlcyIsInJvdXRlIjoic2VydmljZXMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1790393495),('lvJwXm4C8Ce9CPZZUhmnngSoyqyeG7exRB3FGhDq',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJqUmJGa1d3MDNoaTIxOUlLQldqbFNtTkJEZkhMWEdPV0h3bmVCUTR3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9zZXJ2aWNlc1wvbGF1bmRyeS1zZXJ2aWNlcyIsInJvdXRlIjoic2VydmljZXMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1790392703),('n556sdeboUGYUS4PYNYLjFsNcrEKmVAHjqaEpXBP',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJVeHUzanlYaUJjVHlXOTd4T0xGY2tUUkJVRUxmdGxpelZIbkQyRG41IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790392239),('NgtdSG3tYyovF174pfxyYFHsOgqUHBwMP5VP67hS',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJxMXdzUlZHenpYRUg1MFp5a01vNzJad0Z3OFpBSDN6Mks0aHB3ZlpoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1790392004),('Ordjrcq2qUdSIgsUYGAbRIO2fw0Ey570yFtzTH0r',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJMWTFFWHM2Mmh5cDBBSnNZaUpWMExCYjlCcjdyb0FnTjlNWU5NNGF0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvY2hhdHMiLCJyb3V0ZSI6ImFkbWluLmNoYXRzLmxpc3QifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOltdLCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=',1791287001),('uu4odKH1zf0DuHcn1zqN8C88aSw53Um130Fj3y7x',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJBakIwUGlKN0x0dTdicWgwOFdDUDhYbWkwU1NUN1p6cmJVSzF3c2NEIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1790392042),('V8jCUMdpEVW7FuzIEuBNQvARhFh4xOT83q3OI6J0',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJyNE82WjFyZVU1cmhveWdINXQ5c3pTYnFMOE9ZREJXTGVWR2VvbFNXIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvYmxvZ1wvb3J0aG9wZWRpYy1kci4tbmVhci1tZSIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1789640676),('W8gssEDbZAiDHa5NAkJr8FHYojqnzO020X9K1JMG',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJDdlVpSjA1MFhlRDV1NDdGYzdjMnNWMmhxZGJsTVE5Um9nTTdGenNSIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvYmxvZ3NcL2NhcmUtY2FzaG1lcmUtd29vbGVucy13aW50ZXIiLCJyb3V0ZSI6ImJsb2cuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1790392061);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_parameters`
--

DROP TABLE IF EXISTS `site_parameters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_parameters` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_parameters`
--

LOCK TABLES `site_parameters` WRITE;
/*!40000 ALTER TABLE `site_parameters` DISABLE KEYS */;
INSERT INTO `site_parameters` VALUES ('address','Sector 49 & Sector 83, Gurgaon, Haryana - 122018','Physical Address','contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('address_baner','Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II, Sector 49, Gurgaon - 122018','Sector 49 Hub Address','contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('address_hinjewadi','F-193, First Floor, Sapphire 83 Mall, Sector 83, Gurgaon - 122004','Sector 83 Hub Address','contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('ai_model','gemini-2.5-flash','AI Engine Model','other','2026-08-13 06:23:47','2026-08-25 13:56:39'),('chat_assistant_avatar',NULL,'Chat Assistant Avatar Image','branding','2026-10-06 06:17:55','2026-10-06 06:17:55'),('chat_assistant_name','Tenderfold Care Assistant','Chat Assistant Display Name','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('clinic_locations_short','Sector 49 & Sector 83, Gurgaon','Locations Short Text','contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('clinic_timings','Mon - Sun: 8:00 AM - 9:00 PM','Operational Hours','contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('cloudflare_analytics_token',NULL,'Cloudflare Analytics Token','other','2026-07-08 02:57:36','2026-07-08 02:57:36'),('cloudflare_api_token',NULL,'Cloudflare Api Token','other','2026-07-08 02:57:36','2026-07-08 02:57:36'),('cloudflare_r2_access_key_id',NULL,'R2 Access Key ID','other','2026-07-07 04:50:36','2026-07-07 05:04:59'),('cloudflare_r2_account_id',NULL,'R2 Account ID','other','2026-07-07 04:52:35','2026-07-07 04:52:35'),('cloudflare_r2_bucket_name',NULL,'R2 Bucket Name','other','2026-07-07 04:52:35','2026-07-15 05:06:50'),('cloudflare_r2_public_url',NULL,'R2 Public Custom Domain URL','other','2026-07-07 04:50:36','2026-07-15 05:06:50'),('cloudflare_r2_secret_access_key',NULL,'R2 Secret Access Key','other','2026-07-07 04:50:36','2026-07-07 05:04:59'),('cloudflare_zone_id',NULL,'Cloudflare Zone Id','other','2026-07-08 02:57:36','2026-07-08 02:57:36'),('copyright_text','© 2026 Tenderfold. Freshness in Every Single Fold. All Rights Reserved.','Footer Copyright Line','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('email','info@tenderfold.in','Contact Email Address','contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('favicon_path','assets/logo.png','Custom Favicon Image','branding','2026-10-06 06:17:55','2026-10-06 06:17:55'),('footer_description','Tenderfold is Gurgaon\'s trusted premium laundry, dry cleaning & specialized fabric care service. Doorstep pickup across all major sectors.','Footer Description','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('footer_helplines','+91 95824 66445','Footer Helpline Numbers','contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('gemini_api_key','AQ.Ab8RN6Ibz3PhpB5IvZCJK4iiRWjt-RrZQ--WEMbwY6bA2Dgw2Q','Gemini Api Key','other','2026-07-07 02:38:31','2026-07-15 05:18:14'),('gmb_access_token',NULL,'Gmb Access Token','other','2026-07-15 02:27:12','2026-07-15 02:27:12'),('gmb_location_id','ChIJVVXZELW-wjsR55PO9Vgs1jU','Gmb Location Id','other','2026-07-15 02:27:12','2026-08-24 07:34:35'),('google_analytics_id',NULL,'Google Analytics Tracking ID','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('google_analytics_property_id',NULL,'Google Analytics Property Id','other','2026-08-24 07:34:35','2026-08-24 07:35:11'),('google_my_business_link',NULL,'Google My Business Link','other','2026-07-15 02:27:12','2026-07-15 02:27:12'),('google_oauth_client_id','admin@drsumitz.com','Google Oauth Client Id','other','2026-08-24 07:34:35','2026-08-24 07:35:11'),('google_oauth_client_secret','admin123','Google Oauth Client Secret','other','2026-08-24 07:34:35','2026-08-24 07:35:11'),('google_search_console_site_url','sc-domain:tenderfold.in','Google Search Console URL','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('google_tag_id',NULL,'Google Tag / GA4 Measurement ID','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('header_scripts',NULL,'Custom Header Scripts / CSS','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('helpline_baner','+91 95824 66445','Sector 49 Helpline','contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('helpline_hinjewadi','+91 95824 66445','Sector 83 Helpline','contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('index_about_bg','#ffffff','Home About Section Background','styling','2026-10-06 06:17:55','2026-10-06 06:17:55'),('index_cta_bg','linear-gradient(135deg, #0C2454 0%, #143572 100%)','Home CTA Section Background','styling','2026-10-06 06:17:55','2026-10-06 06:17:55'),('index_faq_bg','#ffffff','Home FAQ Background','styling','2026-10-06 06:17:55','2026-10-06 06:17:55'),('index_hero_bg','#F4F7FB','Home Hero Section Background','styling','2026-10-06 06:17:55','2026-10-06 06:17:55'),('index_services_bg','#ffffff','Home Services Section Background','styling','2026-10-06 06:17:55','2026-10-06 06:17:55'),('index_testimonials_bg','#F4F7FB','Home Testimonials Background','styling','2026-10-06 06:17:55','2026-10-06 06:17:55'),('index_why_bg','#ffffff','Home Why Choose Us Background','styling','2026-07-07 05:39:47','2026-07-07 05:39:47'),('live_site_url','https://tenderfold.in','Live Site URL','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('logo_path','assets/logo.png','Custom Logo Image','branding','2026-10-06 06:17:55','2026-10-06 06:17:55'),('meta_description','Tenderfold is a premium laundry and dry cleaner in Gurgaon. Doorstep pickup for wash & fold, steam ironing, dry cleaning and specialized fabric care. Call +91 95824 66445.','Default Meta Description','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('meta_keywords','laundry gurgaon, dry cleaning gurgaon, wash and fold, steam ironing, shoe cleaning, carpet cleaning, sector 49, sector 83, tenderfold','Default Meta Keywords','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('og_image_path','assets/logo.png','Social Open Graph Share Image','branding','2026-10-06 06:17:55','2026-10-06 06:17:55'),('phone','+91 95824 66445','Contact Phone Number','contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('phone_display','+91 95824 66445','Display Phone Number','contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('recaptcha_enabled','0','Enable Google reCAPTCHA','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('recaptcha_secret_key',NULL,'reCAPTCHA Secret Key','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('recaptcha_site_key',NULL,'reCAPTCHA Site Key','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('site_name','Tenderfold','Site Name','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('site_tagline','Freshness in Every Single Fold - Premium Laundry & Dry Cleaner in Gurgaon','Site Tagline','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('sitemap_domain','https://tenderfold.in','Sitemap Domain','general','2026-10-06 06:17:55','2026-10-06 06:17:55'),('smtp_encryption','tls','Smtp Encryption','other','2026-07-08 02:57:36','2026-07-08 02:57:36'),('smtp_from_address',NULL,'Smtp From Address','other','2026-07-08 02:57:36','2026-08-13 06:23:47'),('smtp_from_name',NULL,'Smtp From Name','other','2026-07-08 02:57:36','2026-08-13 06:23:47'),('smtp_host',NULL,'Smtp Host','other','2026-07-08 02:57:36','2026-07-08 02:57:36'),('smtp_password',NULL,'Smtp Password','other','2026-07-08 02:57:36','2026-07-08 02:57:36'),('smtp_port','587','Smtp Port','other','2026-07-08 02:57:36','2026-07-08 02:57:36'),('smtp_to_email',NULL,'Smtp To Email','other','2026-07-08 02:57:36','2026-08-13 06:23:47'),('smtp_username',NULL,'Smtp Username','other','2026-07-08 02:57:36','2026-07-08 02:57:36'),('social_facebook','https://facebook.com/tenderfold','Facebook URL','social','2026-10-06 06:17:55','2026-10-06 06:17:55'),('social_instagram','https://www.instagram.com/tenderfold/','Instagram URL','social','2026-10-06 06:17:55','2026-10-06 06:17:55'),('social_linkedin',NULL,'Social Linkedin','other','2026-07-15 02:27:12','2026-07-15 02:27:12'),('social_practo','https://maps.app.goo.gl/tenderfold','Google Business / Maps URL','social','2026-10-06 06:17:55','2026-10-06 06:17:55'),('social_twitter',NULL,'Social Twitter','other','2026-07-15 02:27:12','2026-07-15 02:27:12'),('whatsapp_admin_recipient','919582466445','WhatsApp Admin Alert Recipient','contact','2026-10-06 06:17:55','2026-10-06 06:17:55'),('whatsapp_api_token',NULL,'Whatsapp Api Token','other','2026-07-08 02:57:36','2026-07-08 02:57:36'),('whatsapp_api_url',NULL,'Whatsapp Api Url','other','2026-07-08 02:57:36','2026-07-08 02:57:36'),('whatsapp_number','919582466445','WhatsApp Number (with country code)','contact','2026-10-06 06:17:55','2026-10-06 06:17:55');
/*!40000 ALTER TABLE `site_parameters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_links`
--

DROP TABLE IF EXISTS `social_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_links` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `utm_source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'social',
  `utm_medium` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `utm_campaign` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clicks` bigint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `social_links_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_links`
--

LOCK TABLES `social_links` WRITE;
/*!40000 ALTER TABLE `social_links` DISABLE KEYS */;
INSERT INTO `social_links` VALUES (1,'eercuy','https://www.drsumitz.com/blog/knee-replacement-tips','facebook','social_post','mobile_share_202608','https://www.drsumitz.com/blog/knee-replacement-tips?utm_source=facebook&utm_medium=social_post&utm_campaign=mobile_share_202608','Knee Surgery Recovery Guide',1,'2026-08-25 08:26:24','2026-08-25 08:26:28');
/*!40000 ALTER TABLE `social_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specialities`
--

DROP TABLE IF EXISTS `specialities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `specialities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `overview` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `search_patterns` json DEFAULT NULL,
  `treatments` json DEFAULT NULL,
  `conditions` json DEFAULT NULL,
  `required_sections` json DEFAULT NULL,
  `related_locations` json DEFAULT NULL,
  `seo_data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `specialities_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specialities`
--

LOCK TABLES `specialities` WRITE;
/*!40000 ALTER TABLE `specialities` DISABLE KEYS */;
/*!40000 ALTER TABLE `specialities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_members`
--

DROP TABLE IF EXISTS `team_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `facebook` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `metatitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadescription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metakeywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tags` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `schema_markup` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_members_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_members`
--

LOCK TABLES `team_members` WRITE;
/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int NOT NULL,
  `avatar_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,'Pooja Narang','South City II, Gurgaon','Tenderfold has completely transformed how our household manages laundry. Their wash & fold service is impeccably hygienic, clothes smell subtle and clean, and turnaround is always on point.',5,NULL,0,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(2,'Rohan Mehra','Sector 83, Gurgaon','I sent my wedding sherwani and my wife\'s designer lehenga for dry cleaning. The embroidery and silk were preserved flawlessly. Exceptional care and attention to detail.',5,NULL,0,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(3,'Ananya Sharma','DLF Phase 5, Gurgaon','The vacuum steam pressing is boutique level. Every business shirt and linen trouser comes back with crisp creases and zero iron shine. Doorstep pickup makes it effortlessly easy.',5,NULL,0,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(4,'Vikram Sethi','Nirvana Country, Sector 50','Their shoe spa restored my white leather sneakers and suede loafers like new. Extremely impressed with their specialized footwear cleaning techniques.',5,NULL,0,'2026-10-06 06:17:55','2026-10-06 06:17:55'),(5,'Meenakshi Rao','Sohna Road, Gurgaon','Prompt doorstep pickup, transparent pricing, and gentle fabric care. They handled our heavy wool carpets and blackout curtains with total professionalism.',5,NULL,0,'2026-10-06 06:17:55','2026-10-06 06:17:55');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `themes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokens` json NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `themes`
--

LOCK TABLES `themes` WRITE;
/*!40000 ALTER TABLE `themes` DISABLE KEYS */;
INSERT INTO `themes` VALUES (1,'Default Coral Red','{\"theme_bg_card\": \"#0a0a0a\", \"theme_bg_dark\": \"#000000\", \"theme_primary\": \"#F53003\", \"theme_bg_border\": \"#111111\", \"theme_font_body\": \"\'Inter\', sans-serif\", \"theme_radius_lg\": \"12px\", \"theme_radius_md\": \"8px\", \"theme_font_scale\": \"1.0\", \"theme_line_height\": \"1.5\", \"theme_font_heading\": \"\'Outfit\', sans-serif\", \"theme_primary_hover\": \"#C42602\"}',1,'2026-07-23 20:41:47','2026-07-23 20:41:47'),(2,'default red','{\"theme_bg_card\": \"#1d1616\", \"theme_bg_dark\": \"#292929\", \"theme_primary\": \"#af2304\", \"theme_bg_border\": \"#fafafa\", \"theme_font_body\": \"\'Inter\', sans-serif\", \"theme_radius_lg\": \"12px\", \"theme_radius_md\": \"8px\", \"theme_font_scale\": \"1.0\", \"theme_line_height\": \"1.5\", \"theme_font_heading\": \"\'Outfit\', sans-serif\", \"theme_primary_hover\": \"#c84d32\"}',0,'2026-07-23 21:06:54','2026-07-23 21:06:54'),(3,'Default','{\"theme_bg_card\": \"#0a0a0a\", \"theme_bg_dark\": \"#000000\", \"theme_primary\": \"#F53003\", \"theme_bg_border\": \"#111111\", \"theme_font_body\": \"\'Inter\', sans-serif\", \"theme_radius_lg\": \"12px\", \"theme_radius_md\": \"8px\", \"theme_font_scale\": \"1.0\", \"theme_line_height\": \"1.5\", \"theme_font_heading\": \"\'Outfit\', sans-serif\", \"theme_primary_hover\": \"#C42602\"}',0,'2026-07-23 21:07:21','2026-07-23 21:07:21');
/*!40000 ALTER TABLE `themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatments`
--

DROP TABLE IF EXISTS `treatments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treatments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `faqs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metatitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadescription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metakeywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tags` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `schema_markup` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `treatments_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatments`
--

LOCK TABLES `treatments` WRITE;
/*!40000 ALTER TABLE `treatments` DISABLE KEYS */;
/*!40000 ALTER TABLE `treatments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `permissions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Tenderfold Admin','admin@tenderfold.in',NULL,'$2y$12$qxQ4p2dn6XpufyZm6Ki4lO8eQpCP12PbYiOUeSF774sNIfiF38Eju','superadmin','[\"all\"]',NULL,'2026-10-06 06:17:55','2026-10-06 06:17:55');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `web_notifications`
--

DROP TABLE IF EXISTS `web_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `web_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `web_notifications_session_id_index` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_notifications`
--

LOCK TABLES `web_notifications` WRITE;
/*!40000 ALTER TABLE `web_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_notifications` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-06 17:18:07
