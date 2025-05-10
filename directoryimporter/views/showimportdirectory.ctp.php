<?php echo showSectionHead($pluginText['Import Directories']); ?>
<form id="actionform">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?=$pluginText['Import Directories']?></td>
		<td class="right">&nbsp;</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Directory Script Type']?>:</td>
		<td class="td_right_col">
			<select name="script_id" id="script_id" style="width: 150px;" onchange="doLoad('script_id', '<?=PLUGIN_SCRIPT_URL?>', 'content', '&action=import')">
				<?php foreach($dirScriptList as $info){?>
					<?php if($info['id'] == $dirScriptId){?>
						<option value="<?=$info['id']?>" selected><?=$info['name']?></option>
					<?php }else{?>
						<option value="<?=$info['id']?>"><?=$info['name']?></option>
					<?php }?>						
				<?php }?>
			</select>
		</td>
	</tr>		
	<tr class="white_row">
		<td class="td_left_col"><?=$spText['common']['lang']?>:</td>
		<td class="td_right_col">
			<?php echo $this->render('language/languageselectbox', 'ajax'); ?>			
		</td>
	</tr>		
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Link Type']?>:</td>
		<td class="td_right_col">
			<select name="link_type" style="width: 150px;">
				<?php foreach($helperObj->linkTypes as $type){?>
					<?php if($type == $post['link_type']){?>
						<option value="<?=$type?>" selected><?=ucfirst($type)?></option>
					<?php }else{?>
						<option value="<?=$type?>"><?=ucfirst($type)?></option>
					<?php }?>						
				<?php }?>
			</select>			
		</td>
	</tr>		
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Check Google Pagerank']?>:</td>
		<td class="td_right_col">
			<input type="checkbox" name="checkpr" value="1" <?php echo empty($post['checkpr']) ? "" : "checked"?>>	
		</td>
	</tr>		
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Check Directory Status']?>:</td>
		<td class="td_right_col">
			<input type="checkbox" name="checkstatus" value="1" <?php echo empty($post['checkstatus']) ? "" : "checked"?>>	
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Directories']?>:</td>
		<td class="td_right_col">
			<textarea name="directories" rows="10"><?=$post['directories']?></textarea><?=$errMsg['directories']?>
			<p style="font-size: 12px;"><?=$pluginText['insertdirwithcoma']?></p>
			<P style="color: black;">Eg: http://directory.seofreetools.net/submit.php , http://www.fat64.net/submit.php</P>
		</td>
	</tr>			
	<tr class="white_row">
		<td class="tab_left_bot_noborder"></td>
		<td class="tab_right_bot"></td>
	</tr>
	<tr class="listBot">
		<td class="left" colspan="1"></td>
		<td class="right"></td>
	</tr>
</table>

<div id="advanced_show">
	<a href="javascript:void(0)" onclick="showAdvancedFields('advanced_fields');"><?=$pluginText['Show Advanced Fields']?></a>
	<input type="hidden" name="show_advanced" id="show_advanced" value="<?=$post['show_advanced']?>">
</div>

<?php
$display = empty($post['show_advanced']) ? "display:none;" : ""; 
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list" id="advanced_fields" style="<?=$display?>">
	<tr class="listHead">
		<td class="left" width='30%'><?=$pluginText['Advanced Fields']?></td>
		<td class="right">&nbsp;</td>
	</tr>
	<?php
	$i = 0;
	foreach ($helperObj->dirCols as $colName) {
	    $style = ($i++ % 2) ? "blue_row" : "white_row";
	    $colVal = empty($post[$colName]) ? $dirScriptInfo[$colName] : $post[$colName];
	    ?>		
    	<tr class="<?=$style?>">
    		<td class="td_left_col"><?=$colName?></td>
    		<td class="td_right_col">
    			<input type="text" name="<?=$colName?>" value="<?=$colVal?>">
    		</td>
    	</tr>
	    <?php
	} 
	?>			
	<tr class="white_row">
		<td class="tab_left_bot_noborder"></td>
		<td class="tab_right_bot"></td>
	</tr>
	<tr class="listBot">
		<td class="left" colspan="1"></td>
		<td class="right"></td>
	</tr>
</table>	


<table width="100%" cellspacing="0" cellpadding="0" border="0" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?=pluginGETMethod('', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('actionform', 'content', 'action=doimport'); ?>
         	<a onclick="<?=$actFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>