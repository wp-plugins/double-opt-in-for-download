<?php

class DOIFDAdminCustonCSSFields extends DOIFDAdmin {

    public function __construct() {
        parent::__construct();

        add_action( 'admin_init', array( $this, 'custom_form_css_settings' ) );
        add_action( 'admin_init', array( $this, 'custom_widget_css_settings' ) );
    }

    public function custom_form_css_settings() {

        add_settings_section( 'doifd_lab_form_custom_css_section', __( 'Custom Form CSS', $this->plugin_slug ), '', 'doifd_lab_form_custom_css' );

        add_settings_field( 'doifd_lab_form_style_class', __( 'Form CSS Class', $this->plugin_slug ), array(
            $this,
            'field_form_class' ), 'doifd_lab_form_custom_css', 'doifd_lab_form_custom_css_section' );
        add_settings_field( 'doifd_lab_form_class_text_area', __( 'Custom CSS', $this->plugin_slug ), array(
            $this,
            'field_form_custom_css' ), 'doifd_lab_form_custom_css', 'doifd_lab_form_custom_css_section' );
    }

    public function custom_widget_css_settings() {

        add_settings_section( 'doifd_lab_widget_custom_css_section', __( 'Custom Widget CSS', $this->plugin_slug ), '', 'doifd_lab_widget_custom_css' );

        add_settings_field( 'doifd_lab_widget_style_class', __( 'Widget CSS Class', $this->plugin_slug ), array(
            $this,
            'field_widget_class' ), 'doifd_lab_widget_custom_css', 'doifd_lab_widget_custom_css_section' );
        add_settings_field( 'doifd_lab_widget_class_text_area', __( 'Custom Widget CSS', $this->plugin_slug ), array(
            $this,
            'field_widget_custom_css' ), 'doifd_lab_widget_custom_css', 'doifd_lab_widget_custom_css_section' );
    }

    public function field_form_class() {

        if( isset( $this->doifd_options[ 'form_class' ] ) && ($this->doifd_options[ 'form_class' ] == !NULL ) ) {

            $form_class = $this->doifd_options[ 'form_class' ];
        } else {

            $form_class = '';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[form_class]" id="form_width"  size="15" value="' . $form_class . '">';
        echo '<p>' . __( 'Create your own CSS class for the form. ( Example: myFormClass )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }

    public function field_form_custom_css() {

        if( !empty( $this->doifd_options[ 'form_class_textarea' ] ) ) {

            $custom_css = $this->doifd_options[ 'form_class_textarea' ];
        } else {

            $custom_css = '';
        }

        echo '<div class="doifd_options_fields">';
        echo '<label><b>' . __( 'Write your custom CSS:', $this->plugin_slug ) . '</b> </label>';
        echo '<div id="doifd_form_custom_css_container">';
        echo '</div>';
        echo '<textarea rows="10" cols="60" name="doifd_lab_options[form_class_textarea]" id="form_class_textarea">' . $custom_css . '</textarea>';
        echo '</div>';
        echo '</div>';
    }
    
     public function field_widget_class() {

        if( isset( $this->doifd_options[ 'widget_class' ] ) && ($this->doifd_options[ 'widget_class' ] == !NULL ) ) {

            $widget_class = $this->doifd_options[ 'widget_class' ];
        } else {

            $widget_class = '';
        }

        echo '<div class="doifd_options_fields">';
        echo '<input type="text" name="doifd_lab_options[widget_class]" id="widget_class"  size="15" value="' . $widget_class . '">';
        echo '<p>' . __( 'Create your own CSS class for the widget. ( Example: myWidgetClass )', $this->plugin_slug ) . '</b></p>';
        echo '</div>';
    }
    
    public function field_widget_custom_css() {

        if ( !empty( $this->doifd_options[ 'widget_class_textarea' ] ) ) {

            $custom_css = $this->doifd_options[ 'widget_class_textarea' ];
        }
        else {

            $custom_css = '';
        }
        
        echo '<div class="doifd_options_fields">';
        echo '<label><b>' . __( 'Write your custom CSS:', $this->plugin_slug ) . '</b> </label>';
        echo '<div id="doifd_widget_form_custom_css_container">';
        echo '</div>';
        echo '<textarea rows="10" cols="60" name="doifd_lab_options[widget_class_textarea]" id="widget_class_textarea">' . $custom_css . '</textarea>';
        echo '</div>';
        
    }

}

new DOIFDAdminCustonCSSFields();
