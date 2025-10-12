-- MySQL dump 10.13  Distrib 8.4.6, for Win64 (x86_64)
--
-- Host: localhost    Database: template
-- ------------------------------------------------------
-- Server version	8.4.6

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
-- Table structure for table `call_center`
--

DROP TABLE IF EXISTS `call_center`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `call_center` (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `nomorwa` varchar(20) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_call_center_sort_order` (`sort_order`),
  KEY `idx_call_center_created_at` (`created_at`),
  KEY `idx_call_center_sort` (`sort_order`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `call_center`
--

LOCK TABLES `call_center` WRITE;
/*!40000 ALTER TABLE `call_center` DISABLE KEYS */;
INSERT INTO `call_center` VALUES (1,'Sales','+6281234567890','Bagian Sales/Penjualan siap melayani anda','2025-10-01 16:21:56','2025-10-10 07:23:53',2),(3,'Manajemen','+6281231231243','Jika Anda akan komplain masalah inkaso & penjualan','2025-10-01 16:29:01','2025-10-03 12:15:54',1),(4,'Purchase','+628802459609','Bagian komplain Pembelian dan Retur','2025-10-01 16:36:29','2025-10-10 07:23:49',4),(9,'Pengiriman','08394898590','Bagian pengiriman jika anda ingin komplain masalah pengirim.','2025-10-07 09:44:28','2025-10-10 07:23:53',3);
/*!40000 ALTER TABLE `call_center` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `konfigurasi`
--

DROP TABLE IF EXISTS `konfigurasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `konfigurasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `namaperusahaan` varchar(255) NOT NULL DEFAULT 'Nama Perusahaan',
  `alamatperusahaan` text NOT NULL,
  `npwp` varchar(50) NOT NULL DEFAULT '',
  `noijin` varchar(100) NOT NULL DEFAULT '',
  `penanggungjawab` varchar(255) NOT NULL DEFAULT 'Penanggung Jawab',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_konfigurasi_nama` (`namaperusahaan`),
  KEY `idx_konfigurasi_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `konfigurasi`
--

LOCK TABLES `konfigurasi` WRITE;
/*!40000 ALTER TABLE `konfigurasi` DISABLE KEYS */;
INSERT INTO `konfigurasi` VALUES (1,'Logics Software','Jl. Supagani No.2 Kemlayan Serengan Surakarta 57151','12.345.678.9-012.000','SIUP-123456789','Nurdin Budi Mustofa','logo_1759164946.png','2025-09-29 16:14:34','2025-10-11 10:59:19');
/*!40000 ALTER TABLE `konfigurasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_groups`
--

DROP TABLE IF EXISTS `menu_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `icon` varchar(50) DEFAULT 'fas fa-folder',
  `description` text,
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `is_collapsible` tinyint(1) DEFAULT '1',
  `default_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Default menu group untuk role Admin',
  `default_manajemen` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Default menu group untuk role Manajemen',
  `default_user` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Default menu group untuk role User',
  `default_marketing` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Default menu group untuk role Marketing',
  `default_customer` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Default menu group untuk role Customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_menu_groups_sort` (`sort_order`),
  KEY `idx_menu_groups_active` (`is_active`),
  KEY `idx_default_admin` (`default_admin`),
  KEY `idx_default_manajemen` (`default_manajemen`),
  KEY `idx_default_user` (`default_user`),
  KEY `idx_default_marketing` (`default_marketing`),
  KEY `idx_default_customer` (`default_customer`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_groups`
--

LOCK TABLES `menu_groups` WRITE;
/*!40000 ALTER TABLE `menu_groups` DISABLE KEYS */;
INSERT INTO `menu_groups` VALUES (16,'App Full System','app-full-system','fas fa-desktop','Sistem menu lengkap untuk admin',0,1,1,1,0,1,0,0,'2025-10-03 08:16:00','2025-10-12 08:01:40'),(17,'Customer System','customer-system','fas fa-users','Sistem menu untuk customer',0,1,1,0,0,0,0,1,'2025-10-03 08:16:00','2025-10-11 03:33:52'),(20,'Manajemen System','manajemen-system','fas fa-flag','Menu untuk Manajemen',0,1,1,0,1,0,0,0,'2025-10-04 06:50:21','2025-10-08 13:56:32'),(22,'Keuangan','keuangan','fas fa-money-check-alt','Menu bagian keuangan',0,1,1,1,1,0,0,0,'2025-10-05 13:33:06','2025-10-10 03:29:57'),(24,'Setting','setting','fas fa-gear','Setting Sistem',0,1,0,1,0,0,0,0,'2025-10-10 03:20:10','2025-10-10 03:20:10');
/*!40000 ALTER TABLE `menu_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `module_id` int DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(50) DEFAULT 'fas fa-circle',
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `is_parent` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_menu_items_group_sort` (`group_id`,`sort_order`),
  KEY `idx_menu_items_parent` (`parent_id`),
  KEY `idx_menu_items_module` (`module_id`),
  KEY `idx_menu_items_is_parent` (`is_parent`),
  CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `menu_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_items_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_items_ibfk_3` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (48,16,NULL,NULL,'Transaksi','fas fa-exchange-alt',2,1,1,'2025-10-03 08:19:36','2025-10-08 04:32:09'),(49,16,NULL,NULL,'Laporan','fas fa-chart-bar',6,1,1,'2025-10-03 08:19:36','2025-10-08 04:32:09'),(50,16,NULL,NULL,'Setting','fas fa-cog',11,1,1,'2025-10-03 08:19:36','2025-10-08 04:32:09'),(51,16,NULL,NULL,'Chat/Pesan','fas fa-comments',15,1,0,'2025-10-03 08:19:36','2025-10-10 07:37:32'),(52,16,48,3,'Transaksi Penjualan','fas fa-shopping-cart',3,1,0,'2025-10-03 08:19:36','2025-10-08 07:21:31'),(53,16,48,3,'Transaksi Retur Penjualan','fas fa-undo',4,1,0,'2025-10-03 08:19:36','2025-10-08 07:27:42'),(54,16,48,4,'Transaksi Penerimaan Pembayaran','fas fa-money-bill',5,1,0,'2025-10-03 08:19:36','2025-10-08 04:32:09'),(55,16,49,3,'Laporan Penjualan','fas fa-chart-line',7,1,0,'2025-10-03 08:19:36','2025-10-08 07:28:02'),(56,16,49,2,'Laporan Retur Penjualan','fas fa-chart-area',8,1,0,'2025-10-03 08:19:36','2025-10-08 07:28:19'),(57,16,49,3,'Laporan Penerimaan Pembayaran','fas fa-chart-pie',9,1,0,'2025-10-03 08:19:36','2025-10-08 07:28:37'),(58,16,49,5,'Laporan Graphics Analisa Penjualan','fas fa-chart-bar',10,1,0,'2025-10-03 08:19:36','2025-10-08 07:28:48'),(59,16,50,5,'Setting Konfigurasi','fas fa-cogs',12,1,0,'2025-10-03 08:19:36','2025-10-10 07:37:22'),(60,16,50,9,'Setting Call Center','fas fa-phone',14,1,0,'2025-10-03 08:19:36','2025-10-10 07:37:32'),(61,17,NULL,NULL,'Dashboard','fas fa-home',1,1,0,'2025-10-03 08:19:36','2025-10-08 04:32:09'),(62,17,NULL,NULL,'Transaksi','fas fa-exchange-alt',20,1,1,'2025-10-03 08:19:36','2025-10-03 08:19:36'),(63,17,NULL,NULL,'Laporan','fas fa-chart-bar',30,1,1,'2025-10-03 08:19:36','2025-10-03 08:19:36'),(64,17,NULL,NULL,'Chat/Pesan','fas fa-comments',40,1,0,'2025-10-03 08:19:36','2025-10-03 08:19:36'),(65,17,62,NULL,'Transaksi Order','fas fa-shopping-bag',1,1,0,'2025-10-03 08:19:36','2025-10-03 08:19:36'),(66,17,62,NULL,'Transaksi Status Penerimaan Barang','fas fa-truck',2,1,0,'2025-10-03 08:19:36','2025-10-03 08:19:36'),(67,17,63,NULL,'Laporan Order','fas fa-file-alt',1,1,0,'2025-10-03 08:19:36','2025-10-03 08:19:36'),(68,17,63,NULL,'Laporan Penjualan','fas fa-chart-line',2,1,0,'2025-10-03 08:19:36','2025-10-03 08:19:36'),(70,16,NULL,5,'Dashboard','fas fa-home',1,1,0,'2025-10-04 17:18:27','2025-10-04 17:18:27'),(73,17,NULL,3,'Setting Konfigurasi','fas fa-cut',90,1,0,'2025-10-06 01:59:33','2025-10-06 01:59:41'),(79,16,49,3,'Percobaan','fas fa-map-marker',16,1,0,'2025-10-08 07:39:08','2025-10-10 07:37:32'),(80,16,50,12,'Setting Menu User','fas fa-user-shield',13,1,0,'2025-10-08 12:23:05','2025-10-10 07:37:43'),(81,24,NULL,NULL,'Setting','fas fa-gear',0,1,1,'2025-10-10 03:30:21','2025-10-10 03:30:21'),(82,24,81,3,'Konfigurasi Sistem','fas fa-building-shield',1,1,0,'2025-10-10 03:30:51','2025-10-10 03:30:51'),(83,24,81,11,'User','fas fa-user-gear',2,1,0,'2025-10-10 03:31:31','2025-10-11 13:22:38'),(84,24,81,8,'Menu','fas fa-bars-staggered',4,1,0,'2025-10-10 03:32:19','2025-10-11 13:22:25'),(85,24,81,12,'Akses Menu','fas fa-address-card',5,1,0,'2025-10-10 03:33:02','2025-10-11 13:22:54'),(86,24,81,2,'Call Center','fas fa-phone-volume',6,1,0,'2025-10-10 03:35:52','2025-10-10 03:46:58'),(87,24,81,9,'Modul Aplikasi','fas fa-shekel-sign',3,1,0,'2025-10-10 03:38:58','2025-10-10 10:56:57'),(88,16,50,9,'Setting Modul','fas fa-house-laptop',14,1,0,'2025-10-10 07:48:29','2025-10-10 07:48:29'),(89,16,49,3,'Laporan Saldo Piutang','fas fa-folder-open',11,1,0,'2025-10-10 07:49:28','2025-10-10 07:49:28');
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_attachments`
--

DROP TABLE IF EXISTS `message_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `message_attachments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message_id` int NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`),
  CONSTRAINT `message_attachments_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_attachments`
--

LOCK TABLES `message_attachments` WRITE;
/*!40000 ALTER TABLE `message_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `message_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_recipients`
--

DROP TABLE IF EXISTS `message_recipients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `message_recipients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message_id` int NOT NULL,
  `recipient_id` int NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_message_recipient` (`message_id`,`recipient_id`),
  KEY `idx_message_recipients_recipient_id` (`recipient_id`),
  KEY `idx_message_recipients_recipient_read` (`recipient_id`,`is_read`),
  KEY `idx_message_recipients_message_id` (`message_id`),
  KEY `idx_message_recipients_recipient_read_created` (`recipient_id`,`is_read`,`created_at`),
  KEY `idx_message_recipients_created_at` (`created_at`),
  CONSTRAINT `message_recipients_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `message_recipients_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_recipients`
--

LOCK TABLES `message_recipients` WRITE;
/*!40000 ALTER TABLE `message_recipients` DISABLE KEYS */;
INSERT INTO `message_recipients` VALUES (9,5,6,1,'2025-10-05 17:53:50','2025-09-30 08:51:13'),(10,6,21,1,'2025-09-30 08:54:22','2025-09-30 08:53:51'),(11,7,22,0,NULL,'2025-09-30 09:39:40'),(15,11,6,1,'2025-10-08 18:26:25','2025-10-01 14:54:09'),(16,12,22,0,NULL,'2025-10-01 14:54:53'),(17,12,19,0,NULL,'2025-10-01 14:54:53'),(18,12,23,0,NULL,'2025-10-01 14:54:53'),(19,13,22,0,NULL,'2025-10-01 15:12:00'),(20,13,19,1,'2025-10-03 04:17:26','2025-10-01 15:12:00'),(21,13,23,0,NULL,'2025-10-01 15:12:00'),(22,13,6,0,NULL,'2025-10-01 15:12:00'),(23,13,3,0,NULL,'2025-10-01 15:12:00'),(24,13,21,1,'2025-10-10 07:07:16','2025-10-01 15:12:00'),(25,14,22,1,'2025-10-07 16:40:02','2025-10-05 09:33:48');
/*!40000 ALTER TABLE `message_recipients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `message_type` enum('direct','group') DEFAULT 'direct',
  `status` enum('draft','sent','read') DEFAULT 'sent',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_messages_created_at` (`created_at`),
  KEY `idx_messages_sender_id` (`sender_id`),
  KEY `idx_messages_status` (`status`),
  KEY `idx_messages_subject` (`subject`),
  KEY `idx_messages_sender_status` (`sender_id`,`status`,`created_at`),
  KEY `idx_messages_sender_created` (`sender_id`,`created_at`),
  KEY `idx_messages_created_at_desc` (`created_at` DESC),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (5,3,'Forward: Test Message','<p><strong>Diteruskan dari:</strong> Administrator (admin@example.com)</p><p><strong>Tanggal:</strong> 30 September 2025, 11:43</p><p><strong>Subjek:</strong> Test Message</p><p>This is a test message content</p>','direct','sent','2025-09-30 08:51:13','2025-09-30 08:51:13'),(6,3,'Lamaran Kerja','<p>Mbak Sheila ini ada lowongan kerja di Apotek Soditan, coba buat lamaran nanti saya bantu.</p>','direct','sent','2025-09-30 08:53:51','2025-09-30 08:53:51'),(7,3,'Tagihan tanggal 1','<p>Nan tagihan tanggal 1 CCS sudah di transfer ya</p>','direct','sent','2025-09-30 09:39:40','2025-09-30 09:39:40'),(11,2,'Update online Samodra','<p>Sudha kita update mas, cukup di refresh	</p>','direct','sent','2025-10-01 14:54:09','2025-10-01 14:54:09'),(12,2,'Tambahan Update ASA','<p>Segera di proses nggih	</p>','direct','sent','2025-10-01 14:54:53','2025-10-01 14:54:53'),(13,2,'Pengumuman ','<p>Besok tanggal 10 Oktober 2025, kita libur bersama ya...</p>','direct','sent','2025-10-01 15:12:00','2025-10-01 15:12:00'),(14,2,'Tagihan tanggal 5 di proses','<p>Nan tagihan tanggal 5 segera di proses ya biar cepet kelar	</p>','direct','sent','2025-10-05 09:33:48','2025-10-05 09:33:48');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `caption` varchar(255) NOT NULL,
  `logo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `admin` tinyint(1) DEFAULT '0',
  `manajemen` tinyint(1) DEFAULT '0',
  `user` tinyint(1) DEFAULT '0',
  `marketing` tinyint(1) DEFAULT '0',
  `customer` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_modules_created_at` (`created_at`),
  KEY `idx_modules_admin` (`admin`),
  KEY `idx_modules_manajemen` (`manajemen`),
  KEY `idx_modules_user` (`user`),
  KEY `idx_modules_marketing` (`marketing`),
  KEY `idx_modules_customer` (`customer`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (2,'Setting Call Center','fas fa-wifi','/callcenter',1,1,0,0,0,'2025-10-02 14:37:10','2025-10-11 09:43:46'),(3,'Setting Konfigurasi','fas fa-cog','/konfigurasi',1,0,0,0,0,'2025-10-02 15:02:51','2025-10-11 03:33:43'),(4,'Pesan/Chat','fas fa-comments','/messages',1,0,0,0,0,'2025-10-02 16:11:41','2025-10-09 14:30:01'),(5,'Dashboard','fas fa-home','/dashboard',1,1,1,1,1,'2025-10-03 05:32:04','2025-10-03 07:13:47'),(8,'Setting Menu','fas fa-chart-bar','/menu',1,0,0,0,0,'2025-10-03 07:13:17','2025-10-03 07:30:21'),(9,'Setting Module','fas fa-puzzle-piece','/modules',1,0,0,0,0,'2025-10-03 07:28:46','2025-10-10 03:42:02'),(11,'User','fas fa-users','/users',1,0,0,0,0,'2025-10-08 09:17:28','2025-10-08 10:16:26'),(12,'Menu User','fas fa-desktop','/menuakses',1,0,0,0,0,'2025-10-08 12:18:09','2025-10-09 07:53:14');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remember_tokens`
--

DROP TABLE IF EXISTS `remember_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `remember_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_token` (`user_id`),
  KEY `idx_expires_at` (`expires_at`),
  CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remember_tokens`
--

LOCK TABLES `remember_tokens` WRITE;
/*!40000 ALTER TABLE `remember_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `remember_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `namalengkap` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manajemen','user','marketing','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'user',
  `registration_reason` text,
  `picture` varchar(255) DEFAULT NULL,
  `status` enum('aktif','non_aktif','register') DEFAULT 'aktif',
  `lastlogin` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_username` (`username`),
  KEY `idx_users_email` (`email`),
  KEY `idx_users_status` (`status`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_created_at` (`created_at`),
  KEY `idx_users_role_status` (`role`,`status`),
  KEY `idx_users_status_role` (`status`,`role`),
  KEY `idx_users_lastlogin` (`lastlogin`),
  KEY `idx_users_email_status` (`email`,`status`),
  KEY `idx_users_username_status` (`username`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'admin','Administrator','admin@example.com','$2y$10$ZTv2TYpCUE5hWZQ06XyO/.7UmuOGZ.5zefzApMd/b9R.jpVX3uw12','admin',NULL,'assets/images/users/user_2_1759063910.jpg','aktif','2025-10-12 07:49:57','2025-09-27 20:25:25','2025-10-12 08:01:48'),(3,'ilyas','Muhammad Ilyas','ilyaslogics@gmail.com','$2y$10$7nhGdseoBdlVN82UbRRlFOD2kl3elLmV4l80w90jxBouN1vITjtaS','user',NULL,NULL,'aktif','2025-10-10 11:57:08','2025-09-28 05:39:55','2025-10-10 11:57:08'),(6,'indra','Indrawan Bramastya','indralogics@gmail.com','$2y$10$9GewsaWNxZP5KgHD83bn.O5N43I0G7GlxG6waaY7TiM1v0Vok//E6','user',NULL,'assets/images/users/user_6_1759724063.jpeg','aktif','2025-10-11 10:08:43','2025-09-28 05:51:20','2025-10-11 10:08:43'),(19,'ari','Ari Purnomo','ari@gmail.com','$2y$10$FBm8bWXleVlHOZRlTegtWOSZaespprqfNW6jTH5sEEYOD0QWaUyxW','manajemen',NULL,'assets/images/users/user_19_1759073245.webp','aktif','2025-10-05 12:31:57','2025-09-28 14:33:53','2025-10-05 12:31:57'),(21,'sheila','Sheila Khalida Hasanah','sheila@gmail.com','$2y$10$54GwZ1rDxbbVs4VPXUMM8.rZNHM4TspIJvFZDROiwL9zp2LaJUeHu','customer','Saya dari Apotek Soditan mohon di approve',NULL,'aktif','2025-10-11 02:35:13','2025-09-28 14:42:41','2025-10-11 02:35:13'),(22,'adnan','Adnan HP','adnan@gmail.com','$2y$10$et/jYa.SGrqXN47SChVqZeTmLcCluv7F9doy76QLekXp0o4bIGJVi','user',NULL,'assets/images/users/user_22_1759143312.jpg','aktif','2025-10-10 18:36:11','2025-09-29 10:55:12','2025-10-10 18:36:11'),(23,'ensofa','Endang Sofia Amperawati','ensofa@gmail.com','$2y$10$HRflct4bkMChmtNrUdGQaOkmApE2s0Qiy8IWZMwVGk10HQbBjcUgm','customer',NULL,'assets/images/users/user_23_1759284210.jpg','aktif','2025-10-05 12:33:59','2025-10-01 02:03:30','2025-10-11 09:40:28'),(26,'elrey','El Rey Faiza','elrey@gmail.com','$2y$10$qWEunI3f8NCtYqhEQVOU5eNiqAwAFPqnJn8iaTFfC2EQTkSJJrAdu','marketing','Saya pengin punya akun Wa',NULL,'register',NULL,'2025-10-02 06:00:16','2025-10-02 06:00:16'),(28,'rosyid','Rosyid Mundhir','raymond@gmail.com','$2y$10$6OCAOBBFrsGgRF29csm/..cect8GL.zAL.fhYvajmL70cGRwnAowy','marketing','Saya sales baru mohon segera di Approval','assets/images/users/user_28_1759818997.webp','aktif',NULL,'2025-10-07 06:36:37','2025-10-07 08:24:02');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_menu`
--

DROP TABLE IF EXISTS `users_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'Foreign key to users table',
  `group_id` int NOT NULL COMMENT 'Foreign key to menu_groups table',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_group` (`user_id`,`group_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_group_id` (`group_id`),
  CONSTRAINT `fk_users_menu_group` FOREIGN KEY (`group_id`) REFERENCES `menu_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_users_menu_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_menu`
--

LOCK TABLES `users_menu` WRITE;
/*!40000 ALTER TABLE `users_menu` DISABLE KEYS */;
INSERT INTO `users_menu` VALUES (5,22,22,'2025-10-08 12:16:12','2025-10-08 12:16:12'),(8,3,20,'2025-10-08 12:16:30','2025-10-08 12:16:30'),(9,3,22,'2025-10-08 12:16:30','2025-10-08 12:16:30'),(11,26,17,'2025-10-08 12:19:55','2025-10-08 12:19:55'),(12,23,17,'2025-10-08 12:20:01','2025-10-08 12:20:01'),(13,6,16,'2025-10-08 12:20:12','2025-10-08 12:20:12'),(14,6,20,'2025-10-08 12:20:12','2025-10-08 12:20:12'),(15,6,22,'2025-10-08 12:20:12','2025-10-08 12:20:12'),(16,28,17,'2025-10-08 12:20:20','2025-10-08 12:20:20'),(23,2,16,'2025-10-10 03:41:14','2025-10-10 03:41:14'),(24,2,24,'2025-10-10 03:41:14','2025-10-10 03:41:14'),(25,21,24,'2025-10-10 18:36:56','2025-10-10 18:36:56'),(26,19,20,'2025-10-11 03:34:02','2025-10-11 03:34:02');
/*!40000 ALTER TABLE `users_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'template'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-12 15:11:27
