<?php
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}

/**
 * creates all tables
 * called during register_activation hook
 *
 * @access internal
 * @return void
 */
function pinimp_install()
{
    global $wpdb, $wp_roles, $wp_version;

    // Check for capability
    //if (!current_user_can('activate_plugins'))
    //    return;

    // Set the capabilities for the administrator
    $role = get_role('administrator');
    // We need this role, no other chance
    if (empty($role)) {
        update_option("pinimp_init_check", __('Sorry, Pinterest Import works only with a role called administrator', "pinimp"));
        return;
    }

    // upgrade function changed in WordPress 2.3
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // add charset & collate like wp core
    $charset_collate = '';

    if (version_compare(mysql_get_server_info(), '4.1.0', '>=')) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";
    }

    $pinimp_images = $wpdb->prefix . 'pinimp_images';

    // Create pictures table
    $sql = "CREATE TABLE " . $pinimp_images . " (
        `pid` BIGINT(20) NOT NULL AUTO_INCREMENT ,
        `id` VARCHAR(255) NOT NULL,
        `title` VARCHAR(255) NOT NULL,
        `filename` VARCHAR(255) NOT NULL ,
        `sourcefeed` MEDIUMTEXT NULL ,
        `imagedate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
        `meta_data` LONGTEXT,
        PRIMARY KEY (`pid`)
    ) $charset_collate;";

    dbDelta($sql);
}

/**
 * Deregister a capability from all classic roles
 *
 * @access internal
 * @param string $capability name of the capability which should be deregister
 * @return void
 */
function pinimp_remove_capability($capability)
{
    // this function remove the $capability only from the classic roles
    $check_order = array("subscriber", "contributor", "author", "editor", "administrator");

    foreach ($check_order as $role) {
        $role = get_role($role);
        $role->remove_cap($capability);
    }

}

/**
 * Uninstall all settings and tables
 * Called via Setup and register_unstall hook
 *
 * @access internal
 * @return void
 */
function pinimp_uninstall()
{
    global $wpdb;

    // first remove all tables
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}pinimp_images");

    // then remove all options
    delete_option('_pinimp_feed_urls');
}

?>