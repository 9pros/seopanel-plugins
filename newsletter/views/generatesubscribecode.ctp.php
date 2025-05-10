<?php 
$headMsg = $pluginText['Generate Subscribe Code'];
echo showSectionHead($headMsg);
$actFun = pluginPOSTMethod('projectform', 'subcontent', 'action=dogensubscribecode');
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
		<td class="td_left_col"><?=$spText['common']['lang']?>:</td>
		<td class="td_right_col">
			<?php echo $this->render('language/languageselectbox', 'ajax'); ?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Source']?>:</td>
		<td class="td_right_col">
			<input type="text" name="source" value="<?=$post['source']?>"><?=$errMsg['source']?>
			<p>The value to identify the source from user subscribed</p>
			<P>Eg: website, blog, forum etc</P>
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Width']?>:</td>
		<td class="td_right_col">
			<input type="text" name="width" value="<?=$post['width']?>"><?=$errMsg['width']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Height']?>:</td>
		<td class="td_right_col">
			<input type="text" name="height" value="<?=$post['height']?>"><?=$errMsg['height']?>
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Background Colour Code']?>:</td>
		<td class="td_right_col">
			<input type="text" name="bgcolor" value="<?=$post['bgcolor']?>"><?=$errMsg['bgcolor']?>
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
<div id="subcontent" style="margin-top: 30px;text-align: center;"></div>