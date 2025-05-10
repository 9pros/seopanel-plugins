<select name="project_id" id="project_id" onchange="<?=$onChange?>" style="width:300px;">
	<?php if($projectNull){ ?>
		<option value="">-- <?=$spText['common']['Select']?> --</option>
	<?php } ?>
	<?php foreach($projectList as $projectInfo){?>
		<?php if($projectInfo['id'] == $projectId){?>
			<option value="<?=$projectInfo['id']?>" selected><?=$projectInfo['share_url']?></option>
		<?php }else{?>
			<option value="<?=$projectInfo['id']?>"><?=$projectInfo['share_url']?></option>
		<?php }?>
	<?php }?>
</select>
<?php if (!empty($submitForm)) {?>
    <script>
        <?php echo pluginPOSTMethod('submitform', 'content', 'action=runproject&prjchange=1'); ?>
    </script>
<?php }?>