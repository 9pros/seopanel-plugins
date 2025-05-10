
-- version 2.1.0 changes

ALTER TABLE `subscription_invoice` CHANGE `txn_id` `txn_id` VARCHAR(160) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `subscription_invoice` CHANGE `txn_log` `txn_log` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

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