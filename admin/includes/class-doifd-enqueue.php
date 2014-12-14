<?php

class DOIFDEnqueue extends DOIFDAdmin {
    
    public function __construct() {
//        parent::__construct();
        
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
    }
    
    public function enqueue_admin_styles() {
        
        global $doifdPages;
       
        if( !isset( $doifdPages ) ) {
            return;
        }

        $screen = get_current_screen();
        
        if (in_array($screen->id, $doifdPages ))  {          
            wp_enqueue_style( $this->plugin_slug . '-admin-styles', DOIFD_URL . 'admin/assets/css/admin.css', array( ), DOIFD::VERSION );
            wp_enqueue_style( $this->plugin_slug . '-admin-jbox-styles', DOIFD_URL . 'admin/assets/css/jBox.css', array( ), DOIFD::VERSION );
            wp_enqueue_style( $this->plugin_slug . '-admin-jbox-tooltip-style', DOIFD_URL . 'admin/assets/css/TooltipBorder.css', array( ), DOIFD::VERSION );
            wp_enqueue_style( $this->plugin_slug . '-admin-js-ui-styles', DOIFD_URL . 'admin/assets/css/doifd-jquery-ui-min.css', array( ), DOIFD::VERSION );
            wp_enqueue_style( 'thickbox' );
        }
    }

    public function enqueue_admin_scripts() {
        
        global $doifdPages;
    
        if( !isset( $doifdPages ) ) {
            return;
        }

        $screen = get_current_screen();
        
        if (in_array($screen->id, $doifdPages )) {
            wp_enqueue_script( $this->plugin_slug . '-admin-script', DOIFD_URL . 'admin/assets/js/admin.js', array( 'jquery' ), DOIFD::VERSION );
            wp_enqueue_script( $this->plugin_slug . '-admin-jbox-script', DOIFD_URL . 'admin/assets/js/jBox.min.js', array( 'jquery' ), DOIFD::VERSION );
            wp_enqueue_script( $this->plugin_slug . '-admin-validate-script', DOIFD_URL . 'admin/assets/js/jquery.validate.min.js', array( 'jquery' ), DOIFD::VERSION );
            wp_enqueue_script( 'jquery-ui-dialog' );
            wp_enqueue_script( 'jquery-ui-tabs' );
            wp_enqueue_script( 'jquery-ui-accordion' );
            wp_enqueue_media();

            wp_localize_script( $this->plugin_slug . '-admin-script', 'ajaxupload', array(
                'uploadFormURL' => 'TB_inline?width=600&height=550&inlineId=doifd-upload-form',
                'mailchimp' => DOIFD_URL . 'admin/mailchimp-options.php',
                'uploadNonce' => wp_create_nonce( 'doifd-upload-nonce' ),
                'filetoolarge' => __( 'Your selected file exceeds your servers PHP file upload size limit. To learn how to get around your PHP file upload size limit, ', $this->plugin_slug ) . '<a href="http://www.doubleoptinfordownload.com/forums/topic/large-file-how-to-get-around-phps-file-upload-size-limit/" target="new" />' . __( ' Click Here', $this->plugin_slug ) . '</a>',
                'editdownloadform' => DOIFD_URL . 'premium/views/view-admin-edit-download.php'
                    )
            );
        }

    }
}
new DOIFDEnqueue();
