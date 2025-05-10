<?php echo showSectionHead($pluginText["Payment Gateway Manager"]); ?>

<?php echo $pagingDiv?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left"><?php echo $spText['common']['Id']?></td>
		<td><?php echo $spText['common']['Name']?></td>
		<td><?php echo $spText['common']['Category']?></td>
		<td><?php echo $pluginText['Sandbox']?></td>
		<td><?php echo $spText['common']['Status']?></td>
		<td class="right"><?php echo $spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 6; 
	if(count($list) > 0){
		$catCount = count($list);
		foreach($list as $i => $listInfo){
			
			$class = ($i % 2) ? "blue_row" : "white_row";
            if ($catCount == ($i + 1)) {
                $leftBotClass = "tab_left_bot";
                $rightBotClass = "tab_right_bot";
            } else {
                $leftBotClass = "td_left_border td_br_right";
                $rightBotClass = "td_br_right";
            }
            
            $pgNameLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editPaymentGateway&pg_id={$listInfo['id']}", "{$listInfo['name']}");
            ?>
			<tr class="<?php echo $class?>">
				<td class="<?php echo $leftBotClass?>"><?php echo $listInfo['id']?></td>
				<td class="td_br_right left"><?php echo $pgNameLink?></td>
				<td class="td_br_right"><?php echo $listInfo['gateway_code']?></td>
				<td class="td_br_right"><?php echo $listInfo['sandbox'] ? $spText['common']["Yes"] : $spText['common']["No"];?></td>
				<td class="td_br_right"><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];	?></td>
				<td class="<?php echo $rightBotClass?>" width="100px">
					<?php
					if ($listInfo['status']) {
						$statVal = "inactivatePG";
						$statLabel = $spText['common']["Inactivate"];
					} else {
						$statVal = "activatePG";
						$statLabel = $spText['common']["Activate"];
					}
					?>
					<select style="width: 120px;" name="action" id="action<?php echo $listInfo['id']?>" 
						onchange="doPluginAction('<?php echo PLUGIN_SCRIPT_URL?>', 'content', 'pg_id=<?php echo $listInfo['id']?>&pageno=<?php echo $pageNo?>',
						'<?php echo $listInfo['id']?>')">
						<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
						<option value="<?php echo $statVal?>"><?php echo $statLabel?></option>
						<option value="editPaymentGateway"><?php echo $spText['common']['Edit']?></option>
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