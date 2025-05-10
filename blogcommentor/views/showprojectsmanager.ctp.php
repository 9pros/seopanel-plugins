<?php echo showSectionHead($pluginText["Projects Manager"]); ?>
<table width="50%" class="search">
	<tr>
		<th><?php echo $spText['common']['Website']?>: </th>
		<td>
			<select name="website_id" id="website_id" onchange="doLoad('website_id', '<?php echo PLUGIN_SCRIPT_URL?>', 'content')">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($websiteList as $websiteInfo){?>
					<?php if($websiteInfo['id'] == $websiteId){?>
						<option value="<?php echo $websiteInfo['id']?>" selected><?php echo $websiteInfo['name']?></option>
					<?php }else{?>
						<option value="<?php echo $websiteInfo['id']?>"><?php echo $websiteInfo['name']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
	</tr>
</table>

<?php echo $pagingDiv?>

<table class="list">
	<tr class="listHead">
		<td><?php echo $spText['common']['Id']?></td>
		<td><?php echo $spText['common']['Name']?></td>
		<td><?php echo $spText['common']['Keyword']?></td>
		<td><?php echo $spText['common']['Website']?></td>
		<td><?php echo $spText['common']['lang']?></td>
		<td><?php echo $pluginText['Maximum Links']?></td>
		<td><?php echo $pluginText['Crawled Links']?></td>
		<td><?php echo $spText['common']['Status']?></td>
		<td><?php echo $spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 9; 
	if(count($list) > 0){
		$catCount = count($list);
		foreach($list as $i => $listInfo){
            $projectLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editproject&project_id={$listInfo['id']}", "{$listInfo['project_name']}");
			?>
			<tr>
				<td><?php echo $listInfo['id']?></td>
				<td><?php echo $projectLink?></td>
				<td><?php echo $listInfo['keyword']?></td>
				<td><?php echo $listInfo['website']?></td>
				<td><?php echo $listInfo['lang_name']?></td>
				<td><?php echo $listInfo['max_links']?></td>
				<td><?php echo $listInfo['crawled_links']?></td>
				<td><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];	?></td>
				<td>
					<?php
						if($listInfo['status']){
							$statAction = "Inactivate";
							$statLabel = $spText['common']["Inactivate"];
						}else{
							$statAction = "Activate";
							$statLabel = $spText['common']["Activate"];
						} 
					?>
					<select style="width: 124px;" name="action" id="action<?php echo $listInfo['id']?>" onchange="doBlogCommenterPluginAction('<?php echo $pgScriptPath?>', 'content', 'project_id=<?php echo $listInfo['id']?>&pageno=<?php echo $pageNo?>&showhead=1', <?php echo $listInfo['id']?>)">
						<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
						<?php if ($listInfo['status']) {?>
    						<option value="runproject"><?php echo $pluginText['Run Project']?></option>
    						<option value="viewreports"><?php echo $pluginText['View Reports']?></option>
    					<?php }?>
    					<option value="importLinks"><?php echo $spTextSA['Import Project Links']?></option>
    					<option value="copyproject"><?php echo $pluginText['Copy Project']?></option>
						<option value="<?php echo $statAction?>"><?php echo $statLabel?></option>
						<option value="editproject"><?php echo $spText['common']['Edit']?></option>
						<option value="deleteproject"><?php echo $spText['common']['Delete']?></option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{	 
		echo showNoRecordsList($colCount-2);		
	} 
	?>
</table>
<table class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a onclick="<?php echo pluginGETMethod('action=newproject&website_id='.$websiteId, 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $pluginText['New Project']?>
         	</a>
    	</td>
	</tr>
</table>