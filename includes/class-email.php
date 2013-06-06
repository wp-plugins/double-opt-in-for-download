<?php

class DoifdEmail {

    public static function admin_resend_verification_email() {
        
        // check if it's coming from the resend verification email button and the user has privileges
            if ( isset ( $_REQUEST['name'] ) && ( $_REQUEST['name'] == 'doifd_lab_resend_verification_email' ) && ( current_user_can ( 'manage_options' ) ) ) {

                // sanitize variables from form and assign to variables
                $a = sanitize_text_field ( $_REQUEST['user_name'] ) ;
                $b = sanitize_email ( $_REQUEST['user_email'] ) ;
                $c = preg_replace ( '/[^ \w]+/' , '' , $_REQUEST['user_ver'] ) ;
                $d = preg_replace ( "/[^0-9]/" , "" , $_REQUEST['download_id'] ) ;

                // package clean variable into an array and send them to the send email function
                $send = doifd_lab_verification_email ( $value = array (
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
}

?>
