-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 05, 2012 at 08:11 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `seopanelv3`
--

-- --------------------------------------------------------

--
-- Table structure for table `nl_campaigns`
--

CREATE TABLE IF NOT EXISTS `nl_campaigns` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` int(11) NOT NULL,
  `campaign_name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `from_name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `from_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `reply_to` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `is_smtp` tinyint(1) NOT NULL DEFAULT '0',
  `smtp_host` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `smtp_username` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `smtp_password` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `website_id` (`website_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nl_campaigns`
--


-- --------------------------------------------------------

--
-- Table structure for table `nl_email_list`
--

CREATE TABLE IF NOT EXISTS `nl_email_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nl_email_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `nl_entry_list`
--

CREATE TABLE IF NOT EXISTS `nl_entry_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mail_content` text COLLATE utf8_unicode_ci NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `html_mail` tinyint(1) NOT NULL DEFAULT '1',
  `track_clicks` tinyint(1) NOT NULL DEFAULT '0',
  `open_tracking` tinyint(1) NOT NULL DEFAULT '1',
  `unsubscribe_option` int(11) NOT NULL DEFAULT '1',
  `cron` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nl_entry_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `nl_list_mapping`
--

CREATE TABLE IF NOT EXISTS `nl_list_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `newsletter_id` int(11) NOT NULL,
  `email_list_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_id_2` (`newsletter_id`,`email_list_id`),
  KEY `newsletter_id` (`newsletter_id`),
  KEY `email_list_id` (`email_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nl_list_mapping`
--


-- --------------------------------------------------------

--
-- Table structure for table `nl_sending_log`
--

CREATE TABLE IF NOT EXISTS `nl_sending_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `newsletter_id` int(11) NOT NULL,
  `subscriber_id` int(11) NOT NULL,
  `opened` tinyint(1) NOT NULL DEFAULT '0',
  `click_count` tinyint(4) NOT NULL DEFAULT '0',
  `log_message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sent_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_id` (`newsletter_id`,`subscriber_id`),
  KEY `subscriber_id` (`subscriber_id`),
  KEY `newsletter_id_2` (`newsletter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nl_sending_log`
--


-- --------------------------------------------------------

--
-- Table structure for table `nl_settings`
--

CREATE TABLE IF NOT EXISTS `nl_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `set_name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `set_val` text COLLATE utf8_unicode_ci NOT NULL,
  `set_type` enum('small','bool','medium','large','text') CHARACTER SET latin1 DEFAULT 'small',
  PRIMARY KEY (`id`),
  UNIQUE KEY `set_name` (`set_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `nl_settings`
--

INSERT INTO `nl_settings` (`id`, `set_label`, `set_name`, `set_val`, `set_type`) VALUES
(1, 'Allow user to access the campaign manager', 'NL_ALLOW_USER_CAMP_MGR', '0', 'bool'),
(2, 'Allow user to access system email server', 'NL_ALLOW_SYSTEM_EMAIL_SERVER', '1', 'bool'),
(3, 'Newsletter content wordwrap length', 'NL_CONTENT_WORDWRAP', '70', 'small');

-- --------------------------------------------------------

--
-- Table structure for table `nl_subscribers`
--

CREATE TABLE IF NOT EXISTS `nl_subscribers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email_list_id` int(11) NOT NULL DEFAULT '1',
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `subscribed` tinyint(1) NOT NULL DEFAULT '1',
  `source` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'manual',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_list_id` (`email_list_id`,`email`),
  KEY `email_list_id_2` (`email_list_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nl_subscribers`
--


-- --------------------------------------------------------

--
-- Table structure for table `nl_texts`
--

CREATE TABLE IF NOT EXISTS `nl_texts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lang_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `category` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'newsletter',
  `label` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_id` (`lang_code`,`category`,`label`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=81 ;

--
-- Dumping data for table `nl_texts`
--

INSERT INTO `nl_texts` (`id`, `lang_code`, `category`, `label`, `content`, `changed`) VALUES
(1, 'en', 'newsletter', 'Campaign Manager', 'Campaign Manager', '2011-09-28 02:00:12'),
(2, 'en', 'newsletter', 'NL_ALLOW_USER_CAMP_MGR', 'Allow user to access the campaign manager', '2011-09-28 02:01:48'),
(3, 'en', 'newsletter', 'Plugin Settings', 'Plugin Settings', '2011-09-28 02:02:06'),
(4, 'en', 'newsletter', 'settingssaved', 'Plugin settings saved successfully!', '2011-09-28 02:03:33'),
(5, 'en', 'newsletter', 'New Campaign', 'New Campaign', '2011-11-02 00:40:42'),
(6, 'en', 'newsletter', 'From Name', 'From Name', '2011-11-02 00:56:00'),
(7, 'en', 'newsletter', 'From Email', 'From Email', '2011-11-02 00:56:25'),
(8, 'en', 'newsletter', 'Reply To Email', 'Reply To Email', '2011-11-02 00:56:25'),
(9, 'en', 'newsletter', 'Enable SMTP', 'Enable SMTP', '2011-11-02 00:56:42'),
(10, 'en', 'newsletter', 'SMTP Username', 'SMTP Username', '2011-11-02 00:56:42'),
(11, 'en', 'newsletter', 'SMTP Password', 'SMTP Password', '2011-11-02 00:56:54'),
(12, 'en', 'newsletter', 'NL_ALLOW_SYSTEM_EMAIL_SERVER', 'Allow user to access system email server', '2011-11-02 01:02:50'),
(13, 'en', 'newsletter', 'campaignexists', 'Campaign already exists!', '2011-11-02 01:54:56'),
(14, 'en', 'newsletter', 'Create Newsletter', 'Create Newsletter', '2011-12-15 02:17:07'),
(15, 'en', 'newsletter', 'Edit Campaign', 'Edit Campaign', '2011-12-15 02:32:17'),
(16, 'en', 'newsletter', 'Newsletter Manager', 'Newsletter Manager', '2011-12-16 01:15:37'),
(17, 'en', 'newsletter', 'Email List Manager', 'Email List Manager', '2011-12-16 01:15:47'),
(18, 'en', 'newsletter', 'Newsletter Reports', 'Newsletter Reports', '2011-12-16 01:17:06'),
(19, 'en', 'newsletter', 'Campaign', 'Campaign', '2011-12-16 01:45:46'),
(20, 'en', 'newsletter', 'Subject', 'Subject', '2011-12-16 01:52:10'),
(21, 'en', 'newsletter', 'New Newsletter', 'New Newsletter', '2011-12-16 02:09:24'),
(22, 'en', 'newsletter', 'Enable HTML Mail', 'Enable HTML Mail', '2012-01-01 22:18:42'),
(23, 'en', 'newsletter', 'Content', 'Content', '2012-01-02 00:07:05'),
(24, 'en', 'newsletter', 'Email List', 'Email List', '2012-01-02 00:08:23'),
(25, 'en', 'newsletter', 'Create Email List', 'Create Email List', '2012-01-02 00:43:44'),
(26, 'en', 'newsletter', 'Enable Unsibscribe Option', 'Enable Unsibscribe Option', '2012-01-02 02:23:23'),
(27, 'en', 'newsletter', 'Enable Clicks Tracking', 'Enable Clicks Tracking', '2012-01-02 02:23:23'),
(28, 'en', 'newsletter', 'newsletterexists', 'Newsletter already exists!', '2012-01-02 16:00:39'),
(29, 'en', 'newsletter', 'Opened', 'Opened', '2012-01-02 16:44:05'),
(30, 'en', 'newsletter', 'Clicked', 'Clicked', '2012-01-02 16:46:18'),
(31, 'en', 'newsletter', 'Sent', 'Sent', '2012-01-02 16:48:51'),
(32, 'en', 'newsletter', 'Send Test Mail', 'Send Test Mail', '2012-01-02 16:51:11'),
(33, 'en', 'newsletter', 'Email Manager', 'Email Manager', '2012-01-02 22:58:24'),
(34, 'en', 'newsletter', 'Subscribed', 'Subscribed', '2012-01-02 23:21:29'),
(35, 'en', 'newsletter', 'Unsubscribed', 'Unsubscribed', '2012-01-02 23:21:39'),
(36, 'en', 'newsletter', 'Edit Newsletter', 'Edit Newsletter', '2012-01-02 23:33:53'),
(37, 'en', 'newsletter', 'New Email List', 'New Email List', '2012-01-02 23:35:08'),
(38, 'en', 'newsletter', 'Edit Email List', 'Edit Email List', '2012-01-02 23:35:22'),
(39, 'en', 'newsletter', 'emaillistexists', 'Email list already exists!', '2012-01-04 00:25:30'),
(40, 'en', 'newsletter', 'Import Emails', 'Import Emails', '2012-01-04 01:19:37'),
(41, 'en', 'newsletter', 'Email Addresses', 'Email Addresses', '2012-01-04 02:12:54'),
(42, 'en', 'newsletter', 'Email Addresses CSV File', 'Email Addresses CSV File', '2012-01-04 02:14:03'),
(43, 'en', 'newsletter', 'Please enter email addresses or CSV file', 'Please enter email addresses or CSV file', '2012-01-04 23:49:22'),
(44, 'en', 'newsletter', 'Sample CSV File', 'Sample CSV File', '2012-01-05 00:06:51'),
(45, 'en', 'newsletter', 'Import Summary', 'Import Summary', '2012-01-05 23:18:20'),
(46, 'en', 'newsletter', 'Valid', 'Valid', '2012-01-06 01:43:59'),
(47, 'en', 'newsletter', 'Invalid', 'Invalid', '2012-01-06 01:44:22'),
(48, 'en', 'newsletter', 'Existing', 'Existing', '2012-01-06 01:44:51'),
(49, 'en', 'newsletter', 'Source', 'Source', '2012-01-11 02:09:33'),
(50, 'en', 'newsletter', 'Edit Email Address', 'Edit Email Address', '2012-01-11 12:27:12'),
(51, 'en', 'newsletter', 'emailexists', 'Email address already exists!', '2012-01-11 12:38:03'),
(52, 'en', 'newsletter', 'Subscribe', 'Subscribe', '2012-01-12 02:24:36'),
(53, 'en', 'newsletter', 'Unsubscribe', 'Unsubscribe', '2012-01-12 02:24:52'),
(54, 'en', 'newsletter', 'Generate Subscribe Code', 'Generate Subscribe Code', '2012-01-12 02:49:50'),
(55, 'en', 'newsletter', 'Height', 'Height', '2012-01-12 12:23:23'),
(56, 'en', 'newsletter', 'Width', 'Width', '2012-01-12 12:27:46'),
(57, 'en', 'newsletter', 'Your email address', 'Your email address', '2012-01-13 01:28:15'),
(58, 'en', 'newsletter', 'Subscribe Newsletter', 'Subscribe Newsletter', '2012-01-13 01:28:24'),
(59, 'en', 'newsletter', 'successsubscribemsg', 'Successfully subscribed to newsletter!', '2012-01-13 01:54:56'),
(60, 'en', 'newsletter', 'Background Colour Code', 'Background Colour Code', '2012-01-13 02:31:13'),
(61, 'en', 'newsletter', 'Newsletter', 'Newsletter', '2012-01-14 02:00:55'),
(62, 'en', 'newsletter', 'Success', 'Success', '2012-01-18 02:25:46'),
(63, 'en', 'newsletter', 'Sent Time', 'Sent Time', '2012-01-19 02:23:17'),
(64, 'en', 'newsletter', 'Sent Log', 'Sent Log', '2012-01-19 02:25:22'),
(65, 'en', 'newsletter', 'Newsletter Test Email', 'Newsletter Test Email', '2012-01-25 01:58:01'),
(66, 'en', 'newsletter', 'Subscriber', 'Subscriber', '2012-01-25 12:43:04'),
(67, 'en', 'newsletter', 'Enable Email Open Tracking', 'Enable Email Open Tracking', '2012-01-26 02:04:52'),
(68, 'en', 'newsletter', 'to unsubscribe from email list', 'to unsubscribe from email list', '2012-01-26 02:51:14'),
(69, 'en', 'newsletter', 'SMTP Host', 'SMTP Host', '2012-01-27 01:40:04'),
(70, 'en', 'newsletter', 'NL_CONTENT_WORDWRAP', 'Newsletter content word wrap length', '2012-01-27 02:07:17'),
(71, 'en', 'newsletter', 'Add to Cron Job', 'Add to Cron Job', '2012-01-31 12:33:07'),
(72, 'en', 'newsletter', 'Start Date', 'Start Date', '2012-01-31 12:42:21'),
(73, 'en', 'newsletter', 'croncommandtext', 'Add following command to your cron job for sending newsletters to subscribers.', '2012-02-01 02:58:57'),
(74, 'en', 'newsletter', 'End Date', 'End Date', '2012-02-02 00:59:16'),
(75, 'en', 'newsletter', 'Send Newsletter', 'Send Newsletter', '2012-02-03 02:17:49'),
(76, 'en', 'newsletter', 'escapetostop', 'Press <b>escape</b> key to stop sending newsletter.', '2012-02-03 02:22:11'),
(77, 'en', 'newsletter', 'tostartsendingnewsletter', 'to start sending newsletter again if you stopped execution', '2012-02-03 02:23:42'),
(78, 'en', 'newsletter', 'No campaigns found!', 'No campaigns found!', '2012-02-05 21:57:25'),
(79, 'en', 'newsletter', 'No newsletter found!', 'No newsletter found!', '2012-02-05 22:00:16'),
(80, 'en', 'newsletter', 'No email list found!', 'No email list found!', '2012-02-05 22:06:38');
