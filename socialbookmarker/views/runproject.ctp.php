<?php echo showSectionHead($pluginText['Run Project']); ?>
<form id="submitform">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>
		<th><?=$spText['common']['Website']?>: </th>
		<td>
			<select name="website_id" id="website_id" onchange="doLoad('website_id', '<?=PLUGIN_SCRIPT_URL?>&action=showprjselbox', 'projectarea')">
				<?php foreach($websiteList as $websiteInfo){?>
					<?php if($websiteInfo['id'] == $websiteId){?>
						<option value="<?=$websiteInfo['id']?>" selected><?=$websiteInfo['name']?></option>
					<?php }else{?>
						<option value="<?=$websiteInfo['id']?>"><?=$websiteInfo['name']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<th><?=$spText['label']['Project']?>: </th>
		<td id="projectarea">
			<?php echo $this->pluginRender('showprjselbox', 'ajax'); ?>
		</td>
		<th><?=$pluginText['Social Bookmarker']?>: </th>
		<td id="sbenginearea">
			<?php echo $this->pluginRender('showsbengineselectbox', 'ajax'); ?>
		</td>
		<td>
			<a href="javascript:void(0);" onclick="<?=pluginPOSTMethod('submitform', 'content', 'action=runproject')?>" class="actionbut"><?=$spText['button']['Submit']?> &gt;&gt;</a>
		</td>
	</tr>
</table>
</form>


<?php if ($completed) {?>
	<?php showSuccessMsg($pluginText['submisioncompleted'], false)?>
<?php } else {?>

    <div id="submitmenu">
    	<ul id="subtabs">
    		<li><a href="javascript:void(0);" onclick="<?=pluginPOSTMethod('submitform', 'content', 'action=runproject&subact=previous')?>" class="">&laquo; <?=$pluginText['Previous']?> </a></li>
    		<li><a href="javascript:void(0);" onclick="<?=pluginPOSTMethod('submitform', 'content', 'action=runproject&subact=login')?>" class="<?=$loginClass?>"><?=$spText['login']['Login']?></a></li>
    		<li><a href="javascript:void(0);" onclick="<?=pluginPOSTMethod('submitform', 'content', 'action=runproject&subact=submit')?>" class="<?=$submitClass?>"><?=$spText['button']['Submit']?></a></li>
    		<li><a href="javascript:void(0);" onclick="<?=pluginPOSTMethod('submitform', 'content', 'action=runproject&subact=register')?>" class="<?=$registerClass?>"><?=$spText['login']['Register']?></a></li>
    		<li><a href="javascript:void(0);" onclick="<?=pluginPOSTMethod('submitform', 'content', 'action=runproject&subact=next')?>" class=""><?=$pluginText['Next']?> &raquo;</a></li>
    	</ul>
    </div>
    <div id="submitcontent">
    	<?php if ($sbEngineInfo['iframe']) {?>	
    		<iframe src="<?=$sbSubmitUrl?>" width="100%" height="600"></iframe>
    	<?php } else {?>
    		<?php showSuccessMsg($pluginText['popupnote'], false)?>
    		<script>openWindow('<?=$sbSubmitUrl?>', '<?=$sbEngineInfo['engine_name']?>', 800, 500)</script>
    	<?php }?>
    </div>
<?php }?>	