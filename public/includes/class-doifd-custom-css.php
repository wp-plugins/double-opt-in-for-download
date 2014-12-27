<?php

class DOIFDCustomCSS extends DOIFD {
    
    public function __construct() {
        parent::__construct();
        
        add_action( 'wp_head', array( $this, 'add_css_to_head' ) );
    }
    
    public function add_css_to_head() {

        if (!empty($this->doifd_options['form_class_textarea'])) {

            $form_custom_css = $this->doifd_options['form_class_textarea'];
        } else {

            $form_custom_css = '';
        }

        if (!empty($this->doifd_options['widget_class_textarea'])) {

            $widget_custom_css = $this->doifd_options['widget_class_textarea'];
        } else {

            $widget_custom_css = '';
        }

        if ((!empty($form_custom_css) ) || (!empty($widget_custom_css) )) {
            echo "\n";
            echo '<style type="text/css" media="screen">' . "\n";
            echo "\n";
            echo '/* DOIFD Form Custom CSS */' . "\n" . "\n";
        }
        if (!empty($form_custom_css)) {
            echo $form_custom_css . "\n";
            echo "\n";
        }
        if (!empty($widget_custom_css)) {
            echo "\n";
            echo $widget_custom_css . "\n";
            echo "\n";
            echo '</style>' . "\n";
        } else {
            echo '</style>' . "\n";
        }
    }
}
new DOIFDCustomCSS();
