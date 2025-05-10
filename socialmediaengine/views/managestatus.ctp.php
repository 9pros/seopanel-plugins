<?php 
echo showSectionHead($pluginText['Post Manager']);
Session::showSessionMessges();
$searchFun = "scriptDoLoadPost('$pgScriptPath', 'listform', 'content')";
?>
<form name="listform" id="listform" onsubmit="<?php echo $searchFun?>">
<table class="search">
	<tr>
		<th><?php echo $spText['button']['Search']?>: </th>
		<td><input type="text" name="keyword" value="<?php echo htmlentities($post['keyword'], ENT_QUOTES)?>"></td>
		<th><?php echo $spText['label']['Project']?>: </th>
		<td>
			<select name="project_id" onchange="<?php echo $searchFun?>">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($projectList as $projectInfo){?>
					<?php if($projectInfo['id'] == $projectId){?>
						<option value="<?php echo $projectInfo['id']?>" selected><?php echo $projectInfo['project']?></option>
					<?php }else{?>
						<option value="<?php echo $projectInfo['id']?>"><?php echo $projectInfo['project']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<th><?php echo $spText['common']['Period']?>:</th>
    	<td>
    		<input type="text" value="<?php echo $fromTime?>" name="from_time"/> 
    		<input type="text" value="<?php echo $toTime?>" name="to_time"/>		
			<script>
			$(function() {
				$( "input[name='from_time'], input[name='to_time']").datepicker({dateFormat: "yy-mm-dd"});
			});
		  	</script>
			&nbsp;&nbsp;
			<a href="javascript:void(0);" onclick="<?php echo $searchFun?>" class="actionbut"><?php echo $spText['button']['Show Records']?></a>
		</td>
	</tr>
</table>
</form>

<?php if(!empty ($alertMsg)){?>
    <div class="alertdiv">
        <?php echo $alertMsg; ?>
    </div>
<?php } ?>

<br>
<div style="width: 200px; float: left; margin-bottom: 4px;">
	<a  onclick="<?php echo pluginGETMethod('action=newstatus&project_id='.$projectId, 'content')?>" href="javascript:void(0);" class="actionbut">
		<?php echo $pluginText['New Status']?>
	</a>
</div>
<?php echo $pagingDiv?>
<table class="list">
	<tr class="listHead">
		<td><?php echo $spText['common']['Id']?></td>
        <td><?php echo $spText['label']['Title']?></td>
		<td><?php echo $spText['label']['Project']?></td>
		<td>Connections</td>
		<td><?php echo $pluginText['Schedule Time']?></td>
		<td><?php echo $spText['common']['Status']?></td>
		<td><?php echo $spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 7;
	if(count($list) > 0){
		$catCount = count($list);
		foreach($list as $i => $listInfo){
            $listInfo['share_title'] = strlen($listInfo['share_title']) > 60 ? substr($listInfo['share_title'], 0, 60) . "..." : $listInfo['share_title'];
			$statusLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=edit_status&id={$listInfo['id']}", $listInfo['share_title']);
			?>
			<tr>
				<td><?php echo $listInfo['id']?></td>
                <td><?php echo $statusLink?></td>
				<td><?php echo $listInfo['project']?></td>
				<td id='source_div_<?php echo $listInfo['id']?>'>
					<script type="text/javascript"><?php echo pluginGETMethod('action=updateSocialMediaSources&status_id='.$listInfo['id'], 'source_div_'.$listInfo['id']); ?></script>
				</td> 
				<td><?php echo $listInfo['schedule_time']?></td>                             
				<td><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"]; ?></td>
				<td width="100px">
					<?php
						if($listInfo['status']){
							$statLabel = "Inactivate";
							 $statAction= "Inactivate_Status";
						}else{
							$statLabel = "Activate";
							$statAction = "Activate_Status";
						}
					?>
					<select name="action" id="action<?php echo $listInfo['id']?>" onchange="doSMEPluginAction('<?php echo $pgScriptPath?>', 'content', 'id=<?php echo $listInfo['id']?>', <?php echo $listInfo['id']?>)">
						<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
						<option value="viewreport">Status Report</option>
						
					    <?php if ($listInfo['status']) { ?>
                        	<option value="postStatus">Publish Status</option>
                        <?php } ?>
						
						<option value="<?php echo $statAction?>"><?php echo $statLabel?></option>
						<option value="duplicateStatus">Duplicate</option>
						<option value="edit_status"><?php echo $spText['common']['Edit']?></option>
						<option value="delete_status"><?php echo $spText['common']['Delete']?></option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{
		echo showNoRecordsList($colCount-2);
	}
	?>
</table>
<table class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a  onclick="<?php echo pluginGETMethod('action=newstatus&project_id='.$projectId, 'content')?>" href="javascript:void(0);" class="actionbut"><?php echo $pluginText['New Status']?></a>
    	</td>
	</tr>
</table>