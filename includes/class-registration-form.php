<?php

class DoifdRegistrationForm {

    function __construct() {
        
    }

    public static function registration_form( $attr, $content ) {

        global $wpdb;

        /* Get the download id from the short code, if not send an error to the potential subscriber. */

        if ( isset ( $attr[ 'download_id' ] ) ) {
            
            $download_id = $attr[ 'download_id' ];
            
        } else {
            
            return '<div id="doifd_user_reg_form">Oooops! There is no download id specified</div>';
        }

        if ( isset ( $attr[ 'text' ] ) ) {
            
            $doifd_form_text = $attr[ 'text' ];
            
        } else {
            
            $doifd_form_text = $header_text = __ ( 'Please provide your name and email address for your free download.', 'Double-Opt-In-For-Download' );
        }

        /* Assign button text. if admin did not assign a specific text then use the default */

        if ( isset ( $attr[ 'button_text' ] ) ) {
            
            $doifd_form_button_text = $attr[ 'button_text' ];
            
        } else {
            
            $doifd_form_button_text = $button_text = __ ( 'Get Your Free Download', 'Double-Opt-In-For-Download' );
        }

        /* Used to create the _wpnounce in the form */
        
        $doifd_lab_user_form_nonce = wp_create_nonce ( 'doifd-subscriber-registration-nonce' );

        /* Get subscribers name and assign to variable */
        
        $subscriber_name = __ ( 'Name', 'Double-Opt-In-For-Download' );

        /* Get subscribers email and assign to variable */
        
        $subscriber_email = __ ( 'Email Address', 'Double-Opt-In-For-Download' );

        /***************************************
         * Set promotional link if option is on
         * *************************************
         */
        
        /* Get options from options table and assign to variable */
        
        $options = get_option ( 'doifd_lab_options' );

        /* See if the admin wants to add the subscriber to the wp user table */
        
        if ( isset ( $options[ 'promo_link' ] ) ) {
            
            $option = $options[ 'promo_link' ];
        }

        if ( ( isset ( $option ) ) && ($option == '1') ) {
            
            $doifd_promo_link = '<p class="doifd_promo_link"><a href="http://www.labwebdesigns.com" target="new">Powered by Lab Web Designs & Hosting</a></p>';
        
            } else {
            
                $doifd_promo_link = '';
                
            }

        /* If the subscriber is submitting the form lets do this.... */

        if ( isset ( $_POST[ 'doifd-subscriber-registration' ] ) ) {
            
            /* Process the reCAPTCHA phrase */
            
            $doifd_resp = DoifdCaptcha::reCaptcha_process();

            /* Assign table name to specific variable */
            
            $wpdb->doifd_subscribers = $wpdb->prefix . 'doifd_lab_subscribers';

            /* Get the nonce from the registration form */
            
            $doifd_lab_nonce = $_POST[ '_wpnonce' ];

            /* Check to make sure data is coming from our form, if not, lets just die right here. */
            
            if ( !wp_verify_nonce ( $doifd_lab_nonce, 'doifd-subscriber-registration-nonce' ) ) wp_die ( 'Security check' );

            /* Sanitize name field and assign to varialbe. */
            
            $doifd_lab_subscriber_name = sanitize_text_field ( $_POST[ 'doifd_user_name' ] );

            /* Sanitize email field and assign to varialbe. */
            
            $doifd_lab_subscriber_email = sanitize_email ( $_POST[ 'doifd_user_email' ] );

            /* Sanitize download id field and assign to varialbe. */
            
            $download_id = preg_replace ( "/[^0-9]/", "", $_POST[ 'download_id' ] );

            /* Query the database to see if this is a duplicate email address. */
            
            $doifd_lab_check_duplicate_email = $wpdb->get_row ( $wpdb->prepare ( "SELECT * FROM $wpdb->doifd_subscribers WHERE doifd_email = %s AND doifd_download_id = %d", $doifd_lab_subscriber_email, $download_id ), ARRAY_A );

            if ( !$doifd_resp->is_valid ) {
                
                $doifd_lab_msg = '<div class="doifd_error_msg">The Validation code does not match!</div>';
            }
            
            /* If the subscriber name is empty after sanitation lets return an error message */
            
            elseif ( empty ( $doifd_lab_subscriber_name ) ) {
            
                $text = __ ( 'Please provide your name.', 'Double-Opt-In-For-Download' );
            
                $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>';
            }
            
            /* If email is not valid after sanitation lets return an error message */
            
            elseif ( !is_email ( $doifd_lab_subscriber_email ) ) {
            
                $text = __ ( 'Not a valid email address.', 'Double-Opt-In-For-Download' );
                
                $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>';
            }
            
            /* If the email address is a duplicate lets return an error message */
            
            elseif ( $doifd_lab_check_duplicate_email != null ) {
            
                $text = __ ( 'This email address has already been used.', 'Double-Opt-In-For-Download' );
                
                $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>';
                
            }

            /* If and error message is returned let show the form again with the error message */
            
            if ( isset ( $doifd_lab_msg ) ) {

                $publickey = DoifdCaptcha::reCaptcha_public_key () ;

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
                <div id="doifd_captcha">' . doifd_recaptcha_get_html ( $publickey ) . '</div>
                <div id="doifd_button_holder">
                <input name="doifd-subscriber-registration" type="submit" value=" ' . $doifd_form_button_text . ' "><br /></div>'
                        . $doifd_promo_link .
                        '</form>
                </div>';
                
            } else {

                /* If no error message was created lets go ahead and add the subscriber
                 * to the database and send them a verification email create verification
                 * number using sha1 and the current time and assign it to a variable.
                 */
                
                $doifd_lab_ver = sha1 ( time () );

                /* Insert subscriber into the database */
                
                if ( $wpdb->insert (
                                $wpdb->prefix . 'doifd_lab_subscribers', array(
                            'doifd_name' => $doifd_lab_subscriber_name,
                            'doifd_email' => $doifd_lab_subscriber_email,
                            'doifd_verification_number' => $doifd_lab_ver,
                            'doifd_download_id' => $download_id,
                            'time' => current_time ( 'mysql', 0 )
                                ), array(
                            '%s',
                            '%s',
                            '%s',
                            '%s'
                                )
                        ) == TRUE ) {

                    /* **********************************************************
                     * Add to wordpress users table if admin selected that option.
                     * ***********************************************************
                     */

                    /* Get options from options table and assign to variable */
                    
                    $options = get_option ( 'doifd_lab_options' );

                    /* See if the admin wants to add the subscriber to the wp user table */
                    
                    $add_to_user_option_table = $options[ 'add_to_wpusers' ];

                    /* If yes, lets add the user if not, we will just go on our merry way. */
                    
                    if ( ( $add_to_user_option_table == '1' ) && ($doifd_lab_check_duplicate_email == NULL ) ) {

                        /* Generate a random password for the new user */
                        
                        $random_password = wp_generate_password ( $length = 12, $include_standard_special_chars = false );

                        /* Insert into wp user table and get user id for meta information */
                        
                        $user_id = wp_create_user ( $doifd_lab_subscriber_email, $random_password, $doifd_lab_subscriber_email );

                        /* Just for fun lets explode the subscriber name. in case they entered their first and last name */
                        
                        $name = explode ( ' ', $doifd_lab_subscriber_name );

                        /* Add first name to user meta table */
                        
                        update_user_meta ( $user_id, 'first_name', $name[ 0 ] );

                        /* If subcriber entered 2 names lets add the second as the last name */
                        
                        if ( !empty ( $name[ 1 ] ) ) {
                        
                            update_user_meta ( $user_id, 'last_name', $name[ 1 ] );
                        }
                        
                    }

                    /* Lets package the subscriber information and download id into an array and send it to the send email function */
                    
                    DoifdEmail::send_verification_email ( $value = array(
                        "user_name" => $doifd_lab_subscriber_name,
                        "user_email" => $doifd_lab_subscriber_email,
                        "user_ver" => $doifd_lab_ver,
                        "download_id" => $download_id ) );

                    /* Return thank you message to subscriber */
                    
                    return '<div id="doifd_user_reg_form" class="thankyou"><h4>Thank You for Registering!</h4>Please check your email for your link to your Free download.<br />'
                            . $doifd_promo_link .
                            '</div>';
                    
                } else {

                    /* If the insert was NOT successfull or TRUE lets show a database error. */
                    
                    $text = __ ( 'Database Error', 'Double-Opt-In-For-Download' );
                    
                    return '<div class="doifd_error_msg">' . $text . '</div>';
                }
            }
        }

        $publickey = DoifdCaptcha::reCaptcha_public_key ();

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
                <div id="doifd_captcha">' . doifd_recaptcha_get_html ( $publickey ) . '</div>
                <div id="doifd_button_holder">
                <input name="doifd-subscriber-registration" type="submit" value=" ' . $doifd_form_button_text . ' "><br /></div>'
                . $doifd_promo_link .
                '</form>
                </div>';

    }

    function verify_email() {

        global $wpdb;

        /* Get options from options table and assign to variable */
        
        $options = get_option ( 'doifd_lab_options' );

        /* Get the downloads allowed option and assign to variable */
        
        $allowed = $options[ 'downloads_allowed' ];

        /* Get the verification number and assign to a value */
        
        if ( isset ( $_GET[ 'ver' ] ) ) {

            $ver = $_GET[ 'ver' ];

            /* Query the database to check the verification number and get the value
             * for downloads_allowed and assign the results to a variable
             */
            
            $checkver = $wpdb->get_row ( "SELECT doifd_email_verified, doifd_downloads_allowed FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " );

            /* If the email is already verified and they have already exceed the number
             * of downloads allowed set session value to 3
             */

            if ( ( $checkver->doifd_email_verified == '1' ) && ( $checkver->doifd_downloads_allowed >= $allowed ) ) {

                return '<div id="doifd_user_reg_form" class="exceeded">You have exceeded your number of<br />downloads for this item.</div>';

                /* If the email is already verified and they have NOT exceed the number of
                 * downloads allowed set session value to 2
                 */
                
            } elseif ( ( $checkver->doifd_email_verified == '1' ) && ( $checkver->doifd_downloads_allowed <= $allowed ) ) {

                $query = $wpdb->get_row ( "SELECT doifd_download_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' ", ARRAY_A );
                
                $download_id_from_db = $query[ 'doifd_download_id' ];

                return '<div id="doifd_user_reg_form" class="thankyou"><form method="post" action="" enctype="multipart/form-data">
                                                             <input type="hidden" name="download_id" value="' . $download_id_from_db . '">
                                                             <input type="hidden" name="ver" value="' . $ver . '">
                                                             <input name="doifd_get_download" type="submit" value=" Click Here For Your Free Download ">
                                                             </form>
                                                             </div>';

                /* If this is the subscribers first visit lets update the database to show
                 * the email is now verified and show them the download link
                 */
                
            } else {

                /* Get the subscriber ID */
                
                $getsubid = $wpdb->get_row ( "SELECT doifd_subscriber_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " );

                /* Update the table */
                
                $wpdb->update (
                        $wpdb->prefix . 'doifd_lab_subscribers', array(
                    'doifd_email_verified' => '1', // string
                        ), array( 'doifd_subscriber_id' => $getsubid->doifd_subscriber_id ), array(
                    '%d' // value2
                        ), array( '%d' )
                );

                $query = $wpdb->get_row ( "SELECT doifd_download_id FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' ", ARRAY_A );
                $download_id_from_db = $query[ 'doifd_download_id' ];

                return '<div id="doifd_user_reg_form" class="thankyou"><form method="post" action="" enctype="multipart/form-data">
                                                             <input type="hidden" name="download_id" value="' . $download_id_from_db . '">
                                                             <input type="hidden" name="ver" value="' . $ver . '">
                                                             <input name="doifd_get_download" type="submit" value=" Click Here For Your Free Download ">
                                                             </form>
                                                             </div>';
            }
        }

    }

}
?>