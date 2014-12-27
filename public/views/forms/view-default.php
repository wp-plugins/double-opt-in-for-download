<div class="<?php if (!empty($this->doifd_options['form_class'])) { echo $this->doifd_options['form_class']; }else{ echo 'doifd_user_reg_form'; }?>">

    <h4 id="h4"><?php echo $this->form_values['form_text']; ?></h4>
    
    <div id="doifd_statusmsg" class="statusmsg"><?php if (isset($this->form_values['error'])) echo $this->form_values['error'] ; ?></div>

    <form id="doifd_form" action="<?php echo  $_SERVER['REQUEST_URI']; ?>" method="POST" onsubmit="getdownload(); return false;">

        <input type="hidden" name="download_id" id="download_id" value="<?php echo $this->form_values['id']; ?>"/>
        <input type="hidden" name="form_source" id="form_source" value="form"/>
        <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php echo $this->form_values['nonce']; ?>"/>

        <ul>

            <li>
                <label for="doifd_user_name"><?php echo $this->form_values['name']; ?>: </label>

                <input type="text" name="doifd_user_name" placeholder="<?php echo $this->form_values['name']; ?>" id="doifd_user_name" value=""/></li>

            <li>
                <label for="doifd_user_email"><?php echo $this->form_values['email']; ?>: </label>

                <input type="text" name="doifd_user_email" placeholder="<?php echo $this->form_values['email']; ?>" id="doifd_user_email" value=""/></li>

            </ul>

        <div id="doifd_button_holder">

            <input name="doifd_download_form" type="submit" id="doifd_download_form" value="<?php echo $this->form_values[ 'button_text' ]; ?>" class="button"><br />
        
        </div>

        <?php echo $this->form_values['privacy']; ?>
        
        <?php echo $this->form_values['promo']; ?>

    </form>
</div>