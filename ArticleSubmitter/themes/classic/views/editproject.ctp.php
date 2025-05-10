<?php 
$headMsg = ($sec == 'update') ? $pluginText['Edit Project'] : $pluginText['New Project'];
echo showSectionHead($headMsg);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'content', 'action='.$sec.'Project');
?>
<form id="projectform" onsubmit="<?php echo $actFun?>;return false;">
<?php if ($sec == 'update') {?>
	<input type="hidden" name="id" value="<?php echo $post['id']?>"/>
<?php }?>

<table id="cust_tab">
	<tr class="form_head">
		<th class="left" width='30%'><?php echo $headMsg?></th>
		<th class="right">&nbsp;</th>
	</tr>
	<?php if(!empty($isAdmin)){ ?>	
		<tr class="form_data">
			<td class="td_left_col"><?php echo $spText['common']['User']?>:</td>
			<td class="td_right_col">
				<select name="user_id">
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
	<tr class="form_data">
		<td class="td_left_col"><?php echo $spText['label']['Project']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="project_name" value="<?php echo $post['project_name']?>"><?php echo $errMsg['project_name']?>
		</td>
	</tr>	
</table>

<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=index', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>