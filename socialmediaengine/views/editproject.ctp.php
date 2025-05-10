<?php 
$headMsg = ($sec == 'update') ? $spTextPanel["Edit Project"] : $spTextPanel["New Project"];
echo showSectionHead($headMsg);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'content', 'action='.$sec.'Project');
?>
<form id="projectform" onsubmit="<?php echo $actFun?>;return false;">
<?php if ($sec == 'update') {?>
	<input type="hidden" name="id" value="<?php echo $post['id']?>"/>
<?php }?>

<table width="100%" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?php echo $headMsg?></td>
		<td class="right">&nbsp;</td>
	</tr>
	<?php if(!empty($isAdmin)){ ?>	
		<tr class="blue_row">
			<td class="td_left_col"><?php echo $spText['common']['User']?>:</td>
			<td class="td_right_col">
				<select name="user_id" style="width:150px;">
					<?php foreach($userList as $userInfo){?>
						<?php if($userInfo['id'] == $userSelected){?>
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
		<td class="td_left_col"><?php echo $spText['common']['Name']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="project" value="<?php echo $post['project']?>"><?php echo $errMsg['project']?>
		</td>
	</tr>
</table>
<table class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>