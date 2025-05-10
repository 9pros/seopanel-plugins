
<form id="submit_form" onsubmit="<?php echo $actFun?>;return false;">
<input type="hidden" name="article_id" value="<?php echo $submitInfo['article_id']?>">
<input type="hidden" name="website_id" value="<?php echo $dirInfo['id']?>">
<table width="100%" id="cust_tab">
	<tr class="form_head">
		<th width="30%"><?php echo $pluginText["Article Submitter"]?></th>
		<th>&nbsp;</th>
	</tr>
    <tr>
		<td><?php echo $spText['common']['Name']?></td>
		<td><?php echo $dirInfo['website_name']?></td>
	</tr>
    <tr>
		<td><?php echo $spText['common']['Url']?></td>
		<td><a target="_blank" href="<?php echo $dirInfo['website_url']?>"><?php echo $dirInfo['website_url']?></a></td>
	</tr>
</table>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=showSubmitDetails', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
			<a onclick="<?php echo pluginConfirmPostMethod('submit_form', 'subcontent', 'action=skipWebsite')?>" href="javascript:void(0);" class="actionbut">
				<?php echo $spText['button']['Skip']?>
         	</a>&nbsp;
         	<a onclick="<?php echo pluginConfirmPostMethod('submit_form', 'subcontent', 'action=submitArticleSite')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Submit']?>
         	</a>
    	</td>
	</tr>
</table>
</form>