<?php echo showSectionHead($spTextPanel['Edit Project']); ?>
<form id="projectform">
<input type="hidden" name="id" value="<?=$post['id']?>"/>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?=$spTextPanel['Edit Project']?></td>
		<td class="right">&nbsp;</td>
	</tr>		
	<tr class="white_row">
		<td class="td_left_col"><?=$spText['common']['Website']?>:*</td>
		<td class="td_right_col">
			<select name="website_id" id="website_id" onchange="doLoad('website_id', 'keywords.php', 'keyword_area', 'sec=keywordbox')">
				<?php foreach($websiteList as $websiteInfo){?>
					<?php if($websiteInfo['id'] == $post['website_id']){?>
						<option value="<?=$websiteInfo['id']?>" selected><?=$websiteInfo['name']?></option>
					<?php }else{?>
						<option value="<?=$websiteInfo['id']?>"><?=$websiteInfo['name']?></option>
					<?php }?>
				<?php }?>
			</select>
			<?=$errMsg['website_id']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$spText['common']['Url']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="share_url" value="<?=$post['share_url']?>" class="large" id='weburl'><?=$errMsg['share_url']?>
			<a style="text-decoration: none;" href="javascript:void(0);" onclick="crawlMetaData('websites.php?sec=crawlmeta', 'crawlstats')">&#171&#171 <?=$spText['common']['Crawl Meta Data']?></a>
			<div id="crawlstats" style="float: right;padding-right:40px;"></div>
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$spText['label']['Title']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="share_title" value="<?=$post['share_title']?>" class="large" id="webtitle"><?=$errMsg['share_title']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$spText['label']['Description']?>:*</td>
		<td class="td_right_col">
			<textarea name="share_description" id="webdescription"><?=$post['share_description']?></textarea><?=$errMsg['share_description']?>
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Tags']?>:*</td>
		<td class="td_right_col">
			<textarea name="share_tags" id="webkeywords"><?=$post['share_tags']?></textarea><?=$errMsg['share_tags']?>
			<p><?=$pluginText['Separate tags with commas']?></p>
		</td>
	</tr>
	<tr class="blue_row">
		<td class="tab_left_bot_noborder"></td>
		<td class="tab_right_bot"></td>
	</tr>
	<tr class="listBot">
		<td class="left" colspan="1"></td>
		<td class="right"></td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?=pluginGETMethod('', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'content', 'action=updateproject'); ?>
         	<a onclick="<?=$actFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>