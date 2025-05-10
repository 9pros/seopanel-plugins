<?php 
echo showSectionHead($pluginText["Newsletter Reports"]);
?>
<form name="listform" id="listform">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="search">
	<tr>
		<th><?=$pluginText['Campaign']?>: </th>
		<td>
			<select name="campaign_id" id="campaign_id" onchange="doLoad('campaign_id', '<?=PLUGIN_SCRIPT_URL?>&action=shownlsel', 'nl_show_id')">
				<?php foreach($campaignList as $campaignInfo){?>
					<?php if($campaignInfo['id'] == $campaignId){?>
						<option value="<?=$campaignInfo['id']?>" selected><?=$campaignInfo['campaign_name']?></option>
					<?php }else{?>
						<option value="<?=$campaignInfo['id']?>"><?=$campaignInfo['campaign_name']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<th width="10%"><?=$pluginText['Newsletter']?>: </th>
		<td id='nl_show_id'><?php echo $this->getPluginViewContent('newsletterselectbox'); ?></td>
		<th><?=$spText['label']['Report Type']?>: </th>
		<td>
			<select name="report_type" onchange="<?=$submitLink?>">
				<?php foreach($reportTypeList as $type => $label){?>
					<?php if($reportType == $type){?>
						<option value="<?=$type?>" selected><?=$label?></option>
					<?php }else{?>
						<option value="<?=$type?>"><?=$label?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
	</tr>
	<tr>
		<th><?=$spText['common']['Period']?>:</th>
		<td>
			<input type="text" style="width: 72px;margin-right:0px;" value="<?=$fromTime?>" name="from_time"/> 
			<img align="bottom" onclick="displayDatePicker('from_time', false, 'ymd', '-');" src="<?=SP_IMGPATH?>/cal.gif"/> 
			<input type="text" style="width: 72px;margin-right:0px;" value="<?=$toTime?>" name="to_time"/> 
			<img align="bottom" onclick="displayDatePicker('to_time', false, 'ymd', '-');" src="images/cal.gif"/>
		</td>
		<th><?=$spText['common']['Status']?>: </th>
		<td>
			<select name="sent_status" onchange="<?=$submitLink?>">
				<option value="">-- <?=$spText['common']['Select']?> --</option>
				<?php foreach($statusList as $type => $label){?>
					<?php if($sentStatus == $type){?>
						<option value="<?=$type?>" selected><?=$label?></option>
					<?php }else{?>
						<option value="<?=$type?>"><?=$label?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<td style="text-align: center;" colspan="2">
			<a href="javascript:void(0);" onclick="<?=$submitLink?>" class="actionbut">
				<?=$spText['button']['Show Records']?>
			</a>
		</td>
	</tr>
</table>
</form>

<div id="suncontent">
    <br>
    <?php 
    switch ($reportType) {		    
        case "report_summary":
            echo $this->getPluginViewContent('reportsummary');
            break;
        
        case "report_daily":
            echo $this->getPluginViewContent('dailyreports');
            break;
        
        case "report_graph":
            echo $this->getPluginViewContent('graphicalreports');
            break;
        
        case "report_email":
            echo $this->getPluginViewContent('emailreports');
            break;
    }
    ?>
</div>