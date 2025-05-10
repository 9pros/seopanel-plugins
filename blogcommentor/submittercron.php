<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

$dirpath = str_ireplace('/plugins/blogcommentor/submittercron.php', '', $_SERVER['SCRIPT_FILENAME']); 
include_once($dirpath."/includes/sp-load.php");
if(empty($_SERVER['REQUEST_METHOD'])){

    include_once(SP_CTRLPATH."/seoplugins.ctrl.php");
    $controller = New SeoPluginsController();

    $pluginInfo = $controller->__getSeoPluginInfo('blogcommentor', 'name');
    $info['pid'] = $pluginInfo['id'];
    $info['action'] = "cronsubmitcomment";
    $_GET['doc_type'] = 'export';     
    $controller->manageSeoPlugins($info, 'get', true);
}else{
	showErrorMsg("<p style='color:red'>You don't have permission to access this page!</p>");	
}
?>