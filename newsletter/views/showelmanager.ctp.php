<?php echo showSectionHead($pluginText["Email List Manager"]); ?>

<?=$pagingDiv?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left"><?=$spText['common']['Id']?></td>
		<td><?=$spText['common']['Name']?></td>
		<td><?=$spText['common']['Total']?></td>
		<td><?=$pluginText['Subscribed']?></td>
		<td><?=$pluginText['Unsubscribed']?></td>
		<td><?=$spText['common']["Inactive"]?></td>
		<td><?=$spText['common']['Status']?></td>
		<td class="right"><?=$spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 9; 
	if(count($list) > 0){
		$catCount = count($list);
		foreach($list as $i => $listInfo){
			$class = ($i % 2) ? "blue_row" : "white_row";
            if($catCount == ($i + 1)){
                $leftBotClass = "tab_left_bot";
                $rightBotClass = "tab_right_bot";
            }else{
                $leftBotClass = "td_left_border td_br_right";
                $rightBotClass = "td_br_right";
            }
            $editLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editEL&email_list_id={$listInfo['id']}", "{$listInfo['name']}")
			?>
			<tr class="<?=$class?>">
				<td class="<?=$leftBotClass?>"><?=$listInfo['id']?></td>
				<td class="td_br_right left"><?=$editLink?></td>
				<td class="td_br_right"><?=$listInfo['total']?></td>
				<td class="td_br_right"><?=$listInfo['subscribed']?></td>
				<td class="td_br_right"><?=$listInfo['unsubscribed']?></td>
				<td class="td_br_right"><?=$listInfo['inactive']?></td>
				<td class="td_br_right"><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];?></td>
				<td class="<?=$rightBotClass?>" width="100px">
					<?php
						if($listInfo['status']){
							$statAction = "InactivateEL";
							$statLabel = $spText['common']["Inactivate"];
						}else{
							$statAction = "ActivateEL";
							$statLabel = $spText['common']["Activate"];
						} 
					?>
					<select style="width: 124px;" name="action" id="action<?=$listInfo['id']?>" onchange="doPluginAction('<?=$pgScriptPath?>', 'content', 'email_list_id=<?=$listInfo['id']?>&pageno=<?=$pageNo?>', <?=$listInfo['id']?>)">
						<option value="select">-- <?=$spText['common']['Select']?> --</option>
						<option value="importemail"><?=$pluginText['Import Emails']?></option>
						<option value="managerEmail"><?=$pluginText['Email Manager']?></option>
						<option value="gensubscribecode"><?=$pluginText['Generate Subscribe Code']?></option>
						<option value="<?=$statAction?>"><?=$statLabel?></option>
						<option value="editEL"><?=$spText['common']['Edit']?></option>
						<option value="deleteEL"><?=$spText['common']['Delete']?></option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{	 
		echo showNoRecordsList($colCount-2);		
	} 
	?>
	<tr class="listBot">
		<td class="left" colspan="<?=($colCount-1)?>"></td>
		<td class="right"></td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a onclick="<?=pluginGETMethod('action=newEL', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$pluginText['New Email List']?>
         	</a>
    	</td>
	</tr>
</table>