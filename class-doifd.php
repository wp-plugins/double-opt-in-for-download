<?php

/*  Load necessary classes */

include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/doifd-widget.php' );
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-email.php' );
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-registration-form.php' );
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-download.php' );
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_DIR . 'class-captcha.php' );

class DOIFD {

    function __construct() {

//            $this->register_default_options();
        // require the recaptcha library
        $this->require_library ();

        /* register the hooks */

        $this->register_actions ();

        /* register the shortcodes */

        $this->register_shortcodes ();

        /* register the languages (textdomain) */
        
        $this->language();

        $this->doifd_admin ();

    }

    function register_actions() {
        
        /*  Activates wordpress hook to run the install function */

        register_activation_hook ( __FILE__, 'doifd_lab_install' );
        
        /* Creates an error log for errors during activation */

        add_action ( 'activated_plugin', array( &$this, 'save_error' ) );

        /* Enqueues the style sheets */

        add_action ( 'wp_enqueue_scripts', array( &$this, 'doifd_lab_add_stylesheet' ) );

        /* Process the email */

        add_action ( 'init', array( &$this, 'doifd_lab_verification_email' ) );

        /* Registers the Widget */

        add_action ( 'widgets_init', array( &$this, 'doifd_lab_widget' ) );

    }

    function register_shortcodes() {

        /* Landing Page Button */

        add_shortcode ( 'lab_landing_page', array( &$this, 'doifd_lab_verify_email' ) );

        /* Page / Post Registration Form */

        add_shortcode ( 'lab_subscriber_download_form', array( &$this, 'doifd_lab_subscriber_registration_form' ) );

    }

    /*Load language translation files */

    function language() {

        load_plugin_textdomain ( 'double-opt-in-for-download', false, DOUBLE_OPT_IN_FOR_DOWNLOAD_LANGUAGES_DIR );

    }

    /* Include admin scripts if the admin is logged in. */

    function doifd_admin() {

        if ( is_admin () ) {

            require_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/admin/doifd-admin.php' );

            $doifd = new DoifdAdmin();
        }

    }

    /* For Debuggin Purposes - Saves plugin activation errors to file for review */

    function save_error() {

        file_put_contents ( ABSPATH . 'wp-content/uploads/2013/error_activation.html', ob_get_contents () );

    }

    /* Install the plugin */

    function doifd_lab_install() {

        include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-install.php' );

        $install_doifd = new DoifdInstall();
        $install_doifd->install_plugin ();

    }

    /* Engueue the style sheets for the plugin */

    function doifd_lab_add_stylesheet() {

        wp_register_style ( 'doifd-style', plugins_url ( 'css/style.css', __FILE__ ) );
        wp_enqueue_style ( 'doifd-style' );
        wp_register_style ( 'doifd-widget-style', plugins_url ( 'css/widget-style.php', __FILE__ ) );
        wp_enqueue_style ( 'doifd-widget-style' );

    }

    /* Include the reCAPTCHA library */
    
    function require_library() {
        require_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/captcha/recaptchalib.php');

    }

    /* Create the registration form */

    function doifd_lab_subscriber_registration_form( $attr, $content ) {

        $form = new DoifdRegistrationForm();
        return $form->registration_form ( $attr, $content );

    }

    /* This function does the bulk of the work. When the user clicks the
     * link in thier email this function checks the verification code to
     * make sure it's valid and to see if the have exceeded there
     * download limit.
     */

    function doifd_lab_verify_email() {

        $verify = new DoifdRegistrationForm();
        return $verify->verify_email ( $value );

    }

    /* This is the function that sends the email to the subscriber */

    function doifd_lab_verification_email( $value ) {

        $email = new DoifdEmail();
        $email->send_verification_email ( $value );

    }

    /* Register the widget */

    function doifd_lab_widget() {
        
        register_widget ( 'DoifdFormWidget' );

    }

}

?>