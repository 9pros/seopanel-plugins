<?php if (!empty($showhead)) {?>
    <?php echo showSectionHead($pluginText['Run Project']); ?>
    
    <div id="run_project">
    	<div id="run_info">
    		<table width="100%" border="0" cellspacing="0" cellpadding="0px" class="summary_tab">
            	<tr>
            		<td class="topheader" colspan="10"><?php echo $spTextSA['Project Summary']?></td>
            	</tr>
            	<tr>
            		<th class="leftcell" width="20%"><?php echo $spText['common']['Keyword']?>:</th>
            		<td width="40%"><?php echo $projectInfo['keyword']?></td>
            		<th width="20%"><?php echo $spTextSA['Maximum Pages']?>:</th>
            		<td><?php echo $projectInfo['max_links']?></td>
            	</tr>
            	<tr>
            		<th><?php echo $spTextSA['Pages Found']?>:</th>
            		<td id="total_links"><?php echo $countLinks?></td>
            		<th><?php echo $pluginText['Checked Links']?>:</th>
            		<td id="checked_links"><?php echo $checkedLinks?></td>
            	</tr>
            	<tr>
            		<th><?php echo $pluginText['Active Links']?>:</th>
            		<td id="active_links"><?php echo $activeLinks?></td>
            		<th><?php echo $pluginText['Inactive Links']?>:</th>
            		<td id="inactive_links"><?php echo $countLinks - $activeLinks?></td>
            	</tr>
            </table>
    	</div>
    	<?php if (!empty($importCount)) {?>
	    	<p class='note'><b>Imported <?php echo $importCount?> links</b></p>
	    <?php }?>
    	<p class='note'>
        	<?php echo $pluginText['escapetostop']?>.
        	<?php echo scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=runproject&project_id=$projectId&showhead=1&import_count=$importCount", $spText['label']['Click Here'])?> <?php echo $pluginText['torunproject']?>.
        </p>
    	<div id="subcontmed">
<?php } ?>
			<?php
			if ($completed == 1) {
			    $submitLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=viewreports&project_id=$projectId", $spText['label']['Click Here']);
			    showSuccessMsg($pluginText['Successfully saved blog search links']."! ".$submitLink." ". $pluginText['to submit comments to blog links']."!", false);
			} elseif ($completed == -1) {
			    echo showErrorMsg($errorMsg, false);
			} else {
			    $delayTime = $showhead ? 10 : BC_SEARCH_DELAY_TIME; 
			    echo "<b>".$pluginText['savedlinkswaitingnext']." ".($delayTime / 1000)." seconds</b>";
			    ?>
        		<script>
        		setTimeout('scriptDoLoad(\'<?php echo PLUGIN_SCRIPT_URL?>&action=runproject&project_id=<?php echo $projectId?>&import_count=<?php echo $importCount?>\', \'subcontmed\')', <?php echo $delayTime?>);
        		</script>
			    <?php
			}
			?>
    		
<?php if (!empty($showhead)) {?>
    	</div>
	</div>
<?php } ?>
	