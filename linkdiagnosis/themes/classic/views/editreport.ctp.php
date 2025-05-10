<?php 
echo showSectionHead($pluginText['Edit Report']);
$post['url'] = isset($post['url']) ? $post['url'] : "http://"; 
$post['max_links'] = isset($post['max_links']) ? $post['max_links'] : LD_MAX_LINKS_REPORT;
?>
<form id="reportform">
<input type="hidden" name="oldName" value="<?php echo $post['oldName']?>"/>
<input type="hidden" name="old_searchengine_id" value="<?php echo $post['old_searchengine_id']?>"/>
<input type="hidden" name="id" value="<?php echo $post['id']?>"/>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?php echo $pluginText['Edit Report']?></td>
		<td class="right">&nbsp;</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $pluginText['Project']?>:</td>
		<td class="td_right_col">
			<select name="project_id" style="width:150px;">
				<?php foreach($projectList as $projectInfo){?>
					<?php if($projectInfo['id'] == $projectId){?>
						<option value="<?php echo $projectInfo['id']?>" selected><?php echo $projectInfo['name']?></option>
					<?php }else{?>
						<option value="<?php echo $projectInfo['id']?>"><?php echo $projectInfo['name']?></option>
					<?php }?>						
				<?php }?>
			</select>
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $spText['common']['Name']?>:</td>
		<td class="td_right_col"><input type="text" name="name" value="<?php echo $post['name']?>"><?php echo $errMsg['name']?></td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $spText['common']['Url']?>:</td>
		<td class="td_right_col"><input type="text" name="url" value="<?php echo $post['url']?>" style="width: 400px;"><?php echo $errMsg['url']?></td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $pluginText['Maximum Links']?>:</td>
		<td class="td_right_col">
			<input type="text" name="max_links" value="<?php echo $post['max_links']?>"><?php echo $errMsg['max_links']?>
			<p><?php echo $pluginText['Maximum links allowed']?>: <?php echo $avlBacklinkCount?></p>
		</td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $pluginText['LD_SEARCH_ENGINE']?>:</td>
		<td class="td_right_col">
			<select name="searchengine_id">
				<?php
				foreach ($seList as $seInfo) {
					$selected = ($seInfo['id'] == $post['searchengine_id']) ? "selected" : "";
					?>			
					<option value="<?php echo $seInfo['id']?>" <?php echo $selected?>><?php echo $seInfo['domain']?></option>
					<?php
				}
				?>
			</select>
		</td>
	</tr>		
	<tr class="white_row">
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
    		<a onclick="<?php echo pluginGETMethod('action=reports', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('reportform', 'content', 'action=updatereport'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>