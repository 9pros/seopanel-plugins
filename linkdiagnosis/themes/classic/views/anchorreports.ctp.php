<table  width="100%" border="0" align="center" class="rep_head">
	<tr>
		<td align="left" valign="bottom" colspan="2">
			<div><b><?php echo $pluginText['Report Url']?></b>: <?php echo $repInfo['url']?></div>
			<div style="margin-bottom: 0px;"><b><?php echo $pluginText['Last Updated']?></b>: <?php echo $repInfo['updated']?></div>
		</td>
	</tr>
<?php if(!empty($printVersion)) {?>	
	<?php echo showSectionHead($pluginText['Most Popular Anchors']); ?>
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
			<a href="<?php echo PLUGIN_SCRIPT_URL?>&action=showreport&report_type=rp_anchor&doc_type=export&report_id=<?php echo $reportId?>"><img src="<?php echo SP_IMGPATH?>/icoExport.gif"></a> &nbsp;
			<a target="_blank" href="<?php echo PLUGIN_SCRIPT_URL?>&action=showreport&report_type=rp_anchor&doc_type=print&report_id=<?php echo $reportId?>&pageno=<?php echo $pageNo?>"><img src="<?php echo SP_IMGPATH?>/print_button.gif"></a>
		</td>
		<td>
			<?php echo $pagingDiv?>
		</td>
	</tr>
<?php }?>
</table>

<table id="cust_tab">
	<tr>
		<th><?php echo $spText['common']['No']?></th>
		<th><?php echo $spText['label']['Title']?></th>
		<th><?php echo $pluginText['Excellent']?></th>
		<th><?php echo $pluginText['Great']?></th>
		<th><?php echo $pluginText['Good']?></th>
		<th><?php echo $pluginText['Poor']?></th>
		<?php for($i=10;$i>=0;$i--) {?>			
			<th>PR<?php echo $i?></th>
        <?php }?>
		<th><?php echo $pluginText['Nofollow']?></th>
		<th><?php echo $spText['common']['Total']?></th>
	</tr>
	<?php
	if(count($list) > 0){
		
		foreach($list as $i => $listInfo){
			$pagingStart = empty($pagingStart) ? 1 : $pagingStart;
            $moreLink = "action=showreport&report_id={$listInfo['report_id']}&report_type=rp_links&link_title=" . urlencode($listInfo['link_title']);
            ?>
			<tr>
				<td><?php echo ($pagingStart + $i)?></td>
				<td><?php echo $listInfo['link_title']?></td>
				<td><?php echo scriptAJAXLinkHrefDialog(PLUGIN_SCRIPT_URL, 'content', $moreLink . "&link_type=excellent", $listInfo['tot_excel'])?></td>
				<td><?php echo scriptAJAXLinkHrefDialog(PLUGIN_SCRIPT_URL, 'content', $moreLink . "&link_type=great", $listInfo['tot_great'])?></td>
				<td><?php echo scriptAJAXLinkHrefDialog(PLUGIN_SCRIPT_URL, 'content', $moreLink . "&link_type=good", $listInfo['tot_good'])?></td>
				<td><?php echo scriptAJAXLinkHrefDialog(PLUGIN_SCRIPT_URL, 'content', $moreLink . "&link_type=poor", $listInfo['tot_poor'])?></td>
				<?php for($i=10; $i >= 0; $i--) {?>			
					<td>
					    <?php 
					    $listInfo['tot_pr_'.$i] = empty($listInfo['tot_pr_'.$i]) ? 0 : "<b>{$listInfo['tot_pr_'.$i]}</b>";
					    echo scriptAJAXLinkHrefDialog(PLUGIN_SCRIPT_URL, 'content', $moreLink . "&google_pagerank=$i", $listInfo['tot_pr_'.$i]);
					    ?>
					</td>
		        <?php }?>
				<td><?php echo scriptAJAXLinkHrefDialog(PLUGIN_SCRIPT_URL, 'content', $moreLink . "&link_type=nofollow", $listInfo['tot_nofollow'])?></td>
				<td><?php echo $listInfo['count']?></td>				
			</tr>
			<?php
		}
	}else{	
		?>
		<tr><td colspan="19"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php	
	} 
	?>
</table>