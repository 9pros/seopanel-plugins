<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<?php echo  $this->getViewContent('email/emailhead'); ?>    
<body>
<?php echo $reportTexts['report_email_body_text1']?><br><br>

<table width="30%" border="0" cellspacing="0" cellpadding="0" class="search">
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

<?php echo  $reportContent; ?>

<br><br><br><br>
<?php 
$reportLink = str_replace("pid=", "sec=show&menu_selected=", PLUGIN_SCRIPT_URL) . "&action=report&campaign_id=$campaignId&keyword_id=0";
$showText = explode(", ", $reportTexts['report_email_body_text2']);
$showText = empty($showText[1]) ? $reportTexts['report_email_body_text2'] : $showText[1];
echo str_replace('[LOGIN_LINK]', "<a href='$reportLink'>{$loginTexts['Login']}</a>", $showText); ?><br><br>

<table cellspacing="0" cellpadding="0" width="100%">
	<tbody>
		<tr style="height: 11px;">
			<td style="vertical-align: middle; margin: 0pt;" colspan="2">
			<hr
				style="margin: 5px 0pt; background-color: rgb(0, 0, 0); color: rgb(0, 0, 0); height: 1px;">
			</td>
		</tr>
	</tbody>
</table>
</body>
</html>