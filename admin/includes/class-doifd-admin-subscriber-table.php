<?php

class DOIFDAdminSubscriberTable extends DOIFDAdmin {

    protected $data = array( );
    protected $display_limit = 5;
    protected $pagenum;

    public function __construct() {
        parent::__construct();
        
        global $current_screen;
        
        if (!isset($current_screen))
            return null;
        
        $this->pagenum = $this->get_pagenum ();
        
        if($current_screen->id == 'doifd_page_doifd-admin-menu_subscribers') {
        $this->data = $this->get_data ();
        }
  
    }

    public function get_data() {

        global $wpdb;
            
        $offset = ( $this->pagenum - 1 ) * $this->display_limit;

        $sql = "SELECT " . $wpdb->prefix . "doifd_lab_subscribers.doifd_name AS name, "
                . $wpdb->prefix . "doifd_lab_subscribers.time, "
                . $wpdb->prefix . "doifd_lab_subscribers.doifd_email AS email, "
                . $wpdb->prefix . "doifd_lab_subscribers.doifd_email_verified AS verified, "
                . $wpdb->prefix . "doifd_lab_subscribers.doifd_subscriber_id AS subscriber_id, "
                . $wpdb->prefix . "doifd_lab_subscribers.doifd_verification_number AS ver, "
                . $wpdb->prefix . "doifd_lab_subscribers.doifd_download_id, "
                . $wpdb->prefix . "doifd_lab_subscribers.doifd_downloads_allowed AS allowed, "
                . $wpdb->prefix . "doifd_lab_downloads.doifd_download_name AS download_name, "
                . $wpdb->prefix . "doifd_lab_downloads.doifd_download_id AS download_id
            FROM " . $wpdb->prefix . "doifd_lab_subscribers
            LEFT JOIN " . $wpdb->prefix . "doifd_lab_downloads ON " . $wpdb->prefix . "doifd_lab_subscribers.doifd_download_id =" . $wpdb->prefix . "doifd_lab_downloads.doifd_download_id ";
        $data = $wpdb->get_results ( $sql, ARRAY_A );

        return $data;
    }

    public function get_pagenum() {

        if ( isset ( $_GET[ 'pagenum' ] ) ) {

            $value = absint ( $_GET[ 'pagenum' ] );
        } else {

            $value = '1';
        }

        return $value;
    }

    public function get_columns() {

        $columns = array(
            'doifd_name' => __ ( 'Subscriber Name', $this->plugin_slug ),
            'doifd_email' => __ ( 'Email Address', $this->plugin_slug ),
            'doifd_email_verified' => __ ( 'Verified', $this->plugin_slug ),
            'doifd_downloads_allowed' => __ ( 'Successful Downloads', $this->plugin_slug ),
            'time' => __ ( 'Date/Time', $this->plugin_slug )
        );

        return  $columns;
    }

    public function prepare_rows() {

        global $wpdb;

        $row = '';
        if ( ! empty ( $this->data ) ) {

            foreach ( $this->data as $key => $value ) {

                $row .= '<tr>';
                $row .= '<td width="25%" class="column-columnname">' . $this->column_doifd_name ( $value ) . '</td>';
                $row .= '<td width="30%" class="column-columnname">' . $this->column_email ( $value ) . '</td>';
                $row .= '<td width="15%" class="column-columnname">' . $this->column_verified ( $value ) . '</td>';
                $row .= '<td width="15%" class="column-columnname">' . $this->column_allowed ( $value ) . '</td>';
                $row .= '<td width="15%" class="column-columnname">' . $this->column_time ( $value ) . '</td>';
                $row .= '<tr>';
            }
        } else {


            $row = '<td colspan="6" align="center">No Subscribers Yet</td>';
        }

        echo apply_filters ( 'doifd_prepare_subscribers_rows', $row, $this->data );
    }

    public function column_doifd_name( $item ) {

        $doifd_lab_nonce = wp_create_nonce ( 'doifd-delete-subscriber-nonce' );
        //Build row actions
        $actions = array(
            'resend' => sprintf ( '<a href="?page=%s&action=%s&name=%s&user_name=%s&user_email=%s&user_ver=%s&download_id=%s" >' . __ ( 'Resend Email', 'double-opt-in-for-download' ) . '</a>', $_REQUEST[ 'page' ], 'doifd_lab_resend_verification_email', 'doifd_lab_resend_verification_email', $item[ 'name' ], $item[ 'email' ], $item[ 'ver' ], $item[ 'download_id' ] ),
            'delete' => sprintf ( '<a data-confirm="' . __ ( 'You are about to send this subscriber into the cyber abyss! Are you sure?', $this->plugin_slug ) . '"href="?page=%s&action=%s&_wpnonce=%s&subscriber_id=%s" class="confirm" >' . __ ( 'Delete', 'double-opt-in-for-download' ) . '</a>', $_REQUEST[ 'page' ], 'delete', $doifd_lab_nonce, $item[ 'subscriber_id' ] ),
        );

        //Return the title contents
        return sprintf ( '%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
                /* $1%s */ $item[ 'name' ],
                /* $2%s */ $item[ 'subscriber_id' ],
                /* $4%s */ $this->row_actions ( $actions )
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

        if ( $item[ 'verified' ] == '0' ) {
            return __ ( 'No', 'double-opt-in-for-download' );
        } elseif ( $item[ 'verified' ] == '1' ) {
            return __ ( 'Yes', 'double-opt-in-for-download' );
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

    public function display_table() {

        global $wpdb;

//        $offset = ( $pagenum - 1 ) * $limit;
        $total = $wpdb->get_var ( "SELECT COUNT('doifd_download_id') FROM {$wpdb->prefix}doifd_lab_downloads" );
        $num_of_pages = ceil ( $total / $this->display_limit );

//        $entries = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}doifd_lab_downloads LIMIT $offset, $limit" );

        echo '<table class="wp-list-table widefat">';
        echo '<thead>';

        $column = $this->get_columns ();
        if ( ! empty ( $column ) ) {

            foreach ( $column as $key => $value ) {

                echo '<th class="manage-column column-columnname">' . $value . '</th>';
            }
        }

        echo '</tr>';
        echo '</thead>';
        echo '<tfoot>';
        echo '<tr>';

        if ( ! empty ( $column ) ) {

            foreach ( $column as $key => $value ) {

                echo '<th class="manage-column column-columnname">' . $value . '</th>';
            }
        }

        echo '</tr>';
        echo '</tfoot>';
        echo '<tbody>';

        $this->prepare_rows ();


        echo '</tbody>';
        echo '</table>';

        $page_links = paginate_links ( array(
            'base' => add_query_arg ( 'pagenum', '%#%' ),
            'format' => '',
            'prev_text' => __ ( '&laquo;', 'text-domain' ),
            'next_text' => __ ( '&raquo;', 'text-domain' ),
            'total' => $num_of_pages,
            'current' => $this->pagenum
                ) );

        if ( $page_links ) {
            echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
        }
    }

    protected function row_actions( $actions, $always_visible = false ) {
        $action_count = count ( $actions );
        $i = 0;

        if ( ! $action_count ) return '';

        $out = '<div class="' . ( $always_visible ? 'row-actions visible' : 'row-actions' ) . '">';
        foreach ( $actions as $action => $link ) {
 ++ $i;
            ( $i == $action_count ) ? $sep = '' : $sep = ' | ';
            $out .= "<span class='$action'>$link$sep</span>";
        }
        $out .= '</div>';

        return $out;
    }

}
new DOIFDAdminSubscriberTable();