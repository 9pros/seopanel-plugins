<select name="keyword_id" style="width: 200px;" onchange="<?php echo $onChange?>">
	
	<?php if ($keywordNull) {?>
		<option value="">-- <?php echo $spText['common']['Select']?> --</option>
	<?php }?>
	
	<?php foreach($keywordList as $kId => $keyword) { ?>
		<?php if($kId == $keywordId) {?>
			<option value="<?php echo $kId?>" selected="selected"><?php echo $keyword?></option>
		<?php } else {?>
			<option value="<?php echo $kId?>"><?php echo $keyword?></option>
		<?php }?>
	<?php }?>
	
</select>