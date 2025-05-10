<?php echo  showSectionHead($pluginText["Campaign Manager"]); ?>
<?php if(!empty($isAdmin)){ ?>
	<table width="50%" border="0" cellspacing="0" cellpadding="0" class="search">
		<tr>
			<th><?php echo $spText['common']['User']?>: </th>
			<td>
				<select name="user_id" id="user_id" onchange="doLoad('user_id', '<?php echo PLUGIN_SCRIPT_URL?>', 'content', 'action=showCampaignManager')">
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

<?php echo $pagingDiv?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left"><?php echo $spText['common']['Id']?></td>
		<td><?php echo $spText['common']['Name']?></td>
		<td><?php echo $spText['common']['User']?></td>
		<td><?php echo $pluginText['Report Generated']?></td>
		<td><?php echo $spText['common']['Status']?></td>
		<td class="right"><?php echo $spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 6; 
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
            $campaignLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editcampaign&campaign_id={$listInfo['id']}", "{$listInfo['name']}")
			?>
			<tr class="<?php echo $class?>">
				<td class="<?php echo $leftBotClass?>"><?php echo $listInfo['id']?></td>
				<td class="td_br_right left"><?php echo $campaignLink?></td>
				<td class="td_br_right left"><?php echo $listInfo['username']?></td>
				<td class="td_br_right"><?php echo stristr($listInfo['last_generated'], '0000-00-00') ? '' : $listInfo['last_generated']?></td>
				<td class="td_br_right"><?php echo  $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];	?></td>
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
					
					<select style="width: 124px;" name="action" id="action<?php echo $listInfo['id']?>" onchange="doBRCPluginAction('<?php echo $pgScriptPath?>', 'content', 'campaign_id=<?php echo $listInfo['id']?>&pageno=<?php echo $pageNo?>', <?php echo $listInfo['id']?>)">
						<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
						<?php if(BRC_ENABLE_UI_REPORT_GENERATION){ ?>
							<option value="showRunCampaign"><?php echo $pluginText['Run Campaign']?></option>
	         	        <?php }?>
						<option value="report"><?php echo $spText['label']['View Reports']?></option>
						<option value="<?php echo $statAction?>"><?php echo $statLabel?></option>
						<option value="editcampaign"><?php echo $spText['common']['Edit']?></option>
						<option value="deleteCampaign"><?php echo $spText['common']['Delete']?></option>
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
		<td class="left" colspan="<?php echo ($colCount-1)?>"></td>
		<td class="right"></td>
	</tr>
</table>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a onclick="<?php echo  pluginGETMethod('action=newcampaign&user_id='.$userId, 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $pluginText['New Campaign']?>
         	</a>
    	</td>
	</tr>
</table>