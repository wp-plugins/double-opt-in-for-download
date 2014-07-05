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
<h2><?php _e ( 'Export Emails', 'double-opt-in-for-download' ) ; ?></h2>
<p><?php _e( 'Download all emails or just verfied emails.', 'double-opt-in-for-download' ); ?></p>
<form method="post" action="" enctype="multipart/form-data">
        <select name="csv_option">
            <option value="0"><?php _e ( 'All Emails', 'double-opt-in-for-download' ) ?></option>
            <option value="1"><?php _e ( 'Only Verified Emails', 'double-opt-in-for-download' ) ?></option>
        </select>
        <br />
        <br />
        <input type="checkbox" name="dupe" value="1"><?php _e( 'Remove duplicate emails.', 'double-opt-in-for-download' ) ?>
        <br />
        <br />

        <input class='button-primary' name="doifd_lab_export_csv" type="submit" value="<?php _e ( 'Download Subscribers (csv)', 'double-opt-in-for-download' ); ?> ">

    </form>
<p><?php _e( 'Export automatically weeds out duplicate emails for you.', 'double-opt-in-for-download' ); ?></p>

</div>

<div id="dialog" title="Delete Confirmation Required"><?php _e ( 'You are about to send this Subscriber into the cyber abyss! Are you sure?', 'double-opt-in-for-download' ); ?></div>
