<?php
$basePluginCtrler = new SeoPluginsController();
$basePluginCtrler->setPluginTextsForRender('blogcommentor', 'bc_texts');
$pluginText = $basePluginCtrler->pluginText;
$spTextPanel = $basePluginCtrler->getLanguageTexts('panel', $_SESSION['lang_code']);
$spTextSeoTool = $basePluginCtrler->getLanguageTexts('seotools', $_SESSION['lang_code']);

$pluginId = Session::readSession('plugin_id');
$pluginInfo = $basePluginCtrler->__getSeoPluginInfo($pluginId);
$pluginDirName = $pluginInfo['name'];

include_once(SP_PLUGINPATH."/$pluginDirName/$pluginDirName.ctrl.php");
$pluginControler = New $pluginDirName();
$data['plgdirname'] = $pluginDirName;
$pluginControler->initPlugin($data);

?>
<ul id='subui'>
	<?php if(isAdmin() || BC_ALLOW_USER_PRJ_MGR) {?>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu(); ?>"><?php echo $pluginText['Projects Manager']?></a></li>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=viewreports'); ?>"><?php echo $pluginText['View Reports']?></a></li>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=importLinks'); ?>"><?php echo $spTextSeoTool['Import Project Links']?></a></li>
    <?php }?>
	<?php if(isAdmin()) {?>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=showcroncommand'); ?>"><?php echo $spTextPanel['Cron Command']?></a></li>		
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=settings'); ?>"><?php echo $pluginText['Plugin Settings']?></a></li>
	<?php }?>
</ul>

<script>
var wantblogsubmit = '<?php echo $pluginText['wantblogsubmit']?>';
</script>