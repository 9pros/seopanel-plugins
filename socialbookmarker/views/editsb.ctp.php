<?php echo showSectionHead($pluginText['Edit Social Bookmarker']); ?>
<form id="sbform">
<input type="hidden" name="id" value="<?=$post['id']?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?=$plugintext['Edit Social Bookmarker']?></td>
		<td class="right">&nbsp;</td>
	</tr>		
	<tr class="white_row">
		<td class="td_left_col"><?=$spText['common']['Rank']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="rank" value="<?=$post['rank']?>" class="small"><?=$errMsg['rank']?>
			<p>Note: <?=$pluginText['ranknote']?></p>
		</td>
	</tr>		
	<tr class="blue_row">
		<td class="td_left_col"><?=$spText['common']['Name']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="engine_name" value="<?=$post['engine_name']?>"><?=$errMsg['engine_name']?>
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Submit Link']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="engine_submit_link" value="<?=$post['engine_submit_link']?>" class="large"><?=$errMsg['engine_submit_link']?>
			<p>Eg: http://www.blinklist.com/?Action=Blink/addblink.php&Description=[[description]]&Url=[[url]]&Title=[[title]]&Tag=[[tags]]</p>
			<p>Note:[[url]],[[title]],[[description]] and [[tags]] are replaced with Url,Title,Description and Tags of project.</p>
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Register Link']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="engine_register_link" value="<?=$post['engine_register_link']?>" class="large"><?=$errMsg['engine_register_link']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Login Link']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="engine_login_link" value="<?=$post['engine_login_link']?>" class="large"><?=$errMsg['engine_login_link']?>
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Show in iframe']?>:*</td>
		<td class="td_right_col">
			<?php $selected = ($post['iframe'] == 0) ? "selected" : ""; ?>
			<select name="iframe">
				<option value="1"><?=$spText['common']['Yes']?></option>
				<option value="0" <?=$selected?>><?=$spText['common']['No']?></option>
			</select>
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
    		<a onclick="<?=pluginGETMethod('&action=showsbmanager', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginPOSTMethod('sbform', 'content', 'action=updatesb'); ?>
         	<a onclick="<?=$actFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>