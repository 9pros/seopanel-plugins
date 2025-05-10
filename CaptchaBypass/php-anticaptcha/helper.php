<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

include_once(PLUGIN_PATH . "/php-anticaptcha/vendor/autoload.php");

use AntiCaptcha\AntiCaptcha;
	
function solveCaptcha($apiKey, $imgPath) {
	$captchaTxt = "";
	
	if (!empty($apiKey)) {
	
		// Get file content
		$image = file_get_contents($imgPath);		
		$antiCaptchaClient = new AntiCaptcha(AntiCaptcha::SERVICE_ANTICAPTCHA,
			[
				'api_key' => $apiKey,
				'debug' => false,
			]
		);
		
		$captchaTxt = $antiCaptchaClient->recognize($image, null, ['phrase' => 0, 'numeric' => 0]);
	}
	
	return $captchaTxt;
}