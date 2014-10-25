<?php

include_once 'class-doifd.php';

if ( !class_exists ( 'DoifdInstall' ) ) {

    class DoifdInstall {

        /* Declare version number of Plugin */

        function __construct() {
            
        }

        public static function activate_doifd_plugin() {

            /* Install function that creates the tables needed by the plugin and adds our options to wordpres */

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            global $wpdb;

            /* If wordpress version is 3.0 or below, deactivate plugin */

            if ( version_compare ( get_bloginfo ( 'version' ), '3.0', '<' ) ) {
                deactivate_plugins ( basename ( __FILE__ ) );
            }

            /* Get Version number from wordpress database. */

            $doifd_lab_installed_ver = get_option ( 'doifd_lab_version' );

            /* If version number is different create/update plugin tables */

            $current_version = '1.1.7';

            if ( $doifd_lab_installed_ver != $current_version ) {

                update_option ( 'doifd_lab_version', $current_version );
                /* Assign subscribers table name to variable */

                $doifd_lab_table_name1 = $wpdb->prefix . 'doifd_lab_subscribers';

                /* Assign downloads table name to variable */

                $doifd_lab_table_name2 = $wpdb->prefix . 'doifd_lab_downloads';

                /* Create Subscribers Table */

                $doifd_lab_sql1 = "CREATE TABLE $doifd_lab_table_name1 (
                        doifd_subscriber_id INT(11) NOT NULL AUTO_INCREMENT,
                        doifd_name VARCHAR(45) NOT NULL ,
                        doifd_email VARCHAR(75) NOT NULL ,
                        doifd_email_verified TINYINT(1) DEFAULT '0' NOT NULL  ,
                        doifd_verification_number VARCHAR(75) NOT NULL ,
                        doifd_download_id VARCHAR(45) NOT NULL ,
                        doifd_downloads_allowed TINYINT(1) DEFAULT '0' ,
                        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                        UNIQUE KEY id (doifd_subscriber_id)
                          );";
                dbDelta ( $doifd_lab_sql1 );

                /* Create Downloads Table */

                $doifd_lab_sql2 = "CREATE TABLE $doifd_lab_table_name2 (
                        doifd_download_id INT(11) NOT NULL AUTO_INCREMENT ,
                        doifd_download_name VARCHAR(75) NOT NULL ,
                        doifd_download_file_name VARCHAR(75) NOT NULL ,
                        doifd_download_landing_page INT(20) NOT NULL ,
                        doifd_number_of_downloads TINYINT(1) DEFAULT '0' NOT NULL ,
                        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL ,
                        UNIQUE KEY id (doifd_download_id)
                        );";
                dbDelta ( $doifd_lab_sql2 );

                /* Add version to Wordpress options table */
            }

            /* Add plugin default options to wordpress options table */

            if ( !get_option ( 'doifd_lab_options' ) ) {
                $doifd_default_options = array(
                    'downloads_allowed' => '1',
                    'landing_page' => '',
                    'email_name' => '',
                    'from_email' => '',
                    'add_to_wpusers' => '0',
                    'promo_link' => '0',
                    'email_message' => __( 'Dear {subscriber},

Thank you for your interest in our free download {download}.

Below you will find the link to your download file. We hope you will enjoy it.

{url}

Thank You', 'double-opt-in-for-download' )
                );

                add_option ( 'doifd_lab_options', $doifd_default_options );
            }

            /* Create download directory if it does not exist */

            if ( !is_dir ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR ) ) {
                mkdir ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR );
            }

            /* Create .htacess file to block access to download folders if it does not exist 
             * is there already an .htaccess file in the download directory?
             */

            if ( !is_file ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . '.htaccess' ) ) {

                /* Create the .htaccess file in the download directory. */

                $create_name = DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . '.htaccess';

                /* Open the .htaccess file for editing */

                $file_handle = fopen ( $create_name, 'w' ) or die ( "Error: Can't open file" );

                /* Add the contents of the .htaccess file */

                $content_string = "deny from all";

                /* Write the file to disk */

                fwrite ( $file_handle, $content_string );

                /* Close the file */

                fclose ( $file_handle );
            }

        }

    }

}

?>
