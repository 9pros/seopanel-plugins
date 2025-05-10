--
-- version 3.0.0 changes
--

ALTER TABLE `ld_backlinks` CHANGE `domain_authority` `domain_authority` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `ld_backlinks` CHANGE `page_authority` `page_authority` FLOAT NOT NULL DEFAULT '0';


INSERT INTO `ld_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'ldplugin', 'ld_backlink_count', 'Backlink Count');

INSERT INTO `ld_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'ldplugin', 'Backlink count is greater than maximum links allowed', 'Backlink count is greater than maximum links allowed');

INSERT INTO `ld_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'ldplugin', 'Backlink count is greater than plugin settings', 'Backlink count is greater than plugin settings');

INSERT INTO `ld_texts` (`lang_code`, `category`, `label`, `content`) VALUES
('en', 'ldplugin', 'total_link_max_link_error', "Total backlinks of report: [TOTAL_LINKS] is greater than maximum allowed links: [MAX_LINKS]");