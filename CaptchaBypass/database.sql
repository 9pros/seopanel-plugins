--
-- Table structure for table `cb_services`
--

CREATE TABLE IF NOT EXISTS `cb_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `identifier` enum('deathbycaptcha','anti-captcha', '2captcha', 'rucaptcha', 'captchas.io') COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_key` text COLLATE utf8_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cb_texts`
--

CREATE TABLE IF NOT EXISTS `cb_texts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lang_code` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `category` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'CaptchaBypass',
  `label` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang_code` (`lang_code`,`category`,`label`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `cb_settings`
--

CREATE TABLE IF NOT EXISTS `cb_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `set_name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `set_val` text COLLATE utf8_unicode_ci NOT NULL,
  `set_type` enum('small','bool','medium','large','text') CHARACTER SET latin1 DEFAULT 'small',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `set_name` (`set_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cb_settings`
--

INSERT INTO `cb_settings` (`id`, `set_label`, `set_name`, `set_val`, `set_type`, `display`) VALUES
(1, 'Enable directory auto submission', 'CB_ENABLE_DIR_AUTO_SUBMISSION', '0', 'bool', 1),
(2, 'Directory auto submission interval', 'CB_DIR_AUTO_SUBMISSION_INTERVAL', '20', 'small', 1);


INSERT INTO `cb_texts` (`category`, `label`, `content`) VALUES 
('CaptchaBypass', 'Edit Captcha Bypass Service', 'Edit Captcha Bypass Service'),
('CaptchaBypass', 'New Captcha Bypass Service', 'New Captcha Bypass Service'),
('CaptchaBypass', 'API Key', 'API Key'),
('CaptchaBypass', 'CB_ENABLE_DIR_AUTO_SUBMISSION', 'Enable directory auto submission'),
('CaptchaBypass', 'CB_DIR_AUTO_SUBMISSION_INTERVAL', 'Directory auto submission interval'),
('CaptchaBypass', 'Captcha Bypass Manager', 'Captcha Bypass Manager');

