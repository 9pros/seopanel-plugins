<?php
$headMsg = $spTextSA['Import Project Links'];
echo showSectionHead($headMsg);
?>
<div id='import_link_div'>
<form id="projectform" name="projectform" method="post">
<input type="hidden" name="pid" value="<?php echo PLUGIN_ID?>">
<table class="list">
	<tr class="listHead">
		<td width='30%'><?php echo $headMsg?></td>
		<td>&nbsp;</td>
	</tr>		
	<tr>
		<td><?php echo $spText['label']['Project']?>:*</td>
		<td>
			<select name="project_id" id="project_id">
				<?php foreach($projectList as $projectInfo){?>
					<?php if($projectInfo['id'] == $post['project_id']){?>
						<option value="<?php echo $projectInfo['id']?>" selected><?php echo $projectInfo['project_name']?></option>
					<?php }else{?>
						<option value="<?php echo $projectInfo['id']?>"><?php echo $projectInfo['project_name']?></option>
					<?php }?>
				<?php }?>
			</select>
			<?php echo $errMsg['project_id']?>
		</td>
	</tr>	
	<tr>
		<td><?php echo $spText['common']['Url']?>:*</td>
		<td>
			<textarea name="links" class="form-control" rows="10"><?php echo $post['links']?></textarea>
			<br><?php echo $errMsg['links']?>
			<p style="font-size: 12px;"><?php echo $spTextSA['Insert links separated with comma']?>.</p>
			<P><b>Eg:</b> http://www.seopanel.in/plugin/l/, http://www.seopanel.in/plugin/d/</P>
		</td>
	</tr>
</table>
<table class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=importLinks', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'content', 'action=doImportProjectLinks'); ?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>
</div>