<?php echo showSectionHead($pluginText["Captcha Bypass Manager"]); ?>

<script type="text/javascript">
$(document).ready(function() { 
    $("table").tablesorter({ 
		sortList: [[2,0]]
    });
});
</script>

<table  id="cust_tab" class="tablesorter">
	<thead>
	<tr>
		<th><?php echo $spText['common']['Id']?></th>
		<th><?php echo $spText['common']['Name']?></th>
		<th><?php echo $spText['label']['Type']?></th>
		<th><?php echo $spText['login']['Username']?></th>
		<th><?php echo $spText['common']['Status']?></th>
		<th><?php echo $spText['common']['Action']?></th>
	</tr>
	</thead>
	
	<tbody>
	<?php
	if(count($serviceList) > 0) {
		foreach($serviceList as $serviceInfo){
			$serviceLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=editService&id={$serviceInfo['id']}", "{$serviceInfo['name']}");
			?>
			<tr>
				<td width="40px"><?php echo $serviceInfo['id'];?></td>
				<td><?php echo $serviceLink;?></td>
				<td><?php echo $serviceInfo['identifier']?></td>
				<td><?php echo $serviceInfo['username']?></td>
                <td><?php echo $serviceInfo['status'] ? $spText['common']["Active"] : $spText['common']["Inactive"];?></td>
				<td width="100px">
					<select name="action" id="action<?php echo $serviceInfo['id']?>" onchange="doPluginAction('<?php echo PLUGIN_SCRIPT_URL?>', 'content', 'id=<?php echo $serviceInfo['id']?>', 'action<?php echo $serviceInfo['id']?>')">
						<option value="">-- <?php echo $spText['common']['Select']?> --</option>
                        <?php if($serviceInfo['status']) { ?>
                            <option value="Inactivate"><?php echo $spText['common']["Inactivate"]?></option>
                        <?php }else{ ?>
                            <option value="Activate"><?php echo $spText['common']["Activate"]?></option>
                        <?php } ?>
                        <option value="editService"><?php echo $spText['common']['Edit']?></option>
					    <option value="deleteService"><?php echo $spText['common']['Delete']?></option>
					</select>
				</td>
			</tr>
			<?php
		}
	}else{
		?>
		<tr><td colspan="6"><b><?php echo $_SESSION['text']['common']['No Records Found']?></b></tr>
		<?php
	} 
	?>
	</tbody>
</table>
<br>
<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;">
         	<a onclick="<?php echo pluginGETMethod('action=newService', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $pluginText["New Captcha Bypass Service"]?>
         	</a>
    	</td>
	</tr>
</table>