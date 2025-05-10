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
