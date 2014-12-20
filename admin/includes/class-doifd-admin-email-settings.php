<?php

class DOIFDAdminEmailSettings extends DOIFDAdmin {

    public function __construct() {
        parent::__construct ();

        add_action ( 'admin_init', array( $this, 'admin_email_settings' ) );
    }

    public function admin_email_settings() {

        add_settings_section (
                'doifd_lab_email_section', __ ( 'Email Settings', $this->plugin_slug ), '', 'doifd_lab_email' );

        add_settings_field (
                'doifd_lab_notification_email', __ ( 'Notify Admin', $this->plugin_slug ), array( $this, 'admin_download_notification' ), 'doifd_lab_email', 'doifd_lab_email_section' );

        add_settings_field (
                'doifd_lab_from_email', __ ( 'Return Email Address', $this->plugin_slug ), array( $this, 'from_email_address_field' ), 'doifd_lab_email', 'doifd_lab_email_section' );

        add_settings_field (
                'doifd_lab_email_name', __ ( 'Email From', $this->plugin_slug ), array( $this, 'from_email_name_field' ), 'doifd_lab_email', 'doifd_lab_email_section' );

        apply_filters ( 'pre_html_option', add_settings_field (
                        'doifd_lab_send_html', __ ( 'Send HTML Verfication Email', $this->plugin_slug ), array( $this, 'html_email_option' ), 'doifd_lab_email', 'doifd_lab_email_section' ) );

        apply_filters ( 'pre_subject_option', add_settings_field (
                        'doifd_lab_email_subject', __ ( 'Email Subject Line', $this->plugin_slug ), array( $this, 'email_subject_option' ), 'doifd_lab_email', 'doifd_lab_email_section' ) );

        apply_filters ( 'pre_html_email', add_settings_field (
                        'doifd_lab_email_message', __ ( 'Verification Email:', $this->plugin_slug ), array( $this, 'email_message_field' ), 'doifd_lab_email', 'doifd_lab_email_section' ) );
    }

    public function admin_download_notification() {

        if ( ! empty ( $this->doifd_options[ 'notification_email' ] ) ) {

            $notification_email = $this->doifd_options[ 'notification_email' ];

            } else {

            $notification_email = '0';
        }

        echo '<input type="radio" id="notification_email" name="doifd_lab_options[notification_email]" ' . ((isset ( $notification_email ) && ( $notification_email ) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ';
        echo '<input type="radio" id="notification_email" name="doifd_lab_options[notification_email]" ' . (isset ( $notification_email ) && ( $notification_email == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ';
        echo '<p>' . __ ( 'Click YES for an email to be sent to the blog admin for each new download.', $this->plugin_slug ) . '</p>';
    }

    /* This function creates the email address field */

    public function from_email_address_field() {

        if ( ! empty ( $this->doifd_options[ 'from_email' ] ) ) {

            $from_email = $this->doifd_options[ 'from_email' ];

            } else {

            $from_email = '';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[from_email]" id="from_email" size="45" value="' . $from_email . '">';
        echo '<p>' . __ ( 'Default is Blog/Website admin email. ( Example: do-not-reply@mywebsite.com )', $this->plugin_slug ) . '</p>';
        echo '</div>';
    }

    /* This function creates the from email name field */

    public function from_email_name_field() {

        if ( ! empty ( $this->doifd_options[ 'email_name' ] ) ) {

            $email_name = $this->doifd_options[ 'email_name' ];

            } else {

            $email_name = '';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[email_name]" id="email_name" size="45" value="' . $email_name . '">';
        echo '<p>' . __ ( 'Default is Blog/Website name. ( Example: Your Product Name )', $this->plugin_slug ) . '</p>';
        echo '</div>';
    }

    /* This function creates the email message text area field */

    public function email_message_field() {

        $email_message = $this->doifd_options[ 'email_message' ];

        echo '<div class="doifd_options_fields">';
        echo '<textarea rows="10" cols="60" name="doifd_lab_options[email_message]" id="email_message">' . $email_message . '</textarea>';
        echo '<p>' . __ ( 'You must use all three of the below fields in your email.', $this->plugin_slug ) . '<br />
        
            <b>{subscriber} = ' . __ ( 'Subscribers Name', $this->plugin_slug ) . '<br />
            {url} = ' . __ ( 'Verification Link', $this->plugin_slug ) . '<br />
            {download} = ' . __ ( 'The name of the download the subscriber has selected', $this->plugin_slug ) . '</b><br />';

        echo '</div>';
    }

    public function html_email_option() {

        echo '<input type="radio" id="send_html" name="doifd_lab_options[send_html]" value="1" disabled/> Yes ';
        echo '<input type="radio" id="send_html" name="doifd_lab_options[send_html]" value="0" disabled/> No ';
        echo '<p>' . __ ( 'Premium Version Only', $this->plugin_slug ) . '</p>';
    }

    public function email_subject_option() {

        if ( isset ( $this->doifd_options[ 'email_subject' ] ) ) {

            $email_subject = $this->doifd_options[ 'email_subject' ];

            } else {

            $email_subject = '';
        }

        echo '<div id="doifd_lab_admin_options">';
        echo '<input type="text" name="doifd_lab_options[email_subject]" id="email_subject" size="50" value="' . $email_subject . '" disabled>';
        echo '<p>' . __ ( 'You can use <b>{download}</b> to insert the download name and <b>{site_name}</b> to insert your website/blog name. <b>( Premium Versions Only )</b>', $this->plugin_slug ) . '</p>';
        echo '</div>';
    }

}
new DOIFDAdminEmailSettings;