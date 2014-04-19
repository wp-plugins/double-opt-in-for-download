<div class = "wrap">  

    <?php $title1 = apply_filters('doifd_download_table_title', __('Downloads', 'double-opt-in-for-download'));
        $title2 = apply_filters('doifd_download_upload_title', __( 'Upload Your Download File Here', 'double-opt-in-for-download' ));

     ?>

    <div id="icon-edit-pages" class="icon32"><br></div><h2><?php $title1; ?></h2>
    
    <?php
    
    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'views/view-admin-header.php' );

    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'admin/class-admin-download-table.php' );
    ?>

    <!--HTML for upload form begins here-->


    <h2><?php echo $title2; ?></h2>

    <form method="post" action="" enctype="multipart/form-data" style="width: 40%;">

        <table class="form-table">
            <input type="hidden" name="max_upload_size" id="max_upload_size" value="<?php
                            $max_size = wp_convert_hr_to_bytes( ini_get('upload_max_filesize') );
                            echo $max_size;
                            ?>"/>
            <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php
    $doifd_lab_nonce = wp_create_nonce( 'doifd-add-download-nonce' );
    echo $doifd_lab_nonce;
    ?>"/>
            <tr valign="top">
                            <?php
                                
                            $call = new DoifdAdminFilters();
                            $output = $call->doifd_additional_admin_fields();
                            echo $output;
                                
                                ?>
                            </tr>
            <tr valign="top">

                <th scope="row"><label id="doifd_labelName" for="name"><?php _e( 'Download Name', 'double-opt-in-for-download' ); ?><span> *</span>: </label></th>

                <td><input type="text" name="name" id="name" size="50" value=""/><p><?php _e( '( Example: My Free eBook )', 'double-opt-in-for-download' ); ?></p></td>


            </tr>

            <tr valign="top">

                <th scope="row"><label for="file"><?php _e( 'Select Landing Page', 'double-opt-in-for-download' ); ?><span> *</span>: </label></th>

                <td>
                    <?php
                    echo '<div id="doifd_lab_admin_options">';
                    echo '<select name="doifd_download_landing_page" id="doifd_download_landing_page">';
                    echo "<option value=''>";
                    echo esc_attr( __( 'Select Landing Page', 'double-opt-in-for-download' ) );
                    echo '</option>';
                    $pages = get_pages();
                    foreach ( $pages as $page ) {
                        $option = '<option value="' . $page->ID . '">';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    echo '</select>';
                    echo '<p><a href="http://www.doubleoptinfordownload.com/what-is-a-landing-page/" target="_blank" />' . __( 'What is a Landing Page?', 'double-opt-in-for-download' ) . '</a></p>';
                    echo '</div>';
                    ?>
                </td>

            </tr>

            <tr id="download_file" valign="top">

                <th scope="row"><label for="name"><?php _e( 'Select Your File', 'double-opt-in-for-download' ); ?><span> *</span>: </label></th>

                <td><div class="filesizelimit"><?php _e ('Your PHP file size limit is:', 'double-opt-in-for-download' ); ?> <?php echo ini_get('upload_max_filesize'); ?></div>
                    <input type="file" name="userfile" size="50" id="userfile"><p><?php _e( '( Allowed File Types: .jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx, ,mp3 )', 'double-opt-in-for-download' ); ?></p></td>

            </tr>

            <tr valigh="top">

                <td><input class='button-primary' name="upload" id="doifd_upload_submit" type="submit" value="<?php _e( 'Upload', 'double-opt-in-for-download' ); ?>"></td>

            </tr>

        </table>

    </form>

    <br />

    <hr />
    <h2><?php echo $title1; ?></h2>
    <br />

<?php
// create an instance of subscriber table class

$doifd_dow_table = new Doifd_Download_Table();

// prepare and get download table from class and surround with <form>

$doifd_dow_table->prepare_items();

echo '<form method="get" >';

echo '<input type="hidden" name="page" value="' . $_REQUEST[ 'page' ] . '" />';

$doifd_dow_table->display();

echo '</form>';
?>


    <div id="dialog" title="Delete Confirmation Required"><?php _e( 'You are about to send this download into the cyber abyss! Are you sure?', 'double-opt-in-for-download' ); ?></div>
    <div id="toolargedialog" title="Your file is too large"><?php _e( 'Your selected file exceeds your servers PHP file upload size limit. To learn how to get around your PHP file upload size limit,', 'double-opt-in-for-download' ); ?><a href="http://www.doubleoptinfordownload.com/forums/topic/large-file-how-to-get-around-phps-file-upload-size-limit/" target="new" /><?php _e( 'Click Here', 'double-opt-in-for-download' ); ?></a></div>

</div>