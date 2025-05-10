<table  width="98%" border="0" cellspacing="0px" cellpadding="0px" align="center" class="rep_head">
	<tr>
		<td align="left" valign="bottom">
			<div><b><?php echo $pluginText['Report Url']?></b>: <?php echo $repInfo['url']?></div>
			<div style="margin-bottom: 0px;"><b><?php echo $pluginText['Last Updated']?></b>: <?php echo $repInfo['updated']?></div>
		</td>

<?php if(!empty($printVersion)) {?>	
	<?php echo showSectionHead($pluginText['Links Report Summary']); ?>
	<style>BODY{background-color:white;padding:50px 10px;}</style>
	<script language="Javascript" src="<?php echo SP_JSPATH?>/common.js"></script>
	<script type="text/javascript">
		window.print();
		loadJsCssFile("<?php echo SP_CSSPATH?>/screen.css", "css");
		loadJsCssFile("<?php echo PLUGIN_CSSPATH?>/ld.css", "css");
	</script>	
<?php } else {?>	
	<td align="right" valign="bottom">
		<a href="<?php echo PLUGIN_SCRIPT_URL?>&action=showreport&report_type=rp_summary&doc_type=export&report_id=<?php echo $reportId?>"><img src="<?php echo SP_IMGPATH?>/icoExport.gif"></a> &nbsp;
		<a target="_blank" href="<?php echo PLUGIN_SCRIPT_URL?>&action=showreport&report_type=rp_summary&doc_type=print&report_id=<?php echo $reportId?>"><img src="<?php echo SP_IMGPATH?>/print_button.gif"></a>
	</td>
<?php }?>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0px" class="rep_summary">
	<tr>
		<td class="topheader" colspan="10"><?php echo $pluginText['Links Report Summary']?></td>
	</tr>
	<tr>
		<th class="leftcell"><?php echo $pluginText['Total Backlinks']?>:</th>
		<td><?php echo $reportInfo['tot_backlinks']?></td>
		<th><?php echo $pluginText['Crawled Backlinks']?>:</th>
		<td><?php echo $reportInfo['tot_crawled']?></td>
		<th><?php echo $pluginText['Unique Anchors']?>:</th>
		<td><?php echo $reportInfo['tot_anchors']?></td>
		<th><?php echo $pluginText['Missing Title']?>:</th>
		<td><?php echo $reportInfo['tot_missing']?></td>
	</tr>
	<tr>
		<th class="leftcell"><?php echo $pluginText['Excellent']?>:</th>
		<td><?php echo $reportInfo['tot_exel']?></td>
		<th><?php echo $pluginText["Great"]?>:</th>
		<td><?php echo $reportInfo['tot_great']?></td>
		<th><?php echo $pluginText['Good']?>:</th>
		<td><?php echo $reportInfo['tot_good']?></td>
		<th><?php echo $pluginText['Poor']?>:</th>
		<td><?php echo $reportInfo['tot_poor']?></td>
	</tr>
	<tr>
		<?php for($i=10;$i>6;$i--) {?>			
			<th <?php echo  ($i==10) ? "class='leftcell'" : ""?>>PR<?php echo $i?>:</th>
			<td><?php echo $reportInfo['tot_pr_'.$i]?></td>
		<?php }?>
	</tr>
	<tr>
		<?php for($i=6;$i>2;$i--) {?>			
			<th <?php echo  ($i==6) ? "class='leftcell'" : ""?>>PR<?php echo $i?>:</th>
			<td><?php echo $reportInfo['tot_pr_'.$i]?></td>
		<?php }?>
	</tr>
	<tr>
		<?php for($i=2;$i>=0;$i--) {?>			
			<th <?php echo  ($i==2) ? "class='leftcell'" : ""?>>PR<?php echo $i?>:</th>
			<td><?php echo $reportInfo['tot_pr_'.$i]?></td>
		<?php }?>
		<th><?php echo $pluginText['Nofollow']?>:</th>
		<td><?php echo $reportInfo['tot_nofollow']?></td>
	</tr>
</table>