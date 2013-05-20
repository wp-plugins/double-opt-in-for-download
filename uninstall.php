<?php

//If uninstall not called from Wordpress exit

if ( ! defined ( 'WP_UNINSTALL_PLUGIN' ) )
    exit ;

/* * ************************************
 * Delete Options from WP_Options table
 * ************************************
 */

delete_option ( 'doifd_lab_version' ) ;
delete_option ( 'doifd_lab_options' ) ;

/* * ************************************
 * Remove tables created by plugin
 * ************************************
 */

global $wpdb ;

$table_name = $wpdb -> prefix . doifd_lab_subscribers ;

$sql1 = "DROP TABLE IF EXISTS " . $table_name ;

$wpdb -> query ( $sql1 ) ;

$table_name = $wpdb -> prefix . doifd_lab_downloads ;

$sql2 = "DROP TABLE IF EXISTS " . $table_name ;

$wpdb -> query ( $sql2 ) ;

?>
