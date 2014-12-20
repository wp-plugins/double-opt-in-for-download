<?php

$uploads = wp_upload_dir();

define( 'DOIFD_SERVICE', '', true );
define( 'DOIFD_VERSION', '2.0.2' );
define( 'DOIFD_URL', plugin_dir_url( __FILE__ ) );
define( 'DOIFD_DIR', plugin_dir_path( __FILE__ ) );
define( 'DOIFD_DOWNLOAD_DIR', $uploads[ 'basedir' ] . '/doifd_downloads/' );
define( 'DOIFD_DOWNLOAD_URL', $uploads[ 'baseurl' ] . '/doifd_downloads/' );
define( 'DOIFD_IMG_URL', plugin_dir_url( __FILE__ ) . 'public/assets/img/' );
define( 'DOIFD_ADMIN_IMG_URL', plugin_dir_url( __FILE__ ) . 'admin/assets/img/' );
