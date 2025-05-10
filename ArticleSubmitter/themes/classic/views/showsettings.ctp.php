<?php echo  showSectionHead($pluginText['Plugin Settings']); ?>
<?php if(!empty($saved)) showSuccessMsg($pluginText['settingssaved'], false); ?>
<form id="updateSettings">
<input type="hidden" value="update" name="sec">
<table width="100%" id="cust_tab">
	<tr class="form_head">
		<th><?php echo $pluginText['Plugin Settings']?></th>
		<th>&nbsp;</th>
	</tr>
	<?php 
	foreach( $list as $i => $listInfo) {
		
		switch($listInfo['set_type']) {
			case "bool":
				if (empty($listInfo['set_val'])) {
					$selectYes = "";					
					$selectNo = "selected";
				} else {
					$selectYes = "selected";					
					$selectNo = "";
				}
				break;
		}	
		?>
		<tr>
			<td>
			    <?php echo  $pluginText[$listInfo['set_name']]; ?>:
		    </td>
			<td>
				<?php if($listInfo['set_type'] != 'text'){?>
					<?php if($listInfo['set_type'] == 'bool'){?>
						<select  name="<?php echo $listInfo['set_name']?>">
							<option value="1" <?php echo $selectYes?>><?php echo $spText['common']['Yes']?></option>
							<option value="0" <?php echo $selectNo?>><?php echo $spText['common']['No']?></option>
						</select>
					<?php }else{?>
						<input type="text" name="<?php echo $listInfo['set_name']?>" value="<?php echo stripslashes($listInfo['set_val'])?>" style='width:<?php echo $width?>px'>					
					<?php }?>
				<?php }else{?>
					<textarea name="<?php echo $listInfo['set_name']?>" style='width:<?php echo $width?>px'><?php echo stripslashes($listInfo['set_val'])?></textarea>
				<?php }?>
			</td>
		</tr>
		<?php
	}
	?>
</table>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=settings', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;
         	<?php $actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('updateSettings', 'content', 'action=updateSettings');?>
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>