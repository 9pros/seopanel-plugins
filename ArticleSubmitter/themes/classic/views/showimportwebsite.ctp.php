<?php echo  showSectionHead($pluginText['Article Directory Importer']); ?>
<form id="actionform">
<table width="100%" id="cust_tab">
	<tr class='form_head'>
		<th ><?php echo $pluginText['Article Directory Importer']?></th>
		<th >&nbsp;</th>
	</tr>	
	<tr>
		<td><?php echo $pluginText['Websites']?>:</td>
		<td>
			<textarea name="websites" rows="20" cols="60"></textarea><?php echo $errMsg['websites']?>
			<P style="color: black;">Eg: http://directory.seofreetools.net/submit.php  username password, http://www.fat64.net/submit.php</P>
		</td>
	</tr>
</table>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('actionform', 'content', 'action=doimport'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>