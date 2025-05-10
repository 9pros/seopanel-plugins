<script type="text/javascript">
$(document).ready(function() {
    $("table").tablesorter({ 
		sortList: [[2,0]]
    });
});
</script>

<table id="cust_tab" class="tablesorter" style="margin-top: 30px;">
	<thead>
		<tr class="listHead">
			<th class="id_hash">#</th>
			<th><?php echo $spText['common']['Link']?></th>
			<th><?php echo $spText['common']['Rank']?></th>
			<th><?php echo $spText['common']['Keyword']?></th>
			<th><?php echo $pluginText['Link Found']?></th>
			<th><?php echo $spText['common']['Date']?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (!empty($pageResultList)) {
		    foreach($pageResultList as $i => $listInfo){
    			?>
    			<tr>
    				<td><?php echo $i + 1;?></td>
    				<td><?php echo $listInfo['url'];?></td>
    				<td><?php echo $listInfo['rank'];?></td>
    				<td><?php echo $listInfo['keyword'];?></td>
    				<td><?php echo $listInfo['link_found'];?></td>
    				<td><?php echo $listInfo['time'];?></td>
    			</tr>
    			<?php
    		}
		} else {
		    echo showNoRecordsList(4);
		}
		?>
	</tbody>
</table>