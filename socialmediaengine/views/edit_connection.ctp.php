<?php 
$headMsg = ($sec == 'update') ? $pluginText['Edit Connection'] :  $pluginText['New Connection'] ;
echo showSectionHead($headMsg);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('statusform', 'content', 'action='.$sec.'Connection');
?>
<form id="statusform" onsubmit="<?php echo $actFun?>;return false;">
<?php if ($sec == 'update') {?>
<input type="hidden" name="id" value="<?php echo $post['id']?>"/>
<?php }?>
<table class="list">
	<tr class="listHead">
		<td class="left" width='30%'><?php echo $headMsg?></td>
		<td class="right">&nbsp;</td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $spText['common']['Name']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="connection_name" value="<?php echo $post['connection_name']?>">
			<br><?php echo $errMsg['connection_name']?>
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $spText['common']['Source']?>:*</td>
		<td class="td_right_col">
			<select name="resource_id" id="resource_id">
				<?php
				$engineName = "";
				foreach($resourceList as $resourceInfo){ ?>
					<?php if($resourceInfo['id'] == $post['resource_id']){
					   $engineName = $resourceInfo['engine_name'];
					   ?>
						<option value="<?php echo $resourceInfo['id']?>" selected><?php echo $resourceInfo['engine_name']?></option>
					<?php } else {?>
						<option value="<?php echo $resourceInfo['id']?>"><?php echo $resourceInfo['engine_name']?></option>
					<?php }?>
				<?php }?>
			</select>
			<?php echo $errMsg['resource_id']?>
		</td>
	</tr>	
	<tr>
		<td><?php echo $pluginText["Submission Pages"]?>:</td>
		<td>
			<div class="field_wrapper">
				<?php
				$i = 0;
				foreach ($post['connection_links'] as $linkId => $link) {
				    $act_class = $i ? "remove_button" : "add_button";
				    $icon_class = $i ? "minus" : "plus";
				    $i++;
				    ?>
                    <div>
                        <input type="text" name="connection_links[<?php echo $linkId?>]" value="<?php echo $link?>" class="large"/>
                        <a href="javascript:void(0);" class="<?php echo $act_class?>" id='add_sub_but'><i class="fa fa-<?php echo $icon_class?>-circle" aria-hidden="true"></i></a>
                    </div>
				<?php }?>
            </div>
			<?php echo $errMsg['connection_links']?>
			<?php if (!empty($post['id']) && ($post['connection_status'] == 'connected') && !in_array($engineName, ['Twitter', 'LinkedIn'])) {?>
				<?php if ($engineName == 'Pinterest') {?>
					<p style="padding-left: 0px; margin-top: 5px;">Eg: https://www.pinterest.com/seopanel/seo-panel</p>
				<?php } elseif ($engineName == 'Facebook') {?>
					<p style="padding-left: 0px; margin-top: 5px;">Eg: https://www.facebook.com/seopanel/</p>
				<?php }?>
    			<div>
    				<a style="text-decoration: none;" href="javascript:void(0);" onclick="<?php echo pluginGETMethod('action=crawlSubmissionPages&id='.$post['id'], 'content')?>">
    					Crawl Submission Pages &raquo;&raquo;
    				</a>
    			</div>
			<?php }?>
		</td>
	</tr>
</table>
<table class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=connectionManager', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;         	
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>

<script type="text/javascript">
$(document).ready(function(){
    var maxField = 25; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div><input type="text" name="connection_links[]" value="" class="large"/> <a href="javascript:void(0);" class="remove_button"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></div>'; //New input field html 
    var x = <?php echo count($post['connection_links'])?>; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });

    setConnectionLinks(0);

    <?php
    if ($post['connection_status'] == 'connected') {
        ?>
        $('#resource_id option:not(:selected)').prop('disabled', true);
        <?php
    }
    ?>

    // onchange function for social media type
    $("#resource_id").change(function () {
    	setConnectionLinks(1);
    });

    function setConnectionLinks(changed) {
    	smName = $("#resource_id option:selected").text()
    	if (smName == "Twitter") {
    		$('input[name^="connection_links"]').prop("value", "https://www.twitter.com/");
    		$('input[name^="connection_links"]').prop("readonly", "readonly");
    		$('#add_sub_but').hide();
        } else if (smName == "LinkedIn") {
    		$('input[name^="connection_links"]').prop("value", "https://www.linkedin.com/");
    		$('input[name^="connection_links"]').prop("readonly", "readonly");
    		$('#add_sub_but').hide();
        } else {
            if (changed) {
        		$('input[name^="connection_links"]').prop("value", "");
        		$('input[name^="connection_links"]').prop("readonly", false);
        		$('#add_sub_but').show();
        	}
        }
    }
});
</script>