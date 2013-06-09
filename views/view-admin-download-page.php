<div class="wrap">

    <h2>Add a download file</h2>

    <div id="icon-edit-pages" class="icon32"></div>

    <?php

    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'admin/class-admin-download-table.php' ) ;

    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'views/view-admin-header.php' ) ;

    ?>

    <!--HTML for upload form begins here-->

    <form method="post" action="" enctype="multipart/form-data">

        <table class="form-table">

            <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php

            $doifd_lab_nonce = wp_create_nonce ( 'doifd-add-download-nonce' );
            echo $doifd_lab_nonce;

            ?>"/>

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

    $doifd_dow_table = new Doifd_Download_Table();

    // prepare and get download table from class and surround with <form>

    $doifd_dow_table->prepare_items ();

    echo '<form method="get" >';

    echo '<input type="hidden" name="page" value="' . $_REQUEST[ 'page' ] . '" />';

    $doifd_dow_table->display ();

    echo '</form>';

    echo '</div>';

?>
    