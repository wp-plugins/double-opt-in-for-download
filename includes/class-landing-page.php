<?php

if ( !class_exists( 'DoifdLandingPage' ) ) {

    class DoifdLandingPage {

        function __construct() {
            
        }

        public static function verify_email( $attr, $content ) {

            global $wpdb;

            /* See if the admin wants to use special button text for the download button. */

            if ( !empty( $attr[ 'button_text' ] ) ) {

                $button_text = $attr[ 'button_text' ];
            } else {

                $button_text = __( ' Click Here For Your Free Download', 'double-opt-in-for-download' );
            }

            /* Get options from options table and assign to variable */

            $options = get_option( 'doifd_lab_options' );

            /* Get the downloads allowed option and assign to variable */

            $allowed = $options[ 'downloads_allowed' ];

            /* Get the verification number and assign to a value */

            if ( isset( $_GET[ 'ver' ] ) ) {

                $ver = $_GET[ 'ver' ];

                /* If ver is empty send a message to the user */

                if ( empty( $ver ) ) {

                    return '<div class="doifd_user_reg_form exceeded">' . __( 'Not a valid verification number.', 'double-opt-in-for-download' ) . '</div>';
                }

                /* Create nounce for download button to prevent really smart people from hyperlinking. */

                $download_nonce = wp_create_nonce( 'doifd-subscriber-download-nonce' );

                /* Query the database to check the verification number and get the value
                 * for downloads_allowed and assign the results to a variable
                 */

                $checkver = $wpdb->get_row( "SELECT doifd_email_verified, doifd_downloads_allowed, doifd_download_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " );

                /* If the verification number that is given is not a valid number send a message to the user */

                if ( empty( $checkver ) ) {

                    return '<div class="doifd_user_reg_form exceeded">' . __( 'Not a valid verification number.', 'double-opt-in-for-download' ) . '</div>';
                }

                /* Query the database to get the download name
                 * **NEED TO UPDATE AND COMBINE BOTH QUERIES***
                 */

                /* If the email is already verified and they have already exceed the number of downloads, lets show a message */ elseif ( ( $checkver->doifd_email_verified == '1' ) && ( $checkver->doifd_downloads_allowed >= $allowed ) ) {

                    return '<div class="doifd_user_reg_form exceeded">' . __( 'You have exceeded your number of<br />downloads for this item.', 'double-opt-in-for-download' ) . '</div>';

                    /* If the email is already verified and they have NOT exceed the number of downloads, lets show the download button */
                } elseif ( ( $checkver->doifd_email_verified == '1' ) && ( $checkver->doifd_downloads_allowed <= $allowed ) ) {


                    $fileName = $wpdb->get_var( "SELECT doifd_download_file_name FROM " . $wpdb->prefix . "doifd_lab_downloads  WHERE doifd_download_id = '$checkver->doifd_download_id' " );

                    /* Use PHP to see if the file is really there */

                    $file = DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . '/' . $fileName;

                    /* If we find there is no file on the server, show the user a generic error message with code */

                    if ( file_exists( $file ) == TRUE ) {

                        $query = $wpdb->get_row( "SELECT doifd_download_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' ", ARRAY_A );

                        $download_id_from_db = $query[ 'doifd_download_id' ];

                        return '<div class="doifd_user_reg_form thankyou"><br /><form method="get" action="" enctype="multipart/form-data">
                                                             <input type="hidden" name="download_id" value="' . $download_id_from_db . '">
                                                             <input type="hidden" name="download_nonce" value="' . $download_nonce . '">
                                                             <input type="hidden" name="ver" value="' . $ver . '">
                                                             <input name="doifd_get_download" type="submit" value="' . $button_text . '">
                                                             </form>
                                                             </div>';

                        /* If this is the subscribers first visit lets update the database to show
                         * the email is now verified and show them the download link
                         */
                    } else {

                        return '<div class="doifd_user_reg_form exceeded">' . __( 'There was an error', 'double-opt-in-for-download' ) . '<br />Please notify the website administrator.</div>';
                    }
                } else {

                    /* Get the subscriber ID */

                    $getsubid = $wpdb->get_row( "SELECT doifd_subscriber_id, doifd_name, doifd_email FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " );

                    /* Update the table */

                    $wpdb->update(
                            $wpdb->prefix . 'doifd_lab_subscribers', array (
                        'doifd_email_verified' => '1', // string
                            ), array ( 'doifd_subscriber_id' => $getsubid->doifd_subscriber_id ), array (
                        '%d' // value2
                            ), array ( '%d' )
                    );

                    $subscriber = $getsubid->doifd_name;
                    $email = $getsubid->doifd_email;

                    if ( $options[ 'notification_email' ] == '1' ) {

                        $download = $wpdb->get_var( "SELECT doifd_download_name FROM " . $wpdb->prefix . "doifd_lab_downloads  WHERE doifd_download_id = '$checkver->doifd_download_id' " );

                        DoifdEmail::admin_notification( $subscriber, $download, $email );
                    }

                    $fileName = $wpdb->get_var( "SELECT doifd_download_file_name FROM " . $wpdb->prefix . "doifd_lab_downloads  WHERE doifd_download_id = '$checkver->doifd_download_id' " );

                    /* Use PHP to see if the file is really there */

                    $file = DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . '/' . $fileName;

                    /* If we find there is no file on the server, show the user a generic error message with code */

                    if ( file_exists( $file ) == TRUE ) {


                        $query = $wpdb->get_row( "SELECT doifd_download_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' ", ARRAY_A );
                        $download_id_from_db = $query[ 'doifd_download_id' ];

                        return '<div class="doifd_user_reg_form thankyou"><br /><form method="get" action="" enctype="multipart/form-data">
                                                             <input type="hidden" name="download_id" value="' . $download_id_from_db . '">
                                                             <input type="hidden" name="download_nonce" value="' . $download_nonce . '">
                                                             <input type="hidden" name="ver" value="' . $ver . '">
                                                             <input name="doifd_get_download" type="submit" value="' . __( 'Click Here For Your Free Download', 'double-opt-in-for-download' ) . '">
                                                             </form>
                                                             </div>';
                    } else {

                        return '<div class="doifd_user_reg_form exceeded">' . __( 'There was an error', 'double-opt-in-for-download' ) . '<br />Please notify the website administrator.</div>';
                    }
                }
            }
        }

    }

}
?>
