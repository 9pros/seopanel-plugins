<?php 
$headMsg = ($sec == 'update') ? $pluginText['Edit Email List'] : $pluginText['New Email List'];
echo showSectionHead($headMsg);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'content', 'action='.$sec.'EL');
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
		<td class="td_left_col"><?=$spText['common']['Name']?>:*</td>
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
    		<a onclick="<?=pluginGETMethod('action=managerEL', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Cancel']?>
         	</a>&nbsp;         	
         	<a onclick="<?=$actFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>