<?php
$pluginCtrler = new SeoPluginsController();
$pluginText = $pluginCtrler->getPluginLanguageTexts('bulkrankchecker', $_SESSION['lang_code'], 'brc_texts');
$spTextPanel = $pluginCtrler->getLanguageTexts('panel', $_SESSION['lang_code']);
$spTextTools = $pluginCtrler->getLanguageTexts('seotools', $_SESSION['lang_code']);
?>
<ul id='subui'>
	<li><a href="javascript:void(0);" onclick="<?php echo  pluginMenu('action=showOverviewDashboard'); ?>"><?php echo $spText['common']['Dashboard']?></a></li>
	<!-- <li><a href="javascript:void(0);" onclick="<?php echo  pluginMenu('action=report'); ?>"><?php echo $spTextPanel['Archived Reports']?></a></li> -->
    <li><a href="javascript:void(0);" onclick="<?php echo  pluginMenu('action=reportDetail'); ?>"><?php echo $spTextTools['Detailed Position Reports']?></a></li>
    <li><a href="javascript:void(0);" onclick="<?php echo  pluginMenu('action=reportGraph'); ?>"><?php echo $spTextTools['Graphical Position Reports']?></a></li>
    <?php if(isAdmin() || BRC_ALLOW_USER_CAMP_MGR) {?>
    	<li><a href="javascript:void(0);" onclick="<?php echo  pluginMenu('action=showCampaignManager'); ?>"><?php echo  $pluginText['Campaign Manager']?></a></li>
	<?php }?>
	<?php if(isAdmin()) {?>	
		<li><a href="javascript:void(0);" onclick="<?php echo  pluginMenu('action=showcroncommand'); ?>"><?php echo  $spTextPanel['Cron Command']?></a></li>	
		<li><a href="javascript:void(0);" onclick="<?php echo  pluginMenu('action=settings'); ?>"><?php echo  $pluginText['Plugin Settings']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo  pluginMenu('action=userTypeSettings'); ?>"><?php echo  $spTextPanel['User Type Settings']?></a></li>
    <?php }?>	
	<li><a href="javascript:void(0);" onclick="<?php echo  pluginMenu('action=aboutus'); ?>"><?php echo  $spTextPanel['About Us']?></a></li>
</ul>