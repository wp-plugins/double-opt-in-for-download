<div class="wrap">

    <h2>Subscribers ( <?php $count = new DoifdSubscribers();
$count->get_subscriber_count ();
echo $count->get_subscriber_count (); ?> )</h2>

    <div id="icon-users" class="icon32"></div>

    <?php

    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'admin/class-admin-subscriber-table.php' );

    include( DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'views/view-admin-header.php' );

// create an instance of subscriber table class

    ?>
    <h2>Subscriber List ( <?php $count = new DoifdSubscribers();
    $count->get_subscriber_count ();
    echo $count->get_subscriber_count (); ?> )</h2>
    Here you can resend a verification email, delete a subscriber or download/export the subscriber list into a convenient csv file.

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

<form method="post" action="" enctype="multipart/form-data">

    <input class='button-primary' name="doifd_lab_export_csv" type="submit" value=" Download Subscribers (csv) ">

</form>
<p>** Since users can download different downloads with the same email address the csv download/export automatically weeds out duplicate emails so you do not have too.</p>
</div>

<div id="dialog" title="Delete Confirmation Required">You are about to send this Subscriber into the cyber abyss! Are you sure?</div>
