<?php

if ( !class_exists( 'DoifdRegistrationForm' ) ) {

    class DoifdRegistrationForm {

        private $options = array ( );

        function __construct() {

            $this->options = get_option( 'doifd_lab_options' );
        }

        public function registration_form( $attr, $content ) {

            global $wpdb;
            global $download_id;

            $wpdb->doifd_subscribers = $wpdb->prefix . 'doifd_lab_subscribers';
            $wpdb->doifd_downloads = $wpdb->prefix . 'doifd_lab_downloads';

            /* Get the download id from the short code, if not send an error to the potential subscriber. */

            if ( !empty( $attr[ 'download_id' ] ) ) {

                $download_id = $attr[ 'download_id' ];
            }
            else {

                return '<div class="doifd_user_reg_form exceeded">' . __( 'There is no download ID specified', 'double-opt-in-for-download' ) . '</div>';
            }

            /* Does the download really exist? Well See if the ID is in the database and if it is we will see if the actual file is really there */

            $isitreal = $wpdb->get_row( $wpdb->prepare( "SELECT doifd_download_name, doifd_download_file_name FROM $wpdb->doifd_downloads WHERE doifd_download_id = %s", $download_id ), ARRAY_A );

            $isthefilethere = DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . '/' . $isitreal[ 'doifd_download_file_name' ];

            if ( $isitreal[ 'doifd_download_name' ] == NULL ) {

                return '<div class="doifd_user_reg_form exceeded">' . __( 'The download ID does not exist', 'double-opt-in-for-download' ) . '</div>';
            }

            /* If we find there is no file on the server, show the user a generic error message with code */

            if ( !file_exists( $isthefilethere ) ) {

                return '<div class="doifd_user_reg_form exceeded">' . __( 'The download files does not exist', 'double-opt-in-for-download' ) . '<br />Please notify the website administrator.</div>';
            }

            /* Get the title text if the admin wants to use something different, otherwise the default is shown. */

            if ( isset( $attr[ 'text' ] ) ) {

                $doifd_form_text = $attr[ 'text' ];
            }
            else {

                $doifd_form_text = $header_text = __( 'Please provide your name and email address for your free download.', 'double-opt-in-for-download' );
            }

            /* Assign button text. if admin did not assign a specific text then use the default */

            if ( isset( $attr[ 'button_text' ] ) ) {

                $doifd_form_button_text = $attr[ 'button_text' ];
            }
            else {

                $doifd_form_button_text = $button_text = __( 'Submit', 'double-opt-in-for-download' );
            }

            /* Create the nounce for the form */

            $doifd_lab_user_form_nonce = wp_create_nonce( 'doifd-subscriber-registration-nonce' );

            /* Get subscribers name and assign to variable for language translation */

            $subscriber_name = __( 'Name', 'double-opt-in-for-download' );

            /* Get subscribers email and assign to variable for language translation */

            $subscriber_email = __( 'Email Address', 'double-opt-in-for-download' );


            /* See if the admin wants to add our promo link */

            if ( ( isset( $this->options[ 'promo_link' ] ) ) && ($this->options[ 'promo_link' ] == '1') ) {

                $doifd_promo_link = '<p class="doifd_promo_link"><a href="http://www.labwebdesigns.com" target="new" Title="' . __( 'Powered by Lab Web Development', 'double-opt-in-for-download' ) . '">' . __( 'Powered by Lab Web Development', 'double-opt-in-for-download' ) . '</a></p>';
            }
            else {

                $doifd_promo_link = '';
            }

            /* See If Privacy Policy is Set */

            if ( ( isset( $this->options[ 'use_privacy_policy' ] ) ) && ($this->options[ 'use_privacy_policy' ] == '1') ) {

                $doifd_privacy_policy = '<div class="doifd_privacy_link"><a href="' . get_page_link( $this->options[ 'privacy_page' ] ) . '" target="new" >' . $this->options[ 'privacy_link_text' ] . '</a></div>';
            }
            else {

                $doifd_privacy_policy = '';
            }


            /* If the subscriber is submitting the form lets do this.... */


            if ( isset( $_POST[ 'doifd-subscriber-registration' ] ) ) {

                /* Assign table name to specific variable */

                $wpdb->doifd_subscribers = $wpdb->prefix . 'doifd_lab_subscribers';

                /* Get the nonce from the registration form */

                $doifd_lab_nonce = $_POST[ '_wpnonce' ];

                /*  See if the admin wants to use nonce, if so, check to make sure data is coming from our form, if not, lets just die right here. */

                if ( $this->options[ 'form_security' ] == '0' ) {

                    if ( !wp_verify_nonce( $doifd_lab_nonce, 'doifd-subscriber-registration-nonce' ) )
                        wp_die( 'Security check' );
                }

                /* Sanitize name field and assign to varialbe. */

                $doifd_lab_subscriber_name = sanitize_text_field( $_POST[ 'doifd_user_name' ] );

                /* Sanitize email field and assign to varialbe. */

                $doifd_lab_subscriber_email = sanitize_email( $_POST[ 'doifd_user_email' ] );

                /* Sanitize download id field and assign to varialbe. */

                $download_id = preg_replace( "/[^0-9]/", "", $_POST[ 'download_id' ] );

                /* Query the database to see if this is a duplicate email address. */

                $doifd_lab_check_duplicate_email = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->doifd_subscribers WHERE doifd_email = %s AND doifd_download_id = %d", $doifd_lab_subscriber_email, $download_id ), ARRAY_A );

                /* If the subscriber name is empty after sanitation lets return an error message */

                if ( empty( $doifd_lab_subscriber_name ) ) {

                    $text = __( 'Please provide your name.', 'double-opt-in-for-download' );

                    $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>';
                }

                /* If email is not valid after sanitation lets return an error message */
                elseif ( !is_email( $doifd_lab_subscriber_email ) ) {

                    $text = __( 'Not a valid email address.', 'double-opt-in-for-download' );

                    $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>';
                }

                /* If the email address is a duplicate lets return an error message */
                elseif ( $doifd_lab_check_duplicate_email != null ) {

                    $text = __( 'This email address has already been used.', 'double-opt-in-for-download' );

                    $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>';
                
                }
                
                if (!isset($doifd_lab_msg)) {
                    $error = '';
                } else {
                    $error = $doifd_lab_msg;
                }
                /* Let put all the values in an array for the form filter */
                
                $form_values = apply_filters( 'doifd_form_setup_values', array (
                    "form_text" => $doifd_form_text,
                    "id" => $download_id,
                    "error" => $error,
                    "nonce" => $doifd_lab_user_form_nonce,
                    "name" => $subscriber_name,
                    "email" => $subscriber_email,
                    "button_text" => $doifd_form_button_text,
                    "privacy" => $doifd_privacy_policy,
                    "promo" => $doifd_promo_link
                ) );

                /* If and error message is returned lets show the form again with the error message */

                if ( isset( $doifd_lab_msg ) ) {

                    ob_start();
                    $form = include_once ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/views/forms/view-default.php' );
                    $form_output = ob_get_contents();
                    ob_end_clean();
                    return apply_filters( 'doifd_form_output', $form_output, $form_values );
                }
                else {

                    /* If no error message was created lets go ahead and add the subscriber
                     * to the database and send them a verification email create verification
                     * number using sha1 and the current time and assign it to a variable.
                     */

                    $doifd_lab_ver = sha1( time() );

                    /* Insert subscriber into the database */

                    if ( $wpdb->insert(
                                    $wpdb->prefix . 'doifd_lab_subscribers', array (
                                'doifd_name' => $doifd_lab_subscriber_name,
                                'doifd_email' => $doifd_lab_subscriber_email,
                                'doifd_verification_number' => $doifd_lab_ver,
                                'doifd_download_id' => $download_id,
                                'time' => current_time( 'mysql', 0 )
                                    ), array (
                                '%s',
                                '%s',
                                '%s',
                                '%s'
                                    )
                            ) == TRUE ) {

                        /*                         * *****************************************************
                         * Add to wordpress users table if admin selected that option.
                         * ***********************************************************
                         */

                        /* If yes, lets add the user if not, we will just go on our merry way. */

                        if ( ( $this->options[ 'add_to_wpusers' ] == '1' ) && ($doifd_lab_check_duplicate_email == NULL ) ) {

                            /* Generate a random password for the new user */

                            $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );

                            /* Insert into wp user table and get user id for meta information */

                            $user_id = wp_create_user( $doifd_lab_subscriber_email, $random_password, $doifd_lab_subscriber_email );

                            /* Just for fun lets explode the subscriber name. in case they entered their first and last name */

                            $name = explode( ' ', $doifd_lab_subscriber_name );

                            /* Add first name to user meta table */

                            update_user_meta( $user_id, 'first_name', $name[ 0 ] );

                            /* If subcriber entered 2 names lets add the second as the last name */

                            if ( !empty( $name[ 1 ] ) ) {

                                update_user_meta( $user_id, 'last_name', $name[ 1 ] );
                            }
                        }

                        /* Lets package the subscriber information and download id into an array and send it to the send email function */

//                        $send_ver_email =  new DoifdEmail();
//                        $send = $send_ver_email->send_verification_email($value = array(
//                            "user_name" => $doifd_lab_subscriber_name,
//                            "user_email" => $doifd_lab_subscriber_email,
//                            "user_ver" => $doifd_lab_ver,
//                            "download_id" => $download_id));

                        if ( has_filter( 'doifd_alt_verification_email' ) ) {

                            apply_filters( 'doifd_alt_verification_email', $value = array (
                                "user_name" => $doifd_lab_subscriber_name,
                                "user_email" => $doifd_lab_subscriber_email,
                                "user_ver" => $doifd_lab_ver,
                                "download_id" => $download_id ) );
                        }
                        else {
                            
//                        $send_ver_email =  new DoifdEmail();
//                        $send = $send_ver_email->send_verification_email($value = array(
//                            "user_name" => $doifd_lab_subscriber_name,
//                            "user_email" => $doifd_lab_subscriber_email,
//                            "user_ver" => $doifd_lab_ver,
//                            "download_id" => $download_id));

                            DoifdEmail::send_verification_email( $value = array (
                                        "user_name" => $doifd_lab_subscriber_name,
                                        "user_email" => $doifd_lab_subscriber_email,
                                        "user_ver" => $doifd_lab_ver,
                                        "download_id" => $download_id ) );
                        }
                    }
                    /* Return thank you message to subscriber */

                    $msg = '<div class="doifd_user_reg_form thankyou"><h4>' . __( 'Thank You for Registering!', 'double-opt-in-for-download' ) . '<br />' . __( 'Please check your email for your link to your Free download.', 'double-opt-in-for-download' ) . '</h4><br /><i>' . __( 'Don\'t forget to check your junk or spam folder.', 'double-opt-in-for-download' ) . '</i><br />'
                            . $doifd_promo_link .
                            '</div>';
                    
                    return apply_filters('doifd_success_msg', $msg, $download_id);

                    /* If the insert was NOT successfull or TRUE lets show a database error. */

                    $text = __( 'Database Error', 'double-opt-in-for-download' );

                    return '<div class="doifd_error_msg">' . $text . '</div>';
                }
            }
            else {
                
                    $form_values = apply_filters( 'doifd_form_setup_values', array(
                    "form_text" => $doifd_form_text,
                    "id" => $download_id,
                    "nonce" => $doifd_lab_user_form_nonce,
                    "name" => $subscriber_name,
                    "email" => $subscriber_email,
                    "button_text" => $doifd_form_button_text,
                    "privacy" => $doifd_privacy_policy,
                    "promo" => $doifd_promo_link
                ) );
                /* Else, lets show the form for the first time */

                ob_start();
                $form = include_once ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/views/forms/view-default.php' );
                $form_output = ob_get_contents();
                ob_end_clean();
                return apply_filters( 'doifd_form_output', $form_output, $form_values );
            }
        }

    }

}
?>
