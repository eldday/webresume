/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.5.26-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: resume
-- ------------------------------------------------------
-- Server version	10.5.26-MariaDB-0+deb11u2

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
-- Table structure for table `Job_history`
--

DROP TABLE IF EXISTS `Job_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Job_history` (
  `job_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_title` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `company_id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `job_description` varchar(4000) NOT NULL,
  UNIQUE KEY `job_id` (`job_id`) USING BTREE,
  FULLTEXT KEY `job_description` (`job_description`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Job_history`
--

LOCK TABLES `Job_history` WRITE;
/*!40000 ALTER TABLE `Job_history` DISABLE KEYS */;
INSERT INTO `Job_history` VALUES (1,'QA engineer','1997-01-01','1998-12-31',1,'Cirrus Logic','<ul><li>Qualified graphics hardware and drivers via verification, validation, black box, and regression testing methods</li><li>Tested Video Graphics PC Cards and drivers&nbsp;<ul><li>Cirrus development 3D hardware card</li><li>Cirrus TV-Tuner+ 2D Display cards</li></ul></li><li>Verified hardware and software in OS2 and Windows operating systems</li><li>Created test plans and matrices for various PC applications, utilities and operating systems.&nbsp;</li><li>Gained experience with PC desktop and mobile systems.&nbsp;</li><li>Helped to resolve problems by working closely with development engineers.</li></ul>'),(2,'Sr QA Engineer / Project Lead','1998-02-01','1999-01-01',3,'3dfx','<ul><li>Promoted to full time QA as a SR QA Engineer.</li><li>Developed and administered SQA Intranet site and documentation server.</li><li>Developed test plans and test matrices for various aspects of 3D graphics testing.</li><li>Qualified graphics hardware, drivers via verification, validation, black box, and regression type testing&nbsp;</li><li>Project Lead and responsible for the shipping quality of the Voodoo 5 5500 initial retail release.</li><li>Assisted with the development and implementation of Product Release processes and procedures.</li><li>Worked closely with development and manufacturing sites during product development cycles.</li><li>Traveled to multiple offsite/shore locations providing training and mentoring of all QA staff.</li><li>Attended numerous trade shows acting as a technical representative for all 3dfx products.</li></ul>'),(4,'Beta Program Manager','2001-09-01','2003-08-01',6,'3Dfx','<ul><li>Inherited ReplayTV Beta Program was responsible for revising the program to accommodate all SONICblue products.</li><li>Designed and developed the Beta Program public website.</li><li>Maintained all servers, accounts, and databases related to the Beta Program website.</li><li>Increased the overall effectiveness and efficiency of the Beta Program</li><li>Created documentation detailing the operational aspects of the Beta Program</li><li>Trained and managed Beta Coordinators tasked with running product specific beta test cycles.</li></ul>'),(5,'SQA Lead ','2024-06-01','2026-01-01',4,'','<ul><li>Lead QA for Financial Loan Management application and related micro services</li><li>Supported Business Analysts by developing the UI mock screens related to new functionality&nbsp;</li><li>General Support for new incoming team as the SME for the Application&nbsp;</li><li>Created new workflows in JIRA to streamline how the team plans and tracks their work&nbsp;</li><li>I acted as Interim Scrum Master to get the project kicked off with the new team</li><li>Generated Requirements documentation for various use cases during active development.</li><li>Created custom dashboards showing metrics for the entire project for the client stakeholders</li><li>Continuing government contract QA Lead</li><li>Continued 3-year contract with company that won contract</li><li>Helped Business Analysts with new functional User Interface screen design&nbsp;</li></ul>'),(6,'QA Manager','2010-02-01','2012-01-01',8,NULL,'<ul><li>Managed QA Automation team and Black Box Testing Resources&nbsp;</li><li>Managed a very large, distributed QA team of over 30 people in multiple locations worldwide.</li><li>Extensive travel to interface with and train offshore teams&nbsp;</li><li>Mentored and assisted in the career development of all direct reports.&nbsp;</li><li>I interviewed and trained new hires into the QA Organization.&nbsp;</li><li>Performed all duties and responsibilities commensurate with a management level position.</li><li>other stuff</li></ul>'),(7,'QA Manager','2010-02-01','2012-01-01',3,NULL,'<ul><li>Promoted to QA Manager of the Software Quality Assurance team.</li><li>Mentored and assisted in the career development of all direct reports.</li><li>Performed all duties and responsibilities commensurate with a management level position.</li><li>Participating member of the Product Releases core management team.</li><li>Backup release engineer</li></ul>'),(8,'Lab Manager','1997-11-01','1998-02-01',3,NULL,'<ul><li>Contracted to set up and configure the SQA test lab.&nbsp;</li><li>Administrated the SQA Domain, file-servers, and Networking access to the QA Lab.&nbsp;</li><li>Set up, configured, maintained, and inventoried all QA test machines and equipment.&nbsp;</li><li>Doubled as a QA Engineer when time permitted.</li></ul>'),(9,'Senior QA Engineer / Trainer','2023-11-01','2024-06-01',5,NULL,'<ul><li>3-year government contract Senior QA Engineer / Trainer&nbsp;</li><li>Create test documentation for Loan servicing web application.&nbsp;</li><li>Developed Performance Automation tests using JMeter&nbsp;</li><li>Create UI Automation via Selenium&nbsp;</li><li>JMeter / Load Runner Performance/Load testing&nbsp;</li><li>JIRA, Microsoft Teams, and Office suite of products&nbsp;</li><li>WinSCP, VDI/Virtual Machines,&nbsp;</li><li>Java, Apache Maven, Angular JavaScript front-end using Spring framework running out of IBM Liberty Server&nbsp;</li><li>Developed an Access front-end and back-end database to house all tests and results.</li></ul>'),(10,'QA Manager','2001-01-01','2001-09-01',10,NULL,'<ul><li>Created QA organization from the ground up for the Consumer Electronics division of Frontpath.</li><li>Developed all testing documentation and helped define the software release processes.</li><li>Setup, Configured and Maintained SQA Fileservers and SQA Intranet site.</li><li>Set up and configured multiple server rooms and testing labs.</li><li>Researched, set up, and maintained a web-based Defect Tracking database.</li><li>Participating as a member of the Product Development core team.</li><li>Interview and train new hires to the QA Organization.</li><li>Traveled to various trade shows to manage the setup teams and serve as a technical resource.</li></ul>'),(11,'Principal QA Engineer','2012-01-01','2012-10-01',8,NULL,'<p>&nbsp;</p><ul><li>Technical role focusing on all aspects of quality for the Consumer Electronics division of R&amp;D.</li><li>Focused on emerging technologies and special projects as directed.</li></ul>'),(12,'Senior QA Engineer/Lead                                                                                              ','2009-03-01','2010-02-01',8,NULL,'<ul><li>Directed the overall Integration test efforts for TotalGuide CE client.</li><li>Administered and customized corporate instance of JIRA</li><li>Extensive travel to interface with and train offshore teams</li><li>Defined test plans test cases and general test approach.</li><li>Coordinated with contemporary development and QA teams responsible for delivering ecosystem components.</li></ul>'),(13,'Senior QA Engineer/Lead','2005-11-01','2009-03-01',7,NULL,'<p>&nbsp;</p><ul><li>Tested Media Server class software.&nbsp;</li><li>Defined overall QA test strategy, attended DLNA interoperability events</li></ul>'),(14,'Senior QA Engineer / Team Lead','2013-01-01','2017-02-01',11,NULL,'<ul><li>1st test resource hired into QA team.</li><li>Implemented and Administered defect tracking and wiki software (Atlassian)</li><li>Implemented and Administered test case management system (Testrail), set up QA lab and domain.</li><li>Create test documentation in the form of test plans, test suites, test matrices, and test cases.</li><li>Validate all aspects of streaming media technologies from component, feature, integration, regression, and end to end test scenarios.</li><li>Configured integration between various company tools Zendesk, Testrail, Jira)&nbsp;</li></ul>'),(15,'Senior Staff QA Engineer/Multi-Team Lead/Manager','2017-02-01','2021-03-01',11,NULL,'<ul><li>Performed onboarding and mentoring of new QA team hires as needed.</li><li>Primary test resource and QA technical lead for ClearCaster Product line</li><li>Primary test resource and QA technical lead for Wowza Streaming Engine product.</li><li>Managed two direct reports (QA Engineers)&nbsp;</li></ul>'),(16,'Senior Manager QA (Head of QA)','2021-03-01','2023-03-01',11,NULL,'<ul><li>Promoted to head of QA in line with re-establishing QA as an org within engineering.&nbsp;</li><li>10 QA Direct Reports and 12 third party developer/QA engineers who followed QA methodology when performing as a test resource.&nbsp;</li><li>Directed and coordinated test efforts across a globally distributed QA team.&nbsp;</li><li>Normalized disparate QA efforts across entire engineering organization.</li><li>Worked with external/third-party test and development teams to align process and delivery.</li><li>Responsible for overall product line test strategy and QA related KPI’s.&nbsp;</li><li>Coordinated and worked closely with peer engineering leaders.&nbsp;</li><li>Help define and implement company-wide career development program.</li><li>Mentored/Coached, and Supported team members facilitated improving their capabilities, and advancing their careers.</li><li>Perform SRE /Tier 3 duties when needed to debug, isolate, and resolve issues found or reported in production environments.&nbsp;</li><li>Developed tooling for Support organization to help them solve customer issues earlier and without the need for engineering escalation.&nbsp;</li><li>Worked with manufacturers to help resolve issues with ClearCaster hardware encoder.&nbsp;</li><li>Cloud SAAS product was a ruby on rails app backed by a MySQL database and served the public API and User interface.&nbsp;</li><li>Maintained, fixed, and delivered newer versions of encoder software affecting large customers.</li></ul>'),(17,'Senior QA Engineer','2004-03-01','2005-09-01',9,NULL,'<ul><li>Became familiar with Network Appliance hardware and software.</li><li>Served as SQA engineer within the Windows SAN QA team testing SnapDrive MMC interface.</li><li>Experienced with SAN technologies (iSCSI, FCP, MPIO, hardware &amp; software initiators).</li></ul>'),(18,'Senior Lab Manager','1997-01-01','1997-10-01',2,NULL,'<ul><li>Ran all day to day operations of the Resource Lab.&nbsp;</li><li>Configured and setup all computer systems for employees.&nbsp;</li><li>Single escalation point for all IT Desktop Support Personnel</li></ul>');
/*!40000 ALTER TABLE `Job_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `access_levels`
--

DROP TABLE IF EXISTS `access_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `access_levels` (
  `access_id` int(11) NOT NULL AUTO_INCREMENT,
  `access_level` varchar(20) NOT NULL,
  `access_desc` varchar(100) NOT NULL,
  PRIMARY KEY (`access_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `access_levels`
--

LOCK TABLES `access_levels` WRITE;
/*!40000 ALTER TABLE `access_levels` DISABLE KEYS */;
INSERT INTO `access_levels` VALUES (1,'View','This level does not allow any updating of records'),(2,'admin','this is the only level that allows updating of records');
/*!40000 ALTER TABLE `access_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `category_id` int(255) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (10,'Certifications'),(11,'collaboration'),(2,'Leadership'),(5,'Platform/Operating Systems'),(9,'Project/Defect/Test Management'),(7,'Quality / Strategy'),(1,'Software Development'),(4,'Team Building'),(8,'Technologies'),(3,'Tooling'),(12,'Video Technologies');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` text DEFAULT NULL,
  `Description` text NOT NULL,
  `logo` varchar(255) NOT NULL,
  PRIMARY KEY (`company_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'Cirrus Logic','This company created video cards','cirrus.png'),(2,'Logistix','Fulfillment and duplication facility that provided large volume creation and packaging for distribution to resellers',''),(3,'3Dfx','3D Video Hardware Company released the first 3D hardware accelerated graphics to PC. ','3dfx.png'),(4,'HarmonyTech','contractor','HT.png'),(5,'ILS','another contractor','ILS.png'),(6,'SONICblue','Parent company of multiple brands such as Rio Mp3 players, GO-VIDEO, Diamond Multimedia, S3 Graphics, and ReplayTV','sonic-blue.png'),(7,'Mediabolic','UpnP Video server technology for storing and presenting VOD content','mediabolic.png'),(8,'Rovi','Macrovision became Rovi and is now Tivo ','rovi.png'),(9,'Network Appliance','NetApp makes filers now referred to as fabrics and are backbone hardware for interconnected server farms ','NetApp.png'),(10,'frontpath','Subsidiary of SONICblue created the first web tablets with wireless networking capability ','frontpath.png'),(11,'Wowza','Streaming Video Technology company focusing on serf hosted server software, cloud based streaming and CDN, and at one point dedicated encoding hardware','wowza.png');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skills` (
  `Skill_id` int(11) NOT NULL AUTO_INCREMENT,
  `Skill_name` varchar(255) NOT NULL,
  `Skill_category_id` int(11) NOT NULL,
  PRIMARY KEY (`Skill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skills`
--

LOCK TABLES `skills` WRITE;
/*!40000 ALTER TABLE `skills` DISABLE KEYS */;
INSERT INTO `skills` VALUES (1,'Agile/Scrum',1),(2,'SAFe',1),(3,'Waterfall',1),(4,'Capability Maturity Model',1),(5,'SVN',3),(6,'CircleCI',3),(7,'Git/Github',3),(8,'Linux',5),(9,'Windows / Windows Server',5),(10,'MacOS, iOS',5),(11,'Docker Containers ',5),(12,'Virtual Machines',5),(13,'Testrail Administration / Customization',9),(14,'Atlassian Suite \r\n * JIRA\r\n * Confluence\r\n * BitBucket\r\n * StatusPage',9),(15,'Leading globally distributed teams',2),(16,'Manual -> Automation ',4),(17,'Knowledge-sharing skill-leveling',4),(18,'Community of Practice',4),(19,'Cross-functional collaboration',11),(20,'Broadcast, Live Streaming,VOD, OTT, WebRTC, SRT',12),(21,'Video Streaming Protocols (HLS, DASH, etc)',12),(22,'Video Transcoding (Software and hardware)',12),(23,'DRM: PlayReady, WideVine, Fairplay',12),(24,'Automation Test frameworks',3);
/*!40000 ALTER TABLE `skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user-accounts`
--

DROP TABLE IF EXISTS `user-accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user-accounts` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `login_id` varchar(20) NOT NULL,
  `login_pword` varchar(200) NOT NULL,
  `access_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user-accounts`
--

LOCK TABLES `user-accounts` WRITE;
/*!40000 ALTER TABLE `user-accounts` DISABLE KEYS */;
INSERT INTO `user-accounts` VALUES (1,'admin','$2y$10$qZPiIL2f/p2Dxtq6FqBxYuhsfkX4aVXZ2Ab5lpX5xOlErodn/4OCW',2),(2,'reader','$2y$10$w4kEcPVeu8hI8W6f3t/puOEWYFcHAEchnU3LdxWsJQjIWa6lWJ1ge',1),(3,'viewer','$2y$10$2rLCtb9YEGYr5kJBRU0QbOr4CozhC4fWDYMfxo/6ILwg4ELeYt4Za',1);
/*!40000 ALTER TABLE `user-accounts` ENABLE KEYS */;
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
  `access_level` enum('admin','view') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$XUBeEDMeiiwdb/37QIQueOIQK7hF7v0R9kx12OkmQcBnzg2ECaKTK','admin','2025-01-05 01:07:24'),(2,'viewer','$2y$10$50Euvrhbj9IzCyMxb03ul.cPY8rDX5/kAQnGi4VIq35Lo1OZNoA9O','view','2025-01-05 01:33:51'),(6,'pday','$2y$10$roEjoE2mTxmvQ5ikwutV4euhmwllUCtWYSfmDmKYSCS09OC3nCP4u','admin','2025-01-05 01:48:02'),(11,'pday2','$2y$10$tVIOoC8i9odAJne3avvk6.boQ3Zrp1OPmXeG1Y1jLF6wT0yV2k17a','admin','2025-01-05 01:55:14'),(12,'administrator','$2y$10$g49ID4Fh5WKUUliNUue7E.wOl5BbqaMiMiEYTdu13FqOkd4fKaqYC','admin','2025-01-05 06:20:43');
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

-- Dump completed on 2025-01-05  0:50:51
