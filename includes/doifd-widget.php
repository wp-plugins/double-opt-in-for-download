<?php
if ( !class_exists( 'DoifdFormWidget' ) ) {

    class DoifdFormWidget extends WP_Widget {

        function DoifdFormWidget() {

            $description = __( 'Display DOIFD Signup Form', 'double-opt-in-for-download' );
            $widget_title = __( 'DOIFD Form', 'double-opt-in-for-download' );

            $widget_ops = array (
                'classname' => 'doifd_lab_widget_signup_class',
                'description' => $description
            );
            $this->WP_Widget( 'doifd_lab_widget_signup', $widget_title, $widget_ops );
        }

        function widget( $args, $instance ) {

            global $wpdb;
            global $widget_download_id;

            // extract widget arguments.
            extract( $args );

            // get $instance variables and assign to variables
            $title = apply_filters( 'widget_title', $instance[ 'title' ] );
            $widget_download_id = apply_filters( 'widget_doifd_download_name', $instance[ 'doifd_download_name' ] );
            $header_text = apply_filters( 'widget_doifd_form_text', $instance[ 'doifd_form_text' ] );
            $lab_widget_form_button_text = apply_filters( 'widget_doifd_form_button_text', $instance[ 'doifd_form_button_text' ] );

            if ( empty( $header_text ) || (!isset( $header_text )) ) {
                // header text for widget form show to subscribers if not set by admin
                $header_text = __( 'Please provide your name and email address for your free download.', 'double-opt-in-for-download' );
            }

            // text shown on submit button in the form
            if ( empty( $lab_widget_form_button_text ) || (!isset( $lab_widget_form_button_text )) ) {
                $lab_widget_form_button_text = __( 'Get Your Free Download', 'double-opt-in-for-download' );
            }

            // used to create the _wpnounce in the form
            $doifd_lab_subscriber_form_nonce = wp_create_nonce( 'doifd-subscriber-registration-nonce' );

            // label for name text input box
            $subscriber_name = __( 'Name', 'double-opt-in-for-download' );

            // label for email text input box
            $subscriber_email = __( 'Email Address', 'double-opt-in-for-download' );

            //Set promotional link if option is on
            // get options from options table and assign to variable
            $options = get_option( 'doifd_lab_options' );

            // see if the admin wants to add the subscriber to the wp user table
            if ( isset( $options[ 'promo_link' ] ) ) {
                $option = $options[ 'promo_link' ];
            }

            if ( ( isset( $option ) ) && ($option == '1') ) {
                $doifd_promo_link = '<p class="doifd_widget_promo_link"><a href="http://www.doubleoptinfordownload.com" target="new">' . __( 'Powered by', 'double-opt-in-for-download' ) . '<br />' . __( 'Lab Web Development', 'double-opt-in-for-download' ) . '</a></p>';
            }
            else {
                $doifd_promo_link = '';
            }

            /* See If Privacy Policy is Set */

            if ( isset( $options[ 'use_privacy_policy' ] ) ) {

                $option = $options[ 'use_privacy_policy' ];
                $text = $options[ 'privacy_link_text' ];
                $link = $options[ 'privacy_page' ];
            }

            if ( ( isset( $option ) ) && ($option == '1') ) {

                $doifd_privacy_policy = '<div class="doifd_privacy_link"><a href="' . get_page_link( $link ) . '" target="new" >' . $text . '</a></div>';
            }
            else {

                $doifd_privacy_policy = '';
            }

            echo $before_widget;

            // get widget title
            if ( isset( $title ) ) {
                echo $before_title . $title . $after_title;
            }

            // get verification number if it's set
            if ( isset( $_GET[ 'ver' ] ) ) {
                $ver = $_GET[ 'ver' ];
            }
            else {
                $ver = '';
            }

            // If the subscriber is submitting the form lets do this....
            if ( isset( $_POST[ 'widget_doifd-subscriber-registration' ] ) ) {

                $get_options = get_option( 'doifd_lab_options' );

                // assign table name to specific variable
                $wpdb->doifd_subscribers = $wpdb->prefix . 'doifd_lab_subscribers';

                // used to create the _wpnounce in the form
                $doifd_lab_nonce = $_POST[ '_wpnonce' ];

                // See if the admin wants to use nonce, if so, check to make sure data is coming from our form, if not, die.
                if ( $get_options[ 'form_security' ] == '0' ) {

                    if ( !wp_verify_nonce( $doifd_lab_nonce, 'doifd-subscriber-registration-nonce' ) )
                        wp_die( 'Security check' );
                }

                // sanitize Name Field and assign to varialbe.
                $doifd_lab_subscriber_name = sanitize_text_field( $_POST[ 'doifd_subscriber_name' ] );

                // sanitize Email Field and assign to varialbe.
                $doifd_lab_subscriber_email = sanitize_email( $_POST[ 'doifd_subscriber_email' ] );

                // sanitize download id field and assign to varialbe.
                $widget_download_id = preg_replace( "/[^0-9]/", "", $_POST[ 'download_id' ] );

                // check for duplicate email address. 
                $doifd_lab_check_duplicate_email = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->doifd_subscribers WHERE doifd_email = %s AND doifd_download_id = %d", $doifd_lab_subscriber_email, $widget_download_id ), ARRAY_A );

                // check if subscriber name field is populated after sanitization.
                if ( empty( $doifd_lab_subscriber_name ) ) {
                    $text = __( 'Please provide your name.', 'double-opt-in-for-download' );
                    $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>';
                }
                // check if email field is populated with a valid email address (example@example.com).
                elseif ( !is_email( $doifd_lab_subscriber_email ) ) {
                    $text = __( 'Not a valid email address.', 'double-opt-in-for-download' );
                    $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>';
                }
                // if a duplicate email is found, send message to the subscriber
                elseif ( $doifd_lab_check_duplicate_email != null ) {
                    $text = __( 'This email address has already been used.', 'double-opt-in-for-download' );
                    $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>';
                }

                // If and error message is returned let show the form again with the error message
                if ( isset( $doifd_lab_msg ) ) {
                    
                    $widget_values = apply_filters( 'doifd_widget_setup_values', array (
                    "widget_form_text" => $header_text,
                    "widget_id" => $widget_download_id,
                    "widget_nonce" => $doifd_lab_subscriber_form_nonce,
                    "widget_error" => $doifd_lab_msg,
                    "widget_name" => $subscriber_name,
                    "widget_email" => $subscriber_email,
                    "widget_button_text" => $lab_widget_form_button_text,
                    "widget_privacy" => $doifd_privacy_policy,
                    "widget_promo" => $doifd_promo_link
                ) );

                    ob_start();
                    $widget_form = include_once ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/views/forms/view-widget-form.php' );
                    $widget_form_output = ob_get_contents();
                    ob_end_clean();
                    echo apply_filters( 'doifd_widget_output', $widget_form_output, $widget_values );
                }
                else {

                    // If no error message was created lets go ahead and add the subscriber to the database and send them
                    // a verification email.
                    // create verification number using sha1 and the current time and assign it to a variable       
                    $doifd_lab_ver = sha1( time() );

                    // insert subscriber into the database
                    if ( $wpdb->insert(
                                    $wpdb->prefix . 'doifd_lab_subscribers', array (
                                'doifd_name' => $doifd_lab_subscriber_name,
                                'doifd_email' => $doifd_lab_subscriber_email,
                                'doifd_verification_number' => $doifd_lab_ver,
                                'doifd_download_id' => $widget_download_id,
                                'time' => current_time( 'mysql', 0 )
                                    ), array (
                                '%s',
                                '%s',
                                '%s',
                                '%s'
                                    )
                            ) == TRUE ) {

                        /*                         * *********************************************************
                         * Add to wordpress users table if admin selected that option.
                         * ***********************************************************
                         */

                        // get options from options table and assign to variable
                        $options = get_option( 'doifd_lab_options' );

                        // see if the admin wants to add the subscriber to the wp user table
                        $add_to_user_option_table = $options[ 'add_to_wpusers' ];

                        // if yes, lets add the user if not, we will just go on our merry way.
                        if ( ( $add_to_user_option_table == '1' ) && ($doifd_lab_check_duplicate_email == NULL ) ) {

                            // generate a random password for the new user
                            $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );

                            // insert into wp user table and get user id for meta information
                            $user_id = wp_create_user( $doifd_lab_subscriber_email, $random_password, $doifd_lab_subscriber_email );

                            // just for fun lets explode the subscriber name. in case they entered their first and last name
                            $name = explode( ' ', $doifd_lab_subscriber_name );

                            // add first name to user meta table
                            update_user_meta( $user_id, 'first_name', $name[ 0 ] );

                            // if subcriber entered 2 names lets add the second as the last name
                            if ( !empty( $name[ 1 ] ) ) {
                                update_user_meta( $user_id, 'last_name', $name[ 1 ] );
                            }
                        }


                        //lets package the subscriber information and download id into an array and send it to the send email function
                        if ( has_filter( 'doifd_alt_verification_email' ) ) {

                            $send = apply_filters( 'doifd_alt_verification_email', $value = array (
                                "user_name" => $doifd_lab_subscriber_name,
                                "user_email" => $doifd_lab_subscriber_email,
                                "user_ver" => $doifd_lab_ver,
                                "download_id" => $widget_download_id ) );
                        }
                        else {

                            $send = DoifdEmail::send_verification_email( $value = array (
                                        "user_name" => $doifd_lab_subscriber_name,
                                        "user_email" => $doifd_lab_subscriber_email,
                                        "user_ver" => $doifd_lab_ver,
                                        "download_id" => $widget_download_id ) );
                        }

                        // return the "Thank You For Registering"
                        echo '<div id="widget_doifd_user_reg_form" class="thankyou"><h4>' . __( 'Thank You for Registering!', 'double-opt-in-for-download' ) . '</h4>' . __( 'Please check your email for your link to your Free download.', 'double-opt-in-for-download' ) . '<br><br><i>' . __( 'Don\'t forget to check your junk or spam folder.', 'double-opt-in-for-download' ) . '</i></div>'
                        . $doifd_promo_link .
                        '</div>';
                    }
                    else {

                        //If the insert was NOT successfull or TRUE lets show a database error.
                        $text = __( 'Database Error', 'double-opt-in-for-download' );
                        echo '<div class="doifd_error_msg">' . $text . '</div>';
                    }
                }
                // if they are not submitting the form then just show the form.    
            }
            else {
                
                $widget_values = apply_filters( 'doifd_widget_setup_values', array (
                    "widget_form_text" => $header_text,
                    "widget_id" => $widget_download_id,
                    "widget_nonce" => $doifd_lab_subscriber_form_nonce,
                    "widget_name" => $subscriber_name,
                    "widget_email" => $subscriber_email,
                    "widget_button_text" => $lab_widget_form_button_text,
                    "widget_privacy" => $doifd_privacy_policy,
                    "widget_promo" => $doifd_promo_link
                ) );

                ob_start();
                $widget_form = include_once ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/views/forms/view-widget-form.php' );
                $widget_form_output = ob_get_contents();
                ob_end_clean();
                echo apply_filters( 'doifd_widget_output', $widget_form_output, $widget_values );
            }

            echo $after_widget;
        }

        function update( $new_instance, $old_instance ) {
            return $new_instance;
        }

        function form( $instance ) {

// this function creates the widget form in the admin area

            global $wpdb;

            // get the $instances and assign them to variables.
            if ( isset( $instance[ 'title' ] ) && $instance[ 'doifd_download_name' ] ) {
                $title = esc_attr( $instance[ 'title' ] );
                $dlid = esc_attr( $instance[ 'doifd_download_name' ] );
                $widget_form_text = esc_attr( $instance[ 'doifd_form_text' ] );
                $widget_form_button_text = esc_attr( $instance[ 'doifd_form_button_text' ] );
            }
            else {
                $title = '';
                $dlid = '';
                $widget_form_text = '';
                $widget_form_button_text = '';
            }
            ?>
            <!--Show the Form-->
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
                </label>
                <label for="<?php echo $this->get_field_id( 'doifd_form_text' ); ?>"><?php _e( 'Form Text:', 'double-opt-in-for-download' ); ?>
                    <textarea class="widefat" rows="3" id="<?php echo $this->get_field_id( 'doifd_form_text' ); ?>" name="<?php echo $this->get_field_name( 'doifd_form_text' ); ?>" type="text"><?php echo $widget_form_text; ?></textarea>
                </label>
                <label for="<?php echo $this->get_field_id( 'doifd_form_button_text' ); ?>"><?php _e( 'Button Text:', 'double-opt-in-for-download' ); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'doifd_form_button_text' ); ?>" name="<?php echo $this->get_field_name( 'doifd_form_button_text' ); ?>" type="text" value="<?php echo $widget_form_button_text; ?>" />
                </label>
                <label for="<?php echo $this->get_field_id( 'Download' ); ?>"><?php _e( 'Select Download:', 'double-opt-in-for-download' ); ?>
                    <select name="<?php echo $this->get_field_name( 'doifd_download_name' ); ?>" id="<?php echo $this->get_field_id( 'doifd_download_id' ); ?>" class="widefat">
                        <!--Get list of Downloads and populate drop down select in form-->
            <?php
            $sql = "SELECT * FROM " . $wpdb->prefix . "doifd_lab_downloads ";
            $downloads = $wpdb->get_results( $sql );
            foreach ( $downloads as $download ) {
                echo '<option value="' . $download->doifd_download_id . '"' . ( ( $dlid == $download->doifd_download_id ) ? 'selected="selected"' : "" ) . '">' . $download->doifd_download_name . '</option>';
            }
            ?> 
                    </select>
                </label>
            </p>
                        <?php
                    }

                }

            }
            ?>
