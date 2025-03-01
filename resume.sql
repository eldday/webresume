-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 12, 2025 at 06:35 AM
-- Server version: 10.11.6-MariaDB-0+deb12u1
-- PHP Version: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resume`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_levels`
--

CREATE TABLE `access_levels` (
  `access_id` int(11) NOT NULL,
  `access_level` varchar(20) NOT NULL,
  `access_desc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_levels`
--

INSERT INTO `access_levels` (`access_id`, `access_level`, `access_desc`) VALUES
(1, 'View', 'This level does not allow any updating of records'),
(2, 'admin', 'this is the only level that allows updating of records'),
(3, 'Guest', 'Another view only access level');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(255) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(10, 'Certifications'),
(11, 'collaboration'),
(2, 'Leadership'),
(5, 'Platform/Operating Systems'),
(9, 'Project/Defect/Test Management'),
(7, 'Quality / Strategy'),
(1, 'Software Development'),
(4, 'Team Building'),
(3, 'Tooling'),
(12, 'Video Technologies');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL,
  `company_name` text DEFAULT NULL,
  `Description` text NOT NULL,
  `logo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `company_name`, `Description`, `logo`) VALUES
(1, 'Cirrus Logic', '<p>Cirrus Logic, founded in 1984, is a leading fabless semiconductor company specializing in low-power, high-precision mixed-signal processing solutions for mobile and consumer applications.</p><p><a href=\"https://www.cirrus.com/company/media-center/corporate-backgrounder/?utm_source=chatgpt.com\">Cirrus Logic</a></p><p><strong>Timeline:</strong></p><p><strong>1981</strong>: Dr. Suhas Patil establishes Patil Systems, Inc. in Salt Lake City, Utah, focusing on integrated circuit solutions for the burgeoning PC components market.</p><p><strong>1984</strong>: The company is renamed Cirrus Logic, and co-founder Mike Hackworth relocates the headquarters to Silicon Valley.</p><p><strong>1989</strong>: Cirrus Logic becomes a publicly traded company, listing on the NASDAQ under the ticker symbol CRUS.</p><p><strong>1991</strong>: The company acquires Crystal Semiconductor, enhancing its capabilities in analog and mixed-signal converter integrated circuits.</p><p><strong>1998</strong>: Cirrus Logic exits the PC graphics card business to concentrate on mixed-signal technologies.</p><p><strong>2000</strong>: The company moves its headquarters to Austin, Texas, positioning itself in a growing tech hub.</p><p><strong>2007</strong>: Jason Rhode is appointed President and CEO, succeeding David D. French.</p><p><strong>2004</strong>: Cirrus Logic acquires UK-based Wolfson Microelectronics, expanding its audio IC portfolio.</p><p><strong>2021</strong>: John Forsyth becomes CEO, succeeding Jason Rhode.</p><p>Throughout its history, Cirrus Logic has evolved from a PC components startup to a global leader in mixed-signal processing solutions, maintaining a strong commitment to innovation in the semiconductor industry.</p>', 'cirrus.png'),
(2, 'Logistix', '<p>Fulfillment and duplication facility that provided large volume creation and packaging for distribution to resellers</p>', ''),
(3, '3Dfx', '<p>&nbsp;</p><p><strong>3dfx Interactive, Inc.</strong> was a pioneering company in the field of graphics acceleration for personal computers during the late 1990s. Based in San Jose, California, it was established in 1994 and quickly became a key player in the gaming industry due to its revolutionary <strong>Voodoo Graphics</strong> line of graphics cards. These cards introduced groundbreaking performance and features that greatly enhanced 3D rendering, making them highly popular among gamers.</p><p>3dfx\'s proprietary technologies, including its Glide API, offered unmatched performance in games optimized for its hardware. However, the company faced mounting challenges as competitors like NVIDIA and ATI (later acquired by AMD) began producing superior, more flexible solutions that supported industry-standard APIs like Direct3D and OpenGL.</p><p>In a bid to remain competitive, 3dfx shifted its business model from selling chips to OEMs (original equipment manufacturers) to producing and marketing complete graphics cards. This move alienated its OEM partners and resulted in reduced market share. By the early 2000s, financial difficulties and strategic missteps led to its decline. In 2000, NVIDIA acquired 3dfx\'s intellectual property, signaling the company\'s end.</p><h3>Timeline of 3dfx Interactive</h3><h4><strong>1994</strong>:</h4><ul><li><strong>Founding</strong>: 3dfx Interactive was founded by Ross Smith, Gary Tarolli, and Scott Sellers. The company focused on developing high-performance 3D graphics solutions.</li></ul><h4><strong>1996</strong>:</h4><ul><li><strong>Voodoo Graphics Launch</strong>: The first <strong>Voodoo Graphics (Voodoo1)</strong> chipset was released, revolutionizing gaming by providing hardware acceleration for 3D graphics. It quickly became a market leader in gaming performance.</li></ul><h4><strong>1997–1998</strong>:</h4><ul><li><strong>Peak Popularity</strong>:<ul><li>The <strong>Voodoo2</strong> was launched, offering significant performance improvements and support for multiple cards in SLI (Scan-Line Interleave) mode, a precursor to modern multi-GPU technologies.</li><li>The company\'s Glide API became widely adopted in gaming, ensuring strong software support for its hardware.</li><li>3dfx cards became the standard for high-performance gaming.</li></ul></li></ul><h4><strong>1998</strong>:</h4><ul><li><strong>Rise of Competitors</strong>: NVIDIA introduced the <strong>RIVA TNT</strong>, a competing graphics card that supported Direct3D and OpenGL, industry-standard APIs. The competition began to intensify.</li><li>3dfx acquired <strong>STB Systems</strong>, a graphics card manufacturer, to produce its own cards, transitioning away from its previous business model of selling chipsets to OEMs.</li></ul><h4><strong>1999</strong>:</h4><ul><li><strong>Voodoo3 Launch</strong>: The Voodoo3 was released but faced criticism for lacking features like 32-bit color and support for newer APIs, which competitors like NVIDIA\'s <strong>GeForce 256</strong> provided.</li><li>Financial losses mounted as the company struggled to keep up with technological advancements and competition.</li></ul><h4><strong>2000</strong>:</h4><ul><li><strong>Decline and Acquisition</strong>:<ul><li>The <strong>Voodoo5</strong> series was released, featuring multi-chip designs for better performance but suffered from delays and high production costs.</li><li>The acquisition of <strong>Gigapixel</strong>, a company with innovative graphics technology, failed to turn the tide.</li><li>In December 2000, NVIDIA acquired 3dfx\'s core assets, including its intellectual property and patents, for $70 million. The company ceased operations.</li></ul></li></ul><h4><strong>2002</strong>:</h4><ul><li><strong>Final Dissolution</strong>: 3dfx formally completed the liquidation process, marking the end of the company.</li></ul>', '3dfx.png'),
(4, 'HarmonyTech', '<p><a href=\"https://harmonytech.com/?utm_source=chatgpt.com\">HarmonyTech</a> is an innovative IT company specializing in digital transformation services, delivering cutting-edge information technology solutions to both federal government and commercial clients.</p><p><strong>Timeline:</strong></p><p><strong>2011</strong>: HarmonyTech is founded by Jason P. Russell, who brings over 22 years of experience in building and maintaining enterprise architectures for businesses and government agencies.</p><p><strong>2011-2015</strong>: The company establishes its presence in the IT industry, focusing on delivering innovative services and solutions within a robust enterprise architecture framework.</p><p><strong>2016-2020</strong>: HarmonyTech expands its service offerings, emphasizing digital transformation and IT excellence, and continues to build a reputation for delivering great value to its customers.</p><p><strong>2021-Present</strong>: The company continues to innovate in the IT sector, maintaining a focus on delivering cutting-edge solutions and services to a diverse clientele.</p><p>HarmonyTech\'s commitment to IT excellence and its focus on digital transformation have positioned it as a trusted partner for organizations seeking innovative solutions in an ever-evolving technological landscape.</p>', 'HT.png'),
(5, 'ILS', '<p>International Logistics Systems, Inc. (ILSI) is a Veteran-Owned Small Business (VOSB) headquartered in York, Pennsylvania, established in March 1989.</p><p><strong>Timeline:</strong></p><p><strong>1989</strong>: ILSI is founded, focusing on providing comprehensive logistics and information technology solutions to both government and commercial clients.</p><p><strong>1990s-2000s</strong>: The company expands its services, offering full project life cycle support ranging from IT consulting to systems integration, catering to a diverse clientele.</p><p><strong>2010s</strong>: ILSI continues to grow, maintaining a successful track record with various government clients, including the Export-Import (EXIM) Bank of the United States and the National Railroad Passenger Corporation (Amtrak).</p><p><strong>Present</strong>: ILSI remains an independent company with an estimated annual revenue between $25 million and $100 million, continuing to provide high-quality professional services in logistics and IT solutions.</p><p>ILSI\'s commitment to integrity and ethics has been a cornerstone of its operations, ensuring that customers receive nothing less than the highest quality professional service.</p>', 'ILS.png'),
(6, 'SONICblue', '<p>SONICblue Incorporated was an electronics company that emerged from the evolution of S3 Incorporated, a firm originally focused on PC graphics chips.</p><p><strong>Timeline:</strong></p><p><strong>1989</strong>: S3 Incorporated was founded as a PC chipset firm, primarily focusing on PC graphics chips.</p><p><strong>1999</strong>: S3 Incorporated merged with Diamond Multimedia, a company known for its graphics cards and multimedia products.</p><p><strong>2000</strong>: S3 Incorporated rebranded itself as SONICblue, marking a strategic shift from its traditional PC graphics chip business to focus on digital media and consumer electronics.</p><p><strong>2001</strong>: SONICblue\'s business was organized into three principal units: Rio (digital audio players), frontpath (Internet appliances), and ReplayTV (digital video recorders).</p><p><strong>2003</strong>: SONICblue filed for Chapter 11 bankruptcy protection, citing financial difficulties and legal challenges, including lawsuits related to its ReplayTV product.</p><p><strong>2003</strong>: Following the bankruptcy filing, SONICblue\'s assets were sold off. D&amp;M Holdings, a Japanese electronics company, acquired the ReplayTV and Rio product lines.</p><p>SONICblue\'s trajectory reflects the challenges of transitioning from a hardware-focused company to a consumer electronics brand in a rapidly evolving market.</p>', 'sonic-blue.png'),
(7, 'Mediabolic', '<p>Mediabolic, founded in 1999 and headquartered in San Mateo, California, was a provider of software solutions for connected consumer electronics devices, including televisions and set-top boxes.</p><p><strong>Timeline:</strong></p><p><strong>1999</strong>: Mediabolic is established, focusing on developing software that enables consumer electronics devices to discover, store, and play back Internet-based content.</p><p><strong>2007</strong>: On January 1, Macrovision Corporation acquires Mediabolic for $43.5 million in cash, aiming to enhance its portfolio with technology that facilitates the transition from physical media to digital content distribution.</p><p>Mediabolic\'s software played a significant role in enabling consumer electronics manufacturers to create devices capable of seamlessly integrating and managing digital media content, contributing to the evolution of connected home entertainment systems.</p>', 'mediabolic.png'),
(8, 'Rovi', '<p>Rovi Corporation, originally founded as Macrovision in 1983, was a technology company specializing in digital entertainment solutions.</p><p><strong>Timeline:</strong></p><p><strong>1983</strong>: Macrovision is established, focusing on copy protection technologies for analog video formats.</p><p><strong>2009</strong>: On July 16, Macrovision Solutions Corporation officially changes its name to Rovi Corporation, signaling a strategic shift towards digital entertainment services.</p><p><strong>2010</strong>: Rovi introduces TotalGuide, an interactive media guide that incorporates entertainment data for search, browsing, and recommendations.</p><p><strong>2010</strong>: On December 23, Rovi announces its intention to acquire Sonic Solutions, a digital video processing and distribution company, in a deal valued at $720 million.</p><p><strong>2011</strong>: Rovi acquires online video guide SideReel, expanding its digital content discovery capabilities.</p><p><strong>2013</strong>: In April, Facebook begins licensing Rovi metadata to enhance its entertainment content offerings.</p><p><strong>2016</strong>: On April 29, Rovi announces its agreement to acquire TiVo for $1.1 billion, aiming to create a $3 billion entertainment technology leader.</p><p><strong>2016</strong>: Following the acquisition, Rovi adopts the TiVo brand name, leveraging TiVo\'s strong consumer recognition in the digital entertainment market.</p><p>Throughout its history, Rovi played a significant role in the evolution of digital entertainment technology, transitioning from analog copy protection to advanced digital content discovery and distribution solutions.</p>', 'rovi.png'),
(9, 'Network Appliance', '<p>NetApp, formerly known as Network Appliance, is a leading American multinational company specializing in data storage and data management solutions. Founded in 1992 by David Hitz, James Lau, and Michael Malcolm, the company has played a significant role in the evolution of network-attached storage (NAS) systems.</p><p><strong>Timeline:</strong></p><p><strong>1992</strong>: Network Appliance, Inc. is founded by David Hitz, James Lau, and Michael Malcolm, focusing on simplifying data access and storage over networks.</p><p><strong>1994</strong>: The company receives venture capital funding from Sequoia Capital, enabling further development and expansion.</p><p><strong>1995</strong>: Network Appliance goes public with an initial public offering (IPO), marking its entry into the public market.</p><p><strong>Mid-1990s to 2001</strong>: The company experiences significant growth during the internet boom, reaching $1 billion in annual revenue.</p><p><strong>2006</strong>: Network Appliance officially shortens its name to NetApp, reflecting a broader focus beyond traditional network appliances.</p><p><strong>2016</strong>: NetApp introduces ONTAP software, centralizing data management across flash, disk, and cloud&nbsp;</p><p>&nbsp;</p><p><strong>2018</strong>: The company launches its first end-to-end NVMe array, delivering over 1.3 million IOPS at 500 microseconds per high-availability pair, emphasizing speed and performance.</p><p>&nbsp;</p><p><strong>2019</strong>: NetApp is recognized as #1 in primary storage in the Gartner Magic Quadrant, highlighting its industry leadership.</p><p>Throughout its history, NetApp has been at the forefront of data storage innovation, adapting to technological advancements and shifting market demands. The company\'s commitment to providing unified data storage, integrated data services, and CloudOps solutions has solidified its position as a trusted partner for organizations worldwide.</p>', 'NetApp.png'),
(10, 'frontpath', '<p>a wholly owned subsidiary of SONICblue Incorporated, was established to explore opportunities in the information appliance sector, targeting both home users and vertical markets such as medical, education, travel, and entertainment.</p><p><strong>Timeline:</strong></p><p><strong>2000</strong>: SONICblue Incorporated, formerly known as S3 Incorporated, rebranded itself to reflect a broader focus beyond graphics chips, venturing into consumer electronics and information appliances.</p><p><strong>2001</strong>: Frontpath introduced the ProGear™, a wireless web device designed for broadband-enabled markets. The ProGear featured a 10.4\" TFT display, touch screen, and ran on the Linux 2.4x operating system. It was tailored for vertical markets, including hospitality, education, and healthcare, where it could be customized to meet specific needs.</p><p><strong>2001</strong>: Agfa Monotype Corporation announced that Frontpath licensed its New Media Core Font set for the ProGear, enhancing on-screen text clarity and legibility.</p><p><strong>2001</strong>: SONICblue\'s CEO, Ken Potashner, indicated plans to expand into video products, positioning the company as a holding entity for its main business units, including Frontpath.</p><p>Despite these developments, Frontpath and its ProGear device faced challenges in gaining significant market traction. The rapid evolution of technology and competitive pressures in the information appliance sector contributed to the company\'s limited impact.</p><p>By the early 2000s, SONICblue shifted its focus towards other consumer electronics, and Frontpath\'s operations were eventually phased out.</p><p>Frontpath\'s endeavor into the information appliance market with the ProGear™ represents an early attempt to bridge the gap between traditional computing and portable, user-friendly devices tailored for specific industries.</p>', 'frontpath.png'),
(11, 'Wowza', '<p>Wowza Media Systems, founded in 2005 by David Stubenvoll and Charlie Good, is a leading provider of streaming software and services that enable organizations to deliver high-quality live and on-demand video to audiences globally.</p><p><strong>Timeline:</strong></p><p><strong>2005</strong>: David Stubenvoll and Charlie Good establish Wowza Media Systems with the goal of simplifying media streaming for various industries.</p><p><strong>2007</strong>: The company releases Wowza Media Server Pro 1.0, offering an alternative to existing streaming solutions and supporting streamed video, audio, and interactive applications for Flash Player clients.</p><p><strong>2008</strong>: Version 1.5.x is released, adding support for H.264 video and AAC audio, as well as ingest support for protocols like RTSP, RTP, and MPEG-TS, broadening the server\'s compatibility with various streaming sources.</p><p><strong>2009</strong>: Wowza Media Server 2.0.x is launched, introducing outbound streaming support for Apple HTTP Live Streaming (HLS) for iOS devices, Microsoft Smooth Streaming for Silverlight, and RTSP/RTP for QuickTime Player and mobile devices, expanding the range of supported playback platforms.</p><p><strong>2011</strong>: Version 3.0.x is released, adding features such as network DVR, live transcoding, and DRM plug-in functionality, enhancing the server\'s capabilities for live streaming and content protection.</p><p><strong>2014</strong>: The product is rebranded as Wowza Streaming Engine with the release of version 4.0, featuring a new web-based graphical interface and full support for MPEG-DASH, improving user experience and expanding protocol support.</p><p><strong>2017</strong>: Version 4.7.3 introduces support for Secure Reliable Transport (SRT), enhancing the reliability and security of streaming over unpredictable networks.</p><p><strong>2018</strong>: Wowza Streaming Engine 4.7.7 adds support for WebRTC, enabling low-latency streaming capabilities for real-time communication applications.</p><p><strong>2019</strong>: Version 4.7.8 introduces support for Low-Latency HLS and Common Media Application Format (CMAF), further reducing streaming latency and improving compatibility across platforms.</p><p><strong>2020</strong>: Version 4.8 is released, adding full support for WebRTC and SRT streaming, as well as the CMAF packetizer for MPEG-DASH, HLS, and Low-Latency HLS streaming, enhancing the server\'s versatility and performance.</p><p>Over the years, Wowza has powered over 35,000 video implementations globally across various industries, including broadcasting, sports, surveillance, and religious organizations, establishing itself as a trusted partner for reliable, scalable video solutions.</p><p>The company\'s commitment to innovation and adaptability has solidified its position as a leader in the streaming industry, continually evolving to meet the changing needs of its diverse clientele.</p>', 'wowza.png');

-- --------------------------------------------------------

--
-- Table structure for table `Job_history`
--

CREATE TABLE `Job_history` (
  `job_id` int(11) NOT NULL,
  `job_title` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `company_id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `job_description` varchar(4000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Job_history`
--

INSERT INTO `Job_history` (`job_id`, `job_title`, `start_date`, `end_date`, `company_id`, `company_name`, `job_description`) VALUES
(1, 'QA engineer', '1996-03-01', '1997-02-01', 1, 'Cirrus Logic', '<ul><li>Qualified graphics hardware and drivers via verification, validation, black box, and regression testing methods</li><li>Tested Video Graphics PC Cards and drivers&nbsp;<ul><li>Cirrus early developmental 3D hardware card</li><li>Cirrus TV-Tuner+ 2D Display cards</li></ul></li><li>Verified hardware and software in OS2 and Windows operating systems</li><li>Created test plans and matrices for various PC applications, utilities and operating systems.&nbsp;</li><li>Gained experience with PC desktop and mobile systems.&nbsp;</li><li>Helped to resolve problems by working closely with development engineers.</li></ul>'),
(2, 'Sr QA Engineer / Project Lead', '1998-02-01', '1999-01-01', 3, '3dfx', '<ul><li>Promoted to full time QA as a SR QA Engineer.</li><li>Developed and administered SQA Intranet site and documentation server.</li><li>Developed test plans and test matrices for various aspects of 3D graphics testing.</li><li>Qualified graphics hardware, drivers via verification, validation, black box, and regression type testing&nbsp;</li><li>Project Lead and responsible for the shipping quality of the Voodoo 5 5500 initial retail release.</li><li>Assisted with the development and implementation of Product Release processes and procedures.</li><li>Worked closely with development and manufacturing sites during product development cycles.</li><li>Traveled to multiple offsite/shore locations providing training and mentoring of all QA staff.</li><li>Attended numerous trade shows acting as a technical representative for all 3dfx products.</li></ul>'),
(4, 'Beta Program Manager', '2001-09-01', '2003-08-01', 6, '3Dfx', '<ul><li>Inherited ReplayTV Beta Program was responsible for revising the program to accommodate all SONICblue products.</li><li>Designed and developed the Beta Program public website.</li><li>Maintained all servers, accounts, and databases related to the Beta Program website.</li><li>Increased the overall effectiveness and efficiency of the Beta Program</li><li>Created documentation detailing the operational aspects of the Beta Program</li><li>Trained and managed Beta Coordinators tasked with running product specific beta test cycles.</li></ul>'),
(5, 'QA Lead ', '2024-06-01', '2026-01-01', 4, '', '<ul><li>Continued 3-year contract with company that won contract</li><li>Lead QA for Financial Loan Management application and related micro services</li><li>General Support for new incoming team as the SME for the Application&nbsp;</li><li>Served as interim Scrum Master to get the project kicked off with the new team</li><li>Configured JIRA Project as needed to facilitate how the team plans and tracks their work&nbsp;</li><li>Closely worked with client product owner as well as the engineering project lead to uphold baseline quality requirements throughout the development cycle.</li><li>Created custom dashboards showing metrics for the entire project for the client stakeholders</li><li>Helped Business Analysts with new functional User Interface screen design approach</li><li>Led Grooming Sessions with team and wrote many of the User Stories to facilitate estimation, capacity planning, and tracking of team velocity over the projects scheduled timeline.</li><li>Supported Business Analysts by developing the UI mock screens related to new functionality&nbsp;</li><li>Authored source reference documentation for Business Analysts to use while generating new Business Requirements Documentation.&nbsp;</li><li>Generated Requirements documentation for various use cases during active development.</li><li>Continued to develop the Test Case test tool and refine, fix, or add new functionality to it to facilitate test documentation</li><li>Tracked test execution through all project phases (initial development, sprint boundary verification, user acceptance testing,and finally qualifying for production deployment.&nbsp;</li></ul>'),
(6, 'QA Manager', '2010-02-01', '2012-01-01', 8, NULL, '<p>Managed QA Automation team and Black Box Testing Resources&nbsp;</p><ul><li>Managed a very large, distributed QA team of over 30 people in multiple locations worldwide.</li><li>Extensive travel to interface with and train offshore teams&nbsp;</li><li>Mentored and assisted in the career development of all direct reports.&nbsp;</li><li>I interviewed and trained new hires into the QA Organization.&nbsp;</li><li>Performed all duties and responsibilities commensurate with a management level position</li></ul>'),
(7, 'QA Manager', '1999-01-01', '2000-12-01', 3, NULL, '<ul><li>Promoted to QA Manager of the Software Quality Assurance team.</li><li>Mentored and assisted in the career development of all direct reports.</li><li>Performed all duties and responsibilities commensurate with a management level position.</li><li>Participating member of the Product Releases core management team.</li><li>Backup release engineer</li></ul>'),
(8, 'Lab Manager', '1997-11-01', '1998-02-01', 3, NULL, '<ul><li>Contracted to build out the QA Test lab.&nbsp;</li><li>Administrated the SQA Domain, DHCP, FTP, file-servers, and configured Networking access to the QA Lab.&nbsp;</li><li>Set up, configured, maintained, and inventoried all QA test machines and equipment.&nbsp;</li><li>Doubled as a QA Engineer when time permitted.</li></ul>'),
(9, 'Senior QA Engineer / Trainer', '2023-11-01', '2024-06-01', 5, NULL, '<ul><li>3-year government contract Senior QA Engineer / Trainer&nbsp;</li><li>Create test documentation for Loan servicing web application.&nbsp;</li><li>Developed Performance Automation tests using JMeter&nbsp;</li><li>Create UI Automation via Selenium&nbsp;</li><li>JMeter / Load Runner Performance/Load testing&nbsp;</li><li>JIRA, Microsoft Teams, and Office suite of products&nbsp;</li><li>WinSCP, VDI/Virtual Machines,&nbsp;</li><li>Java, Apache Maven, Angular JavaScript front-end using Spring framework running out of IBM Liberty Server&nbsp;</li><li>Developed an Access front-end and back-end database to house all tests and results.</li></ul>'),
(10, 'QA Manager', '2001-01-01', '2001-09-01', 10, NULL, '<ul><li>Created QA organization from the ground up for the Consumer Electronics division of Frontpath.</li><li>Developed all testing documentation and helped define the software release processes.</li><li>Setup, Configured and Maintained SQA Fileservers and SQA Intranet site.</li><li>Set up and configured multiple server rooms and testing labs.</li><li>Researched, set up, and maintained a web-based Defect Tracking database.</li><li>Participating as a member of the Product Development core team.</li><li>Interview and train new hires to the QA Organization.</li><li>Traveled to various trade shows to manage the setup teams and serve as a technical resource.</li></ul>'),
(11, 'Principal QA Engineer', '2012-01-01', '2012-10-01', 8, NULL, '<ul><li>Technical role focusing on all aspects of quality for the Consumer Electronics division of R&amp;D.</li><li>Focused on emerging technologies and special projects as directed.</li></ul>'),
(12, 'Senior QA Engineer/Project Lead                                                                                              ', '2009-03-01', '2010-02-01', 8, NULL, '<ul><li>Directed the overall Integration test efforts for TotalGuide CE client.</li><li>Administered and customized corporate instance of JIRA</li><li>Extensive travel to interface with and train offshore teams</li><li>Defined test plans test cases and general test approach.</li><li>Coordinated with contemporary development and QA teams responsible for delivering ecosystem components.</li></ul>'),
(13, 'Senior QA Engineer/Lead', '2005-11-01', '2009-03-01', 7, NULL, '<ul><li>Tested Media Server class software.&nbsp;</li><li>Worked with Microsoft to obtain DRM certificates for for content protected playback of video&nbsp;</li><li>Worked with contracted engineering to help deliver zeroconf networking capabilities in client server software</li><li>Became familiar with many forms of video codecs, containers, storage formats.&nbsp;</li><li>Defined overall QA test strategy, attended DLNA interoperability events with other consumer electronic manufacturers&nbsp;</li></ul>'),
(14, 'Senior QA Engineer / Team Lead', '2013-01-01', '2017-02-01', 11, NULL, '<ul><li>1st test resource hired into QA team.</li><li>Implemented and Administered defect tracking and wiki software (Atlassian)</li><li>Implemented and Administered test case management system (Testrail), set up QA lab and domain.</li><li>Create test documentation in the form of test plans, test suites, test matrices, and test cases.</li><li>Validate all aspects of streaming media technologies from component, feature, integration, regression, and end to end test scenarios.</li><li>Configured integration between various company tools Zendesk, Testrail, Jira)&nbsp;</li></ul>'),
(15, 'Senior Staff QA Engineer/Multi-Team Lead/Manager', '2017-02-01', '2021-03-01', 11, NULL, '<ul><li>Performed onboarding and mentoring of new QA team hires as needed.</li><li>Primary test resource and QA technical lead for ClearCaster Product line</li><li>Primary test resource and QA technical lead for Wowza Streaming Engine product.</li><li>Managed two direct reports (QA Engineers)&nbsp;</li></ul>'),
(16, 'Senior Manager QA (Head of QA)', '2021-03-01', '2023-03-01', 11, NULL, '<ul><li>Promoted to head of QA in line with re-establishing QA as an org within engineering.&nbsp;</li><li>10 QA Direct Reports</li><li>12 third party developer/QA engineers who followed QA methodology when performing as a test resource.&nbsp;</li><li>Directed and coordinated test efforts across a globally distributed QA team.&nbsp;</li><li>Project Management of QA internal initiatives that helped the team move from multiple separate teams to an organization that had established best practices.</li><li>Normalized tools and approach, created a community of practice that enabled team members to distribute subject matter expertise across the entire team.</li><li>Helped with the adoption of modern concepts and focus from a primarily manual test approach to one that embraced automation and continuous delivery</li><li>Helped design and implement an AWS EC2 based infrastructure that automated software installation across multiple platforms, installing hardware specific drivers and system configuration for use in automated tests.</li><li>Created &nbsp;custom docker images and used them with CircleCI for test automation jobs&nbsp;</li><li>Worked with external/third-party test and development teams to align process and delivery.</li><li>Responsible for overall product line test strategy and QA related KPI’s.&nbsp;</li><li>Coordinated and worked closely with peer engineering leaders.&nbsp;</li><li>Help define and implement company-wide career development program.</li><li>Mentored/Coached, and Supported team members facilitated improving their capabilities, and advancing their careers.</li><li>Perform SRE /Tier 3 duties when needed to debug, isolate, and resolve issues found or reported in production environments.&nbsp;</li><li>Developed tooling for Support organization to help them solve customer issues earlier and without the need for engineering escalation.&nbsp;</li><li>Worked with manufacturers to help resolve issues with ClearCaster hardware encoder.&nbsp;</li><li>Cloud SAAS product was a ruby on rails app backed by a MySQL database and served the public API and User interface.&nbsp;</li><li>Maintained, fixed, and delivered newer versions of encoder software affecting large customers.</li></ul>'),
(17, 'Senior QA Engineer', '2004-03-01', '2005-09-01', 9, NULL, '<ul><li>Became familiar with Network Appliance hardware and software.</li><li>Served as SQA engineer within the Windows SAN QA team testing SnapDrive MMC interface.</li><li>Experienced with SAN technologies (iSCSI, FCP, MPIO, hardware &amp; software initiators).</li></ul>'),
(18, 'Senior Lab Manager', '1997-02-01', '1997-10-01', 2, NULL, '<ul><li>Ran all day to day operations of the Resource Lab.&nbsp;</li><li>Configured and setup all computer systems for employees.&nbsp;</li><li>Single escalation point for all IT Desktop Support Personnel</li></ul>');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `profile_id` int(11) NOT NULL,
  `profile_name` varchar(50) NOT NULL,
  `profile_description` varchar(200) NOT NULL,
  `github_url` varchar(200) NOT NULL,
  `linkedin_url` varchar(200) NOT NULL,
  `website_url` varchar(200) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `bg_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`profile_id`, `profile_name`, `profile_description`, `github_url`, `linkedin_url`, `website_url`, `email_address`, `bg_image`) VALUES
(1, 'Patrick D Day', 'Quality Assurance Professional with 30 years experience', 'https://github.com/eldday', 'https://linkedin.com/in/eldday', 'https://ddayzed.com', 'pday@ddayzed.com', 'shoes.png');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `Skill_id` int(11) NOT NULL,
  `Skill_name` varchar(255) NOT NULL,
  `Skill_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`Skill_id`, `Skill_name`, `Skill_category_id`) VALUES
(1, 'Agile / Scrum', 1),
(2, 'SAFe', 1),
(3, 'Waterfall', 1),
(4, 'Capability Maturity Model', 1),
(5, 'SVN', 3),
(6, 'CircleCI', 3),
(7, 'Git/Github', 3),
(8, 'Linux', 5),
(9, 'Windows / Windows Server', 5),
(10, 'MacOS, iOS', 5),
(11, 'Docker Containers', 5),
(12, 'Virtual Machines', 5),
(13, 'Testrail Administration / Customization', 9),
(14, 'Atlassian Suite -\r\nJIRA -\r\nConfluence -\r\nBitBucket -\r\nStatusPage \r\nAdministration/Customization', 9),
(15, 'Leading globally distributed teams', 2),
(16, 'Manual -> Automation ', 4),
(17, 'Knowledge-sharing skill-leveling', 4),
(18, 'Community of Practice', 4),
(19, 'Cross-functional collaboration', 11),
(20, 'Broadcast, Live Streaming,VOD, OTT, WebRTC, SRT', 12),
(21, 'Video Streaming Protocols (HLS, DASH, etc)', 12),
(22, 'Video Transcoding (Software and hardware)', 12),
(23, 'DRM: PlayReady, WideVine, Fairplay', 12),
(24, 'Automation Test frameworks', 3),
(25, 'QA/Engineering Leadership', 2),
(26, 'Mentoring, Career Development.\r\n\r\n', 2),
(27, 'Tuning/Normalization/Process\r\n\r\n', 2),
(28, 'Leadership Inspiring Individuals https://tinyurl.com/2jd9jduh', 10),
(29, 'Quality Systems Management', 7),
(30, 'QA Best Practices\r\n\r\n', 7),
(32, 'Test Documentation, Curation, Reporting\r\n\r\n', 7),
(33, 'Test Automation / Test Execution\r\n\r\n', 7),
(34, 'Embedded Systems', 5),
(35, 'Target Process Administration Customization', 9);

-- --------------------------------------------------------

--
-- Table structure for table `user-accounts`
--

CREATE TABLE `user-accounts` (
  `user_id` int(11) NOT NULL,
  `login_id` varchar(20) NOT NULL,
  `login_pword` varchar(200) NOT NULL,
  `access_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user-accounts`
--

INSERT INTO `user-accounts` (`user_id`, `login_id`, `login_pword`, `access_id`) VALUES
(1, 'admin', '$2y$10$qZPiIL2f/p2Dxtq6FqBxYuhsfkX4aVXZ2Ab5lpX5xOlErodn/4OCW', 2),
(2, 'reader', '$2y$10$w4kEcPVeu8hI8W6f3t/puOEWYFcHAEchnU3LdxWsJQjIWa6lWJ1ge', 1),
(3, 'viewer', '$2y$10$2rLCtb9YEGYr5kJBRU0QbOr4CozhC4fWDYMfxo/6ILwg4ELeYt4Za', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access_level` enum('admin','view') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `access_level`, `created_at`) VALUES
(1, 'admin', '$2y$10$IKVbKePWS64HHpITkhF.MuOixh9QLUpYe4/SEUXB.Ir/WJNiGNU2u', 'admin', '2025-01-05 01:07:24'),
(2, 'viewer', '$2y$10$SHeUTq392cQHophwsiAZG.Qc/tThAVZb6PFpP0RCfQB.aELBNtDli', 'admin', '2025-01-05 01:33:51'),
(6, 'pday', '$2y$10$yNdxOvOOVWcT4N8Kzx4aEeuJhqpSfekZIdjMDlarP.qhAYCkOSbzC', 'admin', '2025-01-05 01:48:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_levels`
--
ALTER TABLE `access_levels`
  ADD PRIMARY KEY (`access_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`) USING BTREE;

--
-- Indexes for table `Job_history`
--
ALTER TABLE `Job_history`
  ADD UNIQUE KEY `job_id` (`job_id`) USING BTREE;
ALTER TABLE `Job_history` ADD FULLTEXT KEY `job_description` (`job_description`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`profile_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`Skill_id`);

--
-- Indexes for table `user-accounts`
--
ALTER TABLE `user-accounts`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_levels`
--
ALTER TABLE `access_levels`
  MODIFY `access_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `Job_history`
--
ALTER TABLE `Job_history`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `Skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user-accounts`
--
ALTER TABLE `user-accounts`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
