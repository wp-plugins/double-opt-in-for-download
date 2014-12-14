<div class = "wrap">
    <div class="doifdAdminLoader"></div>
    <?php include_once( DOIFD_DIR . 'admin/views/view-admin-header.php' ); ?>

    <div id="icon-edit-pages" class="icon32"><br></div><h2></h2>
     <div id="tabs">
        <ul>
            <li><h3><a href="#tabs-1"><?php echo apply_filters( 'doifd_subscriber_table_title', __( 'Subscribers', $this->plugin_slug ) ); ?></a></h3></li>
        </ul>
        <div id="tabs-1">
    <?php
        
    if( class_exists( 'DOIFDPremiumSubscriberTable' ) ) {
        
        $dfdSubscriberTable = new DOIFDPremiumSubscriberTable();
        
    } else {
        
        $dfdSubscriberTable = new DOIFDAdminSubscriberTable();
    }

    $dfdSubscriberTable->display_table();
    

    ?>
        </div>
     </div>

    <a href="#" id="doifdCSVModal" class='button button-primary' >Download Subscribers (CSV)</a>
    <div id="doifdCSVForm" style="display: none">
        <p><?php _e( 'Download all emails or just verfied emails.', $this->plugin_slug ); ?></p>
        <form method="post" action="" enctype="multipart/form-data">
            <select name="csv_option">
                <option value="0"><?php _e( 'All Emails', $this->plugin_slug ) ?></option>
                <option value="1"><?php _e( 'Only Verified Emails', $this->plugin_slug ) ?></option>
            </select><br />
            <br />
            <input type="checkbox" name="dupe" value="1"><?php _e( 'Remove duplicate emails.', $this->plugin_slug ) ?>
            <br />
            <br />

            <input class='button-primary' name="doifd_lab_export_csv" id="doifd_lab_export_csv" type="submit" value="<?php _e( 'Download Subscribers (csv)', $this->plugin_slug ); ?> ">

        </form>
    </div>

</div>