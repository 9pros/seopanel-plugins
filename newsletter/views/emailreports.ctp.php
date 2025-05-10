<?php if (!empty($backLink)) { ?> 
    <div style="float: left;width: 150px;">&nbsp;
    	<?php echo scriptAJAXLinkHref($backLink, 'content', "", "&#171&#171 Back", 'back');?>
	</div>
<?php }?>
<?=$pagingDiv?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left"><?=$spText['login']['Email']?></td>
		<td><?=ucfirst($spText['common']['Status'])?></td>
		<td><?=$pluginText['Opened']?></td>
		<td><?=$pluginText['Clicked']?></td>
		<td><?=$pluginText['Sent Log']?></td>
		<td class="right"><?=$pluginText['Sent Time']?></td>
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
            ?>
			<tr class="<?=$class?>">
				<td class="<?=$leftBotClass?> left"><?=$listInfo['email']?></td>
				<td class="td_br_right">
					<?php
					if ($listInfo['status']) {
					    echo "<font class='success'>".$pluginText["Success"]."</font>";
					} else{
					    echo "<font class='error'>".ucfirst($spText['common']['failed'])."</font>";
					}
					?>
				</td>
				<td class="td_br_right">
					<?php
					if ($listInfo['opened']) {
					    echo $_SESSION['text']['common']['Yes'];
					} else{
					    echo $_SESSION['text']['common']['No'];
					}
					?>	
				</td>
				<td class="td_br_right"><?=$listInfo['click_count']?></td>
				<td class="td_br_right"><?=$listInfo['log_message']?></td>
				<td class="<?=$rightBotClass?>"><?=$listInfo['sent_time']?></td>
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