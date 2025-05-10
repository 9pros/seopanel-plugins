<?php 
echo showSectionHead($pluginText["Email Manager"]);
$submitLink = pluginPOSTMethod('listform', 'content', 'action=managerEmail');
?>
<form name="listform" id="listform">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>
		<th><?=$pluginText['Email List']?>: </th>
		<td>
			<select name="email_list_id" onchange="<?=$submitLink?>">
				<?php foreach($elList as $elInfo){?>
					<?php if($elInfo['id'] == $post['email_list_id']){?>
						<option value="<?=$elInfo['id']?>" selected><?=$elInfo['name']?></option>
					<?php }else{?>
						<option value="<?=$elInfo['id']?>"><?=$elInfo['name']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<th><?=$spText['login']['Email']?>: </th>
		<td width="100px"><input type="text" name="email" value="<?=$post['email']?>" onblur="<?=$submitLink?>"></td>
		<th><?=$spText['common']['Name']?>: </th>
		<td width="100px" colspan="2"><input type="text" name="name" value="<?=$post['name']?>" onblur="<?=$submitLink?>"></td>
	</tr>
	<tr>
		<th><?=$pluginText['Source']?>: </th>
		<td>
			<select name="source" onchange="<?=$submitLink?>">
				<option value="">-- <?=$spText['common']['Select']?> --</option>
				<?php foreach($sourceList as $sourceInfo){?>
					<?php if($post['source'] == $sourceInfo['source']){?>
						<option value="<?=$sourceInfo['source']?>" selected><?=$sourceInfo['source']?></option>
					<?php }else{?>
						<option value="<?=$sourceInfo['source']?>"><?=$sourceInfo['source']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<th><?=$pluginText['Subscribed']?>: </th>
		<td>
			<select name="subscribed" onchange="<?=$submitLink?>">
				<?php foreach($labelList as $key => $val){?>
					<?php if($post['subscribed'] == $val){?>
						<option value="<?=$val?>" selected><?=$key?></option>
					<?php }else{?>
						<option value="<?=$val?>"><?=$key?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<th><?=$spText['common']['Status']?>: </th>
		<td>
			<select name="status" onchange="<?=$submitLink?>">
				<?php foreach($statusList as $key => $val){?>
					<?php if($post['status'] == $val){?>
						<option value="<?=$val?>" selected><?=$key?></option>
					<?php }else{?>
						<option value="<?=$val?>"><?=$key?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<td style="text-align: center;">
			<a href="javascript:void(0);" onclick="<?=$submitLink?>" class="actionbut">
				<?=$spText['button']['Show Records']?>
			</a>
		</td>
	</tr>
</table>

<?=$pagingDiv?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="leftid"><input type="checkbox" id="checkall" onclick="checkList('checkall')"></td>
		<td><?=$spText['login']['Email']?></td>
		<td><?=$spText['common']['Name']?></td>
		<td><?=$pluginText['Subscribed']?></td>
		<td><?=$pluginText['Source']?></td>
		<td><?=$spText['common']['Status']?></td>
		<td class="right"><?=$spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 7; 
	if(count($list) > 0){
		$catCount = count($list);
		foreach($list as $i => $listInfo){
			$class = ($i % 2) ? "blue_row" : "white_row";
            if($catCount == ($i + 1)){
                $leftBotClass = "tab_left_bot";
                $rightBotClass = "tab_right_bot";
            }else{
                $leftBotClass = "td_left_border td_br_right";
                $rightBotClass = "td_br_right";
            }
            $editLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editEmail&email_id={$listInfo['id']}", "{$listInfo['email']}");            
			?>
			<tr class="<?=$class?>">
				<td class="<?=$leftBotClass?>"><input type="checkbox" name="ids[]" value="<?=$listInfo['id']?>"></td>
				<td class="td_br_right left"><?=$editLink?></td>
				<td class="td_br_right left"><?=$listInfo['name']?></td>
				<td class="td_br_right">
					<?php
					if ($listInfo['subscribed']) {
					    $actionVal = 'unsubscribe';
					    $linkText = $spText['common']["Yes"];
					} else{
					    $actionVal = 'subscribe';
					    $linkText = $spText['common']["No"];
					}
					$subscribeLink = scriptAJAXLinkHref($pgScriptPath."&pageno=$pageNo", 'content', "action=changesubscibe&email_id={$listInfo['id']}&subscribe_val=".$listInfo['subscribed'], $linkText); 
					echo $subscribeLink;
					?>
				</td>
				<td class="td_br_right"><?=$listInfo['source']?></td>
				<td class="td_br_right"><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];?></td>
				<td class="<?=$rightBotClass?>" width="100px">
					<?php
						if($listInfo['status']){
							$statAction = "InactivateEmail";
							$statLabel = $spText['common']["Inactivate"];
						}else{
							$statAction = "ActivateEmail";
							$statLabel = $spText['common']["Activate"];
						} 
					?>
					<select style="width: 124px;" name="action" id="action<?=$listInfo['id']?>" onchange="doPluginAction('<?=$pgScriptPath?>', 'content', 'email_id=<?=$listInfo['id']?>&pageno=<?=$pageNo?>', <?=$listInfo['id']?>)">
						<option value="select">-- <?=$spText['common']['Select']?> --</option>
						<option value="<?=$statAction?>"><?=$statLabel?></option>
						<option value="editEmail"><?=$spText['common']['Edit']?></option>
						<option value="deleteEmail"><?=$spText['common']['Delete']?></option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{	 
		echo showNoRecordsList($colCount-2);		
	} 
	?>
	<tr class="listBot">
		<td class="left" colspan="<?=($colCount-1)?>"></td>
		<td class="right"></td>
	</tr>
</table>
<?php
if (SP_DEMO) {
    $subFun = $unSubFun = $actFun = $inactFun = $delFun = "alertDemoMsg()";
} else {
    $subFun = pluginConfirmPOSTMethod('listform', 'content', 'action=changesubscribeall&subscribe_val=1&pageno='.$pageNo);
    $unSubFun = pluginConfirmPOSTMethod('listform', 'content', 'action=changesubscribeall&subscribe_val=0&pageno='.$pageNo);
    $actFun = pluginConfirmPOSTMethod('listform', 'content', 'action=activateallemail&pageno='.$pageNo);
    $inactFun = pluginConfirmPOSTMethod('listform', 'content', 'action=inactivateallemail&pageno='.$pageNo);
    $delFun = pluginConfirmPOSTMethod('listform', 'content', 'action=deleteallemail&pageno='.$pageNo);
}   
?>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a onclick="<?=pluginGETMethod('action=importEmail&email_list_id='.$emailListId, 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$pluginText['Import Emails']?>
         	</a>&nbsp;&nbsp;
         	<a onclick="<?=$subFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$pluginText["Subscribe"]?>
         	</a>&nbsp;&nbsp;
         	<a onclick="<?=$unSubFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$pluginText["Unsubscribe"]?>
         	</a>&nbsp;&nbsp;
         	<a onclick="<?=$actFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['common']["Activate"]?>
         	</a>&nbsp;&nbsp;
         	<a onclick="<?=$inactFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['common']["Inactivate"]?>
         	</a>&nbsp;&nbsp;
         	<a onclick="<?=$delFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['common']['Delete']?>
    	</td>
	</tr>
</table>
</form>