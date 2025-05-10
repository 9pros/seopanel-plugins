<?php
$pluginCtrler = new SeoPluginsController();
$pluginText = $pluginCtrler->getPluginLanguageTexts('sme', $_SESSION['lang_code'], 'sme_texts');
$spTextPanel = $pluginCtrler->getLanguageTexts('panel', $_SESSION['lang_code']);
$spTextDiary = $pluginCtrler->getLanguageTexts('seodiary', $_SESSION['lang_code']);
?>
<ul id='subui'>
    <?php if(isAdmin() || SME_ALLOW_USER_PROJECT_MGR) {?>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu(); ?>"><?php echo $spTextDiary['Projects Manager']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=manageStatus'); ?>"><?php echo $pluginText['Post Manager']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=connectionManager'); ?>"><?php echo $pluginText['Connection Manager']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=viewreport'); ?>"><?php echo $pluginText['Submission Reports']?></a></li>
    <?php }?>
	<?php if(isAdmin()) {?>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=listSocialMedia'); ?>"><?php echo $pluginText['Social Media Manager']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=showcroncommand'); ?>"><?php echo $spTextPanel['Cron Command']?></a></li>		
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=settings'); ?>"><?php echo $pluginText['Plugin Settings']?></a></li>
    <?php }?>
</ul>