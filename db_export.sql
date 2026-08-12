-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: myitcomapny
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `excerpt` text NOT NULL,
  `content` longtext NOT NULL,
  `author` varchar(100) NOT NULL,
  `status` enum('draft','published') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seo_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,'How AI is Revolutionizing Enterprise IT Infrastructure in 2026','how-ai-revolutionizing-enterprise-it','Enterprise IT','https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80','Explore how artificial intelligence is transforming enterprise IT operations, optimizing hybrid clouds, and paving the way for autonomous, self-healing systems.','<p>Artificial intelligence is no longer just a buzzword; it\'s the core engine driving enterprise IT infrastructure. In 2026, we see a migration toward autonomous cloud networking and self-healing servers.</p>','Sarah Connor (CTO)','published','2026-07-21 10:42:59','AI Revolution in Enterprise IT Infrastructure | MyITCompany','Understand how AI is driving next-gen IT infrastructure in 2026. Learn about self-healing servers, edge nodes, and cloud optimization.'),(4,'What is Lorem Ipsum?','what-is-lorem-ipsum','Enterprise IT','','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library in London, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset\'s','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library in London, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset\'s Body Type sheets. It has survived not only many decades, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised thanks to these sheets and more recently with desktop publishing software like Aldus PageMaker and Microsoft Word including versions of Lorem Ipsum.','Jack Devlin (Super Admin)','published','2026-07-21 11:07:42','What is Lorem Ipsum?','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library in London, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset\'s'),(6,'Where does it come from?','where-does-it-come-from','','uploads/blog/blog_20260722_131024_b3386f87.jpeg','Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.','Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.\n\nThe standard chunk of Lorem Ipsum used since 1966 is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\n\nWhere can I get some?\nThere are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.\n\n<ul>\n  <li><h3><b><u>text</u></b></h3></li>\n  <li>Item 2</li>\n</ul>','Jack Devlin (Super Admin)','published','2026-07-22 11:10:27','Where does it come from?','Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.');
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_submissions`
--

DROP TABLE IF EXISTS `contact_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_submissions`
--

LOCK TABLES `contact_submissions` WRITE;
/*!40000 ALTER TABLE `contact_submissions` DISABLE KEYS */;
INSERT INTO `contact_submissions` VALUES (1,';lk;lk','lkjlkjl@gmail.com','sfsdfsdfds','fadsfafgasdfasdfdasdf',0,'2026-07-27 11:38:46'),(2,'habiba','digiraremarketing@gmail.com','marketing','Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.',0,'2026-07-30 10:39:05'),(3,'habiba','digiraremarketing@gmail.com','marketing','Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.',1,'2026-07-30 10:39:08');
/*!40000 ALTER TABLE `contact_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_settings`
--

DROP TABLE IF EXISTS `email_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `mail_engine` enum('smtp','mail') DEFAULT 'smtp',
  `smtp_host` varchar(255) DEFAULT 'smtp.gmail.com',
  `smtp_port` int(11) DEFAULT 587,
  `smtp_encryption` enum('tls','ssl','none') DEFAULT 'tls',
  `smtp_auth` tinyint(1) DEFAULT 1,
  `smtp_username` varchar(255) DEFAULT '',
  `smtp_password` varchar(255) DEFAULT '',
  `from_name` varchar(255) DEFAULT 'DigiRare Technologies',
  `from_email` varchar(255) DEFAULT 'hello@digirare.com',
  `admin_email` varchar(255) DEFAULT 'hello@digirare.com',
  `is_enabled` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_settings`
--

LOCK TABLES `email_settings` WRITE;
/*!40000 ALTER TABLE `email_settings` DISABLE KEYS */;
INSERT INTO `email_settings` VALUES (1,'smtp','smtp.gmail.com',587,'tls',1,'','','DigiRare Technologies','digiraremarketing@gmail.com','digiraremarketing@gmail.com',1,'2026-08-03 09:18:49');
/*!40000 ALTER TABLE `email_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('draft','published') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,'What services does DigiRare Technologies provide?','We specialize in WordPress customized developments, custom web applications (CRM, dashboard system), secure e-commerce portals, landing page conversions, corporate graphic branding, Canva templates, and monthly website support.',1,'published','2026-07-21 10:42:59'),(2,'How do we begin a project estimate with your team?','Simply fill out the Estimate form on our homepage or click the Free Consultation button to supply your project scopes. Our architects will contact you within 24 hours to schedule a call.',2,'published','2026-07-21 10:42:59'),(3,'Do you offer hosting and monthly database updates?','Yes! We offer proactive support packages including weekly offsite cloud backups, database defragmentation, security updates, and performance checks.',3,'published','2026-07-21 10:42:59');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `client` varchar(100) DEFAULT NULL,
  `project_date` date DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `status` enum('draft','published') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `year` varchar(10) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'Mobile Payment Portal',NULL,'UI / UX Design','https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80','Fintech Client','2026-05-10','#','Fintech payment portal dashboard with real-time graphs and multi-currency secure gateway interfaces.','published','2026-07-21 10:42:59',NULL,NULL),(2,'ERP Enterprise Architecture',NULL,'Software Engineering','https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80','Global Logistics Inc.','2026-04-15','#','Enterprise logistics & inventory controller system deployed on automated AWS Kubernetes servers.','published','2026-07-21 10:42:59',NULL,NULL),(3,'Multi-Region Database Sync',NULL,'Cloud / DevOps','https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80','Transit Corp','2026-06-20','#','Mirroring transaction processing systems across multiple globally distributed clusters with absolute latency reduction.','published','2026-07-21 10:42:59',NULL,NULL),(4,'E-Commerce User Journey',NULL,'UI / UX Design','https://images.unsplash.com/photo-1542744094-3a31f103e35f?auto=format&fit=crop&w=800&q=80','Aero Retail','2026-02-18','#','Customer retail layouts and conversion rate testing configurations implemented inside standard templates.','published','2026-07-21 10:42:59',NULL,NULL);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `robots_settings`
--

DROP TABLE IF EXISTS `robots_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `robots_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `robots_content` text DEFAULT NULL,
  `sitemap_url` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `robots_settings`
--

LOCK TABLES `robots_settings` WRITE;
/*!40000 ALTER TABLE `robots_settings` DISABLE KEYS */;
INSERT INTO `robots_settings` VALUES (1,'User-agent: *\nAllow: /\nDisallow: /cms-dashboard/\nDisallow: /includes/\n\nSitemap: http://localhost/myitcomapny/sitemap.xml','http://localhost/myitcomapny/sitemap.xml','2026-07-24 11:17:00');
/*!40000 ALTER TABLE `robots_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_analytics`
--

DROP TABLE IF EXISTS `seo_analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_analytics` (
  `id` int(11) NOT NULL DEFAULT 1,
  `ga_tracking_id` varchar(100) DEFAULT NULL,
  `gtm_container_id` varchar(100) DEFAULT NULL,
  `fb_pixel_id` varchar(100) DEFAULT NULL,
  `clarity_id` varchar(100) DEFAULT NULL,
  `hotjar_id` varchar(100) DEFAULT NULL,
  `custom_head_script` text DEFAULT NULL,
  `custom_body_script` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_analytics`
--

LOCK TABLES `seo_analytics` WRITE;
/*!40000 ALTER TABLE `seo_analytics` DISABLE KEYS */;
INSERT INTO `seo_analytics` VALUES (1,'','',NULL,NULL,NULL,NULL,NULL,'2026-07-24 11:17:00');
/*!40000 ALTER TABLE `seo_analytics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_audit`
--

DROP TABLE IF EXISTS `seo_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `score` int(11) DEFAULT 85,
  `audit_data_json` longtext DEFAULT NULL,
  `recommendations_json` longtext DEFAULT NULL,
  `last_audit_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_audit`
--

LOCK TABLES `seo_audit` WRITE;
/*!40000 ALTER TABLE `seo_audit` DISABLE KEYS */;
/*!40000 ALTER TABLE `seo_audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_global`
--

DROP TABLE IF EXISTS `seo_global`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_global` (
  `id` int(11) NOT NULL DEFAULT 1,
  `website_name` varchar(255) NOT NULL DEFAULT 'DigiRare Technologies',
  `website_title` varchar(255) NOT NULL DEFAULT 'DigiRare | NextGen IT & Software Solutions',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `website_url` varchar(255) NOT NULL DEFAULT 'http://localhost/myitcomapny',
  `canonical_url` varchar(255) DEFAULT NULL,
  `default_keywords` text DEFAULT NULL,
  `author` varchar(100) NOT NULL DEFAULT 'DigiRare Solutions',
  `language` varchar(10) NOT NULL DEFAULT 'en',
  `charset` varchar(20) NOT NULL DEFAULT 'UTF-8',
  `theme_color` varchar(20) NOT NULL DEFAULT '#0b1315',
  `favicon_url` varchar(255) DEFAULT NULL,
  `apple_touch_icon` varchar(255) DEFAULT NULL,
  `default_social_image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_global`
--

LOCK TABLES `seo_global` WRITE;
/*!40000 ALTER TABLE `seo_global` DISABLE KEYS */;
INSERT INTO `seo_global` VALUES (1,'Technologies',' Technologies | Enterprise Software & IT Solutions','','Technologies provides custom software development, web development, mobile app development, cloud computing, cybersecurity, UI/UX design, and digital transformation services to help businesses grow with innovative technology solutions.','http://localhost/myitcomapny','','custom software development, IT company, software house, web development, mobile app development, cloud computing, cybersecurity, enterprise software, UI UX design, digital transformation, IT consulting, software solutions','DigiRare Solutions','en','UTF-8','#0b1315','https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=48&q=80','','https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=630','2026-07-28 10:42:25');
/*!40000 ALTER TABLE `seo_global` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_image_settings`
--

DROP TABLE IF EXISTS `seo_image_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_image_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `default_alt_pattern` varchar(255) DEFAULT '{title} - DigiRare Technologies',
  `lazy_loading_enabled` tinyint(1) DEFAULT 1,
  `webp_support` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_image_settings`
--

LOCK TABLES `seo_image_settings` WRITE;
/*!40000 ALTER TABLE `seo_image_settings` DISABLE KEYS */;
INSERT INTO `seo_image_settings` VALUES (1,'{title} - DigiRare Technologies',1,1,'2026-07-24 11:42:48');
/*!40000 ALTER TABLE `seo_image_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_pages`
--

DROP TABLE IF EXISTS `seo_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_key` varchar(100) NOT NULL,
  `page_name` varchar(100) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `canonical_url` varchar(255) DEFAULT NULL,
  `og_title` varchar(255) DEFAULT NULL,
  `og_description` text DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `twitter_title` varchar(255) DEFAULT NULL,
  `twitter_description` text DEFAULT NULL,
  `twitter_image` varchar(255) DEFAULT NULL,
  `schema_type` varchar(50) DEFAULT 'WebPage',
  `schema_custom_json` longtext DEFAULT NULL,
  `is_indexed` tinyint(1) DEFAULT 1,
  `is_followed` tinyint(1) DEFAULT 1,
  `sitemap_priority` varchar(10) DEFAULT '0.8',
  `sitemap_changefreq` varchar(20) DEFAULT 'weekly',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_key` (`page_key`)
) ENGINE=InnoDB AUTO_INCREMENT=4924 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_pages`
--

LOCK TABLES `seo_pages` WRITE;
/*!40000 ALTER TABLE `seo_pages` DISABLE KEYS */;
INSERT INTO `seo_pages` VALUES (1,'index.php','index.php',' Technologies | Custom Software Development & IT Solutions','Technologies provides custom software development, web development, mobile app development, cloud computing, cybersecurity, UI/UX design, and digital transformation services to help businesses grow with innovative technology solutions.','custom software development, IT company, software house, web development, mobile app development, cloud computing, cybersecurity, enterprise software, UI UX design, digital transformation, IT consulting, software solutions','','','','','','','','WebPage',NULL,1,1,'1.0','daily','2026-07-28 10:42:42'),(2,'about.php','About Us','About Us | DigiRare Technologies','Learn about our engineering expertise, company culture, and the dedicated software developers crafting world-class digital solutions.','about digirare, IT team, software engineers, company profile',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.8','monthly','2026-07-24 11:17:00'),(3,'services.php','Services & Solutions','Professional IT Services & Cloud Solutions | DigiRare Technologies','Comprehensive technical services including custom software engineering, DevOps automation, cybersecurity audits, and UI/UX design.','it services, cloud deployment, web development, cybersecurity audit',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.9','weekly','2026-07-24 11:17:00'),(4,'projects.php','Case Studies & Projects','Portfolio & System Case Studies | DigiRare Technologies','Explore our portfolio of enterprise applications, cloud infrastructure deployments, and scalable web solutions delivered worldwide.','portfolio, IT case studies, software projects, web applications',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.9','weekly','2026-07-24 11:17:00'),(5,'blog.php','Tech Insights & Blog','Tech Insights & Dev Articles | DigiRare Technologies','Deep dive into cloud architecture trends, DevOps practices, software design patterns, and enterprise cybersecurity insights.','tech blog, programming tutorials, devops guides, software engineering blog',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.8','daily','2026-07-24 11:17:00'),(6,'single-blog.php','Single Blog Detail Page','Tech Article | DigiRare Insights','Detailed technical breakdown and software engineering guide by DigiRare experts.','tech post, software guide, IT article',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.7','weekly','2026-07-24 11:17:00'),(7,'contact.php','Contact Us','Contact Us & Free Tech Consultation | DigiRare Technologies','Connect with our solutions engineering group. Schedule a free technical consultation and request project cost estimation.','contact IT company, technical support, free consultation, software estimate',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.8','monthly','2026-07-24 11:17:00'),(67,'solutions.php','Enterprise Solutions','Enterprise Software & Cloud Architecture Solutions | DigiRare','Scalable enterprise software systems, microservices infrastructure, and automated cloud deployments tailored for high growth.','enterprise solutions, microservices, cloud architecture, system design',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.9','weekly','2026-07-24 11:42:48'),(69,'team.php','Our Engineering Team','Meet Our Software Engineering Experts | DigiRare Technologies','Meet the brilliant minds, system architects, UI designers, and security engineers driving innovation at DigiRare.','it team, software engineers, devops team, tech leads',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.7','monthly','2026-07-24 11:42:48'),(70,'pricing.php','Pricing & Project Plans','Flexible IT Service Pricing & Project Estimates | DigiRare','Transparent project pricing models, dedicated developer retainers, and enterprise software engineering contracts.','it pricing, software cost estimate, developer retainer, tech rates',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.8','monthly','2026-07-24 11:42:48'),(71,'careers.php','Careers & Join Us','Join Our Engineering Team | Careers at DigiRare Technologies','Explore exciting career opportunities for senior full-stack developers, cloud architects, and UI/UX designers.','it careers, software engineer jobs, devops jobs, tech hiring',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.7','monthly','2026-07-24 11:42:48'),(73,'blog-category.php','Blog Category Archive','Category Topics | DigiRare Insights','Browse tech articles grouped by category including software engineering, cloud architecture, and cybersecurity.','blog categories, dev topics, tech archives',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.7','weekly','2026-07-24 11:42:48'),(76,'privacy.php','Privacy Policy','Privacy Policy & Data Security | DigiRare Technologies','Read how DigiRare Technologies protects client data, respects user privacy, and adheres to global security standards.','privacy policy, data protection, gdpr compliance',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.5','yearly','2026-07-24 11:42:48'),(77,'terms.php','Terms & Conditions','Terms & Conditions of Service | DigiRare Technologies','Review our master service agreement, terms of service, and software licensing conditions.','terms of service, service agreement, terms and conditions',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.5','yearly','2026-07-24 11:42:48'),(78,'404.php','404 Page Not Found','404 Page Not Found | DigiRare Technologies','The requested page could not be located. Return to DigiRare homepage or explore our IT services.','404 page, error 404, page not found',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WebPage',NULL,1,1,'0.1','never','2026-07-24 11:42:48');
/*!40000 ALTER TABLE `seo_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_redirects`
--

DROP TABLE IF EXISTS `seo_redirects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_redirects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_url` varchar(255) NOT NULL,
  `new_url` varchar(255) NOT NULL,
  `redirect_type` varchar(10) NOT NULL DEFAULT '301',
  `is_enabled` tinyint(1) DEFAULT 1,
  `hit_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `old_url` (`old_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_redirects`
--

LOCK TABLES `seo_redirects` WRITE;
/*!40000 ALTER TABLE `seo_redirects` DISABLE KEYS */;
/*!40000 ALTER TABLE `seo_redirects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_schema`
--

DROP TABLE IF EXISTS `seo_schema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_schema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schema_type` varchar(50) NOT NULL,
  `schema_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `json_data` longtext NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_schema`
--

LOCK TABLES `seo_schema` WRITE;
/*!40000 ALTER TABLE `seo_schema` DISABLE KEYS */;
/*!40000 ALTER TABLE `seo_schema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_settings`
--

DROP TABLE IF EXISTS `seo_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_name` (`page_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_settings`
--

LOCK TABLES `seo_settings` WRITE;
/*!40000 ALTER TABLE `seo_settings` DISABLE KEYS */;
INSERT INTO `seo_settings` VALUES (1,'index.php','NextGen Software Innovators & Digital Soft Solutions | Teckko IT Company','We provide state-of-the-art software engineering, cybersecurity, cloud architecture, and modern IT services tailored to accelerate your business growth.','software, cloud, cybersecurity, nextgen, developers','2026-07-21 10:42:58'),(2,'about.php','About Us | Teckko IT Company','Learn about our engineering expertise, company history, and meet the development team behind our top-tier IT products and services.','it company team, software engineers, about teckko','2026-07-21 10:42:58'),(3,'services.php','Professional IT Services & Pricing | Teckko IT Company','Comprehensive technical solutions including software development, cloud migration, security audit operations, and AI backend integrations.','it services, cloud deployment, tech support, custom web dev','2026-07-21 10:42:58'),(4,'projects.php','IT Case Studies & Portfolio | Teckko IT Company','Read about our successful deployments, enterprise systems, and UI/UX design portfolios engineered for scale.','case studies, portfolio, system architecture, web design projects','2026-07-21 10:42:58'),(5,'blog.php','Tech Insights & News | Teckko IT Company','Browse tech articles, cloud computing guides, threat analysis reports, and software development methodologies.','blog, tech blog, devops articles, programming trends','2026-07-21 10:42:58'),(6,'contact.php','Contact Us | Teckko IT Company','Get in touch with our solutions design group. Request a free consultation and project scope estimation.','contact center, it consulting support, email support','2026-07-21 10:42:58');
/*!40000 ALTER TABLE `seo_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_social`
--

DROP TABLE IF EXISTS `seo_social`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_social` (
  `id` int(11) NOT NULL DEFAULT 1,
  `og_site_name` varchar(255) DEFAULT 'DigiRare Technologies',
  `og_type` varchar(50) DEFAULT 'website',
  `og_default_image` varchar(255) DEFAULT NULL,
  `twitter_site` varchar(100) DEFAULT '@digirare_tech',
  `twitter_creator` varchar(100) DEFAULT '@digirare_tech',
  `twitter_card_type` varchar(50) DEFAULT 'summary_large_image',
  `twitter_default_image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `og_locale` varchar(20) DEFAULT 'en_US',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_social`
--

LOCK TABLES `seo_social` WRITE;
/*!40000 ALTER TABLE `seo_social` DISABLE KEYS */;
INSERT INTO `seo_social` VALUES (1,'DigiRare Technologies','website','https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=630','@digirare_tech','@digirare_tech','summary_large_image',NULL,'2026-07-24 11:17:00','en_US');
/*!40000 ALTER TABLE `seo_social` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_verification`
--

DROP TABLE IF EXISTS `seo_verification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_verification` (
  `id` int(11) NOT NULL DEFAULT 1,
  `google_verification` varchar(255) DEFAULT NULL,
  `bing_verification` varchar(255) DEFAULT NULL,
  `yandex_verification` varchar(255) DEFAULT NULL,
  `pinterest_verification` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `baidu_verification` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_verification`
--

LOCK TABLES `seo_verification` WRITE;
/*!40000 ALTER TABLE `seo_verification` DISABLE KEYS */;
INSERT INTO `seo_verification` VALUES (1,'','',NULL,NULL,'2026-07-24 11:17:00',NULL);
/*!40000 ALTER TABLE `seo_verification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `icon` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `long_description` text DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'WordPress Website Development',NULL,'fa-brands fa-wordpress','Build fast, secure, SEO-friendly, and fully responsive WordPress websites with custom themes and plugin integration.','Our WordPress solutions deliver enterprise-grade performance, clean code architecture, SEO structure validation, and customized administration workflows. We handle plugin integrations, payment gateways, and custom ACF theme structures.','published','2026-07-21 10:42:58',NULL),(2,'Custom Coding & Web Applications',NULL,'fa-solid fa-laptop-code','Develop custom websites, admin panels, SaaS platforms, CRM systems, dashboards, and web applications using modern technologies.','We engineer lightweight, fully responsive custom systems from scratch. Using Node.js, React, Laravel, or PHP frameworks, we construct fast dashboards, RESTful database integrations, SaaS multi-tenant portals, and secure API gateways.','published','2026-07-21 10:42:58',NULL),(3,'E-Commerce Websites',NULL,'fa-solid fa-cart-shopping','Create professional online stores with secure payment gateways, inventory management, order tracking, and responsive shopping experiences.','Convert visitors into buyers with modern e-commerce systems. We deploy Shopify integrations, custom WooCommerce frameworks, secure Stripe/PayPal gateways, stock tracking interfaces, and automated notification scripts.','published','2026-07-21 10:42:58',NULL),(4,'Landing Pages',NULL,'fa-solid fa-bullhorn','Design high-converting landing pages optimized for lead generation, sales, marketing campaigns, and business growth.','Aesthetically stunning landing pages designed with strict conversion optimization rules, quick loading indexes, clear CTA hooks, visual content flow, and full device responsiveness to maximize advertising ROI.','published','2026-07-21 10:42:58',NULL),(5,'Graphic Designing',NULL,'fa-solid fa-palette','Create professional logos, social media posts, business cards, brochures, banners, flyers, and complete brand visuals.','High-end visual communication that elevates your digital presence. Our visual designers build custom vector designs, marketing banners, corporate decks, and high-impact social media layouts that stand out.','published','2026-07-21 10:42:58',NULL),(6,'Canva Designs',NULL,'fa-solid fa-pen-nib','Design engaging Canva templates for social media, presentations, advertisements, marketing campaigns, and business promotions.','Empower your team with customized, editable Canva design templates. We establish layout grids, image placements, typography templates, and style options for social media posts, slides, and flyers.','published','2026-07-21 10:42:58',NULL),(7,'Business Branding',NULL,'fa-solid fa-award','Build strong brand identity including logo design, color palette, typography, brand guidelines, and complete visual identity.','Establish trust with a cohesive brand footprint. We draft identity guidelines, customize professional primary/secondary logotypes, detail color systems, select premium web font palettes, and package assets.','published','2026-07-21 10:42:59',NULL),(8,'Website Maintenance & Support',NULL,'fa-solid fa-screwdriver-wrench','Provide website updates, backups, bug fixing, security monitoring, performance optimization, and technical support.','Proactive technical support, weekly offsite security backups, database defragmentation, theme code updates, plugin validation audits, and quick troubleshooting response schedules.','published','2026-07-21 10:42:59',NULL);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_settings` (
  `setting_key` varchar(55) NOT NULL,
  `setting_value` text DEFAULT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES ('about_counters','[{\"value\":\"250+\",\"label\":\"Successful Projects Delivered\"},{\"value\":\"120+\",\"label\":\"Happy Clients Worldwide\"},{\"value\":\"99%\",\"label\":\"Satisfaction Rate\"},{\"value\":\"6+\",\"label\":\"Years Experience\"}]'),('about_description','Our customized software frameworks are designed to resolve real-world operations limitations. We collaborate with you to build scalable platforms that increase productivity, eliminate overheads, and secure user data.'),('about_features','[\"Custom Software Development\",\"Cloud DevOps Integrations\",\"Enterprise Cybersecurity Operations\",\"Proactive Site Support & Maintenance\"]'),('about_image','https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=700&q=80'),('about_mission','To deliver cutting-edge digital experiences that empower clients to scale efficiently and securely.'),('about_sub_title','Innovate & Grow'),('about_title','Innovate Soft Solutions to Grow Tech Business'),('about_vision','To become the leading software engineering and branding partner globally for growing businesses.'),('activityLogs','[{\"id\":\"act-1785152346473\",\"user\":\"Jack Devlin\",\"action\":\"Deleted item msg-1 from contacts\",\"module\":\"System\",\"ipAddress\":\"127.0.0.1\",\"date\":\"2026-07-27T11:39:06.473Z\"},{\"id\":\"act-1784718627263\",\"user\":\"Jack Devlin\",\"action\":\"Created new item in blogs\",\"module\":\"System\",\"ipAddress\":\"127.0.0.1\",\"date\":\"2026-07-22T11:10:27.263Z\"},{\"id\":\"act-1784718260428\",\"user\":\"Jack Devlin\",\"action\":\"Created new item in blogs\",\"module\":\"System\",\"ipAddress\":\"127.0.0.1\",\"date\":\"2026-07-22T11:04:20.428Z\"},{\"id\":\"act-1784632062624\",\"user\":\"Jack Devlin\",\"action\":\"Created new item in blogs\",\"module\":\"System\",\"ipAddress\":\"127.0.0.1\",\"date\":\"2026-07-21T11:07:42.624Z\"}]'),('backups','[]'),('categories','[{\"id\":\"cat-1\",\"name\":\"Enterprise IT\",\"slug\":\"enterprise-it\",\"count\":12},{\"id\":\"cat-2\",\"name\":\"Security\",\"slug\":\"security\",\"count\":8},{\"id\":\"cat-3\",\"name\":\"Cloud Computing\",\"slug\":\"cloud-computing\",\"count\":15}]'),('company_name','DigiRare Technologies'),('consultation_btn_link','contact.php'),('emailSettings','{\"smtpHost\":\"smtp.gmail.com\",\"smtpPort\":\"587\",\"smtpUsername\":\"digiraremarketing@gmail.com\",\"smtpPassword\":\"Habiba7412\",\"encryption\":\"TLS\",\"senderName\":\"Digiraremarketing technologies\",\"senderEmail\":\"digiraremarketing@gmail.com\",\"autoReplyToggle\":true,\"autoReplyTemplate\":\"Hello [Name],\\n\\nThank you for reaching out to MyITCompany. We have received your inquiry regarding \\\"[Subject]\\\" and our engineering team will get back to you within 24 hours.\\n\\nBest regards,\\nMyITCompany Support\",\"emailLogs\":[{\"id\":\"log-1785408079943\",\"to\":\"digiraremarketing@gmail.com\",\"subject\":\"Re: marketing\",\"status\":\"Success\",\"date\":\"2026-07-30T10:41:19.943Z\"},{\"id\":\"log-1785236373118\",\"to\":\"digiraremarketing@gmail.com\",\"subject\":\"SMTP Handshake Verification Diagnostic Test\",\"status\":\"Success\",\"date\":\"2026-07-28T10:59:33.118Z\"},{\"id\":\"log-1785236348091\",\"to\":\"digiraremarketing@gmail.com\",\"subject\":\"SMTP Handshake Verification Diagnostic Test\",\"status\":\"Success\",\"date\":\"2026-07-28T10:59:08.091Z\"}],\"emailQueue\":[]}'),('facebook_url','https://facebook.com'),('github_url','https://github.com'),('hero_btn_text_1','Get Started Today'),('hero_btn_text_2','Explore Our Services'),('hero_btn_url_1','contact.php'),('hero_btn_url_2','services.php'),('hero_description','We empower startups, businesses, and enterprises with innovative software development, custom web applications, digital marketing, UI/UX design, branding, and cloud-based solutions. Our expert team creates secure, scalable, and high-performance digital products that help businesses grow faster in today\'s competitive market.'),('hero_headline','Transforming Ideas Into Powerful Digital Solutions for Modern Businesses'),('hero_image','https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80'),('hero_sub_heading','NextGen Software Innovators'),('linkedin_url','https://linkedin.com'),('mediaLibrary','[]'),('menus','[{\"id\":\"menu-main\",\"name\":\"Header Navigation\",\"status\":\"Active\",\"items\":[{\"id\":\"m-1\",\"name\":\"Home\",\"type\":\"internal\",\"url\":\"index.php\",\"target\":\"_self\"},{\"id\":\"m-2\",\"name\":\"About\",\"type\":\"internal\",\"url\":\"about.php\",\"target\":\"_self\"},{\"id\":\"m-3\",\"name\":\"Services\",\"type\":\"internal\",\"url\":\"services.php\",\"target\":\"_self\"},{\"id\":\"m-4\",\"name\":\"Projects\",\"type\":\"internal\",\"url\":\"projects.php\",\"target\":\"_self\"},{\"id\":\"m-5\",\"name\":\"Blog\",\"type\":\"internal\",\"url\":\"blog.php\",\"target\":\"_self\"}]}]'),('notifications','[]'),('office_address','Islamabad, Pakistan'),('phone_number','00923199564230'),('roles','[{\"role\":\"Super Admin\",\"description\":\"Full system access to all configurations and data.\",\"permissions\":{\"dashboard\":[\"view\"],\"users\":[\"view\",\"create\",\"update\",\"delete\"],\"blog\":[\"view\",\"create\",\"update\",\"delete\"],\"menu\":[\"view\",\"create\",\"update\",\"delete\"],\"seo\":[\"view\",\"create\",\"update\",\"delete\"],\"media\":[\"view\",\"create\",\"update\",\"delete\"],\"contact\":[\"view\",\"create\",\"update\",\"delete\"],\"email\":[\"view\",\"create\",\"update\",\"delete\"],\"settings\":[\"view\",\"create\",\"update\",\"delete\"]}}]'),('sales_email','digiraremarketing@gmail.com'),('seoSettings','{\"websiteTitle\":\"777MyITCompany | Innovative IT Solutions & Digital Transformation\",\"canonicalUrl\":\"http:\\/\\/localhost\\/myitcomapny\\/\",\"metaTitle\":\"88888Transform Your Business with Expert IT & Software Solutions | 777MyITCompany\",\"metaDescription\":\"Accelerate your business with custom software development, AI solutions, cloud infrastructure, cybersecurity, web development, mobile apps, and digital marketing services from 777MyITCompany.\",\"keywords\":\"Custom Software Development, AI Solutions, Cloud Computing99999, Cybersecurity Services, Web Development Company, Mobile App Development, DevOps Services, Digital Transformation, IT Consulting, SEO Services, UI UX Design, Business Automation, Enterprise Software, Technology Partner\",\"ogImage\":\"https:\\/\\/images.unsplash.com\\/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80\",\"twitterCard\":\"summary_large_image\",\"xmlSitemap\":\"http:\\/\\/localhost\\/myitcomapny\\/sitemap.xml\",\"robotsTxt\":\"User-agent: *\\nDisallow: \\/admin\\/\",\"googleAnalyticsId\":\"\",\"searchConsoleVerification\":\"\",\"bingWebmasterVerification\":\"\",\"indexActive\":true,\"followActive\":true,\"breadcrumbActive\":true,\"jsonLdActive\":true,\"schemaMarkup\":\"{}\"}'),('support_email','digiraremarketing@gmail.com'),('support_phone','00923199564230'),('tags','[{\"id\":\"tag-1\",\"name\":\"AI\",\"slug\":\"ai\"},{\"id\":\"tag-2\",\"name\":\"Cloud\",\"slug\":\"cloud\"}]'),('twitter_url','https://twitter.com'),('websiteSettings','{\"websiteName\":\"MyITCompany\",\"websiteDescription\":\"Premium IT consulting, remote infrastructure monitoring, cloud migrations, and full-stack software development.\",\"logoUrl\":\"https:\\/\\/images.unsplash.com\\/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=150&q=80\",\"faviconUrl\":\"https:\\/\\/images.unsplash.com\\/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=48&q=80\",\"primaryColor\":\"#6366f1\",\"secondaryColor\":\"#8b5cf6\",\"footerText\":\"\\u00a9 2026 MyITCompany. All rights reserved.\",\"socialLinks\":{\"linkedin\":\"https:\\/\\/linkedin.com\",\"twitter\":\"https:\\/\\/twitter\",\"github\":\"https:\\/\\/github\",\"facebook\":\"https:\\/\\/facebook\"},\"businessAddress\":\"Islamabad, Pakistan\",\"businessPhone\":\"+92 XXX XXX XXXX\",\"businessEmail\":\"hello@myitcompany.com\",\"googleMapsEmbedUrl\":\"\",\"timezone\":\"Asia\\/Karachi\",\"language\":\"en\"}');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sitemap_settings`
--

DROP TABLE IF EXISTS `sitemap_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sitemap_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `auto_generate` tinyint(1) DEFAULT 1,
  `include_pages` tinyint(1) DEFAULT 1,
  `include_blogs` tinyint(1) DEFAULT 1,
  `include_services` tinyint(1) DEFAULT 1,
  `include_projects` tinyint(1) DEFAULT 1,
  `default_priority` varchar(10) DEFAULT '0.8',
  `default_changefreq` varchar(20) DEFAULT 'weekly',
  `last_generated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sitemap_settings`
--

LOCK TABLES `sitemap_settings` WRITE;
/*!40000 ALTER TABLE `sitemap_settings` DISABLE KEYS */;
INSERT INTO `sitemap_settings` VALUES (1,1,1,1,1,1,'0.8','weekly',NULL);
/*!40000 ALTER TABLE `sitemap_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_members`
--

DROP TABLE IF EXISTS `team_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('draft','published') DEFAULT 'published',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_members`
--

LOCK TABLES `team_members` WRITE;
/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
INSERT INTO `team_members` VALUES (1,'Sarah Connor','CTO & Co-Founder','Sarah drives the technical direction of the company, focusing on scalable enterprise platforms and secure cloud architectures.','https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80','https://linkedin.com','https://twitter.com','https://github.com',1,'2026-07-21 10:42:59','published'),(2,'Jack Devlin','Lead Software Architect','Jack is an expert in distributed networks and cloud application scalability, leading the development of custom SaaS components.','https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&q=80','https://linkedin.com','https://twitter.com','https://github.com',2,'2026-07-21 10:42:59','published'),(3,'Connor McLeod','Lead Cybersecurity Auditor','Connor ensures all deployments pass zero-trust network checks and database protection validation routines.','https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=300&q=80','https://linkedin.com','https://twitter.com','https://github.com',3,'2026-07-21 10:42:59','published');
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(100) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `rating` tinyint(4) DEFAULT 5,
  `review` text NOT NULL,
  `status` enum('draft','published') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,'Alex Rivers','CEO, Innovate Corp','https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',5,'DigiRare Technologies delivered our payments portal ahead of schedule. Their attention to security protocols and custom dashboards was phenomenal!','published','2026-07-21 10:42:59'),(2,'Brenda Chen','CTO, CloudScale Inc.','https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=150&q=80',5,'Their database clustering and cybersecurity migration saved our system from countless latencies. They are our go-to partners for cloud operations!','published','2026-07-21 10:42:59'),(3,'David Miller','Founder, Transit Logistics','https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80',5,'The custom CRM and order tracking system they designed for our logistics team boosted our delivery rates by 35%. Excellent UI/UX execution!','published','2026-07-21 10:42:59');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$/F4dGxzLhsyFlUbC7Z9WAeXxIcE/w9i9RnK8ApFuda.Dm1.HrrYMu','admin@teckko-it.com','Teckko Administrator','2026-07-21 10:42:58');
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

-- Dump completed on 2026-08-12 10:11:23
