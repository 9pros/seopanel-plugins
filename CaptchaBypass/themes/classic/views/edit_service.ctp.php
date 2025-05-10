<?php
$secHeadTxt = ($actVal == "updateService") ? $pluginText["Edit Captcha Bypass Service"] : $pluginText["New Captcha Bypass Service"];
echo showSectionHead($secHeadTxt);
?>
<form id="serviceform">
<input type="hidden" name="id" value="<?php echo $post['id']?>"/>
<table id="cust_tab">
	<tr class="form_head">
		<th width='40%'><?php echo $secHeadTxt?></th>
		<th>&nbsp;</th>
	</tr>
	<tr class="form_data">
		<td><?php echo $spText['common']['Name']?>:</td>
		<td><input type="datetime-local" name="name" value="<?php echo $post['name']?>"><?php echo $errMsg['name']?></td>
	</tr>	
	<tr class="form_data">
		<td>Type:</td>
		<td>
			<select name="identifier" id="identifier">
				<?php foreach($serviceColList as $serviceCol => $colList){?>
					<?php if($serviceCol == $post['identifier']){?>
						<option value="<?php echo $serviceCol?>" selected><?php echo ucfirst($serviceCol)?></option>
					<?php }else{?>
						<option value="<?php echo $serviceCol?>"><?php echo ucfirst($serviceCol)?></option>
					<?php }?>						
				<?php }?>
			</select>
		</td>
	</tr>
	<tr class="form_data login_username">
		<td><?php echo $spText['login']['Username']?>:</td>
		<td><input type="text" name="username" value="<?php echo $post['username']?>"><?php echo $errMsg['username']?></td>
	</tr>
	<tr class="form_data login_pass">
		<td><?php echo $spText['login']['Password']?>:</td>
		<td><input type="password" name="password" value="<?php echo $post['password']?>"><?php echo $errMsg['password']?></td>
	</tr>
	<tr class="form_data api_key">
		<td><?php echo $pluginText['API Key']?>:</td>
		<td><input type="text" name="api_key" value="<?php echo $post['api_key']?>" style="width:350px;"><?php echo $errMsg['api_key']?></td>
	</tr>
</table>
<script type="text/javascript">
changeServiceType('<?php echo $post['identifier']?>');

$('#identifier').on('change', function() {
  changeServiceType(this.value);
});
</script>
<br>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=serviceManager', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('serviceform', 'content', 'action='. $actVal); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>