<?php echo showSectionHead($spTextPanel['Cron Command']); ?>

<table width="600px" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>
		<th style="text-align: left;font-size: 14px;padding-left: 10px;"><?php echo $pluginText['croncommandtextsubmit']?></th>		
	</tr>
	<tr>
		<td valign="middle">
			<p class="note" style="padding-top: 6px;font-size: 15px;width: 800px;">
			<b>
			<?php
			$command = "0,30 * * * * php ".PLUGIN_PATH."/submittercron.php";
			highlight_string($command); 
			?>
			</b>
			</p>
		</td>
	</tr>
</table>