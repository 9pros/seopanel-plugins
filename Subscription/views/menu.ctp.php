<?php 
$basePluginCtrler = new SeoPluginsController();
$basePluginCtrler->setPluginTextsForRender('subscription', 'texts');
$pluginText = $basePluginCtrler->pluginText;
$spTextPanel = $basePluginCtrler->getLanguageTexts('panel', $_SESSION['lang_code']);
?>
<ul id='subui'>
	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=orderManager'); ?>"><?php echo $pluginText['Orders']?></a></li>
	<?php if(isAdmin()){?>
		<li><a href="javascript:void(0);" onclick="scriptDoLoad('user-types-manager.php', 'content')"><?php echo $spTextPanel['User Type Manager']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=paymentGatewayManager'); ?>"><?php echo $pluginText['Payment Gateway Manager']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=emailTemplateManager'); ?>"><?php echo $pluginText['Email Template Manager']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo  pluginMenu('action=showcroncommand'); ?>"><?php echo  $spTextPanel['Cron Command']?></a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=settings'); ?>"><?php echo $spTextPanel['Settings']?></a></li>
	<?php }?>	
	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=aboutus'); ?>"><?php echo $spTextPanel['About Us']?></a></li>
</ul>