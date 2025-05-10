--
-- Table structure for table `cust_blogs`
--

CREATE TABLE `cust_blogs` (
  `id` int(11) NOT NULL,
  `blog_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `blog_content` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `lang_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `tags` text COLLATE utf8_unicode_ci NOT NULL,
  `link_page` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',  
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `cust_site_details`
--

CREATE TABLE `cust_site_details` (
  `id` int(11) NOT NULL,
  `col_name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `col_value` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cust_site_details`
--

INSERT INTO `cust_site_details` (`id`, `col_name`, `col_value`, `status`) VALUES
(1, 'site_logo', '', 1),
(2, 'site_favicon','',1),
(3, 'site_name', '', 1),
(4, 'site_title', '', 1),
(5, 'site_description', '', 1),
(6, 'site_keywords', '', 1),
(7, 'footer_copyright', '', 1),
(8, 'fb_page_url', '', 1),
(9, 'twitter_page_url', '', 1),
(10, 'contact_url', '', 1),
(11, 'help_url', '', 1),
(12, 'forum_url', '', 1),
(13, 'disable_news', '1', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cust_blogs`
--
ALTER TABLE `cust_blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cust_site_details`
--
ALTER TABLE `cust_site_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `col_name` (`col_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cust_blogs`
--
ALTER TABLE `cust_blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cust_site_details`
--
ALTER TABLE `cust_site_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;


--
-- version 2.0.0 changes
--

INSERT INTO `cust_site_details` (`id`, `col_name`, `col_value`, `status`) VALUES (NULL, 'custom_menu', '0', '1');

--
-- Table structure for table `cust_menu`
--

CREATE TABLE `cust_menu` (
  `id` int(11) NOT NULL,
  `label` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `identifier` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `bg_color` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `font_color` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cust_menu`
--

INSERT INTO `cust_menu` (`id`, `label`, `identifier`, `bg_color`, `font_color`) VALUES
(1, 'Guest Main Menu', 'guest', NULL, NULL),
(2, 'User Main Menu', 'user', NULL, NULL),
(3, 'Admin Main Menu', 'admin', NULL, NULL),
(4, 'Top Menu', 'top', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cust_menu_items`
--

CREATE TABLE `cust_menu_items` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `float_type` enum('left','right') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'left',
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `window_target` enum('new_tab','same_window') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'same_window',
  `priority` int(11) NOT NULL DEFAULT '100',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cust_menu_item_texts`
--

CREATE TABLE `cust_menu_item_texts` (
  `id` bigint(20) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `lang_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- version 3.0.0 changes
--

--
-- Table structure for table `cust_styles`
--

CREATE TABLE IF NOT EXISTS `cust_styles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `style_content` text COLLATE utf8_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '100',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `theme_id` (`theme_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `cust_menu`
--
ALTER TABLE `cust_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cust_menu_items`
--
ALTER TABLE `cust_menu_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menu_id` (`menu_id`,`name`);

--
-- Indexes for table `cust_menu_item_texts`
--
ALTER TABLE `cust_menu_item_texts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_item_id_delete` (`menu_item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cust_menu`
--
ALTER TABLE `cust_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cust_menu_items`
--
ALTER TABLE `cust_menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cust_menu_item_texts`
--
ALTER TABLE `cust_menu_item_texts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `cust_menu_items`
--
ALTER TABLE `cust_menu_items`
  ADD CONSTRAINT `menu_id_delete` FOREIGN KEY (`menu_id`) REFERENCES `cust_menu` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cust_menu_item_texts`
--
ALTER TABLE `cust_menu_item_texts`
  ADD CONSTRAINT `menu_item_id_delete` FOREIGN KEY (`menu_item_id`) REFERENCES `cust_menu_items` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;


ALTER TABLE `cust_styles` ADD `type` ENUM('css','js') NOT NULL DEFAULT 'css' AFTER `priority`;