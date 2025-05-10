<?php echo showSectionHead($pluginText["Search Engine Manager"]); ?>

<script type="text/javascript">
$(document).ready(function() { 
    $("table").tablesorter({ 
		sortList: [[2,0]]
    });
});
</script>

<table id="cust_tab" class="tablesorter">
	<thead>
		<tr>
			<th><?php echo $spText['common']['Id']?></th>
			<th><?php echo $spText['common']['Name']?></th>
			<th><?php echo $spTextSE['no_of_results_page']?></th>
			<th><?php echo $spTextSE['max_results']?></th>
			<th><?php echo $spText['common']['Status']?></th>
			<th><?php echo $spText['common']['Action']?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	if(count($list) > 0) {
		foreach($list as $i => $listInfo){
			?>
			<tr>
				<td width="40px"><?php echo $listInfo['id']?></td>
				<td><?php echo $listInfo['domain']?></td>
				<td><?php echo $listInfo['no_of_results_page']?></td>
				<td><?php echo $listInfo['max_results']?></td>
				<td><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];	?></td>
				<td width="100px">
					<?php
						if($listInfo['status']){
							$statAction = "inactivateSearchEngine";
							$statLabel = $spText['common']["Inactivate"];
						}else{
							$statAction = "activateSearchEngine";
							$statLabel = $spText['common']["Activate"];
						} 
					?>
					<select name="action" id="action<?php echo $listInfo['id']?>" onchange="doPluginAction('<?php echo PLUGIN_SCRIPT_URL?>', 'content', 'se_id=<?php echo $listInfo['id']?>', 'action<?php echo $listInfo['id']?>')">
						<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
						<option value="<?php echo $statAction?>"><?php echo $statLabel?></option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{
		?>
		<tr><td colspan="6"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php
	} 
	?>
	</tbody>
</table>