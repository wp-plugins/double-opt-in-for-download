<?php

if (!class_exists('DOIFD')) {

    class DoifdRegistrationProcess {

        function __construct() {
            
        }

        public static function registration_process() {

            global $wpdb;

            if ( isset ( $_POST[ 'doifd-subscriber-registration' ] ) ) {

            /*  See if the Admin wants Captcha On or Off */

            $options = get_option('doifd_lab_options');

            if (( $options['doifd_recaptcha_enable_form'] ) == 1) {

                $doifd_captcha = TRUE;
            } else {

                $doifd_captcha = FALSE;
            }

            /* Assign table name to specific variable */

            $wpdb->doifd_subscribers = $wpdb->prefix . 'doifd_lab_subscribers';

            /* Get the nonce from the registration form */

            $doifd_lab_nonce = $_POST['_wpnonce'];

            /* Check to make sure data is coming from our form, if not, lets just die right here. */

            if (!wp_verify_nonce($doifd_lab_nonce, 'doifd-subscriber-registration-nonce'))
                wp_die('Security check');

            /* Sanitize name field and assign to varialbe. */

            $doifd_lab_subscriber_name = sanitize_text_field($_POST['doifd_user_name']);

            /* Sanitize email field and assign to varialbe. */

            $doifd_lab_subscriber_email = sanitize_email($_POST['doifd_user_email']);

            /* Sanitize download id field and assign to varialbe. */

            $download_id = preg_replace("/[^0-9]/", "", $_POST['download_id']);

            /* Query the database to see if this is a duplicate email address. */

            $doifd_lab_check_duplicate_email = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->doifd_subscribers WHERE doifd_email = %s AND doifd_download_id = %d", $doifd_lab_subscriber_email, $download_id), ARRAY_A);

            /* If the subscriber name is empty after sanitation lets return an error message */

            if (empty($doifd_lab_subscriber_name)) {

                $msg = __('Please provide your name.', 'double-opt-in-for-download');
                return $msg;

            }
//
            /* If email is not valid after sanitation lets return an error message */ elseif (!is_email($doifd_lab_subscriber_email)) {

                $msg = __('Not a valid email address.', 'double-opt-in-for-download');

                echo '<div class="doifd_error_msg">' . $msg . '</div>';
                die(); /* Added to suppress the Ajax success response */
            }

            /* If the email address is a duplicate lets return an error message */ elseif ($doifd_lab_check_duplicate_email != null) {

                $msg = __('This email address has already been used.', 'double-opt-in-for-download');

                echo '<div class="doifd_error_msg">' . $msg . '</div>';
                die(); /* Added to suppress the Ajax success response */
            } else {

                /* If no error message was created lets go ahead and add the subscriber
                 * to the database and send them a verification email create verification
                 * number using sha1 and the current time and assign it to a variable.
                 */

                $doifd_lab_ver = sha1(time());

                /* Insert subscriber into the database */

                if ($wpdb->insert(
                                $wpdb->prefix . 'doifd_lab_subscribers', array(
                            'doifd_name' => $doifd_lab_subscriber_name,
                            'doifd_email' => $doifd_lab_subscriber_email,
                            'doifd_verification_number' => $doifd_lab_ver,
                            'doifd_download_id' => $download_id,
                            'time' => current_time('mysql', 0)
                                ), array(
                            '%s',
                            '%s',
                            '%s',
                            '%s'
                                )
                        ) == TRUE) {

                    /* ******************************************************
                     * Add to wordpress users table if admin selected that option.
                     * ***********************************************************
                     */
//
//                        /* Get options from options table and assign to variable */
//
                    $options = get_option('doifd_lab_options');

                    /* See if the admin wants to add the subscriber to the wp user table */

                    $add_to_user_option_table = $options['add_to_wpusers'];

                    /* If yes, lets add the user if not, we will just go on our merry way. */

                    if (( $add_to_user_option_table == '1' ) && ($doifd_lab_check_duplicate_email == NULL )) {

                        /* Generate a random password for the new user */

                        $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);

                        /* Insert into wp user table and get user id for meta information */

                        $user_id = wp_create_user($doifd_lab_subscriber_email, $random_password, $doifd_lab_subscriber_email);

                        /* Just for fun lets explode the subscriber name. in case they entered their first and last name */

                        $name = explode(' ', $doifd_lab_subscriber_name);

                        /* Add first name to user meta table */

                        update_user_meta($user_id, 'first_name', $name[0]);

                        /* If subcriber entered 2 names lets add the second as the last name */

                        if (!empty($name[1])) {

                            update_user_meta($user_id, 'last_name', $name[1]);
                        }
                    }

                    /* Lets package the subscriber information and download id into an array and send it to the send email function */

                    DoifdEmail::send_verification_email($value = array(
                        "user_name" => $doifd_lab_subscriber_name,
                        "user_email" => $doifd_lab_subscriber_email,
                        "user_ver" => $doifd_lab_ver,
                        "download_id" => $download_id));
                }
                /* Return thank you message to subscriber */

                $msg = __('Thank You for Registering!', 'double-opt-in-for-download') . '</h4>' . __('Please check your email for your link to your Free download.', 'double-opt-in-for-download') . '<br />';

                echo '<div class="doifd_success_msg">' . $msg . '</div>';
                die();

//                    /* If the insert was NOT successfull or TRUE lets show a database error. */
//
//                    $text = __ ( 'Database Error', 'double-opt-in-for-download' );
//
//                    return '<div class="doifd_error_msg">' . $text . '</div>';
//                }
////            } else {
////
////            $options = get_option ( 'doifd_lab_recaptcha_options' );
////
////            /* See if the admin wants to add the subscriber to the wp user table */
////
////            if (( $options[ 'doifd_recaptcha_enable_form' ] ) ==  1 ) {
////
////              /* If it exists get the public key and show the reCAPTCHA form */
////                    $publickey = DoifdCaptcha::reCaptcha_public_key ();
////                    
////                } 
////                ob_start();
////                $form = include_once ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/views/forms/view-default.php' );
////                $form_output = ob_get_contents();
////                ob_end_clean();
////                return $form_output;
////            }
//
//        }
//
//        public static function verify_email( $attr, $content ) {
//
//            global $wpdb;
//
//            /* See if the admin wants to use special button text for the download button. */
//
//            if ( !empty ( $attr[ 'button_text' ] ) ) {
//
//                $button_text = $attr[ 'button_text' ];
//            } else {
//
//                $button_text = __ ( ' Click Here For Your Free Download', 'double-opt-in-for-download' );
//            }
//
//            /* Get options from options table and assign to variable */
//
//            $options = get_option ( 'doifd_lab_options' );
//
//            /* Get the downloads allowed option and assign to variable */
//
//            $allowed = $options[ 'downloads_allowed' ];
//
//            /* Get the verification number and assign to a value */
//
//            if ( isset ( $_GET[ 'ver' ] ) ) {
//
//                $ver = $_GET[ 'ver' ];
//
//                /* Query the database to check the verification number and get the value
//                 * for downloads_allowed and assign the results to a variable
//                 */
//
//                $checkver = $wpdb->get_row ( "SELECT doifd_email_verified, doifd_downloads_allowed, doifd_download_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " );
//
//                /* Query the database to get the download name
//                 * **NEED TO UPDATE AND COMBINE BOTH QUERIES***
//                 */
//
//                $fileName = $wpdb->get_var ( "SELECT doifd_download_file_name FROM " . $wpdb->prefix . "doifd_lab_downloads  WHERE doifd_download_id = '$checkver->doifd_download_id' " );
//
//                /* Use PHP to see if the file is really there */
//
//                $file = DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . '/' . $fileName;
//
//                /* If we find there is no file on the server, show the user a generic error message with code */
//
//                if ( !file_exists ( $file ) ) {
//
//                    return '<div id="doifd_user_reg_form" class="exceeded">' . __ ( 'There was an error', 'double-opt-in-for-download' ) . '<br />Please notify the website administrator.</div>';
//                }
//
//                /* If the email is already verified and they have already exceed the number of downloads, lets show a message */ elseif ( ( $checkver->doifd_email_verified == '1' ) && ( $checkver->doifd_downloads_allowed >= $allowed ) ) {
//
//                    return '<div id="doifd_user_reg_form" class="exceeded">' . __ ( 'You have exceeded your number of<br />downloads for this item.', 'double-opt-in-for-download' ) . '</div>';
//
//                    /* If the email is already verified and they have NOT exceed the number of downloads, lets show the download button */
//                } elseif ( ( $checkver->doifd_email_verified == '1' ) && ( $checkver->doifd_downloads_allowed <= $allowed ) ) {
//
//                    $query = $wpdb->get_row ( "SELECT doifd_download_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' ", ARRAY_A );
//
//                    $download_id_from_db = $query[ 'doifd_download_id' ];
//
//                    return '<div id="doifd_user_reg_form" class="thankyou"><form method="get" action="" enctype="multipart/form-data">
//                                                             <input type="hidden" name="download_id" value="' . $download_id_from_db . '">
//                                                             <input type="hidden" name="ver" value="' . $ver . '">
//                                                             <input name="doifd_get_download" type="submit" value="' . $button_text . '">
//                                                             </form>
//                                                             </div>';
//
//                    /* If this is the subscribers first visit lets update the database to show
//                     * the email is now verified and show them the download link
//                     */
//                } else {
//
//                    /* Get the subscriber ID */
//
//                    $getsubid = $wpdb->get_row ( "SELECT doifd_subscriber_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " );
//
//                    /* Update the table */
//
//                    $wpdb->update (
//                            $wpdb->prefix . 'doifd_lab_subscribers', array(
//                        'doifd_email_verified' => '1', // string
//                            ), array( 'doifd_subscriber_id' => $getsubid->doifd_subscriber_id ), array(
//                        '%d' // value2
//                            ), array( '%d' )
//                    );
//
//                    $query = $wpdb->get_row ( "SELECT doifd_download_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' ", ARRAY_A );
//                    $download_id_from_db = $query[ 'doifd_download_id' ];
//
//                    return '<div id="doifd_user_reg_form" class="thankyou"><form method="get" action="" enctype="multipart/form-data">
//                                                             <input type="hidden" name="download_id" value="' . $download_id_from_db . '">
//                                                             <input type="hidden" name="ver" value="' . $ver . '">
//                                                             <input name="doifd_get_download" type="submit" value="' . __ ( 'Click Here For Your Free Download', 'double-opt-in-for-download' ) . '">
//                                                             </form>
//                                                             </div>';
//                }
            }
        }

    }
    
    }

}
?>