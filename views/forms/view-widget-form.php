<div id="widget_doifd_user_reg_form">
    
    <h4 id="widget_h4" class="widget_h4"><?php echo $header_text; ?></h4>

    <?php if ( isset ( $doifd_lab_msg ) ) echo $doifd_lab_msg; ?>
    
    <form method="post" action="" enctype="multipart/form-data">
    
        <input type="hidden" name="download_id" id="download_id" value="<?php echo $download_id; ?>"/>
        <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php echo $doifd_lab_subscriber_form_nonce ; ?>"/>
        
        <ul>
        
            <li><label for="name"><?php echo $subscriber_name; ?>: </label>
                <input type="text" name="doifd_subscriber_name" id="doifd_subscriber_name" value=""/></li>
            
            <li><label for="name"><?php echo $subscriber_email ; ?>': </label>
                <input type="text" name="doifd_subscriber_email" id="doifd_subscriber_email" value=""/></li>
        
        </ul>
        
        <div id="doifd_button_holder">
             <input name="widget_doifd-subscriber-registration" type="submit" value="<?php echo $lab_widget_form_button_text ; ?>"><br />'
            
            <?php echo $doifd_privacy_policy; ?>
            
            <?php echo $doifd_promo_link; ?>
            
        </div>
    
    </form>

</div>
