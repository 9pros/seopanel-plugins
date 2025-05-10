<?php if ($htmlMail) {?>
    <table cellspacing="0" cellpadding="0" width="100%">
    	<tbody>
    		<tr style="height: 11px;">
    			<td style="vertical-align: middle; margin: 0pt;" colspan="2">
    			<hr
    				style="margin: 5px 0pt; background-color: rgb(0, 0, 0); color: rgb(0, 0, 0); height: 1px;">
    			</td>
    		</tr>
    		<tr style="height: 20px;">
    			<td style="vertical-align: middle; font-size: 11px; padding: 5px; margin: 0pt;">
    			<div style="word-wrap: break-word;">
    			<p style="font-size: 11px; color: rgb(169, 169, 169);"><a href="<?=$unsubscribeLink?>"><?=$clickHereText?></a> <?=$addtoListText?>.</p>
    			</div>
    			</td>
    		</tr>
    	</tbody>
    </table>
<?php } else {?>


-----------------------------------------------------------
<?=$clickHereText?> <?=$unsubscribeLink?> <?=$addtoListText?>
<?php }?>