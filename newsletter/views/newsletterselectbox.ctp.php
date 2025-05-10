<?php $langStyle = empty($langStyle) ? 150 : $langStyle; ?>  
<select name="newsletter_id" id="newsletter_id" style="width:<?=$nlStyle?>px;" onchange="<?=$onChange?>">
	<?php if($nlNull){ ?>
		<option value="">-- all --</option>
	<?php } ?>
	<?php foreach($nlList as $nlInfo){?>
		<?php if($nlInfo['id'] == $newsletterId){?>
			<option value="<?=$nlInfo['id']?>" selected><?=$nlInfo['name']?></option>
		<?php }else{?>
			<option value="<?=$nlInfo['id']?>"><?=$nlInfo['name']?></option>
		<?php }?>
	<?php }?>
</select>
