<ul id='subui'>
	<?php if(isAdmin()) {?>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=blogmanagement'); ?>">Blog Management</a></li>
    	<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=sitedetails'); ?>">Site Details Management</a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=menumanager'); ?>">Menu Manager</a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=menuitemmanager'); ?>">Menu Item Manager</a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=menuTranslatior'); ?>">Menu Translator</a></li>
		<li><a href="javascript:void(0);" onclick="<?php echo pluginMenu('action=stylemanager'); ?>">Style Manager</a></li>
    <?php }?>
</ul>