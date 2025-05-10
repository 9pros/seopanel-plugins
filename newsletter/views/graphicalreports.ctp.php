<?php
if (empty($dataList)) {
    showErrorMsg($spText['common']['No Records Found']."!");
} else { 
    $imgSrc = SP_WEBPATH."/tmp/$imgFile?".rand(1, 1000);
    ?>
    <img src="<?=$imgSrc?>">
<?php }?>