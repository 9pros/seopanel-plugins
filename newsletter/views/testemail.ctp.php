<?php 
$headMsg = $pluginText['Newsletter Test Email'];
echo showSectionHead($headMsg);
?>
<form id="projectform">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?=$headMsg?></td>
		<td class="right">&nbsp;</td>
	</tr>		
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Newsletter']?>:*</td>
		<td class="td_right_col">
			<?php echo $this->getPluginViewContent('newsletterselectbox'); ?>
            <?=$errMsg['newsletter_id']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$spText['login']['Email']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="email" id='email' value="<?=$post['email']?>"><span id='email_error'></span>
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
    		<a onclick="<?=pluginGETMethod('action=newslettermanager&campaign_id='.$campaignId, 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'subcontent', 'action=sendtestemail'); ?>
         	<a onclick="tinyMCE.triggerSave(true,true);<?=$actFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>
<div id="subcontent"></div>