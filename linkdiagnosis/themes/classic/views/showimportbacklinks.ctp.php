<?php
echo showSectionHead($pluginText["Import Backlinks"]);

// if project count is zero
if(count($projectList) <= 0) {
	showErrorMsg($pluginText['No Projects Found']);
}

?>
<form id="importlinks">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?php echo $spTextSA['Import Project Links']?></td>
		<td class="right">&nbsp;</td>
	</tr>
	<tr class="blue_row">				
		<td class="td_left_col"><?php echo $spText['label']['Project']?>: </td>
		<td class="td_right_col">
			<select id="project_id" name="project_id" onchange="showReportSelectBox('<?php echo PLUGIN_SCRIPT_URL?>', 'report_area', 'action=reportselbox&all_reports=1', 'project_id')">
				<?php foreach($projectList as $list) {?>
					<?php if($list['id'] == $projectId) {?>
						<option value="<?php echo $list['id']?>" selected="selected"><?php echo $list['name']?></option>
					<?php } else {?>
						<option value="<?php echo $list['id']?>"><?php echo $list['name']?></option>
					<?php }?>
				<?php }?>
			</select>
			<br><?php echo $errMsg['project_id']?>
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?php echo $pluginText['Report']?>: </td>
		<td id="report_area" class="td_right_col">
			<select name="report_id" id="report_id" onchange="showAnchorSelectBox('<?php echo PLUGIN_SCRIPT_URL?>', 'anchor_area', 'action=anchorselbox', 'report_id')">
				<?php foreach($reportList as $list) {?>
					<?php if($list['id'] == $reportId) {?>
						<option value="<?php echo $list['id']?>" selected="selected"><?php echo $list['name']?></option>
					<?php } else {?>
						<option value="<?php echo $list['id']?>"><?php echo $list['name']?></option>
					<?php }?>
				<?php }?>
			</select>
			<br><?php echo $errMsg['report_id']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col">Links:</td>
		<td class="td_right_col">
			<textarea name="links" rows="10"><?php echo $post['links']?></textarea>
			<br><?php echo $errMsg['links']?>
			<p style="font-size: 12px;"><?php echo $spTextSA['Insert links separated with comma']?>.</p>
			<P><b>Eg:</b> http://www.seofree.net/, http://www.seofreetools.net/scripts/</P>
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
    		<a onclick="<?php echo pluginGETMethod('action=importBacklinks', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('importlinks', 'content', 'action=doImportBacklinks'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>
<?php 
if (!empty($linkList)) {
	$total = $linkList['valid'] + $linkList['invalid'] + $linkList['duplicate']; 
	?>
	<br>
	<br>
	<table class="summary_tab" id='import_suumary' width="60%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
        	<td class="topheader" colspan="10"><?php echo $pluginText['Import Summary']?></td>
        </tr>
		<tr>
			<th class="leftcell"><?php echo $spTextSA['Total Links']?>:</th>
			<td><?php echo $total?></td>
			<th><?php echo $pluginText['Valid Links']?>:</th>
			<td><?php echo $linkList['valid']?></td>
		</tr>
		<tr>
			<th class="leftcell"><?php echo $pluginText['Invalid Links']?>:</th>
			<td><?php echo $linkList['invalid']?></td>
			<th><?php echo $pluginText['Duplicate Links']?>:</th>
			<td><?php echo $linkList['duplicate']?></td>
		</tr>
	</table>
	<script>
	window.location.hash = '#import_suumary';
	</script>
	<?php
}
?>