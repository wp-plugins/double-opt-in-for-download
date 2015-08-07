<?php

if ( ! class_exists ( 'DoifdAdminValidation' ) ) {

    class DOIFDAdminValidation {

        public function __construct() {
            
        }

        /* This function validates all the options field inputs, just in case. */

        public function admin_options_validation( $input ) {

            $valid = array( );

            $valid[ 'downloads_allowed' ] = preg_replace ( '/[^0-9]/', '', $input[ 'downloads_allowed' ] );

            if ( isset ( $input[ 'email_name' ] ) ) {

                $valid[ 'email_name' ] = preg_replace ( '/[^ \w]+/', '', $input[ 'email_name' ] );
            }

            if ( isset ( $input[ 'from_email' ] ) ) {

                $valid[ 'from_email' ] = $input[ 'from_email' ];
            }

            if ( isset ( $input[ 'email_message' ] ) ) {

                $valid[ 'email_message' ] = $input[ 'email_message' ];
            }

            $valid[ 'add_to_wpusers' ] = preg_replace ( '/[^0-9]/', '', $input[ 'add_to_wpusers' ] );
            
            if ( isset ( $input[ 'allow_dup_emails' ] ) ) {
            
            $valid[ 'allow_dup_emails' ] = preg_replace ( '/[^0-9]/', '', $input[ 'allow_dup_emails' ] );
            
            }

            $valid[ 'form_security' ] = preg_replace ( '/[^0-9]/', '', $input[ 'form_security' ] );

            $valid[ 'promo_link' ] = preg_replace ( '/[^0-9]/', '', $input[ 'promo_link' ] );
            
            $valid[ 'widget_class' ] = preg_replace ( '/[^a-z0-9_-]/i', '', $input[ 'widget_class' ] );
            
            $valid[ 'widget_class_textarea' ]  =  $input[ 'widget_class_textarea' ];

            $valid[ 'widget_width' ] = preg_replace ( '/[^0-9]/', '', $input[ 'widget_width' ] );

            $valid[ 'use_widget_form_labels' ] = preg_replace ( '/[^0-9]/', '', $input[ 'use_widget_form_labels' ] );

            $valid[ 'widget_inside_padding' ] = preg_replace ( '/[^0-9]/', '', $input[ 'widget_inside_padding' ] );

            $valid[ 'widget_margin_top' ] = preg_replace ( '/[^0-9]/', '', $input[ 'widget_margin_top' ] );

            $valid[ 'widget_margin_right' ] = preg_replace ( '/[^0-9]/', '', $input[ 'widget_margin_right' ] );

            $valid[ 'widget_margin_bottom' ] = preg_replace ( '/[^0-9]/', '', $input[ 'widget_margin_bottom' ] );

            $valid[ 'widget_margin_left' ] = preg_replace ( '/[^0-9]/', '', $input[ 'widget_margin_left' ] );

            $valid[ 'widget_input_width' ] = preg_replace ( '/[^#%!a-z0-9]/i', '', $input[ 'widget_input_width' ] );

            $valid[ 'widget_title_color' ] = preg_replace ( '/[^#!a-z0-9]/i', '', $input[ 'widget_title_color' ] );

            $valid[ 'widget_title_size' ] = preg_replace ( '/[^!a-z0-9.]/', '', $input[ 'widget_title_size' ] );

            $valid[ 'widget_input_field_background_color' ] = preg_replace ( '/[^#!a-z0-9]/i', '', $input[ 'widget_input_field_background_color' ] );

            $valid[ 'widget_background_color' ] = preg_replace ( '/[^#!a-z0-9]/i', '', $input[ 'widget_background_color' ] );

            $valid[ 'widget_color' ] = preg_replace ( '/[^#!a-z0-9]/i', '', $input[ 'widget_color' ] );

            $valid[ 'form_width' ] = preg_replace ( '/[^!a-z0-9]/i', '', $input[ 'form_width' ] );

            $valid[ 'use_form_labels' ] = preg_replace ( '/[^0-9]/', '', $input[ 'use_form_labels' ] );

            $valid[ 'form_padding' ] = preg_replace ( '/[^!a-z0-9]/i', '', $input[ 'form_padding' ] );

            $valid[ 'form_background_color' ] = preg_replace ( '/[^#!a-z0-9]/i', '', $input[ 'form_background_color' ] );

            $valid[ 'form_color' ] = preg_replace ( '/[^#!a-z0-9]/i', '', $input[ 'form_color' ] );

            $valid[ 'form_title_color' ] = preg_replace ( '/[^#!a-z0-9]/i', '', $input[ 'form_title_color' ] );

            $valid[ 'form_title_size' ] = preg_replace ( '/[^!a-z0-9.]/', '', $input[ 'form_title_size' ] );

            $valid[ 'form_class' ] = preg_replace ( '/[^a-z0-9_-]/i', '', $input[ 'form_class' ] );
            
            $valid[ 'form_class_textarea' ]  =  $input[ 'form_class_textarea' ];

            $valid[ 'form_input_field_background_color' ] = preg_replace ( '/[^#!a-z0-9]/i', '', $input[ 'form_input_field_background_color' ] );

            $valid[ 'form_input_field_width' ] = preg_replace ( '/[^%!a-z0-9]/i', '', $input[ 'form_input_field_width' ] );

            if ( isset ( $input[ 'notification_email' ] ) ) {

                $valid[ 'notification_email' ] = preg_replace ( '/[^0-9]/', '', $input[ 'notification_email' ] );
            }

            $valid[ 'use_privacy_policy' ] = preg_replace ( '/[^0-9]/', '', $input[ 'use_privacy_policy' ] );

            $valid[ 'privacy_link_font_size' ] = preg_replace ( '/[^a-z0-9.]/', '', $input[ 'privacy_link_font_size' ] );

            $valid[ 'privacy_link_text' ] = preg_replace ( '/ [^a-zA-Z0-9_-]/i', '', $input[ 'privacy_link_text' ] );

            $valid[ 'privacy_page' ] = preg_replace ( '/[^0-9]/', '', $input[ 'privacy_page' ] );

            return apply_filters ( 'doifd_validate_admin_options_values', $valid, $input );
        }

        public function admin_form_upload_validation( $input ) {

            $valid = array( );

            $valid[ 'download_name' ] = sanitize_text_field ( $input[ 'download_name' ] );

            /* clean and sanitize download landing page id */

            $valid[ 'landing_page' ] = preg_replace ( '/[^0-9]/', '', $input[ 'doifd_download_landing_page' ] );

            return apply_filters ( 'doifd_validate_admin_download_values', $valid );
        }

        public function admin_edit_validation( $input ) {

            $valid = array( );

            $valid[ 'reset' ] = preg_replace ( '/[^0-9]/', '', $input[ 'doifd_reset_download_count' ] );

            /* Get old file name for comparison */

            $valid[ 'old_name' ] = sanitize_text_field ( $input[ 'doifd_download_name' ] );

            /* clean and sanitize Download name field */

            if ( isset ( $input[ 'name' ] ) ) {

                $valid[ 'download_name' ] = sanitize_text_field ( $input[ 'name' ] );
            } elseif ( isset ( $input[ 'doifd_edit_name' ] ) ) {

                $valid[ 'download_name' ] = sanitize_text_field ( $input[ 'doifd_edit_name' ] );
            } else {

                $valid[ 'download_name' ] = '';
            }

            /* clean and sanitize download id */

            $valid[ 'download_id' ] = preg_replace ( '/[^0-9]/', '', $input[ 'doifd_download_id' ] );

            /* clean and sanitize landing page id */

            if ( isset ( $input[ 'doifd_download_landing_page' ] ) ) {

                $valid[ 'landing_page' ] = preg_replace ( '/[^0-9]/', '', $input[ 'doifd_download_landing_page' ] );
            } elseif ( isset ( $input[ 'doifd_download_edit_landing_page' ] ) ) {

                $valid[ 'landing_page' ] = preg_replace ( '/[^0-9]/', '', $input[ 'doifd_download_edit_landing_page' ] );
            } else {

                $valid[ 'landing_page' ] = '';
            }

            /* assign file name to variable */

            return apply_filters ( 'doifd_validate_admin_download_values', $valid );
        }

    }

}
new DOIFDAdminValidation();
