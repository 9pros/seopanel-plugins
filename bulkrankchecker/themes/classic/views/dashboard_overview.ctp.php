<div class="col-sm-12">

	<ul class="nav nav-tabs" role="tablist" style="margin-top: 6px;">
    	<?php
    	$i = 0;
    	foreach ($headList as $head => $headLabel) {
    	    $pageOVUrl = pluginLink("action=$head");
    	    $linkClass = "";
    	    if ($i == 0) {
    	        $linkClass = "active";
    	    }
            ?>
            <li role="presentation" class="nav-item">
            	<a 
            		class="sub_menu_link nav-link <?php echo $linkClass?>" href="#" data-href="<?php echo $pageOVUrl?>" 
            		id="link-<?php echo $i;?>" aria-controls="section-<?php echo $i;?>" role="tab" data-target="#section-<?php echo $i;?>" 
            		data-toggle="tab">
            			<?php echo $headLabel?>
            	</a>
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
    	foreach ($headList as $head => $headLabel) {
    	    $linkClass = $i ? "" : "active"; 
            ?>
        	<div role="tabpanel" class="tab-pane <?php echo $linkClass?>" id="section-<?php echo $i;?>"></div>
            <?php
            $i++;
    	}
    	?>
	</div>
  
</div>

<script type="text/javascript">
$(document).ready(function() {
	scriptDoLoad('<?php echo pluginLink('action=report')?>', 'section-0', '');
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