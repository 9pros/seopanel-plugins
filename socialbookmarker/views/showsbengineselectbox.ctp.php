<select name="sbengine_id" id="sbengine_id" onchange="<?=$onSBChange?>">
	<?php if($sbEngineNull){ ?>
		<option value="">-- <?=$spText['common']['Select']?> --</option>
	<?php } ?>
	<?php foreach($sbEngineList as $sbInfo){?>
		<?php if($sbInfo['id'] == $sbEngineId){?>
			<option value="<?=$sbInfo['id']?>" selected><?=$sbInfo['engine_name']?></option>
		<?php }else{?>
			<option value="<?=$sbInfo['id']?>"><?=$sbInfo['engine_name']?></option>
		<?php }?>
	<?php }?>
</select>