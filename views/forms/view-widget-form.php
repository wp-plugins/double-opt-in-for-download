<div id="widget_doifd_user_reg_form">
    
    <?php if ( isset ( $doifd_lab_msg ) ) echo $doifd_lab_msg; ?>
    
    <h4 id="widget_h4" class="widget_h4"><?php echo $header_text; ?></h4>
    
    <div id="widget_statusmsg" class="statusmsg"></div>
    
    <div id="widget_waiting">Please wait...<br />
    
        <img src="<?php echo DOUBLE_OPT_IN_FOR_DOWNLOAD_URL . 'img/ajax-loader.gif' ; ?>" title="Loader" alt="Loader" />
     
    </div>
    
    <form id="doifd_widget_form" onsubmit="widgetgetdownload(); return false;">
    
        <input type="hidden" name="widget_download_id" id="widget_download_id" value="<?php echo $download_id; ?>"/>
        
        <ul>
        
            <li><label for="name"><?php echo $subscriber_name; ?>: </label>
                <input type="text" name="doifd_widget_user_name" id="doifd_widget_user_name" value=""/></li>
            
            <li><label for="name"><?php echo $subscriber_email ; ?>': </label>
                <input type="text" name="doifd_widget_user_email" id="doifd_widget_user_email" value=""/></li>
        
        </ul>

        <?php if(isset($doifd_widget_captcha) && $doifd_widget_captcha == TRUE ) include_once ( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . '/views/forms/view-recaptcha-widget.php' ); ?>
        
        <div id="doifd_button_holder">
            <input  name="submit" type="submit" id="submit" value="<?php echo $lab_widget_form_button_text; ?>" class="button"><br />
            <?php echo $doifd_promo_link; ?>
        </div>
    
    </form>

</div>
