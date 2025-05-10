<?php echo showSectionHead("New Menu Item"); ?>

<form id="menuform">
	<table id="cust_tab">
		<tr class="form_head">
			<th width='30%'>New Menu Item</th>
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
			<td><input type="text" name="name" value="<?php echo $data['name']?>" class="form-control"><?php echo $errMsg['name']; ?></td>
		</tr>
		<tr class="form_data">
			<td>Url:</td>
			<td><input  class="form-control" type="text" name="url" value="<?php echo $data['url']?>"><?php echo $errMsg['url']?></td>
		</tr>
		<tr class="form_data">
			<td>Float Type:</td>
			<td><select name="float_type" id="float_type">
				<?php if($data['float_type'] == 'right'){ ?>
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
				value="<?php echo isset($data['priority']) ? $data['priority'] : 100?>"><?php echo $errMsg['priority']?></td>
		</tr>
		<tr class="form_data">
			<td>Window Target:</td>
			<td><select name="window_target" id="window_target">
			<?php if($data['window_target'] == 'new_tab'){ ?>
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
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('menuform', 'content', 'action=insertMenuItem'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);"
				class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a></td>
		</tr>
	</table>
</form>