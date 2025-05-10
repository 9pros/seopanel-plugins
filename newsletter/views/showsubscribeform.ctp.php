<div id="newsletter_subscribe" style="text-align: center;">
	<form name="subscribe_form1" id='subscribe_form1' onsubmit="scriptDoLoadPost('<?=PLUGIN_WEBPATH?>/subscribemaillist.php', 'subscribe_form1', 'subscribe_out');return false;">
		<input type="hidden" name="email_list_id" value="<?=$post['email_list_id']?>">
		<input type="hidden" name="source" value="<?=$post['source']?>">
		<table width="100%" cellpadding="0" cellspacing="0px" class="actionForm">
			<tr><th style="text-align: left;font-weight: bold;"><?=$pluginText['Your email address']?>:</th></tr>
			<tr>
    			<td>
    				<?php 
    				$width = intval($post['width'] * 0.75);
    				$width = ($width < 162) ? 162 : $width; 
    				?>        				
    				<input type="text" name="subscribe_email" id="subscribe_email" style="height:22px;width:<?=$width?>px;">
    			</td>
			</tr>
			<tr><td id="subscribe_out" style="text-align: left;"></td></tr>
			<tr>
    			<td class="actionsBox">
    				<input class="button" type="submit" value="<?=$pluginText['Subscribe Newsletter']?>">
    			</td>
			</tr>
		</table>
	</form>
</div>