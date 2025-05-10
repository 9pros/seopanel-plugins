<div style='text-align:center;'>
	<img  src='<?php echo SP_IMGPATH; ?>/payment_load.gif' border='0px'/>
</div>
<form action="<?php echo $pgInfo['PP_URL']; ?>" name="tform" id="tform" method="post" accept-charset="utf-8">
   <input type="hidden" name="cmd" value="_xclick" />
   <input type="hidden" name="charset" value="utf-8" />
   <input type="hidden" name="business" value="<?php echo $pgInfo['PP_BUSINESS_EMAIL']; ?>" />
   <input type="hidden" name="item_name" value='<?php echo $paymentInfo['item_name']?>'/>
   <input type="hidden" name="item_number" value='<?php echo $paymentInfo['item_number']?>' />
   <input type="hidden" name="quantity" value='<?php echo $paymentInfo['item_quantity']?>' />
   <input type="hidden" name="amount" value='<?php echo $paymentInfo['item_amount']?>' />
   <input type="hidden" name="currency_code" value="<?php echo $paymentInfo['currency_code']?>" />
   <input type="hidden" name="return" value="<?php echo $paymentInfo['return']?>" />
   <input type="hidden" name="cancel_return" value="<?php echo $paymentInfo['cancel_return']?>" />
   <input type="hidden" name="bn" value="Business_BuyNow_WPS_SE" />
</form>
    		
<SCRIPT type="text/javascript">
	setTimeout("document.tform.submit()", 1000);
</SCRIPT>