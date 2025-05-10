<?php echo showSectionHead($pluginText['Generate Report']); ?>
<p class='note'>
	<?php echo $pluginText['escapetostop']?>.
	<?php echo scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=runreport&report_id=$reportId", $spText['label']['Click Here'])?> <?php echo $pluginText['tostartreportgeneration']?>.
</p>
<div id="run_report">
	<div id="run_info">
		<script>
			<?php echo pluginGETMethod('action=runinfo&report_id='.$reportId, 'run_info'); ?>
		</script>
	</div>
	<div id="subcontmed">
		<div id="loading_subcontmed"></div>
		<script>
			setTimeout('scriptDoLoad(\'<?php echo PLUGIN_SCRIPT_URL?>&action=generatereport&report_id=<?php echo $reportId?>\', \'subcontmed\')', 5000);
		</script>
	</div>
</div>