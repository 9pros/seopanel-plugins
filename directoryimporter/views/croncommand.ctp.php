<?php echo showSectionHead($spTextPanel['Cron Command']); ?>

<table width="600px" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>
		<th style="text-align: left;font-size: 14px;padding-left: 10px;"><?=$pluginText['croncommandtextcheckstatus']?></th>		
	</tr>
	<tr>
		<td valign="middle">
			<p class="note" style="padding-top: 6px;font-size: 15px;width: 800px;">
			<b>
			<?php
			$command = "0 0 1 * * php ".PLUGIN_PATH."/dircheckercron.php";
			highlight_string($command); 
			?>
			</b>
			</p>
		</td>
	</tr>
	<tr><td><br><br></td></tr>
	<tr>
		<th style="text-align: left;font-size: 14px;padding-left: 10px;"><?=$pluginText['croncommandtextsubmitdircheck']?></th>		
	</tr>
	<tr>
		<td valign="middle">
			<p class="note" style="padding-top: 6px;font-size: 15px;width: 800px;">
			<b>
			<?php
			$command = "0 0 1,15 * * php ".PLUGIN_PATH."/dirsubmitcheckercron.php";
			highlight_string($command); 
			?>
			</b>
			</p>
		</td>
	</tr>
</table>