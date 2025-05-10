<?php echo showSectionHead("Menu Manager"); ?>

<table id="cust_tab">
	<tr>
		<th>Id</th>
		<th>Name</th>
		<th>Background Color Style</th>
		<th>Font Color Style</th>
		<th>Action</th>
	</tr>

	<?php
	if(count($menuManager_data) > 0) {
		foreach($menuManager_data as $i => $value){
			$menuLink = ($value['identifier'] == 'top') ? $value['label'] : scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editmenu&menu_id={$value['id']}", "{$value['label']}");
			?>
			<tr>
				<td width="40px"><?php echo $i+1;?></td>
				<td><?php echo $menuLink;?></td>
				<?php if ($value['identifier'] == 'top') {?>
					<td></td>
					<td></td>
				<?php } else {?>
					<td><div style="width: 25px;" class="<?php echo $value['bg_color']?>">&nbsp;</div></td>
					<td><?php echo $navBarColor[$value['font_color']]?></td>
				<?php }?>
				<td width="100px">
					<select name="action" id="action<?php echo $value['id']?>" onchange="doCUST_PluginAction('<?php echo PLUGIN_SCRIPT_URL?>', 'content', 'menu_id=<?php echo $value['id']?>', 'action<?php echo $value['id']?>')">
						<option value="">-- <?php echo $spText['common']['Select']?> --</option>
						<?php if ($value['identifier'] != 'top') {?>
                        	<option value="editmenu">Edit</option>
                        <?php }?>
                        <option value="menuitemmanager">Menu Item Manager</option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{
		?>
		<tr><td colspan="5"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php
	} 
	?>
</table>
