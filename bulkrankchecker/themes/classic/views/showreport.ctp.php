<?php 
if(!empty($printVersion)) {
	showPrintHeader($pluginText['Campaign Report']);
	?>
	<table width="80%" border="0" cellspacing="0" cellpadding="0" class="search">
    	<tr>
    		<th><?php echo $pluginText['Campaign']?>:</th>
        	<td><?php echo  $campaignName; ?></td>
    	</tr>
		<tr>
			<th><?php echo $spText['common']['Period']?>:</th>
    		<td>
    			<?php echo $fromTime?> - <?php echo $toTime?>
			</td>
		</tr>
	</table>
	<?php
} else {
	$scriptLink = PLUGIN_SCRIPT_URL."&action=showreport&campaign_id=$campaignId&searchengine_id=$seId&keyword_id=$keywordId&from_time=$fromTime&to_time=$toTime";
	?>
	<div style="float:right; margin: 0px 10px 6px 0px;">
		<a href="<?php echo $scriptLink?>&doc_type=export"><img src="<?php echo SP_IMGPATH?>/icoExport.gif"></a> &nbsp;
		<a target="_blank" href="<?php echo $scriptLink?>&doc_type=print"><img src="<?php echo SP_IMGPATH?>/print_button.gif"></a>&nbsp; 
		<a href="<?php echo $scriptLink?>&doc_type=export&type_export=pdf"><img src="<?php echo PLUGIN_IMGPATH?>/image.jpg"></a>
	</div>
	<div style="clear: both;"></div>
	<?php 
}
?>
<table width="100%" class="summary">
	
	<tr>
		<td class="subheader" style="border-left: none;"><?php echo $spText['common']['Keyword']?></td>
		<td class="subheader"><?php echo $spText['common']['Url']?></td>
		<?php if (count($seList) > 0) { ?>
			<td class='subheader'>
				<?php echo  implode("</td><td class='subheader'>", $seList); ?>
			</td>
		<?php }?>
	</tr>
	
	<?php
	// check count of reports
	$colCount = count($seList) + 2; 
	if (count($seList) > 0) {
		
		// loop through the keyword reports
		foreach($repDetailList as $kwId => $list) {

			// lopp through link reports
			$i = 0;
			foreach ($list as $linkId => $listInfo) {
				?>
				<tr>
					<?php
					if (empty($i)) {
						?>
						<td rowspan="<?php echo count($cmpLinkList)?>" class="content" style="border-left: none;"><?php echo $cmpKwdList[$kwId]?></td>
						<?php
					} 
					?>	
					<td class="content"><?php echo $cmpLinkList[$linkId]?></td>
					<?php
					// loop through search engine reports
					foreach ($seList as $searchEngineId => $seName) {
						$mainUrl = PLUGIN_SCRIPT_URL . "&campaign_id=$campaignId&keyword_id=$keywordId&from_time=$fromTime&to_time=$toTime&se_id=$searchEngineId&link_id=$linkId";
						?>
						<td class="contentmid" style="color: #0033CC;">
							<?php
							// check wheteher crawling done for this time
							if (isset($listInfo[$searchEngineId][$toTime])) {

								$fromRank = $listInfo[$searchEngineId][$fromTime];
								$toRank = $listInfo[$searchEngineId][$toTime];

								// check whether crawling failed
								if ($toRank == -1) {
									$rankStr = "<font class='red'>Fail</font>";	
								} else {

									$rankStr = "<b>$toRank</b>";
									$rankStr = scriptAJAXLinkHrefDialog($mainUrl, 'content', "&action=reportDetail", $toRank);

									// check from rank values and find rank diff
									if ( $fromRank <= 0 ) {
										$rankDiff = 0;
									} else {
										$rankDiff = $toRank ? ($fromRank - $toRank) : ($fromRank * -1);
									}
									
									// if rank diff not zero
									if ($rankDiff != 0) {
										$rankStr .= ($rankDiff > 0) ? " <font class='green'>($rankDiff)</font>" : " <font class='red'>($rankDiff)</font>";
									}
									
								}

								
							} else {
								$rankStr = "-";
							}
							
							echo $rankStr;
							$graphLink = scriptAJAXLinkHrefDialog($mainUrl, 'content', "&action=reportGraph", '&nbsp;', 'graphicon');
							echo $graphLink;
							?>
						</td>
						<?php
					} 
					?>
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

<?php 
if (!empty($printVersion)) {
	echo showPrintFooter($spText);
}
?>