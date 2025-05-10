<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

include_once("../../includes/sp-load.php");
include_once(SP_CTRLPATH."/seoplugins.ctrl.php");
$controller = New SeoPluginsController();
$pluginInfo = $controller->__getSeoPluginInfo('newsletter', 'name');
$info['pid'] = $pluginInfo['id'];

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $info['action'] = "unsubscribemaillist";     
    $controller->manageSeoPlugins($info, 'get', true);
}
?>