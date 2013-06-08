<?php

class DoifdDownload {
    
    function __construct() {
        
    /* Retrieves the Download */
    
    add_action ( 'init' , array( &$this, 'link_to_download' ) ) ;
    
    }
        
/* This function retrives the download for the subscriber */

function link_to_download() {

    global $wpdb ;

    // if they have clicked the download button let proceed
    if ( isset ( $_POST['doifd_get_download'] ) ) {

        // assigns the sql table names to a varaible
        $wpdb->doifd_subscribers = $wpdb->prefix . 'doifd_lab_subscribers' ;
        $wpdb->doifd_downloads = $wpdb->prefix . 'doifd_lab_downloads' ;

        // sanitize the download id, just in case
        $doifd_download_id = preg_replace ( '/[^0-9]/' , '' , $_POST['download_id'] ) ;

        // get the verification number so we can look up what there download is.
        $ver = $_POST['ver'] ;

        // if both are valid, lets continue
        if ( isset ( $doifd_download_id ) && ( isset ( $ver ) ) ) {

            // get options from options table
            $options = get_option ( 'doifd_lab_options' ) ;

            // get the maximum downloads possible option to variable
            $allowed = $options['downloads_allowed'] ;

            //Set a default of 1 in case the admin did not select this option
            if ( empty ( $allowed ) ) {
                $allowed = '1' ;
            }
            //Check to see how many times this subscriber has downloaded the file
            $checkallowed = $wpdb->get_row ( "SELECT doifd_downloads_allowed FROM " . $wpdb->prefix . "doifd_lab_subscribers  WHERE doifd_verification_number = '$ver' " ) ;

            //If subscriber has exceeded maximum, show them the exceeded download message.
            if ( $checkallowed->doifd_downloads_allowed >= $allowed ) {

                return '<div id="doifd_user_reg_form" class="exceeded">You have exceed your number of downloads for this item.</div>' ;
            }

            // query database and assign results to varialbe
            $get_file_name = $wpdb->get_row ( "SELECT doifd_download_file_name FROM " . $wpdb->prefix . "doifd_lab_downloads  WHERE doifd_download_id = '$doifd_download_id' " , ARRAY_A ) ;

            // assign file name to variable.
            $file_name = $get_file_name['doifd_download_file_name'] ;

            // get the file extension
            $extension = pathinfo ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . $file_name , PATHINFO_EXTENSION ) ;

            // give the file a fake name to help hide the actual file
            $fakeFileName = 'Your-Download.' . $extension ;

            // assign the real file name to a variable
            $realFileName = $file_name ;

            // assign real file name and path to variable
            $file = DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . '/' . $realFileName ;

            // open the file
            $fp = fopen ( $file , 'rb' ) ;

            // assign the appropriate Content-Type header for the file and send file to subscribers browser
            if ( $extension == 'jpg' ) {
                header ( 'Content-Type: image/jpg' ) ;
            }
            elseif ( $extension == 'jpeg' ) {
                header ( 'Content-Type: image/jpeg' ) ;
            }
            elseif ( $extension == 'png' ) {
                header ( 'Content-Type: image/png' ) ;
            }
            elseif ( $extension == 'bmp' ) {
                header ( 'Content-Type: image/bmp' ) ;
            }
            elseif ( $extension == 'zip' ) {
                header ( "Content-type: application/zip" ) ;
            }
            elseif ( $extension == 'gif' ) {
                header ( 'Content-Type: image/gif' ) ;
            }
            elseif ( $extension == 'doc' ) {
                header ( "Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document" ) ;
            }
            elseif ( $extension == 'docx' ) {
                header ( "Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document" ) ;
            }
            elseif ( $extension == 'pdf' ) {
                header ( "Content-type: application/octet-stream" ) ;
            }
            header ( 'Content-Transfer-Encoding: binary' ) ;
            header ( "Content-Disposition: attachment; filename=$fakeFileName" ) ;
            header ( "Content-Length:" . filesize ( $file ) ) ;
            fpassthru ( $fp ) ;

            // if the conection / download status was successful update subscriber status to show successful download
            // and download table to show total downloads
            if ( connection_status () == CONNECTION_NORMAL ) {

                // update download table
                $wpdb->query (
                        "
                        UPDATE $wpdb->doifd_downloads
                        SET doifd_number_of_downloads = doifd_number_of_downloads+1 WHERE doifd_download_id = '$doifd_download_id'
                    "
                ) ;

                // update subscribers downloads_allowed
                $wpdb->query (
                        "
                        UPDATE $wpdb->doifd_subscribers
                        SET doifd_downloads_allowed = doifd_downloads_allowed+1 WHERE doifd_verification_number = '$ver'
                    "
                ) ;
            }
        }
    }
}
    
}

?>
