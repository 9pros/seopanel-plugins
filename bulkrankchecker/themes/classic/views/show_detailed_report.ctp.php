<table width="100%" class="summary">
	
	<tr>
		<td class="subheader" style="border-left: none;"><?php echo $spText['common']['Date']?></td>
		<td class="subheader"><?php echo $spText['common']['Url']?></td>
		<td class='subheader'><?php echo $spText['common']['Rank']?></td>
		<td class="subheader"><?php echo $pluginText['Link Found']?></td>
	</tr>
	
	<?php
	// check count of reports
	$colCount = 4; 
	if (count($repDetailList) > 0) {
		
		// loop through the keyword reports
		foreach($repDetailList as $date => $list) {

			// lopp through link reports
			$i = 0;
			foreach ($list as $linkId => $listInfo) {
				?>
				<tr>
					<?php
					if (empty($i)) {
						?>
						<td rowspan="<?php echo count($list)?>" class="content" style="border-left: none;"><?php echo $date?></td>
						<?php
					} 
					?>	
					<td class="content"><?php echo $cmpLinkList[$linkId]?></td>
					<td class="contentmid" style="color: #0033CC;">
						<?php
						// check whether crawling failed
						if ($listInfo['rank'] == -1) {
							$rankStr = "<font class='red'>Fail</font>";
						} else {
							$rankStr = "<b>{$listInfo['rank']}</b>";
							
							// if rank diff not zero
							if ($listInfo['rank_diff'] != 0) {
								$rankStr .= ($listInfo['rank_diff'] > 0) ? " <font class='green'>({$listInfo['rank_diff']})</font>" : " <font class='red'>({$listInfo['rank_diff']})</font>";
							}
							
						}
						
						echo $rankStr;
						?>
					</td>
					<td class="content"><?php echo $listInfo['link_found']?></td>
				</tr>
				<?php
				$i++;
			}
		}
		
	} else {
		?>
		<tr>
			<td class="contentmid" colspan="<?php echo $colCount?>" style="border-left: none;"><?php echo  $_SESSION['text']['common']['No Records Found']."!"; ?></td>
		</tr>
		<?php
	}
	?>
</table>