<?php echo  showSectionHead($pluginText["Article Directory Manager"]);?>

<form id="searchForm">
<table class="search" width="100%">
    <tr>
    	<?php if (isAdmin()) {?>
    		<th><?php echo $spText['common']['User']?>:</th>
    		<td>
    			<select name="user_id" >
    				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
    				<?php foreach($userList as $userInfo){?>
    					<?php if($userInfo['id'] == $post['user_id']){?>
    						<option value="<?php echo $userInfo['id']?>" selected><?php echo $userInfo['username']?></option>
    					<?php }else{?>
    						<option value="<?php echo $userInfo['id']?>"><?php echo $userInfo['username']?></option>
    					<?php }?>						
    				<?php }?>
    			</select>
    		</td>
    	<?php }?>    	
		<th><?php echo $spText['common']['Status']?>:</th>
		<td>
			<select name="status">
				<option value="-1">-- <?php echo $spText['common']['Select']?> --</option>
				<option value="1" <?php echo ($post['status'] == 1) ? "selected" : "";?> ><?php echo $spText['common']["Active"]?></option>
				<option value="0" <?php echo (isset($post['status']) && $post['status'] == 0) ? "selected" : "";?> ><?php echo $spText['common']["Inactive"]?></option>
	        </select>
    	</td>
		<th><?php echo  'Type'?>:</th>
		<td>
			<select name="type">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach ($scriptType as $type => $typeName) {?>
					<option value="<?php echo $type?>" <?php echo ($type == $post['type']) ? "selected" : "";?> ><?php echo $typeName?></option>
				<?php }?>
	        </select>
    	</td>
    </tr>
    <tr>	
    	<?php if (isAdmin()) {?>
    		<th>Public:</th>
    		<td>
    			<select name="public">
    				<option value="-1">-- <?php echo $spText['common']['Select']?> --</option>
    				<option value="1" <?php echo ($post['public'] == 1) ? "selected" : "";?> ><?php echo $spText['common']["Yes"]?></option>
    				<option value="0" <?php echo (isset($post['public']) && $post['public'] == 0) ? "selected" : "";?> ><?php echo $spText['common']["No"]?></option>
    	        </select>
        	</td>
    	<?php }?>
    	<th><?php echo 'Keyword'?>:</th>
		<td colspan="3">
			<input type="text" class="input" name="keyword" value="<?php echo $post['keyword']?>">
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginPOSTMethod('searchForm', 'content', 'action=website'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Search']?>
         	</a>
		</td>
    </tr>
</table>
</form>

<?php echo $pagingDiv?>

<table id="cust_tab">
	<tr>
		<th><?php echo $spText['common']['Id']?></th>
		<th><?php echo $spText['common']['Website']?></th>
		<th><?php echo 'Type'?></th>
        <th><?php echo $spText['common']['Url']?></th>
        <th><?php echo 'Public'?></th>
		<th><?php echo $spText['common']['Status']?></th>
		<th><?php echo $spText['common']['Action']?></th>
	</tr>
	<?php
	if(count($list) > 0) {
		foreach($list as $i => $listInfo){
			$projectLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editwebsite&website_id={$listInfo['id']}", "{$listInfo['website_name']}");
			?>
			<tr>
				<td width="40px"><?php echo $listInfo['id']?></td>
				<td><?php echo $projectLink?></td>				
				<td><?php echo $scriptType[$listInfo['type']]?></td>
				<td><?php echo $listInfo['website_url']?></td>
				<td><?php echo $listInfo['public']? $spText['common']["Yes"] : $spText['common']["No"];	?></td>
				<td><?php echo  $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];	?></td>
				<td width="100px">
					<?php
						if($listInfo['status']){
							$statAction = "InactivateWebsite";
							$statLabel = $spText['common']["Inactivate"];
						}else{
							$statAction = "ActivateWebsite";
							$statLabel = $spText['common']["Activate"];
						} 
					?>
					<select name="action" id="action<?php echo $listInfo['id']?>" onchange="doASPluginAction('<?php echo PLUGIN_SCRIPT_URL?>', 'content', 'website_id=<?php echo $listInfo['id']?>&pageno=<?php echo $pageNo?>', 'action<?php echo $listInfo['id']?>')">
						<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
						<option value="<?php echo $statAction?>"><?php echo $statLabel?></option>
                        <option value="editwebsite"><?php echo $spText['common']['Edit']?></option>
                        <option value="checkwebsitestatus"><?php echo $spText['button']['Check Status']?></option>
					    <option value="deletewebsite"><?php echo $spText['common']['Delete']?></option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{
		?>
		<tr><td colspan="7"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php
	} 
	?>
</table>
<br>
<table width="100%" class="actionSec">
	<tr>
    	<td>
         	<a onclick="<?php echo pluginGETMethod('action=newwebsite', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $pluginText['New Article Directory']?>
         	</a>
    	</td>
	</tr>
</table>