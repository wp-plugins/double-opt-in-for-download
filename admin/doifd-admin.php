<?php

require( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-doifd.php' );

require( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/admin/class-admin-validation.php');

require( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/admin/class-admin-options.php');

require( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/admin/class-admin-downloads.php');

require( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/admin/class-admin-subscribers.php');

require( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/admin/class-admin-widget-options.php');

require( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/admin/class-admin-dashboard-widget.php');

require( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/admin/class-admin-csv.php');

if ( !class_exists ( 'DoifdAdmin' ) ) {

    class DoifdAdmin {

        function __construct() {

            /* Register actions */

            $this->register_admin_actions ();

            /* Call admin dashboard widget from widget class */

            $dashboard_widget = new DoifdAdminWidget();

            /* Call the Download class */

            $downloads = new DoifdDownloads();

            /* Call the CSV class */

            $csv = new DoifdCSV();

        }

        /* This function calls all the add_actions */

        function register_admin_actions() {

            add_action ( 'admin_init', array( &$this, 'doifd_lab_admin_init' ) );
            add_action ( 'admin_notices', array( &$this, 'showAdminMessages' ) );  
            add_action ( 'admin_menu', array( &$this, 'register_doifd_custom_menu_page' ) );
            add_action ( 'admin_init', array( &$this, 'doifd_lab_resend_verification_email' ) );
            add_action ( 'admin_init', array( &$this, 'register_admin_styles' ) );
            add_action ( 'admin_init', array( &$this, 'register_admin_js' ) );

        }

        /* Register the admin style sheets for use */

        function register_admin_styles() {

            wp_register_style ( 'doifd-admin-stylesheet', DOUBLE_OPT_IN_FOR_DOWNLOAD_URL . 'css/admin-style.css', __FILE__ );
            wp_register_style ( 'doifd-jquery-ui', DOUBLE_OPT_IN_FOR_DOWNLOAD_URL . 'css/jquery-ui.css', __FILE__ );
            wp_enqueue_style ( 'doifd-admin-stylesheet' );
            wp_enqueue_style ( 'doifd-jquery-ui' );

        }

        function register_admin_js() {

            wp_enqueue_script ( 'doifd-admin-js', DOUBLE_OPT_IN_FOR_DOWNLOAD_URL . 'js/admin.js', __FILE__ );
            wp_enqueue_script ( 'jquery-ui-core' );
            wp_enqueue_script ( 'jquery-ui-dialog' );

//             wp_enqueue_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js', array('jquery'), '1.8.16');

        }

        /* Register the options for the options page & reCAPTCHA option page */

        function doifd_lab_admin_init() {

            global $plugin_slug;

            register_setting ( 'doifd_lab_options', 'doifd_lab_options', array( &$this, 'doifd_lab_validate_options' ) );
            add_settings_section ( 'doifd_lab_main', __ ( 'General Settings', 'double-opt-in-for-download' ), '', 'doifd_lab' );
            add_settings_field ( 'doifd_lab_downloads_allowed', __ ( 'Select Maximum Number of Downloads', 'double-opt-in-for-download' ), array( &$this, 'doifd_lab_setting_input' ), 'doifd_lab', 'doifd_lab_main' );
            add_settings_field ( 'doifd_lab_landing_page', __ ( 'Select Landing page', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_option' ), 'doifd_lab', 'doifd_lab_main' );
            add_settings_field ( 'doifd_lab_add_to_wpusers', __ ( 'Add Subscribers to the Wordpress User Table?', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_add_to_wp_user_table' ), 'doifd_lab', 'doifd_lab_main' );
            add_settings_field ( 'doifd_lab_promo_link', __ ( 'Help Us Out?<br />Add a promotional link', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_add_promo_link' ), 'doifd_lab', 'doifd_lab_main' );
            add_settings_section ( 'doifd_lab_email_section', __ ( 'Email Settings', 'double-opt-in-for-download' ), '', 'doifd_lab' );
            add_settings_field ( 'doifd_lab_from_email', __ ( 'Enter The Return Email Address', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_from_email' ), 'doifd_lab', 'doifd_lab_email_section' );
            add_settings_field ( 'doifd_lab_email_name', __ ( 'Enter who the email is from<br>(Default is the Website or Blog name)', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_email_name' ), 'doifd_lab', 'doifd_lab_email_section' );
            add_settings_field ( 'doifd_lab_email_message', __ ( 'Email Message:', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_email_message' ), 'doifd_lab', 'doifd_lab_email_section' );
            add_settings_section ( 'doifd_lab_widget_style_section', __ ( 'Widget Style Settings', 'double-opt-in-for-download' ), '', 'doifd_lab' );
            add_settings_field ( 'doifd_lab_widget_style_width', __ ( 'Width of Widget', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_widget_width' ), 'doifd_lab', 'doifd_lab_widget_style_section' );
            add_settings_field ( 'doifd_lab_widget_style_inside_padding', __ ( 'Widget Inside Padding', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_widget_inside_padding' ), 'doifd_lab', 'doifd_lab_widget_style_section' );
            add_settings_field ( 'doifd_lab_widget_style_margin_top', __ ( 'Widget Margin Top', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_widget_margin_top' ), 'doifd_lab', 'doifd_lab_widget_style_section' );
            add_settings_field ( 'doifd_lab_widget_style_margin_right', __ ( 'Widget Margin Right', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_widget_margin_right' ), 'doifd_lab', 'doifd_lab_widget_style_section' );
            add_settings_field ( 'doifd_lab_widget_style_margin_bottom', __ ( 'Widget Margin Bottom', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_widget_margin_bottom' ), 'doifd_lab', 'doifd_lab_widget_style_section' );
            add_settings_field ( 'doifd_lab_widget_style_margin_left', __ ( 'Widget Margin Left', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_widget_margin_left' ), 'doifd_lab', 'doifd_lab_widget_style_section' );
            add_settings_field ( 'doifd_lab_widget_style_input_width', __ ( 'Widget Input Width', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_widget_input_width' ), 'doifd_lab', 'doifd_lab_widget_style_section' );
            register_setting ( 'doifd_lab_recaptcha_options', 'doifd_lab_recaptcha_options', array( $this, 'doifd_lab_validate_recaptcha_options' ) );
            add_settings_section ( 'doifd_lab_recaptcha_section', __ ( 'reCaptcha Settings', 'double-opt-in-for-download' ), '', 'doifd_lab_recaptcha' );
            add_settings_field ( 'doifd_lab_recaptcha_public_key', __ ( 'Public Key', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_recaptcha_public_key' ), 'doifd_lab_recaptcha', 'doifd_lab_recaptcha_section' );
            add_settings_field ( 'doifd_lab_recaptcha_private_key', __ ( 'Private Key', 'double-opt-in-for-download' ), array( $this, 'doifd_lab_setting_recaptcha_private_key' ), 'doifd_lab_recaptcha', 'doifd_lab_recaptcha_section' );

        }

        /* Add the custom menu pages */

        function register_doifd_custom_menu_page() {

            global $plugin_slug;

            // create main menu page
            add_menu_page ( 'doifd menu title', __ ( 'DOI - Downloads', 'double-opt-in-for-download' ), 'manage_options', __FILE__, array( $this, 'doifd_lab_options_page' ) );

            //create sub menu page for downloads
            add_submenu_page ( __FILE__, 'Settings', __ ( 'Settings', 'double-opt-in-for-download' ), 'manage_options', __FILE__, array( $this, 'doifd_lab_options_page' ) );

            //create sub menu page for downloads
            add_submenu_page ( __FILE__, 'doifd downloads', __ ( 'Downloads', 'double-opt-in-for-download' ), 'manage_options', __FILE__ . '_downloads', array( $this, 'doifd_download_page' ) );

            //create sub menu page for subscribers
            add_submenu_page ( __FILE__, 'doifd subscribers', __ ( 'Subscribers', 'double-opt-in-for-download' ), 'manage_options', __FILE__ . '_subscribers', array( $this, 'doifd_lab_subscribers_page' ) );

            //create sub menu page for editing downloads
            add_submenu_page ( __FILE__, 'doifd edit downloads', '', 'manage_options', __FILE__ . '_edit_downloads', array( $this, 'doifd_lab_edit_downloads_page' ) );

            if ( file_exists ( DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_DIR ) ) {
                //create sub menu page for subscribers
                add_submenu_page ( __FILE__, 'doifd recaptcha', 'reCaptcha', 'manage_options', __FILE__ . '_recaptcha', array( $this, 'doifd_lab_recaptcha_page' ) );
            }

        }

        /*         * *************************************
         * Start DOWNLOAD page & settings fields 
         * **************************************
         */

        /* Get the Download page */

        function doifd_download_page() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'views/view-admin-download-page.php' );
            }

        }

        /* Get the Edit Download page */

        function doifd_lab_edit_downloads_page() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'views/view-admin-edit-download-page.php' );
            }

        }

        /* ****************************************
         * Start SUBSCRIBERS page & settings fields 
         * *****************************************
         */

        /* Get the Subscribers page */

        function doifd_lab_subscribers_page() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            }

            include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'views/view-admin-subscribers-page.php' );

        }

        /*         * ************************************
         * Start OPTIONS page & settings fields 
         * **************************************
         */

        /* Create the Options page */

        function doifd_lab_options_page() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            }

            return DoifdAdminOptions::options_page ();

        }

        /* Create the maximum number of downloads field */

        function doifd_lab_setting_input() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminOptions::allowed_downloads ();
            }

        }

        /* Create the Landing page dropdown select field */

        function doifd_lab_setting_option() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminOptions::select_landing_page ();
            }

        }

        /* Create the add user to wordpress user table radio select option */

        function doifd_lab_add_to_wp_user_table() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminOptions::add_to_user_table ();
            }

        }

        /* Add promotional link option to the admin form */

        function doifd_lab_add_promo_link() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminOptions::add_promo_link ();
            }

        }

        /* Validate the admin input options just in case */

        function doifd_lab_validate_options( $input ) {

            $validate_options = new DoifdAdminValidation();
            return $validate_options->admin_options_validation ( $input );

        }

        /* Create the from email address field to the options page */

        function doifd_lab_setting_from_email() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminOptions::from_email_address_field ();
            }

        }

        /* Create the from email name field to the options page */

        function doifd_lab_setting_email_name() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminOptions::from_email_name_field ();
            }

        }

        /* Create the Email message field for the admin options */

        function doifd_lab_setting_email_message() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminOptions::email_message_field ();
            }

        }

        /* Create the Widget width option field */

        function doifd_lab_setting_widget_width() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminWidgetOptions::field_widget_width ();
            }

        }

        /* Create the Widget inside padding field */

        function doifd_lab_setting_widget_inside_padding() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminWidgetOptions::field_widget_inside_padding ();
            }

        }

        /* Create the Widget margin top field */

        function doifd_lab_setting_widget_margin_top() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminWidgetOptions::field_widget_top_margin ();
            }

        }

        /* Create the Widget margin right field */

        function doifd_lab_setting_widget_margin_right() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminWidgetOptions::field_widget_right_margin ();
            }

        }

        /* Create the Widget margin bottom field */

        function doifd_lab_setting_widget_margin_bottom() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminWidgetOptions::field_widget_bottom_margin ();
            }

        }

        /* Create the Widget margin left field */

        function doifd_lab_setting_widget_margin_left() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminWidgetOptions::field_widget_left_margin ();
            }

        }

        /* Create the Widget input field width field */

        function doifd_lab_setting_widget_input_width() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdAdminWidgetOptions::field_input_field_width ();
            }

        }

        /* ************************************
         * Start reCAPTCHA page & options fields 
         * **************************************
         */

        /* Create the reCAPTCHA page */

        function doifd_lab_recaptcha_page() {

            // check to see if the user has the privileges to access this options page.

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdCaptcha::reCaptcha_admin_options_page ();
            }

        }

        /* Create the reCAPTCHA public key field in the admin section */

        function doifd_lab_setting_recaptcha_public_key() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {


                return DoifdCaptcha::recaptcha_public_key_field ();
            }

        }

        /* Create the reCAPTCHA private key field in the admin section */

        function doifd_lab_setting_recaptcha_private_key() {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdCaptcha::recaptcha_private_key_field ();
            }

        }

        /* Sanitize and validate reCAPTCHA API keys */

        function doifd_lab_validate_recaptcha_options( $input ) {

            if ( !current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                return DoifdCaptcha::reCaptcha_validate ( $input );
            }

        }

        /* ************************
         * Resend verification Email 
         * **************************
         */

        /* Function to resend the verification email */

        function doifd_lab_resend_verification_email() {

            $resend_email = new DoifdEmail();
            $resend_email->admin_resend_verification_email ();

        }

        function showMessage( $message, $errormsg = false ) {

            if ( $errormsg ) {
                echo '<div id="message" class="error">';
            } else {
                echo '<div id="message" class="updated fade">';
            }

            echo "<p><strong>$message</strong></p></div>";

        }

        function showAdminMessages() {

            // Only show to admins
            
            if ( current_user_can ( 'manage_options' ) ) {

                wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'double-opt-in-for-download' ) );
            } else {

                /* Get options from options table */

                $options = get_option ( 'doifd_lab_options' );

                /* Get the landing page options */

                $landing_page = $options[ 'landing_page' ];

                /* If there is no value for the landing page option show an error message. */
                
                if ( empty ( $landing_page ) ) {

                    $this->showMessage( __( "The landing page option in Double OPT-IN for Downloads is NOT SET. Please select a landing page otherwise the plugin will not work properly", 'double-opt-in-for-download' ) );
                }
            }   
        }

    }
    
}
?>