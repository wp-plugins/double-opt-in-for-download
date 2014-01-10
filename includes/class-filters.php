<?php

if ( !class_exists ( 'DoifdFilters' ) ) {

    class DoifdFilters {


        function __construct() {
            
        }

       function doifd_settings_link($links) {
            $links[] = '<a href="'. get_admin_url(null, 'admin.php?page=double-opt-in-for-download/admin/doifd-admin.php') .'">Settings</a>';
            $links[] = '<a href="'. get_admin_url(null, 'admin.php?page=double-opt-in-for-download/admin/doifd-admin.php_downloads') .'">Downloads</a>';
            $links[] = '<a href="'. get_admin_url(null, 'admin.php?page=double-opt-in-for-download/admin/doifd-admin.php_subscribers') .'">Subscribers</a>';
            $links[] = '<a href="http://www.doubleoptinfordownload.com/premium-double-opt-in-for-download/" target="_blank">Get Premium Version</a>';
            return $links;
        }

    }

}

?>
