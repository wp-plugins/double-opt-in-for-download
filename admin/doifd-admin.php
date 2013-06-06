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
            
            if ( ! current_user_can ( 'manage_options' ) ) {
                
                wp_die ( __ ( 'You do not have sufficient permissions to access this page.' ) ) ;
                
            }

            return DoifdAdminOptions::options_page();
            
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

            return DoifdAdminOptions::allowed_downloads();
            
        }

// Landing page dropdown select
        function doifd_lab_setting_option() {

           return DoifdAdminOptions::select_landing_page();
           
        }

//Add user to wordpress user table radio select
        function doifd_lab_add_to_wp_user_table() {

            return DoifdAdminOptions::add_to_user_table ();
            
        }

//Add promo link to forms
        function doifd_lab_add_promo_link() {

            return DoifdAdminOptions::add_promo_link ();
            
        }

//Validate User Input
        function doifd_lab_validate_options( $input ) {

            $validate_options = new AdminValidation();
            return $validate_options->admin_options_validation( $input );
            
        }

        function doifd_lab_setting_from_email() {

            return DoifdAdminOptions::from_email_address_field ();
            
        }

        function doifd_lab_setting_email_name() {

            return DoifdAdminOptions::from_email_name_field ();
            
        }

// Email message for verification email
        function doifd_lab_setting_email_message() {

            return DoifdAdminOptions::email_message_field();
            
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

            $process = new DoifdCSV();
            $process->csv_download();
            
        }

// Function to resend the verification email
        add_action ( 'admin_init' , 'doifd_lab_resend_verification_email' ) ;

        function doifd_lab_resend_verification_email() {

            $resend_email = new DoifdEmail();
            return $resend_email->admin_resend_verification_email();
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