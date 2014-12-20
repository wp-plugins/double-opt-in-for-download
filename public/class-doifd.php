<?php

require_once( DOIFD_DIR . 'public/includes/class-doifd-custom-css.php' );
require_once( DOIFD_DIR . 'public/includes/class-doifd-download.php' );
require_once( DOIFD_DIR . 'public/includes/class-doifd-shortcodes.php' );
require_once( DOIFD_DIR . 'public/includes/class-doifd-registration-form.php' );
require_once( DOIFD_DIR . 'public/includes/class-doifd-registration.php' );
require_once( DOIFD_DIR . 'public/includes/class-doifd-landing-page.php' );
require_once( DOIFD_DIR . 'public/includes/class-doifd-widget.php' );
require_once( DOIFD_DIR . 'public/includes/class-doifd-validation.php' );
require_once( DOIFD_DIR . 'includes/class-doifd-email.php' );

class DOIFD {

    const VERSION = DOIFD_VERSION;

    public $plugin_slug = 'double-opt-in-for-download';
    protected static $instance = null;
    public $doifd_options;

    public function __construct() {

        $this->doifd_options = $this->get_options();

        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
        add_action( 'admin_init', array( $this, 'doifd_upgradecheck' ) );
        add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        add_action( 'activated_plugin', array( $this, 'save_error' ) );

        add_action( 'wp_ajax_populate_download_edit_form', array( $this, 'populate_download_edit_form' ) );

        add_action( 'widgets_init', array( $this, 'doifd_lab_widget' ) );
    }

    public static function get_instance() {

        if( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function get_plugin_slug() {
        return $this->plugin_slug;
    }

    public function get_options() {

        $this->doifd_options = get_option( 'doifd_lab_options' );
        return $this->doifd_options;
    }

    public static function activate( $network_wide ) {

        if( function_exists( 'is_multisite' ) && is_multisite() ) {

            if( $network_wide ) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ( $blog_ids as $blog_id ) {

                    switch_to_blog( $blog_id );
                    self::single_activate();

                    restore_current_blog();
                }
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }

    public static function deactivate( $network_wide ) {

        if( function_exists( 'is_multisite' ) && is_multisite() ) {

            if( $network_wide ) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ( $blog_ids as $blog_id ) {

                    switch_to_blog( $blog_id );
                    self::single_deactivate();

                    restore_current_blog();
                }
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }

    public function activate_new_site( $blog_id ) {

        if( 1 !== did_action( 'wpmu_new_blog' ) ) {
            return;
        }

        switch_to_blog( $blog_id );
        self::single_activate();
        restore_current_blog();
    }

    private static function get_blog_ids() {

        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

        return $wpdb->get_col( $sql );
    }

    private static function single_activate() {

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        global $wpdb;

        if( version_compare( get_bloginfo( 'version' ), '3.0', '<' ) ) {
            deactivate_plugins( basename( __FILE__ ) );
        }

        $old_version = get_option( 'doifd_lab_version' );
        $current_version = DOIFD::VERSION;

        if( $current_version != $old_version ) {

            update_option( 'doifd_lab_version', $current_version );

            self::installRoutine();
        }

        /* Create download directory if it does not exist */

        if( !is_dir( DOIFD_DOWNLOAD_DIR ) ) {
            mkdir( DOIFD_DOWNLOAD_DIR );
        }

        /* Create .htacess file to block access to download folders if it does not exist 
         * is there already an .htaccess file in the download directory?
         */

        if( !is_file( DOIFD_DOWNLOAD_DIR . '.htaccess' ) ) {

            /* Create the .htaccess file in the download directory. */

            $create_name = DOIFD_DOWNLOAD_DIR . '.htaccess';

            /* Open the .htaccess file for editing */

            $file_handle = fopen( $create_name, 'w' ) or die( "Error: Can't open file" );

            /* Add the contents of the .htaccess file */

            $content_string = "deny from all";

            /* Write the file to disk */

            fwrite( $file_handle, $content_string );

            /* Close the file */

            fclose( $file_handle );
        }
    }

    private static function single_deactivate() {
        // @TODO: Define deactivation functionality here
    }

    public function load_plugin_textdomain() {

        $domain = $this->plugin_slug;
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

        load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
        load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
    }

    public function enqueue_styles() {

        wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/style.css', __FILE__ ), array( ), self::VERSION );
        wp_enqueue_style( $this->plugin_slug . '-widget-style', plugins_url( 'assets/css/widget-style.php', __FILE__ ), array( ), self::VERSION );
        wp_enqueue_style( $this->plugin_slug . '-form-style', plugins_url( 'assets/css/form-style.php', __FILE__ ), array( ), self::VERSION );
    }

    public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
    }

    public function save_error() {

        file_put_contents( ABSPATH . 'wp-content/uploads/doifd_downloads/activation_error.txt', ob_get_contents() );
    }

    public function doifd_lab_widget() {
        register_widget( 'DOIFDFormWidget' );
    }

    public function populate_download_edit_form() {

        global $wpdb; // this is how you get access to the database

        if( isset( $_POST[ 'id' ] ) ) {

            $value = $_POST[ 'id' ];

            $download = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}doifd_lab_downloads WHERE doifd_download_id = $value", ARRAY_A );
        }
        echo json_encode( $download );
        die(); // this is required to terminate immediately and return a proper response
    }

    public function doifd_upgradecheck() {

        $current_version = DOIFD::VERSION;

        $installed_version = get_option( 'doifd_lab_version' );

        if( !$installed_version ) {
            //No installed version - we'll assume its just been freshly installed
            add_option( 'doifd_lab_version', $current_version );
        } elseif( $installed_version != $current_version ) {

            self::installRoutine();

            //Database is now up to date: update installed version to latest version
            update_option( 'doifd_lab_version', $current_version );
        }
    }

    private static function installRoutine() {
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        global $wpdb;

        $download_table = $wpdb->prefix . 'doifd_lab_downloads';

        $download = "CREATE TABLE $download_table (
                        doifd_download_id INT(11) NOT NULL AUTO_INCREMENT ,
                        doifd_download_name VARCHAR(250) NOT NULL ,
                        doifd_download_file_name VARCHAR(250) NOT NULL ,
                        doifd_download_landing_page INT(20) NOT NULL ,
                        doifd_number_of_downloads TINYINT(1) DEFAULT '0' NOT NULL ,
                        doifd_download_type TINYINT(1) DEFAULT '0' NOT NULL ,
                        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL ,
                        UNIQUE KEY id (doifd_download_id)
                        );";

        dbDelta( $download );

        $subscriber_table = $wpdb->prefix . 'doifd_lab_subscribers';

        $subscriber = "CREATE TABLE $subscriber_table (
                        doifd_subscriber_id INT(11) NOT NULL AUTO_INCREMENT,
                        doifd_name VARCHAR(250) NOT NULL ,
                        doifd_email VARCHAR(250) NOT NULL ,
                        doifd_email_verified TINYINT(1) DEFAULT '0' NOT NULL  ,
                        doifd_verification_number VARCHAR(75) NOT NULL ,
                        doifd_download_id VARCHAR(45) NOT NULL ,
                        doifd_downloads_allowed TINYINT(1) DEFAULT '0' ,
                        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                        UNIQUE KEY id (doifd_subscriber_id)
                          );";

        dbDelta( $subscriber );

        if( !get_option( 'doifd_lab_options' ) ) {
            $doifd_default_options = array(
                'downloads_allowed' => '1',
                'landing_page' => '',
                'add_to_wpusers' => '0',
                'form_security' => '0',
                'promo_link' => '0',
                'use_privacy_policy' => '0',
                'privacy_link_text' => '',
                'privacy_link_font_size' => '0.9em',
                'privacy_page' => '',
                'use_form_labels' => '1',
                'use_widget_form_labels' => '1',
                'notification_email' => '1',
                'from_email' => '',
                'email_name' => '',
                'email_message' => __( 'Dear {subscriber},

Thank you for your interest in our free download {download}.

Below you will find the link to your download file. We hope you will enjoy it.

{url}

Thank You', 'double-opt-in-for-download' ),
                'widget_class' => ''
            );

            add_option( 'doifd_lab_options', $doifd_default_options );
        }
    }

}