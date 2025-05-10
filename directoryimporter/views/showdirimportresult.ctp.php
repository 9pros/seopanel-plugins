<?php echo showSectionHead($pluginText['Import Directories']); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0px" class="rep_summary">
	<tr>
		<td class="topheader" colspan="10"><?=$pluginText['Directory Import Results']?></td>
	</tr>
	<tr>
		<th class="leftcell"><?=$pluginText['Valid']?>:</th>
		<td><?=$resInfo['valid']?></td>
		<th><?=$pluginText['Existing']?>:</th>
		<td><?=$resInfo['existing']?></td>
		<th><?=$pluginText['Invalid']?>:</th>
		<td><?=$resInfo['invalid']?></td>
	</tr>
</table>

<?php
if (!empty($checkDirFromId)) {
    showSuccessMsg($pluginText['dirimportedsuccess'], false);
    $checkpr = empty($post['checkpr']) ? "" : "&checkpr=1";
    if (!empty($post['checkstatus'])) {        
        echo "<script>".pluginGETMethod('action=checkimportdir&checkdirid='.$checkDirFromId.$checkpr, 'checkdirstat')."</script>";        
        ?>
        <div id="showimpmsg"><?=$pluginText['Checking the status of directories']?>...</div>    
        <div id="checkdirstat"></div>
        <?php
    }    
}
?>