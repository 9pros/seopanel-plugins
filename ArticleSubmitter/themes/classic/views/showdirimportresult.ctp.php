<?php echo  showSectionHead($pluginText['Import Websites']); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0px" class="rep_summary">
	<tr>
		<td class="topheader" colspan="10">Import Websites Result</td>
	</tr>
	<tr>
		<th class="leftcell">Valid:</th>
		<td><?php echo $resInfo['valid']?></td>
		<th>Existing:</th>
		<td><?php echo $resInfo['existing']?></td>
		<th>Invalid:</th>
		<td><?php echo $resInfo['invalid']?></td>
	</tr>
</table>

<?php
if (!empty($checkDirFromId)) {
    showSuccessMsg($pluginText['dirimportedsuccess'], false);
    $checkpr = empty($post['checkpr']) ? "" : "&checkpr=1";
    if (!empty($post['checkstatus'])) {        
        echo "<script>".pluginGETMethod('action=checkimportdir&checkdirid='.$checkDirFromId.$checkpr, 'checkdirstat')."</script>";        
        ?>
        <div id="showimpmsg"><?php echo $pluginText['Checking the status of directories']?>...</div>    
        <div id="checkdirstat"></div>
        <?php
    }    
}
?>