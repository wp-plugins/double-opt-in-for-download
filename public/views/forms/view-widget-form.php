<div class="<?php if (!empty($widget_values['widget_className'])) { echo $widget_values['widget_className']; }else{ echo 'widget_doifd_user_reg_form'; }?>">
    
    <h4 class="doifd_widget_h4"><?php echo $widget_values[ 'widget_form_text' ]; ?></h4>
    
    <div id="doifd_widget_statusmsg" class="doifd_widget_statusmsg"><?php if (isset($widget_values['widget_error'])) echo $widget_values['widget_error'] ; ?></div>
    
    <form id="doifd_widget_form" action="" method="post" onsubmit="widgetgetdownload(); return false;">
    
        <input type="hidden" name="widget_download_id" id="widget_download_id" value="<?php echo $widget_values[ 'widget_id' ]; ?>"/>
        <input type="hidden" name="form_source" id="form_source" value="widget"/>
        <input type="hidden" name="widget_wpnonce" id="widget_wpnonce" value="<?php echo $widget_values['widget_nonce']; ?>"/>
        
        <ul>
        
            <li><label for="name"><?php echo $widget_values[ 'widget_name' ]; ?>: </label>
                
                <input type="text" name="doifd_widget_user_name" placeholder="<?php echo $widget_values[ 'widget_name' ]; ?>" id="doifd_widget_user_name" value=""/></li>
            
            <li><label for="name"><?php echo $widget_values[ 'widget_email' ]; ?>: </label>
                
                <input type="text" name="doifd_widget_user_email" placeholder="<?php echo $widget_values[ 'widget_email' ]; ?>" id="doifd_widget_user_email" value=""/></li>
        
        </ul>
        
        <div id="doifd_widget_button_holder">
            <input name="doifd_widget_download_form" type="submit" id="doifd_widget_download_form" value="<?php echo $widget_values[ 'widget_button_text' ]; ?>" class="button"><br />
            
            <?php echo $widget_values[ 'widget_privacy' ]; ?>
            
            <?php echo $widget_values[ 'widget_promo' ]; ?>
            
        </div>
    
    </form>

</div>
