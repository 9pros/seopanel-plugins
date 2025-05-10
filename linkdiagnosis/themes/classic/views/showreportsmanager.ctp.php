<?php echo showSectionHead($spTextPanel["Reports Manager"]); ?>

<?php
if(count($projectList) <= 0) {
	showErrorMsg($pluginText['No Projects Found']);
} 
?>

<table width="50%" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>
		<th><?php echo $pluginText['Project']?>: </th>
		<td>
			<select name="project_id" id="project_id" onchange="doLoad('project_id', '<?php echo PLUGIN_SCRIPT_URL?>&action=reports', 'content')">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($projectList as $projectInfo){?>
					<?php if($projectInfo['id'] == $projectId){?>
						<option value="<?php echo $projectInfo['id']?>" selected><?php echo $projectInfo['name']?></option>
					<?php }else{?>
						<option value="<?php echo $projectInfo['id']?>"><?php echo $projectInfo['name']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
	</tr>
</table>

<?php echo $pagingDiv?>

<table id="cust_tab">
	<tr>
		<th><?php echo $spText['common']['Id']?></th>
		<th><?php echo $spText['common']['Name']?></th>
		<th><?php echo $pluginText['Project']?></th>
		<th><?php echo $spText['common']['Url']?></th>
		<th><?php echo $pluginText['Maximum Links']?></th>
		<th><?php echo $pluginText['Total Backlinks']?></th>
		<th><?php echo $pluginText['Crawled Backlinks']?></th>
		<th><?php echo $spText['common']['Status']?></th>
		<th><?php echo $pluginText['Updated']?></th>
		<th><?php echo $spText['common']['Action']?></th>
	</tr>
	<?php 
	if(count($list) > 0){
		
		// loop through the list
		foreach($list as $i => $listInfo){            
            $reportLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editreport&report_id={$listInfo['id']}", "{$listInfo['name']}");
            switch ($listInfo['status']) {
            	            	
            	case 2:
            		$statusLabel = $pluginText["Completed"];
            		$statusColor = "green";
            		break;
            	
            	case 1:
            		$statusColor = "blue";
            		$statusLabel = $pluginText["In Complete"];
            		break;
            	
            	default:            		
            		$statusColor = "";
            		$statusLabel = $pluginText["Not Started"];	
            }
            ?>
			<tr>
				<td><?php echo $listInfo['id']?></td>
				<td><?php echo $reportLink?></td>
				<td><?php echo $listInfo['pname']?></td>
				<td><?php echo $listInfo['url']?></td>
				<td><?php echo $listInfo['max_links']?></td>
				<td><?php echo $listInfo['tot_backlinks']?></td>
				<td><?php echo $listInfo['tot_crawled']?></td>
				<td style="color:<?php echo $statusColor?>"><?php echo $statusLabel?></td>
				<td><?php echo $listInfo['updated']?></td>
				<td>
					<select style="width: 120px;" name="action" id="action<?php echo $listInfo['id']?>" onchange="doPluginAction('<?php echo PLUGIN_SCRIPT_URL?>', 'content', 'report_id=<?php echo $listInfo['id']?>&pageno=<?php echo $pageNo?>', 'action<?php echo $listInfo['id']?>')">
						<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
						<?php if ($listInfo['status'] < 2) {?>
							<?php if(isAdmin() || LD_ALLOW_USER_GENREPORTS) {?>
								<option value="runreport"><?php echo $pluginText['Run']?></option>
							<?php }?>
						<?php }?>
						<option value="importBacklinks"><?php echo $pluginText['Import Backlinks']?></option>
						<?php if ($listInfo['status'] > 0) {?>
							<option value="viewreport"><?php echo $pluginText['View Reports']?></option>
							<?php if(isAdmin() || (LD_ALLOW_USER_GENREPORTS && !SP_HOSTED_VERSION)) {?>
								<option value="verifybacklinks"><?php echo $pluginText['Reverify Backlinks']?></option>
							<?php }?>
						<?php }?>
						<?php if ($listInfo['status'] == 2) {?>
							<?php if(isAdmin() || (LD_ALLOW_USER_GENREPORTS && !SP_HOSTED_VERSION)) {?>
								<option value="rerunreport"><?php echo $pluginText['Recheck Reports']?></option>
							<?php }?>
						<?php }?>
						<option value="editreport"><?php echo $spText['common']['Edit']?></option>
						<option value="deletereport"><?php echo $spText['common']['Delete']?></option>
					</select>
				</td>
			</tr>
			<?php
		}
				
	} else {
		?>
		<tr><td colspan="10"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php
	} 
	?>
</table>
<br>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a onclick="<?php echo pluginGETMethod('action=newreport&project_id='.$projectId, 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $pluginText['New Report']?>
         	</a>
    	</td>
	</tr>
</table>