<?php
$basePluginCtrler = new SeoPluginsController();
$basePluginCtrler->checkDBConn();

$pluginId = Session::readSession('plugin_id');
$pluginInfo = $basePluginCtrler->__getSeoPluginInfo($pluginId);
$pluginDirName = $pluginInfo['name'];

include_once(SP_PLUGINPATH."/$pluginDirName/$pluginDirName.ctrl.php");
$pluginControler = New $pluginDirName();
$data['plgdirname'] = $pluginDirName;
$pluginControler->initPlugin($data);

$basePluginCtrler->setPluginTextsForRender($pluginControler->textCategory, $pluginControler->textTable);
$pluginText = $basePluginCtrler->pluginText;
$spTextPanel = $basePluginCtrler->getLanguageTexts('panel', $_SESSION['lang_code']);

?>
<script>loadJsCssFile("<?=SP_WEBPATH?>/plugins/<?=$pluginDirName?>/js/tiny_mce/tiny_mce.js", "js")</script>
<ul id='subui'>
	<?php if(isAdmin() || NL_ALLOW_USER_CAMP_MGR) {?>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu(); ?>"><?=$pluginText['Campaign Manager']?></a></li>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=newslettermanager'); ?>"><?=$pluginText['Newsletter Manager']?></a></li>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=managerEL'); ?>"><?=$pluginText['Email List Manager']?></a></li>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=managerEmail'); ?>"><?=$pluginText['Email Manager']?></a></li>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=importemail'); ?>"><?=$pluginText['Import Emails']?></a></li>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=gensubscribecode'); ?>"><?=$pluginText['Generate Subscribe Code']?></a></li>
	<?php }?>    
    <li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=reportmanager'); ?>"><?=$pluginText['Newsletter Reports']?></a></li>
	<?php if(isAdmin()) {?>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=showcroncommand'); ?>"><?=$spTextPanel['Cron Command']?></a></li>		
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=settings'); ?>"><?=$pluginText['Plugin Settings']?></a></li>
    <?php }?>	
	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=aboutus'); ?>"><?=$spTextPanel['About Us']?></a></li>
</ul>