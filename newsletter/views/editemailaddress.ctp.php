<?php 
$headMsg = $pluginText['Edit Email Address'];
echo showSectionHead($headMsg);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'content', 'action='.$sec.'Email');
?>
<form id="projectform" onsubmit="<?=$actFun?>;return false;">
<?php if ($sec == 'update') {?>
	<input type="hidden" name="id" value="<?=$post['id']?>"/>
<?php }?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?=$headMsg?></td>
		<td class="right">&nbsp;</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Email List']?>:*</td>
		<td class="td_right_col">
			<select name="email_list_id" onchange="<?=$submitLink?>">
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
	<tr class="white_row">
		<td class="td_left_col"><?=$spText['login']['Email']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="email" value="<?=$post['email']?>"><?=$errMsg['email']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$spText['common']['Name']?>:</td>
		<td class="td_right_col">
			<input type="text" name="name" value="<?=$post['name']?>"><?=$errMsg['name']?>
		</td>
	</tr>
	<tr class="white_row">
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
    		<a onclick="<?=pluginGETMethod('action=managerEmail&email_list_id='.$post['email_list_id'], 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Cancel']?>
         	</a>&nbsp;         	
         	<a onclick="<?=$actFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>