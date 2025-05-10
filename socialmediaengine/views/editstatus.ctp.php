<?php 
$headMsg = ($sec == 'update') ? $pluginText['Edit Status'] :  $pluginText['New Status'] ;
echo showSectionHead($headMsg);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('statusform', 'content', 'action='.$sec.'Status');

if (isset($post['schedule_time'])) {
	$schedule_time = strtotime($post['schedule_time']);
	$schedule_date = date("Y-m-d", $schedule_time);
	$schedule_hour = date("H", $schedule_time);;
} else {
	$schedule_date = "";
	$schedule_hour = 0;
}

$post['hour'] = isset($post['hour']) ? $post['time'] : date("H:i");
$time = explode(":", $post['time']);
$currentTime = date('Y-m-d H:i:s');
$post['start_date'] = isset($post['start_date']) ? $post['start_date'] : date("Y-m-d"); 
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
	<tr class="white_row">
		<td class="td_left_col"><?php echo $spText['label']['Project']?>:*</td>
		<td class="td_right_col">
			<select name="project_id" id="projec_id" >
				<?php foreach($projectList as $projectInfo){ ?>
					<?php if($projectInfo['id'] == $post['project_id']){  ?>
						<option value="<?php echo $projectInfo['id']?>" selected><?php echo $projectInfo['project']?></option>
					<?php } elseif ($projectInfo['status'] == "1") {?>
						<option value="<?php echo $projectInfo['id']?>"><?php echo $projectInfo['project']?></option>
					<?php }?>
				<?php }?>
			</select>
			<?php echo $errMsg['project_id']?>
		</td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $spText['common']['Url']?>:*</td>
		<td class="td_right_col">
			<input type="text" id='weburl' name="share_url" value="<?php echo $post['share_url']?>" class="large">
			<a style="text-decoration: none;" href="javascript:void(0);" onclick="crawlMetaData('websites.php?sec=crawlmeta', 'crawlstats')">&#171&#171 <?php echo $spText['common']['Crawl Meta Data']?></a>
			<div id="crawlstats" style="float: right;padding-right:40px;"></div>
			<br><?php echo $errMsg['share_url']?>
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $spText['label']['Title']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="share_title" id="webtitle" value="<?php echo $post['share_title']?>" class="form-control" onkeyup="countChar('webtitle', 'charNum_title')">
			<?php echo $errMsg['share_title']?>
			<p class="char_count">Character count: <span id="charNum_title">0</span></p>
		</td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $spText['label']['Description']?>:*</td>
		<td class="td_right_col">
			<textarea name="share_description" id="webdescription" class="form-control" rows="2" onkeyup="countChar('webdescription', 'charNum_des')"><?php echo $post['share_description']?></textarea>
			<?php echo $errMsg['share_description']?>
			<p class="char_count">Character count: <span id="charNum_des">0</span></p>
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $pluginText['Tags']?>:</td>
		<td class="td_right_col">
			<p><?php echo $pluginText['Separate tags with comma']?></p>
			<textarea name="share_tags" id="webkeywords" class="form-control" rows="2" onkeyup="countChar('webkeywords', 'charNum_tags')"><?php echo $post['share_tags']?></textarea><?php echo $errMsg['share_tags']?>
			<p class="char_count">Character count: <span id="charNum_tags">0</span></p>
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col">Image Url:</td>
		<td class="td_right_col">
		
			<div class='img_preview'>
                <img src="<?php echo !empty($post['share_image']) ? SP_WEBPATH ."/tmp/".$post['share_image'] : ""?>" id="img" width="200" height="200">
            </div>
            <div class="img_upload">
                <input type="file" id="file" name="file"/>
                <input type="button" class="button" value="Upload" id="but_upload">
                <input type="hidden" name="share_image" value="<?php echo $post['share_image']?>">
                <p>Note: Supported extensions are .jpg, .jpeg, .png</p>
            </div>
            
            <div id="upload_error_msg" style="display: none;">
            	<?php echo formatErrorMsg("Image upload failed. Please check your image has valid extension or image upload directory has write permission.")?> 
			</div>
			
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $pluginText['Schedule Time']?>:*</td>
		<td class="td_right_col">
			<input type="text" name="from_time" value="<?php echo $schedule_date?>">
			<script>
			$(function() {
				$( "input[name='from_time']").datepicker({dateFormat: "yy-mm-dd"});
			});
		  	</script>
			&nbsp;&nbsp;
			<?php echo $pluginText['Hour']?>: 
			<select name="hour">
                <?php 
                for ($i = 0; $i <= 23; $i++) {
                    $label = ($i < 10) ? "0$i" : $i;
                    $selectedVal = ($i == $schedule_hour) ? "selected" : "";
                    ?>
                	<option value="<?php echo $i; ?>" <?php echo $selectedVal?>> <?php echo $label; ?></option>
                	<?php
				}
				?>
            </select>
            <?php if (!empty($errMsg['schedule_time'])) { ?>
            	<br><?php echo $errMsg['schedule_time']?>
            <?php }?>
            <p> <?php echo $pluginText['Current server time']?>:  <b><?php echo $currentTime; ?></b>. 
            <br>
            <?php echo $pluginText['Your post will be performed with respect to this time']?></p>
		</td>
	</tr>
	
	<tr><td colspan="2"><h4>Social Media Connections</h4></td></tr>
	
	<?php foreach ($resourceList as $resourceInfo) {?>
		<tr>
			<td colspan="2">
        		<div>
					<b>
    					<i class="fab fa-<?php echo strtolower($resourceInfo['engine_name'])?>"></i>
    					<?php echo $resourceInfo['engine_name']?>
					</b>
        			&nbsp;&nbsp;
        			<a href="javascript:void(0);" onclick="showStatusPreview('<?php echo $resourceInfo['engine_name'];?>')">Show Preview &raquo;&raquo;</a>
    			</div>
    			<div id="preview_<?php echo $resourceInfo['engine_name'];?>" style="display: none;" class="media_preview">
                    <div class='prev_img' style="display:none;"><img src="" width="376" height="376"></div>
                    <div class='prev_msg'></div>
    			</div>
			</td>
		</tr>
		<?php
		if (!empty($sourceConnList[$resourceInfo['id']])) {
		    foreach ($sourceConnList[$resourceInfo['id']] as $connectionInfo) {
		        ?>
		        <tr>
		        	<td><?php echo $connectionInfo['connection_name']?></td>
		        	<td>
		        		<?php if (!empty($connectionInfo['links'])) {
		        		    $connId = $connectionInfo['id'];
		        		    ?>
    		        		<select name="conn_link_<?php echo $connId?>[]" id="conn_link_<?php echo $connId?>" multiple="multiple" class="form-control">
    		        			<?php foreach ($connectionInfo['links'] as $linkInfo) {
    		        				$selectedVal = in_array($linkInfo['id'], $mappedLinkIdList) ? "selected" : "";
    		        				?>
    		        				<option value="<?php echo $linkInfo['id']?>" <?php echo $selectedVal?>><?php echo $linkInfo['url']?></option>
    		        			<?php }?>
    		        		</select>
                			<input type="checkbox" id="select_all_<?php echo $connId?>" 
                				onclick="selectAllSMEOptions('conn_link_<?php echo $connId?>', true); $('#clear_all_<?php echo $connId?>').prop('checked', false);">
                				<?php echo $spText['label']['Select All']?>
                			&nbsp;&nbsp;
                			<input type="checkbox" id="clear_all_<?php echo $connId?>" 
                				onclick="selectAllSMEOptions('conn_link_<?php echo $connId?>', false); $('#select_all_<?php echo $connId?>').prop('checked', false);">
                				<?php echo $spText['label']['Clear All']?>
		        			<?php 
		        		} else {
		        			?>
		        			<font style="color: gray">
		        				No links found. 
		        				<a href="javascript:void(0);" onclick="<?php echo pluginGETMethod('action=editConnection&id='.$connectionInfo['id'], 'content')?>">Click here</a> to create new link.
		        			</font>
		        			<?php
		        		}
		        		?>
		        	</td>
		        </tr>
		        <?php
    		}
		} else {
			?>
			<tr><td colspan="2">
				<font style="color: gray">
					No connections found.
					<a href="javascript:void(0);" onclick="<?php echo pluginGETMethod('action=newConnection&resource_id='.$resourceInfo['id'], 'content')?>">Click here</a> to create new connection.
				</font>
			</td></tr>
			<?php
		}?>
	<?php }?>
	
</table>
<table class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=manageStatus', 'content')?>" href="javascript:void(0);" class="actionbut">
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
	
    $("#but_upload").click(function() {
        var fd = new FormData();
        var files = $('#file')[0].files[0];
        fd.append('file',files);
        $("#upload_error_msg").hide();

        $.ajax({
            url: '<?php echo pluginLink() . "&action=imageUpload"?>',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                if(response != 0){
                    $("#img").attr("src", '<?php echo SP_WEBPATH . "/tmp/"?>' + response); 
                    $(".img_preview img").show();
                    $('input[name^="share_image"]').prop("value", response);
                }else{
                	$("#upload_error_msg").show();
                }
            },
        });
    });

    // find character count
    countChar('webtitle', 'charNum_title');
    countChar('webdescription', 'charNum_des');
    countChar('webkeywords', 'charNum_tags');

});
</script>
