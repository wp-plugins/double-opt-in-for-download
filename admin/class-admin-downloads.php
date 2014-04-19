<?php

if ( !class_exists ( 'DoifdDownloads' ) ) {

    class DoifdDownloads {

        public function __construct() {

            add_action ( 'admin_init', array( &$this, 'upload_file' ) );
            add_action ( 'admin_init', array( &$this, 'edit_download' ) );

        }

        /* This function handles the editing of the download */

        function edit_download() {

            global $wpdb;

            $rb = __ ( 'Return Back', 'double-opt-in-for-download' );

            if ( isset ( $_POST[ 'update_download' ] ) && ( current_user_can ( 'manage_options' ) ) ) {

                $doifd_old_file_name = $_POST[ 'doifd_download_file_name' ];

                /* get the wpnonce from the form and verify it's coming from our form, if not, die */

                $doifd_edit_download_nonce = $_POST[ '_wpnonce' ];

                if ( !wp_verify_nonce ( $doifd_edit_download_nonce, 'doifd-edit-download-nonce' ) ) wp_die ( 'Security check' );

                $clean_doifd_reset_download_count = preg_replace ( '/[^0-9]/', '', $_POST[ 'doifd_reset_download_count' ] );

                /* Get old file name for comparison */
                
                $old_name = $_POST['doifd_download_name'];
                
                /* clean and sanitize Download name field */
                
                $clean_doifd_lab_name = sanitize_text_field ( $_POST[ 'name' ] );

                /* clean and sanitize download id */

                $doifd_clean_download_id = preg_replace ( '/[^0-9]/', '', $_POST[ 'doifd_download_id' ] );
                
                /* clean and sanitize landing page id */

                $doifd_clean_landing_page = preg_replace ( '/[^0-9]/', '', $_POST[ 'doifd_download_landing_page' ] );

                /* assign file name to variable */

                $doifd_lab_current_image = $_FILES[ 'userfile' ][ 'name' ];

                /* if file name is empty after being sanitized show error message */

                if ( empty ( $clean_doifd_lab_name ) ) {

                    $text = __ ( 'Please name your file.', 'double-opt-in-for-download' );

                    echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></p></strong></div>';
                }

                /* Update the file name */

                if ( !empty ( $clean_doifd_lab_name ) ) {
                    
                    $wpdb->update( 
                            $wpdb->prefix . 'doifd_lab_downloads', 
                            array( 
                                    'doifd_download_name' => $clean_doifd_lab_name,	// string
                                    'doifd_download_landing_page' => $doifd_clean_landing_page	// integer (number) 
                            ), 
                            array( 'doifd_download_id' => $doifd_clean_download_id ), 
                            array( 
                                    '%s',	// value1
                                    '%d'	// value2
                            ), 
                            array( '%d' ) 
                    );

                    $text = __ ( 'You successfully changed your download file name.', 'double-opt-in-for-download' );
                    
                    if ( ($clean_doifd_lab_name) != ( $old_name ) ) {

                    echo '<div id="message" class="updated"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . ' ">' . $rb . '</a></strong></p></div>';
                    
                    
                    }
                    
                    }

                /* If they have added a new file to replace the old download file, lets do all this fun stuff */

                if ( !empty ( $doifd_lab_current_image ) ) {

                    /* Get file extension and assign to variable */

                    $clean_doifd_lab_extension = substr ( strrchr ( $doifd_lab_current_image, '.' ), 1 );

                    /* Check to make sure that the file extension is one that is allowed by this plugin. If not, show error message */

                    if ( ($clean_doifd_lab_extension != "jpg") && ($clean_doifd_lab_extension != "jpeg") && ($clean_doifd_lab_extension != "gif") && ($clean_doifd_lab_extension != "png") && ($clean_doifd_lab_extension != "bmp") && ($clean_doifd_lab_extension != "pdf") && ($clean_doifd_lab_extension != "zip") && ($clean_doifd_lab_extension != "doc") && ($clean_doifd_lab_extension != "docx") && ($clean_doifd_lab_extension != "mp3") ) {

                        $text = __ ( 'Unknown File Type (.jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx, .mp3 only).', 'double-opt-in-for-download' );

                        echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></p></div>';
                    } else {

                        /* Rename the file to a radom file name to avoid duplicate file names */

                        $doifd_lab_file_name = 'doifd_' . uniqid ( mt_rand ( 3, 5 ) ) . '_' . time () . '.' . $clean_doifd_lab_extension;

                        /* Upload the file to download directory */

                        $doifd_lab_upload = $this->move_file ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR, $doifd_lab_file_name, $_FILES[ 'userfile' ][ 'tmp_name' ] );

                        if ( $doifd_lab_upload == 0 ) {

                            $text = __ ( 'There was an error uploading your new file', 'double-opt-in-for-download' );

                            echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></p></div>';
                        }

                        /* If the download folder does not exist show this error message */ elseif ( $doifd_lab_upload == 2 ) {

                            $text = __ ( 'The download directory does not exist. Check your permissions and try reactivating the plugin', 'double-opt-in-for-download' );

                            echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></p></strong></div>';
                        }

                        /* If the download folder is not writable show error message */ elseif ( $doifd_lab_upload == 3 ) {

                            $text = __ ( 'The download directory exists but is not writable. Check your folder permissions and try reactivating the plugin', 'double-opt-in-for-download' );

                            echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></p></strong></div>';
                        }

                        /* If the upload was successful, update the download table and show the success message */ elseif ( $doifd_lab_upload == 1 ) {

                            $wpdb->update (
                                    $wpdb->prefix . 'doifd_lab_downloads', array(
                                'doifd_download_file_name' => $doifd_lab_file_name
                                    ), array( 'doifd_download_id' => $doifd_clean_download_id ), array(
                                '%s',
                                    ), array( '%d' )
                            );

                            /* Now, lets delete the old download file */

                            unlink ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . $doifd_old_file_name );

                            $text = __ ( 'You successfully changed your download file.', 'double-opt-in-for-download' );

                            echo '<div id="message" class="updated"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></p></div>';
                        } else {

                            /* Show error message if upload failed */

                            $text = __ ( 'Error Uploading File', 'double-opt-in-for-download' );

                            echo '<div class="error"><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></div>';
                        }
                    }
                }

                /* If the admin has selected to reset the download count, let's do that */

                if ( $clean_doifd_reset_download_count == '1' ) {

                    $wpdb->update (
                            $wpdb->prefix . 'doifd_lab_downloads', array(
                        'doifd_number_of_downloads' => 0
                            ), array( 'doifd_download_id' => $doifd_clean_download_id ), array(
                        '%s',
                            ), array( '%d' )
                    );

                    $text = __ ( 'You successfully reset your download count.', 'double-opt-in-for-download' );

                    echo '<div id="message" class="updated"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . ' ">' . $rb . '</a></strong></p></div>';
                }

            }

        }

        /* This function handles the actual uploading of the download file */

        function upload_file() {

            global $wpdb;

            /* Assign the return back link to a variable */
            
            if(has_action('doifd_free_download_bypass')) {
                    
                do_action('doifd_free_download_bypass');
                    
             } else {

            $rb = __ ( 'Return Back', 'double-opt-in-for-download' );

            /* Check to see if it's coming from the upload form and the user has the privileges to upload a file */

            if ( isset ( $_POST[ 'upload' ] ) && ( current_user_can ( 'manage_options' ) ) ) {

                /* Get the wpnonce from the form and verify it's coming from our form, if not, die */

                $doifd_lab_nonce = $_POST[ '_wpnonce' ];

                if ( !wp_verify_nonce ( $doifd_lab_nonce, 'doifd-add-download-nonce' ) ) wp_die ( 'Security check' );

                /* Clean and sanitize Download name field */

                $clean_doifd_lab_name = sanitize_text_field ( $_POST[ 'name' ] );
                
                /* clean and sanitize download landing page id */

                $doifd_clean_download_landing_page = preg_replace( '/[^0-9]/', '', $_POST[ 'doifd_download_landing_page' ] );

                /* Assign file name to variable */

                $doifd_lab_current_image = $_FILES[ 'userfile' ][ 'name' ];

                /* Get file extension and assign to variable */

                $clean_doifd_lab_extension = substr ( strrchr ( $doifd_lab_current_image, '.' ), 1 );

                /* If file name is empty after being sanitized show error message */

                if ( empty ( $clean_doifd_lab_name ) ) {

                    $text = __ ( 'Please name your file.', 'double-opt-in-for-download' );

                    echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></p></strong></div>';
                }
                
               /* If there is no Landing Page Id selected show message */
                
                if ( empty( $doifd_clean_download_landing_page ) ) {

                    $text = __( 'Please select a Landing Page.', 'double-opt-in-for-download' );

                    echo '<div id="message" class="error"><p><strong>' . $text . '</strong></p></div>';

                }

                /* If the file name is empty show error message */ 
                
                elseif ( empty ( $_FILES[ 'userfile' ][ 'tmp_name' ] ) ) {

                    $text = __ ( 'Please select a file to upload', 'double-opt-in-for-download' );

                    echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></p></div>';
                }

                /* Check to make sure that the file extension is one that is allowed by this plugin. If not, show error message */ 
                
                elseif ( ($clean_doifd_lab_extension != "jpg") && ($clean_doifd_lab_extension != "jpeg") && ($clean_doifd_lab_extension != "gif") && ($clean_doifd_lab_extension != "png") && ($clean_doifd_lab_extension != "bmp") && ($clean_doifd_lab_extension != "pdf") && ($clean_doifd_lab_extension != "zip") && ($clean_doifd_lab_extension != "doc") && ($clean_doifd_lab_extension != "docx") && ($clean_doifd_lab_extension != "mp3") ) {

                    $text = __ ( 'Unknown File Type (.jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx, .mp3 only).', 'double-opt-in-for-download' );

                    echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></p></div>';
                } else {

                    /* Rename the file to a radom file name to avoid duplicate file names */

                    $doifd_lab_file_name = 'doifd_' . uniqid ( mt_rand ( 3, 5 ) ) . '_' . time () . '.' . $clean_doifd_lab_extension;

                    /* upload file to Download directory */

                    $doifd_lab_upload = $this->move_file ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR, $doifd_lab_file_name, $_FILES[ 'userfile' ][ 'tmp_name' ] );

                    /* If upload was unsuccessful send message to admin */

                    if ( $doifd_lab_upload == 0 ) {

                        $text = __ ( 'There was an error uploading your new file', 'double-opt-in-for-download' );

                        echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></p></div>';
                    }

                    /* If the download folder does not exist show this error message */ elseif ( $doifd_lab_upload == 2 ) {

                        $text = __ ( 'The download directory does not exist. Check your permissions and try reactivating the plugin', 'double-opt-in-for-download' );

                        echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></p></strong></div>';
                    }

                    /* If the download folder is not writable show error message */ elseif ( $doifd_lab_upload == 3 ) {

                        $text = __ ( 'The download directory exists but is not writable. Check your folder permissions and try reactivating the plugin', 'double-opt-in-for-download' );

                        echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></p></strong></div>';
                    }

                    /* If upload successful insert into download table and show success message */ elseif ( $doifd_lab_upload == 1 ) {

                        /* Put values into an array */

                        $values = array(
                            'doifd_download_name' => $clean_doifd_lab_name,
                            'doifd_download_file_name' => $doifd_lab_file_name,
                            'doifd_download_landing_page' => $doifd_clean_download_landing_page,
                            'time' => current_time ( 'mysql', 0 )
                        );

                        $values_formats = array(
                            '%s',
                            '%s',
                            '%s'
                        );

                        /* Insert array values into the download table */

                        $wpdb->insert ( $wpdb->prefix . 'doifd_lab_downloads', $values, $values_formats );

                        /* Show success message */

                        $text = __ ( 'File Uploaded Successfully', 'double-opt-in-for-download' );

                        echo '<div class="updated"><strong>' . $text . ' <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></div>';
                    } else {

                        /* Show error message if upload failed */

                        $text = __ ( 'Error Uploading File', 'double-opt-in-for-download' );

                        echo '<div class="error"><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></div>';
                    }
                }
            }
            
             }

        }

        /* This functions moves the download file to the download directory. */

        function move_file( $directory, $file_name, $file_tmp_name ) {

            /* Make sure the download folder exists. If not return the value of 2 */

            if ( !file_exists ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR ) ) {

                return 2;
            } elseif ( !is_writable ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR ) ) {

                return 3;
            } else {

                $move = move_uploaded_file ( $file_tmp_name, $directory . '/' . $file_name );

                /* if move is successful it returns 1 or true else return an error of 0 */

                if ( $move == 1 ) {

                    return 1;
                } else {

                    return 0;
                }
            }

        }

    }

}

?>