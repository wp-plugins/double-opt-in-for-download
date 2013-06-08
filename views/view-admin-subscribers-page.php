<div class="wrap">

    <h2>Subscribers ( <?php $count = new DoifdSubscribers(); $count->get_subscriber_count (); echo $count->get_subscriber_count () ; ?> )</h2>

    <div id="icon-users" class="icon32"></div>

    <?php
    
    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'admin/class-admin-subscriber-table.php' ) ;

    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'includes/doifd-admin-header.php' ) ;

// create an instance of subscriber table class

    $doifd_sub_table = new Doifd_Subscriber_Table();

    // prepare and get subscriber table from class and surround with <form>

    $doifd_sub_table->prepare_items();

    echo '<form method="get" >';

    echo '<input type="hidden" name="page" value="' . $_REQUEST[ 'page' ] . '" />';

    $doifd_sub_table->display();

    ?>

</form>

<br />

<form method="post" action="" enctype="multipart/form-data">

    <input class='button-primary' name="doifd_lab_export_csv" type="submit" value=" Download Subscribers (csv) ">

</form>

</div>
