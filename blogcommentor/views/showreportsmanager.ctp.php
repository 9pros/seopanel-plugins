<?php if ($showhead) {?>
<?php 
echo showSectionHead($pluginText["View Reports"]);
$actionVal = pluginPOSTMethod('search_form', 'subcontent', 'action=viewreports&showhead=0');
?>
<form id='search_form'>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>
		<th><?php echo $spText['common']['Website']?>: </th>
		<td>
			<select name="website_id" id="website_id" onchange="doLoad('website_id', '<?php echo PLUGIN_SCRIPT_URL?>&action=showprjselbox', 'project_sel_div')">
				<?php foreach($websiteList as $websiteInfo){?>
					<?php if($websiteInfo['id'] == $websiteId){?>
						<option value="<?php echo $websiteInfo['id']?>" selected><?php echo $websiteInfo['name']?></option>
					<?php }else{?>
						<option value="<?php echo $websiteInfo['id']?>"><?php echo $websiteInfo['name']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<th><?php echo $spText['label']['Project']?>: </th>
		<td id="project_sel_div">
			<?php echo $this->pluginRender('showprjselbox', 'ajax'); ?>
		</td>
		<th><?php echo $spText['common']['Status']?>: </th>
		<td>
			<select name="status" onchange="<?php echo $actionVal?>">
				<option value="active"><?php echo $spText['common']['Active']?></option>
				<option value="inactive"><?php echo $spText['common']['Inactive']?></option>
			</select>
		</td>
	</tr>
	<tr>
		<th><?php echo $pluginText['Submitted']?>: </th>
		<td>
			<select name="submitted" onchange="<?php echo $actionVal?>">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<option value="yes"><?php echo $spText['common']['Yes']?></option>
				<option value="no"><?php echo $spText['common']['No']?></option>
			</select>
		</td>
		<th><?php echo $pluginText['Approved']?>: </th>
		<td>
			<select name="approved" onchange="<?php echo $actionVal?>">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<option value="yes"><?php echo $spText['common']['Yes']?></option>
				<option value="no"><?php echo $spText['common']['No']?></option>
			</select>
		</td>
		<td colspan="2">			
			<a href="javascript:void(0);" onclick="<?php echo $actionVal?>" class="actionbut">
				<?php echo $spText['button']['Show Records']?>
			</a>
		</td>
	</tr>
</table>
</form>

<div id="subcontent" style="margin-top: 16px;">
<?php } ?>

<?php echo $pagingDiv?>

<?php 
if (empty($projectId)) showErrorMsg($pluginText['No active projects found']."!");
?>

<table class="list" style="margin-top: 18px;">
	<tr class="listHead">
		<td><?php echo $spText['common']['No']?></td>
		<td><?php echo $spText['common']['Url']?></td>
		<td><?php echo $pluginText['Submitted']?></td>
		<td><?php echo $pluginText['Approved']?></td>
		<td><?php echo $spText['common']['Status']?></td>
		<td><?php echo $spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 6;
	$countStart = empty($pageNo) ? 0 : $pageNo - 1; 
	$countStart = ($countStart * SP_PAGINGNO) + 1; 
	if(count($list) > 0){
		$catCount = count($list);
		foreach($list as $i => $listInfo){
            $projectLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editproject&project_id={$listInfo['id']}", "{$listInfo['keyword']}");
			?>
			<tr>
				<td><?php echo ($countStart + $i)?></td>
				<td id='seresult'>
					<a href='<?php echo $listInfo['url']?>' target='_blank'><?php echo stripslashes($listInfo['title']);?></a>
					<p><?php echo stripslashes($listInfo['description']);?><p>
					<label><?php echo $listInfo['url']?></label>
				</td>
				<td id="submitted_<?php echo $listInfo['id']?>">
				    <?php echo $listInfo['submitted'] ? $spText['common']['Yes'] : $spText['common']['No']; ?>
			    </td>
				<td id="approved_<?php echo $listInfo['id']?>">
				    <?php echo $listInfo['approved'] ? $spText['common']['Yes'] : $spText['common']['No']; ?>
			    </td>
				<td id="status_<?php echo $listInfo['id']?>"><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];	?></td>
				<td class="<?php echo $rightBotClass?>" width="100px">
					<?php
						if($listInfo['status']){
							$statAction = "linkinactivate";
							$statLabel = $spText['common']["Inactivate"];
						}else{
							$statAction = "linkactivate";
							$statLabel = $spText['common']["Activate"];
						} 
					?>
					<select style="width:105px;" name="action" id="action<?php echo $listInfo['id']?>" onchange="doBlogCommenterPluginAction('<?php echo $pgScriptPath?>', 'subcontent', 'blog_id=<?php echo $listInfo['id']?>&pageno=<?php echo $pageNo?>&showhead=0', <?php echo $listInfo['id']?>)">
						<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
						<?php if (empty($listInfo['submitted']) && (isAdmin() || BC_ALLOW_USER_SUBMIT_COMM)) {?>
							<option value="submitcomment"><?php echo $pluginText['Submit Comment']?></option>
						<?php } ?>
						<?php if ($listInfo['submitted']) {?>
							<option value="checkSubmission"><?php echo $pluginText['Check Submission']?></option>
						<?php } ?>
						<option value="checkStatus"><?php echo $spText['button']['Check Status']?></option>
						<option value="<?php echo $statAction?>"><?php echo $statLabel?></option>
						<option value="deletebloglink"><?php echo $spText['common']['Delete']?></option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{	 
		echo showNoRecordsList($colCount-2);		
	} 
	?>
</table>

<?php if ($showhead) {?>
</div>
<?php }?>