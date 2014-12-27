<?php

class DOIFDDownloads extends DOIFDAdmin {

    public function __construct() {
        parent::__construct();

        add_action( 'admin_init', array( $this, 'upload_file' ) );
        add_action( 'admin_init', array( $this, 'edit_download' ) );
        add_action( 'admin_init', array( $this, 'doifd_download_delete' ) );
    }

    public function upload_file() {
        
        if( isset( $_POST[ 'upload' ] ) && ( current_user_can( 'manage_options' ) ) ) {
            $html = '';
            $errors = array( );
            $success = array( );
            $ddUpload = new DOIFDManageDownloads( $_POST, $_FILES );
            $ddUpload->validateDownload();
            $ddUpload->validateDownloadFile();
            if( $ddUpload->getValidDownload() ) {
                $ddUpload->uploadDownload();
                $success = $ddUpload->getSuccess();
                foreach ( $success as $value ) {
                    $html .= '<div id="message" class="updated"><p><strong>' . $value . '</p></strong></div>';
                }
                echo $html;
            } else {
                $errors = $ddUpload->getErrors();
                foreach ( $errors as $value ) {

                    $html .= '<div id="message" class="error"><p><strong>' . $value . '</p></strong></div>';
                }
                echo $html;
            }
        }
    }

    public function edit_download() {
        if( isset( $_POST[ 'update_download' ] ) && ( current_user_can( 'manage_options' ) ) ) {
            $html = '';
            $errors = array( );
            $ddUpload = new DOIFDManageDownloads( $_POST, $_FILES );
            $ddUpload->validateDownload();
            if( $ddUpload->getValidDownload() ) {
                $ddUpload->handleData();
                if( $_FILES[ 'userfile' ][ 'error' ] == 0 ) {
                    $ddUpload->validateDownloadFile();
                    if( $ddUpload->getValidDownload() ) {
                    $ddUpload->editDownload();
                    }
                }
                $success = $ddUpload->getSuccess();
                foreach ( $success as $value ) {
                    $html .= '<div id="message" class="updated"><p><strong>' . $value . '</p></strong></div>';
                }
                echo $html;
            } else {
                $errors = $ddUpload->getErrors();
                foreach ( $errors as $value ) {

                    $html .= '<div id="message" class="error"><p><strong>' . $value . '</p></strong></div>';
                }
                echo $html;
            }
        }
    }

     public function doifd_download_delete() {

        global $wpdb;

        if ( isset ( $_REQUEST[ 'id' ] ) && ( $_REQUEST[ 'action' ] == 'doifd_download_delete' ) && ( current_user_can ( 'manage_options' ) ) ) {

            $doifd_lab_nonce = $_REQUEST[ '_wpnonce' ];
            $id = $_REQUEST[ 'id' ];

            if ( ! wp_verify_nonce ( $doifd_lab_nonce, 'doifd-delete-download-nonce' ) ) wp_die ( 'Security check' );


            $delete = $wpdb->delete ( $wpdb->prefix . 'doifd_lab_downloads', array(
                'doifd_download_id' => $id ), array(
                '%d' ) );

            if ( $delete > 0 ) {

                unlink ( DOIFD_DOWNLOAD_DIR . $_REQUEST[ 'doifd_file_name' ] );

                $text = $delete . ' ' . __ ( 'file deleted successfully', $this->plugin_slug );

                echo '<div class="updated"><p><strong>' . $text . '</strong></p></div>';
            } else {

                $text = __ ( 'There was a problem deleting your file', $this->plugin_slug );

                echo '<div class="error"><p><strong>' . $text . '</strong></p></div>';
            }
        }
    }

    public function allowed_file_types() {

        $allowed_types = '.jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx, ,mp3';

        return apply_filters( 'upload_form_allowed_file_types', $allowed_types );
    }

}
new DOIFDDownloads();

/* * *****************************************************************************
 * DOIFDManageDownloads is the class that does all the work for the uploading
 * and editing of the download on the admin side.
 * *****************************************************************************
 */

class DOIFDManageDownloads extends DOIFDAdmin {

    protected $errorMessages = array( );
    protected $successMessage = array( );
    protected $params;
    protected $validDownload = true;
    protected $downloadData = array( );
    protected $file = array( );
    protected $ext = '';
    protected $validExt = true;
    protected $newName = '';
    protected $move = false;

    public function __construct( $params, $file ) {
//        parent::__construct();

        if( empty( $params ) || !is_array( $params ) ) {
            throw new Exception( "Invalid data!" );
        }

        $this->params = $params;
        if( isset( $this->params[ 'upload' ] ) ) {
            $this->downloadData = $this->getCleanUploadData();
        }
        if( isset( $this->params[ 'update_download' ] ) ) {
            $this->downloadData = $this->getCleanEditData();
        }
        $this->file = $file;
        $this->ext = $this->getExtension();
        $this->validExt = $this->getValidExtension();
        $this->newName = $this->renameDownload();
    }

    public function getValidDownload() {
        return $this->validDownload;
    }

    public function getErrors() {
        return $this->errorMessages;
    }

    public function getSuccess() {
        return $this->successMessage;
    }

    public function getCleanUploadData() {
        $ddClean = new DOIFDAdminValidation();
        $this->downloadData = $ddClean->admin_form_upload_validation( $this->params );
        return $this->downloadData;
    }

    public function getCleanEditData() {
        $ddClean = new DOIFDAdminValidation();
        $this->downloadData = $ddClean->admin_edit_validation( $this->params );
        return $this->downloadData;
    }

    public function getExtension() {
        if(!$this->params['type'] == '1') {
        $this->ext = substr( strrchr( $this->file[ 'userfile' ][ 'name' ], '.' ), 1 );
        }
        return $this->ext;
    }

    public function getValidExtension() {

        $validExtension = apply_filters('premium_allowed_upload_types', $validExtension = array( 'jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf', 'zip', 'doc', 'docx', 'mp3') );
        
        if (in_array($this->ext, $validExtension)) {
            $this->validExt = true;
        } else {
            $this->validExt = false;
        }
        return $this->validExt;
    }

    public function validateDownload() {

        if( (isset( $this->params[ 'upload' ] )) && (!wp_verify_nonce( $this->params[ '_wpnonce' ], 'doifd-add-download-nonce' ) ) ) {
            $this->errorMessages[ 'nonce' ] = __( 'Security Check - The Nonce Does Not Match - Clear your browser cache.', $this->plugin_slug );
        }

        if( (isset( $this->params[ 'update_download' ] )) && (!wp_verify_nonce( $this->params[ '_wpnonce' ], 'doifd-edit-download-nonce' ) ) ) {
            $this->errorMessages[ 'nonce' ] = __( 'Security Check - The Nonce Does Not Match - Clear your browser cache.', $this->plugin_slug );
        }

        if( empty( $this->downloadData[ 'download_name' ] ) ) {
            if ($this->params['type'] == '1') {
                $this->errorMessages[ 'download_name' ] = __( 'Please name your list.', $this->plugin_slug );
            } else {
                $this->errorMessages[ 'download_name' ] = __( 'Please name your file.', $this->plugin_slug );
            }
        }

        if( empty( $this->downloadData[ 'landing_page' ] ) ) {
            $this->errorMessages[ 'landing_page' ] = __( 'Please select a Landing Page.', $this->plugin_slug );
        }

        if( (!$this->params['type'] == '1') && (!empty( $this->file[ 'userfile' ][ 'tmp_name' ] ) ) && ( $this->validExt == false ) ) {
            $this->errorMessages[ 'invalidExt' ] = __( 'Unknown File Type (.jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx, .mp3 only).', $this->plugin_slug );
        }

        if( (!$this->params['type'] == '1') && !file_exists( DOIFD_DOWNLOAD_DIR ) ) {
            $this->errorMessages[ 'noDirectory' ] = __( 'The download directory does not exist. Check your permissions and try reactivating the plugin', $this->plugin_slug );
        }

        if( (!$this->params['type'] == '1') && !is_writable( DOIFD_DOWNLOAD_DIR ) ) {
            $this->errorMessages[ 'notWritable' ] = __( 'The download directory exists but is not writable. Check your folder permissions and try reactivating the plugin', $this->plugin_slug );
        }

        if( !empty( $this->errorMessages ) ) {
            $this->validDownload = false;
        }
    }

    public function validateDownloadFile() {

        if( (!$this->params['type'] == '1') && !$this->file[ 'userfile' ][ 'error' ] == 0 ) {

            $error_types = array(
                1 => __( 'The uploaded file exceeds the upload_max_filesize directive in php.ini.', $this->plugin_slug ),
                2 => __( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.', $this->plugin_slug ),
                3 => __( 'The uploaded file was only partially uploaded.', $this->plugin_slug ),
                4 => __( 'Please select a file to upload.', $this->plugin_slug ),
                5 => __( 'Missing a temporary folder.', $this->plugin_slug ),
                6 => __( 'Failed to write file to disk.', $this->plugin_slug ),
                7 => __( 'A PHP extension stopped the file upload.', $this->plugin_slug )
            );
            $this->errorMessages[ 'fileError' ] = $error_types[ $_FILES[ 'userfile' ][ 'error' ] ];

            if( !empty( $this->errorMessages ) ) {
                $this->validDownload = false;
            }
        }
    }

    public function renameDownload() {
        $this->newName = 'doifd_' . uniqid( mt_rand( 3, 5 ) ) . '_' . time() . '.' . $this->ext;
        return $this->newName;
    }

    public function uploadDownload() {
        
        if($this->params['type'] == '1') {
             $this->successMessage[ 'listAdded' ] = __( 'List Created Successfully', $this->plugin_slug );
        }

        if(!$this->params['type'] == '1') {
        $this->move = move_uploaded_file( $this->file[ 'userfile' ][ 'tmp_name' ], DOIFD_DOWNLOAD_DIR . '/' . $this->newName );
        }
        $this->handleData();

        if( $this->move == '1' ) {
            $this->successMessage[ 'fileUpload' ] = __( 'File Uploaded Successfully', $this->plugin_slug );
        }
    }

    public function editDownload() {

        $this->move = move_uploaded_file( $this->file[ 'userfile' ][ 'tmp_name' ], DOIFD_DOWNLOAD_DIR . '/' . $this->newName );
        $this->updateDownloadFileSQL();

        if( $this->move == '1' ) {
            unlink( DOIFD_DOWNLOAD_DIR . $this->params[ 'doifd_download_file_name' ] );
            $this->successMessage[ 'editUpload' ] = __( 'You successfully changed your download file.', $this->plugin_slug );
        }
    }

    public function handleData() {

        global $wpdb;
        
        /***********************************************************************
         * Upload SQL for new Downloads
         * *********************************************************************
         */
        
        if( isset( $this->params[ 'upload' ] ) ) {
            
            $values = apply_filters( 'doifd_premium_upload_sql', $values = array(
                'doifd_download_name' => $this->downloadData[ 'download_name' ],
                'doifd_download_file_name' => $this->newName,
                'doifd_download_landing_page' => $this->downloadData[ 'landing_page' ],
                'doifd_download_type' => $this->params['type'],
                'time' => current_time( 'mysql', 0 )
                    ), $this->downloadData );

            $values_formats = apply_filters( 'doifd_premium_upload_formats_sql', $values_formats = array(
                '%s',
                '%s',
                '%s'
                    ) );

            $wpdb->insert( $wpdb->prefix . 'doifd_lab_downloads', $values, $values_formats );
        }
        
        /***********************************************************************
         * Update SQL for current Downloads
         * *********************************************************************
         */

        if( isset( $this->params[ 'update_download' ] ) ) {
            
            $updateValues = apply_filters( 'doifd_premium_upload_sql', $updateValues = array(
                'doifd_download_name' => $this->downloadData[ 'download_name' ], 
                'doifd_download_landing_page' => $this->downloadData[ 'landing_page' ]
                    ), $this->downloadData );
            
            $updateFormats = apply_filters( 'doifd_premium_upload_formats_sql', $updateFormats = array(
                '%s', // value1
                '%d' // value2
                    ) );
            
            $wpdb->update( $wpdb->prefix . 'doifd_lab_downloads', $updateValues,
                    array( 'doifd_download_id' => $this->params[ 'doifd_download_id' ] ),
                    $updateFormats, array(
                    '%d' )
            );

            if( ($this->downloadData[ 'download_name' ]) != ( $this->downloadData[ 'old_name' ] ) ) {
                $this->successMessage[ 'nameChange' ] = __( 'You successfully changed your download file name.', $this->plugin_slug );
            }
            
        /***********************************************************************
         * Reset Download count if selected
         * *********************************************************************
         */

            if( $this->downloadData[ 'reset' ] == '1' ) {

                $wpdb->update(
                        $wpdb->prefix . 'doifd_lab_downloads', array( 'doifd_number_of_downloads' => 0 ), array( 'doifd_download_id' => $this->params[ 'doifd_download_id' ] ), array( '%s' ), array( '%d' )
                );

                $this->successMessage[ 'reset' ] = __( 'Download count reset.', 'double-opt-in-for-download' );
            }
        }
    }

    public function updateDownloadFileSQL() {

        global $wpdb;

        $wpdb->update(
                $wpdb->prefix . 'doifd_lab_downloads', array(
            'doifd_download_file_name' => $this->newName
                ), array(
            'doifd_download_id' => $this->params[ 'doifd_download_id' ] ), array(
            '%s',
                ), array(
            '%d' )
        );
    }

}