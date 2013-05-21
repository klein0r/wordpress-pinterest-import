<?php

add_action('wp_ajax_pinimp_tinymce', 'pinimp_ajax_tinymce');
/**
 * Call TinyMCE window content via admin-ajax
 *
 * @since 0.1
 * @return html content
 */
function pinimp_ajax_tinymce() {

    // check for rights
    if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') )
        die(__("You are not allowed to be here"));

    include_once( dirname( dirname(__FILE__) ) . '/admin/tinymce/window.php');

    throw new E_Clean_Exit();
}