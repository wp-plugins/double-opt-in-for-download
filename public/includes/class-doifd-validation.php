<?php

class DOIFDValidation extends DOIFD {

    protected $data = array( );
    protected $errorMessages = array( );
    protected $isValid = true;
    protected $download_id = null;
    protected $name = null;
    protected $email = null;
    protected $duplicate_email = null;
    protected $clean_data = array( );

    public function __construct( $params ) {
        parent::__construct();

        if( empty( $params ) || !is_array( $params ) ) {
            throw new Exception( "Invalid data!" );
        }
        $this->data = $params;
        $this->download_id = $this->getDownloadID();
        $this->name = $this->getName();
        $this->email = $this->getEmail();
        $this->duplicate_email = $this->getIsDuplicateEmail();        
    }

    public function validate() {

        if( empty( $this->name ) ) {
            $this->errorMessages[ 'name' ] = __( 'Please provide your name.', $this->plugin_slug );
        } else {
            $firstName = sanitize_text_field( $this->name );
            if( strlen( $firstName ) < 2 ) {
                $this->errorMessages[ 'name' ] = __( 'Your name must be at least 2 characters!', $this->plugin_slug );
            }
        }

        if( empty( $this->email ) ) {
            $this->errorMessages[ 'email' ] = __( 'Please provide your email address!', $this->plugin_slug );
        } else {
            $email = filter_var( $this->email, FILTER_SANITIZE_EMAIL );
            if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $this->errorMessages[ 'email' ] = __( 'Not a valid email address.', $this->plugin_slug );
            }
        }
        if( $this->duplicate_email != null ) {
            $this->errorMessages[ 'duplicate_email' ] = __( 'This email address has already been used.', $this->plugin_slug );
        }

        if( !empty( $this->errorMessages ) ) {
            $this->isValid = false;
        }
    }

    public function getDownloadID() {

        if( isset( $this->data[ 'widget_download_id' ] ) ) {
            $this->download_id = $this->data[ 'widget_download_id' ];
        }

        if( isset( $this->data[ 'download_id' ] ) ) {
            $this->download_id = $this->data[ 'download_id' ];
        }

            return $this->download_id;
    }

    public function getName() {

        if( isset( $this->data[ 'doifd_widget_user_name' ] ) ) {
            $this->name = $this->data[ 'doifd_widget_user_name' ];
        }

        if( isset( $this->data[ 'doifd_user_name' ] ) ) {
            $this->name = $this->data[ 'doifd_user_name' ];
        }
        return $this->name;
    }

    public function getEmail() {

        if( isset( $this->data[ 'doifd_widget_user_email' ] ) ) {
            $this->email = $this->data[ 'doifd_widget_user_email' ];
        }

        if( isset( $this->data[ 'doifd_user_email' ] ) ) {
            $this->email = $this->data[ 'doifd_user_email' ];
        }
        return $this->email;
    }

    public function getIsDuplicateEmail() {

        global $wpdb;

        $this->duplicate_email = $wpdb->get_row(
                $wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}doifd_lab_subscribers
                            WHERE doifd_email = %s AND doifd_download_id = %d", $this->email, $this->download_id ), ARRAY_A );

        return $this->duplicate_email;
    }

    public function getIsValid() {
        return $this->isValid;
    }

    public function getCleanUploadData() {

        $this->clean_data = array(
            'user_name' => $this->name,
            'user_email' => $this->email,
            'id' => $this->download_id
        );
        return $this->clean_data;
    }

    public function getErrors() {
        return $this->errorMessages;
    }

}