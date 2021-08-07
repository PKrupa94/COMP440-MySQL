-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: project
-- ------------------------------------------------------
-- Server version	8.0.18

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
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `blogid` int(10) unsigned NOT NULL,
  `subject` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pdate` date DEFAULT NULL,
  `userid` int(10) DEFAULT NULL,
  PRIMARY KEY (`blogid`),
  KEY `idx_blogs_pdate` (`pdate`),
  KEY `blogs_ibfk_1_idx` (`userid`),
  CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,'Hello World','Hey everyone, this is my first blog. Hello world and all who inhabit it!','2020-03-15',6),(2,'I love cats!','Cats are amazing. They\'re awesome, and fuzzy, and cute. Who DOESN\'T love cats?','2020-03-17',3),(3,'Dogs are the best.','So I saw a post the other day talking about cats. Now, I love cats. They\'re great. But here\'s the thing: dogs are just the best, okay? There\'s no question about it. That is all.','2020-03-19',4),(4,'I am the night.','To all you lowly criminals out there, this is a warning to know I am watching. I am justice. I am righteousness. I am the NIGHT.','2020-03-24',1),(5,'Waka waka','waka waka waka waka waka waka waka waka waka waka waka waka waka waka waka waka','2020-03-31',9),(6,'Who is this Bob guy?','Decided to start tracking down this mysterious human known as \'Bob.\' Who is Bob? What does he do? WHY does he do it? There is a lot of mystery surrounding this person, and I will be going into detail in future posts. Stay tuned!','2020-04-02',8),(7,'Re: I love cats.','A reader recently reached out to me about my last post. To be clear, I\'m not dissing our canine companions! But we\'ve got to be honest here, why are cats better? They\'re smart, affectionate, and great cuddle partners. Cats are better. It\'s just fact.','2020-04-04',3),(8,'Scooby Dooby Doo!','The search for scooby snacks: Where did they go? I know this whole quarantine thing is affecting businesses, but aren\'t scooby snacks counted as an essential service? Please post below if you find anything! I\'m going crazy here!','2020-04-05',10),(9,'Bob Update','Dear readers, I know you have been waiting anxiously for an update on Bob, but there is not much to share so far. He appears to have little to no online presence. Just a clarification: I am decidedly NOT Bob. Thanks all. Stay tuned for more!','2020-04-06',8),(10,'Lizard People.','What are your guys\' thoughts on them? I, for one, welcome out reptitlian overlords.','2020-04-12',5);
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogstags`
--

DROP TABLE IF EXISTS `blogstags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogstags` (
  `blogid` int(10) unsigned NOT NULL,
  `tag` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`blogid`,`tag`),
  CONSTRAINT `blogstags_ibfk_1` FOREIGN KEY (`blogid`) REFERENCES `blogs` (`blogid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogstags`
--

LOCK TABLES `blogstags` WRITE;
/*!40000 ALTER TABLE `blogstags` DISABLE KEYS */;
INSERT INTO `blogstags` VALUES (1,'hello world'),(2,'animals'),(2,'cats'),(3,'animals'),(3,'dogs'),(4,'crime'),(4,'justice'),(5,'cartoon'),(5,'waka'),(6,'bob'),(6,'update'),(7,'cats'),(7,'dogs'),(8,'dogs'),(8,'quarantine'),(8,'scooby snacks'),(9,'bob'),(9,'update'),(10,'lizards');
/*!40000 ALTER TABLE `blogstags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `commentid` int(10) NOT NULL AUTO_INCREMENT,
  `sentiment` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cdate` date DEFAULT NULL,
  `blogid` int(10) unsigned DEFAULT NULL,
  `authorid` int(10) DEFAULT NULL,
  PRIMARY KEY (`commentid`),
  KEY `comments_ibfk_2` (`blogid`),
  KEY `test_idx` (`authorid`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`blogid`) REFERENCES `blogs` (`blogid`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`authorid`) REFERENCES `users` (`userid`),
  CONSTRAINT `sentiment_types` CHECK ((`sentiment` in (_utf8mb4'negative',_utf8mb4'positive')))
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,'negative','Cats are cool and all, but dogs are where it\'s at.','2020-03-17',2,4),(2,'negative','What\'s all the hype about? Cats are clearly superior.','2020-03-20',3,3),(3,'positive','Nice.','2020-03-20',4,10),(4,'positive','Who IS Bob? I can\'t wait to find out.','2020-04-02',6,6),(5,'negative','I guess cat people just don\'t know what they\'re missing.','2020-04-05',7,4),(6,'positive','This is totally unrelated, but I just wanted to say I am a HUGE fan of yours. I love your work!','2020-04-05',8,4),(7,'positive','Have you checked out Dog-Mart? They\'ve got everything.','2020-04-06',8,7),(8,'negative','I was hoping there\'d be more of an update. Sorry, Bob.','2020-04-07',9,6),(9,'positive','I think they\'re all secretly cats. Open your eyes, sheeple!','2020-04-13',10,4),(10,'negative','Who? Me? Multimillionaire philanthropist of Arkham? A lizard person? Nope. Nothing to see here!','2020-04-15',10,1);
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follows`
--

DROP TABLE IF EXISTS `follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `follows` (
  `leaderid` int(10) NOT NULL,
  `followerid` int(10) NOT NULL,
  PRIMARY KEY (`leaderid`,`followerid`),
  KEY `follows_ibfk_2_idx` (`followerid`),
  CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`leaderid`) REFERENCES `users` (`userid`),
  CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`followerid`) REFERENCES `users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follows`
--

LOCK TABLES `follows` WRITE;
/*!40000 ALTER TABLE `follows` DISABLE KEYS */;
INSERT INTO `follows` VALUES (6,2),(1,3),(4,3),(3,4),(6,5),(9,7),(2,8),(5,8),(1,9),(10,9),(4,10),(9,10);
/*!40000 ALTER TABLE `follows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hobbies`
--

DROP TABLE IF EXISTS `hobbies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hobbies` (
  `userid` int(10) NOT NULL,
  `hobby` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`hobby`,`userid`),
  KEY `test_idx` (`userid`),
  CONSTRAINT `hobbies_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`),
  CONSTRAINT `hobby_types` CHECK ((`hobby` in (_utf8mb4'hiking',_utf8mb4'swimming',_utf8mb4'calligraphy',_utf8mb4'bowling',_utf8mb4'movie',_utf8mb4'cooking',_utf8mb4'dancing')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hobbies`
--

LOCK TABLES `hobbies` WRITE;
/*!40000 ALTER TABLE `hobbies` DISABLE KEYS */;
INSERT INTO `hobbies` VALUES (1,'movie'),(2,'movie'),(3,'movie'),(4,'hiking'),(5,'dancing'),(5,'movie'),(6,'hiking'),(7,'bowling'),(8,'calligraphy'),(9,'dancing'),(9,'movie'),(10,'cooking');
/*!40000 ALTER TABLE `hobbies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;

/* this one is given by prof */;

-- CREATE TABLE `users` (
--   `userid` int(10) NOT NULL AUTO_INCREMENT,
--   `username` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
--   `password` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
--   `email` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
--   PRIMARY KEY (`userid`),
--   UNIQUE KEY `username_UNIQUE` (`username`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*our user table*/;

CREATE TABLE `users` (
  `userid` int(10) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdOn` timestamp NOT NULL DEFAULT current_timestamp(),
   PRIMARY KEY (`userid`),
   UNIQUE KEY `user_info` (`username`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
-- INSERT INTO `users` VALUES (1,'batman','1234','nananana@batman.com'),(2,'bob','12345','bobthatsme@yahoo.com'),(3,'catlover','abcd','catlover@whiskers.com'),(4,'doglover','efds','doglover@bark.net'),(5,'jdoe','25478','jane@doe.com'),(6,'jsmith','1111','jsmith@gmail.com'),(7,'matty','2222','matty@csun.edu'),(8,'notbob','5555','stopcallingmebob@yahoo.com'),(9,'pacman','9999','pacman@gmail.com'),(10,'scooby','8888','scooby@doo.net');
INSERT INTO `users` (`userid`, `firstname`, `lastname`, `username`, `email`, `password`, `createdOn`) VALUES
(12, 'testuser', 'test', 'testuser1245', 'test@gmail.com', '$2y$10$yOjv5gp2Nt/xxUO3aK3NvOS/CC5q4f3wOSaKNUQ1MEuHAKvku8frS', '2021-07-28 21:24:10');
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

-- Dump completed on 2021-07-29  0:31:33
