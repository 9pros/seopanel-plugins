<?php 
echo showSectionHead($pluginText['Submission Reports']);
$submitAction = pluginPOSTMethod('search_form', 'subcontent', 'action=showreport');
?>
<form id='search_form'>
<table class="search">
	<tr>				
		<th><?php echo $spText['label']['Project']?>: </th>
		<td>
			<select name="project_id" id="project_id" onchange="doLoad('project_id', '<?php echo PLUGIN_SCRIPT_URL?>&action=showStatusSelectBox', 'status_area')">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($projectList as $projectInfo) {?>
					<?php if($projectInfo['id'] == $projectId) {?>
						<option value="<?php echo $projectInfo['id']?>" selected><?php echo $projectInfo['project']?></option>
					<?php } else {?>
						<option value="<?php echo $projectInfo['id']?>"><?php echo $projectInfo['project']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>						
		<th><?php echo $pluginText['Post']?>: </th>
		<td id="status_area">
			<select name="status_id" onchange="<?php echo $submitAction?>" style="width: 250px;">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($statusList as $list) { ?>
					<?php if($list['id'] == $statusId) {?>
						<option value="<?php echo $list['id']?>" selected="selected"><?php echo $list['share_title']?></option>
					<?php } else {?>
						<option value="<?php echo $list['id']?>"><?php echo $list['share_title']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<th><?php echo $spText['common']['Status']?> : </th>
		<td>
			<select name="sub_status" onchange="<?php echo $submitAction?>">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($subStatusList as $statAction => $statLabel){?>
					<option value="<?php echo $statAction?>"><?php echo $statLabel?></option>
				<?php }?>
			</select>
		</td>
	</tr>
	<tr>
		<th><?php echo $spText['button']['Search']?>: </th>
		<td><input type="text" name="keyword" value="<?php echo htmlentities($post['keyword'], ENT_QUOTES)?>"></td>
		<th><?php echo $pluginText['Connection']?>: </th>
		<td>
			<select name="sm_id" onchange="<?php echo $submitAction?>">
    			<option value="">-- <?php echo $spText['common']['Select']?> --</option>
    			<?php foreach($smList as $list) { ?>
					<option value="<?php echo $list['id']?>"><?php echo $list['connection_name']?></option>
    			<?php }?>
			</select>
		</td>
		<th><?php echo $spText['common']['Period']?>:</th>
		<td colspan="2">
			<input type="text" value="<?php echo $fromTime?>" name="from_time"/>
			<input type="text" value="<?php echo $toTime?>" name="to_time"/>
			<script>
			$(function() {
				$( "input[name='from_time'], input[name='to_time']").datepicker({dateFormat: "yy-mm-dd"});
			});
		  	</script>
			&nbsp;&nbsp;
			<a href="javascript:void(0);" onclick="<?php echo $submitAction?>" class="actionbut"><?php echo $spText['button']['Show Records']?></a>
		</td>
	</tr>
</table>
</form>
<div id='subcontent'>
	<script><?php echo $submitAction?></script>
</div>