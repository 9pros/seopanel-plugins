<?php  echo showSectionHead("Search Results");?>

<table id="cust_tab">
	<tr class="listHead">
		<th><?php echo $spText['common']['Details']?></th>
		<th>&nbsp;</th>
	</tr>
	<?php
	if (count ( $resultList ) > 0) {
		foreach ( $resultList as $listInfo ) {
			?>
			<tr>
				<td id="content_area_<?php echo $listInfo['rank']?>">
					<?php echo  stripslashes($listInfo['title']);?>
					<p><?php echo  stripslashes($listInfo['description']);?></p>
				</td>
				<td><input type="checkbox" name="cont_selected" value="<?php echo $listInfo['rank']; ?>"/></td>
			</tr>
			<?php
		}
	} else {
		?>
		<tr><td colspan="2"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php
	}
	?>
</table>
<table class="actionSec">
	<tr>
		<td>
    		<a href="javascript:void(0);" class="actionbut" id="add_to_article" onclick="addSearchResultsToArticle()">Add To Article</a>
		</td>
	</tr>
</table>