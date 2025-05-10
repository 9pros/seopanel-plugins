<?php echo showSectionHead("Menu Translator"); ?>

<?php if($insertion == 'success'){ ?>
	<div class="alert">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
	Successfully Inserted
	</div> 
<?php } ?>

<?php if($updation == 'success'){ ?>
	<div class="alert">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
	Successfully Updated
	</div> 
<?php } ?>

<table class="report_head" width="100%" align="center">
	<tbody>
		<tr>
			<td valign="bottom" align="left">
				<div><b>Menu Item Name:</b> <?php echo $menu_item_data['name']; ?></div>
			</td>
	</tbody>
</table>


<form id='searching_form'>
<table width="100%" class="search">
	<?php $actFun = pluginPOSTMethod('searching_form', 'content', 'action=menuTranslation'); ?>
	<tr>						
		<th style="width: 140px;">Menu Item: </th>
		<td id="report_area">
			<select name="menu_item_id" onchange="<?php echo $actFun; ?>">
                <?php foreach($menuItemList as $info) {
                    $selected = ($menu_item_data['id'] == $info['id']) ? "selected='selected'" : "";
                	?>
					<option value="<?php echo $info['id'];?>" <?php echo $selected?>><?php echo $info['item_name']; ?></option>
				<?php } ?>
			</select>			
			<a onclick="<?php echo $actFun; ?>" class="actionbut">Search</a>
		</td>
	</tr>
</table>
</form>
<br>

<form id="translationform">
<table id="cust_tab">
	<tr>
		<th style="width: 40px;">Id</th>
		<th>Language</th>
		<th>Translation</th>
	</tr>
	<?php if(!empty($lang)) { ?>
    	<?php $i =1 ; foreach($lang as $key => $value){ ?>
    		<tr class="form_data">
    			<td><?php echo $i; ?></td>
    			<td><?php echo $key;?></td>
    			<td><input class="form-control" type="text" name="translationdata[<?php echo $i-1; ?>][translation]"></td>
    			<input type="hidden" name="translationdata[<?php echo $i-1; ?>][menu_item_id]" value="<?php echo $menu_item_data['id']; ?>">
    			<input type="hidden" name="translationdata[<?php echo $i-1; ?>][lang_code]" value="<?php echo $value; ?>">
    		</tr>
    	<?php $i++;  } ?>
	<?php } else{ ?>
		<?php foreach($data as $k => $val) {?>
			<tr class="form_data">
				<td><?php echo $k+1; ?></td>
				<td><?php echo $val['lang_name'];?></td>
				<td><input class="form-control" type="text" name="translationdata[<?php echo $k; ?>][translation]" value="<?php echo $val['content']?>"></td>
				<input type="hidden" name="translationdata[<?php echo $k; ?>][menu_item_id]" value="<?php echo $menu_item_data['id']; ?>">
				<input type="hidden" name="translationdata[<?php echo $k; ?>][lang_code]" value="<?php echo $val['lang_code']; ?>">
			</tr>
		<?php } ?>
	<?php } ?>
	<input type="hidden" name="menu_item_id" value="<?php echo $menu_item_data['id']; ?>" >
</table>
<br>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=menuitemmanager', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('translationform', 'content', 'action=updatetranslation'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>