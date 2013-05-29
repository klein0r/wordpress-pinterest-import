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
            $_pinimp_feed_urls = str_replace("\r\n", "\r", $_pinimp_feed_urls);

            $valid_urls = array();
            foreach (explode("\r", $_pinimp_feed_urls) as $url) {

                // Only allow pinterest urls
                if (contains($url, 'pinterest')) {

                    if (!endsWith($url, '.rss')) {

                        if (endsWith($url, '/')) {
                            $url = substr($url, 0, strlen($url) - 1);
                        }

                        $url .= '.rss';
                    }

                    // Validate with simple pie
                    $feed = new SimplePie();
                    $feed->enable_cache(false);
                    $feed->set_feed_url($url);
                    $feed->init();
                    $feed->handle_content_type();

                    if (!$feed->error())
                    {
                        $valid_urls[] = $url;
                    }

                }

            }

            update_option('_pinimp_feed_urls', implode("\r", $valid_urls));
        }

        include_once( dirname(__FILE__) . '/settings.php');
    }

    function show_menu()
    {


    }


}