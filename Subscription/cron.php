<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */
$dirpath = realpath ( dirname ( __FILE__ ) );
$spLoadFile = "$dirpath/../../includes/sp-load.php";
$spLoadFileExist = true;
if (! file_exists ( $spLoadFile )) {
	$dirpath = str_ireplace ( '/plugins/Subscription/cron.php', '', $_SERVER ['SCRIPT_FILENAME'] );
	$spLoadFile = $dirpath . "/includes/sp-load.php";
	$spLoadFileExist = ! file_exists ( $spLoadFile ) ? false : true;
}

// check wheteher load file exists
if ($spLoadFileExist) {
	include_once($spLoadFile);
	
	// if not accessed through web server
	if (empty($_SERVER ['REQUEST_METHOD'] )) {
		
		$controller = new SeoPluginsController ();
		$pluginInfo = $controller->__getSeoPluginInfo('Subscription', 'name' );
		$info ['pid'] = $pluginInfo ['id'];
		$info ['action'] = "cronJob";
		$_GET ['doc_type'] = 'export';
		$controller->manageSeoPlugins( $info, 'get', true);
		
	} else {
		showErrorMsg ( "<p style='color:red'>You don't have permission to access this page!</p>" );
	}
	
} else {
	echo "Seo Panel Bootstrap loader file not accessible!";
}
?>