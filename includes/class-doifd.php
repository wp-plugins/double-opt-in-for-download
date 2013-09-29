<?php

/*  Load necessary classes */

include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-install.php' );
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/doifd-widget.php' );
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-email.php' );
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-registration-form.php' );
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-landing-page.php' );
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-download.php' );

if ( file_exists( DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_DIR ) ) {

    include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_DIR . 'class-captcha.php' );
}

if ( !class_exists( 'DOIFD' ) ) {

    class DOIFD {

        protected static $instance = null;
        protected $plugin_screen_hook_suffix = null;

        function __construct() {

            /* require the recaptcha library */

            if ( file_exists( DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_DIR ) ) {

                $this->require_library();
            }

            /* register the hooks */

            $this->register_actions();

            /* register the shortcodes */

            $this->register_shortcodes();

            /* Call the admin function */

            $this->doifd_admin();

            /* Call the Download Class to handle the download */

            DoifdDownload::link_to_download();
        }

        public static function get_instance() {

            /* If the single instance hasn't been set, set it now. */

            if ( null == self::$instance ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        function register_actions() {

            add_action( 'init', array ( &$this, 'doifd_language' ) );

            /* Creates an error log for errors during activation */

            add_action( 'activated_plugin', array ( &$this, 'save_error' ) );

            /* Enqueues the style sheets */

            add_action( 'wp_enqueue_scripts', array ( &$this, 'doifd_lab_add_stylesheet' ) );

            /* Process the email */

            add_action( 'init', array ( &$this, 'doifd_lab_verification_email' ) );

            /* Registers the Widget */

            add_action( 'widgets_init', array ( &$this, 'doifd_lab_widget' ) );
        }

        function register_shortcodes() {

            /* Landing Page Button */

            add_shortcode( 'lab_landing_page', array ( &$this, 'doifd_lab_verify_email' ) );

            /* The Registration Form for Posts & Pages */

            add_shortcode( 'lab_subscriber_download_form', array ( &$this, 'doifd_lab_subscriber_registration_form' ) );
        }

        /* Load language translation files */

        function doifd_language() {

            $lang_loaded = load_plugin_textdomain( 'double-opt-in-for-download', false, DOUBLE_OPT_IN_FOR_DOWNLOAD_LANGUAGES_DIR );
        }

        /* Include admin scripts if the admin is logged in. */

        function doifd_admin() {

            if ( is_admin() ) {

                require_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/admin/doifd-admin.php' );

                $doifd = new DoifdAdmin();
            }
        }

        /* For Debuggin Purposes - Saves plugin activation errors to file for review */

        function save_error() {

            file_put_contents( ABSPATH . 'wp-content/uploads/doifd_downloads/error_activation.html', ob_get_contents() );
        }

        /* Engueue the style sheets for the plugin */

        function doifd_lab_add_stylesheet() {

            global $is_IE;

            if ( $is_IE ) {
                wp_register_style( 'doifd-style-ie8', DOUBLE_OPT_IN_FOR_DOWNLOAD_URL . 'css/style-ie8.css' );
                $GLOBALS[ 'wp_styles' ]->add_data( 'doifd-style-ie8', 'conditional', 'lte IE 8' );
                wp_enqueue_style( 'doifd-style-ie8' );
            }
            /* Register Style Sheet for forms and widget */

            wp_register_style( 'doifd-style', DOUBLE_OPT_IN_FOR_DOWNLOAD_URL . 'css/style.css', __FILE__ );
            wp_register_style( 'doifd-widget-style', DOUBLE_OPT_IN_FOR_DOWNLOAD_URL . 'css/widget-style.php', __FILE__ );
            wp_register_style( 'doifd-form-style', DOUBLE_OPT_IN_FOR_DOWNLOAD_URL . 'css/form-style.php', __FILE__ );

            /* Enqueue Style Sheets */

            wp_enqueue_style( 'doifd-style' );
            wp_enqueue_style( 'doifd-widget-style' );
            wp_enqueue_style( 'doifd-form-style' );
        }

        /* Include the reCAPTCHA library */

        function require_library() {

            require_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/captcha/recaptchalib.php');
        }

        /* Create the registration form */

        function doifd_lab_subscriber_registration_form( $attr, $content ) {

            return DoifdRegistrationForm::registration_form( $attr, $content );
        }

        /* This function does the bulk of the work. When the user clicks the
         * link in thier email this function checks the verification code to
         * make sure it's valid and to see if the have exceeded there
         * download limit.
         */

        function doifd_lab_verify_email( $attr, $content ) {

            return DoifdLandingPage::verify_email( $attr, $content );
        }

        /* This is the function that sends the email to the subscriber */

        function doifd_lab_verification_email( $value ) {

            DoifdEmail::send_verification_email( $value );
        }

        /* Register the widget */

        function doifd_lab_widget() {

            register_widget( 'DoifdFormWidget' );
        }

    }

}
?>