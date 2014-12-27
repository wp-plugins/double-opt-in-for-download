<div class="wrap">
    <div class="doifdAdminLoader"></div>
    <?php include_once( DOIFD_DIR . '/admin/views/view-admin-header.php' ); ?>

    <div id="icon-options-general" class="icon32"></div>

    <form id="doifdOptionsForm" action="options.php" method="post">
        <div id="tabs">
            <ul>
                <?php
                $tabs = new DOIFDAdminGeneralOptionsFields();
                $tabs = $tabs->general_settings_tabs ();

                foreach ( $tabs as $href => $name ) {
                    echo '<li><h3><a href="#' . $href . '">' . $name . '</a></h3></li>';
                }

                ?>
            </ul>
            <?php
            $tabs_content = new DOIFDAdminGeneralOptionsFields();
            $tabs_content = $tabs_content->general_settings_content ();

            foreach ( $tabs_content as $tab_id => $content ) {

                echo '<div id="' . $tab_id . '">';

                if ( is_array ( $content ) ) {

                    $array_key = current ( array_keys ( $content ) );

                    foreach ( $content as $key => $value ) {

                        if ( in_array ( "doifd_mailchimp", $content, true ) ) {

                            if ( class_exists ( 'DOIFDPremiumAdmin' ) ) {

                                echo '</form>';
                                echo '<form action="options.php" method="post">';
                                settings_fields ( $array_key );
                                do_settings_sections ( $value );
                                echo '<input class="doifd_save_button" name="submit" type="submit" value="' . __ ( 'Save Changes', $this->plugin_slug ) . '">';
                            } else {

                                ?>
                                <div class="premium_promo">
                                    <p><?php _e ( 'The Mailchimp feature is only available<br>in the Premium Version', $this->plugin_slug ); ?></p>
                                    <a href="http://www.doubleoptinfordownload.com/premium-double-opt-in-for-download/" target="new" class="premium_promo_button" ><?php _e ( 'Click Here To Purchase Premium DOIFD', $this->plugin_slug ); ?></a></p>
                                </div>
                                <?php
                            }
                        } elseif ( in_array ( "doifd_constantcontact", $content, true ) ) {

                            if ( class_exists ( 'DOIFDPremiumAdmin' ) ) {

                                echo '</form>';
                                echo '<form action="options.php" method="post">';

                                settings_fields ( $array_key );
                                do_settings_sections ( $value );

                                echo '<input class="doifd_save_button" name="submit" type="submit" value="' . __ ( 'Save Changes', $this->plugin_slug ) . '">';
                            } else {

                                ?>
                                <div class="premium_promo">
                                    <p><?php _e ( 'The Constant Contact feature is only available<br>in the Premium Version', $this->plugin_slug ); ?></p>
                                    <p><a href="http://www.doubleoptinfordownload.com/premium-double-opt-in-for-download/" target="new" class="premium_promo_button" ><?php _e ( 'Click Here To Purchase Premium DOIFD', $this->plugin_slug ); ?></a></p>
                                </div>
                    <?php
                }
            } else {

                if ( in_array ( "doifd_aweber", $content, true ) ) {

                    echo '</form>';
                    echo '<form action="options.php" method="post">';
                    settings_fields ( $array_key );
                    do_settings_sections ( $value );
                } else {

                    settings_fields ( $array_key );
                    do_settings_sections ( $value );
                    echo '<input class="doifd_save_button" name="submit" type="submit" value="' . __ ( 'Save Changes', $this->plugin_slug ) . '">';
                }
                echo '<hr />';
            }
        }
    } else {

        if ( ! class_exists ( 'DOIFDPremiumAdmin' ) && ( $content == "doifd_lab_captcha" ) ) {

            ?>

                        <div class="premium_promo">
                            <p><?php _e ( 'The Captcha feature is only available<br>in the Premium Version', $this->plugin_slug ); ?></p>
                            <p><a href="http://www.doubleoptinfordownload.com/premium-double-opt-in-for-download/" target="new" class="premium_promo_button" ><?php _e ( 'Click Here To Purchase Premium DOIFD', $this->plugin_slug ); ?></a></p>
                        </div>

            <?php
        } else {

            settings_fields ( 'doifd_lab_options' );
            do_settings_sections ( $content );
            echo '<input class="doifd_save_button" name="submit" type="submit" value="' . __ ( 'Save Changes', $this->plugin_slug ) . '">';
        }
    }


    echo '</div>';
}

?>

        </div>
    </form>
</div> <!--Wrap End--> 