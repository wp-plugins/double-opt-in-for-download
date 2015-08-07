<div style="display: none" id="doifdListForm">

    <form id="doifd_admin_list_form" method="post" action="<?php echo admin_url( 'admin.php?page=doifd-admin-menu_downloads' ); ?>" enctype="multipart/form-data" style="width: 40%;">

        <fieldset>

            <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php
            $doifd_lab_nonce = wp_create_nonce( 'doifd-add-download-nonce' );
            echo $doifd_lab_nonce;

            ?>"/>
            <input type="hidden" name="type" id="type" value="1" >

            <div>
                <label for="download_name"><?php _e( 'List Name', $this->plugin_slug );

            ?></label>
                <input type="text" name="download_name" id="download_name" size="30" value=""/><br/>
                <p class="expl"><?php _e( '( Example: My Mailing List )', $this->plugin_slug );

            ?></p>
            </div>

            <div>
                <label for="doifd_download_landing_page"><?php _e( 'Select Landing Page', $this->plugin_slug );

            ?></label>

                <?php
                echo '<select name="doifd_download_landing_page" id="doifd_download_landing_page">';
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

            <div style="width: 100%; text-align: center">
                <input class='button button-primary' name="upload" id="upload" type="submit" value="<?php
                    _e( 'Add Mailing List', $this->plugin_slug );

                ?>">
            </div>

        </fieldset>

    </form>
</div>