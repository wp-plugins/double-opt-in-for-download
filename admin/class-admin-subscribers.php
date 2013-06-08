<?php

class DoifdSubscribers {

    public function __construct() {
        
    }

    public function get_subscriber_count() {

        global $wpdb;

// get the total count of subscribers

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . "doifd_lab_subscribers ";

        $count = $wpdb->get_var ( $sql );

        return $count;

    }

}

?>
