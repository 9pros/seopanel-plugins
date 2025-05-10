<?php echo showSectionHead($pluginText['Edit Project']); ?>
<form id="projectform">
<input type="hidden" name="oldName" value="<?php echo $post['oldName']?>"/>
<input type="hidden" name="id" value="<?php echo $post['id']?>"/>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?php echo $pluginText['Edit Project']?></td>
		<td class="right">&nbsp;</td>
	</tr>
	<?php if(!empty($isAdmin)){ ?>	
		<tr class="blue_row">
			<td class="td_left_col"><?php echo $spText['common']['User']?>:</td>
			<td class="td_right_col">
				<select name="user_id" style="width:150px;">
					<?php foreach($userList as $userInfo){?>
						<?php if($userInfo['id'] == $post['user_id']){?>
							<option value="<?php echo $userInfo['id']?>" selected><?php echo $userInfo['username']?></option>
						<?php }else{?>
							<option value="<?php echo $userInfo['id']?>"><?php echo $userInfo['username']?></option>
						<?php }?>						
					<?php }?>
				</select>
			</td>
		</tr>
	<?php }?>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $spText['common']['Name']?>:</td>
		<td class="td_right_col"><input type="text" name="name" value="<?php echo $post['name']?>"><?php echo $errMsg['name']?></td>
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
    		<a onclick="<?php echo pluginGETMethod('', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'content', 'action=updateproject'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>