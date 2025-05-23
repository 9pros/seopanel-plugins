<div class="col-sm-12">

	<ul class="nav nav-tabs" role="tablist">
    	<?php
    	$i = 0;
    	foreach ($seList as $seInfo) {
    	    $linkClass = "";
    	    if ($i == 0) {
    	        $linkClass = "active";
    	        $defaultLinkOVUrl = $linkOVUrl . "&se_id=" . $seInfo['id'];
    	    }
            ?>
            <li role="presentation" class="nav-item">
            	<a class="nav-link <?php echo $linkClass?>" href="#" data-href="<?php echo $linkOVUrl?>&se_id=<?php echo $seInfo['id']?>" 
            	aria-controls="linksection-<?php echo $i;?>" role="tab" data-target="#linksection-<?php echo $i;?>" 
            	data-toggle="tab"><?php echo $seInfo['domain']?></a>
            </li>
            <?php
            $i++;
    	}
    	?>
	</ul>

    <!-- Tab panes -->
  	<div class="tab-content">
    	<?php
    	$i = 0;
    	foreach ($seList as $seInfo) {
    	    $linkClass = $i ? "" : "active"; 
            ?>
        	<div role="tabpanel" class="tab-pane <?php echo $linkClass?>" id="linksection-<?php echo $i;?>"></div>
            <?php
            $i++;
    	}
    	?>
	</div>
  
</div>

<script type="text/javascript">
$(document).ready(function() {
	scriptDoLoad('<?php echo $defaultLinkOVUrl?>', 'linksection-0', '');
});

$('[data-toggle="tab"]').on('click', function(){
    var $this = $(this),
    source = $this.attr('data-href'),
    pane = $this.attr('data-target');
    area = $this.attr('aria-controls');
  
    if($(pane).is(':empty')) {
      scriptDoLoad(source, area, '');
      $(this).tab('show');
      return false;
    }
});
</script>