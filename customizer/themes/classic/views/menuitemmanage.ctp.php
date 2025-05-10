<?php echo showSectionHead("Menu Item Manager"); ?>
<form id='searching_form'>
<table width="100%" class="search">
	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginPOSTMethod('searching_form', 'content', 'action=menuitemmanager'); ?>
	<tr>						
		<th style="width: 140px;">Menu: </th>
		<td id="report_area">
			<select name="menu_id" id="menu_id" onchange="<?php echo $actFun; ?>">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
                <?php foreach($menu_data as $key => $value) {
                	$selected = ($menuId == $value['id']) ? "selected='selected'" : "";
                	?>
					<option value="<?php echo $value['id'];?>" <?php echo $selected?>><?php echo $value['label']; ?></option>
				<?php } ?>
			</select>			
			<a onclick="<?php echo $actFun; ?>" class="actionbut">Search</a>
		</td>
	</tr>
</table>
</form>
<br>

<script type="text/javascript">
$(document).ready(function() { 
    $("table").tablesorter({ 
		sortList: [[7,0]]
    });
});
</script>

<table id="cust_tab" class="tablesorter">
	<thead>
	<tr>
		<th>Id</th>
		<th>Name</th>
		<th>Menu</th>
		<th>Url</th>
		<th>Float Type</th>
		<th>Priority</th>
        <th>Status</th>
        <th>Action</th>
	</tr>
	</thead>
	
	<tbody>
	<?php
	if(count($menu_item_data) > 0) {
		foreach($menu_item_data as $i => $value){
			$itemLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editmenuitem&menu_item_id={$value['id']}", "{$value['name']}");
			?>
			<tr>
				<td width="40px"><?php echo $i+1;?></td>
				<td><?php echo $itemLink;?></td>
				<td><?php echo $menu_data[$value['menu_id']]['label']?></td>
				<td><?php echo $value['url'];?></td>
                <td><?php echo $value['float_type'];?></td>
                <td><?php echo $value['priority'];?></td>
                <td><?php if($value['status'] == '0') {?>Inactive <?php }else{ ?> Active <?php } ?> </td>
				<td width="100px">
					<select name="action" id="action<?php echo $value['id']?>" onchange="doCUST_PluginAction('<?php echo PLUGIN_SCRIPT_URL?>', 'content', 'menu_item_id=<?php echo $value['id']?>', 'action<?php echo $value['id']?>')">
						<option value="">-- <?php echo $spText['common']['Select']?> --</option>
						<option <?php if($value['status'] == '0') { ?> value="activatemenuitem" <?php } else{ ?> value="deactivatemenuitem" <?php } ?> ><?php if($value['status'] == '0') {?> Activate <?php }else{ ?> Inactivate <?php } ?> </option>
					    <option value="editmenuitem">Edit</option>
						<option value="menutranslation">Menu Translation</option>
					    <option value="deletemenuitem">Delete</option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{
		?>
		<tr><td colspan="9"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php
	} 
	?>
	</tbody>
</table>
<br>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a onclick="<?php echo pluginGETMethod('action=newmenuitem', 'content')?>" href="javascript:void(0);" class="actionbut">
         		New Menu Item
         	</a>
    	</td>
	</tr>
</table>