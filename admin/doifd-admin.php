<?php
// This function deregisters the default jquery script and registers the google scripts for jquery. I was
// unable to get ajax to work using the default script
// register and define settings
add_action ( 'admin_init' , 'doifd_lab_admin_init' ) ;

function doifd_lab_admin_init() {

    wp_register_style ( 'doifd-admin-stylesheet' , DOUBLE_OPT_IN_FOR_DOWNLOAD_URL . 'css/admin-style.css' , __FILE__ ) ;
    wp_enqueue_style ( 'doifd-admin-stylesheet' ) ;
    register_setting ( 'doifd_lab_options' , 'doifd_lab_options' , 'doifd_lab_validate_options' ) ;
    add_settings_section ( 'doifd_lab_main' , 'General Settings' , '' , 'doifd_lab' ) ;
    add_settings_field ( 'doifd_lab_downloads_allowed' , 'Select Maximum Number of Downloads' , 'doifd_lab_setting_input' , 'doifd_lab' , 'doifd_lab_main' ) ;
    add_settings_field ( 'doifd_lab_landing_page' , 'Select Landing page' , 'doifd_lab_setting_option' , 'doifd_lab' , 'doifd_lab_main' ) ;
    add_settings_field ( 'doifd_lab_add_to_wpusers' , 'Add Subcribers to the Wordpress User Table?' , 'doifd_lab_add_to_wp_user_table' , 'doifd_lab' , 'doifd_lab_main' ) ;
    add_settings_field ( 'doifd_lab_promo_link' , 'Help Us Out?<br />Add a promotional link' , 'doifd_lab_add_promo_link' , 'doifd_lab' , 'doifd_lab_main' ) ;
    add_settings_section ( 'doifd_lab_email_section' , 'Email Settings' , '' , 'doifd_lab' ) ;
    add_settings_field ( 'doifd_lab_from_email' , 'Enter The Return Email Address' , 'doifd_lab_setting_from_email' , 'doifd_lab' , 'doifd_lab_email_section' ) ;
    add_settings_field ( 'doifd_lab_email_name' , 'Enter who the email is from<br>(Default is the Website or Blog name)' , 'doifd_lab_setting_email_name' , 'doifd_lab' , 'doifd_lab_email_section' ) ;
    add_settings_field ( 'doifd_lab_email_message' , 'Email Message:' , 'doifd_lab_setting_email_message' , 'doifd_lab' , 'doifd_lab_email_section' ) ;
    add_settings_section ( 'doifd_lab_widget_style_section' , 'Widget Style Settings' , '' , 'doifd_lab' ) ;
    add_settings_field ( 'doifd_lab_widget_style_width' , 'Width of Widget' , 'doifd_lab_setting_widget_width' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    add_settings_field ( 'doifd_lab_widget_style_inside_padding' , 'Widget Inside Padding' , 'doifd_lab_setting_widget_inside_padding' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    add_settings_field ( 'doifd_lab_widget_style_margin_top' , 'Widget Margin Top' , 'doifd_lab_setting_widget_margin_top' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    add_settings_field ( 'doifd_lab_widget_style_margin_right' , 'Widget Margin Right' , 'doifd_lab_setting_widget_margin_right' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    add_settings_field ( 'doifd_lab_widget_style_margin_bottom' , 'Widget Margin Bottom' , 'doifd_lab_setting_widget_margin_bottom' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    add_settings_field ( 'doifd_lab_widget_style_margin_left' , 'Widget Margin Left' , 'doifd_lab_setting_widget_margin_left' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
    add_settings_field ( 'doifd_lab_widget_style_input_width' , 'Widget Input Width' , 'doifd_lab_setting_widget_input_width' , 'doifd_lab' , 'doifd_lab_widget_style_section' ) ;
}

add_action ( 'admin_menu' , 'register_doifd_custom_menu_page' ) ;

function register_doifd_custom_menu_page() {
    // create main menu page
    add_menu_page ( 'doifd menu title' , 'DOI - Downloads' , 'manage_options' , __FILE__ , 'doifd_lab_options_page' ) ;

    //create sub menu page for downloads
    add_submenu_page ( __FILE__ , 'Settings' , 'Settings' , 'manage_options' , __FILE__ , 'doifd_lab_options_page' ) ;

    //create sub menu page for downloads
    add_submenu_page ( __FILE__ , 'doifd downloads' , 'Downloads' , 'manage_options' , __FILE__ . '_downloads' , 'doifd_download_page' ) ;

    //create sub menu page for subscribers
    add_submenu_page ( __FILE__ , 'doifd subscribers' , 'Subscribers' , 'manage_options' , __FILE__ . '_subscribers' , 'doifd_lab_subscribers_page' ) ;

    //create sub menu page for editing downloads
    add_submenu_page ( __FILE__ , 'doifd edit downloads' , '' , 'manage_options' , __FILE__ . '_edit_downloads' , 'doifd_lab_edit_downloads_page' ) ;
}

function doifd_download_page() {

    include 'doifd-download-table.php' ;
    include DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'includes/doifd-admin-header.php' ;
    ?>
    <!--Begin HTML--> 
    <div class="wrap">
        <h2>Add a download file</h2>
        <div id="icon-edit-pages" class="icon32"></div>

        <!--HTML for upload form begins here-->

        <form method="post" action="" enctype="multipart/form-data">

            <table class="form-table">
                <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php $doifd_lab_nonce = wp_create_nonce ( 'doifd-add-download-nonce' ) ;
    echo $doifd_lab_nonce ;?>"/>
                <tr valign="top">

                    <th scope="row"><label for="name">Name of Your Download<span> *</span>: </label></th>
                    <td><input type="text" name="name" id="name" value=""/></td>

                </tr>
                <tr valign="top">

                    <th scope="row"><label for="name">Select Your File<span> *</span>: </label></th>
                    <td><input type="file" name="userfile" id="userfile"><p>( Currently, the following file types are allowed; .jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx )</p></td>


                </tr>

                <tr valigh="top">

                    <td><input class='button-primary' name="upload" type="submit" value=" Upload "></td>

                </tr>

            </table>
        </form>
        <br />
        <hr />
        <br />

        <?php
        // create an instance of subscriber table class
        $doifd_dow_table = new Doifd_Download_Table() ;

        // prepare and get download table from class and surround with <form>
        $doifd_dow_table->prepare_items () ;
        echo '<form method="get" >' ;
        echo '<input type="hidden" name="page" value="' . $_REQUEST['page'] . '" />' ;
        $doifd_dow_table->display () ;
        echo '</form>' ;
        echo '</div>' ;
    }

    function doifd_lab_edit_downloads_page() {
        ?>
        <!--Begin HTML--> 
        <div class="wrap">
            <h2>Edit Download File <?php echo $_GET['doifd_download_name'] ;?></h2>
            <div id="icon-edit-pages" class="icon32"></div>

            <!--HTML for upload form begins here-->

            <form method="post" action="" enctype="multipart/form-data">

                <table class="form-table">
                    <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php $doifd_lab_edit_download_form_nonce = wp_create_nonce ( 'doifd-edit-download-nonce' ) ; echo $doifd_lab_edit_download_form_nonce ;?>"/>
                    <input type="hidden" name="doifd_download_id" value="<?php echo $_GET['doifd_download_id'] ;?>" />
                    <input type="hidden" name="doifd_download_file_name" value="<?php echo $_GET['doifd_download_file_name'] ;?>" />
                    <tr valign="top">

                        <th scope="row"><label for="name">Rename Your File<span> *</span>: </label></th>
                        <td><input type="text" name="name" id="name" value="<?php echo $_GET['doifd_download_name'] ;?>"/></td>

                    </tr>
                    <tr valign="top">

                        <th scope="row"><label for="name">Select Your Replacement File<span> *</span>: </label></th>
                        <td><input type="file" name="userfile" id="userfile"><p>( Currently, the following file types are allowed; .jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx )</p></td>


                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="name">Reset Download Count to 0<span></span>: </label></th>
                        <td><input type="radio" id="doifd_reset_download_count" name="doifd_reset_download_count"  value="1" /> Yes
                            <input type="radio" id="doifd_reset_download_count" name="doifd_reset_download_count"  value="0" checked="checked"/> No</td>
                    </tr>

                    <tr valigh="top">

                        <td><input class='button-primary' name="update_download" type="submit" value=" Update "></td>

                    </tr>

                </table>
            </form>

            <?php
        }

        add_action ( 'admin_init' , 'doifd_edit_download' ) ;

        function doifd_edit_download() {

            global $wpdb ;

            $rb = __ ( 'Return Back' , 'Double-Opt-In-For-Download' ) ;

            if ( isset ( $_POST['update_download'] ) && ( current_user_can ( 'manage_options' ) ) ) {

                $doifd_old_file_name = $_POST['doifd_download_file_name'] ;

                // get the wpnonce from the form and verify it's coming from our form, if not, die
                $doifd_edit_download_nonce = $_POST['_wpnonce'] ;
                if ( ! wp_verify_nonce ( $doifd_edit_download_nonce , 'doifd-edit-download-nonce' ) )
                    wp_die ( 'Security check' ) ;

                $clean_doifd_reset_download_count = preg_replace ( '/[^0-9]/' , '' , $_POST['doifd_reset_download_count'] ) ;

                // clean and sanitize Download name field
                $clean_doifd_lab_name = sanitize_text_field ( $_POST['name'] ) ;

                // clean and sanitize download id
                $doifd_clean_download_id = preg_replace ( '/[^0-9]/' , '' , $_POST['doifd_download_id'] ) ;

                // assign file name to variable
                $doifd_lab_current_image = $_FILES['userfile']['name'] ;

                // if file name is empty after being sanitized show error message
                if ( empty ( $clean_doifd_lab_name ) ) {
                    $text = __ ( 'Please name your file.' , 'Double-Opt-In-For-Download' ) ;
                    echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E' , '~' , $_SERVER['REQUEST_URI'] ) . '">' . $rb . '</a></p></strong></div>' ;
                }

                //update file name
                if ( ! empty ( $clean_doifd_lab_name ) ) {

                    $wpdb->update (
                            $wpdb->prefix . 'doifd_lab_downloads' , array (
                        'doifd_download_name'=>$clean_doifd_lab_name
                            ) , array ( 'doifd_download_id'=>$doifd_clean_download_id ) , array (
                        '%s' ,
                            ) , array ( '%d' )
                    ) ;
                }

                // if they have added a new file to replace the old download file, lets do all this fun stuff
                if ( ! empty ( $doifd_lab_current_image ) ) {

                    // get file extension and assign to variable
                    $clean_doifd_lab_extension = substr ( strrchr ( $doifd_lab_current_image , '.' ) , 1 ) ;

                    // check to make sure that the file extension is one that is allowed by this plugin. If not, show error message
                    if ( ($clean_doifd_lab_extension != "jpg") && ($clean_doifd_lab_extension != "jpeg") && ($clean_doifd_lab_extension != "gif") && ($clean_doifd_lab_extension != "png") && ($clean_doifd_lab_extension != "bmp") && ($clean_doifd_lab_extension != "pdf") && ($clean_doifd_lab_extension != "zip") && ($clean_doifd_lab_extension != "doc") && ($clean_doifd_lab_extension != "docx") ) {
                        $text = __ ( 'Unknown File Type (.jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx only).' ) ;
                        echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E' , '~' , $_SERVER['REQUEST_URI'] ) . '">' . $rb . '</a></strong></p></div>' ;
                    }
                    else {

                        // rename the file to a radom file name to avoid duplicate file names
                        $doifd_lab_file_name = 'doifd_' . uniqid ( mt_rand ( 3 , 5 ) ) . '_' . time () . '.' . $clean_doifd_lab_extension ;

                        // upload file to download directory
                        $doifd_lab_upload = doifd_lab_uploadFiles ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR , $doifd_lab_file_name , $_FILES['userfile']['tmp_name'] ) ;

                        // if upload successful, update the download table and show success message
                        if ( $doifd_lab_upload == 1 ) {

                            $wpdb->update (
                                    $wpdb->prefix . 'doifd_lab_downloads' , array (
                                'doifd_download_file_name'=>$doifd_lab_file_name
                                    ) , array ( 'doifd_download_id'=>$doifd_clean_download_id ) , array (
                                '%s' ,
                                    ) , array ( '%d' )
                            ) ;

                            // delete the old download file
                            unlink ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR . $doifd_old_file_name ) ;
                        }
                    }
                }

                // reset the download count if selected
                if ( $clean_doifd_reset_download_count == '1' ) {

                    $wpdb->update (
                            $wpdb->prefix . 'doifd_lab_downloads' , array (
                        'doifd_number_of_downloads'=>0
                            ) , array ( 'doifd_download_id'=>$doifd_clean_download_id ) , array (
                        '%s' ,
                            ) , array ( '%d' )
                    ) ;
                }
                wp_redirect ( 'admin.php?page=double-opt-in-for-download/admin/doifd-admin.php_downloads' ) ;
            }
        }

// This is the contents fo the Subscribers Page

        function doifd_lab_subscribers_page() {

            global $wpdb ;

            include 'doifd-subscriber-table.php' ;
            include DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'includes/doifd-admin-header.php' ;

            // get the total count of subscribers
            $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . "doifd_lab_subscribers " ;
            $doifd_subscriber_count = $wpdb->get_var ( $sql ) ;
            ?>

            <!--HTML for subscribers section starts here-->

            <div id="icon-users" class="icon32"></div>
            <div class="wrap"> 
                <h2>Subscribers ( <?php echo $doifd_subscriber_count ;?> )</h2>

                <?php
                // create an instance of subscriber table class
                $doifd_sub_table = new Doifd_Subscriber_Table() ;

                // prepare and get subscriber table from class and surround with <form>
                $doifd_sub_table->prepare_items () ;

                echo '<form method="get" >' ;
                echo '<input type="hidden" name="page" value="' . $_REQUEST['page'] . '" />' ;
                $doifd_sub_table->display () ;
                ?>
                </form>
                <br />
                <form method="post" action="" enctype="multipart/form-data">
                    <input class='button-primary' name="doifd_lab_export_csv" type="submit" value=" Download Subscribers (csv) ">
                </form>
            </div>

            <?php
        }

// This will be the contests of the Settings or options page
        function doifd_lab_options_page() {

            global $wpdb ;

            // check to see if the user has the privileges to access the options page.
            if ( ! current_user_can ( 'manage_options' ) ) {
                wp_die ( __ ( 'You do not have sufficient permissions to access this page.' ) ) ;
            }

            include DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'includes/doifd-admin-header.php' ;
            ?>

            <!--Begin HTML markup-->
            <div class="wrap">
                <div id="icon-options-general" class="icon32"></div>
                <h2>Settings</h2>

                <!--Save Options Button-->

                <form action="options.php" method="post">
                    <?php
                    settings_fields ( 'doifd_lab_options' ) ;
                    do_settings_sections ( 'doifd_lab' ) ;
                    ?>
                    <input class='button-primary' name="Submit" type="submit" value="Save Changes">
                </form>

            </div> <!--Wrap End--> 

            <?php
        }

// set maximum number of downloads
        function doifd_lab_setting_input() {


            // get options from options table
            $options = get_option ( 'doifd_lab_options' ) ;

            // get maximum number of downloads and assign to variable
            $downloads_allowed = $options['downloads_allowed'] ;

            echo '<div id="doifd_lab_admin_options">' ;
            echo '<select name="doifd_lab_options[downloads_allowed]" id="downloads_allowed">' ;
            echo "<option value='{$options['downloads_allowed']}'>" ;
            echo esc_attr ( __ ( 'Select Maximum Downloads' ) ) ;
            echo '</option>' ;
            echo '<option value="1" ' . (($downloads_allowed == 1 ) ? 'selected="selected"' : "") . '>1</option>' ;
            echo '<option value="2" ' . (($downloads_allowed == 2 ) ? 'selected="selected"' : "") . '>2</option>' ;
            echo '<option value="3" ' . (($downloads_allowed == 3 ) ? 'selected="selected"' : "") . '>3</option>' ;
            echo '<option value="4" ' . (($downloads_allowed == 4 ) ? 'selected="selected"' : "") . '>4</option>' ;
            echo '<option value="5" ' . (($downloads_allowed == 5 ) ? 'selected="selected"' : "") . '>5</option>' ;
            echo '<option value="6" ' . (($downloads_allowed == 6 ) ? 'selected="selected"' : "") . '>6</option>' ;
            echo '<option value="7" ' . (($downloads_allowed == 7 ) ? 'selected="selected"' : "") . '>7</option>' ;
            echo '<option value="8" ' . (($downloads_allowed == 8 ) ? 'selected="selected"' : "") . '>8</option>' ;
            echo '<option value="9" ' . (($downloads_allowed == 9 ) ? 'selected="selected"' : "") . '>9</option>' ;
            echo '<option value="10" ' . (($downloads_allowed == 10 ) ? 'selected="selected"' : "") . '>10</option>' ;
            echo '</select>' ;
            _e ( '<p>Select the maximum number of times a subscriber can download an item. The default is <b>1</b>.' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

// Landing page dropdown select
        function doifd_lab_setting_option() {

            // get options from options table
            $options = get_option ( 'doifd_lab_options' ) ;

            // assign landing page option to variable
            $landing_page = $options['landing_page'] ;

            // echo dropdown
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<select name="doifd_lab_options[landing_page]" id="landing_page">' ;
            echo "<option value='{$options['landing_page']}'>" ;
            echo esc_attr ( __ ( 'Select Landing Page' ) ) ;
            echo '</option>' ;
            $pages = get_pages () ;
            foreach ( $pages as $page ) {
                $option = '<option value="' . $page->ID . '" ' . (($landing_page == $page->ID ) ? 'selected="selected"' : "") . '>' ;
                $option .= $page->post_title ;
                $option .= '</option>' ;
                echo $option ;
            }
            echo '</select>' ;
            _e ( '<p>Select the landing page for your subscribers. This will be the page your subscribers will come to after they have clicked the link in their verification email. Once you have selected your landing page, place this shortcode <b>[lab_landing_page]</b> on that page.</p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

//Add user to wordpress user table radio select
        function doifd_lab_add_to_wp_user_table() {

            // get options from options table
            $options = get_option ( 'doifd_lab_options' ) ;

            // assign add_to_wpusers option to variable
            $add_to_wp_user = $options['add_to_wpusers'] ;

            echo '<input type="radio" id="add_to_wpusers" name="doifd_lab_options[add_to_wpusers]" ' . ((isset ( $add_to_wp_user ) && ($add_to_wp_user) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ' ;
            echo '<input type="radio" id="add_to_wpusers" name="doifd_lab_options[add_to_wpusers]" ' . (isset ( $add_to_wp_user ) && ($add_to_wp_user == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ' ;
            _e ( '<p>If you want to add the subscribers to the wordress user table, check yes. Otherwise they will only be added to the plugins subscriber table.</p>' , 'Double-Opt-In-For-Download' ) ;
        }

//Add promo link to forms
        function doifd_lab_add_promo_link() {

            // get options from options table
            $options = get_option ( 'doifd_lab_options' ) ;

            // assign add_to_wpusers option to variable
            if ( isset ( $options['promo_link'] ) ) {
                $add_promo_link = $options['promo_link'] ;
            }

            echo '<input type="radio" id="promo_link" name="doifd_lab_options[promo_link]" ' . ((isset ( $add_promo_link ) && ( $add_promo_link ) == '1' ) ? 'checked="checked"' : "") . ' value="1" /> Yes ' ;
            echo '<input type="radio" id="promo_link" name="doifd_lab_options[promo_link]" ' . (isset ( $add_promo_link ) && ( $add_promo_link == '0' ) ? 'checked="checked"' : "") . ' value="0" /> No ' ;
            _e ( '<p>If you check "YES", this will add a small promotional link at the bottom of the registration forms.</p>' , 'Double-Opt-In-For-Download' ) ;
        }

//Validate User Input
        function doifd_lab_validate_options( $input ) {

            $valid = array ( ) ;
            $valid['landing_page'] = preg_replace ( '/[^0-9]/' , '' , $input['landing_page'] ) ;
            $valid['downloads_allowed'] = preg_replace ( '/[^0-9]/' , '' , $input['downloads_allowed'] ) ;
            $valid['email_name'] = preg_replace ( '/[^ \w]+/' , '' , $input['email_name'] ) ;
            $valid['from_email'] = $input['from_email'] ;
            $valid['email_message'] = $input['email_message'] ;
            $valid['add_to_wpusers'] = preg_replace ( '/[^0-9]/' , '' , $input['add_to_wpusers'] ) ;
            $valid['promo_link'] = preg_replace ( '/[^0-9]/' , '' , $input['promo_link'] ) ;
            $valid['widget_width'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_width'] ) ;
            $valid['widget_inside_padding'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_inside_padding'] ) ;
            $valid['widget_margin_top'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_margin_top'] ) ;
            $valid['widget_margin_right'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_margin_right'] ) ;
            $valid['widget_margin_bottom'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_margin_bottom'] ) ;
            $valid['widget_margin_left'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_margin_left'] ) ;
            $valid['widget_input_width'] = preg_replace ( '/[^0-9]/' , '' , $input['widget_input_width'] ) ;
            return $valid ;
        }

        function doifd_lab_setting_from_email() {

// Email options form
            // get options from options table
            $email_options = get_option ( 'doifd_lab_options' ) ;

            // get from email and assign to variable
            $from_email = $email_options['from_email'] ;

            // echo email form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[from_email]" id="from_email" value="' . $from_email . '">' ;
            _e ( '<p>This is the email address that shows in the <b>From</b> field in the verification email. If this is left blank it will default to the admin email address</p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_email_name() {

            // get options from options table
            $email_options = get_option ( 'doifd_lab_options' ) ;

            // get email name from options table and assign to variable
            $email_name = $email_options['email_name'] ;

            // show email name input field
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[email_name]" id="email_name" value="' . $email_name . '">' ;
            _e ( '<p>This is the <b>Name</b> that will show in the <b>From</b> field in the verification email. If this is left blank it will default to your website/blog name.</p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

// Email message for verification email
        function doifd_lab_setting_email_message() {

            // get options from options table
            $email_options = get_option ( 'doifd_lab_options' ) ;

            // get email message from options table and assign to variable
            $email_message = $email_options['email_message'] ;

            // show email message textarea
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<textarea rows="10" cols="60" name="doifd_lab_options[email_message]" id="email_message">' . $email_message . '</textarea>' ;
            _e ( '<p>This is the verification email that is sent to a new subscriber. Just remember, at the very least, you need to keep the <b>{URL}</b> in your email, as this provides the subscriber with the verification link. See the complete list below.<br />
                     <b>{subscriber} = Subscribers Name<br />
                     {url} = Verification Link<br />
                     {download} = The name of the download the subscriber has selected</b><br />' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_widget_width() {

// Widget Width
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_width'] ) ) {
                $widget_width = $doifd_option['widget_width'] ;
            }
            else {
                $widget_width = '190' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_width]" id="widget_width"  size="4" value="' . $widget_width . '">' ;
            _e ( '<p>This is the width of the widget in the sidebar. <b>Use numbers only, DO NOT ADD the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_widget_inside_padding() {

// Widget Inside Padding
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_inside_padding'] ) ) {
                $widget_inside_padding = $doifd_option['widget_inside_padding'] ;
            }
            else {
                $widget_inside_padding = '5' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_inside_padding]" id="widget_inside_padding" size="4" value="' . $widget_inside_padding . '">' ;
            _e ( '<p>This is the amount of padding used inside of the widget form. <b>Use numbers only, DO NOT ADD the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_widget_margin_top() {

// Widget Margin Top
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_margin_top'] ) ) {
                $widget_margin_top = $doifd_option['widget_margin_top'] ;
            }
            else {
                $widget_margin_top = '25' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_margin_top]" id="widget_margin_top" size="4" value="' . $widget_margin_top . '">' ;
            _e ( '<p>This is the top margin of the widget. <b>Use numbers only, DO NOT add the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_widget_margin_right() {

// Widget Margin Right
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_margin_right'] ) ) {
                $widget_margin_right = $doifd_option['widget_margin_right'] ;
            }
            else {
                $widget_margin_right = '0' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_margin_right]" id="widget_margin_right" size="4" value="' . $widget_margin_right . '">' ;
            _e ( '<p>This is the right margin of the widget. <b>Use numbers only, DO NOT add the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_widget_margin_bottom() {

// Widget Margin Bottom
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_margin_bottom'] ) ) {
                $widget_margin_bottom = $doifd_option['widget_margin_bottom'] ;
            }
            else {
                $widget_margin_bottom = '25' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_margin_bottom]" id="widget_margin_bottom" size="4" value="' . $widget_margin_bottom . '">' ;
            _e ( '<p>This is the bottom margin of the widget. <b>Use numbers only, DO NOT add the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }

        function doifd_lab_setting_widget_margin_left() {

// Widget Margin Left
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_margin_top'] ) ) {
                $widget_margin_left = $doifd_option['widget_margin_left'] ;
            }
            else {
                $widget_margin_left = '0' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_margin_left]" id="widget_margine_left"  size="4" value="' . $widget_margin_left . '">' ;
            _e ( '<p>This is the left margin of the widget. <b>Use numbers only, DO NOT add the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }
        
                function doifd_lab_setting_widget_input_width() {

// Widget Margin Top
            // get options from options table
            $doifd_option = get_option ( 'doifd_lab_options' ) ;

            // get widget width and assign to variable
            if ( isset ( $doifd_option['widget_input_width'] ) ) {
                $widget_input_width = $doifd_option['widget_input_width'] ;
            }
            else {
                $widget_input_width = '180' ;
            }

            // echo widget width form
            echo '<div id="doifd_lab_admin_options">' ;
            echo '<input type="text" name="doifd_lab_options[widget_input_width]" id="widget_input_width" size="4" value="' . $widget_input_width . '">' ;
            _e ( '<p>This sets the width of the input field on the widget. <b>Use numbers only, DO NOT add the px at the end.</b></p>' , 'Double-Opt-In-For-Download' ) ;
            echo '</div>' ;
        }


// This functions moves the download file to the download directory. this is used a little later
        function doifd_lab_uploadFiles( $directory , $file_name , $file_tmp_name ) {
            if ( move_uploaded_file ( $file_tmp_name , $directory . '/' . $file_name ) ) {
                // if move is successful it returns 1 or true
                return 1 ;
            }
            else {
                return 0 ;
            }
        }

// It's time to upload the download file
        add_action ( 'admin_init' , 'doifd_lab_upload_download' ) ;

        function doifd_lab_upload_download() {

            global $wpdb ;

            // assign the return back link to a variable
            $rb = __ ( 'Return Back' , 'Double-Opt-In-For-Download' ) ;

            // check to see if it's coming from the upload form and the user has the privileges to upload a file
            if ( isset ( $_POST['upload'] ) && ( current_user_can ( 'manage_options' ) ) ) {

                // get the wpnonce from the form and verify it's coming from our form, if not, die
                $doifd_lab_nonce = $_POST['_wpnonce'] ;
                if ( ! wp_verify_nonce ( $doifd_lab_nonce , 'doifd-add-download-nonce' ) )
                    wp_die ( 'Security check' ) ;

                // clean and sanitize Download name field
                $clean_doifd_lab_name = sanitize_text_field ( $_POST['name'] ) ;

                // assign file name to variable
                $doifd_lab_current_image = $_FILES['userfile']['name'] ;

                // get file extension and assign to variable
                $clean_doifd_lab_extension = substr ( strrchr ( $doifd_lab_current_image , '.' ) , 1 ) ;

                // if file name is empty after being sanitized show error message
                if ( empty ( $clean_doifd_lab_name ) ) {
                    $text = __ ( 'Please name your file.' , 'Double-Opt-In-For-Download' ) ;
                    echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E' , '~' , $_SERVER['REQUEST_URI'] ) . '">' . $rb . '</a></p></strong></div>' ;
                }
                // if the file name is empty show error message
                elseif ( empty ( $_FILES['userfile']['tmp_name'] ) ) {
                    $text = __ ( 'Please select a file to upload' , 'Double-Opt-In-For-Download' ) ;
                    echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E' , '~' , $_SERVER['REQUEST_URI'] ) . '">' . $rb . '</a></strong></p></div>' ;
                }

                // check to make sure that the file extension is one that is allowed by this plugin. If not, show error message
                elseif ( ($clean_doifd_lab_extension != "jpg") && ($clean_doifd_lab_extension != "jpeg") && ($clean_doifd_lab_extension != "gif") && ($clean_doifd_lab_extension != "png") && ($clean_doifd_lab_extension != "bmp") && ($clean_doifd_lab_extension != "pdf") && ($clean_doifd_lab_extension != "zip") && ($clean_doifd_lab_extension != "doc") && ($clean_doifd_lab_extension != "docx") ) {
                    $text = __ ( 'Unknown File Type (.jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx only).' ) ;
                    echo '<div id="message" class="error"><p><strong>' . $text . '  <a href="' . str_replace ( '%7E' , '~' , $_SERVER['REQUEST_URI'] ) . '">' . $rb . '</a></strong></p></div>' ;
                }
                else {

                    // rename the file to a radom file name to avoid duplicate file names
                    $doifd_lab_file_name = 'doifd_' . uniqid ( mt_rand ( 3 , 5 ) ) . '_' . time () . '.' . $clean_doifd_lab_extension ;

                    // upload file to download directory
                    $doifd_lab_upload = doifd_lab_uploadFiles ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DOWNLOAD_DIR , $doifd_lab_file_name , $_FILES['userfile']['tmp_name'] ) ;

                    // if upload successful insert into download table and show success message
                    if ( $doifd_lab_upload == 1 ) {

                        //put values into an array
                        $values = array (
                            'doifd_download_name'=>$clean_doifd_lab_name ,
                            'doifd_download_file_name'=>$doifd_lab_file_name ,
                            'time'=>current_time ( 'mysql' , 0 )
                                ) ;

                        $values_formats = array (
                            '%s' ,
                            '%s'
                                ) ;

                        // insert into download table
                        $wpdb->insert ( $wpdb->prefix . 'doifd_lab_downloads' , $values , $values_formats ) ;

                        // show success message
                        $text = __ ( 'File Uploaded Successfully' , 'Double-Opt-In-For-Download' ) ;
                        echo '<div class="updated"><strong>' . $text . ' <a href="' . str_replace ( '%7E' , '~' , $_SERVER['REQUEST_URI'] ) . '">' . $rb . '</a></strong></div>' ;
                    }
                    else {

                        // show error message if upload failed
                        $text = __ ( 'Error Uploading File' , 'Double-Opt-In-For-Download' ) ;
                        echo '<div class="error"><strong>' . $text . '  <a href="' . str_replace ( '%7E' , '~' , $_SERVER['REQUEST_URI'] ) . '">' . $rb . '</a></strong></div>' ;
                    }
                }
            }
        }

// Create CSV file for download
        add_action ( 'admin_init' , 'doifd_lab_create_csv_file_of_subscribers' ) ;

        function doifd_lab_create_csv_file_of_subscribers() {

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

// Function to resend the verification email
        add_action ( 'admin_init' , 'doifd_lab_resend_verification_email' ) ;

        function doifd_lab_resend_verification_email() {

            // check if it's coming from the resend verification email button and the user has privileges
            if ( isset ( $_REQUEST['name'] ) && ( $_REQUEST['name'] == 'doifd_lab_resend_verification_email' ) && ( current_user_can ( 'manage_options' ) ) ) {

                // sanitize variables from form and assign to variables
                $a = sanitize_text_field ( $_REQUEST['user_name'] ) ;
                $b = sanitize_email ( $_REQUEST['user_email'] ) ;
                $c = preg_replace ( '/[^ \w]+/' , '' , $_REQUEST['user_ver'] ) ;
                $d = preg_replace ( "/[^0-9]/" , "" , $_REQUEST['download_id'] ) ;

                // package clean variable into an array and send them to the send email function
                $send = doifd_lab_verification_email ( $value = array (
                    "user_name"=>$a ,
                    "user_email"=>$b ,
                    "user_ver"=>$c ,
                    "download_id"=>$d ) ) ;

                if ( $send === TRUE ) {
                    echo '<div class="updated"><p><strong>Email Sent To Subscriber <a href="' . str_replace ( '%7E' , '~' , $_SERVER['REQUEST_URI'] ) . '">Return Back</a></strong></p></div>' ;
                }
                else {
                    echo '<div class="error"><p><strong>A Problem Prevented the Email From Being Sent<a href="' . str_replace ( '%7E' , '~' , $_SERVER['REQUEST_URI'] ) . '">Return Back</a></strong></p></div>' ;
                }
            }
        }

        function doifd_lab_dashboard_widget_function() {

            global $wpdb ;

            // get the total count of subscribers
            $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . "doifd_lab_subscribers " ;
            $doifd_subscriber_count = $wpdb->get_var ( $sql ) ;

            // get the total count of downloads
            $sql = "SELECT SUM(doifd_number_of_downloads) FROM " . $wpdb->prefix . "doifd_lab_downloads " ;
            $doifd_download_count = $wpdb->get_var ( $sql ) ;

            // get downloads
            $sql = "SELECT * FROM " . $wpdb->prefix . "doifd_lab_downloads " ;
            $doifd_downloads_result = $wpdb->get_results ( $sql , ARRAY_A ) ;

            // display a mini download table with subscriber and download counts
            echo '<table class="doifd_admin_widget_table">' ;
            echo '<tr>' ;
            echo '<th class="doifd_admin_widget_th">Total Subscribers: ' . $doifd_subscriber_count . '</th>' ;
            echo '<th class="doifd_admin_widget_th">Overall Total Downloads: ' . $doifd_download_count . '</th>' ;
            echo '</tr>' ;
            foreach ( $doifd_downloads_result as $value ) {
                echo '<tr>' ;
                echo "<td class='doifd_admin_widget_td'>" . $value['doifd_download_name'] . "</td>" ;
                echo "<td class='doifd_admin_widget_td'>" . $value['doifd_number_of_downloads'] . "</td>" ;
                echo '</tr>' ;
            }
            echo '</table>' ;
        }

        function doifd_lab_add_dashboard_widgets() {
            wp_add_dashboard_widget ( 'doifd_dashboard_widget' , 'Double Opt-In For Downloads' , 'doifd_lab_dashboard_widget_function' ) ;
        }

// Hook into the 'wp_dashboard_setup' action to register our other functions

        add_action ( 'wp_dashboard_setup' , 'doifd_lab_add_dashboard_widgets' ) ;
        ?>