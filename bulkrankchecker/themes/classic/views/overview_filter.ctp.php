<form id='search_form_<?php echo $actionVal?>'>
<table class="search">
	<tr>				
		<th><?php echo $pluginText['Campaign']?>: </th>
		<td>
			<select name="campaign_id" id="campaign_id">
				<?php foreach($campaignList as $campaignInfo) {?>
					<?php if($campaignInfo['id'] == $campaignId) {?>
						<option value="<?php echo $campaignInfo['id']?>" selected><?php echo $campaignInfo['name']?></option>
					<?php } else {?>
						<option value="<?php echo $campaignInfo['id']?>"><?php echo $campaignInfo['name']?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<th> <?php echo $spText['common']['Period']?>: </th>
		<td>
			<input type="text" value="<?php echo $fromTime?>" name="from_time"/>
			<input type="text" value="<?php echo $toTime?>" name="to_time"/>
			<script type="text/javascript">
			$(function() {
				$( "input[name='from_time'], input[name='to_time']").datepicker({dateFormat: "yy-mm-dd"});
			});
		  	</script>
		</td>
		<td>
			<a href="javascript:void(0);" onclick="<?php echo $submitAction?>" class="actionbut"><?php echo $spText['button']['Show Records']?></a>
		</td>
	</tr>        
</table>      
</form>
<br>
<div id='content_<?php echo $actionVal?>'>
	<script><?php echo $submitAction?></script>
</div>