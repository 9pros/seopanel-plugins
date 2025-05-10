<select name="article_id">
	<option value="">-- <?php echo $spText['common']['Select']?> --</option>
	<?php foreach($articleList as $list) {?>
		<option value="<?php echo $list['id']?>"><?php echo $list['title']?></option>
	<?php }?>
</select>