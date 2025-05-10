<select name="report_id" id="report_id" onchange="showAnchorSelectBox('<?php echo PLUGIN_SCRIPT_URL?>', 'anchor_area', 'action=anchorselbox', 'report_id')">
	<?php foreach($reportList as $list) {?>
		<?php if($list['id'] == $reportId) {?>
			<option value="<?php echo $list['id']?>" selected="selected"><?php echo $list['name']?></option>
		<?php } else {?>
			<option value="<?php echo $list['id']?>"><?php echo $list['name']?></option>
		<?php }?>
	<?php }?>
</select>
<script>
	scriptDoLoad('<?php echo PLUGIN_SCRIPT_URL?>', 'anchor_area', 'action=anchorselbox&report_id=<?php echo $reportId?>');
</script>