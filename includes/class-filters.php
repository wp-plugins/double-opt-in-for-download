<?php

if ( !class_exists ( 'DoifdFilters' ) ) {

    class DoifdFilters {


        function __construct() {
            
        }

       function doifd_settings_link($links) {
            $links[] = '<a href="'. get_admin_url(null, 'admin.php?page=doifd-admin-menu_settings') .'">Settings</a>';
            $links[] = '<a href="'. get_admin_url(null, 'admin.php?page=doifd-admin-menu_downloads') .'">Downloads</a>';
            $links[] = '<a href="'. get_admin_url(null, 'admin.php?page=doifd-admin-menu_subscribers') .'">Subscribers</a>';
            $links[] = '<a href="http://www.doubleoptinfordownload.com/premium-double-opt-in-for-download/" target="_blank">Get Premium Version</a>';
            return $links;
        }

    }

}

?>
