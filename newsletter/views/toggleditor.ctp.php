<textarea name="mail_content" id="mail_content" class="mceSimple" style="width:100%"><?=stripslashes(urldecode($post['toggle_content']))?></textarea>
<?php if ($post['toggle_val'] == 1) { ?>
    <script>
    tinyMCE.init({
    	mode : "textareas",
    	theme : "advanced",
    	theme_advanced_buttons1 : "mylistbox,style,mysplitbutton,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,|,bullist,numlist,undo,redo,link,unlink",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
    	editor_selector : "mceSimple",
    });
    </script>
<?php }?>
</form>