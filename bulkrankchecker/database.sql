
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `brc_campaigns` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `lang_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `country_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `cron_job` tinyint(1) NOT NULL DEFAULT '1',
  `report_interval` tinyint(4) NOT NULL DEFAULT '1',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00' COMMENT 'used for front end report generation',
  `last_generated` datetime NOT NULL DEFAULT '0000-00-00' COMMENT 'used cron job report generation',
  `send_reports` enum('Not Send','CSV','Pdf','Html') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Not Send',
  `email_address` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `website_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `brc_keywords` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `keyword` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `campaign_id` (`campaign_id`,`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `brc_links` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `campaign_id` (`campaign_id`,`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `brc_searchengines` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `searchengine_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `campaign_id` (`campaign_id`,`searchengine_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `brc_searchresults` (
  `id` bigint(24) unsigned NOT NULL AUTO_INCREMENT,
  `keyword_id` int(16) DEFAULT NULL,
  `campaign_id` int(11) NOT NULL,
  `searchengine_id` int(11) DEFAULT NULL,
  `link_id` int(11) NOT NULL,
  `rank` int(8) DEFAULT NULL,
  `link_found` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `link_id` (`link_id`),
  KEY `searchengine_id` (`searchengine_id`),
  KEY `keyword_id` (`keyword_id`),
  KEY `campaign_id` (`campaign_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `brc_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `set_name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `set_val` text COLLATE utf8_unicode_ci NOT NULL,
  `set_type` enum('small','bool','medium','large','text') CHARACTER SET latin1 DEFAULT 'small',
  PRIMARY KEY (`id`),
  UNIQUE KEY `set_name` (`set_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;


INSERT INTO `brc_settings` (`id`, `set_label`, `set_name`, `set_val`, `set_type`) VALUES
(1, 'Allow user to access the campaign manager', 'BRC_ALLOW_USER_CAMP_MGR', '0', 'bool'),
(2, 'Number of keywords in cron job', 'BRC_NUMBER_OF_KEYWORDS_CRON', '1', 'small');


CREATE TABLE IF NOT EXISTS `brc_texts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lang_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `category` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'bulkrankchecker',
  `label` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_id` (`lang_code`,`category`,`label`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;


INSERT INTO `brc_texts` (`id`, `lang_code`, `category`, `label`, `content`, `changed`) VALUES
(1, 'en', 'bulkrankchecker', 'BRC_ALLOW_USER_CAMP_MGR', 'Allow user to access the campaign manager', '2013-01-09 00:36:23'),
(2, 'en', 'bulkrankchecker', 'Plugin Settings', 'Plugin Settings', '2013-01-09 00:37:18'),
(3, 'en', 'bulkrankchecker', 'Campaign Manager', 'Campaign Manager', '2013-01-09 00:38:03'),
(4, 'en', 'bulkrankchecker', 'settingssaved', 'Plugin settings saved successfully!', '2013-01-09 01:19:45'),
(5, 'en', 'bulkrankchecker', 'New Campaign', 'New Campaign', '2013-01-10 02:00:09'),
(6, 'en', 'bulkrankchecker', 'campaignexists', 'Campaign already exists!', '2013-02-07 01:43:34'),
(7, 'en', 'bulkrankchecker', 'Duplicate link', 'Duplicate link', '2013-02-15 01:32:53'),
(8, 'en', 'bulkrankchecker', 'Duplicate keyword', 'Duplicate keyword', '2013-02-15 01:33:00'),
(9, 'en', 'bulkrankchecker', 'Cron Report', 'Cron Report', '2013-05-27 09:50:31'),
(10, 'en', 'bulkrankchecker', 'Campaign Report', 'Campaign Report', '2013-05-27 12:02:26'),
(11, 'en', 'bulkrankchecker', 'BRC_NUMBER_OF_KEYWORDS_CRON', 'Number of keywords needs to be checked in each cron execution(Default 0 means all keywords should be checked)', '2013-05-29 00:27:35'),
(12, 'en', 'bulkrankchecker', 'Send reports', 'Send reports', '2013-06-18 11:10:47'),
(13, 'en', 'bulkrankchecker', 'Run Campaign', 'Run Campaign', '2013-06-18 11:11:26'),
(14, 'en', 'bulkrankchecker', 'Report Generated', 'Report Generated', '2014-06-12 01:27:19'),
(15, 'en', 'bulkrankchecker', 'Campaign', 'Campaign', '2014-06-28 02:09:08'),
(16, 'en', 'bulkrankchecker', 'insertemails', 'Insert email addresses separated with comma', '2014-07-24 02:12:59'),
(17, 'en', 'bulkrankchecker', 'Crawling Keyword', 'Crawling Keyword', '2014-08-01 01:53:57'),
(18, 'en', 'bulkrankchecker', 'Crawled Keywords', 'Crawled Keywords', '2014-08-01 01:53:57'),
(19, 'en', 'bulkrankchecker', 'Completed campaign execution', 'Completed campaign execution', '2014-08-01 02:39:11'),
(20, 'en', 'bulkrankchecker', 'crawledsuccesssfullywaitfornext', 'Keyword crawled successfully! Waiting for crawling next keyword for', '2014-08-01 02:56:09');

--
-- version 2.0.0 changes
--

ALTER TABLE `brc_campaigns` CHANGE `last_generated` `last_generated` DATETIME NULL DEFAULT NULL COMMENT 'used cron job report generation';

INSERT INTO `brc_settings` (`set_label`, `set_name`, `set_val`, `set_type`) VALUES 
('Enable reports generation from user interface', 'BRC_ENABLE_UI_REPORT_GENERATION', '0', 'bool');

INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'BRC_ENABLE_UI_REPORT_GENERATION', 'Enable reports generation from user interface');
INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'brc_search_engine_count', 'Search engine count');
INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'Edit Campaign', 'Edit Campaign');
INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'brc_report_interval_limit', 'Lowest report generation interval');
INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'total_count_greater_account_limit', 'Total count is greater than account limit - [limit]');
INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'Selected interval is less than account limit', 'Selected interval is less than account limit');
INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'Link Found', 'Link Found');