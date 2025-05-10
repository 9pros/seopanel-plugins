<?php
$pluginCtrler = new SeoPluginsController();
$pluginText = $pluginCtrler->getPluginLanguageTexts('CaptchaBypass', $_SESSION['lang_code'], 'cb_texts');
$spTextPanel = $pluginCtrler->getLanguageTexts('panel', $_SESSION['lang_code']);
?>
<ul id='subui'>
	<?php if(isAdmin()) {?>
    	<li>
    		<a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=serviceManager'); ?>">
    			<?php echo $pluginText['Captcha Bypass Manager'] ?>
    		</a>
    	</li>
    	<li>
    		<a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=settings'); ?>"><?php echo $spTextPanel['Settings']?></a>
    	</li>
    <?php }?>
</ul>