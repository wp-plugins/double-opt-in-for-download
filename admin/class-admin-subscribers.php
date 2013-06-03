<?php

class DoifdSubscribers {

    public function __construct() {
        
    }

    public static function admin_subscriber_page() {

        global $wpdb;

        // get the total count of subscribers

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . "doifd_lab_subscribers ";

        $doifd_subscriber_count = $wpdb->get_var($sql);
        ?>

        <!--HTML for subscribers section starts here-->

        <div class="wrap">

            <h2>Subscribers ( <?php echo $doifd_subscriber_count; ?> )</h2>

            <div id="icon-users" class="icon32"></div>

            <?php
            include 'class-admin-subscriber-table.php';

            include DOUBLE_OPT_IN_FOR_DOWNLOAD_DIR . 'includes/doifd-admin-header.php';

// create an instance of subscriber table class

            $doifd_sub_table = new Doifd_Subscriber_Table();

            // prepare and get subscriber table from class and surround with <form>

            $doifd_sub_table->prepare_items();

            echo '<form method="get" >';

            echo '<input type="hidden" name="page" value="' . $_REQUEST['page'] . '" />';

            $doifd_sub_table->display();
            ?>

        </form>

        <br />

        <form method="post" action="" enctype="multipart/form-data">

            <input class='button-primary' name="doifd_lab_export_csv" type="submit" value=" Download Subscribers (csv) ">

        </form>

        </div>

        <?php
    }

}
?>
