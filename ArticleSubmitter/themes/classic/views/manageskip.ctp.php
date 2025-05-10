<?php echo  showSectionHead($pluginText["Skipped Submission"]);?>
<table width="50%" class="search">
	<tr>
		<th><?php echo $pluginText["Article"]?>: </th>
		<td>
			<select name="article_id" id="article_id" onchange="doLoad('article_id', '<?php echo PLUGIN_SCRIPT_URL?>&action=skippedSubmittions', 'content')">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($articleList as $alist){?>
					<?php if($alist['id'] == $articleId){?>
						<option value="<?php echo $alist['id']?>" selected><?php echo $alist['title']?></option>
					<?php }else{?>
						<option value="<?php echo $alist['id']?>"><?php echo $alist['title']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
	</tr>
</table>
<?php echo $pagingDiv?>
<table width="100%" id="cust_tab">
	<tr class="form_head">
		<th><?php echo $spText['common']['Id']?></th>
		<th><?php echo $spText['common']['Website']?></th>
		<th><?php echo $pluginText["Article"]?></th>
		<th><?php echo $spText['common']['Action']?></th>
	</tr>
	<?php
	if(count($list) > 0) {
		foreach($list as $i => $listInfo){?>
			<tr>
				<td><?php echo $listInfo['id']?></td>
				<td><?php echo $listInfo['website_name']?></td>
				<td><?php echo $listInfo['title']?></td>				
                <td>
					<a onclick="<?php echo pluginConfirmGETMethod('action=unskip&skipid='.$listInfo['id'].'&pageno='.$pageNo.'&article_id='.$articleId, 'content')?>" href="javascript:void(0);" >
						<?php echo $pluginText["Undo Skip"]?>
					</a>
                </td>
			</tr>
			<?php
		}
	} else {
	    ?>
		<tr><td colspan="4"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php
	}
	?>
</table>