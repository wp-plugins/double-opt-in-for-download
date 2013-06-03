<?php

class DoifdDownloads {

    public function __construct() {
        
    }

    public static function admin_download_page() {

        ?>

        <!--Begin HTML-->

        <div class="wrap">

            <h2>Add a download file</h2>

            <div id="icon-edit-pages" class="icon32"></div>

            <?php

            include 'class-admin-download-table.php';

            include DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'includes/doifd-admin-header.php';

            ?>

            <!--HTML for upload form begins here-->

            <form method="post" action="" enctype="multipart/form-data">

                <table class="form-table">

                    <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php

            $doifd_lab_nonce = wp_create_nonce ( 'doifd-add-download-nonce' );
            echo $doifd_lab_nonce;

            ?>"/>

                    <tr valign="top">

                        <th scope="row"><label for="name">Name of Your Download<span> *</span>: </label></th>

                        <td><input type="text" name="name" id="name" value=""/></td>

                    </tr>

                    <tr valign="top">

                        <th scope="row"><label for="name">Select Your File<span> *</span>: </label></th>

                        <td><input type="file" name="userfile" id="userfile"><p>( Currently, the following file types are allowed; .jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx )</p></td>

                    </tr>

                    <tr valigh="top">

                        <td><input class='button-primary' name="upload" type="submit" value=" Upload "></td>

                    </tr>

                </table>

            </form>

            <br />

            <hr />

            <br />

            <?php

            // create an instance of subscriber table class

            $doifd_dow_table = new Doifd_Download_Table();

            // prepare and get download table from class and surround with <form>

            $doifd_dow_table->prepare_items ();

            echo '<form method="get" >';

            echo '<input type="hidden" name="page" value="' . $_REQUEST[ 'page' ] . '" />';

            $doifd_dow_table->display ();

            echo '</form>';

            echo '</div>';

        }

        public static function edit_download_page() {

            ?>

            <!--Begin HTML--> 

            <div class="wrap">

                <h2>Edit Download File <?php echo $_GET[ 'doifd_download_name' ]; ?></h2>

                <div id="icon-edit-pages" class="icon32"></div>

                <!--HTML for upload form begins here-->

                <form method="post" action="" enctype="multipart/form-data">

                    <table class="form-table">

                        <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php $doifd_lab_edit_download_form_nonce = wp_create_nonce ( 'doifd-edit-download-nonce' );
            echo $doifd_lab_edit_download_form_nonce;

            ?>"/>

                        <input type="hidden" name="doifd_download_id" value="<?php echo $_GET[ 'doifd_download_id' ]; ?>" />

                        <input type="hidden" name="doifd_download_file_name" value="<?php echo $_GET[ 'doifd_download_file_name' ]; ?>" />

                        <tr valign="top">


                            <th scope="row"><label for="name">Rename Your File<span> *</span>: </label></th>

                            <td><input type="text" name="name" id="name" value="<?php echo $_GET[ 'doifd_download_name' ]; ?>"/></td>

                        </tr>

                        <tr valign="top">

                            <th scope="row"><label for="name">Select Your Replacement File<span> *</span>: </label></th>

                            <td><input type="file" name="userfile" id="userfile"><p>( Currently, the following file types are allowed; .jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx )</p></td>

                        </tr>

                        <tr valign="top">

                            <th scope="row"><label for="name">Reset Download Count to 0<span></span>: </label></th>

                            <td><input type="radio" id="doifd_reset_download_count" name="doifd_reset_download_count"  value="1" /> Yes

                                <input type="radio" id="doifd_reset_download_count" name="doifd_reset_download_count"  value="0" checked="checked"/> No</td>

                        </tr>

                        <tr valigh="top">

                            <td><input class='button-primary' name="update_download" type="submit" value=" Update "></td>

                        </tr>

                    </table>

                </form>

                <?php

            }

            public static function edit_download() {

                global $wpdb;

                $rb = __ ( 'Return Back', 'Double-Opt-In-For-Download' );

                if ( isset ( $_POST[ 'update_download' ] ) && ( current_user_can ( 'manage_options' ) ) ) {

                    $doifd_old_file_name = $_POST[ 'doifd_download_file_name' ];

                    // get the wpnonce from the form and verify it's coming from our form, if not, die

                    $doifd_edit_download_nonce = $_POST[ '_wpnonce' ];

                    if ( !wp_verify_nonce ( $doifd_edit_download_nonce, 'doifd-edit-download-nonce' ) ) wp_die ( 'Security check' );

                    $clean_doifd_reset_download_count = preg_replace ( '/[^0-9]/', '', $_POST[ 'doifd_reset_download_count' ] );

                    // clean and sanitize Download name field

                    $clean_doifd_lab_name = sanitize_text_field ( $_POST[ 'name' ] );

                    // clean and sanitize download id

                    $doifd_clean_download_id = preg_replace ( '/[^0-9]/', '', $_POST[ 'doifd_download_id' ] );

                    // assign file name to variable

                    $doifd_lab_current_image = $_FILES[ 'userfile' ][ 'name' ];

                    // if file name is empty after being sanitized show error message

                    if ( empty ( $clean_doifd_lab_name ) ) {

                        $text = __ ( 'Please name your file.', 'Double-Opt-In-For-Download' );

                        echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></p></strong></div>';
                    }

                    //update file name

                    if ( !empty ( $clean_doifd_lab_name ) ) {

                        $wpdb->update (
                                
                                $wpdb->prefix . 'doifd_lab_downloads', array(
                            
                                    'doifd_download_name' => $clean_doifd_lab_name
                                
                                        ), array( 'doifd_download_id' => $doifd_clean_download_id ), array(
                            
                                            '%s',
                                
                                            ), array( '%d' )
                        
                                );
                        
                    }

                    // if they have added a new file to replace the old download file, lets do all this fun stuff

                    if ( !empty ( $doifd_lab_current_image ) ) {


                        // get file extension and assign to variable

                        $clean_doifd_lab_extension = substr ( strrchr ( $doifd_lab_current_image, '.' ), 1 );

                        // check to make sure that the file extension is one that is allowed by this plugin. If not, show error message

                        if ( ($clean_doifd_lab_extension != "jpg") && ($clean_doifd_lab_extension != "jpeg") && ($clean_doifd_lab_extension != "gif") && ($clean_doifd_lab_extension != "png") && ($clean_doifd_lab_extension != "bmp") && ($clean_doifd_lab_extension != "pdf") && ($clean_doifd_lab_extension != "zip") && ($clean_doifd_lab_extension != "doc") && ($clean_doifd_lab_extension != "docx") ) {

                            $text = __ ( 'Unknown File Type (.jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx only).' );

                            echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></p></div>';
                        } else {

                            // rename the file to a radom file name to avoid duplicate file names

                            $doifd_lab_file_name = 'doifd_' . uniqid ( mt_rand ( 3, 5 ) ) . '_' . time () . '.' . $clean_doifd_lab_extension;

                            // upload file to download directory

                            $doifd_lab_upload = doifd_lab_uploadFiles ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR, $doifd_lab_file_name, $_FILES[ 'userfile' ][ 'tmp_name' ] );

                            // if upload successful, update the download table and show success message

                            if ( $doifd_lab_upload == 1 ) {

                                $wpdb->update (
                                  
                                        $wpdb->prefix . 'doifd_lab_downloads', array(
                                    
                                            'doifd_download_file_name' => $doifd_lab_file_name
                                        
                                                ), array( 'doifd_download_id' => $doifd_clean_download_id ), array(
                                    
                                                    '%s',
                                        
                                                    ), array( '%d' )
                                
                                        );

                                // delete the old download file

                                unlink ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . $doifd_old_file_name );
                            }
                        }
                    }

                    // reset the download count if selected

                    if ( $clean_doifd_reset_download_count == '1' ) {

                        $wpdb->update (
                                $wpdb->prefix . 'doifd_lab_downloads', array(
                            'doifd_number_of_downloads' => 0
                                ), array( 'doifd_download_id' => $doifd_clean_download_id ), array(
                            '%s',
                                ), array( '%d' )
                        );
                    }

                    wp_redirect ( 'admin.php?page=double-opt-in-for-download/admin/doifd-admin.php_downloads' );
                }

            }

            public function upload_file() {

                global $wpdb;

                // assign the return back link to a variable

                $rb = __ ( 'Return Back', 'Double-Opt-In-For-Download' );

                // check to see if it's coming from the upload form and the user has the privileges to upload a file

                if ( isset ( $_POST[ 'upload' ] ) && ( current_user_can ( 'manage_options' ) ) ) {

                    // get the wpnonce from the form and verify it's coming from our form, if not, die

                    $doifd_lab_nonce = $_POST[ '_wpnonce' ];

                    if ( !wp_verify_nonce ( $doifd_lab_nonce, 'doifd-add-download-nonce' ) ) wp_die ( 'Security check' );

                    // clean and sanitize Download name field

                    $clean_doifd_lab_name = sanitize_text_field ( $_POST[ 'name' ] );

                    // assign file name to variable

                    $doifd_lab_current_image = $_FILES[ 'userfile' ][ 'name' ];

                    // get file extension and assign to variable

                    $clean_doifd_lab_extension = substr ( strrchr ( $doifd_lab_current_image, '.' ), 1 );

                    // if file name is empty after being sanitized show error message

                    if ( empty ( $clean_doifd_lab_name ) ) {

                        $text = __ ( 'Please name your file.', 'Double-Opt-In-For-Download' );

                        echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></p></strong></div>';
                    }

                    // if the file name is empty show error message
                    elseif ( empty ( $_FILES[ 'userfile' ][ 'tmp_name' ] ) ) {

                        $text = __ ( 'Please select a file to upload', 'Double-Opt-In-For-Download' );

                        echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></p></div>';
                    }

                    // check to make sure that the file extension is one that is allowed by this plugin. If not, show error message
                    elseif ( ($clean_doifd_lab_extension != "jpg") && ($clean_doifd_lab_extension != "jpeg") && ($clean_doifd_lab_extension != "gif") && ($clean_doifd_lab_extension != "png") && ($clean_doifd_lab_extension != "bmp") && ($clean_doifd_lab_extension != "pdf") && ($clean_doifd_lab_extension != "zip") && ($clean_doifd_lab_extension != "doc") && ($clean_doifd_lab_extension != "docx") ) {

                        $text = __ ( 'Unknown File Type (.jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx only).' );

                        echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></p></div>';
                    } else {

                        // rename the file to a radom file name to avoid duplicate file names

                        $doifd_lab_file_name = 'doifd_' . uniqid ( mt_rand ( 3, 5 ) ) . '_' . time () . '.' . $clean_doifd_lab_extension;

                        // upload file to download directory
                        
                        $doifd_lab_upload = $this->doifd_lab_uploadFiles( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR, $doifd_lab_file_name, $_FILES[ 'userfile' ][ 'tmp_name' ] );

//                        $doifd_lab_upload = doifd_lab_uploadFiles ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR, $doifd_lab_file_name, $_FILES[ 'userfile' ][ 'tmp_name' ] );

                        // if upload successful insert into download table and show success message

                        if ( $doifd_lab_upload == 1 ) {

                            //put values into an array

                            $values = array(
                                'doifd_download_name' => $clean_doifd_lab_name,
                                'doifd_download_file_name' => $doifd_lab_file_name,
                                'time' => current_time ( 'mysql', 0 )
                            );

                            $values_formats = array(
                                '%s',
                                '%s'
                            );

                            // insert into download table

                            $wpdb->insert ( $wpdb->prefix . 'doifd_lab_downloads', $values, $values_formats );

                            // show success message

                            $text = __ ( 'File Uploaded Successfully', 'Double-Opt-In-For-Download' );

                            echo '<div class="updated"><strong>' . $text . ' <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></div>';
                        } else {

                            // show error message if upload failed

                            $text = __ ( 'Error Uploading File', 'Double-Opt-In-For-Download' );

                            echo '<div class="error"><strong>' . $text . '  <a href="' . str_replace ( '%7E', '~', $_SERVER[ 'REQUEST_URI' ] ) . '">' . $rb . '</a></strong></div>';
                        }
                    }
                }

            }
            
// This functions moves the download file to the download directory. this is used a little later
            
            
        public function doifd_lab_uploadFiles( $directory , $file_name , $file_tmp_name ) {
         
            if ( move_uploaded_file ( $file_tmp_name , $directory . '/' . $file_name ) ) {
            
                // if move is successful it returns 1 or true
                
                return 1 ;
                
            } else {
              
                return 0 ;
                
            }
        }

        }

        ?>
