<?php
$headMsg = ($sec == 'update') ? $pluginText["Edit Article Directory"] : $pluginText["New Article Directory"];
echo showSectionHead($headMsg);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('Websiteform', 'content', 'action='.$sec.'Website');
?>

<form id="Websiteform" onsubmit="<?php echo $actFun?>;return false;">
<?php if ($sec == 'update') {?>
	<input type="hidden" name="id" value="<?php echo $post['id']?>"/>
<?php }?>

<table id="cust_tab">
	<tr class="form_head">
		<th class="left" width='30%'><?php echo $headMsg?></th>
		<th class="right">&nbsp;</th>
	</tr>
	<tr class="form_data">
		<td class="td_left_col"><?php echo "Type"?>:*</td>
		<td class="td_right_col">
			<select name="type">
				<?php foreach ($scriptType as $type => $typeName) {?>
					<option value="<?php echo $type?>" <?php echo ($type == $post['type']) ? "selected" : "";?> ><?php echo $typeName?></option>
				<?php }?>
	        </select>
	        <?php echo $errMsg['type']?>
		</td>
	</tr>
	<tr class="form_data">
		<td class="td_left_col"><?php echo $pluginText["Article Directory Name"]?>:*</td>
		<td class="td_right_col">
			<input type="text" name="website_name" class="field" value="<?php echo $post['website_name']?>">
			<?php echo $errMsg['website_name']?>
		</td>
	</tr>
    <tr class="form_data">
		<td class="td_left_col"><?php echo $pluginText['Article Directory Url']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="website_url" class="field" value="<?php echo $post['website_url']?>" style="width: 400px;">
			<?php echo $errMsg['website_url']?>
		</td>
	</tr> 
	<?php if (isAdmin()) {?>
    	<tr class="form_data">
    		<td class="td_left_col"><?php echo 'Public'?>:*</td>
    		<td class="td_right_col">
    			<input type="checkbox" <?php echo !empty($post['public']) ?  'checked="checked"' : "";?> value="1" name="public">
        	</td>
    	</tr>
	<?php }?>
	<tr class="form_data">
		<td class="td_left_col"><?php echo $spText['label']['Authentication']?>:*</td>
		<td class="td_right_col"> 
	       	<input type="checkbox" <?php echo !empty($post['authentication']) ? 'checked="checked"' : "";?> value="1" name="authentication">
        </td>
	</tr>  

    <tr class="form_data">
		<td class="td_left_col"><?php echo $spText['login']['Username']?>:*</td>
		<td class="td_right_col">
			<input type="text" class="field" name="username" value="<?php echo $post['username']?>">
			<?php echo $errMsg['username']?>	
		</td>	
	</tr>
	 <tr class="form_data">
		<td class="td_left_col"><?php echo $spText['login']['Password']?>:*</td>
		<td class="td_right_col">
			<?php $passToShow = SP_DEMO ? "******" : $post['password'];?>
			<input type="password" class="field" name="password" value="<?php echo $passToShow;?>">
			<?php echo $errMsg['password']?>
		</td>
	</tr>
</table>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=website', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>