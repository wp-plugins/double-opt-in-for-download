<?php

if ( ! class_exists ( 'DOIFDEmail' ) ) {

    class DOIFDEmail extends DOIFD {

        public function __construct() {
            parent::__construct();
            
        add_action ( 'admin_init', array( $this, 'doifd_admin_resend_verification_email' ) );
            
            
        }

        public function doifd_admin_resend_verification_email() {

            /* Check if it's coming from the resend verification email button and the user has privileges */

            if ( isset ( $_REQUEST[ 'name' ] ) && ( $_REQUEST[ 'name' ] == 'doifd_lab_resend_verification_email' ) && ( current_user_can ( 'manage_options' ) ) ) {

                /* sanitize variables from form and assign to variables */

                $a = sanitize_text_field ( $_REQUEST[ 'user_name' ] );

                $b = sanitize_email ( $_REQUEST[ 'user_email' ] );

                $c = preg_replace ( '/[^ \w]+/', '', $_REQUEST[ 'user_ver' ] );

                $d = preg_replace ( "/[^0-9]/", "", $_REQUEST[ 'download_id' ] );

                /* Package clean variable into an array and send them to the send email function */

                if ( has_filter ( 'doifd_alt_verification_email' ) ) {

                    $send = apply_filters ( 'doifd_alt_verification_email', $value = array(
                        "user_name" => $a,
                        "user_email" => $b,
                        "user_ver" => $c,
                        "download_id" => $d ) );
                } else {

                    $send = $this->send_verification_email ( $value = array(
                                "user_name" => $a,
                                "user_email" => $b,
                                "user_ver" => $c,
                                "download_id" => $d ) );
                }

                if ( $send === TRUE ) {

                    echo '<div class="updated"><p><strong>' . __ ( 'Email Sent', $this->plugin_slug ) . '</strong></p></div>';
                } else {

                    echo '<div class="error"><p><strong>' . __ ( 'The Email was NOT Sent', $this->plugin_slug ) . '</a></strong></p></div>';
                }
                
            }
            
        }

        public function send_verification_email( $value ) {

            global $wpdb;

            $wpdb->doifd_subscribers = $wpdb->prefix . 'doifd_lab_subscribers';
            $wpdb->doifd_downloads = $wpdb->prefix . 'doifd_lab_downloads';

            /* If $value is not empty proceed, other wise, let die */

            if ( ! empty ( $value ) ) {


                /* If the admin set a different "from email" use that otherwise use the default admin email address. */

                if ( ! empty ( $this->doifd_options[ 'from_email' ] ) ) {

                    $msg_from_email = $this->doifd_options[ 'from_email' ];
                } else {

                    $msg_from_email = get_bloginfo ( 'admin_email' );
                }

                /* If the admin set a different "Website Name" use that, otherwise use the default admin blog name. */

                if ( ! empty ( $this->doifd_options[ 'email_name' ] ) ) {

                    $msg_from_name = $this->doifd_options[ 'email_name' ];
                } else {

                    $msg_from_name = get_bloginfo ( 'name' );
                }

                /* Send HTML Email */

                if ( isset ( $this->doifd_options[ 'send_html' ] ) ) {

                    $html = $this->doifd_options[ 'send_html' ];
                } else {

                    $html = FALSE;
                }

                /* Get the email address of subscriber and assign to variable */

                $doifd_lab_to = $value[ 'user_email' ];

                /* Get the user name of subscriber and assign to variable */

                $doifd_user_name = $value[ 'user_name' ];

                /* Get the download_id and assign to variable */

                $doifd_download_id = $value[ 'download_id' ];

                /* Query the database to get the name of download and the landing page and assign to a variables. */

                $sql = $wpdb->get_row ( $wpdb->prepare ( "SELECT doifd_download_name, doifd_download_landing_page FROM $wpdb->doifd_downloads WHERE doifd_download_id = %s", $doifd_download_id ), ARRAY_A );

                /* This is for the conversion from moving the landing page from general options to each individual upload. Can remove/simplify on 01/2015 */

                if ( $sql[ 'doifd_download_landing_page' ] != '0' ) {

                    $landing_page = $sql[ 'doifd_download_landing_page' ];
                } else {

                    $landing_page = $this->doifd_options[ 'landing_page' ];
                }

                $download_name = $sql[ 'doifd_download_name' ];

                /* The $URL provides the link with the verification number attached to the the url for verification */

                $url = add_query_arg ( 'ver', $value[ 'user_ver' ], get_permalink ( $landing_page ) );

                /* The subject line of the email */

                if ( isset ( $this->doifd_options[ 'email_subject' ] ) ) {

                    $subject = $this->doifd_options[ 'email_subject' ];
                } else {

                    $subject = '';
                }

                if ( ! empty ( $subject ) ) {

                    $updated_subject = str_ireplace ( array( '{download}', '{site_name}' ), array( $download_name, get_bloginfo ( 'name' ) ), $subject );

                    $doifd_lab_subject = $updated_subject;
                } else {

                    $doifd_lab_subject = sprintf ( __ ( 'Your Free Download from %s', $this->plugin_slug ), get_bloginfo ( 'name' ) );
                }

                /* Get the email message from the options table */

                $doifd_lab_message_template = $this->doifd_options[ 'email_message' ];

                /* Replace the {user_name}, {download} and {url} in the email message body with the actual name and URL. */

                $doifd_lab_message = str_ireplace ( array( '{subscriber}', '{url}', '{download}' ), array( $doifd_user_name, $url, $download_name ), $doifd_lab_message_template );

                /* Assign value to email header(s) */

                $doifd_lab_headers[ ] = 'From:' . $msg_from_name . '   <' . $msg_from_email . '>';

                /*                 * *****************************************************
                 * Optional cc headers if you need to use them.
                 * $doifd_lab_headers[] = 'Cc: John Q Codex <jqc@wordpress.org>'; 
                 * $doifd_lab_headers[] = 'Cc: iluvwp@wordpress.org'; // note you can just use a simple email address 
                 * **************************************************************
                 */
                if ( $html == TRUE ) {
                    add_filter ( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
                }
                /* Send the email using wp_mail */

                wp_mail ( $doifd_lab_to, $doifd_lab_subject, $doifd_lab_message, $doifd_lab_headers );

                if ( $html == TRUE ) {
                    remove_filter ( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
                }

                return TRUE;
            } else {

                return FALSE;
            }
        }

        public function admin_notification( $value ) {
            
            /*
             * Values
             * 
             * [0] = Subscriber Name
             * [1] = Download Name
             * [2] = Email Address
             * [3] = Download ID
             * [4] = Misc1
             * [5] = Misc2
             * [6] = Misc3
             * 
             */

            global $wpdb;
            
            if (isset($value[4])) { $misc1 = $value[4]; } else { $misc1 = ''; };
            if (isset($value[5])) { $misc2 = $value[5]; } else { $misc2 = ''; };
            if (isset($value[6])) { $misc3 = $value[6]; } else { $misc3 = ''; };

            if ( function_exists ( 'is_multisite' ) && is_multisite () ) {
            //checks if it's a multisite
                $doifd_lab_to = get_blog_option ( $current_blog->blog_id, 'admin_email' );

                //in multisite, it returns the network-wide site E-mail
            } else {

                $doifd_lab_to = get_bloginfo ( 'admin_email' );
            }

            if ( ! empty ( $this->doifd_options[ 'optional_form_field_1' ] ) ) {
                $field1 = $this->doifd_options[ 'optional_form_field_1' ] . ': ';
            } else {
                $field1 = '';
            }
            if ( ! empty ( $this->doifd_options[ 'optional_form_field_2' ] ) ) {
                $field2 = $this->doifd_options[ 'optional_form_field_2' ] . ': ';
            } else {
                $field2 = '';
            }
            if ( ! empty ( $this->doifd_options[ 'optional_form_field_3' ] ) ) {
                $field3 = $this->doifd_options[ 'optional_form_field_3' ] . ': ';
            } else {
                $field3 = '';
            }

            $subject = apply_filters ( 'doifd_admin_email_subject', '[New Download] @ ' . get_bloginfo ( 'name' ), $value[3] );

            $doifd_lab_headers[ ] = 'From:' . get_bloginfo ( 'name' ) . '   <' . $doifd_lab_to . '>';

            $doifd_lab_message = 'Congratulations!

' . $value[0] . ' just verified his/her email address ( ' . $value[2] . ' ) for ' . $value[1] . '
    
Information Provided:

Name:' . $value[0] . '
Email:' . $value[2] . '
' . $field1 . $misc1 . '
' . $field2 . $misc2 . '
' . $field3 . $misc3 . '
                    
                                          
Double Opt In For Download';


            wp_mail ( $doifd_lab_to, $subject, $doifd_lab_message, $doifd_lab_headers );
        }

        public function set_html_content_type() {

            $html = "text/html";
            return $html;
        }

    }

}
new DOIFDEmail();