CREATE DATABASE  IF NOT EXISTS `donoithat` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `donoithat`;
-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: donoithat
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) DEFAULT 'new',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'Đặng Giang Sơn','0326976832','tôi muốn được tư vấn sản phẩm bàn ăn đơn giản','2025-11-23 19:53:04','new');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
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
  `receiver_id` int NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,6,4,'xin chào','2025-11-23 20:01:54'),(2,4,6,'xin chào bạn cần gì','2025-11-23 20:02:10');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (1,1,8,'Sofa đơn giản',6500000.00,1),(2,2,2,'Giường ngủ cách tân',3000000.00,3),(3,2,1,'Giường ngủ cao cấp',4500000.00,2),(4,2,8,'Sofa đơn giản',6500000.00,1),(5,2,3,'Bàn ăn đơn giản hiện đại',3500000.00,1);
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `total_money` decimal(15,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Đang xử lý',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,'Đặng Giang Sơn','0326976832','Lưu Khê - Liên Bạt - Ứng Hòa - Hà nội','cod',6500000.00,'Đang giao hàng','2025-11-23 17:42:46'),(2,6,'Đới Thị Linh','0214403841','ưgrgin  ưgnro sdjg','cod',28000000.00,'Đang xử lý','2025-11-23 20:36:08');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `old_price` decimal(15,0) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT '1',
  `material` varchar(100) DEFAULT NULL,
  `dimensions` varchar(100) DEFAULT NULL,
  `warranty` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Giường ngủ cao cấp','Giường ngủ phong cách hiện đại, được làm từ gỗ Sồi Nga nhập khẩu cao cấp, chống mối mọt và cong vênh. Thiết kế sang trọng, phù hợp với mọi không gian phòng ngủ chung cư và biệt thự.',4500000.00,5850000,'picture/giuongngu2.jpg',1,'Gỗ Sồi Nga','1.8m x 2m','24 tháng'),(2,'Giường ngủ cách tân','Giường ngủ với thiết kế cách tân, nhẹ nhàng và tinh tế. Sử dụng vật liệu thân thiện với môi trường, mang lại cảm giác ấm cúng và thoải mái tối đa cho người dùng.',3000000.00,3900000,'picture/giuongngu1.jpg',1,'Gỗ MDF phủ Melamine','1.6m x 2m','12 tháng'),(3,'Bàn ăn đơn giản hiện đại','Bộ bàn ăn 4 ghế, thiết kế tối giản nhưng sang trọng. Mặt bàn chống thấm nước, dễ dàng vệ sinh. Phù hợp với không gian bếp nhỏ và vừa.',3500000.00,4550000,'picture/banan2.jpg',2,'Gỗ Cao su tự nhiên','1.2m x 0.8m','18 tháng'),(4,'Bàn ăn cách tân','Bộ bàn ăn 6 ghế, thiết kế độc đáo, chân bàn cách điệu. Mang lại vẻ đẹp khác biệt và là điểm nhấn cho phòng ăn của gia đình bạn.',5000000.00,NULL,'picture/banan1.jpg',2,'Gỗ Óc chó (Walnut)','1.6m x 0.9m','24 tháng'),(5,'Tủ quần áo hiện đại','Tủ quần áo 3 cánh mở, thiết kế cánh trượt tiết kiệm không gian. Bên trong chia ngăn hợp lý, có thanh treo đèn LED tự động.',7000000.00,NULL,'picture/tu2.jpg',1,'Gỗ công nghiệp HDF','1.8m x 2.2m','24 tháng'),(6,'Tủ quần áo cách tân mang thiên hướng hiện đại','Tủ quần áo lớn 4 cánh, kết hợp ngăn kéo tiện dụng. Phong cách Bắc Âu, màu sắc trung tính dễ dàng kết hợp với nội thất khác.',6500000.00,NULL,'picture/tu1.webp',1,'Gỗ sồi nhập khẩu','2m x 2.2m','12 tháng'),(7,'Sofa hiện đại','Bộ sofa chữ L, bọc da cao cấp, đệm mút D40 đàn hồi tốt. Thiết kế phù hợp với phòng khách rộng và muốn tối ưu hóa không gian.',8000000.00,NULL,'picture/sofa2.jpg',3,'Khung gỗ Dầu, Da PU','2.6m x 1.6m','36 tháng'),(8,'Sofa đơn giản','Bộ sofa đôi nhỏ gọn, bọc vải bố canvas, đệm rời dễ dàng tháo giặt. Phù hợp cho căn hộ mini hoặc góc đọc sách.',6500000.00,NULL,'picture/sofa1.jpg',3,'Khung gỗ tràm, Vải bố','1.8m x 0.8m','12 tháng'),(9,'Bàn làm việc cách tân','Bàn làm việc góc, có ngăn kéo và kệ sách tích hợp. Thiết kế tối ưu cho các công việc cần nhiều không gian lưu trữ.',3000000.00,NULL,'picture/banlamviec2.jpg',4,'Gỗ MDF chống ẩm','1.4m x 0.6m','18 tháng'),(10,'Bàn làm việc đơn giản','Bàn làm việc chân sắt, mặt gỗ đơn giản. Phong cách công nghiệp, tiết kiệm chi phí nhưng vẫn đảm bảo độ bền cao.',2100000.00,NULL,'picture/banlamviec1.jpg',4,'Mặt gỗ công nghiệp, Chân sắt sơn tĩnh điện','1.2m x 0.6m','12 tháng');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,1,'ưvrebyhyn','2025-11-23 19:16:09'),(2,1,'tôi cảm thấy rất ưng','2025-11-23 19:26:23'),(3,6,'quá ôk','2025-11-23 19:33:05');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'sondeptrai@gmail.com','sondeptrai@gmail.com','$2y$10$xxG/D7mDwFNQZ3OaG1y4oONExSC1Ht5hObRUpNhe728cJXuFYXgNC','user','2025-11-23 09:46:21'),(3,'ADMin gian','Dangnamson24@gmail.com','$2y$10$5UUzj5MX4aaKu4hyazNYbOXdBsC40DaKGsj/34PRDJhVV1lH/X5Su','admin','2025-11-23 17:18:11'),(4,'admin@sland.com','admin@sland.com','$2y$10$50A.YMFmQ6g6IK13Kr30fOIJYiz8TPUxGQGiaRJnVVxzeKQjdfJp6','admin','2025-11-23 17:21:50'),(5,'Linh Xinh Gái','doithilinh@gmail.com','$2y$10$pEOCLiIYHm5xjrhwThfsTOJ4mXqd5LsTbTZYgNzy91mV7MHWBgVxm','user','2025-11-23 19:18:58'),(6,'Linhxinhgai','doithilinh1@gmail.com','$2y$10$WmyWYQyT54qPUkmPK5JBLe4bzE0OznB1w8zoiyFnTIyw7KBh3NFnG','user','2025-11-23 19:21:12'),(7,'thang','sondeptrai1@gmail.com','$2y$10$/AKM8.WBhpwvp8w07njSvu7pfKRkf3sd4Tnj2ieZ5JMwaWU2uv2O.','user','2025-11-23 19:23:28');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-24  3:46:59
