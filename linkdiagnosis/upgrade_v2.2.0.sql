--
-- version 2.2.0 changes
--

ALTER TABLE `ld_backlinks` CHANGE `google_pagerank` `google_pagerank` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `ld_backlinks`  ADD `domain_authority` FLOAT NOT NULL;
ALTER TABLE `ld_backlinks`  ADD `page_authority` FLOAT NOT NULL;

INSERT INTO `ld_seconfig` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `url_index`, `title_index`, `description_index`, `encoding`, `next_link_regex`, `status`) VALUES
('moz', 'http://moz.com', '', 50, 0, 50, 100, '', 0, 2, 3, NULL, '', 1);

UPDATE `ld_settings` SET `set_val` = '8' WHERE `ld_settings`.`id` = 1;

ALTER TABLE `ld_backlinks` ADD `link_score` INT(6) NOT NULL DEFAULT '0' ;


INSERT INTO ld_texts(`lang_code`, `category`, `label`, `content`) VALUES 
('en', 'ldplugin', 'The number of outbound links', 'The number of outbound links');

INSERT INTO ld_texts(`lang_code`, `category`, `label`, `content`) VALUES 
('en', 'ldplugin', 'The page is having excellent domain authority value', 'The page is having excellent domain authority value');

INSERT INTO ld_texts(`lang_code`, `category`, `label`, `content`) VALUES 
('en', 'ldplugin', 'The page is having very good domain authority value', 'The page is having very good domain authority value');

INSERT INTO ld_texts(`lang_code`, `category`, `label`, `content`) VALUES 
('en', 'ldplugin', 'The page is having good domain authority value', 'The page is having good domain authority value');

INSERT INTO ld_texts(`lang_code`, `category`, `label`, `content`) VALUES 
('en', 'ldplugin', 'The page is having poor domain authority value', 'The page is having poor domain authority value');

INSERT INTO ld_texts(`lang_code`, `category`, `label`, `content`) VALUES 
('en', 'ldplugin', 'Poor', 'Poor');
INSERT INTO ld_texts(`lang_code`, `category`, `label`, `content`) VALUES 
('en', 'ldplugin', 'Great', 'Great');





