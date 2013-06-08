<?php

class DoifdadminWidget {

    public function __construct()
    {
        add_action ( 'wp_dashboard_setup', array( &$this, 'doifd_lab_add_dashboard_widgets' ) );
    }
    
    function doifd_lab_dashboard_widget_function() {

        global $wpdb;

        // get the total count of subscribers

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . "doifd_lab_subscribers ";
        $doifd_subscriber_count = $wpdb->get_var ( $sql );

        // get the total count of downloads

        $sql = "SELECT SUM(doifd_number_of_downloads) FROM " . $wpdb->prefix . "doifd_lab_downloads ";
        $doifd_download_count = $wpdb->get_var ( $sql );

        // get downloads

        $sql = "SELECT * FROM " . $wpdb->prefix . "doifd_lab_downloads ";
        $doifd_downloads_result = $wpdb->get_results ( $sql, ARRAY_A );

        // display a mini download table with subscriber and download counts

        echo '<table class="doifd_admin_widget_table">';
        echo '<tr>';
        echo '<th class="doifd_admin_widget_th">Total Subscribers: ' . $doifd_subscriber_count . '</th>';
        echo '<th class="doifd_admin_widget_th">Overall Total Downloads: ' . $doifd_download_count . '</th>';
        echo '</tr>';
        foreach ( $doifd_downloads_result as $value ) {
            echo '<tr>';
            echo "<td class='doifd_admin_widget_td'>" . $value[ 'doifd_download_name' ] . "</td>";
            echo "<td class='doifd_admin_widget_td'>" . $value[ 'doifd_number_of_downloads' ] . "</td>";
            echo '</tr>';
        }
        echo '</table>';

    }

    function doifd_lab_add_dashboard_widgets() {

        wp_add_dashboard_widget ( 'doifd_dashboard_widget', 'Double Opt-In For Downloads', array( &$this, 'doifd_lab_dashboard_widget_function' ) );

    }
}

?>
