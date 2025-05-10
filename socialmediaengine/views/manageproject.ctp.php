<?php
echo showSectionHead($spTextDiary["Projects Manager"]); ?>
<?php if(!empty($isAdmin)){ ?>
	<table class="search">
		<tr>
			<th><?php echo $spText['common']['User']?>: </th>
			<td>
				<select name="user_id" id="user_id" onchange="doLoad('user_id', '<?php echo PLUGIN_SCRIPT_URL?>', 'content')">
					<option value="">-- <?php echo $spText['common']['Select']?> --</option>
					<?php foreach($userList as $userInfo){?>
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

<?php if(!empty ($alertMsg)){?>
    <div class="alertdiv">
        <?php echo $alertMsg; ?>
    </div>
<?php } ?>

<?php echo $pagingDiv?>
<table class="list">
	<tr class="listHead">
		<td class="left"><?php echo $spText['common']['Id']?></td>
		<td><?php echo $spText['label']['Project']?></td>
		<td><?php echo $spText['common']['User']?></td>				
		<td><?php echo $spText['common']['Status']?></td>
		<td class="right"><?php echo $spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 5;
	if(count($list) > 0){
		$catCount = count($list);
		foreach($list as $i => $listInfo){
			$projectLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=edit&project_id={$listInfo['id']}", "{$listInfo['project']}")
			?>
			<tr class="<?php echo $class?>">
				<td class="<?php echo $leftBotClass?>"><?php echo $listInfo['id']?></td>
				<td class="td_br_right left"><?php echo $projectLink?></td>
				<td class="td_br_right left"><?php echo $listInfo['users']?></td>
				<td class="td_br_right"><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];	?></td>
				<td class="<?php echo $rightBotClass?>" width="100px">
					<?php
						if($listInfo['status']){
							$statAction = "Inactivate";
							$statLabel = $spText['common']["Inactivate"];
						}else{
							$statAction = "Activate";
							$statLabel = $spText['common']["Activate"];
						}
					?>
					<select style="width: 124px;" name="action" id="action<?php echo $listInfo['id']?>" onchange="doSMEPluginAction('<?php echo $pgScriptPath?>', 'content', 'project_id=<?php echo $listInfo['id']?>&pageno=<?php echo $pageNo?>', <?php echo $listInfo['id']?>)">
						<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
						<option value="manageStatus"><?php echo $pluginText['Post Manager']?></option>
						<option value="<?php echo $statAction?>"><?php echo $statLabel?></option>
						<option value="edit"><?php echo $spText['common']['Edit']?></option>
						<option value="delete"><?php echo $spText['common']['Delete']?></option>
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
         	<a  onclick="<?php echo pluginGETMethod('action=newproject&user_id='.$userId, 'content')?>" href="javascript:void(0);" class="actionbut"><?php echo $spTextPanel['New Project']?></a>
    	</td>
	</tr>
</table>