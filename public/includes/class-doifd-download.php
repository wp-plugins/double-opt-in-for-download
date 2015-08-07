<?php

if ( ! class_exists ( 'DOIFDDownload' ) ) {

    class DOIFDDownload extends DOIFD {

        public function __construct() {

            /* Retrieves the Download */

            add_action ( 'init', array( $this, 'link_to_download' ) );
        }

        /* This function retrives the download for the subscriber */

        public function link_to_download() {

            global $wpdb;
                        
            @ini_set('zlib.output_compression', 'Off');

            /* if they have clicked the download button let proceed */

            if ( isset ( $_GET[ 'doifd_get_download' ] ) ) {

                /* Assigns the sql table names to a varaible */

                $wpdb->doifd_subscribers = $wpdb->prefix . 'doifd_lab_subscribers';

                $wpdb->doifd_downloads = $wpdb->prefix . 'doifd_lab_downloads';

                /* Sanitize the download id, just in case */

                $doifd_download_id = preg_replace ( '/[^0-9]/', '', $_GET[ 'download_id' ] );

                /* Get the verification number so we can look up what there download is. */

                $ver = $_GET[ 'ver' ];

                /* If both are valid, lets continue */

                if ( isset ( $doifd_download_id ) && ( isset ( $ver ) ) ) {

                    /* Get options from options table */

                    $options = get_option ( 'doifd_lab_options' );

                    /* Get the maximum downloads possible option to variable */

                    $allowed = $options[ 'downloads_allowed' ];

                    /* Set a default of 1 in case the admin did not select this option */

                    if ( empty ( $allowed ) ) {

                        $allowed = '1';
                    }

                    /* Check to see how many times this subscriber has downloaded the file */

                    $checkallowed = $wpdb->get_row ( "SELECT doifd_downloads_allowed FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " );

                    /* If subscriber has exceeded maximum, show them the exceeded download message. */

                    if ( $checkallowed->doifd_downloads_allowed >= $allowed ) {

                        return '<div class="exceeded"><img src="' . DOIFD_URL . 'public/assets/img/warning.png" alt="Warning" title="Warning" /><br />' . __ ( 'You have exceed your number of downloads for this item.', $this->plugin_slug ) . '</div>';
                    }

                    /* Query database and assign results to varialbe */

                    $get_file_name = $wpdb->get_row ( "SELECT doifd_download_file_name, doifd_download_name FROM " . $wpdb->prefix . "doifd_lab_downloads  WHERE doifd_download_id = '$doifd_download_id' ", ARRAY_A );

                    /* Assign file name to variable. */

                    $file_name = $get_file_name[ 'doifd_download_file_name' ];

                    $given_name = $get_file_name[ 'doifd_download_name' ];

                    /* Get the file extension */
                    $upload_dir = wp_upload_dir ();
                    $extension = pathinfo ( $upload_dir[ 'basedir' ] . '/doifd_downloads/' . $file_name, PATHINFO_EXTENSION );

                    /* Give the file a fake name to help hide the actual file */

                    $fakeFileName = '"' . $given_name . '.' . $extension . '"';

                    /* Assign the real file name to a variable */

                    $realFileName = $file_name;

                    /* Assign real file name and path to variable */

                    $file = $upload_dir[ 'basedir' ] . '/doifd_downloads/' . $realFileName;

                    if ( ! file_exists ( $file ) ) {

                        echo '<div class="exceeded"><img src="' . DOIFD_URL . 'public/assets/img/warning.png" alt="Warning" title="Warning" /><br />' . __ ( 'There is no download file.', $this->plugin_slug ) . '</div>';
                    } else {

                            $wpdb->query (
                                    "
                        UPDATE $wpdb->doifd_downloads
                        SET doifd_number_of_downloads = doifd_number_of_downloads+1 WHERE doifd_download_id = '$doifd_download_id'
                    "
                            );

                            /* Update subscribers downloads_allowed */

                            $wpdb->query (
                                    "
                        UPDATE $wpdb->doifd_subscribers
                        SET doifd_downloads_allowed = doifd_downloads_allowed+1 WHERE doifd_verification_number = '$ver'
                    "
                            );

                        $this->doDownload( $file, $extension, $fakeFileName );


                    }
                }
            }
        }
        
        
        public function doDownload( $file, $extension, $fakeFileName ) {
            
                       if( ! $fp = fopen ( $file, 'rb' ) ) {
                            die("Cannot Open File!"); 
                        } else {
                            
                        session_write_close(); 

                        /* Assign the appropriate Content-Type header for the file and send file to subscribers browser */

                        if ( $extension == 'jpg' ) {
                            header ( 'Content-Type: image/jpg' );
                        } elseif ( $extension == 'jpeg' ) {
                            header ( 'Content-Type: image/jpeg' );
                        } elseif ( $extension == 'png' ) {
                            header ( 'Content-Type: image/png' );
                        } elseif ( $extension == 'bmp' ) {
                            header ( 'Content-Type: image/bmp' );
                        } elseif ( $extension == 'zip' ) {
                            header ( "Content-type: application/zip" );
                            header ( "Content-Encoding: zip" );
                        } elseif ( $extension == 'gif' ) {
                            header ( 'Content-Type: image/gif' );
                        } elseif ( $extension == 'xls' ) {
                            header ( 'Content-Type: application/vnd.ms-excel' );
                        } elseif ( $extension == 'xlsx' ) {
                            header ( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
                        } elseif ( $extension == 'doc' ) {
                            header ( "Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document" );
                        } elseif ( $extension == 'docx' ) {
                            header ( "Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document" );
                        } elseif ( $extension == 'pdf' ) {
                            header ( "Content-type: application/pdf" );
                        } elseif ( $extension == 'mp3' ) {
                            header ( "Content-Type: application/octet-stream" );
                        } elseif ( $extension == 'mp4' ) {
                            header ( "Content-Type: video/mp4" );
                        } elseif ( $extension == 'mobi' ) {
                            header ( "Content-Type: application/octet-stream" );
                        } elseif ( $extension == 'epub' ) {
                            header ( "Content-Type: application/epub+zip" );
                        }
                        header ( "Content-Transfer-Encoding: binary" );
                        header ( "Content-Disposition: attachment; filename=" . $fakeFileName . "" );
                        header ( "Content-Length: " . filesize ( $file ) . "" );
                        fpassthru ( $fp );
                        fclose($fp);
                        exit();

                    }
        }

    }

}
new DOIFDDownload();
