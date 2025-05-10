<?php echo $pagingDiv?>
<table id="cust_tab" class="table">
	<tr>
        <th><?php echo $spText['common']['Id']?></th>
		<th><?php echo $pluginText['Post']?></th>
		<th>Connection</th>
        <th><?php echo $spText['common']['Link']?></th>
        <th><?php echo $spText['label']['Project']?></th>
		<th><?php echo $spText['common']['Status']?></th>
		<th>Method</th>
		<th>Scheduled Time</th>
		<th>Published Time</th>
		<th><?php echo $pluginText['Submit Log']?></th>
	</tr>
	<?php
	$colCount = 10;
	if(count($list) > 0){
		foreach($list as $i => $listInfo) {
            ?>
			<tr class="<?php echo ($listInfo['submit_status'] == 'Success') ? "table-success" : "table-danger"?>">
				<td><?php echo $listInfo['id']?></td>
				<td><?php echo $listInfo['share_title']?></td>
				<td><i class="fab fa-<?php echo strtolower($resourceList[$listInfo['resource_id']])?>"></i> <?php echo $listInfo['connection_name']?></td>
				<td><a target="_blank" href="<?php echo $listInfo['url']?>"><?php echo $listInfo['url']?></a></td>
                <td><?php echo $listInfo['project']?></td>
				<td>					
					<?php
					if ($listInfo['submit_status'] == 'Success') {
						echo "<b class='success'>{$spText['label']['Success']}</b>";
					} else {
						echo "<b class='error'>{$spText['common']['failed']}</b>";
					}
					?>
				<td>					
					<?php
					if ($listInfo['cron']) {
					    echo $spText['label']['Cron'];
					} else {
					    echo "Manual";
					}
					?>
				</td>
				<td><?php echo $listInfo['schedule_time']?></td>
				<td><?php echo $listInfo['submit_time']?></td>
				<td><?php echo $listInfo['submit_log']?></td>
			</tr>
			<?php
		}
	}else{
		echo showNoRecordsList($colCount-2);
	}
	?>
</table>
