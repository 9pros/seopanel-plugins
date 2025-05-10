<?php 
$scriptPath = PLUGIN_SCRIPT_URL . "&action=reportmanager&report_type=report_email&newsletter_id=$newsletterId";
$scriptPath .= "&back_report_type=report_daily&back_from_time=$fromTime&back_to_time=$toTime";
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left"><?=$spText['common']['Date']?></td>
		<td><?=$pluginText['Sent']?></td>
		<td><?=$pluginText['Success']?></td>
		<td><?=ucfirst($spText['common']['failed'])?></td>
		<td><?=$pluginText['Opened']?></td>
		<td class="right"><?=$pluginText['Clicked']?></td>
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
            $listInfo['failed'] = $listInfo['sent'] - $listInfo['success'];
            $timevar = "&from_time={$listInfo['day']}&to_time={$listInfo['day']}";
			?>
			<tr class="<?=$class?>">
				<td class="<?=$leftBotClass?>"><?=$listInfo['day']?></td>
				<td class="td_br_right">
			        <?php echo scriptAJAXLinkHref($scriptPath, 'content', "&sent_status=sent".$timevar, $listInfo['sent']);?>
		        </td>
				<td class="td_br_right">
			        <?php echo scriptAJAXLinkHref($scriptPath, 'content', "&sent_status=success".$timevar, $listInfo['success']);?>
				</td>
				<td class="td_br_right">
			        <?php echo scriptAJAXLinkHref($scriptPath, 'content', "&sent_status=failed".$timevar, $listInfo['failed']);?>
		        </td>
				<td class="td_br_right">
			        <?php echo scriptAJAXLinkHref($scriptPath, 'content', "&sent_status=opened".$timevar, $listInfo['opened']);?>
		        </td>
				<td class="<?=$rightBotClass?>">
			        <?php echo scriptAJAXLinkHref($scriptPath, 'content', "&sent_status=clicked".$timevar, $listInfo['click_count']);?>
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