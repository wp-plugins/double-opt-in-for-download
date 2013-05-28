<?php

add_action ( 'admin_init' , 'doifd_lab_recaptcha_init' ) ;

function doifd_lab_recaptcha_init() {

    register_setting ( 'doifd_lab_recaptcha_options' , 'doifd_lab_recaptcha_options' , 'doifd_lab_validate_recaptcha_options' ) ;
    add_settings_section ( 'doifd_lab_recaptcha_section' , 'reCaptcha Settings' , '' , 'doifd_lab_recaptcha' ) ;
    add_settings_field ( 'doifd_lab_recaptcha_public_key' , 'Enter Your reCaptcha Public Key' , 'doifd_lab_setting_recaptcha_public_key' , 'doifd_lab_recaptcha' , 'doifd_lab_recaptcha_section' ) ;
    
}

function doifd_lab_recaptcha_options() {

            global $wpdb ;

            // check to see if the user has the privileges to access the options page.
            if ( ! current_user_can ( 'manage_options' ) ) {
                wp_die ( __ ( 'You do not have sufficient permissions to access this page.' ) ) ;
            }

            include DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'includes/doifd-admin-header.php' ;
            ?>

            <!--Begin HTML markup-->
            <div class="wrap">
                <div id="icon-options-general" class="icon32"></div>
                <h2>Settings</h2>

                <!--Save Options Button-->

                <form action="options.php" method="post">
                    <?php
                    settings_fields ( 'doifd_lab_recaptcha_options' ) ;
                    do_settings_sections ( 'doifd_lab_recaptcha' ) ;
                    ?>
                    <input class='button-primary' name="Submit" type="submit" value="Save Changes">
                </form>

            </div> <!--Wrap End--> 

            <?php
        }

function doifd_lab_setting_recaptcha_public_key() {

// Email options form
            // get options from options table
            $doifd_recaptcha_options = get_option ( 'doifd_lab_recaptcha_options' ) ;

            // get recaptcha keys and assign to variable
            $doifd_recaptcha_public = $doifd_recaptcha_options['doifd_recaptcha_public'] ;
            $doifd_recaptcha_private = $doifd_recaptcha_options['doifd_recaptcha_private'] ;

            // echo email form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_recaptcha_options[doifd_recaptcha_public]" id="public_key" value="' . $doifd_recaptcha_public . '">' ;
            _e ( '<p>Enter your reCaptcha Pulic Key</p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }
?>
