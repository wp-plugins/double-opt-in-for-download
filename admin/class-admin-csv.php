<?php

class DoifdCSV {
    
    public function __construct() { 
        
        add_action ( 'admin_init', array( &$this, 'csv_download' ) );
        
    }
    
    function csv_download() {
        
        global $wpdb ;

            // check if it's coming from the download subsribers button and the user has privileges
            if ( isset ( $_POST['doifd_lab_export_csv'] ) && ( current_user_can ( 'manage_options' ) ) ) {

                // create name for file "Website Name-Subscribers-Date"
                $fileName = get_bloginfo ( 'name' ) . '-Subscribers-' . date ( 'Y-m-d' ) . '.csv' ;
                
                // header for download
                header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" ) ;
                header ( 'Content-Description: File Transfer' ) ;
                header ( "Content-type: text/csv" ) ;
                header ( "Content-Disposition: attachment; filename={$fileName}" ) ;
                header ( "Expires: 0" ) ;
                header ( "Pragma: public" ) ;
                
                // create file
                $fh = @fopen ( 'php://output' , 'w' ) ;

                // query database for list of subscribers. Only pull verified email addresses and don't include duplicates.
                $sql = "SELECT doifd_name AS Name, doifd_email AS Email
                FROM {$wpdb->prefix}doifd_lab_subscribers
                WHERE doifd_email_verified = '1'
                GROUP BY doifd_email" ;
                $results = $wpdb->get_results ( $sql , ARRAY_A ) ;

                $headerDisplayed = false ;

                foreach ( $results as $data ) {

                    // add header rows if not already displayed
                    if ( ! $headerDisplayed ) {

                        // use the keys from $data as the titles
                        fputcsv ( $fh , array_keys ( $data ) ) ;
                        $headerDisplayed = true ;
                    }

                    // put the data into the file
                    fputcsv ( $fh , $data ) ;
                }

                // close the file
                fclose ( $fh ) ;

                // make sure nothing else is sent, our file is done
                exit ;
            }
            
    }
}
?>
