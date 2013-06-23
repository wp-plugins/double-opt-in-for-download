<?php

if ( ! class_exists ( 'WP_List_Table' ) ) {
    
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

class Doifd_Download_Table extends WP_List_Table {

    function __construct() {
        global $status , $page ;

        //Set parent defaults
        parent::__construct ( array (
            'singular'=>'id' , //singular name of the listed records
            'plural'=>'ids' , //plural name of the listed records
            'ajax'=>false        //does this table support ajax?
        ) ) ;
    }

    function column_default( $item , $column_name ) {
        switch ( $column_name ) {
            case 'Download Name':
            case 'File Type':
            case 'Shortcode':
            case 'Downloads':
                return $item[$column_name] ;
            default:
                return print_r ( $item , true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    function column_doifd_download_name( $item ) {

        $doifd_lab_nonce = wp_create_nonce ( 'doifd-delete-download-nonce' ) ;
        //Build row actions
        $actions = array (
            'delete'=>sprintf ( '<a class="confirm" href="?page=%s&action=%s&_wpnonce=%s&id=%s&doifd_file_name=%s" id="' . $item["doifd_download_id"] .'" title="' . $item["doifd_download_name"] .'" >Delete</a>' , $_REQUEST['page'] , 'delete' , $doifd_lab_nonce , $item['doifd_download_id'] , $item['doifd_download_file_name'] ) ,
            'edit'=>sprintf ( '<a href="?page=%s&doifd_download_id=%s&doifd_download_name=%s&doifd_download_file_name=%s">Edit</a>' , 'double-opt-in-for-download/admin/doifd-admin.php_edit_downloads' , $item['doifd_download_id'] , $item['doifd_download_name'] , $item['doifd_download_file_name'] ) ,
                ) ;

        //Return the title contents
        return sprintf ( '%1$s <span style="color:silver">(id:%2$s)</span>%3$s' ,
                        /* $1%s */ $item['doifd_download_name'] ,
                        /* $2%s */ $item['doifd_download_id'] ,
                        /* $4%s */ $this->row_actions ( $actions )
                ) ;
    }

    function column_type( $item ) {

        // assign file names to variable
        $filename = $item['doifd_download_file_name'] ;

        // get the file extension
        $ext = pathinfo ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . $filename , PATHINFO_EXTENSION ) ;

        // show the appropriate image for the file extension
        if ( $ext == 'pdf' ) {
            $img = '<img src="' . DOUBLE_OPT_IN_FOR_DOWNLOAD_IMG_URL . 'pdf.png" alt="PDF File Icon" class="document_icon" />' ;
        }
        if ( $ext == 'jpg' || $ext == 'jpeg' ) {
            $img = '<img src="' . DOUBLE_OPT_IN_FOR_DOWNLOAD_IMG_URL . 'jpg.png" alt="JPG File Icon" class="document_icon" />' ;
        }
        if ( $ext == 'png' ) {
            $img = '<img src="' . DOUBLE_OPT_IN_FOR_DOWNLOAD_IMG_URL . 'png.png" alt="PNG File Icon" class="document_icon" />' ;
        }
        if ( $ext == 'bmp' ) {
            $img = '<img src="' . DOUBLE_OPT_IN_FOR_DOWNLOAD_IMG_URL . 'bmp.png" alt="Bitmap File Icon" class="document_icon" />' ;
        }
        if ( $ext == 'gif' ) {
            $img = '<img src="' . DOUBLE_OPT_IN_FOR_DOWNLOAD_IMG_URL . 'gif.png" alt="GIF File Icon" class="document_icon" />' ;
        }
        if ( $ext == 'zip' ) {
            $img = '<img src="' . DOUBLE_OPT_IN_FOR_DOWNLOAD_IMG_URL . 'zip.png" alt="ZIP File Icon" class="document_icon" />' ;
        }
        if ( $ext == 'doc' ) {
            $img = '<img src="' . DOUBLE_OPT_IN_FOR_DOWNLOAD_IMG_URL . 'doc.png" alt="MS Word DOC File Icon" class="document_icon" />' ;
        }
        if ( $ext == 'docx' ) {
            $img = '<img src="' . DOUBLE_OPT_IN_FOR_DOWNLOAD_IMG_URL . 'docx.png" alt="MS Word DOCX File Icon" class="document_icon" />' ;
        }
        if ( $ext == 'mp3' ) {
            $img = '<img src="' . DOUBLE_OPT_IN_FOR_DOWNLOAD_IMG_URL . 'mp3.png" alt="MP3 File Icon" class="document_icon" />' ;
        }

        //Return the file type image contents
        return sprintf ( '%1$s' ,
                        /* $1%s */ $img
                ) ;
    }

    function column_shortcode( $item ) {

        //Return the shortcode
        return sprintf ( '%1$s' ,
                        /* $1%s */ '[lab_subscriber_download_form download_id=' . $item['doifd_download_id'] . ']'
                ) ;
    }

    function column_doifd_number_of_downloads( $item ) {

        //Return the number of downloads
        return sprintf ( '%1$s' ,
                        /* $1%s */ $item['doifd_number_of_downloads']
                ) ;
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
            'doifd_download_name'=>'Download Name' ,
            'type'=>'File Type' ,
            'shortcode'=>'Shortcode' ,
            'doifd_number_of_downloads'=>'Downloads'
                ) ;
        return $columns ;
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

        $sql = "SELECT * FROM " . $wpdb->prefix . "doifd_lab_downloads " ;
        $doifd_lab_result = $wpdb->get_results ( $sql , ARRAY_A ) ;

        $data = $doifd_lab_result ;

        function usort_reorder( $a , $b ) {
            $orderby = ( ! empty ( $_REQUEST['orderby'] )) ? $_REQUEST['orderby'] : 'doifd_download_name' ; //If no sort, default to title
            $order = ( ! empty ( $_REQUEST['order'] )) ? $_REQUEST['order'] : 'asc' ; //If no order, default to asc
            $result = strcmp ( $a[$orderby] , $b[$orderby] ) ; //Determine sort order
            return ($order === 'asc') ? $result : -$result ; //Send final sort direction to usort
        }

        usort ( $data , 'usort_reorder' ) ;

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

}