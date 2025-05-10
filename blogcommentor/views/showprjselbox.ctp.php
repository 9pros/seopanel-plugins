<select name="project_id" id="project_id" onchange="<?php echo $onChange?>">
	<?php if($projectNull){ ?>
		<option value="">-- Select --</option>
	<?php } ?>
	<?php foreach($projectList as $projectInfo){?>
		<?php if($projectInfo['id'] == $projectId){?>
			<option value="<?php echo $projectInfo['id']?>" selected><?php echo $projectInfo['project_name']?></option>
		<?php }else{?>
			<option value="<?php echo $projectInfo['id']?>"><?php echo $projectInfo['project_name']?></option>
		<?php }?>
	<?php }?>
</select>