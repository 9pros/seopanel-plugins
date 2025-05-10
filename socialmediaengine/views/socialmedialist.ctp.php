<?php echo showSectionHead($pluginText['Social Media Manager']); ?>
<?php echo $pagingDiv?>
<table class="list">
	<tr class="listHead">
		<td class="left"><?php echo $spText['common']['Id']?></td>
		<td><?php echo $spText['common']['Name']?></td>
		<td><?php echo $spText['common']['Website']?></td>
		<td><?php echo $spText['common']['Status']?></td>
		<td class="right"><?php echo $spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 5;
	if (count($list) > 0) {
		foreach($list as $i => $listInfo){
		    $sourceLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editSocialMedia&media_id={$listInfo['id']}", "<i class='fab fa-".strtolower($listInfo['engine_name'])."'></i> {$listInfo['engine_name']}");
			?>
			<tr>
				<td class="<?php echo $leftBotClass?>"><?php echo $listInfo['id']?></td>
				<td class="td_br_right left"><?php echo $sourceLink?></td>
				<td class="td_br_right left">
					<a target="_blank" href="<?php echo $listInfo['engine_website']?>"><?php echo $listInfo['engine_website']?></a>
				</td>
               	<td class="td_br_right"><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];?></td>
                <td class="<?php echo $rightBotClass?>" width="100px">
					<?php
					if ($listInfo['status']) {
						$statAction = "InactivateSocialMedia";
						$statLabel = $spText['common']["Inactivate"];
					} else {
						$statAction = "ActivateSocialMedia";
						$statLabel = $spText['common']["Activate"];
					}
    				?>
    				<select name="action" id="action<?php echo $listInfo['id']?>" onchange="doSMEPluginAction('<?php echo $pgScriptPath?>', 'content', 'media_id=<?php echo $listInfo['id']?>&pageno=<?php echo $pageNo?>', <?php echo $listInfo['id']?>)">
    					<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
    					<option value="<?php echo $statAction?>"><?php echo $statLabel?></option>
    					<option value="connectionManager"><?php echo $pluginText['Connection Manager']?></option>
    					<option value="editSocialMedia"><?php echo $spText['common']['Edit']?></option>
    				</select>
				</td>
			</tr>
			<?php
		}
	} else {
		echo showNoRecordsList($colCount - 2);
	}
	?>
</table>