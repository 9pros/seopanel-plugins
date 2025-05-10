--
-- version 2.0.0 changes
--

ALTER TABLE `brc_campaigns` CHANGE `last_generated` `last_generated` DATETIME NULL DEFAULT NULL COMMENT 'used cron job report generation';

ALTER TABLE `brc_campaigns` drop column `last_updated`;
ALTER TABLE `brc_campaigns` add column `last_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'used for front end report generation';

ALTER TABLE `brc_searchresults` add column `time_back` DATE;
UPDATE `brc_searchresults` SET `time_back` = FROM_UNIXTIME(`time`, '%Y-%m-%d');
ALTER TABLE `brc_searchresults` drop column `time`;
ALTER TABLE `brc_searchresults` add column `time` DATE NOT NULL COMMENT 'used for front end report generation';
update `brc_searchresults` set `time`=`time_back`;
ALTER TABLE `brc_searchresults` drop column `time_back`;

UPDATE `brc_settings` SET `set_val` = '1' WHERE set_name='BRC_NUMBER_OF_KEYWORDS_CRON';

INSERT INTO `brc_settings` (`set_label`, `set_name`, `set_val`, `set_type`) VALUES 
('Enable reports generation from user interface', 'BRC_ENABLE_UI_REPORT_GENERATION', '1', 'bool');


INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'BRC_ENABLE_UI_REPORT_GENERATION', 'Enable reports generation from user interface');
INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'brc_search_engine_count', 'Search engine count');
INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'Edit Campaign', 'Edit Campaign');
INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'brc_report_interval_limit', 'Lowest report generation interval');
INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'total_count_greater_account_limit', 'Total count is greater than account limit - [limit]');
INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'Selected interval is less than account limit', 'Selected interval is less than account limit');
INSERT INTO `brc_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'bulkrankchecker', 'Link Found', 'Link Found');
