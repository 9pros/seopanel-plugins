<?php 
$headMsg = ($sec == 'update') ? $pluginText['Edit Campaign'] : $pluginText['New Campaign'];
echo showSectionHead($headMsg);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'content', 'action='.$sec.'Campaign');
?>
<form id="projectform">
<?php if ($sec == 'update') {?>
	<input type="hidden" name="id" value="<?=$post['id']?>"/>
<?php }?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?=$headMsg?></td>
		<td class="right">&nbsp;</td>
	</tr>		
	<tr class="white_row">
		<td class="td_left_col"><?=$spText['common']['Website']?>:*</td>
		<td class="td_right_col">
			<select name="website_id" id="website_id">
				<?php foreach($websiteList as $websiteInfo){?>
					<?php if($websiteInfo['id'] == $post['website_id']){?>
						<option value="<?=$websiteInfo['id']?>" selected><?=$websiteInfo['name']?></option>
					<?php }else{?>
						<option value="<?=$websiteInfo['id']?>"><?=$websiteInfo['name']?></option>
					<?php }?>
				<?php }?>
			</select>
			<?=$errMsg['website_id']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$spText['common']['Name']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="campaign_name" value="<?=$post['campaign_name']?>"><?=$errMsg['campaign_name']?>
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['From Name']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="from_name" value="<?=$post['from_name']?>"><?=$errMsg['from_name']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['From Email']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="from_email" value="<?=$post['from_email']?>"><?=$errMsg['from_email']?>
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Reply To Email']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="reply_to" value="<?=$post['reply_to']?>"><?=$errMsg['reply_to']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Enable SMTP']?>:</td>
		<td class="td_right_col">
			<input type="checkbox" name="is_smtp" value="1" <?=$disableSMTPCheck?> <?=$checkSMTP?>>
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['SMTP Host']?>:</td>
		<td class="td_right_col">
			<input type="text" name="smtp_host" value="<?=$post['smtp_host']?>"><?=$errMsg['smtp_host']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['SMTP Username']?>:</td>
		<td class="td_right_col">
			<input type="text" name="smtp_username" value="<?=$post['smtp_username']?>"><?=$errMsg['smtp_username']?>
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['SMTP Password']?>:</td>
		<td class="td_right_col">
			<input type="password" name="smtp_password" value="<?=$post['smtp_password']?>"><?=$errMsg['smtp_password']?>
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
    		<a onclick="<?=pluginGETMethod('&website_id='.$post['website_id'], 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Cancel']?>
         	</a>&nbsp;         	
         	<a onclick="<?=$actFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>