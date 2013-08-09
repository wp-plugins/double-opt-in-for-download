<div class="<?php if (!empty($options['form_class'])) { echo $options['form_class']; }else{ echo 'doifd_user_reg_form'; }?>">

    <h4 id="h4"><?php echo $doifd_form_text; ?></h4>
    
        <?php if (isset($msg)) echo $msg ; ?>

    <form method="post" action="" enctype="multipart/form-data">

        <input type="hidden" name="download_id" id="download_id" value="<?php echo $download_id; ?>"/>
        <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php echo $doifd_lab_user_form_nonce ; ?>"/>

        <ul>

            <li><label for="doifd_user_name"><?php echo $subscriber_name; ?>: </label>

                <input type="text" name="doifd_user_name" id="doifd_user_name" value=""/></li>


            <li><label for="doifd_user_email"><?php echo $subscriber_email; ?>: </label>

                <input type="text" name="doifd_user_email" id="doifd_user_email" value=""/></li>

            </ul>

        <div id="doifd_button_holder">

       <input name="doifd-subscriber-registration" type="submit" value="<?php echo $doifd_form_button_text ; ?>"><br /></div><br />

        <?php echo $doifd_promo_link; ?>

    </form>
    
</div>