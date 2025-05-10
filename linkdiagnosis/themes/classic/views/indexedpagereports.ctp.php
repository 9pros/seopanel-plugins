<table  width="100%" border="0" cellspacing="0px" cellpadding="0px" align="center" class="rep_head">
	<tr>
		<td align="left" valign="bottom" colspan="2">
			<div><b><?php echo $pluginText['Report Url']?></b>: <?php echo $repInfo['url']?></div>
			<div style="margin-bottom: 0px;"><b><?php echo $pluginText['Last Updated']?></b>: <?php echo $repInfo['updated']?></div>
		</td>
	</tr>
<?php if(!empty($printVersion)) {?>	
	<?php echo showSectionHead($pluginText['Indexed Page Reports']); ?>
	<style>BODY{background-color:white;padding:50px 10px;}</style>
	<script language="Javascript" src="<?php echo SP_JSPATH?>/common.js"></script>
	<script type="text/javascript">
		window.print();
		loadJsCssFile("<?php echo SP_CSSPATH?>/screen.css", "css");
		loadJsCssFile("<?php echo PLUGIN_CSSPATH?>/ld.css", "css");
	</script>	
<?php } else {?>
	<tr>	
		<td valign="bottom">
			<a href="<?php echo PLUGIN_SCRIPT_URL?>&action=showreport&report_type=rp_indexed&doc_type=export&report_id=<?php echo $reportId?>"><img src="<?php echo SP_IMGPATH?>/icoExport.gif"></a> &nbsp;
			<a target="_blank" href="<?php echo PLUGIN_SCRIPT_URL?>&action=showreport&report_type=rp_indexed&doc_type=print&report_id=<?php echo $reportId?>&pageno=<?php echo $pageNo?>"><img src="<?php echo SP_IMGPATH?>/print_button.gif"></a>
		</td>
		<td>
			<?php echo $pagingDiv?>
		</td>
	</tr>
<?php }?>
</table>

<table id="cust_tab">
	<tr>
		<th><?php echo $spText['common']['Url']?></th>
		<th><?php echo $pluginText['Total Backlinks']?></th>
		<th><?php echo $pluginText['Popular Title']?></th>
		<th><?php echo $pluginText['Excellent']?></th>
		<th><?php echo $pluginText['Great']?></th>
		<th><?php echo $pluginText['Good']?></th>
		<th><?php echo $pluginText['Poor']?></th>
		<th><?php echo $pluginText['Nofollow']?></th>
		<th><?php echo $pluginText['Missing']?></th>
	</tr>
	<?php
	if(count($list) > 0){
		
		foreach($list as $i => $listInfo){
            ?>
			<tr>
				<td><a target="_blank" href="<?php echo $listInfo['url']?>"><?php echo $listInfo['url']?></td>
				<td><?php echo $listInfo['tot_backlinks']?></td>
				<td><?php echo $listInfo['pop_anchor']?></td>
				<td><?php echo $listInfo['tot_exel']?></td>
				<td><?php echo $listInfo['tot_great']?></td>
				<td><?php echo $listInfo['tot_good']?></td>
				<td><?php echo $listInfo['tot_poor']?></td>
				<td><?php echo $listInfo['tot_nofollow']?></td>
				<td><?php echo $listInfo['tot_missing']?></td>
			</tr>
			<?php
		}
	}else{	
		?>
		<tr><td colspan="9"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php
	} 
	?>
</table>