<div class = "wrap">  


    <?php include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'views/view-admin-header.php' ); ?>
    
    <div id="icon-edit-pages" class="icon32"><br></div><h2><?php _e( 'Edit Download File', 'double-opt-in-for-download' );
    echo $_GET[ 'doifd_download_name' ];
    ?></h2>

    <form method="post" action="" enctype="multipart/form-data">

        <table class="form-table">

            <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php

            $doifd_lab_edit_download_form_nonce = wp_create_nonce ( 'doifd-edit-download-nonce' );

            echo $doifd_lab_edit_download_form_nonce;

            ?>"/>

            <input type="hidden" name="doifd_download_id" value="<?php echo $_GET[ 'doifd_download_id' ]; ?>" />

            <input type="hidden" name="doifd_download_file_name" value="<?php echo $_GET[ 'doifd_download_file_name' ]; ?>" />

            <input type="hidden" name="doifd_download_name" value="<?php echo $_GET[ 'doifd_download_name' ]; ?>" />
            
            <input type="hidden" name="doifd_download_landing_page" value="<?php echo $_GET[ 'doifd_download_landing_page' ]; ?>" />

            <tr valign="top">


                <th scope="row"><label for="name"><?php _e ( 'Rename Your File', 'double-opt-in-for-download' ); ?><span> *</span>: </label></th>

                <td><input type="text" name="name" id="name" value="<?php if ( isset ( $_POST[ 'name' ] ) ) {
                echo $_POST[ 'name' ];
            } else {
                echo $_GET[ 'doifd_download_name' ];
            } ?>"/></td>

            </tr>
            
            <?php if ( isset ( $_POST[ 'doifd_download_landing_page' ] ) ) {
                $landing = $_POST[ 'doifd_download_landing_page' ];
            } else {
                $landing =$_GET[ 'doifd_download_landing_page' ];
            } ?>
            
            <tr valign="top">

                <th scope="row"><label for="file"><?php _e( 'Select Landing Page', 'double-opt-in-for-download' ); ?><span> *</span>: </label></th>

                <td>
                    <?php
                    echo '<div id="doifd_lab_admin_options">';
                    echo '<select name="doifd_download_landing_page" id="doifd_download_landing_page">';
                    echo "<option value='{$landing}'>";
                    echo esc_attr( __( 'Select Landing Page', 'double-opt-in-for-download' ) );
                    echo '</option>';
                    $pages = get_pages();
                    foreach ( $pages as $page ) {
                       $option = '<option value="' . $page->ID . '" ' . (($landing == $page->ID ) ? 'selected="selected"' : "") . '>';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    echo '</select>';
                    echo '</div>';
                    ?>
                </td>

            </tr>

            <tr valign="top">

                <th scope="row"><label for="name"><?php _e ( 'Select Replacement File', 'double-opt-in-for-download' ); ?><span> *</span>: </label></th>

                <td><input type="file" name="userfile" id="userfile"><p><?php _e ( '(Currently, the following file types are allowed; .jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx )', 'double-opt-in-for-download' ); ?></p></td>

            </tr>

            <tr valign="top">

                <th scope="row"><label for="name"><?php _e ( 'Reset Download Count', 'double-opt-in-for-download' ); ?><span></span>: </label></th>

                <td><input type="radio" id="doifd_reset_download_count" name="doifd_reset_download_count"  value="1" /><?php _e ( 'Yes', 'double-opt-in-for-download' ); ?>

                    <input style="margin-left: 15px;" type="radio" id="doifd_reset_download_count" name="doifd_reset_download_count"  value="0" checked="checked"/><?php _e ( 'No', 'double-opt-in-for-download' ); ?></td>

            </tr>

            <tr valigh="top">

                <td><input class='button-primary' name="update_download" type="submit" value="<?php _e ( 'Update', 'double-opt-in-for-download' ); ?>"></td>

            </tr>

        </table>

    </form>
