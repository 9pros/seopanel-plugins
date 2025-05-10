<?php echo showSectionHead($spText['button']['Check Status']); ?>
<form id='search_form'>
<table width="88%" border="0" cellspacing="0" cellpadding="0" class="search">
	<?php $submitLink = pluginPOSTMethod('search_form', 'checkdirstat', 'action=checkdirstatus'); ?>
	<tr>
		<th><?=$spText['common']['Status']?>: </th>
		<td width="100px">
			<select name="stscheck">
				<?php foreach($statusList as $key => $val){?>
					<option value="<?=$val?>"><?=$key?></option>
				<?php }?>
			</select>
		</td>
		<th><?=$spTextDir['Captcha']?>: </th>
		<td>
			<select name="capcheck">
				<option value="">-- All --</option>
				<?php foreach($captchaList as $key => $val){?>
					<option value="<?=$val?>"><?=$key?></option>
				<?php }?>
			</select>
		</td>				
		<th><?=$spText['button']['Check Status']?>: </th>
		<td>
			<select name="check_status">
				<option value="">-- <?=$spText['common']['Select']?> --</option>
				<option value="0" selected="selected"><?=$pluginText['Not Checked']?></option>
				<option value="1"><?=$pluginText['Already Checked']?></option>				
			</select>
		</td>		
	</tr>
	<tr>
		<th><?=$spText['common']['lang']?>: </th>
		<td>
			<select name="lang_code">
				<option value="">-- <?=$spText['common']['Select']?> --</option>
				<?php
				foreach ($langList as $langInfo) {
					?>			
					<option value="<?=$langInfo['lang_code']?>"><?=$langInfo['lang_name']?></option>
					<?php
				}
				?>
			</select>
		</td>
		<th><?=$pluginText['Check Google Pagerank']?>:</th>
		<td>
			<input type="checkbox" name="checkpr" value="1" checked="checked">	
		</td>
		<td colspan="2" style="text-align: center;">
			<?php if (SP_DEMO) {?>
    			<a href="javascript:void(0);" onclick="alertDemoMsg();" class="actionbut">
    				<?=$spText['button']['Proceed']?>
    			</a>
    		<?php } else {?>
    			<a href="javascript:void(0);" onclick="showDiv('checkdirstat');<?=$submitLink?>;hideDiv('note');showDiv('showimpmsg');" class="actionbut">
    				<?=$spText['button']['Proceed']?>
    			</a>
			<?php }?>
		</td>
	</tr>
</table>
</form>
<div id="subcontent">
	<p class='note' id="note"><?=$spTextDir['clicktoproceeddirsts']?></p>
	<br>
	<div id="showimpmsg" style="display:none;"><?=$pluginText['Checking the status of directories']?>. <?=$pluginText['escapetostop']?></div>    
    <div id="checkdirstat" style="display:none;"></div>
</div>
