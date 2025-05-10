<?php
echo showSectionHead($pluginText["Article Spinner"]);
$actFun = SP_DEMO ? "alertDemoMsg()" : pluginPOSTMethod('articlechecker', 'content', 'action=articleChecker', true);
$actsave = SP_DEMO ? "alertDemoMsg()" : pluginConfirmPOSTMethod('articlechecker', 'content', 'action=saveArticle');
?>
<form id="articlechecker" onsubmit="<?php echo $actFun?>;return false;">
	<table class="search" id="cust_tab">
	<tr class="form_head">
		<th><?php echo $pluginText['Manage Spinner']?></th>
		<th>&nbsp;</th>
	</tr>
	<tr>
		<td><?php echo $spText['common']['Keyword']?>:</td>
		<td>
            <input type="text" name="keyword" value="<?php echo  $searchInfo['keyword']; ?>" class="large">
            <a href="javascript:void(0);"  onclick="<?php echo $actFun?>"  class="actionbut"><?php echo $pluginText["Search"]?></a>
            <?php echo $errMsg['keyword']?>
		</td>
	</tr>
    <tr>	
		<td><?php echo $spText['common']['Search Engine']?>: </td>
		<td>			
			 <select name="searchengine">
                <?php foreach ($list as $listInfo){
                    if($listInfo['id'] == $searchInfo['searchengine']){?>
                         <option selected="selected" value=<?php echo $listInfo['id']?>><?php echo $listInfo['domain']?></option>
                 		<?php
                    }else{
                    	?>
                		<option value=<?php echo $listInfo['id']?>><?php echo $listInfo['domain']?></option>
                     <?php } 
                }?>
             </select>
             <?php echo $errMsg['searchengine']?>					
		</td>
	</tr>
    <tr>
		<td><?php echo $pluginText["Article"]?>: </td>
        <td>			
            <textarea id="article_draft" name="article"><?php echo  $searchInfo['article']; ?></textarea>
            <?php echo $errMsg['article']?>						
		</td>       
	</tr>
</table>

<table width="100%" class="actionSec">
	<tr>
    	<td style="padding-top: 6px;text-align:right;">
    		<a onclick="<?php echo pluginGETMethod('action=spinner', 'content')?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $spText['button']['Cancel']?>
         	</a>&nbsp;

         	<a onclick="<?php echo $actsave?>" href="javascript:void(0);" class="actionbut">
         		<?php echo $pluginText["Save Article"]?>
         	</a>
    	</td>
	</tr>
</table>
</form>

<script type="text/javascript">
	$(document).ready(function(){
		spinnerTextArea = new SimpleMDE({ element: document.getElementById("article_draft"), forceSync: true });
	});
</script>