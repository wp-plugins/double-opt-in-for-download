<?php

class DOIFDLandingPage extends DOIFD {

    protected $errorMessages = array( );
    protected $validVer = true;
    protected $verification = '';
    protected $data = array( );
    protected $attr;
    protected $content;
    protected $buttonText = NULL;
    protected $fileExists = true;

    public function __construct( $attr = array( ), $content = array( ) ) {
        parent::__construct();

        
        $this->verification = $this->getVerification();
        $this->data = $this->getUserData();
        $this->fileExists = $this->file_exists();
        $this->attr = $attr;
        $this->content = $content;
        $this->buttonText = $this->getButtonText();
    }

    public function getVerification() {
        $this->verification = $_GET[ 'ver' ];
        return $this->verification;
    }

    public function getAttr() {
        if( isset( $attr ) ) {
            $this->attr = $attr;
        } else {
            $this->attr = '';
        }
        return $this->attr;
    }

    public function getContent() {
        if( isset( $content ) ) {
            $this->content = $content;
        } else {
            $this->content = '';
        }
        return $this->content;
    }

    public function getValidVer() {
        return $this->validVer;
    }

    public function getErrors() {
        return '<div class="exceeded"><img src="' . DOIFD_URL . 'public/assets/img/warning.png" alt="Warning" title="Warning" /><br />' . $this->errorMessages . '</div>';
    }

    public function getButtonText() {

        if( !empty( $this->attr[ 'button_text' ] ) ) {
            $this->buttonText = $this->attr[ 'button_text' ];
        } else {
            $this->buttonText = __( 'Click Here For Your Free Download', $this->plugin_slug );
        }
        return $this->buttonText;
    }

    public function getUserData() {

        global $wpdb;

        $sql = "SELECT * 
                FROM {$wpdb->prefix}doifd_lab_subscribers
                INNER JOIN {$wpdb->prefix}doifd_lab_downloads
                ON {$wpdb->prefix}doifd_lab_downloads.doifd_download_id = {$wpdb->prefix}doifd_lab_subscribers.doifd_download_id
                WHERE doifd_verification_number = '$this->verification'";

        $this->data = $wpdb->get_row( $sql, ARRAY_A );

        return $this->data;
    }

    public function renderButton() {
        
        if( $this->data[ 'doifd_download_type' ] == '1' ) {
            
            $this->button = '<h3>Thank you for confirming your email address</h3>';
            
        } else {
            
        $this->button = '<div id="doifd_user_reg_form" class="thankyou">
                        <br /><form method="get" action="" enctype="multipart/form-data">
                        <input type="hidden" name="download_id" value="' . $this->data[ 'doifd_download_id' ] . '">
                        <input type="hidden" name="ver" value="' . $this->verification . '">
                        <input name="doifd_get_download" type="submit" value="' . $this->buttonText . '">
                        </form>
                        </div>';
        }
        return $this->button;
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

    public function update_user() {

        global $wpdb;

        $wpdb->update(
                $wpdb->prefix . 'doifd_lab_subscribers', array(
            'doifd_email_verified' => '1',
                ), array( 'doifd_subscriber_id' => $this->data[ 'doifd_subscriber_id' ] ), array(
            '%d'
                ), array( '%d' )
        );
    }

    public function notify_admin() {

        if( $this->doifd_options[ 'notification_email' ] == '1'  &&  $this->data[ 'doifd_email_verified' ] == '0' ) {

            $notify_admin = new DoifdEmail();
            $notify_admin->admin_notification( $dfdValue = array(
                    $this->data[ 'doifd_name' ], $this->data[ 'doifd_download_name' ], $this->data[ 'doifd_email' ], $this->data[ 'doifd_download_id' ] ) );
        }
    }

    public function verify_email() {

        global $wpdb;

        /* Check for the verification number */
        if( empty( $this->verification ) ) {
            $this->errorMessages = __( 'Not a valid verification number.', $this->plugin_slug );
        }

        /* See if the verification number is valid */
        if( empty( $this->data ) ) {

            $this->errorMessages = __( 'Not a valid verification number.', $this->plugin_slug );
        }

        /* See if the file really exists on the server */
        if( $this->fileExists == false ) {

            $this->errorMessages = __( 'The download does not exist', $this->plugin_slug );
        }

        /* See if the user has exceeded the download limit */
        if( ( $this->data[ 'doifd_email_verified' ] == '1' ) && ( $this->data[ 'doifd_downloads_allowed' ] >= $this->doifd_options[ 'downloads_allowed' ] ) ) {

            $this->errorMessages = __( 'You have exceeded the maximum number of', $this->plugin_slug ) . '<br />' . __( 'downloads for this item.', $this->plugin_slug );
        }

        /* Set validVer to false if any errors were generated */
        if( !empty( $this->errorMessages ) ) {
            $this->validVer = false;
        }
    }

    

}