<?php echo showSectionHead("Blog Management"); ?>
<form id='searching_form'>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>
		<th>Keyword: </th>
		<td style="width: 15%;">
			<input type="text" name="search" value="<?php echo $data['search']; ?>">
		</td>				
		<th>Status: </th>
		<td>
			<select id="status" name="status" >
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<option value="active" <?php if($data['status'] == 'active'){ ?>selected <?php } ?> >Active</option>
				<option value="inactive" <?php if($data['status'] == 'inactive'){ ?>selected <?php } ?>>Inactive</option>
			</select>
		</td>
	</tr>
	<tr>						
		<th style="width: 140px;">Link Page: </th>
		<td id="report_area">
			<select name="link_page" id="link_page" >
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<option value="home" <?php if($data['link_page'] == 'home'){ ?>selected <?php } ?>>Home</option>
				<option value="support" <?php if($data['link_page'] == 'support'){ ?>selected <?php } ?>>Support</option>
				<option value="aboutus" <?php if($data['link_page'] == 'aboutus'){ ?>selected <?php } ?>>About Us</option>
			</select>
		</td>
		<th></th>
		<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginPOSTMethod('searching_form', 'content', 'action=blogmanagement'); ?>
		<td><a onclick="<?php echo $actFun; ?>" class="actionbut">Show Records</a> </td>
	</tr>
</table>
</form>

<?php echo $pagingDiv?>

<table id="cust_tab">
	<tr>
		<th>Id</th>
		<th>Blog Title</th>
		<th>Meta Title</th>
		<th>Link Page</th>
		<th>Status</th>
		<th>Action</th>
	</tr>

	<?php
	if(count($blog_data) > 0) {
		foreach($blog_data as $i => $value){
			$blogLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editblog&blog_id={$value['id']}", "{$value['blog_title']}");
			?>
			<tr>
				<td width="40px"><?php echo $i+1;?></td>
				<td><?php echo $blogLink;?></td>
				<td><?php echo $value['meta_title']?></td>
				<td><?php echo $value['link_page'];?></td>
                <td><?php if($value['status'] == '0') {?>Inactive <?php }else{ ?> Active <?php } ?> </td>
				<td width="100px">
					<select name="action" id="action<?php echo $value['id']?>" onchange="doCUST_PluginAction('<?php echo PLUGIN_SCRIPT_URL?>', 'content', 'blog_id=<?php echo $value['id']?>&pageno=<?php echo $pageNo?>', 'action<?php echo $value['id']?>')">
						<option value="">-- <?php echo $spText['common']['Select']?> --</option>
                        <?php if($value['status'] == '0') { ?>
                            <option value="activateblog">Activate</option>
                        <?php }else{ ?>
                            <option value="deactivateblog">Deactivate</option>
                        <?php } ?>
                        <option value="editblog">Edit</option>
					    <option value="deleteblog">Delete</option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{
		?>
		<tr><td colspan="6"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php
	} 
	?>
</table>
<br>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a onclick="<?php echo pluginGETMethod('action=newblog', 'content')?>" href="javascript:void(0);" class="actionbut">
         		Add New Blog
         	</a>
    	</td>
	</tr>
</table>