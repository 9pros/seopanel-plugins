--
-- version 1.1.0 changes
--

ALTER TABLE `as_submit_details` ADD `ref_id` VARCHAR(200) NULL;

ALTER TABLE `as_websites` CHANGE `domain` `domain` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
CHANGE `website_url` `website_url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
CHANGE `username` `username` VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
CHANGE `password` `password` VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL; 

