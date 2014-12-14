<?php

class DOIFDAdminFormOptionFields extends DOIFDAdmin {

    public function __construct() {
        parent::__construct();

        add_action( 'admin_init', array( $this, 'form_field_settings' ) );
       
    }

    public function form_field_settings() {

        add_settings_section( 'doifd_lab_form_style_section', __( 'Form Style Settings', $this->plugin_slug ), '', 'doifd_lab_form_style' );

        
        add_settings_field( 'doifd_lab_form_show_labels', __( 'Show Form Labels', $this->plugin_slug ), array(
            $this,
            'doifd_lab_setting_show_form_labels' ), 'doifd_lab_form_style', 'doifd_lab_form_style_section' );

        add_settings_field( 'doifd_lab_form_style_width', __( 'Form Width', $this->plugin_slug ), array(
            $this,
            'field_form_width' ), 'doifd_lab_form_style', 'doifd_lab_form_style_section' );

        add_settings_field( 'doifd_lab_form_style_padding', __( 'Form Padding', $this->plugin_slug ), array(
            $this,
            'field_form_padding' ), 'doifd_lab_form_style', 'doifd_lab_form_style_section' );

        add_settings_field( 'doifd_lab_form_background_color', __( 'Form Background Color', $this->plugin_slug ), array(
            $this,
            'field_form_background_color' ), 'doifd_lab_form_style', 'doifd_lab_form_style_section' );

        add_settings_field( 'doifd_lab_form_color', __( 'Form Font Color', $this->plugin_slug ), array(
            $this,
            'field_form_color' ), 'doifd_lab_form_style', 'doifd_lab_form_style_section' );

        add_settings_field( 'doifd_lab_form_title_color', __( 'Title Font Color', $this->plugin_slug ), array(
            $this,
            'field_form_title_color' ), 'doifd_lab_form_style', 'doifd_lab_form_style_section' );

        add_settings_field( 'doifd_lab_form_title_size', __( 'Title Font Size', $this->plugin_slug ), array(
            $this,
            'field_form_title_size' ), 'doifd_lab_form_style', 'doifd_lab_form_style_section' );

        add_settings_field( 'doifd_lab_form_input_field_background_color', __( 'Form Input Field Background Color', $this->plugin_slug ), array(
            $this,
            'field_form_input_field_background_color' ), 'doifd_lab_form_style', 'doifd_lab_form_style_section' );

        add_settings_field( 'doifd_lab_form_input_field_width', __( 'Form Input Field Width', $this->plugin_slug ), array(
            $this,
            'field_form_input_field_width' ), 'doifd_lab_form_style', 'doifd_lab_form_style_section' );
    }

    public function doifd_lab_setting_show_form_labels() {

        if( isset( $this->doifd_options[ 'use_form_labels' ] ) ) {

            $use_form_labels = $this->doifd_options[ 'use_form_labels' ];
        } else {

            $use_form_labels = '1';
        }

        echo '<input type="radio" id="use_form_labels" name="doifd_lab_options[use_form_labels]" ' . ((isset( $use_form_labels ) && ($use_form_labels) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ';
        echo '<input type="radio" id="use_form_labels" name="doifd_lab_options[use_form_labels]" ' . (isset( $use_form_labels ) && ($use_form_labels == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ';
    }

    public function field_form_width() {

        if( isset( $this->doifd_options[ 'form_width' ] ) && ($this->doifd_options[ 'form_width' ] == !NULL ) ) {

            $form_width = $this->doifd_options[ 'form_width' ];
        } else {

            $form_width = '450';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[form_width]" id="form_width"  size="4" value="' . $form_width . '">';
        echo '<p>' . __( '( Example: 250 )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_form_padding() {

        if( isset( $this->doifd_options[ 'form_padding' ] ) && ($this->doifd_options[ 'form_padding' ] == !NULL ) ) {

            $form_padding = $this->doifd_options[ 'form_padding' ];
        } else {

            $form_padding = '15';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[form_padding]" id="form_padding" size="4" value="' . $form_padding . '">';
        echo '<p>' . __( '( Example: 25 )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_form_background_color() {

        if( isset( $this->doifd_options[ 'form_background_color' ] ) && ($this->doifd_options[ 'form_background_color' ] == !NULL ) ) {

            $form_background_color = $this->doifd_options[ 'form_background_color' ];
        } else {

            $form_background_color = 'transparent';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[form_background_color]" id="form_background_color" size="15" value="' . $form_background_color . '">';
        echo '<p>' . __( '( Example: #000000, transparent, etc )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_form_color() {

        if( isset( $this->doifd_options[ 'form_color' ] ) && ($this->doifd_options[ 'form_color' ] == !NULL ) ) {

            $form_color = $this->doifd_options[ 'form_color' ];
        } else {

            $form_color = '#000000';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[form_color]" id="form_margin_right" size="10" value="' . $form_color . '">';
        echo '<p>' . __( '( Example: #000000, transparent, etc )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_form_input_field_background_color() {

        if( isset( $this->doifd_options[ 'form_input_field_background_color' ] ) && ($this->doifd_options[ 'form_input_field_background_color' ] == !NULL ) ) {

            $form_input_field_background_color = $this->doifd_options[ 'form_input_field_background_color' ];
        } else {

            $form_input_field_background_color = 'transparent';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[form_input_field_background_color]" id="form_margin_bottom" size="10" value="' . $form_input_field_background_color . '">';
        echo '<p>' . __( '( Example: #000000, transparent, etc )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_form_title_color() {

        if( isset( $this->doifd_options[ 'form_title_color' ] ) && ($this->doifd_options[ 'form_title_color' ] == !NULL ) ) {

            $form_title_color = $this->doifd_options[ 'form_title_color' ];
        } else {

            $form_title_color = '#000000';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[form_title_color]" id="form_margine_left"  size="10" value="' . $form_title_color . '">';
        echo '<p>' . __( '( Example: #000000, transparent, etc )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_form_title_size() {

        if( isset( $this->doifd_options[ 'form_title_size' ] ) && ($this->doifd_options[ 'form_title_size' ] == !NULL ) ) {

            $form_title_size = $this->doifd_options[ 'form_title_size' ];
        } else {

            $form_title_size = '1em';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[form_title_size]" id="form_title_size"  size="10" value="' . $form_title_size . '">';
        echo '<p>' . __( '( Example: 1em, 12px etc )', $this->plugin_slug ) . '</p>';
        echo '</div>';
    }

    public function field_form_input_field_width() {

        if( isset( $this->doifd_options[ 'form_input_field_width' ] ) && ($this->doifd_options[ 'form_input_field_width' ] == !NULL ) ) {

            $form_input_width = $this->doifd_options[ 'form_input_field_width' ];
        } else {

            $form_input_width = '70%';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[form_input_field_width]" id="form_input_width" size="4" value="' . $form_input_width . '">';
        echo '<p>' . __( '( Example: 70% or 200px etc )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

}
new DOIFDAdminFormOptionFields;