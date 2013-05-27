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


// Define URL & DIR path to plugin folder and DIR path to download folder

$uploads = wp_upload_dir () ;

define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_URL' , plugin_dir_url ( __FILE__ ) ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR' , plugin_dir_path ( __FILE__ ) ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR' , $uploads['basedir'] . '/doifd_downloads/' ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_URL' , $uploads['baseurl'] . '/doifd_downloads/' ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_LANGUAGES_DIR' , plugin_dir_path ( __FILE__ ) . 'languages/' ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_URL' , plugin_dir_url ( __FILE__ ) . 'captcha/' ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_DIR' , plugin_dir_path ( __FILE__ ) . 'captcha/' ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_IMG_URL' , plugin_dir_url ( __FILE__ ) . 'img/' ) ;

// Load language translation files if used

load_plugin_textdomain ( 'double-opt-in-for-download' , false , DOUBLE_OPT_IN_FOR_DOWNLOAD_LANGUAGES_DIR ) ;

// Load widget file

require 'includes/doifd-widget.php' ;

// Include admin script if the admin is logged in.

if ( is_admin () ) {
    require_once( dirname ( __FILE__ ) . '/admin/doifd-admin.php' ) ;
}

// For Debuggin Purposes - Saves plugin activation errors to file for review

add_action ( 'activated_plugin' , 'save_error' ) ;

function save_error() {
    file_put_contents ( ABSPATH . 'wp-content/uploads/2013/error_activation.html' , ob_get_contents () ) ;
}

// Activates wordpress hook to run the install function

register_activation_hook ( __FILE__ , 'doifd_lab_install' ) ;

function doifd_lab_install() {

// Install function that creates the tables needed by the plugin and adds our options to wordpres

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ) ;

    global $wpdb ;

//Declares version number of Plugin for future upgrades

    $doifd_lab_version = '0.9' ;

//If wordpress version is 3.0 or below, deactivate plugin

    if ( version_compare ( get_bloginfo ( 'version' ) , '3.0' , '<' ) ) {
        deactivate_plugins ( basename ( __FILE__ ) ) ;
    }
//Get Version number from wordpress database.

    $doifd_lab_installed_ver = get_option ( 'doifd_lab_version' ) ;

//If version number is different create/update plugin tables

    if ( $doifd_lab_installed_ver != $doifd_lab_version ) {

        //Assign subscribers table name to variable
        $doifd_lab_table_name1 = $wpdb->prefix . 'doifd_lab_subscribers' ;

        //Assign downloads table name to variable
        $doifd_lab_table_name2 = $wpdb->prefix . 'doifd_lab_downloads' ;

// Create Subscribers Table

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
                          );" ;
        dbDelta ( $doifd_lab_sql1 ) ;

// Create Downloads Table

        $doifd_lab_sql2 = "CREATE TABLE $doifd_lab_table_name2 (
                        doifd_download_id INT(11) NOT NULL AUTO_INCREMENT ,
                        doifd_download_name VARCHAR(75) NOT NULL ,
                        doifd_download_file_name VARCHAR(75) NOT NULL ,
                        doifd_number_of_downloads TINYINT(1) DEFAULT '0' NOT NULL ,
                        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL ,
                        UNIQUE KEY id (doifd_download_id)
                        );" ;
        dbDelta ( $doifd_lab_sql2 ) ;
    }

// Add version to Wordpress options table

    add_option ( 'doifd_lab_version' , $doifd_lab_version ) ;

// Add plugin default options to wordpress options table

    if ( ! get_option ( 'doifd_lab_options' ) ) {
        $doifd_default_options = array (
            'downloads_allowed'=>'1' ,
            'landing_page'=>'' ,
            'email_name'=>'' ,
            'from_email'=>'' ,
            'add_to_wpusers'=>'0' ,
            'promo_link'=>'0' ,
            'email_message'=>'Dear {subscriber},

Thank you for your interest in our free download {download}.

Below you will find the link to your download file. We hope you will enjoy it.

{url}

Thank You'
                ) ;

        add_option ( 'doifd_lab_options' , $doifd_default_options ) ;
    }

// Create download directory if it does not exist

    if ( ! is_dir ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR ) ) {
        mkdir ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR ) ;
    }

// Create .htacess file to block access to download folders if it does not exist
    //is there already an .htaccess file in the download directory?
    if ( ! is_file ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . '/.htaccess' ) ) {

        // create the .htaccess file in the download directory.
        $create_name = DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . '/.htaccess' ;

        // open the .htaccess file for editing
        $file_handle = fopen ( $create_name , 'w' ) or die ( "Error: Can't open file" ) ;

        // add the contents of the .htaccess file
        $content_string = "deny from all" ;

        // write the file to disk
        fwrite ( $file_handle , $content_string ) ;

        // close the file
        fclose ( $file_handle ) ;
    }
}

// Register and add plugin style sheet form

add_action ( 'wp_enqueue_scripts' , 'doifd_lab_add_stylesheet' ) ;

function doifd_lab_add_stylesheet() {

    wp_register_style ( 'doifd-style' , plugins_url ( 'css/style.css' , __FILE__ ) ) ;
    wp_enqueue_style ( 'doifd-style' ) ;
    wp_register_style ( 'doifd-widget-style' , plugins_url ( 'css/widget-style.php' , __FILE__ ) ) ;
    wp_enqueue_style ( 'doifd-widget-style' ) ;
}

// Start a session for captcha

add_action('init', 'myStartSession', 1);
function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

//Add the shortcode for the registration form

add_shortcode ( 'lab_subscriber_download_form' , 'doifd_lab_subscriber_registration_form' ) ;

function doifd_lab_subscriber_registration_form( $attr , $content ) {
            
    global $wpdb ;
    
//    require_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_DIR . 'recaptchalib.php');
    
    //Get the download id from the short code, if not send an error to the potential subscriber.
    if ( isset ( $attr['download_id'] ) ) {
        $download_id = $attr['download_id'] ;
    }
    else {
        return '<div id="doifd_user_reg_form">Oooops! There is no download id specified</div>' ;
    }

    if ( isset ( $attr['text'] ) ) {
        $doifd_form_text = $attr['text'] ;
    }
    else {
        $doifd_form_text = $header_text = __ ( 'Please provide your name and email address for your free download.' , 'Double-Opt-In-For-Download' ) ;
    }

    // assign button text. if admin did not assign a specific text then use the default
    
    if ( isset ( $attr['button_text'] ) ) {
        $doifd_form_button_text = $attr['button_text'] ;
    }
    else {
        $doifd_form_button_text = $button_text = __ ( 'Get Your Free Download' , 'Double-Opt-In-For-Download' ) ;
    }

    // used to create the _wpnounce in the form
    $doifd_lab_user_form_nonce = wp_create_nonce ( 'doifd-subscriber-registration-nonce' ) ;

    // get subscribers name and assign to variable
    $subscriber_name = __ ( 'Name' , 'Double-Opt-In-For-Download' ) ;

    // get subscribers email and assign to variable
    $subscriber_email = __ ( 'Email Address' , 'Double-Opt-In-For-Download' ) ;
    
//Set promotional link if option is on
    
    // get options from options table and assign to variable
        $options = get_option ( 'doifd_lab_options' ) ;

    // see if the admin wants to add the subscriber to the wp user table
        if ( isset( $options[ 'promo_link' ] ) ) {
        $option = $options[ 'promo_link' ] ;
        }
          
            if ( ( isset( $option ) ) && ($option == '1') ) {
                $doifd_promo_link = '<p class="doifd_promo_link"><a href="http://www.labwebdesigns.com" target="new">Powered by Lab Web Designs & Hosting</a></p>';
                
                } else {
                    $doifd_promo_link = '';
                }

// If the subscriber is submitting the form lets do this....

    if ( isset ( $_POST['doifd-subscriber-registration'] ) ) {
    
                        // reCapcha
        require_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_DIR . 'recaptchalib.php');
        $privatekey = "6Ldo7eESAAAAAA_en-CwymylgXIVq7jgzEeJRXiz";
        $doifd_resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
 
        print_r($doifd_resp);

        // assign table name to specific variable
        $wpdb->doifd_subscribers = $wpdb->prefix . 'doifd_lab_subscribers' ;

        //get the nonce from the registration form
        $doifd_lab_nonce = $_POST['_wpnonce'] ;

        //check to make sure data is coming from our form, if not, lets just die right here.
        if ( ! wp_verify_nonce ( $doifd_lab_nonce , 'doifd-subscriber-registration-nonce' ) )
            wp_die ( 'Security check' ) ;

        //sanitize name field and assign to varialbe.
        $doifd_lab_subscriber_name = sanitize_text_field ( $_POST['doifd_user_name'] ) ;

        //sanitize email field and assign to varialbe.
        $doifd_lab_subscriber_email = sanitize_email ( $_POST['doifd_user_email'] ) ;

        //sanitize download id field and assign to varialbe.
        $download_id = preg_replace ( "/[^0-9]/" , "" , $_POST['download_id'] ) ;

        //query the database to see if this is a duplicate email address. 
        $doifd_lab_check_duplicate_email = $wpdb->get_row ( $wpdb->prepare ( "SELECT * FROM $wpdb->doifd_subscribers WHERE doifd_email = %s AND doifd_download_id = %d" , $doifd_lab_subscriber_email , $download_id ) , ARRAY_A ) ;

        if (!$doifd_resp->is_valid) {
	$doifd_lab_msg = '<div class="doifd_error_msg">The Validation code does not match!</div>';
	}
        //if the subscriber name is empty after sanitation lets return an error message
        elseif ( empty ( $doifd_lab_subscriber_name ) ) {
            $text = __ ( 'Please provide your name.' , 'Double-Opt-In-For-Download' ) ;
            $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>' ;
        }
        //if email is not valid after sanitation lets return an error message
        elseif ( ! is_email ( $doifd_lab_subscriber_email ) ) {
            $text = __ ( 'Not a valid email address.' , 'Double-Opt-In-For-Download' ) ;
            $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>' ;
        }
        //if the email address is a duplicate lets return an error message
        elseif ( $doifd_lab_check_duplicate_email != null ) {
            $text = __ ( 'This email address has already been used.' , 'Double-Opt-In-For-Download' ) ;
            $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>' ;
        }

        // If and error message is returned let show the form again with the error message
        if ( isset ( $doifd_lab_msg ) ) {

//            require_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_DIR . 'recaptchalib.php');
            $publickey = "6Ldo7eESAAAAAAVnndDvTOZlQ_u08b-8abAUxrIb";


            return '<div id="doifd_user_reg_form">' . $doifd_lab_msg . '
            <script type="text/javascript">
            var RecaptchaOptions = {
            theme : "red"
            };
            </script>
            <h4>' . $doifd_form_text . '</h4> 
            <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="download_id" id="download_id" value="' . $download_id . '"/>
            <input type="hidden" name="_wpnonce" id="_wpnonce" value="' . $doifd_lab_user_form_nonce . '"/>
            <ul>
                <li><label for="name">' . $subscriber_name . '<span>*</span>: </label>
                    <input type="text" name="doifd_user_name" id="doifd_user_name" value=""/></li>

                <li><label for="name">' . $subscriber_email . '<span>*</span>: </label>
                    <input type="text" name="doifd_user_email" id="doifd_user_email" value=""/></li>
            </ul>
                <div id="doifd_captcha">' . recaptcha_get_html($publickey) . '</div>
                <div id="doifd_button_holder">
                <input name="doifd-subscriber-registration" type="submit" value=" ' . $doifd_form_button_text . ' "><br /></div>'
                . $doifd_promo_link .
            '</form>
                </div>' ;
        }
        else {

            // If no error message was created lets go ahead and add the subscriber to the database and send them
            // a verification email.
            // create verification number using sha1 and the current time and assign it to a variable 
            $doifd_lab_ver = sha1 ( time () ) ;

            // insert subscriber into the database
            if ( $wpdb->insert (
                            $wpdb->prefix . 'doifd_lab_subscribers' , array (
                        'doifd_name'=>$doifd_lab_subscriber_name ,
                        'doifd_email'=>$doifd_lab_subscriber_email ,
                        'doifd_verification_number'=>$doifd_lab_ver ,
                        'doifd_download_id'=>$download_id ,
                        'time'=>current_time ( 'mysql' , 0 )
                            ) , array (
                        '%s' ,
                        '%s' ,
                        '%s' ,
                        '%s'
                            )
                    ) == TRUE ) {

                /* ***********************************************************
                 * Add to wordpress users table if admin selected that option.
                 * ***********************************************************
                 */

                // get options from options table and assign to variable
                $options = get_option ( 'doifd_lab_options' ) ;

                // see if the admin wants to add the subscriber to the wp user table
                $add_to_user_option_table = $options['add_to_wpusers'] ;

                // if yes, lets add the user if not, we will just go on our merry way.
                if ( ( $add_to_user_option_table == '1' ) && ($doifd_lab_check_duplicate_email == NULL ) ) {

                    // generate a random password for the new user
                    $random_password = wp_generate_password ( $length = 12 , $include_standard_special_chars = false ) ;

                    // insert into wp user table and get user id for meta information
                    $user_id = wp_create_user ( $doifd_lab_subscriber_email , $random_password , $doifd_lab_subscriber_email ) ;

                    // just for fun lets explode the subscriber name. in case they entered their first and last name
                    $name = explode ( ' ' , $doifd_lab_subscriber_name ) ;

                    // add first name to user meta table
                    update_user_meta ( $user_id , 'first_name' , $name[0] ) ;

                    // if subcriber entered 2 names lets add the second as the last name
                    if ( ! empty ( $name[1] ) ) {
                        update_user_meta ( $user_id , 'last_name' , $name[1] ) ;
                    }
                }

                //lets package the subscriber information and download id into an array and send it to the send email function
                doifd_lab_verification_email ( $value = array (
                    "user_name"=>$doifd_lab_subscriber_name ,
                    "user_email"=>$doifd_lab_subscriber_email ,
                    "user_ver"=>$doifd_lab_ver ,
                    "download_id"=>$download_id ) ) ;

                // return thank you message to subscriber
                return '<div id="doifd_user_reg_form" class="thankyou"><h4>Thank You for Registering!</h4>Please check your email for your link to your Free download.<br />'
                . $doifd_promo_link .
            '</div>' ;
            }
            else {

                //If the insert was NOT successfull or TRUE lets show a database error.
                $text = __ ( 'Database Error' , 'Double-Opt-In-For-Download' ) ;
                return '<div class="doifd_error_msg">' . $text . '</div>' ;
            }
        }
    }

//    require_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_DIR . 'recaptchalib.php');
    $publickey = "6Ldo7eESAAAAAAVnndDvTOZlQ_u08b-8abAUxrIb";
    $privatekey = "6Ldo7eESAAAAAA_en-CwymylgXIVq7jgzEeJRXiz";

    return '<script type="text/javascript">
            var RecaptchaOptions = {
            theme : "red"
            };
            </script>
            <div id="doifd_user_reg_form">
            <h4>' . $doifd_form_text . '</h4> 
            <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="download_id" id="download_id" value="' . $download_id . '"/>
            <input type="hidden" name="_wpnonce" id="_wpnonce" value="' . $doifd_lab_user_form_nonce . '"/>
            <ul>
                <li><label for="name">' . $subscriber_name . '<span>*</span>: </label>
                    <input type="text" name="doifd_user_name" id="doifd_user_name" value=""/></li>

                <li><label for="name">' . $subscriber_email . '<span>*</span>: </label>
                    <input type="text" name="doifd_user_email" id="doifd_user_email" value=""/></li>
            </ul>
                <div id="doifd_captcha">' . recaptcha_get_html($publickey) . '</div>
                <div id="doifd_button_holder">
                <input name="doifd-subscriber-registration" type="submit" value=" ' . $doifd_form_button_text . ' "><br /></div>'
                . $doifd_promo_link .
            '</form>
                </div>' ;
}

// This shortcode goes on the landing page. When the user clicks the link in thier email
// this function checks the verification code to make sure it's valid and to see if they
// have exceeded there download limit.

add_shortcode ( 'lab_landing_page' , 'doifd_lab_verify_email' ) ;

function doifd_lab_verify_email() {

    global $wpdb ;

    // get options from options table and assign to variable
    $options = get_option ( 'doifd_lab_options' ) ;

    // get the downloads allowed option and assign to variable
    $allowed = $options['downloads_allowed'] ;

    // get the verification number and assign to a value
    if ( isset ( $_GET['ver'] ) ) {

        $ver = $_GET['ver'] ;

        // query the database to check the verification number and get the value for downloads_allowed and assign the results to a variable
        $checkver = $wpdb->get_row ( "SELECT doifd_email_verified, doifd_downloads_allowed FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " ) ;

        // if the email is already verified and they have already exceed the number of downloads allowed set session value to 3
        if ( ( $checkver->doifd_email_verified == '1' ) && ( $checkver->doifd_downloads_allowed >= $allowed ) ) {

            return '<div id="doifd_user_reg_form" class="exceeded">You have exceeded your number of<br />downloads for this item.</div>' ;

            // if the email is already verified and they have NOT exceed the number of downloads allowed set session value to 2
        }
        elseif ( ( $checkver->doifd_email_verified == '1' ) && ( $checkver->doifd_downloads_allowed <= $allowed ) ) {

            $query = $wpdb->get_row ( "SELECT doifd_download_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " , ARRAY_A ) ;
            $download_id_from_db = $query['doifd_download_id'] ;

            return '<div id="doifd_user_reg_form" class="thankyou"><form method="post" action="" enctype="multipart/form-data">
                                                             <input type="hidden" name="download_id" value="' . $download_id_from_db . '">
                                                             <input type="hidden" name="ver" value="' . $ver . '">
                                                             <input name="doifd_get_download" type="submit" value=" Click Here For Your Free Download ">
                                                             </form>
                                                             </div>' ;

            // if this is the subscribers first visit lets update the database to show the email is now verified and show them the download link
        }
        else {

            // get the subscriber ID
            $getsubid = $wpdb->get_row ( "SELECT doifd_subscriber_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " ) ;

            // update the table
            $wpdb->update (
                    $wpdb->prefix . 'doifd_lab_subscribers' , array (
                'doifd_email_verified'=>'1' , // string
                    ) , array ( 'doifd_subscriber_id'=>$getsubid->doifd_subscriber_id ) , array (
                '%d' // value2
                    ) , array ( '%d' )
            ) ;

            $query = $wpdb->get_row ( "SELECT doifd_download_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " , ARRAY_A ) ;
            $download_id_from_db = $query['doifd_download_id'] ;

            return '<div id="doifd_user_reg_form" class="thankyou"><form method="post" action="" enctype="multipart/form-data">
                                                             <input type="hidden" name="download_id" value="' . $download_id_from_db . '">
                                                             <input type="hidden" name="ver" value="' . $ver . '">
                                                             <input name="doifd_get_download" type="submit" value=" Click Here For Your Free Download ">
                                                             </form>
                                                             </div>' ;
        }
    }
}

// This is the function that sends the email to the subscriber

add_action ( 'init' , 'doifd_lab_verification_email' ) ;

function doifd_lab_verification_email( $value ) {

    global $wpdb ;

    // if $value is not empty proceed, other wise, let die
    if ( ! empty ( $value ) ) {

        // get the options from the options table
        $options = get_option ( 'doifd_lab_options' ) ;

        // if the admin set a different "from email" use that otherwise use the default admin email address.
        if ( ! empty ( $options['from_email'] ) ) {
            $msg_from_email = $options['from_email'] ;
        }
        else {
            $msg_from_email = get_bloginfo ( 'admin_email' ) ;
        }

        // if the admin set a different "Website Name" use that, otherwise use the default admin blog name.
        if ( ! empty ( $options['email_name'] ) ) {
            $msg_from_name = $options['email_name'] ;
        }
        else {
            $msg_from_name = get_bloginfo ( 'name' ) ;
        }

        // get the landing page number from the options table
        $landing_page = $options['landing_page'] ;

        // the $URL provides the link with the verification number attached to the the url for verification
        $url = add_query_arg ( 'ver' , $value['user_ver'] , get_permalink ( $landing_page ) ) ;

        // get the email address of subscriber and assign to variable 
        $doifd_lab_to = $value['user_email'] ;

        // get the user name of subscriber and assign to variable
        $doifd_user_name = $value['user_name'] ;

        // the subject line of the email
        $doifd_lab_subject = 'Your Free Download from ' . get_bloginfo ( 'name' ) ;

        // get the download_id and assign to variable
        $doifd_download_id = $value['download_id'] ;

        // query the database to get the name of download and assign to a variable.
        $doifd_get_download_name = $wpdb->get_row ( "SELECT doifd_download_name FROM " . $wpdb->prefix . "doifd_lab_downloads WHERE doifd_download_id = '$doifd_download_id' " , ARRAY_A ) ;
        $download_name = $doifd_get_download_name['doifd_download_name'] ;

        // get the email message from the options table
        $doifd_lab_message_template = $options['email_message'] ;

        // replace the {user_name}, {download} and {url} in the email message body with the actual name and URL.
        $doifd_lab_message = str_ireplace ( array ( '{subscriber}' , '{url}' , '{download}' ) , array ( $doifd_user_name , $url , $download_name ) , $doifd_lab_message_template ) ;

        // assign value to email header(s)
        $doifd_lab_headers[] = 'From:' . $msg_from_name . '   <' . $msg_from_email . '>' ;
        /*         * ************************************************************
         * Optional cc headers if you need to use them.
         * $doifd_lab_headers[] = 'Cc: John Q Codex <jqc@wordpress.org>'; 
         * $doifd_lab_headers[] = 'Cc: iluvwp@wordpress.org'; // note you can just use a simple email address 
         */

        //send the email using wp_mail
        wp_mail ( $doifd_lab_to , $doifd_lab_subject , $doifd_lab_message , $doifd_lab_headers ) ;

        return TRUE ;
    }
    else {
        return FALSE ;
    }
}

// This function retrives the download for the subscriber

add_action ( 'init' , 'doifd_lab_link_to_download' ) ;

function doifd_lab_link_to_download() {

    global $wpdb ;

    // if they have clicked the download button let proceed
    if ( isset ( $_POST['doifd_get_download'] ) ) {

        // assigns the sql table names to a varaible
        $wpdb->doifd_subscribers = $wpdb->prefix . 'doifd_lab_subscribers' ;
        $wpdb->doifd_downloads = $wpdb->prefix . 'doifd_lab_downloads' ;

        // sanitize the download id, just in case
        $doifd_download_id = preg_replace ( '/[^0-9]/' , '' , $_POST['download_id'] ) ;

        // get the verification number so we can look up what there download is.
        $ver = $_POST['ver'] ;

        // if both are valid, lets continue
        if ( isset ( $doifd_download_id ) && ( isset ( $ver ) ) ) {

            // get options from options table
            $options = get_option ( 'doifd_lab_options' ) ;

            // get the maximum downloads possible option to variable
            $allowed = $options['downloads_allowed'] ;

            //Set a default of 1 in case the admin did not select this option
            if ( empty ( $allowed ) ) {
                $allowed = '1' ;
            }
            //Check to see how many times this subscriber has downloaded the file
            $checkallowed = $wpdb->get_row ( "SELECT doifd_downloads_allowed FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " ) ;

            //If subscriber has exceeded maximum, show them the exceeded download message.
            if ( $checkallowed->doifd_downloads_allowed >= $allowed ) {

                return '<div id="doifd_user_reg_form" class="exceeded">You have exceed your number of downloads for this item.</div>' ;
            }

            // query database and assign results to varialbe
            $get_file_name = $wpdb->get_row ( "SELECT doifd_download_file_name FROM " . $wpdb->prefix . "doifd_lab_downloads  WHERE doifd_download_id = '$doifd_download_id' " , ARRAY_A ) ;

            // assign file name to variable.
            $file_name = $get_file_name['doifd_download_file_name'] ;

            // get the file extension
            $extension = pathinfo ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . $file_name , PATHINFO_EXTENSION ) ;

            // give the file a fake name to help hide the actual file
            $fakeFileName = 'Your-Download.' . $extension ;

            // assign the real file name to a variable
            $realFileName = $file_name ;

            // assign real file name and path to variable
            $file = DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . '/' . $realFileName ;

            // open the file
            $fp = fopen ( $file , 'rb' ) ;

            // assign the appropriate Content-Type header for the file and send file to subscribers browser
            if ( $extension == 'jpg' ) {
                header ( 'Content-Type: image/jpg' ) ;
            }
            elseif ( $extension == 'jpeg' ) {
                header ( 'Content-Type: image/jpeg' ) ;
            }
            elseif ( $extension == 'png' ) {
                header ( 'Content-Type: image/png' ) ;
            }
            elseif ( $extension == 'bmp' ) {
                header ( 'Content-Type: image/bmp' ) ;
            }
            elseif ( $extension == 'zip' ) {
                header ( "Content-type: application/zip" ) ;
            }
            elseif ( $extension == 'gif' ) {
                header ( 'Content-Type: image/gif' ) ;
            }
            elseif ( $extension == 'doc' ) {
                header ( "Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document" ) ;
            }
            elseif ( $extension == 'docx' ) {
                header ( "Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document" ) ;
            }
            elseif ( $extension == 'pdf' ) {
                header ( "Content-type: application/octet-stream" ) ;
            }
            header ( 'Content-Transfer-Encoding: binary' ) ;
            header ( "Content-Disposition: attachment; filename=$fakeFileName" ) ;
            header ( "Content-Length:" . filesize ( $file ) ) ;
            fpassthru ( $fp ) ;

            // if the conection / download status was successful update subscriber status to show successful download
            // and download table to show total downloads
            if ( connection_status () == CONNECTION_NORMAL ) {

                // update download table
                $wpdb->query (
                        "
                        UPDATE $wpdb->doifd_downloads
                        SET doifd_number_of_downloads = doifd_number_of_downloads+1 WHERE doifd_download_id = '$doifd_download_id'
                    "
                ) ;

                // update subscribers downloads_allowed
                $wpdb->query (
                        "
                        UPDATE $wpdb->doifd_subscribers
                        SET doifd_downloads_allowed = doifd_downloads_allowed+1 WHERE doifd_verification_number = '$ver'
                    "
                ) ;
            }
        }
    }
}

// Register the widget

add_action ( 'widgets_init' , 'doifd_lab_widget' ) ;

function doifd_lab_widget() {
    register_widget ( 'doifd_lab_widget_signup' ) ;
}

?>