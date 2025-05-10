<select name="link_title" id="link_title" style="width: 200px;">
	<option value="">-- <?php echo $spText['common']['Select']?> --</option>
	<?php foreach($anchorList as $anchor) {?>
		<option value="<?php echo $anchor['link_title']?>"><?php echo $anchor['link_title']?></option>
	<?php }?>
</select>
