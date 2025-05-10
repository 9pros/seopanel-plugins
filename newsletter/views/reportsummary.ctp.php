<?php 
$scriptPath = PLUGIN_SCRIPT_URL . "&action=reportmanager&report_type=report_email&newsletter_id=$newsletterId";
$scriptPath .= "&back_report_type=report_summary&back_from_time=$fromTime&back_to_time=$toTime&from_time=$fromTime&to_time=$toTime";
?>
<table width="90%" cellspacing="0" cellpadding="0px" border="0"
	align="center" class="summary_tab">
	<tbody>
		<tr>
			<td colspan="6" class="topheader"><?=$spTextSA['Report Summary']?></td>
		</tr>
		<tr>
			<th class="leftcell"><?=$spText['common']['Total']?>:</th>
			<td><?=$listInfo['total']?></td>
			<th><?=$pluginText['Sent']?>:</th>
			<td><?php echo scriptAJAXLinkHref($scriptPath, 'content', "&sent_status=", $listInfo['sent']);?></td>
			<th><?=$pluginText['Success']?>:</th>
			<td><font class="success"><?php echo scriptAJAXLinkHref($scriptPath, 'content', "&sent_status=success", $listInfo['success']);?></font></td>
		</tr>
		<tr>
			<th class="leftcell"><?=ucfirst($spText['common']['failed'])?>:</th>
			<td><font class="error"><?php echo scriptAJAXLinkHref($scriptPath, 'content', "&sent_status=failed", $listInfo['failed']);?></font></td>
			<th><?=$pluginText['Opened']?>:</th>
			<td><?php echo scriptAJAXLinkHref($scriptPath, 'content', "&sent_status=opened", $listInfo['opened']);?></td>
			<th><?=$pluginText['Clicked']?>:</th>
			<td><?php echo scriptAJAXLinkHref($scriptPath, 'content', "&sent_status=clicked", $listInfo['click_count']);?></td>
		</tr>
	</tbody>
</table>
