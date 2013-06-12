<div class="wrap">

    <h2>Downloads</h2>

    <div id="icon-edit-pages" class="icon32"></div>

    <?php

    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'admin/class-admin-download-table.php' ) ;

    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'views/view-admin-header.php' ) ;

    ?>

    <!--HTML for upload form begins here-->
    <h2>Upload Your Dowload File Here</h2>
    <p>Here is where you upload your free download. You simply give your download a name, then click the browse button and select your download
        file from your computer. Once you have selected your download, press the upload button. When the page refreshes you will see your download
        in the download table below. You will also see that the shortcode is generated for you. If you choose to put the sign up form in a post or page
        just copy the shortcode and paste it into the page or post you want it to show up in. If you choose to use the widget, you will just need to select
        the download in the dropdown box when setting up your widget.</p>
    <form method="post" action="" enctype="multipart/form-data">

        <table class="form-table">

            <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php

            $doifd_lab_nonce = wp_create_nonce ( 'doifd-add-download-nonce' );
            echo $doifd_lab_nonce;

            ?>"/>

            <tr valign="top">

                <th scope="row"><label for="name">Name of Your Download<span> *</span>: </label></th>

                <td><input type="text" name="name" id="name" size="50" value=""/><p>The name you give your download file will also appear in the verification email.</p></td>
                

            </tr>

            <tr valign="top">

                <th scope="row"><label for="name">Select Your File<span> *</span>: </label></th>

                <td><input type="file" name="userfile" size="50" id="userfile"><p>( Currently, the following file types are allowed; .jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx )</p></td>

            </tr>

            <tr valigh="top">

                <td><input class='button-primary' name="upload" type="submit" value=" Upload "></td>

            </tr>

        </table>

    </form>

    <br />

    <hr />
        <h2>Downloads</h2>
        <p>You can Edit, Delete or view your download count here.</p>
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

    echo '</div>';

?>
    