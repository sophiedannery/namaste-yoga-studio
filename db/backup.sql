-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: namaste_test2
-- ------------------------------------------------------
-- Server version	8.0.43

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
-- Table structure for table `class_type`
--

DROP TABLE IF EXISTS `class_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `class_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `style` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_type`
--

LOCK TABLES `class_type` WRITE;
/*!40000 ALTER TABLE `class_type` DISABLE KEYS */;
INSERT INTO `class_type` VALUES (1,'Hatha Découverte','Hatha','Débutant','Bases, respiration, postures clés.','2025-10-01 10:00:00','2025-10-01 10:00:00'),(2,'Vinyasa Flow','Vinyasa','Intermédiaire','Séquences dynamiques en musique.','2025-10-01 10:05:00','2025-10-01 10:05:00'),(3,'Yin Relax','Yin','Tous niveaux','Étirements tenus longtemps, relaxation.','2025-10-01 10:10:00','2025-10-01 10:10:00');
/*!40000 ALTER TABLE `class_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20251026130413','2025-10-26 13:08:24',200),('DoctrineMigrations\\Version20251027110416','2025-10-27 11:09:03',300),('DoctrineMigrations\\Version20251027123641','2025-10-27 12:37:06',97),('DoctrineMigrations\\Version20251027124521','2025-10-27 12:46:40',87),('DoctrineMigrations\\Version20251027132439','2025-10-27 13:27:47',304),('DoctrineMigrations\\Version20251027141748','2025-10-27 14:18:39',648),('DoctrineMigrations\\Version20251027142752','2025-10-27 14:28:13',446),('DoctrineMigrations\\Version20251027143330','2025-10-27 14:34:12',244);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `session_id` int NOT NULL,
  `cancelled_by_id` int DEFAULT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booked_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `cancelled_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_reservation_student_session` (`student_id`,`session_id`),
  KEY `IDX_42C84955CB944F1A` (`student_id`),
  KEY `IDX_42C84955613FECDF` (`session_id`),
  KEY `IDX_42C84955187B2D12` (`cancelled_by_id`),
  CONSTRAINT `FK_42C84955187B2D12` FOREIGN KEY (`cancelled_by_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_42C84955613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`),
  CONSTRAINT `FK_42C84955CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation`
--

LOCK TABLES `reservation` WRITE;
/*!40000 ALTER TABLE `reservation` DISABLE KEYS */;
INSERT INTO `reservation` VALUES (1,2,1,NULL,'CONFIRMED','2025-10-15 10:00:00',NULL,'2025-10-15 10:00:00','2025-10-15 10:00:00'),(2,5,1,NULL,'CONFIRMED','2025-10-16 11:30:00',NULL,'2025-10-16 11:30:00','2025-10-16 11:30:00'),(3,6,2,NULL,'CONFIRMED','2025-10-16 12:00:00',NULL,'2025-10-16 12:00:00','2025-10-16 12:00:00'),(4,2,4,1,'CANCELLED','2025-10-20 09:00:00','2025-10-24 12:05:00','2025-10-20 09:00:00','2025-10-24 12:05:00'),(5,5,3,NULL,'CONFIRMED','2025-10-18 14:00:00',NULL,'2025-10-18 14:00:00','2025-10-18 14:00:00'),(6,6,6,NULL,'CONFIRMED','2025-10-19 10:15:00',NULL,'2025-10-19 10:15:00','2025-10-19 10:15:00');
/*!40000 ALTER TABLE `reservation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `class_type_id` int NOT NULL,
  `rating` int NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci,
  `statut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_794381C6CB944F1A` (`student_id`),
  KEY `IDX_794381C639EB6F` (`class_type_id`),
  CONSTRAINT `FK_794381C639EB6F` FOREIGN KEY (`class_type_id`) REFERENCES `class_type` (`id`),
  CONSTRAINT `FK_794381C6CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
INSERT INTO `review` VALUES (1,2,1,5,'Super pour débuter !','PUBLISHED','2025-10-21 09:00:00','2025-10-21 09:30:00'),(2,5,2,4,'Dynamique et ludique.','PUBLISHED','2025-10-21 10:00:00','2025-10-21 10:00:00'),(3,6,3,5,'Très relaxant, parfait le soir.','PENDING','2025-10-22 18:00:00','2025-10-22 18:00:00'),(4,2,2,2,'Cours annulé, déçu…','PUBLISHED','2025-10-25 20:00:00','2025-10-25 20:00:00');
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name_room` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note_room` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room`
--

LOCK TABLES `room` WRITE;
/*!40000 ALTER TABLE `room` DISABLE KEYS */;
INSERT INTO `room` VALUES (1,'Lotus','Salle lumineuse, 15 tapis.','2025-10-01 10:15:00','2025-10-01 10:15:00'),(2,'Bamboo','Salle cosy, 10 tapis.','2025-10-01 10:16:00','2025-10-01 10:16:00');
/*!40000 ALTER TABLE `room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `session` (
  `id` int NOT NULL AUTO_INCREMENT,
  `teacher_id` int NOT NULL,
  `cancelled_by_id` int DEFAULT NULL,
  `class_type_id` int NOT NULL,
  `room_id` int DEFAULT NULL,
  `start_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `end_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `capacity` int NOT NULL,
  `price` decimal(7,2) DEFAULT NULL,
  `details` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cancelled_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `cancel_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_D044D5D441807E1D` (`teacher_id`),
  KEY `IDX_D044D5D4187B2D12` (`cancelled_by_id`),
  KEY `IDX_D044D5D439EB6F` (`class_type_id`),
  KEY `IDX_D044D5D454177093` (`room_id`),
  CONSTRAINT `FK_D044D5D4187B2D12` FOREIGN KEY (`cancelled_by_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_D044D5D439EB6F` FOREIGN KEY (`class_type_id`) REFERENCES `class_type` (`id`),
  CONSTRAINT `FK_D044D5D441807E1D` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_D044D5D454177093` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
INSERT INTO `session` VALUES (1,4,NULL,1,1,'2025-11-03 18:00:00','2025-11-03 19:15:00',15,18.00,'Cours du soir Hatha.','SCHEDULED',NULL,NULL,'2025-10-10 09:00:00','2025-10-10 09:00:00'),(2,3,NULL,2,2,'2025-11-05 07:30:00','2025-11-05 08:30:00',10,20.00,'Morning flow.','SCHEDULED',NULL,NULL,'2025-10-10 09:05:00','2025-10-10 09:05:00'),(3,3,NULL,3,1,'2025-10-20 20:00:00','2025-10-20 21:15:00',15,16.00,'Session relax du lundi.','COMPLETED',NULL,NULL,'2025-10-05 09:10:00','2025-10-21 22:00:00'),(4,4,1,2,2,'2025-10-25 18:00:00','2025-10-25 19:15:00',10,20.00,'Annulé pour maintenance.','CANCELLED','2025-10-24 12:00:00','Plafond à réparer','2025-10-05 09:15:00','2025-10-24 12:00:00'),(5,4,NULL,1,2,'2025-11-10 12:15:00','2025-11-10 13:15:00',10,18.00,'Hatha lunch break.','SCHEDULED',NULL,NULL,'2025-10-12 10:00:00','2025-10-12 10:00:00'),(6,3,NULL,3,1,'2025-11-12 19:00:00','2025-11-12 20:15:00',15,16.00,'Yin en fin de journée.','SCHEDULED',NULL,NULL,'2025-10-12 10:05:00','2025-10-12 10:05:00');
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suspension`
--

DROP TABLE IF EXISTS `suspension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suspension` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `admin_res_id` int NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `end_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_82AF0500CB944F1A` (`student_id`),
  KEY `IDX_82AF05001BB8D7FD` (`admin_res_id`),
  CONSTRAINT `FK_82AF05001BB8D7FD` FOREIGN KEY (`admin_res_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_82AF0500CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suspension`
--

LOCK TABLES `suspension` WRITE;
/*!40000 ALTER TABLE `suspension` DISABLE KEYS */;
INSERT INTO `suspension` VALUES (1,6,1,'No-show répétés','2025-10-26 00:00:00','2025-11-02 23:59:59','ACTIVE','2025-10-26 08:00:00','2025-10-26 08:00:00');
/*!40000 ALTER TABLE `suspension` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `bio` longtext COLLATE utf8mb4_unicode_ci,
  `specialties` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin@namaste.com','[\"ROLE_ADMIN\"]','$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG','Aline','Admin',NULL,1,NULL,NULL,'2025-10-01 09:00:00','2025-10-01 09:00:00'),(2,'maddie@mail.com','[\"ROLE_USER\"]','$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG','Maddie','Luna',NULL,1,NULL,NULL,'2025-10-01 09:10:00','2025-10-01 09:10:00'),(3,'sophie@namaste.com','[\"ROLE_TEACHER\", \"ROLE_USER\"]','$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG','Sophie','Durand',NULL,1,'Enseigne Vinyasa & Yin.','Vinyasa,Yin','2025-10-01 09:20:00','2025-10-01 09:20:00'),(4,'lucas@namaste.com','[\"ROLE_TEACHER\", \"ROLE_USER\"]','$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG','Lucas','Bernard',NULL,1,'Spécialiste Hatha.','Hatha','2025-10-01 09:25:00','2025-10-01 09:25:00'),(5,'emma@mail.com','[\"ROLE_USER\"]','$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG','Emma','Leroy',NULL,1,NULL,NULL,'2025-10-01 09:30:00','2025-10-01 09:30:00'),(6,'martin@mail.com','[\"ROLE_USER\"]','$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG','Martin','Morel',NULL,1,NULL,NULL,'2025-10-01 09:35:00','2025-10-01 09:35:00');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-28 10:45:56
