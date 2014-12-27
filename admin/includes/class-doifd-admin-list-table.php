<?php

class DOIFDAdminListTable extends DOIFD_List_Table {

    public function __construct() {
        global $status , $page ;

        //Set parent defaults
        parent::__construct ( array (
            'singular'=>'id' , //singular name of the listed records
            'plural'=>'ids' , //plural name of the listed records
            'ajax'=>false        //does this table support ajax?
        ) ) ;
    }

    public function column_default( $item , $column_name ) {
        switch ( $column_name ) {
            case 'Download Name':
            case 'Landing Page':
            case 'Shortcode':
                return $item[$column_name] ;
            default:
                return print_r ( $item , true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    public function column_doifd_download_name( $item ) {

        $doifd_lab_nonce = wp_create_nonce ( 'doifd-delete-download-nonce' ) ;
        //Build row actions
        $actions = array (
            'delete'=>sprintf ( '<a data-confirm="' . __ ( 'You are about to send this list into the cyber abyss! Are you sure?', $this->plugin_slug ) . '" href="?&action=%s&page=%s&_wpnonce=%s&id=%s&doifd_file_name=%s" id="' . $item[ "doifd_download_id" ] . '" title="' . $item[ "doifd_download_name" ] . '" >' . __ ( 'Delete', $this->plugin_slug ) . '</a>', 'doifd_download_delete', $_REQUEST[ 'page' ], $doifd_lab_nonce, $item[ 'doifd_download_id' ], $item[ 'doifd_download_file_name' ]  ) ,
            'edit'=>sprintf ( '<a href="#" class="doifdEditUploadModal" id="%s">' . __ ( 'Edit', $this->plugin_slug ) . '</a>', $item[ 'doifd_download_id' ] ) ,
                ) ;

        //Return the title contents
        return sprintf ( '%1$s <span style="color:silver">(id:%2$s)</span>%3$s' ,
                        /* $1%s */ $item['doifd_download_name'] ,
                        /* $2%s */ $item['doifd_download_id'] ,
                        /* $4%s */ $this->row_actions ( $actions )
                ) ;
    }

    public function column_shortcode( $item ) {

        //Return the shortcode
        return sprintf ( '%1$s' ,
                        /* $1%s */ '[lab_subscriber_download_form download_id=' . $item['doifd_download_id'] . ']'
                ) ;
    }
    
    function column_doifd_landing_page( $item ) {

        if ($item['doifd_download_landing_page'] != '0') {
        //Return the landing page assigned
        return sprintf ( '%1$s' ,
                        /* $1%s */ get_the_title ( $item['doifd_download_landing_page'] )
                ) ;
        }  else {
            
            return sprintf ( '%1$s' ,
                        /* $1%s */ '<div class="no_landing">' . __( 'No Landing Page Selected.', 'double-opt-in-for-download') . '<br />' . __( 'Edit Download To Select Landing Page.', 'double-opt-in-for-download') . '</div>'
                ) ;
            
        }
    }

    function column_cb( $item ) {
        $doifd_lab_nonce = wp_create_nonce ( 'doifd-delete-download-nonce' ) ;
        return sprintf (
                        '<input type="checkbox" name="id[]" value="%1$s" />
                         <input type="hidden" name="doifd_file_name[]" value="%2$s" />' ,
                        /* $1%s */ $item['doifd_download_id'] , //The value of the checkbox should be the record's id
                        /* $2%s */ $item['doifd_download_file_name'] //The value of the checkbox should be the record's id
                ) ;
    }

    function get_columns() {
        $columns = array (
            'cb'=>'<input type="checkbox" />' , //Render a checkbox instead of text
            'doifd_download_name'=>__( 'Download Name',  $this->plugin_slug ) ,
            'doifd_landing_page'=>__( 'Landing Page' ,  $this->plugin_slug ),
            'shortcode'=> __( 'Shortcode' ,  $this->plugin_slug )
                ) ;
        return apply_filters( 'doifd_download_table_headers', $columns ) ;
    }

    function get_sortable_columns() {
        $sortable_columns = array (
            'doifd_download_name'=>array ( 'doifd_download_name' , false ) //true means it's already sorted
                ) ;
        return $sortable_columns ;
    }

    function get_bulk_actions() {
        $actions = array (
            'delete'=>'Delete'
                ) ;
        return $actions ;
    }

    function process_bulk_action() {
        global $wpdb ;
        $table_name = $wpdb->prefix . 'doifd_lab_downloads' ; // do not forget about tables prefix

        if ( 'delete' === $this->current_action () ) {
            $ids = isset ( $_REQUEST['id'] ) ? $_REQUEST['id'] : array ( ) ;
            if ( is_array ( $ids ) )
                $ids = implode ( ',' , $ids ) ;

            if ( ! empty ( $ids ) ) {
                $wpdb->query ( "DELETE FROM $table_name WHERE doifd_download_id IN($ids)" ) ;
            }

            $file = isset ( $_REQUEST['doifd_file_name'] ) ? $_REQUEST['doifd_file_name'] : array ( ) ;
            if ( is_array ( $file ) )
                $file = implode ( ',' , $file ) ;
            $file = explode ( ',' , $file ) ;

            foreach ( $file as $key=> $value ) {
                unlink ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . $value ) ;
            }
        }
    }

    function prepare_items() {
        global $wpdb ; //This is used only if making any database queries

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 5 ;

        $columns = $this->get_columns () ;
        $hidden = array ( ) ;
        $sortable = $this->get_sortable_columns () ;


        $this->_column_headers = array ( $columns , $hidden , $sortable ) ;

        $this->process_bulk_action () ;

        $sql = "SELECT * FROM " . $wpdb->prefix . "doifd_lab_downloads WHERE doifd_download_type = '1'" ;
        $doifd_lab_result = $wpdb->get_results ( $sql , ARRAY_A ) ;

        $data = $doifd_lab_result ;

        function list_usort_reorder( $a , $b ) {
            $orderby = ( ! empty ( $_REQUEST['orderby'] )) ? $_REQUEST['orderby'] : 'doifd_download_name' ; //If no sort, default to title
            $order = ( ! empty ( $_REQUEST['order'] )) ? $_REQUEST['order'] : 'asc' ; //If no order, default to asc
            $result = strcmp ( $a[$orderby] , $b[$orderby] ) ; //Determine sort order
            return ($order === 'asc') ? $result : -$result ; //Send final sort direction to usort
        }

        usort ( $data , 'list_usort_reorder' ) ;

        $current_page = $this->get_pagenum () ;

        $total_items = count ( $data ) ;

        $data = array_slice ( $data , (($current_page - 1) * $per_page ) , $per_page ) ;

        $this->items = $data ;

        $this->set_pagination_args ( array (
            'total_items'=>$total_items , //WE have to calculate the total number of items
            'per_page'=>$per_page , //WE have to determine how many items to show on a page
            'total_pages'=>ceil ( $total_items / $per_page )   //WE have to calculate the total number of pages
        ) ) ;
    }
    
    public function display_doifd_upload_form() {

        if ( class_exists ( 'DOIFDPremiumAdmin' ) ) {

            ob_start ();
            include_once DOIFD_DIR . '/premium/views/view-admin-upload-form.php';
            $form = ob_get_contents ();
            ob_end_flush ();
            return $form;
        } else {
            ob_start ();
            include_once DOIFD_DIR . '/admin/views/view-admin-upload-form.php';
            $form = ob_get_contents ();
            ob_end_flush ();

            return $form;
        }
    }

    public function display_download_edit_form() {

        if ( class_exists ( 'DOIFDPremiumAdmin' ) ) {

            ob_start ();
            include_once DOIFD_DIR . '/premium/views/view-admin-edit-download.php';
            $editForm = ob_get_contents ();
            ob_end_flush ();
        } else {

            ob_start ();
            include_once DOIFD_DIR . '/admin/views/view-admin-edit-upload-form.php';
            $editForm = ob_get_contents ();
            ob_end_flush ();
        }

        return $editForm;
    }
    
    public function display_doifd_list_form() {

        if ( class_exists ( 'DOIFDPremiumAdmin' ) ) {

            ob_start ();
            include_once DOIFD_DIR . '/premium/views/view-admin-list-form.php';
            $form = ob_get_contents ();
            ob_end_flush ();
            return $form;
        } else {
            ob_start ();
            include_once DOIFD_DIR . '/admin/views/view-admin-list-form.php';
            $form = ob_get_contents ();
            ob_end_flush ();

            return $form;
        }
    }

}