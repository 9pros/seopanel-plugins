--
-- version 2.2.0 changes
--

UPDATE `brc_settings` SET `set_val` = '1' WHERE set_name='BRC_NUMBER_OF_KEYWORDS_CRON';
UPDATE `brc_settings` SET `set_val` = '0' WHERE set_name='BRC_ENABLE_UI_REPORT_GENERATION';
