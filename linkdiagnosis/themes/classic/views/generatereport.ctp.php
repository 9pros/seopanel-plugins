<?php
$waitSeconds = LD_CRAWL_DELAY_BACKLINKS / 1000;
?>
<div id="wait_div"><?php echo $msg?> for <?php echo $waitSeconds?> seconds...</div>
<script>
	setTimeout('scriptDoLoad(\'<?php echo PLUGIN_SCRIPT_URL?>&action=generatereport&report_id=<?php echo $reportId?>\', \'subcontmed\')', <?php echo LD_CRAWL_DELAY_BACKLINKS?>);
</script>
