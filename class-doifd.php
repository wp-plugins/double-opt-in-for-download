<?php

/*
  Plugin Name: Double Opt In For Download
  Plugin URI: http://www.labwebdesigns.com/wordpress-plugins.html
  Description: Plugin for allowing download in exchange for email address
  Author: Labwebdesigns.com / Andy Bates
  Version: 0.9
  Author URI: http://www.labwebdesigns.com
  License: GPLv3

  Double OPT-IN for download
  Copyright (C) 2013, Lab Web Designs / Andy Bates - andy@labwebdesigns.com

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


// Load widget file

include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/doifd-widget.php' ) ;
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-email.php' ) ;
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-registration-form.php' ) ;
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-download.php' ) ;

// Load reCaptcha Library
include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_DIR  . 'class-captcha.php' );


class DOIFD {

    function __construct() {
            
//            $this->register_default_options();
            
            // require the recaptcha library
            $this->require_library();
            
            // register the hooks
            $this->register_actions();
            
            $this->register_shortcodes();
            
            $this->launguage();
//            $this->register_filters();
            
            $this->doifd_admin();
        
        }
        
        
function register_actions() {

    /* Creates an error log for errors during activation */
    add_action ( 'activated_plugin' , array(&$this, 'save_error' ) ) ;
    
    /* Enqueues the style sheets */
    
    add_action ( 'wp_enqueue_scripts' , array( &$this, 'doifd_lab_add_stylesheet' ) ) ;
    
    /* Process the email */
    
    add_action ( 'init' , array( &$this, 'doifd_lab_verification_email' ) ) ;
    
    /* Registers the Widget */
    
    add_action ( 'widgets_init' , array( &$this, 'doifd_lab_widget' ) ) ;

}

function register_shortcodes() {
    
    /* Landing Page Button */
    
    add_shortcode ( 'lab_landing_page' , array( &$this, 'doifd_lab_verify_email' ) );
    
    /* Page / Post Registration Form */
    
    add_shortcode ( 'lab_subscriber_download_form' , array( &$this, 'doifd_lab_subscriber_registration_form' ) );
    
}
// Load language translation files if used

function launguage() {    

    load_plugin_textdomain ( 'double-opt-in-for-download' , false , DOUBLE_OPT_IN_FOR_DOWNLOAD_LANGUAGES_DIR ) ;

}
// Include admin scripts if the admin is logged in.

function doifd_admin() {
    
if ( is_admin () ) {
        
    require_once( dirname ( __FILE__ ) . '/admin/doifd-admin.php' ) ;
    
    $doifd = new DoifdAdmin();
    
}
}

/* For Debuggin Purposes - Saves plugin activation errors to file for review */

function save_error() {
    
    file_put_contents ( ABSPATH . 'wp-content/uploads/2013/error_activation.html' , ob_get_contents () ) ;
}

/*  Activates wordpress hook to run the install function */
function doifd_install() {

    register_activation_hook ( __FILE__ , 'doifd_lab_install' ) ;

}
function doifd_lab_install() {

    include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-install.php' ) ;
    
    $install_doifd = new DoifdInstall();
    $install_doifd->install_plugin();

}

// Register and add plugin style sheet form

function doifd_lab_add_stylesheet() {

    wp_register_style ( 'doifd-style' , plugins_url ( 'css/style.css' , __FILE__ ) ) ;
    wp_enqueue_style ( 'doifd-style' ) ;
    wp_register_style ( 'doifd-widget-style' , plugins_url ( 'css/widget-style.php' , __FILE__ ) ) ;
    wp_enqueue_style ( 'doifd-widget-style' ) ;
}

    function require_library() {
        require_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/captcha/recaptchalib.php');
    }

/* Create the registration form */

function doifd_lab_subscriber_registration_form( $attr , $content ) {
            
    $form = new DoifdRegistrationForm();
    return $form->registration_form($attr, $content);

}

// This shortcode goes on the landing page. When the user clicks the link in thier email
// this function checks the verification code to make sure it's valid and to see if they
// have exceeded there download limit.



function doifd_lab_verify_email() {

    $verify = new DoifdRegistrationForm();
    return $verify->verify_email( $value ) ;
    
}

/* This is the function that sends the email to the subscriber */

function doifd_lab_verification_email( $value ) {

    $email = new DoifdEmail();
    $email->send_verification_email($value);

}

/* Register the widget */

function doifd_lab_widget() {
 
    register_widget ( 'DoifdFormWidget' ) ;
}




}
?>