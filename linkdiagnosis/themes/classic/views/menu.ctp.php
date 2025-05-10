<?php
$pluginCtrler = new SeoPluginsController();
$pluginText = $pluginCtrler->getPluginLanguageTexts('ldplugin', $_SESSION['lang_code'], 'ld_texts');
$spTextPanel = $pluginCtrler->getLanguageTexts('panel', $_SESSION['lang_code']);
?>
<ul id='subui'>
	<?php if(isAdmin() || LD_ALLOW_USER_REPORTS) {?>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu(); ?>"><?php echo $pluginText['Projects Manager']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=reports'); ?>"><?php echo $spTextPanel['Reports Manager']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=importBacklinks'); ?>"><?php echo $pluginText['Import Backlinks']?></a></li>
	<?php }?>
	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=viewreport'); ?>"><?php echo $pluginText['View Reports']?></a></li>
	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=viewreport&report_type=rp_summary'); ?>"><?php echo $pluginText['Report Summary']?></a></li>
	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=viewreport&report_type=rp_anchor'); ?>"><?php echo $pluginText['Popular Anchors']?></a></li>
	<?php if(isAdmin()) {?>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=settings'); ?>"><?php echo $pluginText['Plugin Settings']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=userTypeSettings'); ?>"><?php echo $spTextPanel['User Type Settings']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=searchEngineManager'); ?>"><?php echo $spTextPanel['Search Engine Manager']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=showcroncommand'); ?>"><?php echo $spTextPanel['Cron Command']?></a></li>
	<?php }?>
	<?php if(!SP_HOSTED_VERSION) {?>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=aboutus'); ?>"><?php echo $spTextPanel['About Us']?></a></li>
	<?php }?>
</ul>