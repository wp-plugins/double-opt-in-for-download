<div class="<?php if (!empty($this->options['form_class'])) { echo $this->options['form_class']; }else{ echo 'doifd_user_reg_form'; }?>">

    <h4 id="h4"><?php echo $form_values['form_text']; ?></h4>
    
        <?php if (isset($form_values['error'])) echo $form_values['error'] ; ?>

    <form method="post" action="" enctype="multipart/form-data">

        <input type="hidden" name="download_id" id="download_id" value="<?php echo $form_values['id']; ?>"/>
        <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php echo $form_values['nonce']; ?>"/>

        <ul>

            <li><label for="doifd_user_name"><?php echo $form_values['name']; ?>: </label>

                <input type="text" name="doifd_user_name" id="doifd_user_name" value=""/></li>


            <li><label for="doifd_user_email"><?php echo $form_values['email']; ?>: </label>

                <input type="text" name="doifd_user_email" id="doifd_user_email" value=""/></li>

            </ul>

        <div id="doifd_button_holder">

       <input name="doifd-subscriber-registration" type="submit" value="<?php echo $form_values[ 'button_text' ]; ?>"><br /></div><br />

           <?php echo $form_values['privacy']; ?>
       
          <?php echo $form_values['promo']; ?>

    </form>
    
</div>