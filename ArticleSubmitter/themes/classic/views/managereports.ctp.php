<?php echo $pagingDiv?>
<table id="cust_tab">
	<tr>
		<th><?php echo $pluginText["Article"]?></th>
		<th><?php echo $spText['common']['Website']?></th>
		<th><?php echo $pluginText["Submit Log"]?></th>
        <th><?php echo $spText['common']['Status']?></th>
        <th><?php echo 'Confirmation'?></th>
        <th><?php echo $spText['common']['Action']?></th>
		<th><?php echo $spText['label']['Updated']?></th>
	</tr>
	<?php
	$colCount = 7;
	if(count($list) > 0){
		$catCount = count($list);
		foreach($list as $i => $listInfo){
            $postLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=manageStatus&status_id={$listInfo['status_id']}", "{$listInfo['share_title']}");
            $statusId = "status_".$listInfo['id'];
            ?>
			<tr class="<?php echo $class?>">
				<td ><?php echo $listInfo['article']?></td>
				<td><?php echo $listInfo['website_name']?></td>
				<td><?php echo $listInfo['submit_status_desc']?></td>
				<td><?php echo $listInfo['submit_status']?></td>
                <td id='<?php echo $statusId?>'><?php echo $listInfo['confirmation']?></td>
                <td>
                    <select style="width: 124px;" name="action" id="action<?php echo $listInfo['id']?>" onchange="doPluginAction('<?php echo $pgScriptPath?>', '<?php echo $statusId?>', 'id=<?php echo $listInfo['id']?>&pageno=<?php echo $pageNo?>', <?php echo $listInfo['id']?>)">
						<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
						<option value="checkstatus"><?php echo $spText['button']['Check Status']?></option>
					</select>
                                 </td>
				<td><?php echo $listInfo['submit_time']?></td>
			</tr>
			<?php
		}
	}else{
		echo showNoRecordsList($colCount-2);
	}
	?>
	
</table>
