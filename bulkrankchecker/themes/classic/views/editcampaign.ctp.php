<?php 
$headMsg = ($sec == 'update') ? $pluginText['Edit Campaign'] : $pluginText['New Campaign'];
echo showSectionHead($headMsg);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'content', 'action='.$sec.'Campaign');
?>
<form id="projectform">
<?php if ($sec == 'update') {?>
	<input type="hidden" name="id" value="<?php echo $post['id']?>"/>
<?php }?>
<table width="100%" class="list">
	<tr class="listHead">
		<td class="left" width='30%' colspan="2"><?php echo $headMsg?></td>
	</tr>
	<?php if(!empty($isAdmin)){ ?>	
		<tr class="blue_row">
			<td class="td_left_col"><?php echo $spText['common']['User']?>:</td>
			<td class="td_right_col">
				<select name="user_id" style="width:150px;">
					<?php foreach($userList as $userInfo){?>
						<?php if($userInfo['id'] == $post['user_id']){?>
							<option value="<?php echo $userInfo['id']?>" selected><?php echo $userInfo['username']?></option>
						<?php }else{?>
							<option value="<?php echo $userInfo['id']?>"><?php echo $userInfo['username']?></option>
						<?php }?>						
					<?php }?>
				</select>
				<?php echo $errMsg['user_id']?>
			</td>
		</tr>
	<?php }?>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $spText['common']['Name']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="name" value="<?php echo $post['name']?>"><?php echo $errMsg['name']?>
		</td>
	</tr>
			
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $spText['common']['lang']?>:</td>
		<td class="td_right_col">
			<?php echo  $this->render('language/languageselectbox', 'ajax'); ?>			
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $spText['common']['Country']?>:</td>
		<td class="td_right_col">
			<?php echo  $this->render('country/countryselectbox', 'ajax'); ?>
		</td>
	</tr>
	<?php $post['searchengines'] = is_array($post['searchengines']) ? $post['searchengines'] : array(); ?>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $spText['common']['Search Engine']?>:</td>
		<td class="td_right_col">
			<select name="searchengines[]" class="multi" multiple="multiple" id="searchengines">				
				<?php foreach($seList as $seInfo){?>
					<?php $selected = in_array($seInfo['id'], $post['searchengines']) ? "selected" : ""?>
					<option value="<?php echo $seInfo['id']?>" <?php echo $selected?>><?php echo $seInfo['domain']?></option>
				<?php }?>
			</select>
			<?php echo $errMsg['searchengines']?>
			<?php echo $errMsg['brc_search_engine_count']?>
			<br>
			<input type="checkbox" id="select_all" onclick="selectAllOptions('searchengines', true); $('clear_all').checked=false;"> <?php echo $spText['label']['Select All']?>
			&nbsp;&nbsp;
			<input type="checkbox" id="clear_all" onclick="selectAllOptions('searchengines', false); $('select_all').checked=false;"> <?php echo $spText['label']['Clear All']?>
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?php echo $spText['common']['Keywords']?>:</td>
		<td class="td_right_col">
			<textarea name="keywords" rows="10"><?php echo $post['keywords']?></textarea>
			<?php echo $errMsg['keywords']?>
			<?php echo $errMsg['brc_keyword_count']?>
			<p style="font-size: 12px;"><?php echo $spTextKeyword['Insert keywords separated with comma']?></p>
			<P><b>Eg:</b> google seo tools,seo tools,seo</P>
		</td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col">Links:</td>
		<td class="td_right_col">
			<textarea name="links" rows="10"><?php echo $post['links']?></textarea>
			<?php echo $errMsg['links']?>
			<?php echo $errMsg['brc_website_count']?>
			<p style="font-size: 12px;"><?php echo $spTextSA['Insert links separated with comma']?>.</p>
			<P><b>Eg:</b> http://www.seopanel.in/plugin/, http://www.seopanel.in/download/</P>
		</td>
	</tr>
	<?php if(!empty($isAdmin)){ ?>
		<tr class="white_row">
			<td class="td_left_col"><?php echo $spTextSA['Execute with cron']?>:</td>
			<td class="td_right_col">
				<?php 
				$selected = (isset($post['cron_job']) && $post['cron_job'] == 0) ? 'selected' : '';
				?>
				<select name="cron_job">
					<option value="1"><?php echo $spText['common']['Yes']?></option>
					<option value="0" <?php echo $selected?>><?php echo $spText['common']['No']?></option>
				</select>
			</td>
		</tr>
	<?php } else {?>
		<input type="hidden" value="1" name="cron_job">
	<?php }?>
	<tr class="blue_row">				
		<td class="td_left_col"><?php echo $spTextReport['Reports generation interval']?>: </td>
		<td class="td_right_col">
			<select name="report_interval">
				<?php 
				$scheduleList = array(
        			1 => $spText['label']['Daily'],
        			2 => $spTextReport['2 Days'],
        			7 => $spText['label']['Weekly'],
        			30 => $spText['label']['Monthly'],
        		);
				foreach ($scheduleList as $key => $val) {
					
					// if not admin,check for report interval lowest limit
					if (empty($isAdmin) && !empty($userTypeSpecList)) {
						
						// if it is less, skip it
						if ($key < $userTypeSpecList['brc_report_interval_limit']) {
							continue;	
						}
						
					}
					
					$selected = ($key == $post['report_interval']) ? "selected" : "";
					?>
					<option value="<?php echo $key?>" <?php echo $selected?> ><?php echo $val?></option>
					<?php
				}
				?>
			</select>			
			<?php echo $errMsg['brc_report_interval_limit']?>
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $pluginText['Send reports']?>:</td>
		<td class="td_right_col">
			<select name="send_reports">
				<?php
				$sendReport = array(
					'Not Send' => 'Not Send',
					'CSV' => 'CSV',
					'Pdf' => 'Pdf',
					'Html' => 'Html',
				);
                foreach($sendReport as $sendkey => $sendInfo){                                      
                	$selected = ($sendkey == $post['send_reports'])? "selected":"";
                    ?>
                    <option value="<?php echo $sendkey?>" <?php echo $selected?>><?php echo $sendInfo?></option>
					<?php
				}
				?>
			</select>
		</td>
	</tr>
	
	<tr class="blue_row">
		<td class="td_left_col"><?php echo  $spText['login']['Email']; ?>: </td>
		<td class="td_right_col">
			<input type="text" name="email_address" value="<?php echo  $post['email_address'];?>" style="width:400px"><?php echo $errMsg['email_address']?>
			<p style="font-size: 12px;"><?php echo $pluginText['insertemails']?>.</p>
		</td>
	</tr>
</table>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('&action=showCampaignManager&user_id='.$post['user_id'], 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;         	
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>