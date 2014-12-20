<?php

class DOIFDAdminDownloadTable extends DOIFDAdmin {

    protected $data = array( );
    protected $display_limit = 5;
    protected $pagenum;

    public function __construct() {
        parent::__construct();
        
        global $current_screen;
        
        if (!isset($current_screen))
            return null;
        
        $this->pagenum = $this->get_pagenum ();
        
        if($current_screen->id == 'doifd_page_doifd-admin-menu_downloads'){
        $this->data = $this->get_data ();
        }
    }

    public function get_data() {

        global $wpdb;

        $offset = ( $this->pagenum - 1 ) * $this->display_limit;

        $sql = "SELECT * FROM " . $wpdb->prefix . "doifd_lab_downloads WHERE doifd_download_type = '0' LIMIT $offset, $this->display_limit ";

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
            'doifd_download_name' => __ ( 'Download Name', $this->plugin_slug ),
            'type' => __ ( 'File Type', $this->plugin_slug ),
            'doifd_download_landing_page' => __ ( 'Landing Page', $this->plugin_slug ),
            'shortcode' => __ ( 'Shortcode', $this->plugin_slug ),
            'doifd_number_of_downloads' => __ ( 'Downloads', $this->plugin_slug )
        );

        return $columns;
    }

    public function prepare_rows() {

        global $wpdb;

        $row = '';
        if ( ! empty ( $this->data ) ) {

            foreach ( $this->data as $key => $value ) {

                $row .= '<tr>';
                $row .= '<td width="30%" class="column-columnname">' . $this->column_doifd_download_name ( $value ) . '</td>';
                $row .= '<td width="10%" class="column-columnname">' . $this->column_type ( $value ) . '</td>';
                $row .= '<td width="20%" class="column-columnname">' . $this->column_doifd_landing_page ( $value ) . '</td>';
                $row .= '<td width="30%" class="column-columnname">[lab_subscriber_download_form download_id=' . $value[ 'doifd_download_id' ] . ']</td>';
                $row .= '<td width="5%" class="column-columnname">' . $value[ 'doifd_number_of_downloads' ] . '</td>';
                $row .= '<tr>';
            }
        } else {


            $row = '<td colspan="6" align="center">No Downloads Yet</td>';
        }

        echo $row;
    }

    public function column_doifd_download_name( $item ) {

        $doifd_lab_nonce = wp_create_nonce ( 'doifd-delete-download-nonce' );
        //Build row actions
        $actions = array(
            'delete' => sprintf ( '<a data-confirm="' . __ ( 'You are about to send this download into the cyber abyss! Are you sure?', $this->plugin_slug ) . '" href="?&action=%s&page=%s&_wpnonce=%s&id=%s&doifd_file_name=%s" id="' . $item[ "doifd_download_id" ] . '" title="' . $item[ "doifd_download_name" ] . '" >' . __ ( 'Delete', $this->plugin_slug ) . '</a>', 'doifd_download_delete', $_REQUEST[ 'page' ], $doifd_lab_nonce, $item[ 'doifd_download_id' ], $item[ 'doifd_download_file_name' ] ),
            'edit' => sprintf ( '<a href="#" class="doifdEditUploadModal" id="%s">' . __ ( 'Edit', $this->plugin_slug ) . '</a>', $item[ 'doifd_download_id' ] ),
        );

        //Return the title contents
        return sprintf ( '%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
                /* $1%s */ $item[ 'doifd_download_name' ],
                /* $2%s */ $item[ 'doifd_download_id' ],
                /* $4%s */ $this->row_actions ( $actions )
        );
    }

    public function column_doifd_landing_page( $item ) {

        if ( $item[ 'doifd_download_landing_page' ] != '0' ) {
            //Return the landing page assigned
            return sprintf ( '%1$s',
                    /* $1%s */ get_the_title ( $item[ 'doifd_download_landing_page' ] )
            );
        } else {

            return sprintf ( '%1$s',
                    /* $1%s */ '<div class="no_landing">' . __ ( 'No Landing Page Selected.', $this->plugin_slug ) . '<br />' . __ ( 'Edit Download To Select Landing Page.', $this->plugin_slug ) . '</div>'
            );
        }
    }

    function column_type( $item ) {

        // assign file names to variable
//        $filename = $item[ 'doifd_download_file_name' ];

        if ( isset ( $item[ 'doifd_download_type' ] ) ) {

            $type = $item[ 'doifd_download_type' ];
        } else {

            $type = '0';
        }

        // get the file extension
        $ext = pathinfo ( DOIFD_DOWNLOAD_DIR . $item[ 'doifd_download_file_name' ], PATHINFO_EXTENSION );

        // show the appropriate image for the file extension
        if ( $ext == 'pdf' ) {
            $img = '<img src="' . DOIFD_URL . 'admin/assets/img/pdf.png" alt="PDF File Icon" class="document_icon" />';
        } elseif ( $ext == 'jpg' || $ext == 'jpeg' ) {
            $img = '<img src="' . DOIFD_URL . 'admin/assets/img/jpg.png" alt="JPG File Icon" class="document_icon" />';
        } elseif ( $ext == 'png' ) {
            $img = '<img src="' . DOIFD_URL . 'admin/assets/img/png.png" alt="PNG File Icon" class="document_icon" />';
        } elseif ( $ext == 'bmp' ) {
            $img = '<img src="' . DOIFD_URL . 'admin/assets/img/bmp.png" alt="Bitmap File Icon" class="document_icon" />';
        } elseif ( $ext == 'gif' ) {
            $img = '<img src="' . DOIFD_URL . 'admin/assets/img/gif.png" alt="GIF File Icon" class="document_icon" />';
        } elseif ( $ext == 'zip' ) {
            $img = '<img src="' . DOIFD_URL . 'admin/assets/img/zip.png" alt="ZIP File Icon" class="document_icon" />';
        } elseif ( $ext == 'doc' ) {
            $img = '<img src="' . DOIFD_URL . 'admin/assets/img/doc.png" alt="MS Word DOC File Icon" class="document_icon" />';
        } elseif ( $ext == 'docx' ) {
            $img = '<img src="' . DOIFD_URL . 'admin/assets/img/docx.png" alt="MS Word DOCX File Icon" class="document_icon" />';
        } elseif ( $ext == 'mp3' ) {
            $img = '<img src="' . DOIFD_URL . 'admin/assets/img/mp3.png" alt="MP3 File Icon" class="document_icon" />';
        } elseif ( ($ext == '') && ( $type == '1' ) ) {
            $img = '<img src="' . DOIFD_URL . 'admin/assets/img/list.png" alt="List Icon" class="document_icon" />';
        } else {
            $img = '';
        }

        return apply_filters ( 'doifd_download_table_type', $img, $type, $ext );
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
