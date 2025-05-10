<?php echo showSectionHead($pluginText["Orders"]); ?>

<form name="listform" id="listform" onsubmit="<?php echo $submitLink?>;return false;">
<input type="hidden" name="action" value="orderManager">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>
		<th><?php echo $spText['button']['Search']?>: </th>
		<td width="100px">
			<input type="text" name="search_name" value="<?php echo htmlentities($info['search_name'], ENT_QUOTES)?>" onblur="<?php echo $submitLink?>">
		</td>
		<?php if(isAdmin()) {?>
			<th><?php echo $spText['common']['User']?>: </th>
			<td width="100px">
				<select name="user_id" id="user_id" onchange="<?php echo $submitLink?>">
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
		<?php } ?>
		<td>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="javascript:void(0);" onclick="<?php echo $submitLink; ?>" class="actionbut"><?php echo $spText['button']['Show Records']?></a>
		</td>
	</tr>
</table>
</form>

<?php echo $pagingDiv?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left"><?php echo $spText['common']['Id']; ?></td>
		<td><?php echo $spText['label']['Type']; ?></td>
		<td><?php echo $spText['common']['User']; ?></td>
		<td><?php echo $spText['common']['Name']; ?></td>
		<td><?php echo $pluginText['Quantity']; ?></td>
		<td><?php echo $pluginText['Amount']; ?></td>
		<td><?php echo $spText['common']['Total']; ?></td>
		<td><?php echo $spText['label']['Updated']; ?></td>
		<td class="right"><?php echo $spText['common']['Status']?></td>
	<?php
	$colCount = 9; 
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
            
            $total = $listInfo['item_quantity'] * $listInfo['item_amount'];
            $orderLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=viewOrder&id={$listInfo['id']}", "#{$listInfo['id']}");
            $currencySymbol = $currencyList[$listInfo['currency']]['symbol'];
            ?>
			<tr class="<?php echo $class?>">
				<td class="<?php echo $leftBotClass?>"><?php echo $orderLink; ?></td>
				<td class="td_br_right"><?php echo $listInfo['invoice_type']; ?></td>
				<td class="td_br_right left"><?php echo $listInfo['username']; ?></td>
				<td class="td_br_right left"><?php echo $listInfo['item_name']; ?></td>
				<td class="td_br_right"><?php echo $listInfo['item_quantity']; ?></td>
				<td class="td_br_right"><?php echo $currencySymbol . $listInfo['item_amount']; ?></td>
				<td class="td_br_right"><?php echo $total . $currencySymbol; ?></td>
				<td class="td_br_right"><?php echo $listInfo['invoice_date']; ?></td>
				<td class="<?php echo $rightBotClass?>">
					<?php
					$className = "noteleft";
					if ($listInfo['status'] == 'success') {
						$className = "notesuccess";
					} elseif ($listInfo['status'] == 'error') {
						$className = "notefailed";
					} 
					?>
					<b class="<?php echo $className; ?>"><?php echo $listInfo['status']; ?></b>
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