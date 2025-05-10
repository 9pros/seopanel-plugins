<?php
if ($completed == 1) {
	$reportLink = pluginGETMethod('action=report&keyword_id=0&campaign_id=' . $campaignInfo['id'], 'content');
	$reportLink = "<a href='javascript:void(0);' onclick=\"$reportLink\">{$spText['label']['Click Here']}</a>";
	showSuccessMsg($pluginText['Completed campaign execution']."! $reportLink ".$spTextSA['to view the reports'], false);
} else {
    $delay = 30 * 1000;
    echo "<b>{$pluginText['crawledsuccesssfullywaitfornext']} ".($delay/1000)." seconds</b>";    
    ?>
	<script>
 		setTimeout("<?php echo  pluginGETMethod('action=runCampaign&campaign_id=' . $campaignInfo['id'], 'subcontmed'); ?>", <?php echo $delay?>);
    </script>
	<?php
}
?>
	