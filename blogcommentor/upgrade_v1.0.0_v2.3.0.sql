--
-- 2.0.0 changes
--
ALTER TABLE `bc_search_results` CHANGE `status` `status` TINYINT( 1 ) NOT NULL DEFAULT '0';

ALTER TABLE `bc_search_results` ADD `checked` BOOLEAN NOT NULL DEFAULT '0' AFTER `status`;

INSERT INTO `bc_texts` (`lang_code`, `category`, `label`, `content`) VALUES ('en', 'blogcommentor', 'Inactive Links', 'Inactive Links');
INSERT INTO `bc_texts` (`lang_code`, `category`, `label`, `content`) VALUES ('en', 'blogcommentor', 'Active Links', 'Active Links');
INSERT INTO `bc_texts` (`lang_code`, `category`, `label`, `content`) VALUES ('en', 'blogcommentor', 'Checked Links', 'Checked Links');
INSERT INTO `bc_texts` (`lang_code`, `category`, `label`, `content`) VALUES ('en', 'blogcommentor', 'Copy Project', 'Copy Project');

--
-- 2.2.0 changes
--

UPDATE bc_blog_meta SET `extra_val` = 'comment_parent=0' WHERE domain = 'search.wordpress.com';

ALTER TABLE `bc_blog_meta` ADD `extra_cols` VARCHAR(255) NOT NULL AFTER `extra_val`;

UPDATE `bc_blog_meta` SET `extra_cols` = 
'comment_post_url,highlander_comment_nonce,_wp_http_referer,hc_post_as,ak_js,akismet_comment_nonce,genseq,wp_access_token,wp_avatar,wp_user_id'
WHERE domain = 'search.wordpress.com';

INSERT INTO `bc_texts` (`lang_code`, `category`, `label`, `content`) 
VALUES ('en', 'blogcommentor', 'Check Submission', 'Check Submission');

INSERT INTO `bc_texts` (`lang_code`, `category`, `label`, `content`) 
VALUES ('en', 'blogcommentor', 'link_title_info', 'Please add link titles by comma separated.<br> Eg: seo tools,seo script,seo tracker');

ALTER TABLE `bc_projects` ADD `link_title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `website_id` ;

