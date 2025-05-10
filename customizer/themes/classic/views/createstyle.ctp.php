<?php echo showSectionHead("New Style"); ?>

<form id="menuform">
	<table id="cust_tab">
		<tr class="form_head">
			<th width='30%'>New Style</th>
			<th>&nbsp;</th>
		</tr>

		<tr class="form_data">
			<td>Type:</td>
			<td>
    			<select name="type">
                    <?php foreach($typeList as $value) {?>
    					<option value="<?php echo $value;?>" <?php if($data['type'] == $value){ ?> selected <?php }?> ><?php echo strtoupper($value); ?></option>
    				<?php } ?>
    			</select><?php echo $errMsg['type']?>
        	</td>
		</tr>
		<tr class="form_data">
			<td>Theme:</td>
			<td><select name="theme_id" id="theme_id">
                <?php foreach($theme_data as $key => $value) {?>
					<option value="<?php echo $value['id'];?>"><?php echo $value['name']; ?></option>
				<?php } ?>
			</select><?php echo $errMsg['theme_id']?>
        	</td>
		</tr>
		<tr class="form_data">
			<td>Name:</td>
			<td><input type="text" name="name" value="<?php echo $data['name']?>"><?php echo $errMsg['name']; ?></td>
		</tr>

		<tr class="form_data">
			<td>Style Content:</td>
			<td><textarea class="form-control" rows="30" name="style_content"><?php echo $data['style_content']?></textarea><?php echo $errMsg['style_content']; ?></td>
		</tr>

		<tr class="form_data">
			<td>Priority:</td>
			<td><input type="number" name="priority"
				value="<?php echo isset($data['priority']) ? $data['priority'] : 100?>"><?php echo $errMsg['priority']?></td>
		</tr>

	</table>
	<br>
	<table width="100%" class="actionSec">
		<tr>
			<td style="padding-top: 6px; text-align: right;"><a
				onclick="<?php echo pluginGETMethod('action=stylemanager', 'content')?>"
				href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('menuform', 'content', 'action=insertstyle'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);"
				class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a></td>
		</tr>
	</table>
</form>