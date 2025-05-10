<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese
 */

include_once("../../includes/sp-load.php");
include_once(SP_CTRLPATH."/seoplugins.ctrl.php");

// create plugin object and verify the callback action
$seopluginCtrler = New SeoPluginsController();
if ($seopluginCtrler->isPluginActive("socialmediaengine")) {
    $pluginCtrler = $seopluginCtrler->createPluginObject("socialmediaengine");
    $pluginCtrler->callbackSM($_REQUEST);
} else {
    showErrorMsg("Not authorized to access this page!");
}
?>