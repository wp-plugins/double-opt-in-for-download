<div class = "wrap">    

<div id="icon-users" class="icon32"><br></div><h2><?php _e( 'Subscribers' , 'double-opt-in-for-download' ); ?></h2>
        
    <?php

    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'admin/class-admin-subscriber-table.php' );

    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'views/view-admin-header.php' );

// create an instance of subscriber table class

    ?>
    <h2><?php _e ( 'Subscriber List', 'double-opt-in-for-download' ); ?> ( <?php

    $count = new DoifdSubscribers();
    $count->get_subscriber_count ();
    echo $count->get_subscriber_count ();

    ?> )</h2>
    <?php _e ( 'Here you can resend a verification email, delete a subscriber or download/export the subscriber list into a convenient csv file.', 'double-opt-in-for-download' ); ?>

    <?php

    $doifd_sub_table = new Doifd_Subscriber_Table();

    // prepare and get subscriber table from class and surround with <form>

    $doifd_sub_table->prepare_items ();

    echo '<form method="get" >';

    echo '<input type="hidden" name="page" value="' . $_REQUEST[ 'page' ] . '" />';

    $doifd_sub_table->display ();

    ?>

</form>

<br />
<hr />
<h2><?php _e ( 'Export emails to a csv file.', 'double-opt-in-for-download' ) ; ?></h2>
<p><?php _e ( 'Choose to download all emails or just verfied emails.', 'double-opt-in-for-download' ); ?></p>
<form method="post" action="" enctype="multipart/form-data">
        <select name="csv_option">
            <option value="0"><?php _e ( 'All Emails', 'double-opt-in-for-download' ) ?></option>
            <option value="1"><?php _e ( 'Only Verified Emails', 'double-opt-in-for-download' ) ?></option>
        </select>
        <br />
        <br />

        <input class='button-primary' name="doifd_lab_export_csv" type="submit" value="<?php _e ( 'Download Subscribers (csv)', 'double-opt-in-for-download' ); ?> ">

    </form>
<p><?php _e ( '** Since users can download different downloads with the same email address the csv download/export automatically weeds out duplicate emails so you do not have too.', 'double-opt-in-for-download' ); ?></p>

</div>

<div id="dialog" title="Delete Confirmation Required"><?php _e ( 'You are about to send this Subscriber into the cyber abyss! Are you sure?', 'double-opt-in-for-download' ); ?></div>
