<table width="100%" border="0" cellspacing="0" cellpadding="5px" class="search">
	<tr>
		<th><?php echo $pluginText['Paged Indexed']?>:</th>
		<td id="tot_indexed"><?php echo $runInfo['tot_indexed']?></td>
		<th><?php echo $pluginText['Total Backlinks']?>:</th>
		<td id="tot_backlinks"><?php echo $runInfo['tot_backlinks']?></td>
		<th><?php echo $pluginText['Crawled Backlinks']?>:</th>
		<td id="tot_crawled"><?php echo $runInfo['tot_crawled']?></td>
		<th><?php echo $pluginText['Unique Anchors']?>:</th>
		<td id="tot_anchors"><?php echo $runInfo['tot_anchors']?></td>
	</tr>
	<tr>
		<th><?php echo $pluginText['Excellent Links']?>:</th>
		<td id="tot_exel"><?php echo $runInfo['tot_exel']?></td>
		<th><?php echo $pluginText['Good Links']?>:</th>
		<td id="tot_good"><?php echo $runInfo['tot_good']?></td>
		<th><?php echo $pluginText['Nofollow Links']?>:</th>
		<td id="tot_nofollow"><?php echo $runInfo['tot_nofollow']?></td>
		<th><?php echo $pluginText['Missing Title']?>:</th>
		<td id="tot_missing"><?php echo $runInfo['tot_missing']?></td>
	</tr>
	<tr>
		<?php for($i=10;$i>6;$i--) {?>			
			<th>PR<?php echo $i?>:</th>
			<td id="<?php echo 'tot_pr_'.$i?>"><?php echo $runInfo['tot_pr_'.$i]?></td>
		<?php }?>
	</tr>
	<tr>
		<?php for($i=6;$i>2;$i--) {?>			
			<th>PR<?php echo $i?>:</th>
			<td id="<?php echo 'tot_pr_'.$i?>"><?php echo $runInfo['tot_pr_'.$i]?></td>
		<?php }?>
	</tr>
	<tr>
		<?php for($i=2;$i>=0;$i--) {?>			
			<th>PR<?php echo $i?>:</th>
			<td id="<?php echo 'tot_pr_'.$i?>"><?php echo $runInfo['tot_pr_'.$i]?></td>
		<?php }?>
	</tr>
</table>