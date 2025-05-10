<?php echo showSectionHead($spTextPanel['Cron Command']); ?>

<table class="search">
	<tr>
		<th style="text-align: left;font-size: 14px;padding-left: 10px;"><?php echo $pluginText['croncommandtextsubmit']?></th>		
	</tr>
	<tr>
		<td valign="middle">
			<p class="note" style="padding-top: 6px;font-size: 15px;width: 800px;">
			<b>
			<?php
			$command = "0 * * * * php ".PLUGIN_PATH."/socialmediaenginecron.php";
			highlight_string($command); 
			?>
			</b>
			</p>
		</td>
	</tr>
</table>