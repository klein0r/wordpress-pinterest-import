<?php
/**
 * @package PinterestImport
 */
/*
Plugin Name: Pinterest Import
Plugin URI: http://mkleine.de
Description: Will add a button to the ...
Version: 0.0.1
Author: Matthias Kleine
Author URI: http://mkleine.de
License: GPLv2 or later
*/

require_once(dirname(__FILE__).'/lib/plugin_functions.php');
require_once(dirname(__FILE__).'/lib/autoload.php');

if (!class_exists('pinterestImport')) {
    class pinterestImport
    {
        protected $plugin_name;
        protected $adminPanel;

        public function __construct()
        {
            $this->defineConstants();
            $this->loadDependencies();

            $this->declareHooks();
        }

        public function declareHooks()
        {
            $this->plugin_name = basename(dirname(__FILE__)) . '/' . basename(__FILE__);

            // Init options & tables during activation & deregister init option
            register_activation_hook($this->plugin_name, array(&$this, 'activate'));
            register_deactivation_hook($this->plugin_name, array(&$this, 'deactivate'));

            // Register a uninstall hook to remove all tables & option automatic
            register_uninstall_hook($this->plugin_name, array(__CLASS__, 'uninstall'));
        }

        function defineConstants()
        {
            define('PINIMPTRANSLATE', 'PinImp');
            define('PINIMPFOLDER', basename(dirname(__FILE__)));
            define('PINIMP_URLPATH', trailingslashit(plugins_url(PINIMPFOLDER)));
        }

        public function loadDependencies()
        {
            // We don't need all stuff during a AJAX operation
            if (defined('DOING_AJAX')) {
                require_once(dirname(__FILE__) . '/admin/ajax.php');
            } else {
                include_once(dirname(__FILE__) . '/admin/tinymce/tinymce.php');

                // Load backend libraries
                if (is_admin()) {
                    require_once(dirname(__FILE__) . '/admin/feedupdater.php');
                    require_once(dirname(__FILE__) . '/admin/admin.php');
                    $this->adminPanel = new pinImpAdminPanel();
                }
            }
        }

        function activate()
        {
            global $wpdb;
            //Starting from version 1.8.0 it's works only with PHP5.2
            if (version_compare(PHP_VERSION, '5.2.0', '<')) {
                deactivate_plugins($this->plugin_name); // Deactivate ourself
                wp_die("Sorry, but you can't run this plugin, it requires PHP 5.2 or higher.");
                return;
            }

            include_once(dirname(__FILE__) . '/admin/install.php');

            if (is_multisite()) {
                $network = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : "";
                $activate = isset($_GET['action']) ? $_GET['action'] : "";
                $isNetwork = ($network == '/wp-admin/network/plugins.php') ? true : false;
                $isActivation = ($activate == 'deactivate') ? false : true;

                if ($isNetwork and $isActivation) {
                    $old_blog = $wpdb->blogid;
                    $blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs", NULL));
                    foreach ($blogids as $blog_id) {
                        switch_to_blog($blog_id);
                        pinimp_install();
                    }
                    switch_to_blog($old_blog);
                    return;
                }
            }

            // check for tables
            pinimp_install();
        }

        function deactivate()
        {

        }

        function uninstall()
        {
            include_once(dirname(__FILE__) . '/admin/install.php');
            pinimp_uninstall();
        }
    }

    // Let's start the holy plugin
    global $pinimp;
    $pinimp = new pinterestImport();
}
