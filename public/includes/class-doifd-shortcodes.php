<?php

class DOIFDShortcodes extends DOIFD {

    public function __construct() {

        /* Landing Page Button */

        add_shortcode( 'lab_landing_page', array( &$this, 'doifd_lab_verify_email' ) );

        /* The Registration Form for Posts & Pages */

        add_shortcode( 'lab_subscriber_download_form', array( &$this, 'doifd_lab_subscriber_registration_form' ) );
    }

    public function doifd_lab_verify_email( $attr, $content ) {

        if( isset( $_GET[ 'ver' ] ) ) {
            $process = new DOIFDLandingPage( $attr, $content );
            $process->verify_email();

            if( $process->getValidVer() ) {
                $process->update_user();
                if( class_exists( 'DOIFDPremiumLandingPage' ) ) {
                    $preProcess = new DOIFDPremiumLandingPage( $attr, $content );
                    $preProcess->notify_admin();
                    $preProcess->permium_servies();
                } else {
                $process->notify_admin();
                }
                $landing = $process->renderButton();

                return apply_filters( 'doifd_landing_bypass', $landing );
            } else {
                $errors = $process->getErrors();
                return $errors;
            }
        }
    }

    /* Create the registration form */

    function doifd_lab_subscriber_registration_form( $attr, $content ) {

        if( !empty( $_POST ) ) {
            if( class_exists( 'DOIFDPremiumRegistrationForm' ) ) {
                $process = new DOIFDPremiumRegistrationProcess( $_POST );
                $msg = $process->registration_process();
            } else {
                $process = new DOIFDRegistrationProcess( $_POST );
                $msg = $process->registration_process();
            }
        } else {
            $msg = '';
        }
        if( class_exists( 'DOIFDPremiumRegistrationForm' ) ) {
            $getForm = new DOIFDPremiumRegistrationForm( $attr, $content, $msg );
            $getForm->registration_form();
        } else {
            $getForm = new DOIFDRegistrationForm( $attr, $content, $msg );
            $getForm->registration_form();
        }

        if( $getForm->getValidDownload() ) {
            $doifd_form = $getForm->render_form();
            return $doifd_form;
        } else {
            $errors = $getForm->getErrors();
            return $errors;
        }
    }

}
new DOIFDShortcodes();
