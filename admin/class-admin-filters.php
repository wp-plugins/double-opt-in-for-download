<?php

if ( !class_exists( 'DoifdAdminFilters' ) ) {

    class DoifdAdminFilters {

        function __construct() {
            
        }

        function doifd_additional_admin_fields() {
            
            $output = '';
            
            return apply_filters( 'doifd_upload_fields', $output );

        }

    }

}
?>
