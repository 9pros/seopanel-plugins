<?php
$pluginCtrler = new SeoPluginsController();
$pluginText = $pluginCtrler->getPluginLanguageTexts('diplugin', $_SESSION['lang_code'], 'di_texts');
$spTextPanel = $pluginCtrler->getLanguageTexts('panel', $_SESSION['lang_code']);
?>
<ul id='subui'>
	<?php if(isAdmin() || DI_ALLOW_USER_DIR_MGR) {?>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu(); ?>"><?=$spTextPanel['Directory Manager']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=import'); ?>"><?=$pluginText['Import Directories']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=showcheckdir'); ?>"><?=$spText['button']['Check Status']?></a></li>
	<?php }?>
	<?php if(isAdmin()) {?>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=showcroncommand'); ?>"><?=$spTextPanel['Cron Command']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=settings'); ?>"><?=$pluginText['Plugin Settings']?></a></li>
	<?php }?>
	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=aboutus'); ?>"><?=$spTextPanel['About Us']?></a></li>
</ul>