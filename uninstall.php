<?php

//If uninstall not called from Wordpress exit

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}



if (is_multisite()) {
	global $wpdb;
	$blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A); 
		
		delete_option ( 'doifd_lab_version' ) ;
                delete_option ( 'doifd_lab_options' ) ;
                delete_option ( 'doifd_lab_recaptcha_options' );
		//info: remove custom file directory for main site 
		$upload_dir = wp_upload_dir();
		$directory = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . "doifd_downloads" . DIRECTORY_SEPARATOR;
		if (is_dir($directory)) {
			foreach(glob($directory.'*.*') as $v){
				unlink($v);
			}
			rmdir($directory);
		}

	if ($blogs) {
		foreach($blogs as $blog) {
			switch_to_blog($blog['blog_id']);

			delete_option ( 'doifd_lab_version' ) ;
                        delete_option ( 'doifd_lab_options' ) ;
                        delete_option ( 'doifd_lab_recaptcha_options' );
			//info: remove custom file directory for main site 
			$upload_dir = wp_upload_dir();
			$directory = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . "doifd_downloads" . DIRECTORY_SEPARATOR;
			if (is_dir($directory)) {
				foreach(glob($directory.'*.*') as $v){
					unlink($v);
				}
				rmdir($directory);
			}
			//info: remove tables
			$GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."doifd_lab_subscribers`");
			$GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."doifd_lab_downloads`");

			restore_current_blog();
		}
	}
}
else
{

	delete_option ( 'doifd_lab_version' ) ;
        delete_option ( 'doifd_lab_options' ) ;
        delete_option ( 'doifd_lab_recaptcha_options' );
	//info: remove custom file directory for main site 
	$upload_dir = wp_upload_dir();
	$directory = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . "doifd_downloads" . DIRECTORY_SEPARATOR;
	if (is_dir($directory)) {
		foreach(glob($directory.'*.*') as $v){
			unlink($v);
		}
		rmdir($directory);
	}
	//info: remove and optimize tables
	$GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."doifd_lab_subscribers`");
        $GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."doifd_lab_downloads`");

}
?>