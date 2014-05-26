<?php

if ( !class_exists ( 'DoifdAdminWidgetOptions' ) ) {

    class DoifdAdminWidgetOptions {

        public function __construct() {
            
        }

        public static function field_widget_width() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get widget stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'widget_width' ] ) && ($doifd_option[ 'widget_width' ] == !NULL ) ) {

                $widget_width = $doifd_option[ 'widget_width' ];
            } else {

                $widget_width = '190';
            }

            /* echo widget width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[widget_width]" id="widget_width"  size="4" value="' . $widget_width . '">';
            echo '<p>' . __( '( Example: 250 )', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

        public static function field_widget_inside_padding() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get widget stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'widget_inside_padding' ] ) && ($doifd_option[ 'widget_inside_padding' ] == !NULL ) ) {

                $widget_inside_padding = $doifd_option[ 'widget_inside_padding' ];
            } else {

                $widget_inside_padding = '5';
            }

            /* echo widget width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[widget_inside_padding]" id="widget_inside_padding" size="4" value="' . $widget_inside_padding . '">';
            echo '<p>' . __( '( Example: 25 )', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

        public static function field_widget_top_margin() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get widget stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'widget_margin_top' ] ) && ($doifd_option[ 'widget_margin_top' ] == !NULL ) ) {

                $widget_margin_top = $doifd_option[ 'widget_margin_top' ];
            } else {

                $widget_margin_top = '25';
            }

            /* echo widget width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[widget_margin_top]" id="widget_margin_top" size="4" value="' . $widget_margin_top . '">';
            echo '<p>' . __( '( Example: 25 )', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

        public static function field_widget_right_margin() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get widget stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'widget_margin_right' ] ) && ($doifd_option[ 'widget_margin_right' ] == !NULL ) ) {

                $widget_margin_right = $doifd_option[ 'widget_margin_right' ];
            } else {

                $widget_margin_right = '0';
            }

            /* echo widget width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[widget_margin_right]" id="widget_margin_right" size="4" value="' . $widget_margin_right . '">';
            echo '<p>' . __( '( Example: 25 )', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

        public static function field_widget_bottom_margin() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get widget stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'widget_margin_bottom' ] ) && ($doifd_option[ 'widget_margin_bottom' ] == !NULL ) ) {

                $widget_margin_bottom = $doifd_option[ 'widget_margin_bottom' ];
            } else {

                $widget_margin_bottom = '25';
            }

            /* echo widget width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[widget_margin_bottom]" id="widget_margin_bottom" size="4" value="' . $widget_margin_bottom . '">';
            echo '<p>' . __( '( Example: 25 )', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

        public static function field_widget_left_margin() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get widget stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'widget_margin_left' ] ) && ($doifd_option[ 'widget_margin_left' ] == !NULL ) ) {

                $widget_margin_left = $doifd_option[ 'widget_margin_left' ];
            } else {

                $widget_margin_left = '0';
            }

            /* echo widget width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[widget_margin_left]" id="widget_margine_left"  size="4" value="' . $widget_margin_left . '">';
            echo '<p>' . __( '( Example: 25 )', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }

        public static function field_input_field_width() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get widget stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'widget_input_width' ] ) && ($doifd_option[ 'widget_input_width' ] == !NULL ) ) {

                $widget_input_width = $doifd_option[ 'widget_input_width' ];
            } else {

                $widget_input_width = '180';
            }

            /* echo widget width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[widget_input_width]" id="widget_input_width" size="4" value="' . $widget_input_width . '">';
            echo '<p>' . __( '( Example: 70% or 200px etc )', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }
        
        public static function field_widget_background_color() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get widget stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'widget_background_color' ] ) && ($doifd_option[ 'widget_background_color' ] == !NULL ) ) {

                $widget_background_color = $doifd_option[ 'widget_background_color' ];
            } else {

                $widget_background_color = 'transparent';
            }

            /* echo widget width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[widget_background_color]" id="widget_background_color" size="10" value="' . $widget_background_color . '">';
            echo '<p>' . __( '( Example: #000000, transparent, etc )', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }
        
        public static function field_widget_input_field_background_color() {

            /* get the options from wp options table */

            $doifd_option = get_option ( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset ( $doifd_option[ 'widget_input_field_background_color' ] ) && ($doifd_option[ 'widget_input_field_background_color' ] == !NULL ) ) {

                $widget_input_field_background_color = $doifd_option[ 'widget_input_field_background_color' ];
            } else {

                $widget_input_field_background_color = 'transparent';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[widget_input_field_background_color]" id="widget_input_field_background_color" size="10" value="' . $widget_input_field_background_color . '">';
            echo '<p>' . __( '( Example: #000000, transparent, etc )', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';

        }
        
        public static function field_widget_title_color() {

            /* get the options from wp options table */

            $doifd_option = get_option( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset( $doifd_option[ 'widget_title_color' ] ) && ($doifd_option[ 'widget_title_color' ] == !NULL ) ) {

                $widget_title_color = $doifd_option[ 'widget_title_color' ];
            } else {

                $widget_title_color = '#000000';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[widget_title_color]" id="widget_title_color"  size="10" value="' . $widget_title_color . '">';
            echo '<p>' . __( '( Example: #000000, transparent, etc )', 'double-opt-in-for-download' ) . '</b></p>';
            echo '</div>';
        }
        
        public static function field_widget_title_size() {

            /* get the options from wp options table */

            $doifd_option = get_option( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset( $doifd_option[ 'widget_title_size' ] ) && ($doifd_option[ 'widget_title_size' ] == !NULL ) ) {

                $widget_title_size = $doifd_option[ 'widget_title_size' ];
            } else {

                $widget_title_size = '1em';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[widget_title_size]" id="widget_title_size"  size="10" value="' . $widget_title_size . '">';
            echo '<p>' . __( '( Example: 1em, 12px etc )', 'double-opt-in-for-download' ) . '</p>';
            echo '</div>';
        }
        
            public static function field_widget_color() {

            /* get the options from wp options table */

            $doifd_option = get_option( 'doifd_lab_options' );

            /* get form stored field value and assign to variable.
             * If set or not null use the stored option otherwise use the default */

            if ( isset( $doifd_option[ 'widget_color' ] ) && ($doifd_option[ 'widget_color' ] == !NULL ) ) {

                $widget_color = $doifd_option[ 'widget_color' ];
            } else {

                $widget_color = '#000000';
            }

            /* echo form width form */

            echo '<div id="doifd_lab_admin_options">';
            echo '<input type="text" name="doifd_lab_options[widget_color]" id="widget_color"  size="10" value="' . $widget_color . '">';
            echo '<p>' . __( '( Example: #000000, transparent etc ).', 'double-opt-in-for-download' ) . '</p>';
            echo '</div>';
        }


    }

}

?>