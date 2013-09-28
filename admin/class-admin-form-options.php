<?php

if ( !class_exists ( 'DoifdAdminFormOptions' ) ) {

    class DoifdAdminFormOptions {

        public function __construct() {
            
        }
        
        public static function field_form_class() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'form_class' ] ) && ($doifd_option[ 'form_class' ] == !NULL ) ) {

                $form_class = $doifd_option[ 'form_class' ];
            } else {

                $form_class = '';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[form_class]" id="form_width"  size="15" value="' . $form_class . '">';
            echo '<p>' . __( 'If you want to create your own CSS class for the form, enter the name of your CSS class' , 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

        public static function field_form_width() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'form_width' ] ) && ($doifd_option[ 'form_width' ] == !NULL ) ) {

                $form_width = $doifd_option[ 'form_width' ];
            } else {

                $form_width = '450';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[form_width]" id="form_width"  size="4" value="' . $form_width . '">';
            echo '<p>' . __( 'This is the width of the main form. <b>Use numbers only, DO NOT ADD the px at the end.' , 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

        public static function field_form_padding() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'form_padding' ] ) && ($doifd_option[ 'form_padding' ] == !NULL ) ) {

                $form_padding = $doifd_option[ 'form_padding' ];
            } else {

                $form_padding = '15';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[form_padding]" id="form_padding" size="4" value="' . $form_padding . '">';
            echo '<p>' . __( 'This is the amount of padding used inside of the form. <b>Use numbers only, DO NOT ADD the px at the end.' , 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

        public static function field_form_background_color() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'form_background_color' ] ) && ($doifd_option[ 'form_background_color' ] == !NULL ) ) {

                $form_background_color = $doifd_option[ 'form_background_color' ];
            } else {

                $form_background_color = 'transparent';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[form_background_color]" id="form_background_color" size="15" value="' . $form_background_color . '">';
            echo '<p>' . __( 'This sets the background color of the form. <b>You can use tranparent or hex values ( #000000 etc ).',  'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

        public static function field_form_color() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'form_color' ] ) && ($doifd_option[ 'form_color' ] == !NULL ) ) {

                $form_color = $doifd_option[ 'form_color' ];
            } else {

                $form_color = '#000000';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[form_color]" id="form_margin_right" size="10" value="' . $form_color . '">';
            echo '<p>' . __( 'This set the forms font color. <b>Use hex values ( #000000 etc ).', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

        public static function field_form_input_field_background_color() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'form_input_field_background_color' ] ) && ($doifd_option[ 'form_input_field_background_color' ] == !NULL ) ) {

                $form_input_field_background_color = $doifd_option[ 'form_input_field_background_color' ];
            } else {

                $form_input_field_background_color = 'transparent';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[form_input_field_background_color]" id="form_margin_bottom" size="10" value="' . $form_input_field_background_color . '">';
            echo '<p>' . __( 'This sets the background color of the text input fields. <b>You can use transparent or hex values ( #000000 etc ).', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

        public static function field_form_title_color() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'form_title_color' ] ) && ($doifd_option[ 'form_title_color' ] == !NULL ) ) {

                $form_title_color = $doifd_option[ 'form_title_color' ];
            } else {

                $form_title_color = '#000000';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[form_title_color]" id="form_margine_left"  size="10" value="' . $form_title_color . '">';
            echo '<p>' . __( 'This sets the font color for the form title. <b>Use hex values ( #000000 etc ).', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }
        
        public static function field_form_title_size() {

            /* get the options from wp options table */

            $doifd_option = get_option( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset( $doifd_option[ 'form_title_size' ] ) && ($doifd_option[ 'form_title_size' ] == !NULL ) ) {

                $form_title_size = $doifd_option[ 'form_title_size' ];
            } else {

                $form_title_size = '1em';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[form_title_size]" id="form_title_size"  size="10" value="' . $form_title_size . '">';
            echo '<p>' . __( 'This sets the font size for the form title. ( Example: 1em, 12px etc ).', 'double-opt-in-for-download' ) . '</p>';
            echo '</div>';
        }

        public static function field_form_input_field_width() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'form_input_field_width' ] ) && ($doifd_option[ 'form_input_field_width' ] == !NULL ) ) {

                $form_input_width = $doifd_option[ 'form_input_field_width' ];
            } else {

                $form_input_width = '70%';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[form_input_field_width]" id="form_input_width" size="4" value="' . $form_input_width . '">';
            echo '<p>' . __( 'This sets the width of the input field on the form. <b>Use Percentage or PX ( 70% or 200px etc ).', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

    }

}

?>