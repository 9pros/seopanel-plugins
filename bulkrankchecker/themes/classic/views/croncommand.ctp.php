<?php echo  showSectionHead($spTextPanel['Cron Command']); ?>

<table class="search">
	<tr>
		<th style="text-align: left;font-size: 14px;padding-left: 10px;"><?php echo $spTextPanel['Add following command to your cron tab']?></th>		
	</tr>
	<tr>
		<td valign="middle">
			<p class="note" style="padding-top: 6px;font-size: 15px;width: 800px;">
			<b>
			<?php
			$command = "*/20 * * * * php ". PLUGIN_PATH."/cron.php";
			highlight_string($command); 
			?>
			</b>
			</p>
		</td>
	</tr>
</table>