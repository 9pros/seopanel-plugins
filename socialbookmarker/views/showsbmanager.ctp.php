<?php 
echo showSectionHead($pluginText["Social Bookmarker Manager"]);
$submitLink = pluginPOSTMethod('search_form', 'content', 'action=showsbmanager');
?>
<form id='search_form' onsubmit="return false;">
<table width="70%" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>
		<th><?=$spText['common']['Name']?>: </th>
		<td width="100px"><input type="text" name="engine_name" value="<?=$info['engine_name']?>" onblur="<?=$submitLink?>"></td>
		<th><?=$spText['common']['Status']?>: </th>
		<td>
			<select id="stscheck" name="stscheck" onchange="<?=$submitLink?>">
				<?php foreach($statusList as $key => $val){?>
					<?php if($info['stscheck'] == $val){?>
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
</form>

<?=$pagingDiv?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left"><?=$spText['common']['Rank']?></td>
		<td><?=$spText['common']['Name']?></td>
		<td><?=$pluginText['Submit Link']?></td>
		<td><?=$spText['common']['Status']?></td>
		<td class="right"><?=$spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 5; 
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
            $projectLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editSB&sb_id={$listInfo['id']}", "{$listInfo['engine_name']}")
			?>
			<tr class="<?=$class?>">
				<td class="<?=$leftBotClass?>"><?=$listInfo['rank']?></td>
				<td class="td_br_right left"><?=$projectLink?></td>
				<td class="td_br_right left"><?=$listInfo['engine_submit_link']?></td>
				<td class="td_br_right"><?php echo $listInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];?></td>
				<td class="<?=$rightBotClass?>" width="100px">
					<?php
						if($listInfo['status']){
							$statAction = "InactivateSB";
							$statLabel = $spText['common']["Inactivate"];
						}else{
							$statAction = "ActivateSB";
							$statLabel = $spText['common']["Activate"];
						} 
					?>
					<select style="width: 124px;" name="action" id="action<?=$listInfo['id']?>" onchange="doPluginAction('<?=$pgScriptPath?>', 'content', 'sb_id=<?=$listInfo['id']?>&pageno=<?=$pageNo?>', <?=$listInfo['id']?>)">
						<option value="select">-- <?=$spText['common']['Select']?> --</option>
						<option value="<?=$statAction?>"><?=$statLabel?></option>
						<option value="editSB"><?=$spText['common']['Edit']?></option>
						<option value="deleteSB"><?=$spText['common']['Delete']?></option>
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
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a onclick="<?=pluginGETMethod('action=newSB', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$pluginText['New Social Bookmarker']?>
         	</a>
    	</td>
	</tr>
</table>