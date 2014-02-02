<?php
require( '../../../../wp-blog-header.php' );

header( 'Content-type: text/css' );

$doifd_form_option = get_option ( 'doifd_lab_options' ) ;
?>

.doifd_user_reg_form {
    margin: 15px auto;
    width: <?php if (isset($doifd_form_option['form_width'])) { echo $doifd_form_option['form_width']; }else{ echo '450'; }?>px;
    padding: <?php if (isset($doifd_form_option['form_padding'])) { echo $doifd_form_option['form_padding']; }else{ echo '15'; }?>px;
    background-color: <?php if (isset($doifd_form_option['form_background_color'])) { echo $doifd_form_option['form_background_color']; }else{ echo 'transparent'; }?>;
    color: <?php if (isset($doifd_form_option['form_color'])) { echo $doifd_form_option['form_color']; }else{ echo '#000000'; }?>;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
     border-radius: 8px;
    -webkit-box-shadow: inset 0 1px 15px rgba(68,68,68,0.6);
    -moz-box-shadow: inset 0 1px 15px rgba(68,68,68,0.6);
    box-shadow: inset 0 1px 15px rgba(68,68,68,0.6);
    text-align: center;
}

.doifd_user_reg_form ul li{
    list-style: none;
    padding-top: 10px;
    margin: 0 auto;
}

.doifd_user_reg_form label {
    display: block;
    width: auto;
    float: left;
    margin: 2px 4px 6px 4px;
    text-align: right;
}

.doifd_user_reg_form input[type=text]{
    border: 1px solid #006;
    background: <?php if (isset($doifd_form_option['form_input_field_background_color'])) { echo $doifd_form_option['form_input_field_background_color']; }else{ echo 'transparent'; }?>;
    width: <?php if (isset($doifd_form_option['form_input_field_width'])) { echo $doifd_form_option['form_input_field_width']; }else{ echo '70%'; }?>;
}

.doifd_user_reg_form h4 {
    width: 80%;
    color: <?php if (isset($doifd_form_option['form_title_color'])) { echo $doifd_form_option['form_title_color']; }else{ echo '#000000'; }?>;
    margin: 15px auto!important;
    text-align: center;
    font-weight: bold;
    font-size: <?php if ( isset( $doifd_form_option[ 'form_title_size' ] ) ) {
    echo $doifd_form_option[ 'form_title_size' ];
} else {
    echo '1em';
} ?>;

}

#doifd_button_holder {
    width: 100%;
    margin: 15px auto;
    text-align: center;
}
.doifd_error_msg {
    
    font-size: .9em;
    color: #ff0000;
    font-weight: bold;
    text-align: center;
    padding: 15px 0;
}

.doifd_privacy_link {
text-align: center;
font-size: <?php if (isset($doifd_form_option['privacy_link_font_size'])) { echo $doifd_form_option['privacy_link_font_size']; }else{ echo '0.9em'; }?>;
}
