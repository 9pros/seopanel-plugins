<?php
echo showSectionHead($pluginText['Connection Manager']);
Session::showSessionMessges();
$searchFun = "scriptDoLoadPost('$pgScriptPath', 'listform', 'content')";
?>
<form name="listform" id="listform" onsubmit="<?php echo $searchFun?>">
<table class="search">
	<tr>
		<th><?php echo $spText['button']['Search']?>: </th>
		<td><input type="text" name="keyword" value="<?php echo htmlentities($post['keyword'], ENT_QUOTES)?>"></td>
		<?php if(isAdmin()){ ?>
			<th><?php echo $spText['common']['User']?>: </th>
			<td>
				<select name="user_id" onchange="<?php echo $searchFun?>">
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
        <?php } ?>
	</tr>
	<tr>
		<th><?php echo $spText['common']['Source']?> : </th>
		<td>
			<select name="resource_id" onchange="<?php echo $searchFun?>">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($resourceList as $resourceInfo){?>
					<?php if($resourceInfo['id'] == $post['resource_id']){?>
						<option value="<?php echo $resourceInfo['id']?>" selected><?php echo $resourceInfo['engine_name']?></option>
					<?php }else{?>
						<option value="<?php echo $resourceInfo['id']?>"><?php echo $resourceInfo['engine_name']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<th><?php echo $spText['common']['Status']?> : </th>
		<td>
			<select name="connection_status" onchange="<?php echo $searchFun?>">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($connStatusList as $statAction => $statLabel){?>
					<?php if($statAction == $post['connection_status']){?>
						<option value="<?php echo $statAction?>" selected><?php echo $statLabel?></option>
					<?php }else{?>
						<option value="<?php echo $statAction?>"><?php echo $statLabel?></option>
					<?php }?>
				<?php }?>
			</select>
			&nbsp;&nbsp;
			<a href="javascript:void(0);" onclick="<?php echo $searchFun?>" class="actionbut"><?php echo $spText['button']['Show Records']?></a>
		</td>
	</tr>
</table>
</form>

<?php echo $pagingDiv?>
<table class="list">
	<tr class="listHead">
		<td><?php echo $spText['common']['Id']?></td>
		<td><?php echo $spText['common']['Name']?></td>
		<td><?php echo $spText['common']['Source']?></td>
		<td><?php echo $spText['common']['User']?></td>
		<td><?php echo $spText['common']['Status']?></td>
		<td><?php echo $spText['common']['Action']?></td>
		<td><?php echo $spText['common']['Results']?></td>
		<td>Connection Date</td>
		<td>Account</td>
		<td width="100px"><?php echo $spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 10;
	if (count($list) > 0) {
		foreach($list as $i => $listInfo){
		    $sourceLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editConnection&id={$listInfo['id']}", "{$listInfo['connection_name']}");
			?>
			<tr>
				<td><?php echo $listInfo['id']?></td>
				<td><?php echo $sourceLink?></td>
				<td style="width: 110px;">
					<i class="fab fa-<?php echo strtolower($listInfo['engine_name'])?>"></i>
					<?php echo $listInfo['engine_name']?>
				</td>
				<td><?php echo $listInfo['username']?></td>
				<td>
					<?php
					if ($listInfo['connection_status'] == 'connected') {
						echo "<b class='success'>{$spTextMyAccount['Connected']}</b>";
					} else {
						echo "<b class='error'>{$spTextMyAccount['Disconnected']}</b>";
					}
					?>
				</td>
				<td>
					<?php
					if (SP_DEMO) {
						$connectImg = PLUGIN_IMGPATH . "/" . strtolower($listInfo['engine_name']) . "_connect.png";
						echo ($listInfo['connection_status'] == 'connected') ? "" : "<img src='$connectImg' style='width:130px;height:31px;border-radius:4px'>";
					} else {					
						if ($listInfo['connection_status'] == 'connected') {
						    echo confirmScriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=removeSMConnection&id={$listInfo['id']}", "{$spTextMyAccount['Disconnect']}");
						} else {
							$connectImg = PLUGIN_IMGPATH . "/" . strtolower($listInfo['engine_name']) . "_connect.png"; 
						    echo scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=doSMConnection&id={$listInfo['id']}", "<img src='$connectImg' style='width:130px;height:31px;border-radius:4px'>");
						}
					}
					?>
				</td>
				<td><?php echo $listInfo['connection_log']?></td>
				<td><?php echo $listInfo['connection_date'] == '0000-00-00 00:00:00' ? "" : $listInfo['connection_date']?></td>
				<td><?php echo $listInfo['account_name']?></td>
                <td>
    				<select name="action" id="action<?php echo $listInfo['id']?>" style="width: 200px;" 
    					onchange="doSMEPluginAction('<?php echo $pgScriptPath?>', 'content', 'id=<?php echo $listInfo['id']?>&pageno=<?php echo $pageNo?>', <?php echo $listInfo['id']?>)">
    					<option value="select">-- <?php echo $spText['common']['Select']?> --</option>
    					<?php if ($listInfo['connection_status'] == 'connected') {?>
    						<option value="removeSMConnection"><?php echo $spTextMyAccount['Disconnect']?></option>
    						<?php if (!in_array($engineName, ['Twitter', 'LinkedIn'])) {?>
    							<option value="crawlSubmissionPages">Crawl Submission Pages</option>
							<?php }?>
    					<?php } else {?>
    						<option value="doSMConnection"><?php echo $spTextMyAccount['Connect']?></option>
    					<?php }?>
    					<option value="editConnection"><?php echo $spText['common']['Edit']?></option>
    					<option value="deleteConnection"><?php echo $spText['common']['Delete']?></option>    					
    				</select>
				</td>
			</tr>
			<?php
		}
	} else {
		echo showNoRecordsList($colCount - 2);
	}
	?>
</table>

<table class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a  onclick="<?php echo pluginGETMethod('action=newConnection', 'content')?>" href="javascript:void(0);" class="actionbut"><?php echo $pluginText['New Connection']?></a>
    	</td>
	</tr>
</table>