<?php
$pluginCtrler = new SeoPluginsController();
$pluginText = $pluginCtrler->getPluginLanguageTexts('as', $_SESSION['lang_code'], 'as_texts');
$spTextPanel = $pluginCtrler->getLanguageTexts('panel', $_SESSION['lang_code']);
?>
<ul id='subui'> 
    <?php if(isAdmin() || AS_ALLOW_USER_PROJECT_MGR) {?> 
        <li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=index'); ?>"><?php echo $pluginText['Project Manager']?></a></li>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=article'); ?>"><?php echo $pluginText['Article Manager']?></a></li>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=spinner'); ?>"><?php echo $pluginText['Article Spinner']?></a></li>
        <li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=showSubmitDetails'); ?>"><?php echo $pluginText['Article Submitter']?></a></li>
        <li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=skippedSubmittions'); ?>"><?php echo $pluginText['Skipped Submission']?></a></li>
        <li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=report'); ?>"><?php echo $pluginText['Submission Reports']?></a></li>
    <?php }?>
    
    <?php if(isAdmin() || AS_ALLOW_USER_WEBSITE_MGR) {?> 
	    <li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=website'); ?>"><?php echo $pluginText['Article Directory Manager']?></a></li>
	    <!-- <li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=importer'); ?>"><?php echo $pluginText['Article Directory Importer']?></a></li> -->
	<?php }?>
    
	<?php if(isAdmin()) {?>
		<!-- <li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=croncommand'); ?>"><?php echo $pluginText['Cron Command']?></a></li>-->
        <li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=settings'); ?>"><?php echo $pluginText['Plugin Settings']?></a></li>
    <?php }?>
</ul>