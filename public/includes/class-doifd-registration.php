<?php

//require_once( DOIFD_DIR . '/securimage/securimage.php' );
//require_once( DOIFD_DIR . 'includes/class-doifd-email.php' );

if( !class_exists( 'DOIFDRegistrationProcess' ) ) {

    class DOIFDRegistrationProcess extends DOIFD {

        protected $data = array( );
        protected $ver;
        protected $download_id;

        public function __construct( $params ) {
            parent::__construct();

            if( empty( $params ) || !is_array( $params ) ) {
                throw new Exception( "Invalid data!" );
            }

            $this->data = $params;
            $this->ver = $this->ver();
            $this->download_id = $this->download_id();
        }

        public function ver() {

            $this->ver = sha1( time() );
            return $this->ver;
        }
        
        public function download_id(){
            if(isset($this->data['download_id'])){
                $this->download_id = $this->data['download_id'];
            }elseif(isset($this->data['widget_download_id'])){
                $this->download_id = $this->data['widget_download_id'];
            }else{
                $this->download_id = '';
            }
            return $this->download_id;
        }

        public function widget_registration_process() {

            if( isset( $_POST[ 'doifd_widget_download_form' ] ) ) {

                global $wpdb;

                if( $this->doifd_options[ 'form_security' ] == '0' ) {

                    if( !wp_verify_nonce( $_POST[ 'widget_wpnonce' ], 'doifd-subscriber-registration-nonce' ) ) wp_die( 'Security check' );
                }

                $html = '';
                $errors = array( );
                $validatorObj = new DOIFDValidation( $_POST );
                $validatorObj->validate();

                if( $validatorObj->getIsValid() ) {
                    $value = $validatorObj->getCleanUploadData();
                    $this->add_wp_users_db( $value );
                    $this->add_to_db( $value );
                    $this->send_email( $value );

                    add_filter( 'doifd_widget_output', array( $this, 'doifd_form_success_msg' ) );
                } else {

                    $errors = $validatorObj->getErrors();
                    foreach ( $errors as $value ) {
                        $html .= '<div class="doifd_error_msg">' . $value . '</div>';
                    }
                    return $html;
                }
            }
        }

        public function registration_process() {

            if( isset( $this->data[ 'doifd_download_form' ] ) ) {

                global $wpdb;

                if( isset($this->doifd_options[ 'form_security' ]) && $this->doifd_options[ 'form_security' ] == '0' ) {

                    if( !wp_verify_nonce( $_POST[ '_wpnonce' ], 'doifd-subscriber-registration-nonce' ) ) wp_die( 'Security check' );
                }

                $html = '';
                $errors = array( );
                $validatorObj = new DOIFDValidation( $this->data );
                $validatorObj->validate();

                if( $validatorObj->getIsValid() ) {
                    $value = $validatorObj->getCleanUploadData();
                    $this->add_wp_users_db( $value );
                    $this->add_to_db( $value );
                    $this->send_email( $value );

                    add_filter( 'doifd_form_output', array( $this, 'doifd_form_success_msg' ) );
                } else {

                    $errors = $validatorObj->getErrors();
                    foreach ( $errors as $value ) {
                        $html .= '<div class="doifd_error_msg">' . $value . '</div>';
                    }
                    return $html;
                }
            }
        }

        public function doifd_form_success_msg() {
           
            global $wpdb;

            $type = $wpdb->get_var( $wpdb->prepare( "SELECT doifd_download_type FROM {$wpdb->prefix}doifd_lab_downloads WHERE doifd_download_id = %d", $this->download_id ) );

            if( $type === '1' ) {
                
                $msg = '<div class="doifd_success_msg"><h4>' . __( 'Thank You for Registering!', $this->plugin_slug ) . '<br />' . __( 'Please check your email to confirm your subscription.', $this->plugin_slug ) . '</h4><br /><i>' . __( 'Don\'t forget to check your junk or spam folder.', $this->plugin_slug ) . '</i></div>';
            
                } else {
                
                $msg = '<div class="doifd_success_msg"><h4>' . __( 'Thank You for Registering!', $this->plugin_slug ) . '<br />' . __( 'Please check your email for your link to your Free download.', $this->plugin_slug ) . '</h4><br /><i>' . __( 'Don\'t forget to check your junk or spam folder.', $this->plugin_slug ) . '</i></div>';
            }

            return $msg;
        }

        public function add_to_db( $values ) {

            global $wpdb;

            /* Insert subscriber into the database */

            $data = apply_filters( 'doifd_insert_subscriber_data', $data = array(
                'doifd_name' => $values[ 'user_name' ],
                'doifd_email' => $values[ 'user_email' ],
                'doifd_verification_number' => $this->ver,
                'doifd_download_id' => $values[ 'id' ],
                'time' => current_time( 'mysql', 0 )
                    ), $values, $this->ver );
            $format = apply_filters( 'doifd_insert_subscriber_format', $format = array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
                    ) );

            $wpdb->insert( $wpdb->prefix . 'doifd_lab_subscribers', $data, $format );
        }

        public function send_email( $values ) {

            if( has_filter( 'doifd_alt_verification_email' ) ) {

                $send = apply_filters( 'doifd_alt_verification_email', $value = array(
                    "user_name" => $values[ 'user_name' ],
                    "user_email" => $values[ 'user_email' ],
                    "user_ver" => $this->ver,
                    "download_id" => $values[ 'id' ] ) );
            } else {

                $send_ver_email = new DOIFDEmail();
                $send_ver_email->send_verification_email( $value = array(
                    "user_name" => $values[ 'user_name' ],
                    "user_email" => $values[ 'user_email' ],
                    "user_ver" => $this->ver,
                    "download_id" => $values[ 'id' ] ) );
            }
        }

        public function add_wp_users_db( $values ) {

            global $wpdb;

            $duplicate_email = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}doifd_lab_subscribers WHERE doifd_email = %s AND doifd_download_id = %d", $values[ 'user_email' ], $values[ 'id' ] ), ARRAY_A );
            /* If yes, lets add the user if not, we will just go on our merry way. */

            if( ( $this->doifd_options[ 'add_to_wpusers' ] == '1' ) && ($duplicate_email == NULL ) ) {

                /* Generate a random password for the new user */

                $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );

                /* Insert into wp user table and get user id for meta information */

                $user_id = wp_create_user( $values[ 'user_email' ], $random_password, $values[ 'user_email' ] );

                /* Just for fun lets explode the subscriber name. in case they entered their first and last name */

                $name = explode( ' ', $values[ 'user_name' ] );

                /* Add first name to user meta table */

                update_user_meta( $user_id, 'first_name', $name[ 0 ] );

                /* If subcriber entered 2 names lets add the second as the last name */

                if( !empty( $name[ 1 ] ) ) {

                    update_user_meta( $user_id, 'last_name', $name[ 1 ] );
                }
            }
        }

    }

}
//new DOIFDRegistrationProcess();