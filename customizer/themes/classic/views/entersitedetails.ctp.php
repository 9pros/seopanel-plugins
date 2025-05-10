<?php echo showSectionHead("Site Details Management"); ?>

<?php if($updation == 'success'){ ?>
<div class="alert">
	<span class="closebtn"
		onclick="this.parentElement.style.display='none';">&times;</span>
	Successfully Updated
</div>
<?php } ?>


<form id="siteform">
	<table id="cust_tab">
		<tr class="form_head">
			<th width='30%'>Site Details Management</th>
			<th>&nbsp;</th>
		</tr>

		<tr class="form_data">
			<td>Site Logo (Url):</td>
			<td><input type="text" name="site_logo" class="form-control"
				placeholder="Dimension :131 x 31 px"
				value="<?php echo $post['site_logo']?>"><?php echo $errMsg['site_logo']?></td>
		</tr>
		<tr class="form_data">
			<td>Site Favicon (Url):</td>
			<td><input type="text" name="site_favicon" class="form-control"
				value="<?php echo $post['site_favicon']?>"><?php echo $errMsg['site_favicon']?></td>
		</tr>
		<tr class="form_data">
			<td>Site Name:</td>
			<td><input type="text" name="site_name" maxlength="25" class="form-control"
				value="<?php echo $post['site_name']?>"><?php echo $errMsg['site_name']?></td>
		</tr>
		<tr class="form_data">
			<td>Site Title:</td>
			<td><input type="text" name="site_title" class="form-control"
				value="<?php echo $post['site_title']?>"><?php echo $errMsg['site_title']?></td>
		</tr>
		<tr class="form_data">
			<td>Site Description:</td>
			<td><textarea name="site_description" class="form-control"><?php echo $post['site_description']?></textarea><?php echo $errMsg['site_description']?></td>
		</tr>
		<tr class="form_data">
			<td>Site Keywords:</td>
			<td><textarea name="site_keywords" class="form-control"><?php echo $post['site_keywords']?></textarea><?php echo $errMsg['site_keywords']?></td>
		</tr>
		<tr class="form_data">
			<td>Footer Copyright Text:</td>
			<td>
				<textarea name="footer_copyright" id="footer_copyright"><?php echo $post['footer_copyright']?></textarea>
				<p><b>[year]</b> will be replaced with current year</p>
				<?php echo $errMsg['footer_copyright']?>
			</td>
		</tr>
		<tr class="form_data">
			<td>Facebook Page Url:</td>
			<td><input type="text" name="fb_page_url" class="form-control"
				value="<?php echo $post['fb_page_url']?>"><?php echo $errMsg['fb_page_url']?></td>
		</tr>
		<tr class="form_data">
			<td>Twitter Page Url:</td>
			<td><input type="text" name="twitter_page_url" class="form-control"
				value="<?php echo $post['twitter_page_url']?>"><?php echo $errMsg['twitter_page_url']?></td>
		</tr>
		<tr class="form_data">
			<td>Contact Url:</td>
			<td><input type="text" name="contact_url" class="form-control"
				value="<?php echo $post['contact_url']?>"><?php echo $errMsg['contact_url']?></td>
		</tr>
		<tr class="form_data">
			<td>Help Url:</td>
			<td><input type="text" name="help_url" class="form-control"
				value="<?php echo $post['help_url']?>"><?php echo $errMsg['help_url']?></td>
		</tr>
		<tr class="form_data">
			<td>Forum Url:</td>
			<td><input type="text" name="forum_url" class="form-control"
				value="<?php echo $post['forum_url']?>"><?php echo $errMsg['forum_url']?></td>
		</tr>
		<tr class="form_data">
			<td>Disable News:</td>
			<td><input type="checkbox" name="disable_news"
				value="<?php echo $post['disable_news']?>"
				<?php if($post['disable_news'] == '1'){ ?> checked="checked"
				<?php }?>><?php echo $errMsg['disable_news']?></td>
		</tr>
		<tr class="form_data">
			<td>Custom Menu:</td>
			<td><input type="checkbox" name="custom_menu"
				value="<?php echo $post['custom_menu']?>"
				<?php if($post['custom_menu'] == '1'){ ?> 
					checked="checked"
				<?php }?>><?php echo $errMsg['custom_menu']?></td>
		</tr>
	</table>
	<br>
	<table width="100%" border="0"
		class="actionSec">
		<tr>
			<td style="padding-top: 6px; text-align: right;"><a
				onclick="<?php echo pluginGETMethod('', 'content')?>"
				href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginPOSTMethod('siteform', 'content', 'action=insertdetails'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);"
				class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a></td>
		</tr>
	</table>
</form>

<script>
$(document).ready(function(){
	var simplemde = new SimpleMDE({ element: document.getElementById("footer_copyright"), forceSync: true });
});
</script>