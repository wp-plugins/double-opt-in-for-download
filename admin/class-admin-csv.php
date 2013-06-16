<?php

if ( !class_exists ( 'DoifdCSV' ) ) {

    class DoifdCSV {

        public function __construct() {

            add_action ( 'admin_init', array( &$this, 'csv_download' ) );

        }

        function csv_download() {

            global $wpdb;

            /* Check if it's coming from the download subsribers button and the user has privileges */

            if ( isset ( $_POST[ 'doifd_lab_export_csv' ] ) && ( current_user_can ( 'manage_options' ) ) ) {

                /* Create name for file "Website Name-Subscribers-Date" */

                $fileName = get_bloginfo ( 'name' ) . '-Subscribers-' . date ( 'Y-m-d' ) . '.csv';

                /* Header for download */

                header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
                header ( 'Content-Description: File Transfer' );
                header ( "Content-type: text/csv" );
                header ( "Content-Disposition: attachment; filename={$fileName}" );
                header ( "Expires: 0" );
                header ( "Pragma: public" );

                /* Create file */

                $fh = @fopen ( 'php://output', 'w' );

                /* Query database for list of subscribers. Only pull verified email addresses
                 * and don't include duplicates.
                 */

                $sql = "SELECT doifd_name AS Name, doifd_email AS Email
                FROM {$wpdb->prefix}doifd_lab_subscribers
                WHERE doifd_email_verified = '1'
                GROUP BY doifd_email";

                $results = $wpdb->get_results ( $sql, ARRAY_A );

                $headerDisplayed = false;

                foreach ( $results as $data ) {

                    /* Add header rows if not already displayed */

                    if ( !$headerDisplayed ) {

                        /* Use the keys from $data as the titles */

                        fputcsv ( $fh, array_keys ( $data ) );

                        $headerDisplayed = true;
                    }

                    /* Put the data into the file */

                    fputcsv ( $fh, $data );
                }

                /* Close the file */

                fclose ( $fh );

                /* Make sure nothing else is sent, our file is done */

                exit;
            }

        }

    }

}

?>
