<?php echo showSectionHead($spTextPanel['New Project']); ?>
<form id="projectform">
<table class="list">
	<tr class="listHead">
		<td width='30%'><?php echo $spTextPanel['New Project']?></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><?php echo $spText['common']['Name']?>*:</td>
		<td>
			<input class="form-control" type="text" name="project_name" value="<?php echo $post['project_name']?>"><?php echo $errMsg['project_name']?>
		</td>
	</tr>
	<tr>
		<td><?php echo $spText['common']['Keyword']?>*:</td>
		<td>
			<input class="form-control" type="text" name="keyword" value="<?php echo $post['keyword']?>">
			<?php echo $errMsg['keyword']?>
			<p><?php echo $pluginText['keywordinfotext']?></p>
		</td>
	</tr>			
	<tr>
		<td><?php echo $spText['common']['Website']?>:*</td>
		<td>
			<select name="website_id" id="website_id" onchange="doLoad('website_id', 'keywords.php', 'keyword_area', 'sec=keywordbox')">
				<?php foreach($websiteList as $websiteInfo){?>
					<?php if($websiteInfo['id'] == $post['website_id']){?>
						<option value="<?php echo $websiteInfo['id']?>" selected><?php echo $websiteInfo['name']?></option>
					<?php }else{?>
						<option value="<?php echo $websiteInfo['id']?>"><?php echo $websiteInfo['name']?></option>
					<?php }?>
				<?php }?>
			</select>
			<?php echo $errMsg['website_id']?>
		</td>
	</tr>	
	<tr>
		<td><?php echo $spTextSA['Link Title']; ?>:*</td>
		<td>
			<input class="form-control" type="text" name="link_title" value="<?php echo $post['link_title']?>">
			<?php echo $errMsg['link_title']?>
			<p><?php echo $pluginText['link_title_info']?></p>
		</td>
	</tr>	
	<tr>
		<td><?php echo $pluginText['Comment Email']?>:*</td>
		<td>
			<input type="text" name="email" value="<?php echo $post['email']?>"><?php echo $errMsg['email']?>
			<p><?php echo $pluginText['emailinfotext']?></p>
		</td>
	</tr>			
	<tr>
		<td><?php echo $spText['common']['lang']?>:*</td>
		<td>
			<?php echo $this->render('language/languageselectbox', 'ajax'); ?>			
		</td>
	</tr>
	<tr>
		<td><?php echo $pluginText['Maximum Blog Links']?>:*</td>
		<td>
			<input type="text" name="max_links" value="<?php echo $post['max_links']?>"><?php echo $errMsg['max_links']?>
			<p><?php echo $pluginText['BC_MAX_BLOG_LINKS']?>: <?php echo BC_MAX_BLOG_LINKS?></p>
		</td>
	</tr>
	<?php 
		for($i=1;$i<=10;$i++) {
	    $class = $i % 2 ? "blue_row" : "white_row";
	    ?>		
    	<tr>
    		<td><?php echo $pluginText['Comment'].$i?>:<?php echo $i==1 ? "*" : "";?></td>
    		<td>
    			<textarea name="comment<?php echo $i?>" class="form-control"><?php echo $post['comment'.$i]?></textarea><br><?php echo $errMsg['comment'.$i]?>
    		</td>
    	</tr>
	<?php }?>
</table>
<table class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginPOSTMethod('projectform', 'content', 'action=createproject'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>