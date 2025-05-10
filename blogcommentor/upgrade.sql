--
-- 2.4.0 changes
--

ALTER TABLE `bc_projects` ADD `project_name` VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `id`;

ALTER TABLE `bc_projects` CHANGE `keyword` `keyword` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `link_title` `link_title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `email` `email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment1` `comment1` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment2` `comment2` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment3` `comment3` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment4` `comment4` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment5` `comment5` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment6` `comment6` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment7` `comment7` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment8` `comment8` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment9` `comment9` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `comment10` `comment10` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;


