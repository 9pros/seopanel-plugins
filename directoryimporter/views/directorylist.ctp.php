<?php echo showSectionHead($spTextPanel['Directory Manager']); ?>
<form id='search_form'>
<table width="88%" border="0" cellspacing="0" cellpadding="0" class="search">
	<?php $submitLink = pluginPOSTMethod('search_form', 'content'); ?>
	<tr>
		<th><?=$spText['common']['Directory']?>: </th>
		<td width="100px"><input type="text" name="dir_name" value="<?=$info['dir_name']?>" onblur="<?=$submitLink?>"></td>
		<th><?=$spText['common']['Status']?>: </th>
		<td width="100px">
			<select name="stscheck" onchange="<?=$submitLink?>">
				<?php foreach($statusList as $key => $val){?>
					<?php if(preg_match('/^\d+$/', $info['stscheck']) && ($info['stscheck'] == $val)) {?>
						<option value="<?=$val?>" selected><?=$key?></option>
					<?php }else{?>
						<option value="<?=$val?>"><?=$key?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<th><?=$spTextDir['Captcha']?>: </th>
		<td>
			<select name="capcheck" onchange="<?=$submitLink?>">
				<option value="">-- All --</option>
				<?php foreach($captchaList as $key => $val){?>
					<?php if($info['capcheck'] == $val){?>
						<option value="<?=$val?>" selected><?=$key?></option>
					<?php }else{?>
						<option value="<?=$val?>"><?=$key?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>		
	</tr>
	<tr>		
		<th><?=$spText['common']['Google Pagerank']?>: </th>
		<td>
			<select name="google_pagerank" onchange="<?=$submitLink?>">
				<option value="">-- <?=$spText['common']['Select']?> --</option>				
				<?php
				for ($i=0; $i<=10; $i++) {
					$selected = (preg_match('/^\d+$/', $info['google_pagerank']) && ($i == $info['google_pagerank'])) ? "selected" : "";					
					?>			
					<option value="<?=$i?>" <?=$selected?>>PR <?=$i?></option>
					<?php
				}
				?>
			</select>
		</td>
		<th><?=$spText['common']['lang']?>: </th>
		<td>
			<select name="lang_code" onchange="<?=$submitLink?>">
				<option value="">-- <?=$spText['common']['Select']?> --</option>
				<?php
				foreach ($langList as $langInfo) {
					$selected = ($langInfo['lang_code'] == $info['lang_code']) ? "selected" : "";
					?>			
					<option value="<?=$langInfo['lang_code']?>" <?=$selected?>><?=$langInfo['lang_name']?></option>
					<?php
				}
				?>
			</select>
		</td>
		<td colspan="2" style="text-align: center;">
			<a href="javascript:void(0);" onclick="<?=$submitLink?>" class="actionbut">
				<?=$spText['button']['Show Records']?>
			</a>
		</td>
	</tr>
</table>
<br></br>
</form>
<?=$pagingDiv?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left"><?=$spText['common']['Id']?></td>		
		<td><?=$spText['common']['Website']?></td>
		<td>PR</td>
		<td><?=$spTextDir['Captcha']?></td>
		<td><?=$spText['common']['lang']?></td>
		<td><?=$pluginText['Type']?></td>
		<td><?=$pluginText['Script']?></td>
		<td><?=$spText['common']['Status']?></td>
		<td class="right"><?=$spText['common']['Action']?></td>
	</tr>
	<?php
	$colCount = 9;
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
            
            $statusLink = $dirCtrler->getStatusLink($listInfo['id'], $listInfo['working']);
            $checkUrl = "directories.php?sec=checkdir&dir_id={$listInfo['id']}&nodebug=1&checkpr=1";
            $checkDiv = "status_{$listInfo['id']}";
            $allUrl = $pgScriptPath."&dirid={$listInfo['id']}&pageno=$pageNo";
            
            $typeOptions = "";
            $dirScriptInfo = $dirScriptList[$listInfo['script_type_id']];
            foreach ($helperObj->linkTypes as $type) {
                $selected = stristr($listInfo['extra_val'], $dirScriptInfo['link_type_col'].'='.$dirScriptInfo[$type]) ? "selected" : "";
                $typeOptions .= "<option value='{$dirScriptInfo[$type]}' $selected>".ucfirst($type)."</option>";
            }
            $onchangeFunc = SP_DEMO ? "alertDemoMsg()" : "doLoad('link_type_{$listInfo['id']}', '".PLUGIN_SCRIPT_URL."&dirid={$listInfo['id']}&action=settype', 'tmp')";          
            ?>
			<tr class="<?=$class?>">
				<td class="<?=$leftBotClass?>"><?=$listInfo['id']?></td>				
				<td class="td_br_right left"><a target="_blank" href="<?=$listInfo['submit_url']?>"><?php echo str_replace('http://', '', $listInfo['domain']); ?></a></td>
				<td class="td_br_right" id="pr_<?=$listInfo['id']?>"><?=$listInfo['google_pagerank']?></td>
				<td class="td_br_right" id="captcha_<?=$listInfo['id']?>"><?php echo $listInfo['is_captcha'] ? $spText['common']["Yes"] : $spText['common']["No"];	?></td>
				<td class="td_br_right"><?=$listInfo['lang_name']?></td>
				<td class="td_br_right">
					<select name="link_type_<?=$listInfo['id']?>" id="link_type_<?=$listInfo['id']?>" onchange="<?=$onchangeFunc?>">
					    <?=$typeOptions?>
					</select>
				</td>
				<td class="td_br_right"><?=$listInfo['scriptname']?></td>
				<td class="td_br_right" id="status_<?=$listInfo['id']?>"><?php echo $statusLink;?></td>
				<td class="<?=$rightBotClass?>" width="100px">
				    <select name="action" id="action<?=$listInfo['id']?>" onchange="doDIAction('<?=$allUrl?>', 'content', '<?=$checkUrl?>', '<?=$checkDiv?>', 'action<?=$listInfo['id']?>')">
						<option value="select">-- <?=$spText['common']['Select']?> --</option>
						<option value="checkdir"><?=$spText['button']["Check Status"]?></option>
						<option value="editdir"><?=$spText['common']['Edit']?></option>
						<option value="deletedir"><?=$spText['common']['Delete']?></option>
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
         	<a onclick="<?=pluginGETMethod('action=import', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$pluginText['Import Directories']?>
         	</a>
    	</td>
	</tr>
</table>
