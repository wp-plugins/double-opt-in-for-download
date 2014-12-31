<?php

class DOIFDManageSubscribers extends DOIFDAdmin {

    public function __construct() {
//        parent::__construct();

//        add_action ( 'admin_init', array(
//            $this,
//            'doifd_subscriber_delete' ) );
    }

//    public function doifd_subscriber_delete() {
//
//        global $wpdb;
//
//        if ( isset ( $_REQUEST[ 'subscriber_id' ] ) && ( current_user_can ( 'manage_options' ) ) ) {
//
//            $doifd_lab_nonce = $_REQUEST[ '_wpnonce' ];
//            $id = $_REQUEST[ 'subscriber_id' ];
//
//            if ( ! wp_verify_nonce ( $doifd_lab_nonce, 'doifd-delete-subscriber-nonce' ) ) wp_die ( 'Security check' );
//
//
//            $delete = $wpdb->delete ( $wpdb->prefix . 'doifd_lab_subscribers', array(
//                'doifd_subscriber_id' => $id ), array(
//                '%d' ) );
//
//            if ( $delete > 0 ) {
//
//                $text = $delete . ' ' . __ ( 'Subscriber deleted successfully', $this->plugin_slug );
//
//                echo '<div class="updated"><p><strong>' . $text . '</strong></p></div>';
//            } else {
//
//                $text = __ ( 'There was a problem deleting this subscriber', $this->plugin_slug );
//
//                echo '<div class="error"><p><strong>' . $text . '</strong></p></div>';
//            }
//        }
//    }

}
new DOIFDManageSubscribers();