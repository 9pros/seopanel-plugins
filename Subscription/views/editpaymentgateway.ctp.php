<?php
echo showSectionHead($pluginText['Edit Payment Gateway']);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('projectform', 'content', 'action=updatePaymentGateway');
?>
<form id="projectform">
	<input type="hidden" name="id" value="<?php echo $post['id']?>"/>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
		<tr class="listHead">
			<td class="left" width='30%'><?php echo $pluginText['Edit Payment Gateway']?></td>
			<td class="right">&nbsp;</td>
		</tr>
		<tr class="white_row">
			<td class="td_left_col"><?php echo $spText['common']['Name']?>:*</td>
			<td class="td_right_col">
				<input type="text" name="name" value="<?php echo $post['name']?>"><?php echo $errMsg['name']?>
			</td>
		</tr>	
		
		<?php
		// loop through plugin option 
		foreach($post['option_list'] as $i => $listInfo) {

			// if display false hide from form
			if ($listInfo['display'] == 0) continue;
			
			$class = ($i % 2) ? "white_row" : "blue_row";
			
			// switch through the typr
			switch($listInfo['set_type']){
				
				case "small":
					$width = 40;
					break;
	
				case "bool":
					
					if (empty($listInfo['set_val'])) {
						$selectYes = "";					
						$selectNo = "selected";
					} else {
						$selectYes = "selected";					
						$selectNo = "";
					}
					
					break;
					
				case "medium":
					$width = 200;
					break;
	
				case "large":
				case "text":
					$width = 500;
					break;
			}
			
			$optionLabel = empty($pluginText[$listInfo['set_name']]) ? $listInfo['set_label'] : $pluginText[$listInfo['set_name']];
			?>
			<tr class="<?php echo $class?>">
				<td class="td_left_col"><?php echo $optionLabel?>:</td>
				<td class="td_right_col">
					
					<?php 
					switch($listInfo['set_type']) {

						case "text":
							?>
							<textarea name="<?php echo $listInfo['set_name']?>" style='width:<?php echo $width?>px'><?php echo stripslashes($listInfo['set_val'])?></textarea>
							<?php
							break;
							
						case "bool":
							?>
							<select  name="<?php echo $listInfo['set_name']?>">
								<option value="1" <?php echo $selectYes?>><?php echo $spText['common']['Yes']?></option>
								<option value="0" <?php echo $selectNo?>><?php echo $spText['common']['No']?></option>
							</select>
							<?php
							break;
							
						default:
							?>
							<input type="<?php echo $type?>" name="<?php echo $listInfo['set_name']?>" value="<?php echo stripslashes($listInfo['set_val'])?>" style='width:<?php echo $width?>px'>						
							<?php
							break;

					}
					?>
					<?php echo $errMsg[$listInfo['set_name']]; ?>
				</td>
			</tr>
			<?php 
		}
		?>	
		<tr class="blue_row">
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
	    		<a onclick="<?php echo pluginGETMethod('&action=paymentGatewayManager', 'content')?>" href="javascript:void(0);" class="actionbut">
	         		<?php echo $spText['button']['Cancel']?>
	         	</a>&nbsp;         	
	         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
	         		<?php echo $spText['button']['Proceed']?>
	         	</a>
	    	</td>
		</tr>
	</table>
</form>