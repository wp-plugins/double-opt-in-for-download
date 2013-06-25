<div class="wrap">
    
    <h2><?php _e( 'Downloads' , 'double-opt-in-for-download' ); ?></h2>

    <div id="icon-edit-pages" class="icon32"></div>

    <?php
    
    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'views/view-admin-header.php' ) ;

    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'admin/class-admin-download-table.php' ) ;

    ?>

    <!--HTML for upload form begins here-->
    
    <h2><?php _e( 'Upload Your Dowload File Here', 'double-opt-in-for-download' ); ?></h2>
    <p><?php _e( 'Here is where you upload your free download. You simply give your download a name, then click the browse button and select your download file from your computer. Once you have selected your download, press the upload button. When the page refreshes you will see your download in the download table below. You will also see that the shortcode is generated for you. If you choose to put the sign up form in a post or page just copy the shortcode and paste it into the page or post you want it to show up in. If you choose to use the widget, you will just need to select the download in the dropdown box when setting up your widget.' , 'double-opt-in-for-download' ); ?></p>
 
    <form method="post" action="" enctype="multipart/form-data">

        <table class="form-table">

            <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php

            $doifd_lab_nonce = wp_create_nonce ( 'doifd-add-download-nonce' );
            echo $doifd_lab_nonce;

            ?>"/>

            <tr valign="top">

                <th scope="row"><label for="name"><?php _e( 'Name of Your Download', 'double-opt-in-for-download' ); ?><span> *</span>: </label></th>

                <td><input type="text" name="name" id="name" size="50" value=""/><p><?php _e( 'The name you give your download file will also appear in the verification email.' , 'double-opt-in-for-download' ); ?></p></td>
                

            </tr>

            <tr valign="top">

                <th scope="row"><label for="name"><?php _e( 'Select Your File' ,'double-opt-in-for-download' ); ?><span> *</span>: </label></th>

                <td><input type="file" name="userfile" size="50" id="userfile"><p><?php _e( '( Currently, the following file types are allowed; .jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx, ,mp3 )' , 'double-opt-in-for-download' ); ?></p></td>

            </tr>

            <tr valigh="top">

                <td><input class='button-primary' name="upload" type="submit" value="<?php _e('Upload', 'double-opt-in-for-download' ); ?>"></td>

            </tr>

        </table>

    </form>

    <br />

    <hr />
        <h2><?php _e( 'Downloads' , 'double-opt-in-for-download' ); ?></h2>
        <p><?php _e( 'You can edit, delete or view your download count here.' , 'double-opt-in-for-download' ) ; ?></p>
    <br />

    <?php

    // create an instance of subscriber table class

    $doifd_dow_table = new Doifd_Download_Table();

    // prepare and get download table from class and surround with <form>

    $doifd_dow_table->prepare_items ();

    echo '<form method="get" >';

    echo '<input type="hidden" name="page" value="' . $_REQUEST[ 'page' ] . '" />';

    $doifd_dow_table->display ();

    echo '</form>';

?>

   
<div id="dialog" title="Delete Confirmation Required"><?php _e( 'You are about to send this download into the cyber abyss! Are you sure?' , 'double-opt-in-for-download' ) ; ?></div>
  
</div>