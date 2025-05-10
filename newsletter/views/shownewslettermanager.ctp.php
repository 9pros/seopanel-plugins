<?php echo showSectionHead($pluginText["Newsletter Manager"]); ?>
<table width="50%" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>
		<th><?=$pluginText['Campaign']?>: </th>
		<td>
			<select name="campaign_id" id="campaign_id" onchange="doLoad('campaign_id', '<?=$pgScriptPath?>', 'content')">
				<?php foreach($campaignList as $campaignInfo){?>
					<?php if($campaignInfo['id'] == $campaignId){?>
						<option value="<?=$campaignInfo['id']?>" selected><?=$campaignInfo['campaign_name']?></option>
					<?php }else{?>
						<option value="<?=$campaignInfo['id']?>"><?=$campaignInfo['campaign_name']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
	</tr>
</table>

<?=$pagingDiv?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left"><?=$spText['common']['Id']?></td>
		<td><?=$spText['common']['Name']?></td>
		<td><?=$spText['common']['Total']?></td>
		<td><?=$pluginText['Sent']?></td>
		<td><?=ucfirst($spText['common']['failed'])?></td>
		<td><?=$pluginText['Opened']?></td>
		<td><?=$pluginText['Clicked']?></td>
		<td><?=$pluginText['Start Date']?></td>
		<td><?=$pluginText['End Date']?></td>
		<td><?=$spText['label']['Cron']?></td>
		<td><?=$spText['common']['Status']?></td>
		<td class="right"><?=$spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 12; 
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
            $editLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editNL&newsletter_id={$listInfo['id']}", "{$listInfo['name']}")
			?>
			<tr class="<?=$class?>">
				<td class="<?=$leftBotClass?>"><?=$listInfo['id']?></td>
				<td class="td_br_right left"><?=$editLink?></td>
				<td class="td_br_right"><?=$listInfo['total']?></td>
				<td class="td_br_right"><?=$listInfo['sent']?></td>
				<td class="td_br_right"><?=$listInfo['failed']?></td>
				<td class="td_br_right"><?=$listInfo['opened']?></td>
				<td class="td_br_right"><?=$listInfo['click_count']?></td>
				<td class="td_br_right"><?=$listInfo['start_date']?></td>
				<td class="td_br_right"><?=$listInfo['end_date']?></td>
				<td class="td_br_right">
					<?php echo ($listInfo['cron']) ? $spText['common']["Yes"] : $spText['common']["No"]; ?>
				</td>
				<td class="td_br_right"><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];?></td>
				<td class="<?=$rightBotClass?>" width="100px">
					<?php
						if($listInfo['status']){
							$statAction = "InactivateNL";
							$statLabel = $spText['common']["Inactivate"];
						}else{
							$statAction = "ActivateNL";
							$statLabel = $spText['common']["Activate"];
						} 
					?>
					<select style="width: 124px;" name="action" id="action<?=$listInfo['id']?>" onchange="doPluginAction('<?=$pgScriptPath?>', 'content', 'newsletter_id=<?=$listInfo['id']?>&pageno=<?=$pageNo?>', <?=$listInfo['id']?>)">
						<option value="select">-- <?=$spText['common']['Select']?> --</option>
						<option value="testmail"><?=$pluginText['Send Test Mail']?></option>
						<?php if ($listInfo['status']) {?>
							<option value="startsendnewsletter"><?=$pluginText['Send Newsletter']?></option>
						<?php }?>
						<?php if ($listInfo['sent'] > 0) {?>
							<option value="reportmanager"><?=$pluginText['Newsletter Reports']?></option>
						<?php }?>
						<option value="<?=$statAction?>"><?=$statLabel?></option>
						<option value="editNL"><?=$spText['common']['Edit']?></option>
						<option value="deleteNL"><?=$spText['common']['Delete']?></option>
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
         	<a onclick="<?=pluginGETMethod('action=newNL&campaign_id='.$campaignId, 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$pluginText['New Newsletter']?>
         	</a>
    	</td>
	</tr>
</table>