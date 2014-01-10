<?php

if ( !class_exists ( 'DoifdEmail' ) ) {

    class DoifdEmail {

        private $options = array();
        
        function __construct() {
            
            $this->options = get_option ( 'doifd_lab_options' );
            
        }

        public function admin_resend_verification_email() {

            /* Check if it's coming from the resend verification email button and the user has privileges */

            if ( isset ( $_REQUEST[ 'name' ] ) && ( $_REQUEST[ 'name' ] == 'doifd_lab_resend_verification_email' ) && ( current_user_can ( 'manage_options' ) ) ) {

                /* sanitize variables from form and assign to variables */

                $a = sanitize_text_field ( $_REQUEST[ 'user_name' ] );

                $b = sanitize_email ( $_REQUEST[ 'user_email' ] );

                $c = preg_replace ( '/[^ \w]+/', '', $_REQUEST[ 'user_ver' ] );

                $d = preg_replace ( "/[^0-9]/", "", $_REQUEST[ 'download_id' ] );

                /* Package clean variable into an array and send them to the send email function */

                $send = DoifdEmail::send_verification_email ( $value = array(
                            "user_name" => $a,
                            "user_email" => $b,
                            "user_ver" => $c,
                            "download_id" => $d ) );

                if ( $send === TRUE ) {

                    echo '<div class="updated"><p><strong>' . __( 'Email Sent To Subscriber', 'double-opt-in-for-download' ) . ' <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . __( 'Return Back' , 'double-opt-in-for-download' ) . '</a></strong></p></div>';
                } else {

                    echo '<div class="error"><p><strong>' .__( 'A Problem Prevented the Email From Being Sent', 'double-opt-in-for-download' ) . ' <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . __( 'Return Back' , 'double-opt-in-for-download' ) . '</a></strong></p></div>';
                }
            }

        }

        public function send_verification_email( $value ) {

            global $wpdb;
            
            $wpdb->doifd_subscribers = $wpdb->prefix . 'doifd_lab_subscribers';
            $wpdb->doifd_downloads = $wpdb->prefix . 'doifd_lab_downloads';

            /* If $value is not empty proceed, other wise, let die */

            if ( !empty ( $value ) ) {

                /* If the admin set a different "from email" use that otherwise use the default admin email address. */

                if ( !empty ( $this->options[ 'from_email' ] ) ) {

                    $msg_from_email = $this->options[ 'from_email' ];
                } else {

                    $msg_from_email = get_bloginfo ( 'admin_email' );
                }

                /* If the admin set a different "Website Name" use that, otherwise use the default admin blog name. */

                if ( !empty ( $this->options[ 'email_name' ] ) ) {

                    $msg_from_name = $this->options[ 'email_name' ];
                } else {

                    $msg_from_name = get_bloginfo ( 'name' );
                }

                

                /* Get the email address of subscriber and assign to variable */

                $doifd_lab_to = $value[ 'user_email' ];

                /* Get the user name of subscriber and assign to variable */

                $doifd_user_name = $value[ 'user_name' ];

                /* The subject line of the email */

                $doifd_lab_subject = sprintf(__('Your Free Download from %s', 'double-opt-in-for-download'), get_bloginfo ( 'name' ));

                /* Get the download_id and assign to variable */

                $doifd_download_id = $value[ 'download_id' ];

                /* Query the database to get the name of download and the landing page and assign to a variables. */

                $sql = $wpdb->get_row ( $wpdb->prepare ( "SELECT doifd_download_name, doifd_download_landing_page FROM $wpdb->doifd_downloads WHERE doifd_download_id = %s", $doifd_download_id ), ARRAY_A );

                /* This is for the conversion from moving the landing page from general options to each individual upload. Can remove/simplify on 01/2015 */
                
                if ($sql['doifd_download_landing_page'] != '0') {
                    
                    $landing_page = $sql['doifd_download_landing_page'];
                    
                } else {
                    
                    $landing_page = $this->options['landing_page'];
                }

                $download_name = $sql[ 'doifd_download_name' ];
                
                /* The $URL provides the link with the verification number attached to the the url for verification */

                $url = add_query_arg ( 'ver', $value[ 'user_ver' ], get_permalink ( $landing_page ) );

                /* Get the email message from the options table */

                $doifd_lab_message_template = $this->options[ 'email_message' ];

                /* Replace the {user_name}, {download} and {url} in the email message body with the actual name and URL. */

                $doifd_lab_message = str_ireplace ( array( '{subscriber}', '{url}', '{download}' ), array( $doifd_user_name, $url, $download_name ), $doifd_lab_message_template );

                /* Assign value to email header(s) */

                $doifd_lab_headers[ ] = 'From:' . $msg_from_name . '   <' . $msg_from_email . '>';

                /* **********************************************************
                 * Optional cc headers if you need to use them.
                 * $doifd_lab_headers[] = 'Cc: John Q Codex <jqc@wordpress.org>'; 
                 * $doifd_lab_headers[] = 'Cc: iluvwp@wordpress.org'; // note you can just use a simple email address 
                 * **************************************************************
                 */

                /* Send the email using wp_mail */

                wp_mail ( $doifd_lab_to, $doifd_lab_subject, $doifd_lab_message, $doifd_lab_headers );

                return TRUE;
            } else {

                return FALSE;
            }

        }
        
                public function admin_notification( $subscriber, $download, $email ) {

            global $wpdb;

            if ( !empty( $this->options[ 'from_email' ] ) ) {

                $doifd_lab_to = $this->options[ 'from_email' ];
            } else {

                $doifd_lab_to = get_bloginfo( 'admin_email' );
            }

            $doifd_lab_subject = '[New Download] @ ' . get_bloginfo( 'name' );

            $doifd_lab_headers[ ] = 'From:' . get_bloginfo( 'name' ) . '   <' . $doifd_lab_to . '>';

            $doifd_lab_message = 'Congratulations!

'. $subscriber . ' just verified his/her email address ( '. $email . ' ) to download ' . $download . '
                                          
Double Opt In For Download';


            wp_mail( $doifd_lab_to, $doifd_lab_subject, $doifd_lab_message, $doifd_lab_headers );
        }

    }

}

?>
