<?php echo showSectionHead($pluginText['Edit Directory']); ?>
<form id="actionform">
<input type="hidden" name="id" value="<?=$post['id']?>"></input>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?=$pluginText['Edit Directory']?></td>
		<td class="right">&nbsp;</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Directory Script Type']?>:</td>
		<td class="td_right_col">
			<select name="script_type_id" style="width: 150px;">
				<?php foreach($dirScriptList as $info){?>
					<?php if($info['id'] == $post['script_type_id']){?>
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
				<?php foreach($linkTypes as $type => $typeName){?>
					<?php if($type == $post['link_type']){?>
						<option value="<?=$type?>" selected><?=$typeName?></option>
					<?php }else{?>
						<option value="<?=$type?>"><?=$typeName?></option>
					<?php }?>						
				<?php }?>
			</select>			
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Directory Submit Url']?>:</td>
		<td class="td_right_col">
			<input name="submit_url" type="text" value="<?=$post['submit_url']?>" style="width: 450px"><?=$errMsg['submit_url']?>
			<P style="color: black;">Eg: http://directory.seofreetools.net/submit.php</P>
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
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('actionform', 'content', 'action=updatedir'); ?>
         	<a onclick="<?=$actFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>