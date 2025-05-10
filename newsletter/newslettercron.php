<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */
$dirpath = str_ireplace('/plugins/newsletter/newslettercron.php', '', $_SERVER['SCRIPT_FILENAME']); 
include_once($dirpath."/includes/sp-load.php");
include_once(SP_CTRLPATH."/seoplugins.ctrl.php");
$controller = New SeoPluginsController();
$pluginInfo = $controller->__getSeoPluginInfo('newsletter', 'name');
$info['pid'] = $pluginInfo['id'];

if (empty($_SERVER['REQUEST_METHOD'])) {
    $info['action'] = "cronjob";
    $_GET['doc_type'] = 'export';     
    $controller->manageSeoPlugins($info, 'get', true);
} else {
	showErrorMsg("<p style='color:red'>You don't have permission to access this page!</p>");	
}
?>