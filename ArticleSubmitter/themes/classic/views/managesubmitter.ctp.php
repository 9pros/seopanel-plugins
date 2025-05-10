<?php  echo showSectionHead($pluginText["Article Submitter"]); 
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginPOSTMethod('articleform', 'subcontent', 'action=submitArticle');
?>
<form id="articleform" onsubmit="<?php echo $actFun?>;return false;">
<table class="search" width="50%">
	<tr>
		<th><?php echo $pluginText["Article"]?>: </th>
		<td>
            <select name="article_id">
                <option value="">--<?php echo $spText['common']['Select']?>--</option>
                <?php foreach ($list as $listInfo) {?>
					<option  value ="<?php echo $listInfo['id']?>"><?php echo $listInfo['title']?></option>
                <?php }?>
            </select>
            &nbsp;
         	<a onclick="<?php echo $actFun?>" href="javascript:void(0);" class="actionbut" id="proceed_button">
         		<?php echo $spText['button']['Proceed']?>
         	</a>
    	</td>
	</tr>
</table>
</form>
<div id="subcontent">
	<p class="note left">Select an <b>article</b> to <b>proceed</b> with article submission.</p>
</div>