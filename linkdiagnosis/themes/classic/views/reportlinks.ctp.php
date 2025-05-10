<table  width="100%" border="0px" align="center" class="rep_head">
	<tr>
		<td align="left" valign="bottom" colspan="2">
			<div><b><?php echo $pluginText['Report Url']?></b>: <?php echo $repInfo['url']?></div>
			<div style="margin-bottom: 0px;"><b><?php echo $pluginText['Last Updated']?></b>: <?php echo $repInfo['updated']?></div>
			<div style="margin-bottom: 0px;margin-top: 10px;"><b><?php echo $pluginText['Total Results']?></b>: <?php echo $totalResults?></div>
		</td>
	</tr>

<?php if(!empty($printVersion)) {?>	
	<?php echo showSectionHead($pluginText['Backlinks Reports']); ?>
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
			<a href="<?php echo PLUGIN_SCRIPT_URL?>&action=showreport&report_type=<?php echo $reportType?>&doc_type=export&report_id=<?php echo $reportId?><?php echo $filter?>"><img src="<?php echo SP_IMGPATH?>/icoExport.gif"></a> &nbsp;
			<a target="_blank" href="<?php echo PLUGIN_SCRIPT_URL?>&action=showreport&report_type=<?php echo $reportType?>&doc_type=print&report_id=<?php echo $reportId?>&pageno=<?php echo $pageNo?><?php echo $filter?>"><img src="<?php echo SP_IMGPATH?>/print_button.gif"></a>
			
		</td>
		<td><?php echo $pagingDiv?></td>
	</tr>
<?php }?>
</table>

<table id="cust_tab">
	<tr>
		<th><?php echo $pluginText['Backlink Url']?></th>
		<th><?php echo $_SESSION['text']['common']['MOZ Rank']?></th>
		<th><?php echo $_SESSION['text']['common']['Domain Authority']?></th>
		<th><?php echo $_SESSION['text']['common']['Page Authority']?></th>
		<th><?php echo $pluginText['Anchor']?></th>
		<th><?php echo $pluginText['Outbound Links']?></th>
		<th><?php echo $_SESSION['text']['label']['Score']?></th>
		<th><?php echo $pluginText['Link Type']?></th>
		<th><?php echo $spText['common']['Action']?></th>
	</tr>
	<?php
	if(count($list) > 0){
		foreach($list as $i => $listInfo){            
            $scoreInfo = $repObj->__getLinkType($listInfo);
            $listInfo['link_title'] = empty($listInfo['link_title']) ? "&nbsp;" : $listInfo['link_title'];
            $checkLink = SP_DEMO ? "" : confirmScriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'load_id_'.$listInfo['id'], "action=checkReportLink&id={$listInfo['id']}", $spText["button"]["Check Status"]);
            $infoLinkTitle = wordwrap($listInfo['url'], 100, "<br>", true);
            
            if (!empty($searchInfo['fromPopUp'])) {
            	$infoLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=viewLinkInfo&id={$listInfo['id']}", $infoLinkTitle);
            } else {
            	$infoLink = scriptAJAXLinkHrefDialog(PLUGIN_SCRIPT_URL, 'content', "action=viewLinkInfo&id={$listInfo['id']}", $infoLinkTitle);
            }
            ?>
			<tr id="link_id_<?php echo $listInfo['id']?>">
				<td><?php echo $infoLink?></td>
				<td><?php echo $listInfo['google_pagerank']?></td>
				<td><?php echo $listInfo['domain_authority']?></td>
				<td><?php echo $listInfo['page_authority']?></td>
				<td><?php echo $listInfo['link_title']?></td>
				<td><?php echo $listInfo['outbound_links']?></td>
				<td>
					<?php
					// display score
					if ($listInfo['link_score'] < 0) {
						$scoreClass = 'minus';
						$listInfo['link_score'] = $listInfo['link_score'] * -1;
					} else {
						$scoreClass = 'plus';
					}

					$scoreMax = $listInfo['link_score'] > 50 ? 50 : $listInfo['link_score'];
					for($b = 0; $b <= $scoreMax; $b++) {
						echo "<span class='$scoreClass' title='{$listInfo['link_score']}'>&nbsp;</span>";
					}
					?>
				</td>
				<td><?php echo $scoreInfo['label']?></td>
				<td>
					<?php if(isAdmin() || (LD_ALLOW_USER_GENREPORTS && !SP_HOSTED_VERSION)) {?>
						<?php echo $checkLink?>
					<?php } else {?>
						-
					<?php }?>
					<div id='load_id_<?php echo $listInfo['id']?>'></div>
				</td>
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