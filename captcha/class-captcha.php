<?php

if ( !class_exists ( 'DoifdCaptcha' ) ) {

    class DoifdCaptcha {

        public function __construct() {
            
        }

        /* This function processes the reCAPTCHA phrase with GOOGLE */

        public static function reCaptcha_process() {

            $options = get_option ( 'doifd_lab_recaptcha_options' );

            $privatekey = $options[ 'doifd_recaptcha_private' ];

            $response = doifd_recaptcha_check_answer ( $privatekey, $_SERVER[ "REMOTE_ADDR" ], $_POST[ "recaptcha_challenge_field" ], $_POST[ "recaptcha_response_field" ] );

            return $response;

        }

        /* This fucntion gets the public key from the wp options table */

        public static function reCaptcha_public_key() {

            $options = get_option ( 'doifd_lab_recaptcha_options' );

            $publickey = $options[ 'doifd_recaptcha_public' ];

            return $publickey;

        }

        /* This fucntion creates the reCAPTCHA admin options page */

        public static function reCaptcha_admin_options_page() {

            ?>

            <!--Begin HTML markup-->

            <div class="wrap">

                <div id="icon-options-general" class="icon32"></div>

                <h2>reCaptcha Settings</h2>

            <?php include DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'views/view-admin-header.php'; ?>

                <!--Save Options Button-->

                <form action="options.php" method="post">

                    <?php

                    settings_fields ( 'doifd_lab_recaptcha_options' );

                    do_settings_sections ( 'doifd_lab_recaptcha' );

                    ?>

                    <input class='button-primary' name="Submit" type="submit" value="Save Changes">

                </form>

            </div> <!--Wrap End--> 

            <?php

        }

        /* This function creates the private key field in the admin reCAPTCHA options section */

        public static function recaptcha_private_key_field() {

            /* Get options from options table */

            $doifd_recaptcha_option = get_option ( 'doifd_lab_recaptcha_options' );

            /* Get recaptcha keys and assign to variable */

            $doifd_recaptcha_private = $doifd_recaptcha_option[ 'doifd_recaptcha_private' ];

            /* Echo reCAPTCHA private key field */

            echo '<div id="doifd_lab_admin_options">';

            echo '<input type="text" name="doifd_lab_recaptcha_options[doifd_recaptcha_private]" id="private_key" size="50" value="' . $doifd_recaptcha_private . '">';

            echo '<p>' . __( 'Enter your reCaptcha Private Key', 'double-opt-in-for-download' ) . '</p>';

            echo '</div>';

        }

        public static function recaptcha_public_key_field() {

            // get options from options table

            $doifd_recaptcha_options = get_option ( 'doifd_lab_recaptcha_options' );

            // get recaptcha keys and assign to variable

            $doifd_recaptcha_public = $doifd_recaptcha_options[ 'doifd_recaptcha_public' ];

            // echo email form
            echo '<div id="doifd_lab_admin_options">';

            echo '<input type="text" name="doifd_lab_recaptcha_options[doifd_recaptcha_public]" id="public_key" size="50" value="' . $doifd_recaptcha_public . '">';

            echo '<p>' . __( 'Enter your reCaptcha Pulic Key', 'double-opt-in-for-download' ) . '</p>';

            echo '</div>';

        }

        public static function reCaptcha_validate( $input ) {

            $valid = array( );

            $valid[ 'doifd_recaptcha_public' ] = preg_replace ( '/[^a-z0-9_-]/i', '', $input[ 'doifd_recaptcha_public' ] );

            $valid[ 'doifd_recaptcha_private' ] = preg_replace ( '/[^a-z0-9_-]/i', '', $input[ 'doifd_recaptcha_private' ] );

            return $valid;

        }

    }

}

?>