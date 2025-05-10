<?php echo  showSectionHead($pluginText["Run Campaign"]); ?>
<div id="run_project">
	<div id="run_info" style="height: 110px;">
		<table width="100%" class="summary_tab">
        	<tr>
        		<td class="topheader" colspan="10"><?php echo $pluginText['Run Campaign']?></td>
        	</tr>
        	<tr>
        		<th class="leftcell" width="20%"><?php echo $pluginText['Campaign']?>:</th>
        		<td width="40%" style="text-align: left;"><?php echo $campaignInfo['name']?></td>
        		<th width="20%"><?php echo $spText['label']['Keywords']?>:</th>
        		<td><?php echo $campaignInfo['keyword_count']?></td>
        	</tr>
        	<tr>
        		<th class="leftcell"><?php echo $pluginText['Crawling Keyword']?>:</th>
        		<td style="text-align: left;" id="crawling_keyword"><?php echo $campaignInfo['crawling_keyword']?></td>
        		<th><?php echo $pluginText['Crawled Keywords']?>:</th>
        		<td id="crawled_keywords"><?php echo $campaignInfo['crawled_keyword_count']?></td>
        	</tr>
        </table>
	</div>
	<div id="subcontmed">
		<?php 
		if ($completed) {
			$reportLink = pluginGETMethod('action=report&keyword_id=0&campaign_id=' . $campaignInfo['id'], 'content');
			$reportLink = "<a href='javascript:void(0);' onclick=\"$reportLink\">{$spText['label']['Click Here']}</a>";
			showSuccessMsg($pluginText['Completed campaign execution']."! $reportLink ".$spTextSA['to view the reports'], false);
		} else {
			?>
			<script><?php echo  pluginGETMethod('action=runCampaign&campaign_id=' . $campaignInfo['id'], 'subcontmed'); ?></script>
			<?php 
		}
		?>
	</div>
</div>