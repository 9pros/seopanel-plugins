ALTER TABLE `sme_resources` ENGINE = InnoDB;

ALTER TABLE `sme_api_details` ENGINE = InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE `sme_settings` ENGINE = InnoDB;

ALTER TABLE `sme_status` DROP `share_resources` ;

ALTER TABLE `sme_status` DROP `share_url` ;

Drop table `sme_connections`;

ALTER TABLE `sme_project` CHANGE `status` `status` TINYINT( 1 ) NOT NULL DEFAULT '1';

ALTER TABLE `sme_resources` DROP `user_connect` ;

ALTER TABLE `sme_status` CHANGE `start_date` `creation_date` DATETIME NOT NULL ;

ALTER TABLE `sme_status` CHANGE `time` `schedule_time` DATETIME NULL;

ALTER TABLE `sme_submissions` ENGINE = InnoDB;

ALTER TABLE `sme_submissions` CHANGE `submit_time` `submit_time` DATETIME NOT NULL ;

ALTER TABLE `sme_submissions` CHANGE `resource_id` `connection_link_id` INT( 11 ) UNSIGNED NOT NULL ;

ALTER TABLE `sme_texts` ENGINE = InnoDB;


ALTER TABLE `sme_submissions` ADD `impressions` BIGINT NOT NULL DEFAULT '0',
ADD `likes` BIGINT NOT NULL DEFAULT '0',
ADD `shares` BIGINT NOT NULL DEFAULT '0',
ADD `comments` BIGINT NOT NULL DEFAULT '0',
ADD `clicks` BIGINT NOT NULL DEFAULT '0',
ADD `engagements` BIGINT NOT NULL DEFAULT '0';

  
CREATE TABLE IF NOT EXISTS `sme_connections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `connection_name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `resource_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `connection_status` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `connection_log` text COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `connection_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `connection_name` (`connection_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ; 
  
   
CREATE TABLE IF NOT EXISTS `sme_connection_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `connection_id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `sme_status_connection_mapping` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL,
  `connection_link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;  

ALTER TABLE `sme_project` ADD CONSTRAINT `sme_project_user_id_delete` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_status` ADD CONSTRAINT `sme_status_delete_project_id` FOREIGN KEY (`project_id`) REFERENCES `sme_project`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_status_connection_mapping` ADD CONSTRAINT `sme_status_connection_mapping_connection_delete` FOREIGN KEY (`status_id`) REFERENCES `sme_status`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_connections` ADD CONSTRAINT `sme_connections_resource_is_delete` FOREIGN KEY (`resource_id`) REFERENCES `sme_resources`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_connections` ADD CONSTRAINT `sme_connections_delete_user_id` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_connection_links` ADD CONSTRAINT `sme_connection_links_delete_connection` FOREIGN KEY (`connection_id`) REFERENCES `sme_connections`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_submissions` ADD CONSTRAINT `sme_submissions_delete_status_id` FOREIGN KEY (`status_id`) REFERENCES `sme_status`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_api_details` ADD CONSTRAINT `sme_api_details_delete_resource_id` FOREIGN KEY (`resource_id`) REFERENCES `sme_resources`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `sme_status_connection_mapping` ADD UNIQUE (
`status_id` ,
`connection_link_id`
);

delete FROM `sme_api_details` WHERE `api_key_name` LIKE 'facebook_post_link';

ALTER TABLE `sme_connection_links` ADD UNIQUE( `connection_id`, `url`);

ALTER TABLE `sme_connections` ADD `connection_token` VARCHAR( 255 ) NULL ;

ALTER TABLE `sme_submissions` ADD `submit_ref_id` VARCHAR(255) NULL;

ALTER TABLE `sme_connections` ADD `auth_token` VARCHAR(255) NULL AFTER `connection_token`, ADD `auth_token_secret` VARCHAR(255) NULL AFTER `auth_token`;

ALTER TABLE `sme_connections` ADD `account_name` VARCHAR(120) NULL;

update sme_api_details set api_key_label='Client ID', api_key_name='linkedin_client_id' where api_key_name='linkedin_api_key';
update sme_api_details set api_key_label='Client Secret', api_key_name='linkedin_client_secret' where api_key_name='linkedin_secret_key';

ALTER TABLE `sme_connections` ADD `token_expire_at` DATETIME NULL;

INSERT INTO `sme_resources` (`id`, `engine_name`, `engine_website`, `status`) VALUES
(4, 'Pinterest', 'https://www.pinterest.com/', 1);

INSERT INTO `sme_api_details` (`id`, `resource_id`, `api_key_label`, `api_key_name`, `api_key_value`) VALUES
(9, 4, 'Application Id', 'app_id', ''),
(10, 4, 'Secret Key', 'secret_key', '');

ALTER TABLE `sme_connections` ADD `connection_ref` VARCHAR(120) NULL;

ALTER TABLE `sme_status` ADD `share_image` VARCHAR(255) NULL;


INSERT INTO `sme_texts` (`category`, `label`, `content`) VALUES
('sme', 'Post', 'Post'),
('sme', 'Submission Pages', 'Submission Pages'),
('sme', 'Edit Connection', 'Edit Connection'),
('sme', 'New Connection', 'New Connection'),
('sme', 'Connection Manager', 'Connection Manager'),
('sme', 'Post Manager', 'Post Manager');

UPDATE `sme_texts` SET `content` = 'Add following command to your cron job for submitting status messages according to your schedule.' WHERE `sme_texts`.`id` = 9;


