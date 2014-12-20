<?php
include_once( dirname(__FILE__) . '/doifd-config.php' );

/**
 * @package   Double Opt In For Download
 * @author    Andy Bates <support@doubleoptinfordownload.com>
 * @license   GPL-2.0+
 * @link      http://www.doubleoptinfordownload.com
 * @copyright 2014 LAB Web Development
 *
 * @wordpress-plugin
 * Plugin Name:       Double Opt In For Download
 * Plugin URI:        http://www.doubleoptinfordownload.com
 * Description:       Double OPT-IN For Download ( aka DOIFD ) is a Wordpress plugin that helps you capture your website visitors names and email addresses by offering them a free download in exhange for thier name and email address. Premium versions work with MailChimp, Constant Contact & AWeber.
 * Version:           2.0.2
 * Author:            LAB Web Development
 * Author URI:        http://www.doubleoptinfordownload.com
 * Text Domain:       doifd-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

if( !defined( 'WPINC' ) ) {
    die;
}

if ( DOIFD_SERVICE != '' ) {

    require_once( DOIFD_DIR . 'public/class-doifd.php' );
    require_once( DOIFD_DIR . 'premium/class-doifd-premium.php' );

    register_activation_hook( __FILE__, array( 'DOIFDPremium', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'DOIFDPremium', 'deactivate' ) );

    add_action( 'plugins_loaded', array( 'DOIFDPremium', 'get_instance' ) );
    
} else {

    require_once( DOIFD_DIR . 'public/class-doifd.php' );

    register_activation_hook( __FILE__, array( 'DOIFD', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'DOIFD', 'deactivate' ) );

    add_action( 'plugins_loaded', array( 'DOIFD', 'get_instance' ) );
    
}

/* ----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 * ---------------------------------------------------------------------------- */

if( is_admin() && (!defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {

    if ( DOIFD_SERVICE != '' ) {

        require_once( DOIFD_DIR . 'admin/class-doifd-admin.php' );
        require_once( DOIFD_DIR . 'premium/admin/class-doifd-premium-admin.php' );
        
        add_action( 'plugins_loaded', array( 'DOIFDPremiumAdmin', 'get_instance' ) );
        
    } else {
        
        require_once( DOIFD_DIR . 'admin/class-doifd-admin.php' );
        add_action( 'plugins_loaded', array( 'DOIFDAdmin', 'get_instance' ) );
        
    }
}