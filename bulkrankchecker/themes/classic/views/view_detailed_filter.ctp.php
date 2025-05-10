<?php  
echo showSectionHead($pluginText['Campaign Report']);
$changeCampAction = "doLoad('campaign_id', 'seo-plugins.php?pid=".PLUGIN_ID."', 'keyword_area', 'action=showKeywordSelectBox')";
?>
<form id='search_form'>
<table width="100%" class="search">
	<tr>				
		<th><?php echo $pluginText['Campaign']?>: </th>
		<td>
			<select name="campaign_id" id="campaign_id" onchange="<?php echo $changeCampAction?>">
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
			<input type="text" value="<?php echo $info['from_time']?>" name="from_time"/>
			<input type="text" value="<?php echo $info['to_time']?>" name="to_time"/>
			<script type="text/javascript">
			$(function() {
				$( "input[name='from_time'], input[name='to_time']").datepicker({dateFormat: "yy-mm-dd"});
			});
		  	</script>
		</td>
	</tr>
	<tr>						
		<th><?php echo $spText['common']['Keyword']?>: </th>
		<td id="keyword_area">
			<?php echo  $this->pluginRender('keyword_select_box', 'ajax', false); ?>
		</td>		
		<th><?php echo $spText['common']['Search Engine']?>: </th>
		<td>
			<?php echo  $this->render('searchengine/seselectbox', 'ajax'); ?>
		</td>
	</tr>
	<tr>
		<th><?php echo $spText['common']['Url']?></th>
		<td>
			<select name="link_id" id="link_id" onchange="<?php echo $submitAction?>">
				<option value="">-- <?php echo $spText['common']['Select']?> --</option>
				<?php foreach($cmpLinkList as $linkId => $link) {?>
					<?php if($linkId == $cmpLinkId) {?>
						<option value="<?php echo $linkId?>" selected><?php echo $link?></option>
					<?php } else {?>
						<option value="<?php echo $linkId?>"><?php echo $link?></option>
					<?php }?>
				<?php }?>
			</select>
		</td>
		<th>
			<a href="javascript:void(0);" onclick="<?php echo $submitAction?>" class="actionbut"><?php echo $spText['button']['Show Records']?></a>
		</th>
		<td>&nbsp;</td>
	</tr>
        
</table>      
</form>
<br>
<div id='subcontent'>
	<script><?php echo $submitAction?></script>
</div>