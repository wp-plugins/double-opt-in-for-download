<?php
/*
  Plugin Name: Double Opt In For Download
  Plugin URI: http://www.labwebdesigns.com/wordpress-plugins.html
  Description: Plugin for allowing download in exchange for email address
  Author: Labwebdesigns.com / Andy Bates
  Version: 0.9
  Author URI: http://www.labwebdesigns.com
  License: GPLv3

  Double OPT-IN for download
  Copyright (C) 2013, Lab Web Designs / Andy Bates - andy@labwebdesigns.com

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/* Define URL & DIR path to plugin folder and DIR path to download folder */

$uploads = wp_upload_dir () ;

define('ALLOW_INCLUDE', true);

define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_URL' , plugin_dir_url ( __FILE__ ) ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR' , plugin_dir_path ( __FILE__ ) ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR' , $uploads['basedir'] . '/doifd_downloads/' ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_URL' , $uploads['baseurl'] . '/doifd_downloads/' ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_LANGUAGES_DIR' , plugin_dir_path ( __FILE__ ) . 'languages/' ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_URL' , plugin_dir_url ( __FILE__ ) . 'captcha/' ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_CAPTCHA_DIR' , plugin_dir_path ( __FILE__ ) . 'captcha/' ) ;
define ( 'DOUBLE_OPT_IN_FOR_DOWNLOAD_IMG_URL' , plugin_dir_url ( __FILE__ ) . 'img/' ) ;

include_once( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/includes/class-doifd.php' );

register_activation_hook ( __FILE__, array( 'DoifdInstall', 'activate_doifd_plugin' ) );


DOIFD::get_instance ();

?>
