<?php

class pinImpAdminPanel
{
    // constructor
    function __construct()
    {
        // Add the admin menu
        add_action('admin_menu', array(&$this, 'add_menu'));

        //add_action('admin_head', array($this, 'getOptionsHead'));
    }

    function add_menu()
    {
        add_menu_page(_n('Image', 'Images', 1, PINIMPTRANSLATE), _n('Image', 'Images', 1, PINIMPTRANSLATE), 'Pinterest feed overview', PINIMPFOLDER, array(&$this, 'show_menu'), path_join(PINIMP_URLPATH, 'admin/images/icon_16.png'));

        // Add a new submenu under Options:
        add_options_page('Pinterest Import', 'Pinterest Import', 'manage_options', 'pinterestimport', array($this, 'createOptionsPage'));
    }

    function createOptionsPage()
    {
        if (isset($_POST['_pinimp_feed_urls'])) {
            $_pinimp_feed_urls = $_POST['_pinimp_feed_urls'];

            // TODO: Validate URLs and add .rss if required

            update_option('_pinimp_feed_urls', $_pinimp_feed_urls);
        }

        include_once( dirname(__FILE__) . '/settings.php');
    }

    function show_menu()
    {


    }
}