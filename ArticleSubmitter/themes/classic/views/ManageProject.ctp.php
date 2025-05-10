<?php echo showSectionHead("Projects Manager"); ?>
<?php if(!empty($isAdmin)){ ?>
	<table class="search">
		<tr>
			<th><?php echo $spText['common']['User']?>: </th>
			<td>
				<select name="user_id" id="user_id" onchange="doLoad('user_id', '<?php echo PLUGIN_SCRIPT_URL?>', 'content')">
					<option value="">-- <?php echo $spText['common']['Select']?> --</option>
					<?php foreach($userList as $userInfo){  
						echo $userId."<br>";
						?>
						<?php if($userInfo['id'] == $userId){?>
							<option value="<?php echo $userInfo['id']?>" selected><?php echo $userInfo['username']?></option>
						<?php }else{?>
							<option value="<?php echo $userInfo['id']?>"><?php echo $userInfo['username']?></option>
						<?php }?>
					<?php }?>
				</select>
			</td>
		</tr>
	</table>
<?php } ?>

<?php echo $pagingDiv?>

<table id="cust_tab">
	<tr>
		<th><?php echo $spText['common']['Id']?></th>
		<th><?php echo $spText['common']['Name']?></th>
    	<th><?php echo $spText['common']['User']?></th>
		<th><?php echo $spText['common']['Status']?></th>
		<th><?php echo $spText['common']['Action']?></th>
	</tr>
	<?php
	if(count($list) > 0) {
		foreach($list as $i => $listInfo){
			$projectLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=edit&project_id={$listInfo['id']}", "{$listInfo['project']}");
			?>
			<tr>
				<td><?php echo $listInfo['id']?></td>
				<td><?php echo $projectLink?></td>				
				<td><?php echo $listInfo['user_name']?></td>
				<td><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];	?></td>
				<td width="100px">
					<?php
						if($listInfo['status']){
							$statAction = "Inactivate";
							$statLabel = $spText['common']["Inactivate"];
						}else{
							$statAction = "Activate";
							$statLabel = $spText['common']["Activate"];
						} 
					?>
					<select name="action" id="action<?php echo $listInfo['id']?>" onchange="doASPluginAction('<?php echo PLUGIN_SCRIPT_URL?>', 'content', 'project_id=<?php echo $listInfo['id']?>&pageno=<?php echo $pageNo?>', 'action<?php echo $listInfo['id']?>')">
						<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
						<option value="<?php echo $statAction?>"><?php echo $statLabel?></option>
                        <option value="edit"><?php echo $spText['common']['Edit']?></option>
                        <option value="Article"><?php echo $pluginText['Article Manager']?></option>
					    <option value="Delete"><?php echo $spText['common']['Delete']?></option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{
		?>
		<tr><td colspan="5"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></td></tr>
		<?php
	} 
	?>
</table>
<br>
<table width="100%" class="actionSec">
	<tr>
    	<td>
         	<a onclick="<?php echo pluginGETMethod('action=newProject&user_id='.$userId, 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $pluginText['New Project']?>
         	</a>
    	</td>
	</tr>
</table>