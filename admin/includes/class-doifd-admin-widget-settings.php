<?php

class DOIFDAdminWidgetOptionFields extends DOIFDAdmin {

    public function __construct() {
        parent::__construct();

        add_action( 'admin_init', array(
            $this,
            'widget_field_settings' ) );
    }

    public function widget_field_settings() {

        add_settings_section( 'doifd_lab_widget_style_section', __( 'Widget Style Settings', $this->plugin_slug ), '', 'doifd_lab_widget_style' );
       
        add_settings_field( 'doifd_lab_widget_show_labels', __( 'Show Form Labels', $this->plugin_slug ), array(
            $this,
            'use_widget_form_labels' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
        add_settings_field( 'doifd_lab_widget_style_width', __( 'Width of Widget', $this->plugin_slug ), array(
            $this,
            'field_widget_width' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
        add_settings_field( 'doifd_lab_widget_style_inside_padding', __( 'Widget Inside Padding', $this->plugin_slug ), array(
            $this,
            'field_widget_inside_padding' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
        add_settings_field( 'doifd_lab_widget_style_margin_top', __( 'Widget Margin Top', $this->plugin_slug ), array(
            $this,
            'field_widget_top_margin' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
        add_settings_field( 'doifd_lab_widget_style_margin_right', __( 'Widget Margin Right', $this->plugin_slug ), array(
            $this,
            'field_widget_right_margin' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
        add_settings_field( 'doifd_lab_widget_style_margin_bottom', __( 'Widget Margin Bottom', $this->plugin_slug ), array(
            $this,
            'field_widget_bottom_margin' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
        add_settings_field( 'doifd_lab_widget_style_margin_left', __( 'Widget Margin Left', $this->plugin_slug ), array(
            $this,
            'field_widget_left_margin' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
        add_settings_field( 'doifd_lab_widget_style_input_width', __( 'Widget Input Width', $this->plugin_slug ), array(
            $this,
            'field_input_field_width' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
        add_settings_field( 'doifd_lab_widget_input_field_background_color', __( 'Widget Input Field Background Color', $this->plugin_slug ), array(
            $this,
            'field_widget_input_field_background_color' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
        add_settings_field( 'doifd_lab_widget_title_color', __( 'Widget Title Font Color', $this->plugin_slug ), array(
            $this,
            'field_widget_title_color' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
        add_settings_field( 'doifd_lab_widget_title_size', __( 'Widget Title Font Size', $this->plugin_slug ), array(
            $this,
            'field_widget_title_size' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
        add_settings_field( 'doifd_lab_widget_style_background_color', __( 'Widget Background Color', $this->plugin_slug ), array(
            $this,
            'field_widget_background_color' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
        add_settings_field( 'doifd_lab_widget_font_color', __( 'Widget Font Color', $this->plugin_slug ), array(
            $this,
            'field_widget_color' ), 'doifd_lab_widget_style', 'doifd_lab_widget_style_section' );
    }

    public function use_widget_form_labels() {

        if( isset( $this->doifd_options[ 'use_widget_form_labels' ] ) ) {

            $use_widget_form_labels = $this->doifd_options[ 'use_widget_form_labels' ];
        } else {

            $use_widget_form_labels = '1';
        }

        echo '<input type="radio" id="use_widget_form_labels" name="doifd_lab_options[use_widget_form_labels]" ' . ((isset( $use_widget_form_labels ) && ($use_widget_form_labels) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ';
        echo '<input type="radio" id="use_widget_form_labels" name="doifd_lab_options[use_widget_form_labels]" ' . (isset( $use_widget_form_labels ) && ($use_widget_form_labels == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ';
    }

    public function field_widget_width() {

        if( isset( $this->doifd_options[ 'widget_width' ] ) && ($this->doifd_options[ 'widget_width' ] == !NULL ) ) {

            $widget_width = $this->doifd_options[ 'widget_width' ];
        } else {

            $widget_width = '190';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_width]" id="widget_width"  size="4" value="' . $widget_width . '">';
        echo '<p>' . __( '( Example: 250 )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_widget_inside_padding() {

        if( isset( $this->doifd_options[ 'widget_inside_padding' ] ) && ($this->doifd_options[ 'widget_inside_padding' ] == !NULL ) ) {

            $widget_inside_padding = $this->doifd_options[ 'widget_inside_padding' ];
        } else {

            $widget_inside_padding = '5';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_inside_padding]" id="widget_inside_padding" size="4" value="' . $widget_inside_padding . '">';
        echo '<p>' . __( '( Example: 25 )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_widget_top_margin() {

        if( isset( $this->doifd_options[ 'widget_margin_top' ] ) && ($this->doifd_options[ 'widget_margin_top' ] == !NULL ) ) {

            $widget_margin_top = $this->doifd_options[ 'widget_margin_top' ];
        } else {

            $widget_margin_top = '25';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_margin_top]" id="widget_margin_top" size="4" value="' . $widget_margin_top . '">';
        echo '<p>' . __( '( Example: 25 )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_widget_right_margin() {

        if( isset( $this->doifd_options[ 'widget_margin_right' ] ) && ($this->doifd_options[ 'widget_margin_right' ] == !NULL ) ) {

            $widget_margin_right = $this->doifd_options[ 'widget_margin_right' ];
        } else {

            $widget_margin_right = '0';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_margin_right]" id="widget_margin_right" size="4" value="' . $widget_margin_right . '">';
        echo '<p>' . __( '( Example: 25 )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_widget_bottom_margin() {

        if( isset( $this->doifd_options[ 'widget_margin_bottom' ] ) && ($this->doifd_options[ 'widget_margin_bottom' ] == !NULL ) ) {

            $widget_margin_bottom = $this->doifd_options[ 'widget_margin_bottom' ];
        } else {

            $widget_margin_bottom = '25';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_margin_bottom]" id="widget_margin_bottom" size="4" value="' . $widget_margin_bottom . '">';
        echo '<p>' . __( '( Example: 25 )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_widget_left_margin() {

        if( isset( $this->doifd_options[ 'widget_margin_left' ] ) && ($this->doifd_options[ 'widget_margin_left' ] == !NULL ) ) {

            $widget_margin_left = $this->doifd_options[ 'widget_margin_left' ];
        } else {

            $widget_margin_left = '0';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_margin_left]" id="widget_margine_left"  size="4" value="' . $widget_margin_left . '">';
        echo '<p>' . __( '( Example: 25 )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_input_field_width() {

        if( isset( $this->doifd_options[ 'widget_input_width' ] ) && ($this->doifd_options[ 'widget_input_width' ] == !NULL ) ) {

            $widget_input_width = $this->doifd_options[ 'widget_input_width' ];
        } else {

            $widget_input_width = '180';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_input_width]" id="widget_input_width" size="4" value="' . $widget_input_width . '">';
        echo '<p>' . __( '( Example: 70% or 200px etc )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_widget_background_color() {

        if( isset( $this->doifd_options[ 'widget_background_color' ] ) && ($this->doifd_options[ 'widget_background_color' ] == !NULL ) ) {

            $widget_background_color = $this->doifd_options[ 'widget_background_color' ];
        } else {

            $widget_background_color = 'transparent';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_background_color]" id="widget_background_color" size="10" value="' . $widget_background_color . '">';
        echo '<p>' . __( '( Example: #000000, transparent, etc )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_widget_input_field_background_color() {

        if( isset( $this->doifd_options[ 'widget_input_field_background_color' ] ) && ($this->doifd_options[ 'widget_input_field_background_color' ] == !NULL ) ) {

            $widget_input_field_background_color = $this->doifd_options[ 'widget_input_field_background_color' ];
        } else {

            $widget_input_field_background_color = 'transparent';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_input_field_background_color]" id="widget_input_field_background_color" size="10" value="' . $widget_input_field_background_color . '">';
        echo '<p>' . __( '( Example: #000000, transparent, etc )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_widget_title_color() {

        if( isset( $this->doifd_options[ 'widget_title_color' ] ) && ($this->doifd_options[ 'widget_title_color' ] == !NULL ) ) {

            $widget_title_color = $this->doifd_options[ 'widget_title_color' ];
        } else {

            $widget_title_color = '#000000';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_title_color]" id="widget_title_color"  size="10" value="' . $widget_title_color . '">';
        echo '<p>' . __( '( Example: #000000, transparent, etc )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_widget_title_size() {

        if( isset( $this->doifd_options[ 'widget_title_size' ] ) && ($this->doifd_options[ 'widget_title_size' ] == !NULL ) ) {

            $widget_title_size = $this->doifd_options[ 'widget_title_size' ];
        } else {

            $widget_title_size = '1em';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_title_size]" id="widget_title_size"  size="10" value="' . $widget_title_size . '">';
        echo '<p>' . __( '( Example: 1em, 12px etc )', $this->plugin_slug ) . '</p>';
        echo '</div>';
    }

    public function field_widget_color() {

        if( isset( $this->doifd_options[ 'widget_color' ] ) && ($this->doifd_options[ 'widget_color' ] == !NULL ) ) {

            $widget_color = $this->doifd_options[ 'widget_color' ];
        } else {

            $widget_color = '#000000';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_color]" id="widget_color"  size="10" value="' . $widget_color . '">';
        echo '<p>' . __( '( Example: #000000, transparent etc ).', $this->plugin_slug ) . '</p>';
        echo '</div>';
    }

}
new DOIFDAdminWidgetOptionFields();