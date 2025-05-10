<?php

// check for action
if ($actVal == 'updateEmailTemplate') {
	$headMsg = $pluginText['Edit Email Template']; 
} else {
	$headMsg = 'Create Email Template';
}

echo showSectionHead($headMsg);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'content', "action=$actVal");
?>
<form id="projectform">
	<?php if ($actVal == 'updateEmailTemplate') {?>
		<input type="hidden" name="id" value="<?php echo $post['id']?>"/>
	<?php }	?>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
		<tr class="listHead">
			<td class="left" width='30%'><?php echo $headMsg?></td>
			<td class="right">&nbsp;</td>
		</tr>
		<?php if ($actVal == 'createEmailTemplate') {?>
			<tr class="white_row">
				<td class="td_left_col"><?php echo $spText['common']['Name']?>:*</td>
				<td class="td_right_col">
					<input type="text" name="name" value="<?php echo $post['name']?>"><?php echo $errMsg['name']?>
				</td>
			</tr>
		<?php }	?>
		<tr class="blue_row">
			<td class="td_left_col"><?php echo $spText['label']['Subject']?>:*</td>
			<td class="td_right_col">
				<input type="text" name="email_subject" value="<?php echo $post['email_subject']?>"><?php echo $errMsg['email_subject']?>
			</td>
		</tr>
		<tr class="white_row">
			<td class="td_left_col"><?php echo $spText['label']['Email Body']?>:*</td>
			<td class="td_right_col">
				<textarea name="email_content" style='width:500px' rows='20'><?php echo $post['email_content']?></textarea><?php echo $errMsg['email_content']?>
				<p>[FIRST_NAME] - will be replaced with user first name</p>
				<p>[LAST_NAME] - will be replaced with user last name</p>
				<p>[USERNAME] - will be replaced with username</p>
				<p>[EMAIL] - will be replaced with user email</p>
				<p>[EXPIRY_DATE] - will be replaced with user expiry date</p>
				<p>[RENEW_LINK] - will be replaced with membership renew link</p>
			</td>
		</tr>	
		<tr class="blue_row">
			<td class="tab_left_bot_noborder"></td>
			<td class="tab_right_bot"></td>
		</tr>
		<tr class="listBot">
			<td class="left" colspan="1"></td>
			<td class="right"></td>
		</tr>
	</table>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="actionSec">
		<tr>
	    	<td style="padding-top: 6px;text-align:right;">
	    		<a onclick="<?php echo pluginGETMethod('&action=emailTemplateManager', 'content')?>" href="javascript:void(0);" class="actionbut">
	         		<?php echo $spText['button']['Cancel']?>
	         	</a>&nbsp;         	
	         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
	         		<?php echo $spText['button']['Proceed']?>
	         	</a>
	    	</td>
		</tr>
	</table>
</form>