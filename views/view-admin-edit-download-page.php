<div class = "wrap">

<h2>Edit Download File <?php echo $_GET[ 'doifd_download_name' ] ; ?></h2>

<div id="icon-edit-pages" class="icon32"></div>

<form method="post" action="" enctype="multipart/form-data">

    <table class="form-table">

        <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php

               $doifd_lab_edit_download_form_nonce = wp_create_nonce ( 'doifd-edit-download-nonce' );
               
               echo $doifd_lab_edit_download_form_nonce;

               ?>"/>

        <input type="hidden" name="doifd_download_id" value="<?php echo $_GET[ 'doifd_download_id' ]; ?>" />

        <input type="hidden" name="doifd_download_file_name" value="<?php echo $_GET[ 'doifd_download_file_name' ]; ?>" />

        <tr valign="top">


            <th scope="row"><label for="name">Rename Your File<span> *</span>: </label></th>

            <td><input type="text" name="name" id="name" value="<?php echo $_GET[ 'doifd_download_name' ]; ?>"/></td>

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
