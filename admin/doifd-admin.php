<?php

// This function deregisters the default jquery script and registers the google scripts for jquery. I was
// unable to get ajax to work using the default script
// register and define settings
add_action ( 'admin_init' , 'doifd_lab_admin_init' ) ;

function doifd_lab_admin_init() {

    wp_register_style ( 'doifd-admin-stylesheet' , DOUBLE_OPT_IN_FOR_DOWNLOAD_URL . 'css/admin-style.css' , __FILE__ ) ;
    wp_enqueue_style ( 'doifd-admin-stylesheet' ) ;
    register_setting ( 'doifd_lab_options' , 'doifd_lab_options' , 'doifd_lab_validate_options' ) ;
    add_settings_section ( 'doifd_lab_main' , 'General Settings' , '' , 'doifd_lab' ) ;
    add_settings_field ( 'doifd_lab_downloads_allowed' , 'Select Maximum Number of Downloads' , 'doifd_lab_setting_input' , 'doifd_lab' , 'doifd_lab_main' ) ;
    add_settings_field ( 'doifd_lab_landing_page' , 'Select Landing page' , 'doifd_lab_setting_option' , 'doifd_lab' , 'doifd_lab_main' ) ;
    add_settings_field ( 'doifd_lab_add_to_wpusers' , 'Add Subcribers to the Wordpress User Table?' , 'doifd_lab_add_to_wp_user_table' , 'doifd_lab' , 'doifd_lab_main' ) ;
    add_settings_field ( 'doifd_lab_promo_link' , 'Help Us Out?<br />Add a promotional link' , 'doifd_lab_add_promo_link' , 'doifd_lab' , 'doifd_lab_main' ) ;
    add_settings_section ( 'doifd_lab_email_section' , 'Email Settings' , '' , 'doifd_lab' ) ;
    add_settings_field ( 'doifd_lab_from_email' , 'Enter The Return Email Address' , 'doifd_lab_setting_from_email' , 'doifd_lab' , 'doifd_lab_email_section' ) ;
    add_settings_field ( 'doifd_lab_email_name' , 'Enter who the email is from<br>(Default is the Website or Blog name)' , 'doifd_lab_setting_email_name' , 'doifd_lab' , 'doifd_lab_email_section' ) ;
    add_settings_field ( 'doifd_lab_email_message' , 'Email Message:' , 'doifd_lab_setting_email_message' , 'doifd_lab' , 'doifd_lab_email_section' ) ;
    add_settings_section ( 'doifd_lab_widget_style_section' , 'Widget Style Settings' , '' , 'doifd_lab' ) ;
    add_settings_field ( 'doifd_lab_widget_style_width' , 'Width of Widget' , 'doifd_lab_setting_widget_width' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    add_settings_field ( 'doifd_lab_widget_style_inside_padding' , 'Widget Inside Padding' , 'doifd_lab_setting_widget_inside_padding' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    add_settings_field ( 'doifd_lab_widget_style_margin_top' , 'Widget Margin Top' , 'doifd_lab_setting_widget_margin_top' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    add_settings_field ( 'doifd_lab_widget_style_margin_right' , 'Widget Margin Right' , 'doifd_lab_setting_widget_margin_right' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    add_settings_field ( 'doifd_lab_widget_style_margin_bottom' , 'Widget Margin Bottom' , 'doifd_lab_setting_widget_margin_bottom' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    add_settings_field ( 'doifd_lab_widget_style_margin_left' , 'Widget Margin Left' , 'doifd_lab_setting_widget_margin_left' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    add_settings_field ( 'doifd_lab_widget_style_input_width' , 'Widget Input Width' , 'doifd_lab_setting_widget_input_width' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    register_setting ( 'doifd_lab_recaptcha_options' , 'doifd_lab_recaptcha_options' , 'doifd_lab_validate_recaptcha_options' ) ;
    add_settings_section ( 'doifd_lab_recaptcha_section' , 'reCaptcha Settings' , '' , 'doifd_lab_recaptcha' ) ;
    add_settings_field ( 'doifd_lab_recaptcha_public_key' , 'Enter Your reCaptcha Public Key' , 'doifd_lab_setting_recaptcha_public_key' , 'doifd_lab_recaptcha' , 'doifd_lab_recaptcha_section' ) ;
    add_settings_field ( 'doifd_lab_recaptcha_private_key' , 'Enter Your reCaptcha Private Key' , 'doifd_lab_setting_recaptcha_private_key' , 'doifd_lab_recaptcha' , 'doifd_lab_recaptcha_section' ) ;
    
}

add_action ( 'admin_menu' , 'register_doifd_custom_menu_page' ) ;

function register_doifd_custom_menu_page() {
    // create main menu page
    add_menu_page ( 'doifd menu title' , 'DOI - Downloads' , 'manage_options' , __FILE__ , 'doifd_lab_options_page' ) ;

    //create sub menu page for downloads
    add_submenu_page ( __FILE__ , 'Settings' , 'Settings' , 'manage_options' , __FILE__ , 'doifd_lab_options_page' ) ;

    //create sub menu page for downloads
    add_submenu_page ( __FILE__ , 'doifd downloads' , 'Downloads' , 'manage_options' , __FILE__ . '_downloads' , 'doifd_download_page' ) ;

    //create sub menu page for subscribers
    add_submenu_page ( __FILE__ , 'doifd subscribers' , 'Subscribers' , 'manage_options' , __FILE__ . '_subscribers' , 'doifd_lab_subscribers_page' ) ;

    //create sub menu page for editing downloads
    add_submenu_page ( __FILE__ , 'doifd edit downloads' , '' , 'manage_options' , __FILE__ . '_edit_downloads' , 'doifd_lab_edit_downloads_page' ) ;
    
    //create sub menu page for subscribers
    add_submenu_page ( __FILE__ , 'doifd recaptcha' , 'reCaptcha' , 'manage_options' , __FILE__ . '_recaptcha' , 'doifd_lab_recaptcha_page' ) ;

}

function doifd_download_page() {
    
     if ( ! current_user_can ( 'manage_options' ) ) {
    
        wp_die ( __ ( 'You do not have sufficient permissions to access this page.' ) ) ;
        
        } else {
    
            return DoifdDownloads::admin_download_page();
    
        }
    
    }

    function doifd_lab_edit_downloads_page() {
        
         if ( ! current_user_can ( 'manage_options' ) ) {
    
        wp_die ( __ ( 'You do not have sufficient permissions to access this page.' ) ) ;
        
        } else {
    
            return DoifdDownloads::edit_download_page();
            
        }
        
    }

add_action ( 'admin_init' , 'doifd_edit_download' ) ;

    function doifd_edit_download() {
        
        $process = new DoifdDownloads;
       
        $process->edit_download();
        
        }

// This is the contents fo the Subscribers Page

        function doifd_lab_subscribers_page() {
            
            if ( ! current_user_can ( 'manage_options' ) ) {
                
                wp_die ( __ ( 'You do not have sufficient permissions to access this page.' ) ) ;
                
            }

            return DoifdSubscribers::admin_subscriber_page();
            
        }

// This will be the contests of the Settings or options page
        function doifd_lab_options_page() {

            global $wpdb ;

            // check to see if the user has the privileges to access the options page.
            if ( ! current_user_can ( 'manage_options' ) ) {
                wp_die ( __ ( 'You do not have sufficient permissions to access this page.' ) ) ;
            }
            ?>

            <!--Begin HTML markup-->
            <div class="wrap">
                <div id="icon-options-general" class="icon32"></div>
                <h2>Settings</h2>

                <?php include DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'includes/doifd-admin-header.php' ; ?>
                
                <!--Save Options Button-->

                <form action="options.php" method="post">
                    <?php
                    settings_fields ( 'doifd_lab_options' ) ;
                    do_settings_sections ( 'doifd_lab' ) ;
                    ?>
                    <input class='button-primary' name="Submit" type="submit" value="Save Changes">
                </form>

            </div> <!--Wrap End--> 

            <?php
        }
        
function doifd_lab_recaptcha_page() {
            
    // check to see if the user has the privileges to access this options page.
    
    if ( ! current_user_can ( 'manage_options' ) ) {
    
        wp_die ( __ ( 'You do not have sufficient permissions to access this page.' ) ) ;
        
        }else{
           
            return DoifdCaptcha::reCaptcha_admin_options_page();
            
            }
}
// Create the reCaptcha public key field in the admin section

function doifd_lab_setting_recaptcha_public_key() {
    
    return DoifdCaptcha::recaptcha_public_key_field();
    
    }
    
// Create the reCaptcha private key field in the admin section
    
function doifd_lab_setting_recaptcha_private_key() {
            
    return DoifdCaptcha::recaptcha_private_key_field();

    }

// Sanitize and validate reCaptcha API keys
    
function doifd_lab_validate_recaptcha_options( $input ) {
    
    return DoifdCaptcha::reCaptcha_validate($input);

}
        
// set maximum number of downloads
        function doifd_lab_setting_input() {


            // get options from options table
            $options = get_option ( 'doifd_lab_options' ) ;

            // get maximum number of downloads and assign to variable
            $downloads_allowed = $options['downloads_allowed'] ;

            echo '<div id="doifd_lab_admin_options">' ;
            echo '<select name="doifd_lab_options[downloads_allowed]" id="downloads_allowed">' ;
            echo "<option value='{$options['downloads_allowed']}'>" ;
            echo esc_attr ( __ ( 'Select Maximum Downloads' ) ) ;
            echo '</option>' ;
            echo '<option value="1" ' . (($downloads_allowed == 1 ) ? 'selected="selected"' : "") . '>1</option>' ;
            echo '<option value="2" ' . (($downloads_allowed == 2 ) ? 'selected="selected"' : "") . '>2</option>' ;
            echo '<option value="3" ' . (($downloads_allowed == 3 ) ? 'selected="selected"' : "") . '>3</option>' ;
            echo '<option value="4" ' . (($downloads_allowed == 4 ) ? 'selected="selected"' : "") . '>4</option>' ;
            echo '<option value="5" ' . (($downloads_allowed == 5 ) ? 'selected="selected"' : "") . '>5</option>' ;
            echo '<option value="6" ' . (($downloads_allowed == 6 ) ? 'selected="selected"' : "") . '>6</option>' ;
            echo '<option value="7" ' . (($downloads_allowed == 7 ) ? 'selected="selected"' : "") . '>7</option>' ;
            echo '<option value="8" ' . (($downloads_allowed == 8 ) ? 'selected="selected"' : "") . '>8</option>' ;
            echo '<option value="9" ' . (($downloads_allowed == 9 ) ? 'selected="selected"' : "") . '>9</option>' ;
            echo '<option value="10" ' . (($downloads_allowed == 10 ) ? 'selected="selected"' : "") . '>10</option>' ;
            echo '</select>' ;
            _e ( '<p>Select the maximum number of times a subscriber can download an item. The default is <b>1</b>.' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

// Landing page dropdown select
        function doifd_lab_setting_option() {

            // get options from options table
            $options = get_option ( 'doifd_lab_options' ) ;

            // assign landing page option to variable
            $landing_page = $options['landing_page'] ;

            // echo dropdown
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<select name="doifd_lab_options[landing_page]" id="landing_page">' ;
            echo "<option value='{$options['landing_page']}'>" ;
            echo esc_attr ( __ ( 'Select Landing Page' ) ) ;
            echo '</option>' ;
            $pages = get_pages () ;
            foreach ( $pages as $page ) {
                $option = '<option value="' . $page->ID . '" ' . (($landing_page == $page->ID ) ? 'selected="selected"' : "") . '>' ;
                $option .= $page->post_title ;
                $option .= '</option>' ;
                echo $option ;
            }
            echo '</select>' ;
            _e ( '<p>Select the landing page for your subscribers. This will be the page your subscribers will come to after they have clicked the link in their verification email. Once you have selected your landing page, place this shortcode <b>[lab_landing_page]</b> on that page.</p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

//Add user to wordpress user table radio select
        function doifd_lab_add_to_wp_user_table() {

            // get options from options table
            $options = get_option ( 'doifd_lab_options' ) ;

            // assign add_to_wpusers option to variable
            $add_to_wp_user = $options['add_to_wpusers'] ;

            echo '<input type="radio" id="add_to_wpusers" name="doifd_lab_options[add_to_wpusers]" ' . ((isset ( $add_to_wp_user ) && ($add_to_wp_user) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ' ;
            echo '<input type="radio" id="add_to_wpusers" name="doifd_lab_options[add_to_wpusers]" ' . (isset ( $add_to_wp_user ) && ($add_to_wp_user == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ' ;
            _e ( '<p>If you want to add the subscribers to the wordress user table, check yes. Otherwise they will only be added to the plugins subscriber table.</p>' , 'Double-Opt-In-For-Download' ) ;
        }

//Add promo link to forms
        function doifd_lab_add_promo_link() {

            // get options from options table
            $options = get_option ( 'doifd_lab_options' ) ;

            // assign add_to_wpusers option to variable
            if ( isset ( $options['promo_link'] ) ) {
                $add_promo_link = $options['promo_link'] ;
            }

            echo '<input type="radio" id="promo_link" name="doifd_lab_options[promo_link]" ' . ((isset ( $add_promo_link ) && ( $add_promo_link ) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ' ;
            echo '<input type="radio" id="promo_link" name="doifd_lab_options[promo_link]" ' . (isset ( $add_promo_link ) && ( $add_promo_link == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ' ;
            _e ( '<p>If you check "YES", this will add a small promotional link at the bottom of the registration forms.</p>' , 'Double-Opt-In-For-Download' ) ;
        }

//Validate User Input
        function doifd_lab_validate_options( $input ) {

            $valid = array ( ) ;
            $valid['landing_page'] = preg_replace ( '/[^0-9]/' , '' , $input['landing_page'] ) ;
            $valid['downloads_allowed'] = preg_replace ( '/[^0-9]/' , '' , $input['downloads_allowed'] ) ;
            $valid['email_name'] = preg_replace ( '/[^ \w]+/' , '' , $input['email_name'] ) ;
            $valid['from_email'] = $input['from_email'] ;
            $valid['email_message'] = $input['email_message'] ;
            $valid['add_to_wpusers'] = preg_replace ( '/[^0-9]/' , '' , $input['add_to_wpusers'] ) ;
            $valid['promo_link'] = preg_replace ( '/[^0-9]/' , '' , $input['promo_link'] ) ;
            $valid['widget_width'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_width'] ) ;
            $valid['widget_inside_padding'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_inside_padding'] ) ;
            $valid['widget_margin_top'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_margin_top'] ) ;
            $valid['widget_margin_right'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_margin_right'] ) ;
            $valid['widget_margin_bottom'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_margin_bottom'] ) ;
            $valid['widget_margin_left'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_margin_left'] ) ;
            $valid['widget_input_width'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_input_width'] ) ;
            return $valid ;
        }

        function doifd_lab_setting_from_email() {

// Email options form
            // get options from options table
            $email_options = get_option ( 'doifd_lab_options' ) ;

            // get from email and assign to variable
            $from_email = $email_options['from_email'] ;

            // echo email form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[from_email]" id="from_email" value="' . $from_email . '">' ;
            _e ( '<p>This is the email address that shows in the <b>From</b> field in the verification email. If this is left blank it will default to the admin email address</p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_email_name() {

            // get options from options table
            $email_options = get_option ( 'doifd_lab_options' ) ;

            // get email name from options table and assign to variable
            $email_name = $email_options['email_name'] ;

            // show email name input field
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[email_name]" id="email_name" value="' . $email_name . '">' ;
            _e ( '<p>This is the <b>Name</b> that will show in the <b>From</b> field in the verification email. If this is left blank it will default to your website/blog name.</p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

// Email message for verification email
        function doifd_lab_setting_email_message() {

            // get options from options table
            $email_options = get_option ( 'doifd_lab_options' ) ;

            // get email message from options table and assign to variable
            $email_message = $email_options['email_message'] ;

            // show email message textarea
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<textarea rows="10" cols="60" name="doifd_lab_options[email_message]" id="email_message">' . $email_message . '</textarea>' ;
            _e ( '<p>This is the verification email that is sent to a new subscriber. Just remember, at the very least, you need to keep the <b>{URL}</b> in your email, as this provides the subscriber with the verification link. See the complete list below.<br />
                     <b>{subscriber} = Subscribers Name<br />
                     {url} = Verification Link<br />
                     {download} = The name of the download the subscriber has selected</b><br />' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_widget_width() {

// Widget Width
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_width'] ) ) {
                $widget_width = $doifd_option['widget_width'] ;
            }
            else {
                $widget_width = '190' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_width]" id="widget_width"  size="4" value="' . $widget_width . '">' ;
            _e ( '<p>This is the width of the widget in the sidebar. <b>Use numbers only, DO NOT ADD the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_widget_inside_padding() {

// Widget Inside Padding
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_inside_padding'] ) ) {
                $widget_inside_padding = $doifd_option['widget_inside_padding'] ;
            }
            else {
                $widget_inside_padding = '5' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_inside_padding]" id="widget_inside_padding" size="4" value="' . $widget_inside_padding . '">' ;
            _e ( '<p>This is the amount of padding used inside of the widget form. <b>Use numbers only, DO NOT ADD the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_widget_margin_top() {

// Widget Margin Top
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_margin_top'] ) ) {
                $widget_margin_top = $doifd_option['widget_margin_top'] ;
            }
            else {
                $widget_margin_top = '25' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_margin_top]" id="widget_margin_top" size="4" value="' . $widget_margin_top . '">' ;
            _e ( '<p>This is the top margin of the widget. <b>Use numbers only, DO NOT add the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_widget_margin_right() {

// Widget Margin Right
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_margin_right'] ) ) {
                $widget_margin_right = $doifd_option['widget_margin_right'] ;
            }
            else {
                $widget_margin_right = '0' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_margin_right]" id="widget_margin_right" size="4" value="' . $widget_margin_right . '">' ;
            _e ( '<p>This is the right margin of the widget. <b>Use numbers only, DO NOT add the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_widget_margin_bottom() {

// Widget Margin Bottom
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_margin_bottom'] ) ) {
                $widget_margin_bottom = $doifd_option['widget_margin_bottom'] ;
            }
            else {
                $widget_margin_bottom = '25' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_margin_bottom]" id="widget_margin_bottom" size="4" value="' . $widget_margin_bottom . '">' ;
            _e ( '<p>This is the bottom margin of the widget. <b>Use numbers only, DO NOT add the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_widget_margin_left() {

// Widget Margin Left
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_margin_left'] ) ) {
                $widget_margin_left = $doifd_option['widget_margin_left'] ;
            }
            else {
                $widget_margin_left = '0' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_margin_left]" id="widget_margine_left"  size="4" value="' . $widget_margin_left . '">' ;
            _e ( '<p>This is the left margin of the widget. <b>Use numbers only, DO NOT add the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }
        
                function doifd_lab_setting_widget_input_width() {

// Widget Margin Top
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_input_width'] ) ) {
                $widget_input_width = $doifd_option['widget_input_width'] ;
            }
            else {
                $widget_input_width = '180' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_input_width]" id="widget_input_width" size="4" value="' . $widget_input_width . '">' ;
            _e ( '<p>This sets the width of the input field on the widget. <b>Use numbers only, DO NOT add the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

// It's time to upload the download file

        add_action ( 'admin_init' , 'doifd_lab_upload_download' ) ;

        function doifd_lab_upload_download() {

            $process_upload = new DoifdDownloads();
            $process_upload->upload_file();
            
        }

// Create CSV file for download
        add_action ( 'admin_init' , 'doifd_lab_create_csv_file_of_subscribers' ) ;

        function doifd_lab_create_csv_file_of_subscribers() {

            $process = new DoifdCVS;
            $process->csv_download();
            
        }

// Function to resend the verification email
        add_action ( 'admin_init' , 'doifd_lab_resend_verification_email' ) ;

        function doifd_lab_resend_verification_email() {

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

        function doifd_lab_dashboard_widget_function() {

            global $wpdb ;

            // get the total count of subscribers
            $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . "doifd_lab_subscribers " ;
            $doifd_subscriber_count = $wpdb->get_var ( $sql ) ;

            // get the total count of downloads
            $sql = "SELECT SUM(doifd_number_of_downloads) FROM " . $wpdb->prefix . "doifd_lab_downloads " ;
            $doifd_download_count = $wpdb->get_var ( $sql ) ;

            // get downloads
            $sql = "SELECT * FROM " . $wpdb->prefix . "doifd_lab_downloads " ;
            $doifd_downloads_result = $wpdb->get_results ( $sql , ARRAY_A ) ;

            // display a mini download table with subscriber and download counts
            echo '<table class="doifd_admin_widget_table">' ;
            echo '<tr>' ;
            echo '<th class="doifd_admin_widget_th">Total Subscribers: ' . $doifd_subscriber_count . '</th>' ;
            echo '<th class="doifd_admin_widget_th">Overall Total Downloads: ' . $doifd_download_count . '</th>' ;
            echo '</tr>' ;
            foreach ( $doifd_downloads_result as $value ) {
                echo '<tr>' ;
                echo "<td class='doifd_admin_widget_td'>" . $value['doifd_download_name'] . "</td>" ;
                echo "<td class='doifd_admin_widget_td'>" . $value['doifd_number_of_downloads'] . "</td>" ;
                echo '</tr>' ;
            }
            echo '</table>' ;
        }

        function doifd_lab_add_dashboard_widgets() {
            wp_add_dashboard_widget ( 'doifd_dashboard_widget' , 'Double Opt-In For Downloads' , 'doifd_lab_dashboard_widget_function' ) ;
        }

// Hook into the 'wp_dashboard_setup' action to register our other functions

        add_action ( 'wp_dashboard_setup' , 'doifd_lab_add_dashboard_widgets' ) ;
        ?>