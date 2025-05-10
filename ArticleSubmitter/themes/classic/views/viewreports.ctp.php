<?php 
echo showSectionHead($pluginText['Submission Reports']);
$submitAction = pluginPOSTMethod('search_form', 'content', 'action=report');
?>
<form id='search_form'>
<table width="100%" class="search">
	<tr>				
		<th><?php echo $spText['label']['Project']?>: </th>
		<td style="width: 160px;">
			<select name="project_id" id="project_id" onchange="doLoad('project_id', '<?php echo PLUGIN_SCRIPT_URL?>&action=showArticleSelectBox', 'article_area')">
				<?php foreach($projectList as $projectInfo) {?>
					<?php if($projectInfo['id'] == $projectId) {?>
						<option value="<?php echo $projectInfo['id']?>" selected><?php echo $projectInfo['project']?></option>
					<?php } else {?>
						<option value="<?php echo $projectInfo['id']?>"><?php echo $projectInfo['project']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>						
		<th style="width: 140px;"><?php echo $pluginText["Article"]?>: </th>
		<td id="article_area">
			<select name="article_id" onchange="<?php echo $submitAction?>">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($articleList as $list) {				  
				    ?>
					<?php if($post['article_id'] == $list['id']) {?>
						<option value="<?php echo $list['id']?>" selected="selected"><?php echo $list['title']?></option>
					<?php } else {?>
						<option value="<?php echo $list['id']?>"><?php echo $list['title']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
	</tr>
	<tr>						
		<th> <?php echo $spText['common']['Website']?>: </th>
		<td>
			<select name="site_id" onchange="<?php echo $submitAction?>">
    			<option value="">-- <?php echo $spText['common']['Select']?> --</option>
    			<?php foreach($websiteList as $list) { ?>
    				<?php if($post['site_id'] == $list['id']) {?>
    					<option value="<?php echo $list['id']?>" selected="selected"><?php echo $list['website_name']?></option>
    				<?php } else {?>
    					<option value="<?php echo $list['id']?>"><?php echo $list['website_name']?></option>
    				<?php }?>
    			<?php }?>
			</select>
		</td>
		<th><?php echo $spText['common']['Period']?>:</th>
		<td>
			<input type="text" class="date" value="<?php echo $fromTime?>" name="from_time"/>
			<input type="text" class="date" value="<?php echo $toTime?>" name="to_time"/>			
			<script>
			$(function() {
				$( "input[name='from_time'], input[name='to_time']").datepicker({dateFormat: "yy-mm-dd"});
			});
		  	</script>
		  	&nbsp;
			<a href="javascript:void(0);" onclick="<?php echo $submitAction?>" class="actionbut"><?php echo $spText['button']['Show Records']?></a>
		</td>
	</tr>
</table>
</form>

<?php echo $pagingDiv?>
<table id="cust_tab">
	<tr>
        <th><?php echo $spText['common']['Id']?></th>
		<th><?php echo $pluginText["Article"]?></th>
		<th><?php echo $spText['common']['Website']?></th>
		<th><?php echo $pluginText["Submit Log"]?></th>
        <th><?php echo $spText['common']['Status']?></th>
		<th><?php echo $spText['label']['Reference']?></th>
		<th><?php echo $spText['label']['Updated']?></th>
		<th><?php echo $spText['common']['Action']?></th>
	</tr>
	<?php
	$colCount = 8;
	if(count($report) > 0) {
		$catCount = count($report);
		foreach($report as $i => $listInfo) {
            ?>
			<tr>
				<td><?php echo $listInfo['id']?></td>
				<td><?php echo $listInfo['title']?></td>
				<td><?php echo $listInfo['website_name']?></td>
				<td><?php echo $listInfo['submit_status_desc']?></td>
				<td><?php echo $listInfo['submit_status']?></td>
				<td><?php echo $listInfo['ref_id']?></td>
				<td><?php echo $listInfo['submit_time']?></td>				
                <td>
					<a onclick="<?php echo pluginConfirmGETMethod('action=removeSubmission&id='.$listInfo['id'].'&pageno='.$pageNo, 'content')?>" href="javascript:void(0);" >
						<?php echo $spText['common']["Delete"]?>
					</a>
                </td>
			</tr>
			<?php
		}
	} else {
		echo showNoRecordsList($colCount-2);
	}
	?>
</table>