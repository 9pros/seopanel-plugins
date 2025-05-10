<?php 
echo showSectionHead($pluginText['Post Status Results']);
Session::showSessionMessges();
?>
<br></br>
<table class="actionSec">
	<tr>
    	<td>
    		<a onclick="<?php echo pluginGETMethod("action=manageStatus&keyword=$keyword", 'content')?>" href="javascript:void(0);" class="actionbut">
         		<< Back
         	</a>
    	</td>
	</tr>
</table>

