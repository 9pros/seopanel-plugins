--
-- Table structure for table `subscription_gatewayoption`
--

CREATE TABLE IF NOT EXISTS `subscription_gatewayoption` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gateway_id` int(11) NOT NULL,
  `set_label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `set_name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `set_val` text COLLATE utf8_unicode_ci NOT NULL,
  `set_type` enum('small','bool','medium','large','text') CHARACTER SET latin1 DEFAULT 'small',
  `validation` enum('blank','number','email','alpha') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'blank',
  `required` tinyint(1) NOT NULL DEFAULT '1',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `gateway_id` (`gateway_id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `subscription_gatewayoption`
--

INSERT INTO `subscription_gatewayoption` (`id`, `gateway_id`, `set_label`, `set_name`, `set_val`, `set_type`, `validation`, `required`, `display`) VALUES
(1, 1, 'Paypal Business Email', 'PP_BUSINESS_EMAIL', '', 'medium', 'email', 1, 1),
(2, 2, 'Paypal Business Email', 'PP_BUSINESS_EMAIL', '', 'medium', 'email', 1, 1),
(3, 1, 'Paypal Token', 'PP_TOKEN', '', 'medium', 'blank', 1, 1),
(4, 2, 'Paypal Token', 'PP_TOKEN', '', 'medium', 'blank', 1, 1),
(5, 1, 'Paypal Url', 'PP_URL', 'https://www.paypal.com/cgi-bin/webscr', 'medium', 'blank', 1, 0),
(6, 2, 'Paypal Url', 'PP_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr', 'medium', 'blank', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_paymentgateway`
--

CREATE TABLE IF NOT EXISTS `subscription_paymentgateway` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gateway_code` varchar(60) NOT NULL,
  `sandbox` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `subscription_paymentgateway`
--

INSERT INTO `subscription_paymentgateway` (`id`, `name`, `gateway_code`, `sandbox`, `status`) VALUES
(1, 'Paypal Standard', 'paypal', 0, 1),
(2, 'Paypal Standard Sandbox', 'paypal', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_settings`
--

CREATE TABLE IF NOT EXISTS `subscription_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `set_name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `set_val` text COLLATE utf8_unicode_ci NOT NULL,
  `set_type` enum('small','bool','medium','large','text') CHARACTER SET latin1 DEFAULT 'small',
  PRIMARY KEY (`id`),
  UNIQUE KEY `set_name` (`set_name`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `subscription_settings`
--

INSERT INTO `subscription_settings` (`id`, `set_label`, `set_name`, `set_val`, `set_type`) VALUES
(1, 'Default Payment Plugin', 'SP_PAYMENT_PLUGIN', '1', 'medium');


--
-- Table structure for table `subscription_invoice`
--

CREATE TABLE IF NOT EXISTS `subscription_invoice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `payment_plugin_id` int(10) unsigned NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(160) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `item_quantity` int(11) NOT NULL DEFAULT '1',
  `item_amount` float NOT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_type` enum('register','upgrade','renew') NOT NULL DEFAULT 'register',
  `status` enum('paid','pending','cancelled') NOT NULL DEFAULT 'pending',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `invoice_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `item_id` (`item_id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;


ALTER TABLE `subscription_invoice` ADD `txn_id` VARCHAR( 160 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `invoice_type` ,
ADD `txn_log` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `txn_id` ;

ALTER TABLE `subscription_invoice` CHANGE `status` `status` ENUM( 'success', 'pending', 'cancelled' ) 
CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending';

-- version 2.0.0 changes

--
-- Table structure for table `subscription_email_templates`
--

CREATE TABLE IF NOT EXISTS `subscription_email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `email_subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT INTO `subscription_email_templates` (`id`, `name`, `email_subject`, `email_content`, `status`) VALUES
(1, 'SUBSCRIPTION_RENEWAL_REMINDER', 'Seo Panel membership expiry reminder', 'Hi [FIRST_NAME] [LAST_NAME],<br><br>\r\n\r\n\r\nYour seo panel membership will <b>expire in [EXPIRE_DAYS] days</b>. Please <b>renew the subscription</b> to continue using all features.<br><br>\r\n\r\n<b>Click on</b> below link to renew the subscription.<br><br>\r\n\r\n<a href=''[RENEW_LINK]''>[RENEW_LINK]</a><br><br>\r\n\r\nRegards,\r\n<br>\r\nSeo Panel Team\r\n<br>\r\nwww.seopanel.in', 1),
(2, 'SUBSCRIPTION_EXPIRED_NOTIFICATION', 'Seo Panel membership expired', 'Hi [FIRST_NAME] [LAST_NAME],<br><br>\r\n\r\n\r\nYour seo panel <b>membership is expired</b>. Please <b>renew the membership</b> subscription to continue using all features.<br><br>\r\n\r\n<b>Click on</b> below link to renew the subscription.<br><br>\r\n\r\n<a href=''[RENEW_LINK]''>[RENEW_LINK]</a><br><br>\r\n\r\nRegards,\r\n<br>\r\nSeo Panel Team\r\n<br>\r\nwww.seopanel.in\r\n  ', 1);

INSERT INTO `subscription_settings` (`set_label`, `set_name`, `set_val`, `set_type`) VALUES
('Membership Renewal Remainder 1(Before)', 'SUBSCRIPTION_RENEWAL_REMINDER_1', '14', 'small');

INSERT INTO `subscription_settings` (`set_label`, `set_name`, `set_val`, `set_type`) VALUES
('Membership Renewal Remainder 2(Before)', 'SUBSCRIPTION_RENEWAL_REMINDER_2', '7', 'small');

INSERT INTO `subscription_settings` (`set_label`, `set_name`, `set_val`, `set_type`) VALUES
('Membership Expired Notification(After)', 'SUBSCRIPTION_EXPIRED_NOTIFICATION', '3', 'small');

INSERT INTO `texts` (`category`, `label`, `content`) VALUES
('label', 'Email Body', 'Email Body'),
('label', 'Days', 'Days'),
('subscription', 'Email Template Manager', 'Email Template Manager'),
('subscription', 'Edit Email Template', 'Edit Email Template'),
('subscription', 'SUBSCRIPTION_RENEWAL_REMINDER_1', 'Membership Renewal Remainder 1(Before)'),
('subscription', 'SUBSCRIPTION_RENEWAL_REMINDER_2', 'Membership Renewal Remainder 2(Before)'),
('subscription', 'SUBSCRIPTION_EXPIRED_NOTIFICATION', 'Membership Expired Notification(After)');
