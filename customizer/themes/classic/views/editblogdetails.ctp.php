<?php echo showSectionHead("Edit Blog"); ?>

<form id="blogform">
	<input type="hidden" name="blog_id"
		value="<?php echo $blog_data['id'];?>">
	<table id="cust_tab">
		<tr class="form_head">
			<th width='30%'>Edit Blog</th>
			<th>&nbsp;</th>
		</tr>
		<tr class="form_data">
			<td>Blog Title:</td>
			<td><input type="text" name="blog_title" class="form-control"
				<?php if($errMsg['blog_title'] != '') {?>
					value="<?php echo $data['blog_title']; ?>" 
				<?php }else{ ?>
					value="<?php echo $blog_data['blog_title']?>" 
				<?php } ?> ><?php echo $errMsg['blog_title']?></td>
		</tr>
		<tr class="form_data">
			<td>Blog Content:</td>
			<td><textarea id="blog_content" name="blog_content"><?php echo $blog_data['blog_content'];?></textarea>
		<?php echo $errMsg['blog_content']?>
		</td>
		</tr>
		<tr class="form_data">
			<td>Blog Meta Title:</td>
			<td><input type="text" name="meta_title" class="form-control"
				<?php if($errMsg['meta_title'] != '') {?>
				value="<?php echo $data['meta_title']; ?>" <?php }else{ ?>
				value="<?php echo $blog_data['meta_title']?>" <?php } ?>><?php echo $errMsg['meta_title']?>
		</td>
		</tr>
		<tr class="form_data">
			<td>Blog Meta description:</td>
			<td><textarea name="meta_description" class="form-control"><?php if($errMsg['meta_description'] != '') { echo $data['meta_description']; ?> <?php }else{ echo $blog_data['meta_description']; }?></textarea><?php echo $errMsg['meta_description']?></td>
		</tr>

		<tr class="form_data">
			<td>Blog Meta keywords:</td>
			<td><textarea name="meta_keywords" class="form-control"><?php if($errMsg['meta_keywords'] != '') { echo $data['meta_keywords'];  ?><?php }else{ echo $blog_data['meta_keywords']; }?></textarea><?php echo $errMsg['meta_keywords']?></td>
		</tr>

		<tr class="form_data">
			<td>Language:</td>
			<td><select name="lang_code" style="">
				<?php foreach($lang as $name => $code) {?>
				<option value="<?php echo $code;?>"
						<?php if($blog_data['lang_code'] == $code){ ?> selected="selected"
						<?php }?>> <?php echo $name; ?> </option>
				<?php } ?>
			</select></td>
		</tr>
		<tr class="form_data">
			<td>Tags:</td>
			<td><input type="text" name="tags" class="form-control"
				<?php if($errMsg['tags'] != '') {?>
				value="<?php echo $data['tags']; ?>" <?php }else{ ?>
				value="<?php echo $blog_data['tags']?>" <?php } ?>> <?php echo $errMsg['tags']?></td>
		</tr>
		<tr class="form_data">
			<td>Replace Page:</td>
			<td><select name="link_page" style="">
					<option value="">-- Select --</option>
					<option value="home"
						<?php if($blog_data['link_page'] == 'home') {?>
						selected="selected" <?php } ?>>Home</option>
					<option value="support"
						<?php if($blog_data['link_page'] == 'support') {?>
						selected="selected" <?php } ?>>Support</option>
					<option value="aboutus"
						<?php if($blog_data['link_page'] == 'aboutus') {?>
						selected="selected" <?php } ?>>About Us</option>
			</select></td>
		</tr>
	</table>
	<br>

	<table width="100%" class="actionSec">
		<tr>
			<td style="padding-top: 6px; text-align: right;"><a
				onclick="<?php echo pluginGETMethod('', 'content')?>"
				href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('blogform', 'content', 'action=updateblogdetails'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);"
				class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a></td>
		</tr>
	</table>
</form>

<script>
$(document).ready(function(){
	var simplemde = new SimpleMDE({ element: document.getElementById("blog_content"), forceSync: true });
});
</script>