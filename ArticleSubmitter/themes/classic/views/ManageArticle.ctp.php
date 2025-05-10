<?php
echo showSectionHead($pluginText["Article Manager"]);
$searchActFun = pluginPOSTMethod('searchForm', 'content', 'action=Article');
?>
<form id="searchForm">
<table class="search">
	<tr>
		<th><?php echo $spText['label']['Project']?>: </th>
		<td>
			<select name="project_id" id="project_id" onchange="<?php echo $searchActFun?>">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($projectList as $plist){?>
					<?php if($plist['id'] == $post['project_id']){?>
						<option value="<?php echo $plist['id']?>" selected><?php echo $plist['project']?></option>
					<?php }else{?>
						<option value="<?php echo $plist['id']?>"><?php echo $plist['project']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
    	<th><?php echo 'Keyword'?>:</th>
		<td><input type="text" class="input" name="keyword" value="<?php echo $post['keyword']?>">
         	<a onclick="<?php echo $searchActFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Search']?>
         	</a>
		</td>
	</tr>
</table>
</form>

<?php echo $pagingDiv?>

<table id="cust_tab" class="list">
	<tr>
		<th>Id</th>
		<th>Title</th>
		<th>Project</th>
		<th>Category</th>
		<th>Status</th>
		<th>Action</th>
	</tr>
	<?php
	if(count($list) > 0) {
	    foreach($list as $i => $listInfo){
	        $projectLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editArticle&article_id={$listInfo['id']}", "{$listInfo['title']}");
			?>
			<tr>
				<td width="40px"><?php echo $listInfo['id']?></td>
				<td><?php echo $projectLink?></td>				
				<td><?php echo $listInfo['project_name']?></td>
				<td><?php echo $listInfo['category']?></td>
				<td><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];	?></td>
				<td width="100px">
					<?php
						if($listInfo['status']){
							$statAction = "inactivateArticle";
							$statLabel = $spText['common']["Inactivate"];
						}else{
							$statAction = "activateArticle";
							$statLabel = $spText['common']["Activate"];
						} 
					?>
					<select name="action" id="action<?php echo $listInfo['id']?>" onchange="doASPluginAction('<?php echo PLUGIN_SCRIPT_URL?>', 'content', 'article_id=<?php echo $listInfo['id']?>&pageno=<?php echo $pageNo?>', 'action<?php echo $listInfo['id']?>')">
						<option value="">-- <?php echo $spText['common']['Select']?> --</option>
						<option value="<?php echo $statAction?>"><?php echo $statLabel?></option>
						<option value="editArticle">Edit Article</option>
						<option value="copyArticle">Copy Article</option>
						<option value="showSubmitDetails">Submit Article</option>
						<option value="deleteArticle"><?php echo $spText['common']['Delete']?></option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{
		?>
		<tr><td colspan="6"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php
	} 
	?>
</table>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a  onclick="<?php echo pluginGETMethod('action=newArticle&user_id='.$userId, 'content')?>" href="javascript:void(0);" class="actionbut"><?php echo $pluginText["New Article"]?></a>
    	</td>
	</tr>
</table>