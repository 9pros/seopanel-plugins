<?php
$basePluginCtrler = new SeoPluginsController();
$basePluginCtrler->checkDBConn();
$basePluginCtrler->setPluginTextsForRender('socialbookmarker', 'sb_texts');
$pluginText = $basePluginCtrler->pluginText;
$spTextPanel = $basePluginCtrler->getLanguageTexts('panel', $_SESSION['lang_code']);

$pluginId = Session::readSession('plugin_id');
$pluginInfo = $basePluginCtrler->__getSeoPluginInfo($pluginId);
$pluginDirName = $pluginInfo['name'];

include_once(SP_PLUGINPATH."/$pluginDirName/$pluginDirName.ctrl.php");
$pluginControler = New $pluginDirName();
$data['plgdirname'] = $pluginDirName;
$pluginControler->initPlugin($data);

?>
<ul id='subui'>
	<?php if(isAdmin() || SB_ALLOW_USER_PRJ_MGR) {?>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu(); ?>"><?=$pluginText['Projects Manager']?></a></li>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=runproject'); ?>"><?=$pluginText['Run Project']?></a></li>
    <?php }?>
	<?php if(isAdmin()) {?>		
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=settings'); ?>"><?=$pluginText['Plugin Settings']?></a></li>
    <?php }?>
	<?php if(isAdmin() || SB_ALLOW_USER_SB_MGR) {?>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=showsbmanager'); ?>"><?=$pluginText['Social Bookmarker Manager']?></a></li>
	<?php }?>	
	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=aboutus'); ?>"><?=$spTextPanel['About Us']?></a></li>
</ul>