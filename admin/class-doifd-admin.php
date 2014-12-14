<?php

//        require_once( DOIFD_DIR . '/admin/includes/class-doifd-custom-post-type.php' );
//        require_once( DOIFD_DIR . '/admin/includes/class-doifd-post-meta.php' );
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-enqueue.php' );
        require_once( DOIFD_DIR . 'includes/class-doifd-email.php' );
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-menu.php' );
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-manage-downloads.php');
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-manage-subscribers.php');
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-general-settings.php' );
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-validation.php' );
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-email-settings.php' );
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-form-settings.php' );
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-widget-settings.php' );
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-custom-css-settings.php' );
        require_once( DOIFD_DIR . 'admin/includes/class-admin-csv.php');
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-messages.php');
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-download-table.php');
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-list-table.php');
        require_once( DOIFD_DIR . 'admin/includes/class-doifd-admin-subscriber-table.php');

class DOIFDAdmin extends DOIFD {

    protected static $instance = null;

    public function __construct() {
        parent::__construct();
        
        $plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
        add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
        
    }
    
    public static function get_instance() {

        if( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }
    
    public function get_options() {

        $this->doifd_options = get_option( 'doifd_lab_options' );

        return $this->doifd_options;
    }

        public function add_action_links( $links ) {

        return array_merge(
                array(
            'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
                ), $links
        );
    }
    
    public function doifd_lab_resend_verification_email() {

        $resend_email = new DoifdEmail();
        $resend_email->admin_resend_verification_email();
    }

}
new DOIFDAdmin();