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

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $info['action'] = "dosubscribeform";     
    $controller->manageSeoPlugins($info, 'post');
}else{
    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
        <link rel="stylesheet" type="text/css" href="<?=SP_CSSPATH?>/screen.css" media="all" />
        <script language="Javascript" src="<?=SP_JSPATH?>/prototype.js"></script>
    	<script language="Javascript" src="<?=SP_JSPATH?>/common.js"></script>
    </head>
    <body style="background-color: <?=urldecode($_GET['bgcolor'])?>">
        <?php    
        $info['action'] = "showsubscribeform";     
        $controller->manageSeoPlugins($info, 'get', true);
        ?>
    </body>
    </html>
    <?php
}
?>