-- MySQL dump 10.13  Distrib 8.0.25, for Win64 (x86_64)
--
-- Host: localhost    Database: forum_baza
-- ------------------------------------------------------
-- Server version	8.0.25

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
-- Table structure for table `komentar`
--

DROP TABLE IF EXISTS `komentar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `komentar` (
  `id_komentar` int NOT NULL AUTO_INCREMENT,
  `id_komentator` int NOT NULL,
  `id_objava` int NOT NULL,
  `tekst` text NOT NULL,
  PRIMARY KEY (`id_komentar`),
  KEY `fk_korisnik_has_objava_objava1_idx` (`id_objava`),
  KEY `fk_korisnik_has_objava_korisnik1_idx` (`id_komentator`),
  CONSTRAINT `fk_korisnik_has_objava_korisnik1` FOREIGN KEY (`id_komentator`) REFERENCES `korisnik` (`id_korisnik`),
  CONSTRAINT `fk_korisnik_has_objava_objava1` FOREIGN KEY (`id_objava`) REFERENCES `objava` (`id_objava`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `komentar`
--

LOCK TABLES `komentar` WRITE;
/*!40000 ALTER TABLE `komentar` DISABLE KEYS */;
INSERT INTO `komentar` VALUES (1,3,1,'Hvala! :)'),(2,2,2,'Što može biti nejasno?? Staviš post, ljudi komentiraju, voila.'),(3,4,2,'Mi smo simulacija :::(((('),(5,3,2,'O čemu ovaj?'),(6,5,1,'Wow kul'),(19,1,2,'Pozdrav svima!'),(24,13,9,'kul bro'),(26,1,10,'Bok zuzo!!!'),(27,3,10,'Bok!'),(28,4,3,'Kako ti mogu poomoći?'),(30,4,10,'Dobrodošao!'),(59,1,20,'<h1>YO</h1>'),(60,3,20,'Ti si čudan.');
/*!40000 ALTER TABLE `komentar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `korisnik`
--

DROP TABLE IF EXISTS `korisnik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `korisnik` (
  `id_korisnik` int NOT NULL AUTO_INCREMENT,
  `korime` varchar(45) NOT NULL,
  `sol` char(64) NOT NULL,
  `lozinka` char(64) NOT NULL,
  `email` varchar(200) NOT NULL,
  `datum_registracije` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_korisnik`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `korisnik`
--

LOCK TABLES `korisnik` WRITE;
/*!40000 ALTER TABLE `korisnik` DISABLE KEYS */;
INSERT INTO `korisnik` VALUES (1,'goran','3473a11b18ce04af78265e3566fc2e410a8dd031ec14d0547cc4235ab8305662','327ff04124fca4b4c565f1604c4efe88b545020e85f8155d272618def4931313','goran@g.com','2021-07-04 15:18:01'),(2,'mislav','3277fcd6a929a6da3be6e6993f32a469ccc54010f629f8b25d2347447774848e','060602f1ca802430cccf0fcb9a34ba20ac6c20ebdec74f59ba8840de5e0edd34','test@abc.com','2021-07-05 13:05:23'),(3,'ana','e8e38802be1e22d7839ce1ba7469fc9b63a28dd0c86f5bb324ad69b4ce775caf','7a55d26f5738186cadf58f515ba3f2fb07ca37c9d11b4a3fb93b744a3ece8373','sds@c.cc','2021-07-06 11:56:08'),(4,'jure','5967f191849eada6fd1ef05144efbb3948bc8f047cb0a585793bb7cd4841e401','d7715b41280d7a7fe86b95c4dce178214f6c943708c0c443161fe4eaebd9e9a3','test@test.com','2021-07-07 10:39:52'),(5,'željko','de35983d9c2506be41add6687304d16a687c5e1d6a8035a55abb96c055f9a3fd','0e33f0fa946f9b00e3b53b709c9ac5195dc072ed42f2f62322f755674c27ffb0','test@test.com','2021-07-05 13:37:28'),(6,'irena','3385ffdd88873bfcf6032872b83c1386e8823f21f5f768b935d38a4f882f5f58','ace507b78f0abacea9294604739f1abba43f736970c20f2cd7fd8ddcda9f6a38','test@test.com','2021-07-07 10:40:37'),(7,'bobo','b39036db92e372ea765f62f45582af0dd58f35d1bcdd81f7f25001da3ee19ba0','66a610f99034a893e1559ff6d914da97d796eaffe187dcf07837f094e7162a02','test@test.com','2021-07-07 10:41:26'),(8,'grgo','7a17f51e80f95be95b27a60e8a71f8cab92611915761d25c7ddb7bab71474b6e','4e1d0737a1ef03a9e219264fe7f2a9f2ec757c326fa3c1c3514fbfbf6dde996f','test@test.com','2021-07-07 10:41:29'),(13,'mrgo','d75b21fc430663332a1329e3864c834ca1da56f462127f64d8c4322a9f24d6ea','250def4e5a7542e89e25d486493ae6acbdce7904e394581df2018f60cf85ce0b','test@test.com','2021-07-08 12:47:03'),(14,'zuzo','27867811255e7f25eeda497c13f9ee6b1fb8cebe1b9476e300551b4b547f2663','f3ef79d8dc688b08b3bd880fec37c1b7c08066d159babe3bdb67ab79454f958b','zuzo@mail.com','2021-07-09 11:22:39');
/*!40000 ALTER TABLE `korisnik` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objava`
--

DROP TABLE IF EXISTS `objava`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `objava` (
  `id_objava` int NOT NULL AUTO_INCREMENT,
  `naslov` varchar(45) NOT NULL,
  `tekst` text NOT NULL,
  `datum_objave` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_objavitelj` int NOT NULL,
  PRIMARY KEY (`id_objava`),
  KEY `fk_objava_korisnik_idx` (`id_objavitelj`),
  CONSTRAINT `fk_objava_korisnik` FOREIGN KEY (`id_objavitelj`) REFERENCES `korisnik` (`id_korisnik`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objava`
--

LOCK TABLES `objava` WRITE;
/*!40000 ALTER TABLE `objava` DISABLE KEYS */;
INSERT INTO `objava` VALUES (1,'Pozdrav svima!','Dobrodošli na forum! Slobodno pitajte što želite u obliku nove objave!','2021-07-05 13:05:01',1),(2,'Što je ovo?','Kako funkcionira ovaj forum?','2021-07-05 13:07:54',7),(3,'Treba mi pomoć s Javascriptom!','Ne razumijem arrow funkciju??? welp','2021-07-05 13:18:18',5),(9,'Poyy ekipa','Kako ide?','2021-07-08 10:43:38',1),(10,'Došao sam','Pozdrav svima ekipa! \nKako ste mi? :)','2021-07-09 11:23:06',14),(20,'Objava preko Javascripta','Tekst objave.','2021-08-10 15:16:21',1),(23,'Ovo nećete vjerovati!','<a href=\'stranica.com\' onmouseover=\'javascript:alert(\"IMATE VIRUS NA RAČUNALU\")\'>BRZO OVO NEĆETE VJEROVATI ŠTO JE NA LINKU</a>','2021-08-10 16:26:17',3),(24,'Kul objava','Veoma kul objava, thx for being here.','2021-08-10 16:43:02',2);
/*!40000 ALTER TABLE `objava` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-10 18:45:17
