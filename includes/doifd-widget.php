<?php

class doifd_lab_widget_signup extends WP_Widget {

    function doifd_lab_widget_signup () {
        $widget_ops = array (
            'classname' => 'doifd_lab_widget_signup_class' ,
            'description' => 'Display Double-Opt-In Signup form'
        ) ;
        $this -> WP_Widget ( 'doifd_lab_widget_signup' , 'Double-Opt-In Signup' , $widget_ops ) ;
    }

    function widget ( $args , $instance ) {

        global $wpdb ;

        // extract widget arguments.
        extract ( $args ) ;

        // get $instance variables and assign to variables
        $title = apply_filters ( 'widget_title' , $instance[ 'title' ] ) ;
        $download_id = apply_filters ( 'widget_doifd_download_name' , $instance[ 'doifd_download_name' ] ) ;

        // used to create the _wpnounce in the form
        $doifd_lab_user_form_nonce = wp_create_nonce ( 'doifd-user-registration-nonce' ) ;

        // header text for widget form show to subscribers
        $header_text = __ ( 'Please proivde your name and email address for your free download.' , 'Double-Opt-In-For-Download' ) ;

        // label for name text input box
        $subscriber_name = __ ( 'Name' , 'Double-Opt-In-For-Download' ) ;

        // label for email text input box
        $subscriber_email = __ ( 'Email Address' , 'Double-Opt-In-For-Download' ) ;

        // text shown on submit button in the form
        $button_text = __ ( 'Get Your Free Download' , 'Double-Opt-In-For-Download' ) ;

        echo $before_widget ;

        // get widget title
        if ( isset ( $title ) ) {
            echo $before_title . $title . $after_title ;
        }

        // get verification number if it's set
        if ( isset ( $_GET[ 'ver' ] ) ) {
            $ver = $_GET[ 'ver' ] ;
        }
        else {
            $ver = '' ;
        }

// If the subscriber is submitting the form lets do this....
        if ( isset ( $_POST[ 'widget_doifd-user-registration' ] ) ) {

            // assign table name to specific variable
            $wpdb -> doifd_subscribers = $wpdb -> prefix . 'doifd_lab_subscribers' ;

            // used to create the _wpnounce in the form
            $doifd_lab_nonce = $_POST[ '_wpnonce' ] ;

            // check to make sure data is coming from our form, if not, die.
            if ( ! wp_verify_nonce ( $doifd_lab_nonce , 'doifd-user-registration-nonce' ) )
                wp_die ( 'Security check' ) ;

            // sanitize Name Field and assign to varialbe.
            $doifd_lab_user_name = sanitize_text_field ( $_POST[ 'doifd_user_name' ] ) ;

            // sanitize Email Field and assign to varialbe.
            $doifd_lab_user_email = sanitize_email ( $_POST[ 'doifd_user_email' ] ) ;

            // sanitize download id field and assign to varialbe.
            $download_id = preg_replace ( "/[^0-9]/" , "" , $_POST[ 'download_id' ] ) ;

            // check for duplicate email address. 
            $doifd_lab_check_duplicate_email = $wpdb -> get_row ( $wpdb -> prepare ( "SELECT * FROM $wpdb -> doifd_subscribers WHERE doifd_email = %s AND doifd_download_id = %d" , $doifd_lab_user_email , $download_id ) , ARRAY_A ) ;

            // check if user name field is populated after sanitization.
            if ( empty ( $doifd_lab_user_name ) ) {
                $text = __ ( 'Please provide your name.' , 'Double-Opt-In-For-Download' ) ;
                $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>' ;
            }
            // check if email field is populated with a valid email address (example@example.com).
            elseif ( ! is_email ( $doifd_lab_user_email ) ) {
                $text = __ ( 'Email is not valid.' , 'Double-Opt-In-For-Download' ) ;
                $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>' ;
            }
            // if a duplicate email is found, send message to the user
            elseif ( $doifd_lab_check_duplicate_email != null ) {
                $text = __ ( 'This email address has already been used.' , 'Double-Opt-In-For-Download' ) ;
                $doifd_lab_msg = '<div class="doifd_error_msg">' . $text . '</div>' ;
            }

            // If and error message is returned let show the form again with the error message
            if ( isset ( $doifd_lab_msg ) ) {

                echo '<div id="widget_doifd_user_reg_form">' . $doifd_lab_msg . '
            <h4>' . $header_text . '</h4> 
            <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="download_id" id="download_id" value="' . $download_id . '"/>
            <input type="hidden" name="_wpnonce" id="_wpnonce" value="' . $doifd_lab_user_form_nonce . '"/>
            <ul>
                <li><label for="name">' . $subscriber_name . '<span> *</span>: </label><br />
                    <input type="text" name="doifd_user_name" id="doifd_user_name" value=""/></li>


                <li><label for="name">' . $subscriber_email . '<span> *</span>: </label><br />
                    <input type="text" name="doifd_user_email" id="doifd_user_email" value=""/></li>
            </ul>
            <div id="doifd_button_holder">
            <input name="widget_doifd-user-registration" type="submit" value=" ' . $button_text . ' ">
            </div>
        </form>
        </div>' ;
            }
            else {

                // If no error message was created lets go ahead and add the subscriber to the database and send them
                // a verification email.
                
                // create verification number using sha1 and the current time and assign it to a variable       
                $doifd_lab_ver = sha1 ( time () ) ;

                // insert subscriber into the database
                if ( $wpdb -> insert (
                                $wpdb -> prefix . 'doifd_lab_subscribers' , array (
                            'doifd_name' => $doifd_lab_user_name ,
                            'doifd_email' => $doifd_lab_user_email ,
                            'doifd_verification_number' => $doifd_lab_ver ,
                            'doifd_download_id' => $download_id ,
                            'time' => current_time ( 'mysql' , 0 )
                                ) , array (
                            '%s' ,
                            '%s' ,
                            '%s' ,
                            '%s'
                                )
                        ) == TRUE ) {

                    //lets package the subscriber information and download id into an array and send it to the send email function
                    doifd_lab_verification_email ( $value = array (
                        "user_name" => $doifd_lab_user_name ,
                        "user_email" => $doifd_lab_user_email ,
                        "user_ver" => $doifd_lab_ver ,
                        "download_id" => $download_id ) ) ;

                    // return the "Thank You For Registering"
                    echo '<div id="widget_doifd_user_reg_form" class="thankyou"><h4>Thank You for Registering!</h4>Please check your email for your link to your Free download.</div>' ;
                }
                else {

                    //If the insert was NOT successfull or TRUE lets show a database error.
                    $text = __ ( 'Database Error' , 'Double-Opt-In-For-Download' ) ;
                    echo '<div class="doifd_error_msg">' . $text . '</div>' ;
                }
            }
            // if they are not submitting the form then just show the form.    
        }
        else {

            echo '<div id="widget_doifd_user_reg_form">
                <h4>' . $header_text . '</h4> 
                <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="download_id" id="download_id" value="' . $download_id . '"/>
                <input type="hidden" name="_wpnonce" id="_wpnonce" value="' . $doifd_lab_user_form_nonce . '"/>
                <ul>
                <li><label for="name">' . $subscriber_name . '<span> *</span>: </label><br />
                <input type="text" name="doifd_user_name" id="doifd_user_name" value=""/></li>
                <li><label for="name">' . $subscriber_email . '<span> *</span>: </label><br />
                <input type="text" name="doifd_user_email" id="doifd_user_email" value=""/></li>
                </ul>
                <div id="doifd_button_holder">
                <input name="widget_doifd-user-registration" type="submit" value=" ' . $button_text . ' ">
                </div>
                </form>
        </div>' ;
        }

        echo $after_widget ;
    }

    function update ( $new_instance , $old_instance ) {
        return $new_instance ;
    }

    function form ( $instance ) {

// this function creates the widget form in the admin area

        global $wpdb ;

        // get the $instances and assign them to variables.
        if ( isset ( $instance[ 'title' ] ) && $instance[ 'doifd_download_name' ] ) {
            $title = esc_attr ( $instance[ 'title' ] ) ;
            $dlid = esc_attr ( $instance[ 'doifd_download_name' ] ) ;
        }
        else {
            $title = '' ;
            $dlid = '' ;
        }
        ?>
        <!--Show the Form-->
        <p>
            <label for="<?php echo $this -> get_field_id ( 'title' ) ; ?>"><?php _e ( 'Title:' ) ; ?>
                <input class="widefat" id="<?php echo $this -> get_field_id ( 'title' ) ; ?>" name="<?php echo $this -> get_field_name ( 'title' ) ; ?>" type="text" value="<?php echo $title ; ?>" />
            </label>
            <label for="<?php echo $this -> get_field_id ( 'Download' ) ; ?>"><?php _e ( 'Download:' ) ; ?>
                <select name="<?php echo $this -> get_field_name ( 'doifd_download_name' ) ; ?>" id="<?php echo $this -> get_field_id ( 'doifd_download_id' ) ; ?>" class="widefat">
                    <!--Get list of Downloads and populate drop down select in form-->
        <?php
        $sql = "SELECT * FROM " . $wpdb -> prefix . "doifd_lab_downloads " ;
        $downloads = $wpdb -> get_results ( $sql ) ;
        foreach ( $downloads as $download ) {
            echo '<option value="' . $download -> doifd_download_id . '"' . ( ( $dlid == $download -> doifd_download_id ) ? 'selected="selected"' : "" ) . '">' . $download -> doifd_download_name . '</option>' ;
        }
        ?> 
                </select>
            </label>
        </p>
        <?php
    }
}
?>