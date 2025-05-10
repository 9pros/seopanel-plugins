<?php
$headMsg = ($sec == 'update') ? $pluginText["Edit Article"] :  $pluginText["New Article"];
echo showSectionHead($headMsg);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('Articleform', 'content', 'action='.$sec.'Article');
?>
<form id="Articleform" onsubmit="<?php echo $actFun?>;return false;">
<?php if ($sec == 'update') {?>
	<input type="hidden" name="id" value="<?php echo $post['id']?>"/>
<?php }?>

<table  id="cust_tab">
	<tr class="form_head">
		<th><?php echo $headMsg?></th>
		<th>&nbsp;</th>
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
		<td class="td_left_col"><?php echo $spText['label']['Title']?>:*</td>
		<td class="td_right_col">
			<input type="text" id='title' name="title" value="<?php echo $post['title']?>" class="large">
			<?php echo $errMsg['title']?>
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $pluginText["Short Description"]?>:*</td>
		<td class="td_right_col">
			<textarea name="short_desc" id="shortdescription"><?php echo $post['short_desc']?></textarea><?php echo $errMsg['short_desc']?>
		</td>
	</tr>
	<tr class="blue_row">
		<td class="td_left_col"><?php echo $pluginText["Article"]?>:*</td>
		<td class="td_right_col">
			<textarea name="article" id="webkeywords" style="height: 100px;"><?php echo $post['article']?></textarea><?php echo $errMsg['article']?>
			<p><?php echo $pluginText['Separate tags with commas']?></p>
		</td>
	</tr>
	<tr class="white_row">
		<td class="td_left_col"><?php echo $spText['common']['Category']?>:*</td>
		<td class="td_right_col">
                    <input type="text" id='category' name="category" value="<?php echo $post['category']?>" class="large">
			<?php echo $errMsg['category']?>
		</td>
	</tr>	
</table>

<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=article', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;

         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>

<script>
	$(document).ready(function(){
		var simplemde = new SimpleMDE({ element: document.getElementById("webkeywords"), forceSync: true });
	});
</script>