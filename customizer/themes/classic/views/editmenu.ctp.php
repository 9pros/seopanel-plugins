<?php echo showSectionHead("Edit Menu"); ?>

<form id="menuform">
<input type="hidden" name="menu_id" value="<?php echo $menu_data['id'];?>">
<table id="cust_tab">
	<tr class="form_head">
		<th width="40%">Edit Menu</th>
		<th>&nbsp;</th>
	</tr>
	<tr class="form_data">
		<td>Name:</td>
		<td><?php echo $menu_data['label']?></td>
	</tr>
	<tr class="form_data">
		<td>Background Color Style:</td>
		<td>
			<select name="bg_color">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach ($navBarBg as $bgClass) {?>
					<option value="<?php echo $bgClass?>" <?php if($bgClass == $menu_data['bg_color']){ ?> selected <?php }?>>
						<?php echo ucfirst(str_replace('bg-', '', $bgClass))?>
					</option>
				<?php }?>
			</select>
		</td>
	</tr>
	<tr class="form_data">
		<td>Font Color Style:</td>
		<td>
			<select name="font_color">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach ($navBarColor as $colorClass => $color) {?>
					<option value="<?php echo $colorClass?>" <?php if($colorClass == $menu_data['font_color']){ ?> selected <?php }?>>
						<?php echo $color?>
					</option>
				<?php }?>
			</select>
	</tr>
</table>
<br>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=menumanager', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('menuform', 'content', 'action=updatemenudetails'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>


