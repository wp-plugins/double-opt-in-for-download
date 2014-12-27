<?php

/*
 * @TODO
 * Calling pluggable because of the nonce location. Needs to be within an action.
 * I don't like this.
 */
require_once(ABSPATH .'wp-includes/pluggable.php');

if( !class_exists( 'DOIFDRegistrationForm' ) ) {

    class DOIFDRegistrationForm extends DOIFD {

        protected $errorMessages = array( );
        protected $validDownload = true;
        protected $attr;
        protected $download_id = '';
        protected $data = array( );
        protected $fileExists = true;
        protected $promoLink = '';
        protected $headerText = '';
        protected $buttonText = '';
        protected $nonce = '';
        protected $privacyPolicy = '';
        protected $msg = '';

        public function __construct( $attr = array( ), $content = array( ), $msg = null ) {
            parent::__construct();
            
            $this->attr = $attr;
            $this->download_id = $this->getDownloadID();
            $this->data = $this->getDownloadInfo();
            $this->fileExists = $this->file_exists();
            $this->promoLink = $this->getPromoLink();
            $this->headerText = $this->getHeaderText();
            $this->buttonText = $this->getButtonText();
            $this->nonce = wp_create_nonce( 'doifd-subscriber-registration-nonce' );
            $this->privacyPolicy = $this->getPrivacyPolicy();
            $this->msg = $msg;

        }

        public function getErrors() {
            return '<div class="exceeded"><img src="' . DOIFD_URL . 'public/assets/img/warning.png" alt="Warning" title="Warning" /><br />' . $this->errorMessages . '</div>';
        }

        public function getValidDownload() {
            return $this->validDownload;
        }
        
        public function getDownloadID() {
            if( isset($this->attr[ 'download_id' ])) {
                $this->download_id = $this->attr[ 'download_id' ];
            } else {
                $this->download_id = '';
            }
            return $this->download_id;
        }
 
        public function getDownloadInfo() {
            global $wpdb;

            $sql = $wpdb->prepare( "SELECT
                doifd_download_name,
                doifd_download_file_name,
                doifd_download_type
                FROM
                {$wpdb->prefix}doifd_lab_downloads WHERE doifd_download_id = %s", $this->download_id );

            $this->data = $wpdb->get_row( $sql, ARRAY_A );
            return $this->data;
        }

        public function file_exists() {

            $upload_dir = wp_upload_dir();
            $file = $upload_dir[ 'basedir' ] . '/doifd_downloads/' . $this->data[ 'doifd_download_file_name' ];

            if( ( $this->data[ 'doifd_download_type' ] == '0' ) && !file_exists( $file ) ) {
                $this->fileExists = false;
            } else {
                $this->fileExists = true;
            }
            return $this->fileExists;
        }

        public function getHeaderText() {

            if( isset( $this->attr[ 'text' ] ) ) {
                $this->headerText = $this->attr[ 'text' ];
            } else {
                $this->headerText = __( 'Please provide your name and email address for your free download.', $this->plugin_slug );
            }
            return $this->headerText;
        }
        
        public function getButtonText() {

            if( isset( $this->attr[ 'button_text' ] ) ) {
                $this->buttonText = $this->attr[ 'button_text' ];
            } else {
                $this->buttonText = __( 'Submit', $this->plugin_slug );
            }
            return $this->buttonText;
        }

        public function getPromoLink() {

            if( $this->doifd_options[ 'promo_link' ] == '1' ) {
                $this->promoLink = '<p class="doifd_promo_link"><a href="http://www.doubleoptinfordownload.com" target="new" Title="' . __( 'Powered by DOIFD', $this->plugin_slug ) . '">' . __( 'Powered by DOIFD', $this->plugin_slug ) . '</a></p>';
            } else {
                $this->promoLink = '';
            }
            return $this->promoLink;
        }

        public function getNonce() {
            global $wpdb;
            $this->nonce = wp_create_nonce( 'doifd-subscriber-registration-nonce' );
            return $this->nonce;
        }

        public function getPrivacyPolicy() {

            if( isset($this->doifd_options[ 'use_privacy_policy' ]) && ( $this->doifd_options[ 'use_privacy_policy' ] == '1' ) ) {
                $text = $this->doifd_options[ 'privacy_link_text' ];
                $link = $this->doifd_options[ 'privacy_page' ];

                $this->privacyPolicy = '<div class="doifd_privacy_link"><a href="' . get_page_link( $link ) . '" target="new" >' . $text . '</a></div>';
            } else {

                $this->privacyPolicy = '';
            }
            return $this->privacyPolicy;
        }

        public function getCaptch() {
            /*
             * @TODO
             * How am I going to integrate captcha
             * Old code below
             */
            $options = get_option( 'doifd_lab_options' );

            if( class_exists( 'DOIFDPremium' ) && ( $options[ 'doifd_recaptcha_enable_form' ] ) == 1 ) {

                $doifd_captcha = TRUE;
            } else {

                $doifd_captcaha = FALSE;
            }
        }
        
        public function render_form() {
            
            global $download_id;
            $download_id = $this->download_id;
            
            $this->form_values = apply_filters( 'doifd_form_setup_values', array(
                "form_text" => $this->headerText,
                "id" => $this->download_id,
                "name" => __( 'Name', $this->plugin_slug ),
                "nonce" => $this->nonce,
                "email" => __( 'Email', $this->plugin_slug ),
                "button_text" => $this->buttonText,
                "privacy" => $this->privacyPolicy,
                "promo" => $this->promoLink,
                "error" => $this->msg
                    ) );

            ob_start();
            if( isset($this->doifd_options[ 'use_form_labels' ]) && $this->doifd_options[ 'use_form_labels' ] == 1 ) {
                $form = include_once ( DOIFD_DIR . '/public/views/forms/view-default.php' );
            } else {
                $form = include_once ( DOIFD_DIR . 'public/views/forms/view-default-no-labels.php' );
            }
            $this->form_output = ob_get_contents();
            ob_end_clean();
            return apply_filters( 'doifd_form_output', $this->form_output, $this->form_values );
        }

        public function registration_form() {

            global $wpdb;

            if( empty( $this->download_id ) ) {

                $this->errorMessages = __( 'There is no download ID specified', $this->plugin_slug );
            }

            if( empty( $this->data ) ) {

                $this->errorMessages = __( 'The download ID does not exist', $this->plugin_slug );
            }

            /* Is the physical donwload on the server? */
            if( $this->fileExists == false ) {
                $this->errorMessages = __( 'The download files does not exist', $this->plugin_slug );
            }

            /* Set validDownload to false if any errors were generated */
            if( !empty( $this->errorMessages ) ) {
                $this->validDownload = false;
            }            

            
        }

    }

}