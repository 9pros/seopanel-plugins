--
-- Table structure for table `bc_blog_meta`
--

CREATE TABLE IF NOT EXISTS `bc_blog_meta` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `domain` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cookie_send` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_of_results_page` int(8) DEFAULT NULL,
  `start` int(11) NOT NULL DEFAULT '0',
  `max_results` int(11) NOT NULL DEFAULT '100',
  `regex` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url_index` int(11) NOT NULL DEFAULT '1',
  `title_index` int(11) NOT NULL DEFAULT '2',
  `description_index` int(11) NOT NULL DEFAULT '3',
  `comment_script` varchar(200) COLLATE utf8_unicode_ci DEFAULT 'wp-comments-post.php',
  `author_col` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'author',
  `email_col` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'email',
  `url_col` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'url',
  `comment_col` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'comment',
  `comment_post_ID_col` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'comment_post_ID',
  `extra_val` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'submit=Submit Comment',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bc_blog_meta`
--

INSERT INTO `bc_blog_meta` (`id`, `domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `max_results`, `regex`, `url_index`, `title_index`, `description_index`, `comment_script`, `author_col`, `email_col`, `url_col`, `comment_col`, `comment_post_ID_col`, `extra_val`, `status`) VALUES
(1, 'search.wordpress.com', 'http://[--lang--].search.wordpress.com/?q=[--keyword--]&f=feed&page=[--page--]', NULL, 10, 0, 0, '<item>.*?<title>(.*?)<\\/title>.*?<link>(.*?)<\\/link>.*?<description>(.*?)<\\/description>.*?<\\/item>', 2, 1, 3, 'wp-comments-post.php', 'author', 'email', 'url', 'comment', 'comment_post_ID', 'submit=Submit Comment', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bc_projects`
--

CREATE TABLE IF NOT EXISTS `bc_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `website_id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lang_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `max_links` int(8) NOT NULL DEFAULT '100',
  `comment1` text COLLATE utf8_unicode_ci NOT NULL,
  `comment2` text COLLATE utf8_unicode_ci NOT NULL,
  `comment3` text COLLATE utf8_unicode_ci NOT NULL,
  `comment4` text COLLATE utf8_unicode_ci NOT NULL,
  `comment5` text COLLATE utf8_unicode_ci NOT NULL,
  `comment6` text COLLATE utf8_unicode_ci NOT NULL,
  `comment7` text COLLATE utf8_unicode_ci NOT NULL,
  `comment8` text COLLATE utf8_unicode_ci NOT NULL,
  `comment9` text COLLATE utf8_unicode_ci NOT NULL,
  `comment10` text COLLATE utf8_unicode_ci NOT NULL,
  `crawled_page` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `bc_projects`
--


-- --------------------------------------------------------

--
-- Table structure for table `bc_search_results`
--

CREATE TABLE IF NOT EXISTS `bc_search_results` (
  `id` bigint(24) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `blog_se_id` smallint(6) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `submitted` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `checked_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_id` (`project_id`,`blog_se_id`,`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `bc_search_results`
--


-- --------------------------------------------------------

--
-- Table structure for table `bc_settings`
--

CREATE TABLE IF NOT EXISTS `bc_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `set_name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `set_val` text COLLATE utf8_unicode_ci NOT NULL,
  `set_type` enum('small','bool','medium','large','text') CHARACTER SET latin1 DEFAULT 'small',
  PRIMARY KEY (`id`),
  UNIQUE KEY `set_name` (`set_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `bc_settings`
--

INSERT INTO `bc_settings` (`id`, `set_label`, `set_name`, `set_val`, `set_type`) VALUES
(1, 'Allow user to access project manager', 'BC_ALLOW_USER_PRJ_MGR', '0', 'bool'),
(3, 'Maximum number of blog links per project', 'BC_MAX_BLOG_LINKS', '100', 'small'),
(4, 'Allow user to submit comment to a blog more than one time', 'BC_COMMENT_POST_ONCE', '0', 'bool'),
(2, 'Allow user to submit comment to blog ', 'BC_ALLOW_USER_SUBMIT_COMM', '0', 'bool');

-- --------------------------------------------------------

--
-- Table structure for table `bc_texts`
--

CREATE TABLE IF NOT EXISTS `bc_texts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lang_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `category` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'blogcommentor',
  `label` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_id` (`lang_code`,`category`,`label`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=39 ;

--
-- Dumping data for table `bc_texts`
--

INSERT INTO `bc_texts` (`id`, `lang_code`, `category`, `label`, `content`, `changed`) VALUES
(1, 'en', 'blogcommentor', 'Plugin Settings', 'Plugin Settings', '2011-03-10 01:10:52'),
(2, 'en', 'blogcommentor', 'BC_ALLOW_USER_PRJ_MGR', 'Allow user to access project manager', '2011-03-10 01:29:34'),
(3, 'en', 'blogcommentor', 'settingssaved', 'Plugin settings saved successfully!', '2011-03-10 01:35:59'),
(4, 'en', 'blogcommentor', 'Projects Manager', 'Projects Manager', '2011-03-10 02:11:52'),
(5, 'en', 'blogcommentor', 'New Project', 'New Project', '2011-03-10 02:26:42'),
(6, 'en', 'blogcommentor', 'BC_MAX_BLOG_LINKS', 'Maximum number of blog links allowed per project', '2011-03-10 13:21:00'),
(7, 'en', 'blogcommentor', 'Maximum Blog Links', 'Maximum number of blog links to which comment submitted', '2011-03-10 13:32:02'),
(8, 'en', 'blogcommentor', 'Comment', 'Comment', '2011-03-11 01:27:16'),
(9, 'en', 'blogcommentor', 'Project already exist', 'Project already exist', '2011-03-11 01:34:14'),
(10, 'en', 'blogcommentor', 'numberlinksgreater', 'Number of links is greater than maximum links allowed', '2011-03-11 01:46:05'),
(11, 'en', 'blogcommentor', 'numbercharslessincomment', 'Number of characters in comment is less than', '2011-03-11 01:57:23'),
(12, 'en', 'blogcommentor', 'Run Project', 'Run Project', '2011-03-17 00:12:33'),
(13, 'en', 'blogcommentor', 'Submit Comment', 'Submit Comment', '2011-03-17 00:18:55'),
(14, 'en', 'blogcommentor', 'escapetostop', 'Press <b>escape</b> key to stop execution of script', '2011-03-23 00:52:29'),
(15, 'en', 'blogcommentor', 'torunproject', 'to run project again', '2011-03-23 00:54:11'),
(16, 'en', 'blogcommentor', 'Successfully saved blog search links', 'Successfully saved blog search links', '2011-03-23 01:09:46'),
(17, 'en', 'blogcommentor', 'savedlinkswaitingnext', 'Saved blog links..Waiting for crawling next set of links for', '2011-03-23 01:14:37'),
(18, 'en', 'blogcommentor', 'to submit comments to blog links', 'to view the reports of blog links.', '2011-03-23 01:21:21'),
(19, 'en', 'blogcommentor', 'Regular expression failed', 'Regular expression failed!. Please run project again.', '2011-03-23 02:17:59'),
(20, 'en', 'blogcommentor', 'Search result page is empty', 'Search result page is empty', '2011-03-23 02:36:10'),
(21, 'en', 'blogcommentor', 'keywordinfotext', 'The keyword used to search and find blog links', '2011-03-26 00:18:58'),
(22, 'en', 'blogcommentor', 'Maximum Links', 'Maximum Links', '2011-03-30 00:34:57'),
(23, 'en', 'blogcommentor', 'View Reports', 'View Reports', '2011-03-30 00:41:31'),
(24, 'en', 'blogcommentor', 'Crawled Links', 'Crawled Links', '2011-03-30 01:11:12'),
(25, 'en', 'blogcommentor', 'Approved', 'Approved', '2011-04-01 00:19:49'),
(26, 'en', 'blogcommentor', 'Submitted', 'Submitted', '2011-04-01 00:19:49'),
(27, 'en', 'blogcommentor', 'wantblogsubmit', 'We recommend you to use cron for blog submission to get better results.', '2011-04-01 02:15:20'),
(28, 'en', 'blogcommentor', 'emailinfotext', 'Email submitted with comment to blogs', '2011-04-02 02:18:08'),
(29, 'en', 'blogcommentor', 'Comment Email', 'Comment Email', '2011-04-02 02:18:54'),
(30, 'en', 'blogcommentor', 'alreadysubmittedcomment', 'You have already submitted comment to this blog', '2011-04-06 00:31:46'),
(31, 'en', 'blogcommentor', 'BC_COMMENT_POST_ONCE', 'Allow user to submit comment to a blog more than one time', '2011-04-07 00:46:32'),
(32, 'en', 'blogcommentor', 'No active projects found', 'No active projects found', '2011-04-07 00:51:41'),
(33, 'en', 'blogcommentor', 'croncommandtextsubmit', 'Add following command to your cron tab for submitting comments to blogs twice in an hour', '2011-04-07 01:57:01'),
(34, 'en', 'blogcommentor', 'BC_ALLOW_USER_SUBMIT_COMM', 'Allow user to submit comment to blog', '2011-04-08 00:56:42'),
(35, 'en', 'blogcommentor', 'Inactive Links', 'Inactive Links', '2012-09-20 22:56:26'),
(36, 'en', 'blogcommentor', 'Active Links', 'Active Links', '2012-09-20 22:56:26'),
(37, 'en', 'blogcommentor', 'Checked Links', 'Checked Links', '2012-09-20 22:56:26'),
(38, 'en', 'blogcommentor', 'Copy Project', 'Copy Project', '2012-09-20 22:56:26');


UPDATE bc_blog_meta SET `extra_val` = 'comment_parent=0' WHERE domain = 'search.wordpress.com';

ALTER TABLE `bc_blog_meta` ADD `extra_cols` VARCHAR(255) NOT NULL AFTER `extra_val`;

UPDATE `bc_blog_meta` SET `extra_cols` = 
'comment_post_url,highlander_comment_nonce,_wp_http_referer,hc_post_as,ak_js,akismet_comment_nonce,genseq,wp_access_token,wp_avatar,wp_user_id'
WHERE domain = 'search.wordpress.com';

INSERT INTO `bc_texts` (`lang_code`, `category`, `label`, `content`) 
VALUES ('en', 'blogcommentor', 'Check Submission', 'Check Submission');

INSERT INTO `bc_texts` (`lang_code`, `category`, `label`, `content`) 
VALUES ('en', 'blogcommentor', 'link_title_info', 'Please add link titles by comma separated.<br> Eg: seo tools,seo script,seo tracker');

ALTER TABLE `bc_projects` ADD `link_title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `website_id`;

ALTER TABLE `bc_projects` ADD `project_name` VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `id`;

ALTER TABLE `bc_projects` CHANGE `keyword` `keyword` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `link_title` `link_title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `email` `email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment1` `comment1` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment2` `comment2` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment3` `comment3` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment4` `comment4` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment5` `comment5` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment6` `comment6` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment7` `comment7` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment8` `comment8` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment9` `comment9` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment10` `comment10` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
