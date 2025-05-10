<?php 
echo showSectionHead($pluginText['View Order']);
$backLink = pluginMenu('action=orderManager');
?>
<div>&nbsp;<a href="javascript:void(0)" onclick="<?php echo $backLink?>" class="back">&#171&#171 Back</a></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0px" class="summary_tab">
	<tr>
    	<th class="leftcell" width="20%"><?php echo $pluginText['Order Id']?>:</th>
        <td width="40%" style="text-align: left;">#<?php echo $orderInfo['id']?></td>
        <th width="20%"><?php echo $spText['common']['Date']?>:</th>
        <td style="text-align: left;"><?php echo date("Y-m-d", strtotime($orderInfo['invoice_date']))?></td>        		
	</tr>
	<tr>
    	<th class="leftcell" width="20%"><?php echo $spText['common']['Status']?>:</th>
        <td width="40%" style="text-align: left;">
        	<?php 
        	$className = "noteleft";
        	
        	if ($orderInfo['status'] == 'success') {
        		$className = "notesuccess";
        	} elseif ($orderInfo['status'] == 'error') {
        		$className = "notefailed";
        	}
        	?>
        	<b class="<?php echo $className; ?>"><?php echo $orderInfo['status']; ?></b>
        </td>
        <th width="20%"><?php echo $pluginText['Paid By']?>:</th>
        <td style="text-align: left;"><?php echo $pgInfo['name']; ?></td>        		
	</tr>
	<tr>
    	<th class="leftcell" width="20%"><?php echo $pluginText['Transaction Id']?>:</th>
        <td width="40%" style="text-align: left;"><?php echo $orderInfo['txn_id']?></td>
        <th width="20%"><?php echo $spText['label']['Updated']?>:</th>
        <td style="text-align: left;"><?php echo $orderInfo['last_updated']?></td>        		
	</tr>
	<tr>
    	<th class="leftcell" width="20%"><?php echo $spText['common']['User']?>:</th>
        <td width="40%" style="text-align: left;"><?php echo $userInfo['first_name'] . " " . $userInfo['last_name']?></td>
        <th width="20%"><?php echo $spText['label']['Type']?>:</th>
        <td style="text-align: left;"><?php echo $orderInfo['invoice_type']?></td>        		
	</tr>
</table>
<br><br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="plainHead">
		<td class="left"><?php echo $spText['common']['Id']?></td>
		<td><?php echo $spText['common']['Name']; ?></td>
		<td><?php echo $pluginText['Quantity']; ?></td>
		<td><?php echo $pluginText['Amount']; ?></td>
		<td><?php echo $spText['common']['Total']; ?></td>
	</tr>
	<?php
	$total = $orderInfo['item_quantity'] * $orderInfo['item_amount'];
	$currSymbol = $currencyList[$orderInfo['currency']]['symbol'];
	$colCount = 5;
	?>
	<tr class="white_row">
		<td class="td_left_border td_br_right"><?php echo $orderInfo['item_id']?></td>
		<td class="td_br_right"><?php echo $orderInfo['item_name']?></td>
		<td class="td_br_right"><?php echo $orderInfo['item_quantity']?></td>
		<td class="td_br_right"><?php echo  $currSymbol. $orderInfo['item_amount']?></td>
		<td class="td_br_right" style="font-weight: bold;"><?php echo  $currSymbol. $total?></td>
	</tr>
	<tr class="listBot">
		<td class="left" colspan="<?php echo ($colCount-1)?>"></td>
		<td class="right"></td>
	</tr>
</table>

<?php if (isAdmin()) {?>
	<br><br>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
		<tr class="plainHead">
			<td class="left"><?php echo $pluginText['Transaction Log']?></td>
		</tr>
		<tr class="white_row">
			<td class="td_left_border td_br_right left"><?php echo $orderInfo['txn_log']?></td>
		</tr>
		<tr class="listBot">
			<td class="left"></td>
		</tr>
	</table>
<?php }?>