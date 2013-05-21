<?php

if ( !defined('ABSPATH') )
    die('You are not allowed to call this page directly.');

global $wpdb;
global $pinimp;

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

// Get WordPress scripts and styles
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-widget');
wp_enqueue_script('jquery-ui-position');

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Pinterest Import</title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<?php wp_print_scripts() ?>
    <base target="_self" />
</head>
<script type="text/javascript">
    jQuery(document).ready(function(){
        tinyMCEPopup.resizeToInnerSize();

        jQuery("input.insert-image").click(function() {
            if(window.tinyMCE) {
                window.tinyMCE.execInstanceCommand(window.tinyMCE.activeEditor.id, 'mceInsertContent', false, 'test');
                tinyMCEPopup.editor.execCommand('mceRepaint');
                tinyMCEPopup.close();
            }
            return;
        });

        jQuery("input.close-dialog").click(function() {
            tinyMCEPopup.close();
            return;
        });
    });
</script>
<body class="nextgen_tinymce_window" id="link">
	<form name="PinterestImport" action="#">
        <div class="mceActionPanel">
            <div style="float: left">
                <input type="button" id="cancel" name="cancel" class="close-dialog" value="<?php _e("Cancel", 'pinimp'); ?>" />
            </div>

            <div style="float: right">
                <input type="submit" id="insert" name="insert" class="insert-image" value="<?php _e("Insert", 'pinimp'); ?>" />
            </div>
        </div>
    </form>
</body>
</html>