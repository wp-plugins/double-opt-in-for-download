<div id="widget_doifd_user_reg_form">
    
    <h4 id="widget_h4" class="widget_h4"><?php echo $widget_values[ 'widget_form_text' ]; ?></h4>

    <?php if (isset($widget_values['widget_error'])) echo $widget_values['widget_error'] ; ?>
    
    <form method="post" action="" enctype="multipart/form-data">
    
        <input type="hidden" name="download_id" id="download_id" value="<?php echo $widget_values[ 'widget_id' ]; ?>"/>
        <input type="hidden" name="_wpnonce" id="_wpnonce" value="<?php echo $doifd_lab_subscriber_form_nonce ; ?>"/>
        
        <ul>
        
            <li><label for="name"><?php echo $widget_values[ 'widget_name' ]; ?>: </label>
                <input type="text" name="doifd_subscriber_name" id="doifd_subscriber_name" value=""/></li>
            
            <li><label for="name"><?php echo $widget_values[ 'widget_email' ]; ?>: </label>
                <input type="text" name="doifd_subscriber_email" id="doifd_subscriber_email" value=""/></li>
        
        </ul>
        
        <div id="doifd_widget_button_holder">
             <input name="widget_doifd-subscriber-registration" type="submit" value="<?php echo $widget_values[ 'widget_button_text' ]; ?>"></div><br />
            
            <?php echo $widget_values[ 'widget_privacy' ]; ?>
            
            <?php echo $widget_values[ 'widget_promo' ]; ?>
    
    </form>

</div>
