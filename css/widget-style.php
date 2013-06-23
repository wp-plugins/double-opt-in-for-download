<?php

require( '../../../../wp-blog-header.php' );

header( 'Content-type: text/css' );

$doifd_widget_option = get_option ( 'doifd_lab_options' ) ;
?>

#widget_doifd_user_reg_form {
    width: <?php if (isset($doifd_widget_option['widget_width'])) { echo $doifd_widget_option['widget_width']; }else{ echo '190'; }?>px;
    margin-top: <?php if (isset($doifd_widget_option['widget_margin_top'])) { echo $doifd_widget_option['widget_margin_top']; }else{ echo '25'; }?>px;
    margin-right: <?php if (isset($doifd_widget_option['widget_margin_right'])) { echo $doifd_widget_option['widget_margin_right']; }else{ echo '0'; }?>px;
    margin-bottom: <?php if (isset($doifd_widget_option['widget_margin_bottom'])) { echo $doifd_widget_option['widget_margin_bottom']; }else{ echo '25'; }?>px;
    margin-left: <?php if (isset($doifd_widget_option['widget_margin_left'])) { echo $doifd_widget_option['widget_margin_left']; }else{ echo '0'; }?>px;
    border: #333333 thin solid;
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    -khtml-border-radius: 10px;
    border-radius: 10px;
    padding: <?php if (isset($doifd_widget_option['widget_inside_padding'])) { echo $doifd_widget_option['widget_inside_padding']; }else{ echo '5'; }?>px ;
}

#widget_doifd_user_reg_form ul li {
    list-style-type: none;
    padding: 0px;
    margin: 0px;
}

#widget_doifd_user_reg_form input[type=text] {

    width: <?php if (isset($doifd_widget_option['widget_input_width'])) { echo $doifd_widget_option['widget_input_width']; }else{ echo '180'; }?>px;
}    

.doifd_widget_promo_link {
    font-size: 0.7em !important;
    padding-top: 15px !important;
}

.doifd_widget_promo_link a:link,
.doifd_widget_promo_link a:visited,
.doifd_widget_promo_link a:hover,
.doifd_widget_promo_link a:active {
    color: #CCCCCC !important;
}