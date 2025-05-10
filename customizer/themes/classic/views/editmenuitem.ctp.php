<?php echo showSectionHead("Edit Menu Item"); ?>

<form id="menuform">
	<input type="hidden" name="menu_item_id"
		value="<?php echo $menu_item_data['id'];?>">
	<table id="cust_tab">
		<tr class="form_head">
			<th width='30%'>Edit Menu Item</th>
			<th>&nbsp;</th>
		</tr>

		<tr class="form_data">
			<td>Menu:</td>
			<td><select name="menu_id" id="menu_id">
                <?php foreach($menu_data as $key => $value) {?>
				<option value="<?php echo $value['id'];?>"
						<?php if($value['id'] == $menu_item_data['menu_id'] ){?> selected
						<?php }?>><?php echo $value['label']; ?></option>
				<?php } ?>
			</select><?php echo $errMsg['menu_id']?>
        </td>
		</tr>
		<tr class="form_data">
			<td>Name:</td>
			<td><input type="text" name="name" class="form-control"
				<?php if($errMsg['name'] != ''){ ?>
				value="<?php echo $data['name']; ?>" <?php }else{ ?>
				value="<?php echo $menu_item_data['name']?>" <?php } ?>><?php echo $errMsg['name'];?></td>
		</tr>
		<tr class="form_data">
			<td>Url:</td>
			<td><input class="form-control" type="text" name="url" <?php if($errMsg['url'] != ''){ ?>
				value="<?php echo $data['url']; ?>" <?php }else{ ?>
				value="<?php echo $menu_item_data['url']?>" <?php } ?>><?php echo $errMsg['url']?></td>
		</tr>
		<tr class="form_data">
			<td>Float Type:</td>
			<td><select name="float_type" id="float_type">
				<?php if($menu_item_data['float_type'] == 'right'){ ?>
					<option value="right" selected>Right</option>
					<option value="left">Left</option>
				<?php }else{ ?>
					<option value="right">Right</option>
					<option value="left" selected>Left</option>
				<?php } ?>
			</select></td>
		</tr>
		<tr class="form_data">
			<td>Priority:</td>
			<td><input type="number" name="priority"
				<?php if($errMsg['priority'] != ''){ ?>
				value="<?php echo $data['priority']; ?>" <?php }else{ ?>
				value="<?php echo $menu_item_data['priority']?>" <?php } ?>><?php echo $errMsg['priority']?></td>
		</tr>
		<tr class="form_data">
			<td>Window Target:</td>
			<td><select name="window_target" id="window_target">
			<?php if($menu_item_data['window_target'] == 'new_tab'){ ?>
                <option value="new_tab" selected>New Tab</option>
					<option value="same_window">Same Window</option>
			<?php }else{ ?>
                <option value="same_window" selected>Same Window</option>
					<option value="new_tab">New Tab</option>
			<?php } ?>
			</select></td>
		</tr>

	</table>
	<br>
	<table width="100%" class="actionSec">
		<tr>
			<td style="padding-top: 6px; text-align: right;"><a
				onclick="<?php echo pluginGETMethod('action=menuitemmanager', 'content')?>"
				href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('menuform', 'content', 'action=updatemenuitem'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);"
				class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a></td>
		</tr>
	</table>
</form>