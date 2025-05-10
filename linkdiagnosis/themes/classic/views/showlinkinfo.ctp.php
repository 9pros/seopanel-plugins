<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?php echo $pluginText['Backlinks Reports']?></td>
		<td class="right">&nbsp;</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $pluginText['Backlink Url']?>:</td>
		<td class="td_right_col"><a target="_blank" href="<?php echo $linkInfo['url']?>"><?php echo wordwrap($linkInfo['url'], 100, "<br>", true);?></a></td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $pluginText['Link Found']?>:</td>
		<td class="td_right_col"><?php echo $linkInfo['url_found']?></td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $_SESSION['text']['common']['MOZ Rank']?>:</td>
		<td class="td_right_col"><?php echo $linkInfo['google_pagerank']?></td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $_SESSION['text']['common']['Domain Authority']?>:</td>
		<td class="td_right_col"><?php echo $linkInfo['domain_authority']?></td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $_SESSION['text']['common']['Page Authority']?>:</td>
		<td class="td_right_col"><?php echo $linkInfo['page_authority']?></td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $pluginText['Anchor']?>:</td>
		<td class="td_right_col"><?php echo $linkInfo['link_title']?></td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $pluginText['Outbound Links']?>:</td>
		<td class="td_right_col"><?php echo $linkInfo['outbound_links']?></td>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $_SESSION['text']['label']['Score']?>:</td>
		<td class="td_right_col"><?php echo $linkInfo['link_score']?></td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $pluginText['Link Type']?>:</td>
		<td class="td_right_col"><?php echo $scoreInfo['label']?></td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $_SESSION['text']['label']['Comments']?>:</td>
		<td class="td_right_col"><?php echo $scoreInfo['comments']?></td>
	</tr>
</table>
<br><br>