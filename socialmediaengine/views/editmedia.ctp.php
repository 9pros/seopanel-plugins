<?php
$headMsg = ($sec == 'update') ? $pluginText['Edit Media'] : $pluginText['New Media'];
echo showSectionHead($headMsg);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('mediaform', 'content', 'action='.$sec.'media');
?>
<form id="mediaform">
<?php if ($sec == 'update') {?>
	<input type="hidden" name="id" value="<?php echo $post['id']?>"/>
<?php }?>
<table class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?php echo $headMsg?></td>
		<td class="right">&nbsp;</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $pluginText['Media Name']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="engine_name" value="<?php echo $post['engine_name']?>" readonly="readonly" class="form-control">
		</td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $pluginText['Media Url']?>:*</td>
		<td class="td_right_col">
			<input type="text" id='weburl' name="engine_website" value="<?php echo $post['engine_website']?>" readonly="readonly" class="form-control">
		</td>
	</tr>
	
	<?php
	$inputType = SP_DEMO ? "password" : "text"; 
	foreach ($post['api_list'] as $apiInfo) {
		$apiInfo['api_key_value'] = SP_DEMO ? "password" : $apiInfo['api_key_value']; 
	    ?>
        <tr>
            <td class="td_left_col"><?php echo $apiInfo['api_key_label']?>:*</td>
            <td class="td_right_col">
            	<input type="<?php echo $inputType?>" name="<?php echo $apiInfo['api_key_name']?>" value="<?php echo $apiInfo['api_key_value']?>" class="form-control">
            	<br><?php echo $errMsg[$apiInfo['api_key_name']]?>
            </td>
		</tr>
	<?php }?>
	<tr>
		<td>Callback URL:</td>
		<td>
			<?php
			echo PLUGIN_WEBPATH . "/" . SME_CALLBACK_PAGE;
			?>
			<p style="padding-left: 0px; margin-top: 5px;">Note: Set it in your <?php echo $post['engine_name']?> app settings.</p>
		</td>
	</tr>
	
	<!-- <tr class="blue_row">
		<td class="td_left_col"><?php echo $spText['common']['help']?>:</td>
		<td class="td_right_col" width="60%"><pre class="help_text"><?php echo $pluginText[$post['engine_name'].'_help_text']?></pre></td>
	</tr> -->
</table>
<table class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=listSocialMedia', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form> 