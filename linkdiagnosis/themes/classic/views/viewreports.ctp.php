<?php echo showSectionHead($pluginText['View Reports']); ?>
<?php
if(empty($projectId)) {
	showErrorMsg($pluginText['No Projects Found']);
}

$submitAction = pluginPOSTMethod('search_form', 'subcontent', 'action=showreport');
?>
<form id='search_form'>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>				
		<th><?php echo $pluginText['Project']?>: </th>
		<td style="width: 160px;">
			<select id="project_id" name="project_id" onchange="showReportSelectBox('<?php echo PLUGIN_SCRIPT_URL?>', 'report_area', 'action=reportselbox', 'project_id')">
				<?php foreach($projectList as $list) {?>
					<?php if($list['id'] == $projectId) {?>
						<option value="<?php echo $list['id']?>" selected="selected"><?php echo $list['name']?></option>
					<?php } else {?>
						<option value="<?php echo $list['id']?>"><?php echo $list['name']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>						
		<th style="width: 140px;"><?php echo $pluginText['Report']?>: </th>
		<td id="report_area">
			<select name="report_id" id="report_id" onchange="showAnchorSelectBox('<?php echo PLUGIN_SCRIPT_URL?>', 'anchor_area', 'action=anchorselbox', 'report_id')">
				<?php foreach($reportList as $list) {?>
					<?php if($list['id'] == $reportId) {?>
						<option value="<?php echo $list['id']?>" selected="selected"><?php echo $list['name']?></option>
					<?php } else {?>
						<option value="<?php echo $list['id']?>"><?php echo $list['name']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
	</tr>
	<tr>						
		<th><?php echo $pluginText['Report Type']?>: </th>
		<td>
			<select name="report_type" id="report_type" onchange="<?php echo $submitAction?>">
				<?php foreach($typeList as $type => $label) {?>
					<?php $selected = ($type == $searchInfo['report_type']) ? " selected" : ""?>
					<option value="<?php echo $type?>" <?php echo $selected?> ><?php echo $label?></option>
				<?php }?>
			</select>
		</td>			
		<th><?php echo $spText['common']['Google Pagerank']?>: </th>
		<td>
			<select name="google_pagerank" onchange="<?php echo $submitAction?>">
				<option value="-1">-- <?php echo $spText['common']['Select']?> --</option>
				<?php for($i=0;$i<=10;$i++) {?>
					<option value="<?php echo $i?>">PR<?php echo $i?></option>
				<?php }?>
			</select>
		</td>
	</tr>	
	<tr>
		<th><?php echo $pluginText['Anchor']?>: </th>
		<td id="anchor_area">
			<select name="link_title" id="link_title" style="width: 160px;" onchange="<?php echo $submitAction?>">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($anchorList as $anchor) {?>
					<option value="<?php echo $anchor['link_title']?>"><?php echo $anchor['link_title']?></option>
				<?php }?>
			</select>
		</td>								
		<th><?php echo $pluginText['Link Type']?>: </th>
		<td>
			<select name="link_type" onchange="<?php echo $submitAction?>">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($linkType as $type => $label) {?>
					<option value="<?php echo $type?>"><?php echo $label?></option>
				<?php }?>
			</select>
		</td>
	</tr>	
	<tr>
		<th><?php echo $spTextHome['Backlinks']?>: </th>
		<td id="anchor_area"><input type="text" name="search" value=""></td>
		<th>
			<a href="javascript:void(0);" onclick="<?php echo pluginPOSTMethod('search_form', 'subcontent', 'action=showreport')?>" class="actionbut"><?php echo $spText['button']['Show Records']?></a>
		</th>
		<td>&nbsp;</td>
	</tr>
</table>
</form>
<div id='subcontent'>
	<script><?php echo $submitAction?></script>
</div>