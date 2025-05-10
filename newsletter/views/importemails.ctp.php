<?php
$headMsg = $pluginText['Import Emails'];
echo showSectionHead($headMsg);
?>
<div id='import_email_div'>
<form id="projectform" name="projectform" target="email_import_frame" action="<?=PLUGIN_SCRIPT_URL?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="action" value="doimportemail">
<input type="hidden" name="pid" value="<?=PLUGIN_ID?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?=$headMsg?></td>
		<td class="right">&nbsp;</td>
	</tr>		
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Email List']?>:*</td>
		<td class="td_right_col">
			<select name="email_list_id" id="email_list_id">
				<?php foreach($elList as $elInfo){?>
					<?php if($elInfo['id'] == $post['email_list_id']){?>
						<option value="<?=$elInfo['id']?>" selected><?=$elInfo['name']?></option>
					<?php }else{?>
						<option value="<?=$elInfo['id']?>"><?=$elInfo['name']?></option>
					<?php }?>
				<?php }?>
			</select>
			<?=$errMsg['email_list_id']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Email Addresses']?>:*</td>
		<td class="td_right_col">
			<textarea name="email_addresses" rows="10" style="width:70%"><?=stripslashes($post['email_addresses'])?></textarea>
			<p>Insert email addresses per line, Just add ',' when name not available with email address</p>
			<p style="font-weight: bold;">Eg:</p>
			<p>smithpatrick@gmail.com,Smith Patrick</p>
			<p>kellythomas@yahoo.com,Kelly Thomas</p>
			<p>johnjames@live.com,</p>
			<p>jean4u@gmail.com,Jean Alwin</p>
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Email Addresses CSV File']?>:</td>
		<td class="td_right_col">
			<input type="file" name="email_csv_file" style="height: 22px;">
			<a href="<?=PLUGIN_WEBPATH?>/sample.csv" target="_blank"><?=$pluginText['Sample CSV File']?></a>
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
    		<a onclick="<?=pluginGETMethod('action=managerEL', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : 'projectform.submit();'; ?>
         	<a onclick="tinyMCE.triggerSave(true,true);<?=$actFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>
</div>
<div><iframe style="border:none;" name="email_import_frame" id="email_import_frame"></iframe></div>