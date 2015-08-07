<?php ob_start(); ?>
<?php if( version_compare( phpversion(), '5.4.19', '>' ) ) : ?>
    <?php
    $absolute_path = explode( 'wp-content', $_SERVER[ 'SCRIPT_FILENAME' ] );
    $wp_load = $absolute_path[ 0 ] . 'wp-load.php';

    header( 'Content-type: text/css' );

    ?>

    <?php
    require_once($wp_load);
    $doifd_widget_option = get_option( 'doifd_lab_options' );

    ?>
<?php else : ?>

    <?php
    $absolute_path = explode( 'wp-content', $_SERVER[ 'SCRIPT_FILENAME' ] );
    $wp_load = $absolute_path[ 0 ] . 'wp-load.php';
    require_once($wp_load);

    header( 'Content-type: text/css' );

    $doifd_widget_option = get_option( 'doifd_lab_options' );

    ?>
<?php endif; ?>

.widget_doifd_user_reg_form {
width: <?php if( isset( $doifd_widget_option[ 'widget_width' ] ) ) {
    echo $doifd_widget_option[ 'widget_width' ];
} else {
    echo '190';
} ?>px;
margin-top: <?php if( isset( $doifd_widget_option[ 'widget_margin_top' ] ) ) {
    echo $doifd_widget_option[ 'widget_margin_top' ];
} else {
    echo '25';
} ?>px;
margin-right: <?php if( isset( $doifd_widget_option[ 'widget_margin_right' ] ) ) {
    echo $doifd_widget_option[ 'widget_margin_right' ];
} else {
    echo '0';
} ?>px;
margin-bottom: <?php if( isset( $doifd_widget_option[ 'widget_margin_bottom' ] ) ) {
    echo $doifd_widget_option[ 'widget_margin_bottom' ];
} else {
    echo '25';
} ?>px;
margin-left: <?php if( isset( $doifd_widget_option[ 'widget_margin_left' ] ) ) {
    echo $doifd_widget_option[ 'widget_margin_left' ];
} else {
    echo '0';
} ?>px;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
border-radius: 4px;
-webkit-box-shadow: inset 0 1px 15px rgba(68,68,68,0.6);
-moz-box-shadow: inset 0 1px 15px rgba(68,68,68,0.6);
box-shadow: inset 0 1px 15px rgba(68,68,68,0.6);
padding: <?php if( isset( $doifd_widget_option[ 'widget_inside_padding' ] ) ) {
    echo $doifd_widget_option[ 'widget_inside_padding' ];
} else {
    echo '5';
} ?>px ;
background-color: <?php if( isset( $doifd_widget_option[ 'widget_background_color' ] ) ) {
    echo $doifd_widget_option[ 'widget_background_color' ];
} else {
    echo 'transparent';
} ?>;
}

.widget_doifd_user_reg_form label {
color: <?php if( isset( $doifd_widget_option[ 'widget_color' ] ) ) {
    echo $doifd_widget_option[ 'widget_color' ];
} else {
    echo '#000000';
} ?>;
}

.widget_doifd_user_reg_form ul li {
list-style-type: none;
padding: 0px;
margin: 0px;
}

.widget_doifd_user_reg_form input[type=text] {

width: <?php if( isset( $doifd_widget_option[ 'widget_input_width' ] ) ) {
    echo $doifd_widget_option[ 'widget_input_width' ];
} else {
    echo '180';
} ?>px;
background: <?php if( isset( $doifd_widget_option[ 'widget_input_field_background_color' ] ) ) {
    echo $doifd_widget_option[ 'widget_input_field_background_color' ];
} else {
    echo 'transparent';
} ?>;
margin-bottom: 10px;
}    

.doifd_widget_promo_link {
font-size: 0.7em !important;
padding-top: 15px !important;
text-align: center;
}

.doifd_widget_promo_link a:link,
.doifd_widget_promo_link a:visited,
.doifd_widget_promo_link a:hover,
.doifd_widget_promo_link a:active {
color: #CCCCCC !important;
}

.doifd_widget_statusmsg {

margin-bottom: 20px;
width: 100%;
font-size: 1em;
text-align: center;

}

.doifd_widget_waiting {
color: #767676;
text-align: center;
display: none;
font-family : Arial, sans-serif;
font-size:0.8em;
font-weight: bold;
margin: 100px auto;
background: transparent;
}

.widget_h4 {
margin: 5px auto;
text-align: center;
width: 90%;
color: <?php
if( isset( $doifd_widget_option[ 'widget_title_color' ] ) ) {
    echo $doifd_widget_option[ 'widget_title_color' ];
} else {
    echo '#000000';
}

?>;
font-size: <?php
if( isset( $doifd_widget_option[ 'widget_title_size' ] ) ) {
    echo $doifd_widget_option[ 'widget_title_size' ];
} else {
    echo '1em';
}

?>;
}

.doifd_privacy_link {
text-align: center;
margin: 10px 0;
font-size: <?php if( isset( $doifd_widget_option[ 'privacy_link_font_size' ] ) ) {
    echo $doifd_widget_option[ 'privacy_link_font_size' ];
} else {
    echo '0.9em';
} ?>;
}