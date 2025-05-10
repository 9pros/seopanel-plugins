<?php 
$headMsg = ($sec == 'update') ? $pluginText['Edit Newsletter'] : $pluginText['New Newsletter'];
echo showSectionHead($headMsg);
$post['start_date'] = str_replace(' 00:00:00', '', $post['start_date']);
$post['end_date'] = str_replace(' 23:59:59', '', $post['end_date']);
?>
<form id="projectform">
<?php if ($sec == 'update') {?>
	<input type="hidden" name="id" value="<?=$post['id']?>"/>
<?php }?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?=$headMsg?></td>
		<td class="right">&nbsp;</td>
	</tr>		
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Campaign']?>:*</td>
		<td class="td_right_col">
			<select name="campaign_id" id="campaign_id">
				<?php foreach($campaignList as $campaignInfo){?>
					<?php if($campaignInfo['id'] == $post['campaign_id']){?>
						<option value="<?=$campaignInfo['id']?>" selected><?=$campaignInfo['campaign_name']?></option>
					<?php }else{?>
						<option value="<?=$campaignInfo['id']?>"><?=$campaignInfo['campaign_name']?></option>
					<?php }?>
				<?php }?>
			</select>
			<?=$errMsg['campaign_id']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$spText['common']['Name']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="name" value="<?=$post['name']?>"><?=$errMsg['name']?>
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Subject']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="subject" value="<?=$post['subject']?>"><?=$errMsg['subject']?>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Enable HTML Mail']?>:</td>
		<td class="td_right_col">
			<?php 
			$checkHtmlMail = (!isset($post['html_mail']) || !empty($post['html_mail'])) ? "checked" : "";
			$toggleFunc = pluginPOSTMethod('projectform', 'content', 'action=toggleEditor&sec='.$sec); 
			?>			
			<input type="checkbox" name="html_mail" id="html_mail" value="1" onclick="tinyMCE.triggerSave(true,true);<?=$toggleFunc?>" <?=$checkHtmlMail?> >
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Content']?>:*</td>
		<td class="td_right_col">
			<div id="editor_div">
    			<textarea name="mail_content" class="mceSimple" style="width:98%" rows="15"><?=stripslashes($post['mail_content'])?></textarea>
    			<?php if ($checkHtmlMail == 'checked') {?>
        			<script>
            			tinyMCE.init({
            				mode : "textareas",
            				theme : "advanced",
            				theme_advanced_buttons1 : "mylistbox,style,mysplitbutton,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,|,bullist,numlist,undo,redo,link,unlink",
            			    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,outdent,indent,blockquote,|,anchor,image,cleanup,code,|,forecolor,backcolor",
            			    theme_advanced_buttons3 : "",
            			    theme_advanced_toolbar_location : "top",
            			    theme_advanced_toolbar_align : "left",
            			    theme_advanced_statusbar_location : "bottom",
            				editor_selector : "mceSimple",
            				relative_urls : false,
            				remove_script_host : false,            				            				
            				height : "420"
            			});
            		</script>
        		<?php }?>
    		</div>
			<?=$errMsg['mail_content']?>
			<p></p>
			<p>[name] => Will be replaced with subscriber name</p>
			<p>[email] => Will be replaced with subscriber email address</p>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Email List']?>:*</td>
		<td class="td_right_col">
			<?php $post['email_list'] = is_array($post['email_list']) ? $post['email_list'] : array(); ?>
			<select name="email_list[]" class="multi" multiple="multiple">				
				<?php foreach($userEmailList as $elInfo){?>
					<?php $selected = in_array($elInfo['id'], $post['email_list']) ? "selected" : ""?>
					<option value="<?=$elInfo['id']?>" <?=$selected?>><?=$elInfo['name']?></option>
				<?php }?>
			</select>
			<?=$errMsg['email_list']?>
			<br></br>
			<a onclick="<?=pluginGETMethod('action=newEL', 'content')?>" href="javascript:void(0);">
         		<?=$pluginText['Create Email List']?>
         	</a>
		</td>
	</tr>	
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Start Date']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="start_date" value="<?=$post['start_date']?>">
			<img align="bottom" onclick="displayDatePicker('start_date', false, 'ymd', '-');" src="<?=SP_IMGPATH?>/cal.gif"/> 
			<?=$errMsg['start_date']?>
			<p>The date from which newsletter send to the subscribers</p>
		</td>
	</tr>	
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['End Date']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="end_date" value="<?=$post['end_date']?>">
			<img align="bottom" onclick="displayDatePicker('end_date', false, 'ymd', '-');" src="<?=SP_IMGPATH?>/cal.gif"/> 
			<?=$errMsg['end_date']?>
			<p>After this date newsletter will not be send to subscribers</p>
		</td>
	</tr>		
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Enable Clicks Tracking']?>:</td>
		<td class="td_right_col">
			<?php $checkTrackClicks = !empty($post['track_clicks']) ? "checked" : ""; ?>			
			<input type="checkbox" name="track_clicks" value="1" <?=$checkTrackClicks?> > <?=$errMsg['track_clicks']?>
			<p>Note: If you installed seo panel in your local machine, it will not work.</p>
		</td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Enable Email Open Tracking']?>:</td>
		<td class="td_right_col">
			<?php $checkOpen = (!isset($post['open_tracking']) || !empty($post['open_tracking'])) ? "checked" : ""; ?>			
			<input type="checkbox" name="open_tracking" value="1" <?=$checkOpen?> >
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?=$pluginText['Enable Unsibscribe Option']?>:</td>
		<td class="td_right_col">
			<?php $checkUnsubscribe = (!isset($post['unsubscribe_option']) || !empty($post['unsubscribe_option'])) ? "checked" : ""; ?>			
			<input type="checkbox" name="unsubscribe_option" value="1" <?=$checkUnsubscribe?> >
		</td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?=$pluginText['Add to Cron Job']?>:</td>
		<td class="td_right_col">
			<?php $checkCron = !empty($post['cron']) ? "checked" : ""; ?>			
			<input type="checkbox" name="cron" value="1" <?=$checkCron?> >
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
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?=pluginGETMethod('action=newslettermanager&campaign_id='.$post['campaign_id'], 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'content', 'action='.$sec.'NL'); ?>
         	<a onclick="tinyMCE.triggerSave(true,true);<?=$actFun?>" href="javascript:void(0);" class="actionbut">
         		<?=$spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>