/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Table structure for table `as_article`
--

CREATE TABLE `as_article` (
  `id` bigint(20) NOT NULL,
  `project_id` int(11) NOT NULL,
  `title` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `short_desc` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `article` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `as_project`
--

CREATE TABLE `as_project` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `as_settings`
--

CREATE TABLE `as_settings` (
  `id` int(11) NOT NULL,
  `set_label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `set_name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `set_val` text COLLATE utf8_unicode_ci NOT NULL,
  `set_type` enum('small','bool','medium','large','text') CHARACTER SET latin1 DEFAULT 'small'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `as_settings`
--

INSERT INTO `as_settings` (`id`, `set_label`, `set_name`, `set_val`, `set_type`) VALUES
(1, 'Allow user to access the project manager', 'AS_ALLOW_USER_PROJECT_MGR', '1', 'bool'),
(2, 'Allow user to access the article directory manager', 'AS_ALLOW_USER_WEBSITE_MGR', '1', 'bool');

-- --------------------------------------------------------

--
-- Table structure for table `as_skip_websites`
--

CREATE TABLE `as_skip_websites` (
  `id` bigint(20) NOT NULL,
  `website_id` int(11) NOT NULL,
  `article_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `as_submit_details`
--

CREATE TABLE `as_submit_details` (
  `id` bigint(20) NOT NULL,
  `article_id` bigint(20) NOT NULL,
  `website_id` int(11) NOT NULL,
  `submit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `submit_status` varchar(16) NOT NULL,
  `submit_status_desc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `confirmation` varchar(16) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `as_texts`
--

CREATE TABLE `as_texts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lang_code` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `category` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'as',
  `label` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `as_texts`
--

INSERT INTO `as_texts` (`id`, `lang_code`, `category`, `label`, `content`, `changed`) VALUES
(1, 'en', 'as', 'Project Manager', 'Project Manager', '2013-03-01 18:12:45'),
(2, 'en', 'as', 'Article Manager', 'Article Manager', '2013-03-01 20:04:28'),
(3, 'en', 'as', 'Article Directory Manager', 'Article Directory Manager', '2013-03-03 18:35:28'),
(4, 'en', 'as', 'Article Submitter', 'Article Submitter', '2013-03-26 23:06:47'),
(6, 'en', 'as', 'AS_ALLOW_USER_PROJECT_MGR', 'Allow user to access the project manager', '2013-01-10 22:01:56'),
(7, 'en', 'as', 'Plugin Settings', 'Plugin Settings', '2013-01-10 22:10:21'),
(8, 'en', 'as', 'settingssaved', 'Plugin settings saved successfully!', '2013-01-10 22:14:01'),
(9, 'en', 'as', 'selectarticleproceed', 'Select a Article to Proceed Article submission.\r\nCheck Directories with out captcha to submit to website without captcha!', '2013-04-05 20:28:46'),
(10, 'en', 'as', 'Please select aN Article to proceed', 'Please select an Article to proceed', '2013-04-05 22:08:42'),
(11, 'en', 'as', 'Article Spinner', 'Article Spinner', '2013-04-23 18:03:18'),
(12, 'en', 'as', 'Manage Spinner', 'Manage Spinner', '2013-04-23 19:27:08'),
(13, 'en', 'as', 'Submission Reports', 'Submission Reports', '2013-05-07 23:43:58'),
(14, 'en', 'as', 'Skipped Submission', 'Skipped Submission', '2013-05-09 06:03:43'),
(15, 'en', 'as', 'Pending', 'Pending', '2013-05-13 10:00:45'),
(16, 'en', 'as', 'Approved', 'Approved', '2013-05-13 10:00:45'),
(17, 'en', 'as', 'Cron Command', 'Cron Command', '2013-05-14 04:34:28'),
(18, 'en', 'as', 'croncommandtextsubmit', 'Add following command to your cron job for checking article status in every 5 minutes according to your schedule.', '2013-05-14 04:37:46'),
(19, 'en', 'as', 'cronwebsitesubmit', 'Add following command to your cron job for checking website status in every 5 minutes according to your schedule.', '2013-05-15 05:18:11'),
(20, 'en', 'as', 'Submit Article', 'Submit Article', '2013-05-15 05:56:58'),
(21, 'en', 'as', 'New Article', 'New Article', '2013-05-15 06:00:20'),
(22, 'en', 'as', 'New Category', 'New Category', '2013-05-15 06:02:19'),
(23, 'en', 'as', 'Article', 'Article', '2013-05-15 06:05:36'),
(24, 'en', 'as', 'Short Description', 'Short Description', '2013-05-15 06:07:48'),
(25, 'en', 'as', 'Search', 'Search', '2013-05-15 06:15:27'),
(26, 'en', 'as', 'Save Article', 'Save Article', '2013-05-15 06:16:17'),
(27, 'en', 'as', 'articleChecker', 'articleChecker', '2013-05-15 06:19:05'),
(28, 'en', 'as', 'ADD', 'ADD', '2013-05-15 06:25:15'),
(29, 'en', 'as', 'New Article Directory', 'New Article Directory', '2013-05-15 06:43:33'),
(30, 'en', 'as', 'Edit Article', 'Edit Article', '2013-05-15 06:48:24'),
(31, 'en', 'as', 'Edit Project', 'Edit Project', '2013-05-15 08:54:45'),
(32, 'en', 'as', 'New Project', 'New Project', '2013-05-15 08:56:23'),
(33, 'en', 'as', 'Edit Article Directory', 'Edit Article Directory', '2013-05-15 09:03:44'),
(34, 'en', 'as', 'Article Directory Name', 'Article Directory Name', '2013-05-15 09:07:10'),
(35, 'en', 'as', 'Domain', 'Domain', '2013-05-15 09:11:45'),
(36, 'en', 'as', 'Article Directory Url', 'Article Directory Url', '2013-05-15 09:15:26'),
(37, 'en', 'as', 'Submit Log', 'Submit Log', '2013-05-15 09:22:09'),
(38, 'en', 'as', 'Undo Skip', 'Undo Skip', '2013-05-16 05:24:46'),
(39, 'en', 'as', 'Directories without captcha', 'Directories without captcha', '2013-05-16 05:32:04'),
(40, 'en', 'as', 'Enter the code shown', 'Enter the code shown', '2013-05-16 05:38:39'),
(41, 'en', 'as', 'Article Directory Importer', 'Article Directory Importer', '2013-05-16 06:10:40'),
(42, 'en', 'as', 'Article Directories', 'Article Directories', '2013-05-16 07:11:53'),
(43, 'en', 'as', 'Article Directory Script Type', 'Article Directory Script Type', '2013-05-16 07:19:10'),
(44, 'en', 'as', 'Link Type', 'Link Type', '2013-05-16 07:20:58'),
(45, 'en', 'as', 'Check Google Pagerank', 'Check Google Pagerank', '2013-05-16 07:21:18'),
(46, 'en', 'as', 'Check Article Directory Status', 'Check Article Directory Status', '2013-05-16 07:21:45'),
(47, 'en', 'as', 'Article Directory', 'Article Directory', '2013-05-16 07:23:49'),
(48, 'en', 'as', 'Show Advanced Fields', 'Show Advanced Fields', '2013-05-16 07:24:38'),
(49, 'en', 'as', 'Advanced Fields', 'Advanced Fields', '2013-05-16 07:29:24'),
(50, 'en', 'as', 'AS_ALLOW_USER_WEBSITE_MGR', 'Allow user to access the article directory manager', '2013-05-16 07:29:24');

-- --------------------------------------------------------

--
-- Table structure for table `as_websites`
--

CREATE TABLE `as_websites` (
  `id` int(11) NOT NULL,
  `type` varchar(60) NOT NULL DEFAULT 'phpld',
  `website_name` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `domain` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `website_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `authentication` tinyint(4) NOT NULL DEFAULT '1',
  `username` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `is_captcha` tinyint(4) NOT NULL DEFAULT '0',
  `category_col` varchar(60) NOT NULL DEFAULT 'CATEGORY_ID',
  `google_pagerank` int(11) NOT NULL DEFAULT '0',
  `captcha_script` varchar(12) NOT NULL DEFAULT 'captcha.php',
  `imagehash_col` varchar(16) NOT NULL DEFAULT 'IMAGEHASH',
  `imagehashurl_col` varchar(16) NOT NULL DEFAULT 'imagehash',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `as_article`
--
ALTER TABLE `as_article`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_id` (`project_id`,`title`);

--
-- Indexes for table `as_project`
--
ALTER TABLE `as_project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `as_settings`
--
ALTER TABLE `as_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `set_name` (`set_name`);

--
-- Indexes for table `as_skip_websites`
--
ALTER TABLE `as_skip_websites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_delete` (`article_id`);

--
-- Indexes for table `as_submit_details`
--
ALTER TABLE `as_submit_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `articleid_delete` (`article_id`);

--
-- Indexes for table `as_texts`
--
ALTER TABLE `as_texts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lang_code` (`lang_code`,`category`,`label`);

--
-- Indexes for table `as_websites`
--
ALTER TABLE `as_websites`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `as_article`
--
ALTER TABLE `as_article`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `as_project`
--
ALTER TABLE `as_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `as_settings`
--
ALTER TABLE `as_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `as_skip_websites`
--
ALTER TABLE `as_skip_websites`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `as_submit_details`
--
ALTER TABLE `as_submit_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `as_texts`
--
ALTER TABLE `as_texts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `as_websites`
--
ALTER TABLE `as_websites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `as_article`
--
ALTER TABLE `as_article`
  ADD CONSTRAINT `project_delete` FOREIGN KEY (`project_id`) REFERENCES `as_project` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `as_skip_websites`
--
ALTER TABLE `as_skip_websites`
  ADD CONSTRAINT `article_delete` FOREIGN KEY (`article_id`) REFERENCES `as_article` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `as_submit_details`
--
ALTER TABLE `as_submit_details`
  ADD CONSTRAINT `articleid_delete` FOREIGN KEY (`article_id`) REFERENCES `as_article` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- version 1.1.0 changes
--

ALTER TABLE `as_submit_details` ADD `ref_id` VARCHAR(200) NULL;

ALTER TABLE `as_websites` CHANGE `domain` `domain` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
CHANGE `website_url` `website_url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
CHANGE `username` `username` VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
CHANGE `password` `password` VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL; 

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
