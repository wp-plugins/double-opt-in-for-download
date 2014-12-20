<?php

class DOIFDAdminGeneralOptionsFields extends DOIFDAdmin {

    public function __construct() {
        parent::__construct ();

        add_action ( 'admin_init', array( $this, 'register_doifd_admin_options' ) );
        add_action ( 'admin_init', array( $this, 'doifd_general_settings' ) );
        add_action ( 'admin_init', array( $this, 'doifd_privacy_settings' ) );
    }

    public function doifd_general_settings() {

        add_settings_section (
                'doifd_lab_main', __ ( 'General Settings', $this->plugin_slug ), '', 'doifd_lab_general' );

        add_settings_field (
                'doifd_lab_downloads_allowed', __ ( 'Set Download Limit', $this->plugin_slug ), array( $this, 'allowed_downloads' ), 'doifd_lab_general', 'doifd_lab_main' );

        add_settings_field (
                'doifd_lab_add_to_wpusers', __ ( 'Add Subscribers to the Wordpress User Table?', $this->plugin_slug ), array( $this, 'add_to_user_table' ), 'doifd_lab_general', 'doifd_lab_main' );

        add_settings_field (
                'doifd_lab_form_security', __ ( 'Disable Form Security?', $this->plugin_slug ), array( $this, 'form_security' ), 'doifd_lab_general', 'doifd_lab_main' );

        add_settings_field (
                'doifd_lab_promo_link', __ ( 'Help Us Out?', $this->plugin_slug ), array( $this, 'add_promo_link' ), 'doifd_lab_general', 'doifd_lab_main' );
    }
    
     public function register_doifd_admin_options() {
    
        register_setting(
                'doifd_lab_options', 'doifd_lab_options', array( new DOIFDAdminValidation, 'admin_options_validation' )
        );
    }

    public function allowed_downloads() {

        $downloads_allowed = $this->doifd_options[ 'downloads_allowed' ];
        
        echo '<div class="doifd_options_fields">';
        echo '<select name="doifd_lab_options[downloads_allowed]" id="downloads_allowed">';
        echo "<option value='{$this->doifd_options[ 'downloads_allowed' ]}'>";
        echo esc_attr ( __ ( 'Select Maximum Downloads', $this->plugin_slug ) );
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
        echo '<p>' . __ ( 'Select the maximum number of times a subscriber can download an item. The default is <b>1</b>.', $this->plugin_slug ) . '</p>';
        echo '</div>';
    }

    public function add_to_user_table() {

        if ( isset ( $this->doifd_options[ 'add_to_wpusers' ] ) ) {

            $add_to_wp_user = $this->doifd_options[ 'add_to_wpusers' ];
        } else {

            $add_to_wp_user = '0';
        }

        /* Echo radio select button */
        echo '<div class="doifd_options_fields">';
        echo '<input type="radio" id="add_to_wpusers" name="doifd_lab_options[add_to_wpusers]" ' . ((isset ( $add_to_wp_user ) && ($add_to_wp_user) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ';
        echo '<input type="radio" id="add_to_wpusers" name="doifd_lab_options[add_to_wpusers]" ' . (isset ( $add_to_wp_user ) && ($add_to_wp_user == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ';
        echo '<p>' . __ ( 'Select YES to automatically add subscriber as a Subscriber to your Website/Blog', $this->plugin_slug ) . '</p>';
        echo '</div>';
    }

    public function form_security() {

        if ( isset ( $this->doifd_options[ 'form_security' ] ) ) {

            $security = $this->doifd_options[ 'form_security' ];
        } else {

            $security = '0';
        }

        /* Echo radio select button */
        echo '<div class="doifd_options_fields">';
        echo '<input type="radio" id="form_security" name="doifd_lab_options[form_security]" ' . ((isset ( $security ) && ($security) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ';
        echo '<input type="radio" id="form_security" name="doifd_lab_options[form_security]" ' . (isset ( $security ) && ($security == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ';
        echo '<p>' . __ ( 'DOIFD uses wordpress nonce security to protect your from from malicious attacks. Click YES to disable. (Not Recommended)', $this->plugin_slug ) . '</p>';
        echo '</div>';
    }

    public function add_promo_link() {

        if ( isset ( $this->doifd_options[ 'promo_link' ] ) ) {

            $add_promo_link = $this->doifd_options[ 'promo_link' ];
        } else {

            $add_promo_link = '0';
        }

        /* Echo radio select button */
        echo '<div class="doifd_options_fields">';
        echo '<input type="radio" id="promo_link" name="doifd_lab_options[promo_link]" ' . ((isset ( $add_promo_link ) && ( $add_promo_link ) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ';
        echo '<input type="radio" id="promo_link" name="doifd_lab_options[promo_link]" ' . (isset ( $add_promo_link ) && ( $add_promo_link == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ';
        echo '<p>' . __ ( 'Click YES to add a small promotional link at the bottom of the forms.', $this->plugin_slug ) . '</p>';
        echo '</div>';
    }

    public function doifd_privacy_settings() {

        add_settings_section (
                'doifd_lab_form_privacy_section', __ ( 'Privacy Policy Settings', $this->plugin_slug ), '', 'doifd_lab_form_privacy' );

        add_settings_field (
                'doifd_lab_form_privacy_policy', __ ( 'Use Privacy Policy', $this->plugin_slug ), array( new DOIFDAdminGeneralOptionsFields, 'use_privacy_policy' ), 'doifd_lab_form_privacy', 'doifd_lab_form_privacy_section' );

        add_settings_field (
                'doifd_lab_form_privacy_text', '', array( new DOIFDAdminGeneralOptionsFields, 'field_privacy_link_text' ), 'doifd_lab_form_privacy', 'doifd_lab_form_privacy_section' );

        add_settings_field (
                'doifd_lab_form_privacy_font_size', '', array( new DOIFDAdminGeneralOptionsFields, 'field_privacy_link_text_size' ), 'doifd_lab_form_privacy', 'doifd_lab_form_privacy_section' );

        add_settings_field (
                'doifd_lab_form_privacy_page', '', array( new DOIFDAdminGeneralOptionsFields, 'field_select_privacy_page' ), 'doifd_lab_form_privacy', 'doifd_lab_form_privacy_section' );
    }

    public function use_privacy_policy() {

        if ( isset ( $this->doifd_options[ 'use_privacy_policy' ] ) ) {

            $use_privacy_policy = $this->doifd_options[ 'use_privacy_policy' ];
        } else {

            $use_privacy_policy = '0';
        }

        /* Echo radio select button */
        echo '<div class="doifd_options_fields">';
        echo '<input type="radio" id="use_privacy_policy_y" name="doifd_lab_options[use_privacy_policy]" ' . ((isset ( $use_privacy_policy ) && ( $use_privacy_policy ) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ';
        echo '<input type="radio" id="use_privacy_policy_n" name="doifd_lab_options[use_privacy_policy]" ' . (isset ( $use_privacy_policy ) && ( $use_privacy_policy == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ';
        echo '<p>' . __ ( 'Click YES to show a link to your Privacy Policy Page on the bottom of the forms.', $this->plugin_slug ) . '</p>';
        echo '</div>';
    }

    public function field_privacy_link_text() {

        if ( isset ( $this->doifd_options[ 'privacy_link_text' ] ) && ($this->doifd_options[ 'privacy_link_text' ] == ! NULL ) ) {

            $form_privacy_link_text = $this->doifd_options[ 'privacy_link_text' ];
        } else {

            $form_privacy_link_text = '';
        }

        /* echo privacy text form field */
        echo '<div id="priv_text" class="doifd_options_fields">';
        echo '<label><b>Privacy Page Link Text:</b> </label>';
        echo '<input type="text" name="doifd_lab_options[privacy_link_text]" id="form_privacy_link_text" size="25" value="' . $form_privacy_link_text . '">';
        echo '<p>' . __ ( '( Example: See Our Privacy Policy )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_privacy_link_text_size() {

        if ( isset ( $this->doifd_options[ 'privacy_link_font_size' ] ) && ($this->doifd_options[ 'privacy_link_font_size' ] == ! NULL ) ) {

            $form_privacy_link_font_size = $this->doifd_options[ 'privacy_link_font_size' ];
        } else {

            $form_privacy_link_font_size = '0.9em';
        }

        /* echo form privacy link text size */

        echo '<div id="priv_font_size" class="doifd_options_fields">';
        echo '<label><b>' . __ ( 'Link font size:', $this->plugin_slug ) . '</b> </label>';
        echo '<input type="text" name="doifd_lab_options[privacy_link_font_size]" id="form_padding" size="5" value="' . $form_privacy_link_font_size . '">';
        echo '<p>' . __ ( '(Example: 0.9em or 8px etc.)', $this->plugin_slug ) . '</p>';
        echo '</div>';
    }

    public function field_select_privacy_page() {

        $privacy_page = $this->doifd_options[ 'privacy_page' ];

        /* Echo drop down select menu */

        echo '<div id="priv_sel_pag" class="doifd_options_fields">';
        echo '<label><b>Select Privacy Page:</b> </label>';
        echo '<select name="doifd_lab_options[privacy_page]" id="privacy_page">';
        echo "<option value='{$this->doifd_options[ 'privacy_page' ]}'>";
        echo esc_attr ( __ ( 'Select Privacy Policy Page', $this->plugin_slug ) );
        echo '</option>';
        $pages = get_pages ();
        foreach ( $pages as $page ) {
            $option = '<option value="' . $page->ID . '" ' . (($privacy_page == $page->ID ) ? 'selected="selected"' : "") . '>';
            $option .= $page->post_title;
            $option .= '</option>';
            echo $option;
        }
        echo '</select>';
        echo '</div>';
    }

    public function general_settings_tabs() {

        $tabs = array(
            "tabs-1" => __ ( 'General Settings', $this->plugin_slug ),
            "tabs-2" => __ ( 'Email Settings', $this->plugin_slug ),
            "tabs-3" => __ ( 'Captcha Settings', $this->plugin_slug ),
            "tabs-4" => __ ( 'Default Form Settings', $this->plugin_slug ),
            "tabs-5" => __ ( 'Default Widget Settings', $this->plugin_slug ),
            "tabs-6" => __ ( 'Custom CSS', $this->plugin_slug ),
            "tabs-7" => __ ( 'Mailchimp Settings', $this->plugin_slug ),
            "tabs-8" => __ ( 'Constant Contact Settings', $this->plugin_slug )
        );

        return apply_filters ( 'premium_option_tabs', $tabs );
    }

    public function general_settings_content() {

        $tabs_content = array(
            "tabs-1" => array( "doifd_lab_options" => "doifd_lab_general", "doifd_lab_form_privacy" ),
            "tabs-2" => 'doifd_lab_email',
            "tabs-3" => 'doifd_lab_captcha',
            "tabs-4" => array( "doifd_lab_options" => "doifd_lab_form_style" ),
            "tabs-5" => 'doifd_lab_widget_style',
            "tabs-6" => array( "doifd_lab_options" => "doifd_lab_form_custom_css", 'doifd_lab_widget_custom_css' ),
            "tabs-7" => array( "doifd_mailchimp_options" => "doifd_mailchimp" ),
            "tabs-8" => array( "doifd_constantcontact_options" => "doifd_constantcontact" )
        );

        return apply_filters ( 'premium_options_content', $tabs_content );
    }

    public function get_mailchimp() {

        if ( class_exists ( 'DOIFDPremiumAdmin' ) ) {

            $mc = new DOIFDMailChimpSettings();
            $mc->mailchimp_api_key ();
        } else {

            ?>
            <div class="premium_promo">
                <p><?php _e ( 'The Mailchimp feature is only available<br>in the Premium Version', $this->plugin_slug ); ?></p>
                <a href="http://www.doubleoptinfordownload.com/premium-double-opt-in-for-download/" target="new" class="premium_promo_button" ><?php _e ( 'Click Here To Purchase Premium DOIFD', $this->plugin_slug ); ?></a></p>
            </div>
            <?php
        }
    }

    public function mailchimp_validate_options( $input ) {

        $valid = array(
        );

        $valid[ 'mailchimp_api_key' ] = preg_replace ( '/[^-a-z0-9]/', '', $input[ 'mailchimp_api_key' ] );

        return $valid;
    }

}
new DOIFDAdminGeneralOptionsFields;