<div style="display: none" id="doifdEditUploadForm">

    <form id="doifd_admin_edit_download_form" method="post" action="" enctype="multipart/form-data" style="width: 40%;">

        <fieldset>

            <input type="hidden" name="doifd_download_id" id="doifd_download_id" value="" />
            <input type="hidden" name="doifd_download_name" id="doifd_download_name" value="" />
            <input type="hidden" name="doifd_download_file_name" id="doifd_download_file_name" value="" />
            <input type="hidden" name="max_upload_size" id="max_upload_size" value="<?php
            $max_size = wp_convert_hr_to_bytes( ini_get( 'upload_max_filesize' ) );
            echo $max_size;

            ?>"/>

            <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php
            $doifd_lab_edit_download_form_nonce = wp_create_nonce( 'doifd-edit-download-nonce' );

            echo $doifd_lab_edit_download_form_nonce;

            ?>"/>
            <input type="hidden" name="type" id="type" value="0" >

            <?php
//                    $call = new DoifdAdminFilters();
//                    $output = $call->doifd_additional_admin_fields ();
//                    echo $output;

            ?>
            <div>
                <label id="doifd_labelName" for="name"><?php
            _e( 'Download Name', $this->plugin_slug );

            ?></label><input type="text" name="doifd_edit_name" id="doifd_edit_name" size="30" value=""/><br/>
                <p class="expl"><?php
                    _e( '( Example: My Free eBook )', $this->plugin_slug );

            ?></p>
            </div>
            <div>
                <label for="doifd_download_edit_landing_page"><?php
                    _e( 'Select Landing Page', $this->plugin_slug );

            ?></label>


                <?php
                echo '<select name="doifd_download_edit_landing_page" id="doifd_download_edit_landing_page">';
                echo "<option value=''>";
                echo esc_attr( __( 'Select Landing Page', $this->plugin_slug ) );
                echo '</option>';
                $pages = get_pages();
                foreach ( $pages as $page ) {
                    $option = '<option value="' . $page->ID . '">';
                    $option .= $page->post_title;
                    $option .= '</option>';
                    echo $option;
                }
                echo '</select><br />';
                echo '<p class="expl"><a href="http://www.doubleoptinfordownload.com/what-is-a-landing-page/" target="_blank" />' . __( 'What is a Landing Page?', $this->plugin_slug ) . '</a></p>';

                ?>
            </div>

            <div>

                <label for="userfile"><?php _e( 'Select Your File', $this->plugin_slug );

                ?></label>
                <input type="file" name="userfile" id="userfile" size="50" value=""><br />
            </div>
            
            <div>

                <div class="editallowedfiletypes" data-jbox-title="Allowed File Types" data-jbox-content="<?php $allowed = new DOIFDDownloads;
                echo $allowed->allowed_file_types() ?>"><?php _e( '( Allowed File Types )', $this->plugin_slug ); ?></div>
                <div class="editfilesizelimit" data-jbox-title="Your PHP File Size Limit" data-jbox-content="Your PHP file size limit is: <?php echo ini_get( 'upload_max_filesize' ); ?>"><?php _e( '( What is my PHP file size limit? )', $this->plugin_slug ); ?></div>

            </div>

            <div>

                <label for="name"><?php _e( 'Reset Download Count', $this->plugin_slug ); ?><span></span>: </label>

                <input type="radio" id="doifd_reset_download_count" name="doifd_reset_download_count"  value="1" /><?php _e( 'Yes', $this->plugin_slug ); ?>

                <input style="margin-left: 15px;" type="radio" id="doifd_reset_download_count" name="doifd_reset_download_count"  value="0" checked="checked"/><?php _e( 'No', $this->plugin_slug ); ?>

            </div>

            <div style="width: 100%; text-align: center">
                <input class='button button-primary' name="update_download" id="update_download" type="submit" value="<?php
                _e( 'Upload Your File', $this->plugin_slug );

                ?>">
            </div>

        </fieldset>

    </form>
</div>