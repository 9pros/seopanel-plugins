<select name="status_id" style="width: 300px;" onchange="<?php echo pluginPOSTMethod('search_form', 'subcontent', 'action=showreport')?>">
	<option value="">-- <?php echo $spText['common']['Select']?> --</option>
	<?php foreach($statusList as $list) {
	    $list['share_title'] = strlen($list['share_title']) > 60 ? substr($list['share_title'], 0, 60) . "..." : $list['share_title'];
	    ?>
		<?php if($list['id'] == $statusId) {?>
			<option value="<?php echo $list['id']?>" selected="selected"><?php echo $list['share_title']?></option>
		<?php } else {?>
			<option value="<?php echo $list['id']?>"><?php echo $list['share_title']?></option>
		<?php }?>
	<?php }?>
</select>