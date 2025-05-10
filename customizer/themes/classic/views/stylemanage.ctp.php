<?php echo showSectionHead("Style Manager"); ?>

<form id='searching_form'>
<table class="search">
	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginPOSTMethod('searching_form', 'content', 'action=stylemanager'); ?>
	<tr>						
		<th style="width: 140px;">Theme: </th>
		<td>
			<select name="theme_id">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
                <?php foreach($theme_data as $key => $value) {?>
					<option value="<?php echo $value['id'];?>" <?php if($data['theme_id'] == $value['id']){ ?> selected <?php }?>><?php echo $value['name']; ?></option>
				<?php } ?>
			</select>
		</td>					
		<th>Type: </th>
		<td>
			<select name="type">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
                <?php foreach($typeList as $value) {?>
					<option value="<?php echo $value;?>" <?php if($data['type'] == $value){ ?> selected <?php }?> ><?php echo strtoupper($value); ?></option>
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
		sortList: [[1,0]]
    });
});
</script>

<table id="cust_tab" class="tablesorter">
	<thead>
	<tr>
		<th>Id</th>
		<th>Name</th>
		<th>Type</th>
		<th>Theme</th>
		<th>Priority</th>
        <th>Status</th>
        <th>Action</th>
	</tr>
	</thead>
	
	<tbody>
	<?php
	if(count($styleManagerData) > 0) {
		foreach($styleManagerData as $i => $value){
			$itemLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editstyle&style_id={$value['id']}", "{$value['name']}");
			?>
			<tr>
				<td width="40px"><?php echo $i+1;?></td>
				<td><?php echo $itemLink;?></td>
				<td><?php echo $value['type']?></td>
				<td><?php echo $value['themename']?></td>
                <td><?php echo $value['priority'];?></td>
                <td><?php if($value['status'] == '0') {?>Inactive <?php }else{ ?> Active <?php } ?> </td>
				<td width="100px">
					<select name="action" id="action<?php echo $value['id']?>" onchange="doCUST_PluginAction('<?php echo PLUGIN_SCRIPT_URL?>', 'content', 'style_id=<?php echo $value['id']?>', 'action<?php echo $value['id']?>')">
						<option value="">-- <?php echo $spText['common']['Select']?> --</option>
						<option <?php if($value['status'] == '0') { ?> value="activatestyle" <?php } else{ ?> value="deactivatestyle" <?php } ?> ><?php if($value['status'] == '0') {?> Activate <?php }else{ ?> Inactivate <?php } ?> </option>
					    <option value="editstyle">Edit</option>
					    <option value="deletestyle">Delete</option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{
		?>
		<tr><td colspan="7"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php
	} 
	?>
	</tbody>
</table>
<br>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a onclick="<?php echo pluginGETMethod('action=newstyle', 'content')?>" href="javascript:void(0);" class="actionbut">
         		New Style
         	</a>
    	</td>
	</tr>
</table>