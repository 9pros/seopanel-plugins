CREATE TABLE `sme_api_details` (
  `id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `api_key_label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `api_key_name` varchar(120) CHARACTER SET latin1 NOT NULL,
  `api_key_value` varchar(200) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `sme_api_details` (`id`, `resource_id`, `api_key_label`, `api_key_name`, `api_key_value`) VALUES
(1, 3, 'Application Id', 'app_id', ''),
(2, 3, 'Secret Key', 'secret_key', ''),
(3, 2, 'Consumer key', 'twitter_con_key', ''),
(4, 2, 'Consumer secret', 'twitter_con_secret', ''),
(7, 1, 'Client ID', 'linkedin_client_id', ''),
(8, 1, 'Client Secret', 'linkedin_client_secret', ''),
(9, 4, 'Application Id', 'app_id', ''),
(10, 4, 'Secret Key', 'secret_key', '');

CREATE TABLE `sme_connections` (
  `id` int(11) NOT NULL,
  `connection_name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `resource_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `connection_status` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `connection_log` text COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `connection_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `sme_connection_links` (
  `id` int(11) NOT NULL,
  `connection_id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `sme_project` (
  `id` int(11) NOT NULL,
  `project` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `sme_resources` (
  `id` int(11) NOT NULL,
  `engine_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `engine_website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `sme_resources` (`id`, `engine_name`, `engine_website`, `status`) VALUES
(1, 'LinkedIn', 'http://www.linkedin.com/', 1),
(2, 'Twitter', 'http://twitter.com/', 1),
(3, 'Facebook', 'http://www.facebook.com/', 1),
(4, 'Pinterest', 'https://www.pinterest.com/', 1);

CREATE TABLE `sme_settings` (
  `id` int(11) NOT NULL,
  `set_label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `set_name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `set_val` text COLLATE utf8_unicode_ci NOT NULL,
  `set_type` enum('small','bool','medium','large','text') CHARACTER SET latin1 DEFAULT 'small'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `sme_settings` (`id`, `set_label`, `set_name`, `set_val`, `set_type`) VALUES
(1, 'Allow user to access the project manager', 'SME_ALLOW_USER_PROJECT_MGR', '0', 'bool');

CREATE TABLE `sme_status` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `share_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `share_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `share_description` text COLLATE utf8_unicode_ci NOT NULL,
  `share_tags` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `schedule_time` datetime DEFAULT NULL,
  `cron_job` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `sme_status_connection_mapping` (
  `id` bigint(20) NOT NULL,
  `status_id` int(11) NOT NULL,
  `connection_link_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `sme_submissions` (
  `id` bigint(24) UNSIGNED NOT NULL,
  `status_id` int(11) NOT NULL,
  `connection_link_id` int(11) NOT NULL,
  `submit_status` enum('Pending','Failed','Success') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Pending',
  `submit_log` text COLLATE utf8_unicode_ci NOT NULL,
  `cron` tinyint(1) NOT NULL DEFAULT '0',
  `submit_time` datetime NOT NULL,
  `impressions` bigint(20) NOT NULL DEFAULT '0',
  `likes` bigint(20) NOT NULL DEFAULT '0',
  `shares` bigint(20) NOT NULL DEFAULT '0',
  `comments` bigint(20) NOT NULL DEFAULT '0',
  `clicks` bigint(20) NOT NULL DEFAULT '0',
  `engagements` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `sme_texts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lang_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `category` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'sme',
  `label` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `sme_texts` (`id`, `lang_code`, `category`, `label`, `content`, `changed`) VALUES
(1, 'en', 'sme', 'SME_ALLOW_USER_PROJECT_MGR', 'Allow user to access the project manager', '2013-01-11 13:31:56'),
(3, 'en', 'sme', 'Status Manager', 'Status Manager', '2013-01-11 13:32:22'),
(4, 'en', 'sme', 'Plugin Settings', 'Plugin Settings', '2013-01-11 13:40:21'),
(5, 'en', 'sme', 'settingssaved', 'Plugin settings saved successfully!', '2013-01-11 13:44:01'),
(6, 'en', 'sme', 'Social Media Manager', 'Social Media Manager', '2013-01-11 13:44:01'),
(7, 'en', 'sme', 'Manage Apps', 'Manage Apps', '2013-01-31 05:15:57'),
(8, 'en', 'sme', 'Submission Reports', 'Submission Reports', '2013-02-22 04:02:32'),
(9, 'en', 'sme', 'croncommandtextsubmit', 'Add following command to your cron job for submitting status messages according to your schedule.', '2013-03-22 00:34:02'),
(10, 'en', 'sme', 'Post Status Results', 'Post Status Results', '2013-04-23 01:00:05'),
(11, 'en', 'sme', 'Social Media', 'Social Media', '2013-04-24 01:20:59'),
(12, 'en', 'sme', 'Facebook_help_text', '1) Go to <a target=\'_blank\' href=\'https://developers.facebook.com/apps?ref=mb\'>https://developers.facebook.com/apps?ref=mb</a> and  you\'ll find the button \"Create New App\".\r\nYou can then register a new application for social media engine plugin.\r\n\r\n3) Disable sandbox in basic settings and fill rest with appropriate details.\r\n\r\n4) Click on Website with Facebook Login, give link as your seo panel location.\r\n\r\nEg:- http://demo.seopanel.in/\r\n\r\n5) Click on permission in setting, give following values\r\n\r\n-  User & Friend Permissions => email, public_action \r\n-  Extended Permissions => public_stream, read_stream, offline_access, status_update\r\n - Auth Token Parameter => Query String.\r\n\r\nSave the details.\r\n', '2013-04-24 21:05:22'),
(13, 'en', 'sme', 'LinkedIn_help_text', '1) Signup in linkedin.com, if not a member in LinkedIn.\r\n\r\n2) To create application go to link <a target=\'_blank\' href=\'https://www.linkedin.com/secure/developer?newapp=\'>https://www.linkedin.com/secure/developer?newapp=</a> \r\n  \r\n3) Fill with appropriate details and submit the form.\r\n\r\n4) Copy the details under the section \"OAuth Keys\" to media edit page.', '2013-04-24 21:13:09'),
(14, 'en', 'sme', 'Twitter_help_text', '1) Signup for twitter.com, if not a member in Twitter\r\n\r\n2) To create new app go to <a target=\'_blank\' href=\'https://dev.twitter.com/apps/new\'>https://dev.twitter.com/apps/new</a>\r\n  \r\n3) Fill with appropriate details.\r\n\r\n4) Change access level to Read, write, and direct messages.\r\nTo change it, go to settings and then change Application type to Read, write, and direct messages\r\n\r\n5) Copy details from the sections \"OAuth settings\" and \"Your access token\" to media edit page.', '2013-04-24 21:14:05'),
(15, 'en', 'sme', 'Post Status', 'Post Status', '2013-04-25 00:33:26'),
(16, 'en', 'sme', 'New Status', 'New Status', '2013-04-25 00:33:38'),
(17, 'en', 'sme', 'Edit Status', 'Edit Status', '2013-04-25 00:33:53'),
(18, 'en', 'sme', 'Tags', 'Tags', '2013-04-25 00:36:57'),
(19, 'en', 'sme', 'Separate tags with comma', 'Separate tags with comma', '2013-04-25 00:37:05'),
(20, 'en', 'sme', 'Schedule Time', 'Scheduled Time', '2013-04-25 00:43:35'),
(21, 'en', 'sme', 'Your post will be performed with respect to this time', 'Your post will be performed with respect to this time', '2013-04-25 00:44:07'),
(22, 'en', 'sme', 'Current server time', 'Current server time', '2013-04-25 00:45:02'),
(23, 'en', 'sme', 'Add To Cron', 'Add To Cron', '2013-04-25 00:45:49'),
(24, 'en', 'sme', 'Minute', 'Minute', '2013-04-25 00:46:38'),
(25, 'en', 'sme', 'Hour', 'Hour', '2013-04-25 00:46:50'),
(26, 'en', 'sme', 'Edit Media', 'Edit Media', '2013-04-25 00:48:35'),
(27, 'en', 'sme', 'New Media', 'New Media', '2013-04-25 00:48:45'),
(28, 'en', 'sme', 'Media Name', 'Media Name', '2013-04-25 00:50:23'),
(29, 'en', 'sme', 'Media Url', 'Media Url', '2013-04-25 00:50:23'),
(30, 'en', 'sme', 'Connection', 'Connection', '2013-04-25 00:53:19'),
(31, 'en', 'sme', 'Connect', 'Connect', '2013-04-25 00:53:38'),
(32, 'en', 'sme', 'Submit Log', 'Submit Log', '2013-04-25 00:57:46');


ALTER TABLE `sme_api_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resource_id` (`resource_id`);

ALTER TABLE `sme_connections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `connection_name` (`connection_name`),
  ADD KEY `sme_connections_resource_is_delete` (`resource_id`),
  ADD KEY `sme_connections_delete_user_id` (`user_id`);

ALTER TABLE `sme_connection_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sme_connection_links_delete_connection` (`connection_id`);

ALTER TABLE `sme_project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sme_project_user_id_delete` (`user_id`);

ALTER TABLE `sme_resources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `engine_name` (`engine_name`);

ALTER TABLE `sme_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `set_name` (`set_name`);

ALTER TABLE `sme_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sme_status_delete_project_id` (`project_id`);

ALTER TABLE `sme_status_connection_mapping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sme_status_connection_mapping_connection_delete` (`status_id`);

ALTER TABLE `sme_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `resource_id` (`connection_link_id`);

ALTER TABLE `sme_texts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_id` (`lang_code`,`category`,`label`);


ALTER TABLE `sme_api_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `sme_connections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sme_connection_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sme_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sme_resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE `sme_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `sme_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sme_status_connection_mapping`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sme_submissions`
  MODIFY `id` bigint(24) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `sme_texts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;


ALTER TABLE `sme_connections`
  ADD CONSTRAINT `sme_connections_delete_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `sme_connections_resource_is_delete` FOREIGN KEY (`resource_id`) REFERENCES `sme_resources` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_connection_links`
  ADD CONSTRAINT `sme_connection_links_delete_connection` FOREIGN KEY (`connection_id`) REFERENCES `sme_connections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_project`
  ADD CONSTRAINT `sme_project_user_id_delete` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_status`
  ADD CONSTRAINT `sme_status_delete_project_id` FOREIGN KEY (`project_id`) REFERENCES `sme_project` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_status_connection_mapping`
  ADD CONSTRAINT `sme_status_connection_mapping_connection_delete` FOREIGN KEY (`status_id`) REFERENCES `sme_status` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_submissions`
  ADD CONSTRAINT `sme_submissions_delete_status_id` FOREIGN KEY (`status_id`) REFERENCES `sme_status` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
  
ALTER TABLE `sme_api_details` ADD CONSTRAINT `sme_api_details_delete_resource_id` FOREIGN KEY (`resource_id`) REFERENCES `sme_resources`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_status_connection_mapping` ADD UNIQUE (
`status_id` ,
`connection_link_id`
);

ALTER TABLE `sme_connection_links` ADD UNIQUE( `connection_id`, `url`);

ALTER TABLE `sme_connections` ADD `connection_token` VARCHAR( 255 ) NULL ;

ALTER TABLE `sme_submissions` ADD `submit_ref_id` VARCHAR(255) NULL;

ALTER TABLE `sme_connections` ADD `auth_token` VARCHAR(255) NULL AFTER `connection_token`, ADD `auth_token_secret` VARCHAR(255) NULL AFTER `auth_token`;

ALTER TABLE `sme_connections` ADD `account_name` VARCHAR(120) NULL;

ALTER TABLE `sme_connections` ADD `token_expire_at` DATETIME NULL;

ALTER TABLE `sme_connections` ADD `connection_ref` VARCHAR(120) NULL;

ALTER TABLE `sme_status` ADD `share_image` VARCHAR(255) NULL;

INSERT INTO `sme_texts` (`category`, `label`, `content`) VALUES
('sme', 'Post', 'Post'),
('sme', 'Submission Pages', 'Submission Pages'),
('sme', 'Edit Connection', 'Edit Connection'),
('sme', 'New Connection', 'New Connection'),
('sme', 'Connection Manager', 'Connection Manager'),
('sme', 'Post Manager', 'Post Manager');

--
-- version 3.0.0 changes
--

ALTER TABLE `sme_connections` CHANGE `connection_log` `connection_log` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `sme_connections` CHANGE `connection_date` `connection_date` DATETIME NULL DEFAULT NULL ;


ALTER TABLE `sme_status` CHANGE `share_description` `share_description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
CHANGE `share_tags` `share_tags` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
CHANGE `creation_date` `creation_date` DATETIME NULL;


ALTER TABLE `sme_connections` CHANGE `connection_token` `connection_token` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, 
CHANGE `auth_token` `auth_token` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, 
CHANGE `auth_token_secret` `auth_token_secret` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, 
CHANGE `connection_ref` `connection_ref` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

