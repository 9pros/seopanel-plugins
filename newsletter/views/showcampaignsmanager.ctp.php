<?php echo showSectionHead($pluginText["Projects Manager"]); ?>
<table width="50%" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>
		<th><?=$spText['common']['Website']?>: </th>
		<td>
			<select name="website_id" id="website_id" onchange="doLoad('website_id', '<?=PLUGIN_SCRIPT_URL?>', 'content')">
				<option value="">-- <?=$spText['common']['Select']?> --</option>
				<?php foreach($websiteList as $websiteInfo){?>
					<?php if($websiteInfo['id'] == $websiteId){?>
						<option value="<?=$websiteInfo['id']?>" selected><?=$websiteInfo['name']?></option>
					<?php }else{?>
						<option value="<?=$websiteInfo['id']?>"><?=$websiteInfo['name']?></option>
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
		<td><?=$pluginText['From Name']?></td>
		<td><?=$pluginText['From Email']?></td>
		<td><?=$pluginText['Reply To Email']?></td>
		<td>SMTP</td>
		<td><?=$spText['common']['Status']?></td>
		<td class="right"><?=$spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 8; 
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
            $campaignLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editcampaign&campaign_id={$listInfo['id']}", "{$listInfo['campaign_name']}")
			?>
			<tr class="<?=$class?>">
				<td class="<?=$leftBotClass?>"><?=$listInfo['id']?></td>
				<td class="td_br_right left"><?=$campaignLink?></td>
				<td class="td_br_right left"><?=$listInfo['from_name']?></td>
				<td class="td_br_right left"><?=$listInfo['from_email']?></td>
				<td class="td_br_right"><?=$listInfo['reply_to']?></td>
				<td class="td_br_right">
					<?php echo $listInfo['is_smtp'] ? $spText['common']["Yes"] : $spText['common']["No"];?>
				</td>
				<td class="td_br_right"><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];	?></td>
				<td class="<?=$rightBotClass?>" width="100px">
					<?php
						if($listInfo['status']){
							$statAction = "Inactivate";
							$statLabel = $spText['common']["Inactivate"];
						}else{
							$statAction = "Activate";
							$statLabel = $spText['common']["Activate"];
						} 
					?>
					<select style="width: 124px;" name="action" id="action<?=$listInfo['id']?>" onchange="doPluginAction('<?=$pgScriptPath?>', 'content', 'campaign_id=<?=$listInfo['id']?>&pageno=<?=$pageNo?>', <?=$listInfo['id']?>)">
						<option value="select">-- <?=$spText['common']['Select']?> --</option>
						<?php if ($listInfo['status']) {?>
    						<option value="newslettermanager"><?=$pluginText['Newsletter Manager']?></option>
    					<?php }?>
						<option value="<?=$statAction?>"><?=$statLabel?></option>
						<option value="editcampaign"><?=$spText['common']['Edit']?></option>
						<option value="deletecampaign"><?=$spText['common']['Delete']?></option>
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
         	<a onclick="<?=pluginGETMethod('action=newcampaign&website_id='.$websiteId, 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$pluginText['New Campaign']?>
         	</a>
    	</td>
	</tr>
</table>