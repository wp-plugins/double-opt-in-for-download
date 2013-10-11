<?php

if ( !class_exists ( 'DoifdAdminOptions' ) ) {

    class DoifdAdminOptions {

        public function __construct() {
            
        }

       public static function options_page() {

            global $wpdb;
            ?>

            <!--Begin HTML markup-->

            <div class="wrap">

                <div id="icon-options-general" class="icon32"></div>

                <h2>Settings</h2>

                <?php include DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'views/view-admin-header.php'; ?>

<form action="options.php" method="post">
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">General Settings</a></li>
                        <li><a href="#tabs-2">Email Settings</a></li>
                        <li><a href="#tabs-3">Captcha Settings</a></li>
                        <li><a href="#tabs-4">Form Style Settings</a></li>
                        <li><a href="#tabs-5">Widget Style Settings</a></li>
                        <li><a href="#tabs-6">MailChimp Settings</a></li>
                        <li><a href="#tabs-7">Constant Contact Settings</a></li>
                    </ul>
                    <div id="tabs-1">
                        
                            <?php
                            settings_fields('doifd_lab_options');

                            do_settings_sections('doifd_lab_general');
                            
                            do_settings_sections('doifd_lab_form_privacy');
                            ?>
                        
                            <input class='button-primary' name="Submit" type="submit" value="<?php _e('Save Changes', 'double-opt-in-for-download'); ?>">


                    </div>
                    <div id="tabs-2">

                            <?php
                            settings_fields('doifd_lab_options');

                            do_settings_sections('doifd_lab_email');
                            ?>
                            <input class='button-primary' name="Submit" type="submit" value="<?php _e('Save Changes', 'double-opt-in-for-download'); ?>">


                    </div>
                    <div id="tabs-3">
                        <h1><a href="http://www.labwebdesigns.com/premium-double-opt-in-for-download.html" target="new" ><?php _e( 'Available in the Premium Edition', 'double-opt-in-for-download' ); ?></a></h1>
                    </div>
                    <div id="tabs-4">
                        <?php
                            settings_fields('doifd_lab_options');
                            
                            do_settings_sections('doifd_lab_form_style');
                            ?>
                            <input class='button-primary' name="Submit" type="submit" value="<?php _e('Save Changes', 'double-opt-in-for-download'); ?>">
                    </div>
                    <div id="tabs-5">

                            <?php
                            settings_fields('doifd_lab_options');

                            do_settings_sections('doifd_lab_widget_style');
                            ?>
                            <input class='button-primary' name="Submit" type="submit" value="<?php _e('Save Changes', 'double-opt-in-for-download'); ?>">

                        
                    </div>
                    <div id="tabs-6">
                         <h1><a href="http://www.labwebdesigns.com/premium-double-opt-in-for-download.html" target="new" ><?php _e( 'Available in the Premium Edition', 'double-opt-in-for-download' ); ?></a></h1>
                    </div>
                    <div id="tabs-7">
                         <h1><a href="http://www.labwebdesigns.com/premium-double-opt-in-for-download.html" target="new" ><?php _e( 'Available in the Premium Edition', 'double-opt-in-for-download' ); ?></a></h1>
                    </div>
                </div>
</form>
            </div> <!--Wrap End--> 

            <?php
        }
        
        public static function allowed_downloads() {
            
            /* Get options from options table */

            $options = get_option ( 'doifd_lab_options' );

            /* Get maximum number of downloads and assign to variable */

            $downloads_allowed = $options[ 'downloads_allowed' ];

            echo '<div id="doifd_lab_admin_options">';
            echo '<select name="doifd_lab_options[downloads_allowed]" id="downloads_allowed">';
            echo "<option value='{$options[ 'downloads_allowed' ]}'>";
            echo esc_attr ( __ ( 'Select Maximum Downloads' ) );
            echo '</option>';
            echo '<option value="1" ' . (($downloads_allowed == 1 ) ? 'selected="selected"' : "") . '>1</option>';
            echo '<option value="2" ' . (($downloads_allowed == 2 ) ? 'selected="selected"' : "") . '>2</option>';
            echo '<option value="3" ' . (($downloads_allowed == 3 ) ? 'selected="selected"' : "") . '>3</option>';
            echo '<option value="4" ' . (($downloads_allowed == 4 ) ? 'selected="selected"' : "") . '>4</option>';
            echo '<option value="5" ' . (($downloads_allowed == 5 ) ? 'selected="selected"' : "") . '>5</option>';
            echo '<option value="6" ' . (($downloads_allowed == 6 ) ? 'selected="selected"' : "") . '>6</option>';
            echo '<option value="7" ' . (($downloads_allowed == 7 ) ? 'selected="selected"' : "") . '>7</option>';
            echo '<option value="8" ' . (($downloads_allowed == 8 ) ? 'selected="selected"' : "") . '>8</option>';
            echo '<option value="9" ' . (($downloads_allowed == 9 ) ? 'selected="selected"' : "") . '>9</option>';
            echo '<option value="10" ' . (($downloads_allowed == 10 ) ? 'selected="selected"' : "") . '>10</option>';
            echo '</select>';
            echo '<p>' . __( 'Select the maximum number of times a subscriber can download an item. The default is <b>1</b>.', 'double-opt-in-for-download' ) . '</p>';
            echo '</div>';

        }

        public static function select_landing_page() {

            /* Get options from options table */

            $options = get_option ( 'doifd_lab_options' );

            /* Assign landing page option to variable */

            $landing_page = $options[ 'landing_page' ];

            /* Echo drop down select menu */

            echo '<div id="doifd_lab_admin_options">';
            echo '<select name="doifd_lab_options[landing_page]" id="landing_page">';
            echo "<option value='{$options[ 'landing_page' ]}'>";
            echo esc_attr ( __ ( 'Select Landing Page', 'double-opt-in-for-download' ) );
            echo '</option>';
            $pages = get_pages ();
            foreach ( $pages as $page ) {
                $option = '<option value="' . $page->ID . '" ' . (($landing_page == $page->ID ) ? 'selected="selected"' : "") . '>';
                $option .= $page->post_title;
                $option .= '</option>';
                echo $option;
            }
            echo '</select>';
            echo '<p>' . __( 'Select the landing page for your subscribers. This will be the page your subscribers will come to after they have clicked the link in their verification email. Once you have selected your landing page, place this shortcode <b>[lab_landing_page]</b> on that page. You can also change the button text by adding <b>button_text="My Special Text"</b> to the short code. The whole short code would look like this: <b>[lab_landing_page button_text="My Special Text"]</b>', 'double-opt-in-for-download' ) . '</p>';
            echo '</div>';

        }

        public static function add_to_user_table() {
            
            /* Get options from options table */

            $options = get_option ( 'doifd_lab_options' );

            /* Assign add_to_wpusers option to variable */

            $add_to_wp_user = $options[ 'add_to_wpusers' ];

            /* Echo radio select button */

            echo '<input type="radio" id="add_to_wpusers" name="doifd_lab_options[add_to_wpusers]" ' . ((isset ( $add_to_wp_user ) && ($add_to_wp_user) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ';
            echo '<input type="radio" id="add_to_wpusers" name="doifd_lab_options[add_to_wpusers]" ' . (isset ( $add_to_wp_user ) && ($add_to_wp_user == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ';
            echo '<p>' . __( 'If you want to add the subscribers to the wordress user table, check yes. Otherwise they will only be added to the plugins subscriber table.', 'double-opt-in-for-download' ) . '</p>';

        }

        public static function add_promo_link() {
            
            /* Get options from options table */

            $options = get_option ( 'doifd_lab_options' );

            /* Assign promo link option to variable */

            if ( isset ( $options[ 'promo_link' ] ) ) {

                $add_promo_link = $options[ 'promo_link' ];
            }

            /* Echo radio select button */

            echo '<input type="radio" id="promo_link" name="doifd_lab_options[promo_link]" ' . ((isset ( $add_promo_link ) && ( $add_promo_link ) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ';
            echo '<input type="radio" id="promo_link" name="doifd_lab_options[promo_link]" ' . (isset ( $add_promo_link ) && ( $add_promo_link == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ';
            echo '<p>' . __( 'If you check YES, this will add a small promotional link at the bottom of the registration forms.', 'double-opt-in-for-download' ) . '</p>';

        }
        
        public static function admin_download_notification() {

            /* Get options from options table */

            $options = get_option( 'doifd_lab_options' );

            /* Assign promo link option to variable */

            if ( isset( $options[ 'notification_email' ] ) ) {

                $notification_email = $options[ 'notification_email' ];
            } else {
                
                $notification_email = '0';
            }

            /* Echo radio select button */

            echo '<input type="radio" id="notification_email" name="doifd_lab_options[notification_email]" ' . ((isset( $notification_email ) && ( $notification_email ) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ';
            echo '<input type="radio" id="notification_email" name="doifd_lab_options[notification_email]" ' . (isset( $notification_email ) && ( $notification_email == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ';
            echo '<p>' . __( 'If you check YES, an email will be sent to you notifying you of the download.', 'double-opt-in-for-download' ) . '</p>';
        }

        /* This function creates the email address field */

        public static function from_email_address_field() {
            
            /* Get options from options table */

            $email_options = get_option ( 'doifd_lab_options' );

            /* Get from email and assign to variable */

            $from_email = $email_options[ 'from_email' ];

            /* Echo the from email address field */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[from_email]" id="from_email" value="' . $from_email . '">';
            echo '<p>' . __( 'This is the email address that shows in the <b>From</b> field in the verification email. If this is left blank it will default to the admin email address.', 'double-opt-in-for-download' ) . '</p>';
            echo '</div>';

        }

        /* This function creates the from email name field */

        public static function from_email_name_field() {
            
            /* Get options from options table */

            $email_options = get_option ( 'doifd_lab_options' );

            /* Get email name from options table and assign to variable */

            $email_name = $email_options[ 'email_name' ];

            /* Echo the email name input field */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[email_name]" id="email_name" value="' . $email_name . '">';
            echo '<p>' . __( 'This is the <b>Name</b> that will show in the <b>From</b> field in the verification email. If this is left blank it will default to your website or blog name.', 'double-opt-in-for-download' ) . '</p>';
            echo '</div>';

        }

        /* This function creates the email message text area field */

        public static function email_message_field() {
            
            /* Get options from options table */

            $email_options = get_option ( 'doifd_lab_options' );

            /* Get email message from options table and assign to variable */

            $email_message = $email_options[ 'email_message' ];

            /* Echo email message textarea */

            echo '<div id="doifd_lab_admin_options">';
            echo '<textarea rows="10" cols="60" name="doifd_lab_options[email_message]" id="email_message">' . $email_message . '</textarea>';
            echo '<p>' . __( 'This is the verification email that is sent to a new subscriber. Just remember, at the very least, you need to keep the <b>{URL}</b> in your email, as this provides the subscriber with the verification link. See the complete list below.', 'double-opt-in-for-download' ) . '<br />
        
            <b>{subscriber} = ' . __( 'Subscribers Name', 'double-opt-in-for-download' ) . '<br />
            {url} = ' . __( 'Verification Link', 'double-opt-in-for-download' ) . '<br />
            {download} = ' . __( 'The name of the download the subscriber has selected', 'double-opt-in-for-download' ). '</b><br />';

            echo '</div>';

        }
        
        public static function use_privacy_policy() {
            
            /* Get options from options table */

            $options = get_option ( 'doifd_lab_options' );

            /* Assign promo link option to variable */

            if ( isset ( $options[ 'use_privacy_policy' ] ) ) {

                $use_privacy_policy = $options[ 'use_privacy_policy' ];
            }

            /* Echo radio select button */

            echo '<input type="radio" id="use_privacy_policy_y" name="doifd_lab_options[use_privacy_policy]" ' . ((isset ( $use_privacy_policy ) && ( $use_privacy_policy ) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ';
            echo '<input type="radio" id="use_privacy_policy_n" name="doifd_lab_options[use_privacy_policy]" ' . (isset ( $use_privacy_policy ) && ( $use_privacy_policy == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ';
            echo '<p>' . __( 'If you check YES, this will add a link to your Privacy Policy Page at the bottom of the registration forms.', 'double-opt-in-for-download' ) . '</p>';

        }
        
        public static function field_privacy_link_text() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'privacy_link_text' ] ) && ($doifd_option[ 'privacy_link_text' ] == !NULL ) ) {

                $form_privacy_link_text = $doifd_option[ 'privacy_link_text' ];
            } else {

                $form_privacy_link_text = '';
            }

            /* echo privacy text form field */
            echo '<div id="priv_text">';
            echo '<div id="doifd_lab_admin_options">';
            echo '<label><b>Privacy Page Link Text:</b> </label>';
            echo '<input type="text" name="doifd_lab_options[privacy_link_text]" id="form_privacy_link_text" size="25" value="' . $form_privacy_link_text . '">';
            echo '<p>' . __( 'This sets the link text for your privacy statement ( Example: See Out Privacy Policy )',  'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';
            

        }
        
        public static function field_privacy_link_text_size() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'privacy_link_font_size' ] ) && ($doifd_option[ 'privacy_link_font_size' ] == !NULL ) ) {

                $form_privacy_link_font_size = $doifd_option[ 'privacy_link_font_size' ];
            } else {

                $form_privacy_link_font_size = '0.9em';
            }

            /* echo form privacy link text size */

            echo '<div id="priv_font_size">';
            echo '<div id="doifd_lab_admin_options">';
            echo '<label><b>Set Font Size of Link:</b> </label>';
            echo '<input type="text" name="doifd_lab_options[privacy_link_font_size]" id="form_padding" size="5" value="' . $form_privacy_link_font_size . '">';
            echo '<p>' . __( 'Set the font size of the privacy policy link (Example: 0.9em or 8px etc.)' , 'double-opt-in-for-download' ) . '</p>';
            echo '</div>';
            echo '</div>';

        }
        
        public static function field_select_privacy_page() {

            /* Get options from options table */

            $options = get_option ( 'doifd_lab_options' );

            /* Assign landing page option to variable */

            $privacy_page = $options[ 'privacy_page' ];

            /* Echo drop down select menu */

            echo '<div id="priv_sel_pag">';
            echo '<div id="doifd_lab_admin_options">';
            echo '<label><b>Select Privacy Page:</b> </label>';
            echo '<select name="doifd_lab_options[privacy_page]" id="privacy_page">';
            echo "<option value='{$options[ 'privacy_page' ]}'>";
            echo esc_attr ( __ ( 'Select Privacy Policy Page', 'double-opt-in-for-download' ) );
            echo '</option>';
            $pages = get_pages ();
            foreach ( $pages as $page ) {
                $option = '<option value="' . $page->ID . '" ' . (($privacy_page == $page->ID ) ? 'selected="selected"' : "") . '>';
                $option .= $page->post_title;
                $option .= '</option>';
                echo $option;
            }
            echo '</select>';
            echo '<p>' . __( 'Select your Privacy Policy Page.', 'double-opt-in-for-download' ) . '</p>';
            echo '</div>';
            echo '</div>';

        }

    }

}
?>