<?php

if ( ! class_exists ( 'WP_List_Table' ) ) {
    
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Doifd_Subscriber_Table extends WP_List_Table {

    function __construct() {
        global $status, $page;

        //Set parent defaults
        parent::__construct ( array (
            'singular' => 'subscriber', //singular name of the listed records
            'plural' => 'subscribers', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ) );
    }

    function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'Subscriber Name':
            case 'Email Address':
            case 'Download Name':
            case 'Successful Downloads':
            case 'Date / Time':
                return $item[ $column_name ];
        }
    }

    function column_name( $item ) {

        $doifd_lab_nonce = wp_create_nonce ( 'doifd-delete-subscriber-nonce' );
        //Build row actions
        $actions = array (
            'resend' => sprintf ( '<a href="?page=%s&action=%s&name=%s&user_name=%s&user_email=%s&user_ver=%s&download_id=%s" >' . __('Resend Email', 'double-opt-in-for-download' ) . '</a>', $_REQUEST[ 'page' ], 'doifd_lab_resend_verification_email', 'doifd_lab_resend_verification_email', $item[ 'name' ] , $item[ 'email' ], $item[ 'ver' ], $item[ 'download_id' ] ),
            'delete' => sprintf ( '<a href="?page=%s&action=%s&name=%s&_wpnonce=%s&id=%s" class="confirm" >' . __( 'Delete', 'double-opt-in-for-download' ) . '</a>', $_REQUEST[ 'page' ], 'delete', 'delete' , $doifd_lab_nonce, $item[ 'subscriber_id' ] ),
        );

        //Return the title contents
        return sprintf ( '%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
                        /* $1%s */ $item[ 'name' ],
                        /* $2%s */ $item[ 'subscriber_id' ],
                        /* $4%s */ $this -> row_actions ( $actions )
        );
    }

    function column_email( $item ) {

        //Return the title contents
        return sprintf ( '%1$s',
                        /* $1%s */ $item[ 'email' ]
        );
    }

    function column_download_name( $item ) {

        //Return the title contents
        return sprintf ( '%1$s',
                        /* $1%s */ $item[ 'download_name' ]
        );
    }
        
    function column_time( $item ) {

        //Return the title contents
        return sprintf ( '%1$s',
                        /* $1%s */ $item[ 'time' ]
        );
    }
    
    function column_verified( $item ) {
        
        if ($item['verified'] == '0') {
            return __('No', 'double-opt-in-for-download');
        } elseif ($item['verified'] == '1') {
            return __('Yes', 'double-opt-in-for-download');
        } else {
            return '';
        }

    }
    
    function column_allowed( $item ) {

        //Return the title contents
        return sprintf ( '%1$s',
                        /* $1%s */ $item[ 'allowed' ]
        );
    }

    function column_cb( $item ) {
        
        return sprintf (
                        '<input type="checkbox" name="id[]" value="%1$s" />',
                        /* $1%s */ $item[ 'subscriber_id' ] //The value of the checkbox should be the record's id
        );
    }

    function get_columns() {
        $columns = array (
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'name' => __( 'Name', 'double-opt-in-for-download' ),
            'email' => __( 'Email Address', 'double-opt-in-for-download' ),
            'verified' => __( 'Verified', 'double-opt-in-for-download' ),
            'download_name' => __( 'Download Name', 'double-opt-in-for-download' ),
            'allowed' => __( 'Successful Downloads' , 'double-opt-in-for-download' ),
            'time' => __( 'Date / Time' , 'double-opt-in-for-download' )
        );
        return apply_filters('doifd_subscriber_table_headers',  $columns );
    }

    function get_sortable_columns() {
        $sortable_columns = array (
            'name' => array ( 'name', false ), //true means it's already sorted
            'email' => array ( 'email', false ),
            'download_name' => array ( 'download_name', false ),
            'time' => array ( 'time', false )
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array (
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action() {

        global $wpdb;
        
        $table_name = $wpdb->prefix . 'doifd_lab_subscribers'; // do not forget about tables prefix

        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this -> current_action () ) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);
            
            // delete subscriber from subscriber table
            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE doifd_subscriber_id IN($ids)");
            }
        }
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 5;

        $columns = $this -> get_columns ();
        $hidden = array ( );
        $sortable = $this -> get_sortable_columns ();


        $this -> _column_headers = array ( $columns, $hidden, $sortable );

        $this -> process_bulk_action ();

        $sql = "SELECT " . $wpdb -> prefix . "doifd_lab_subscribers.doifd_name AS name, "
                . $wpdb -> prefix . "doifd_lab_subscribers.time, "
                . $wpdb -> prefix . "doifd_lab_subscribers.doifd_email AS email, "
                . $wpdb->prefix . "doifd_lab_subscribers.doifd_email_verified AS verified, "
                . $wpdb -> prefix . "doifd_lab_subscribers.doifd_subscriber_id AS subscriber_id, "
                . $wpdb -> prefix . "doifd_lab_subscribers.doifd_verification_number AS ver, "
                . $wpdb -> prefix . "doifd_lab_subscribers.doifd_download_id, "
                . $wpdb -> prefix . "doifd_lab_subscribers.doifd_downloads_allowed AS allowed, "
                . $wpdb -> prefix . "doifd_lab_downloads.doifd_download_name AS download_name, "
                . $wpdb -> prefix . "doifd_lab_downloads.doifd_download_id AS download_id
            FROM " . $wpdb -> prefix . "doifd_lab_subscribers
            LEFT JOIN " . $wpdb -> prefix . "doifd_lab_downloads ON " . $wpdb -> prefix . "doifd_lab_subscribers.doifd_download_id =" . $wpdb -> prefix . "doifd_lab_downloads.doifd_download_id ";
        $subscribers = $wpdb -> get_results ( $sql, ARRAY_A );

        $data = $subscribers;

        function usort_reorder( $a, $b ) {
            $orderby = ( ! empty ( $_REQUEST[ 'orderby' ] )) ? $_REQUEST[ 'orderby' ] : 'name'; //If no sort, default to title
            $order = ( ! empty ( $_REQUEST[ 'order' ] )) ? $_REQUEST[ 'order' ] : 'asc'; //If no order, default to asc
            $result = strcmp ( $a[ $orderby ], $b[ $orderby ] ); //Determine sort order
            return ($order === 'asc') ? $result : -$result; //Send final sort direction to usort
        }

        usort ( $data, 'usort_reorder' );

        $current_page = $this -> get_pagenum ();

        $total_subscribers = count ( $data );

        $data = array_slice ( $data, (($current_page - 1) * $per_page ), $per_page );

        $this -> items = $data;

        $this -> set_pagination_args ( array (
            'total_items' => $total_subscribers, //WE have to calculate the total number of items
            'per_page' => $per_page, //WE have to determine how many items to show on a page
            'total_pages' => ceil ( $total_subscribers / $per_page )   //WE have to calculate the total number of pages
        ) );
    }

}