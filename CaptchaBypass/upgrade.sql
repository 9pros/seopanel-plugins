--
-- version 1.1.0 changes
--

ALTER TABLE `cb_services` CHANGE `identifier` `identifier` ENUM( 'deathbycaptcha', 'anti-captcha', '2captcha', 'rucaptcha', 'captchas.io' ) 
CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;

