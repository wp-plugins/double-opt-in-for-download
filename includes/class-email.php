<?php

class DoifdEmail {
    
    function __construct() {
        
    }

    public static function admin_resend_verification_email() {
        
        // check if it's coming from the resend verification email button and the user has privileges
            if ( isset ( $_REQUEST['name'] ) && ( $_REQUEST['name'] == 'doifd_lab_resend_verification_email' ) && ( current_user_can ( 'manage_options' ) ) ) {

                // sanitize variables from form and assign to variables
                $a = sanitize_text_field ( $_REQUEST['user_name'] ) ;
                $b = sanitize_email ( $_REQUEST['user_email'] ) ;
                $c = preg_replace ( '/[^ \w]+/' , '' , $_REQUEST['user_ver'] ) ;
                $d = preg_replace ( "/[^0-9]/" , "" , $_REQUEST['download_id'] ) ;

                // package clean variable into an array and send them to the send email function
                $send = DoifdEmail::send_verification_email( $value = array (
                    "user_name"=>$a ,
                    "user_email"=>$b ,
                    "user_ver"=>$c ,
                    "download_id"=>$d ) ) ;

                if ( $send === TRUE ) {
                    echo '<div class="updated"><p><strong>Email Sent To Subscriber <a href="' . str_replace ( '%7E' , '~' , $_SERVER['REQUEST_URI'] ) . '">Return Back</a></strong></p></div>' ;
                }
                else {
                    echo '<div class="error"><p><strong>A Problem Prevented the Email From Being Sent<a href="' . str_replace ( '%7E' , '~' , $_SERVER['REQUEST_URI'] ) . '">Return Back</a></strong></p></div>' ;
                }
            }
    }
    
    public static function send_verification_email( $value ) {
        
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
}

?>
