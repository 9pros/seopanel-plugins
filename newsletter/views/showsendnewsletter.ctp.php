<?php echo showSectionHead($pluginText['Send Newsletter']); ?>
<p class='note'>
	<?=$pluginText['escapetostop']?>.
	<?=scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=startsendnewsletter&newsletter_id=$newsletterId", $spText['label']['Click Here'])?> <?=$pluginText['tostartsendingnewsletter']?>.
</p>
<div id="run_report">
	<div id="subcontmed">
		<script>
		    <?php echo pluginGETMethod('action=dosendnewsletter&newsletter_id='.$newsletterId, 'subcontmed'); ?>
		</script>
	</div>
</div>