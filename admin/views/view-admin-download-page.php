 <?php
        if( class_exists( 'DOIFDAWeberDownloadTable' ) ) {
            $dfdDownloadTable = new DOIFDAWeberDownloadTable();
            $dfdListTable = new DOIFDAWeberListTable();
        } elseif( (class_exists( 'DOIFDMailChimpSettings' )) && (class_exists( 'DOIFDConstantContactSettings' )) ) {
            $dfdDownloadTable = new DOIFDPremiumAdminDownloadTable();
            $dfdListTable = new DOIFDPremiumAdminListTable();
            
        } elseif( (class_exists( 'DOIFDMailChimpSettings' )) && (!class_exists( 'DOIFDConstantContactSettings' )) ) {
            $dfdDownloadTable = new DOIFDMailchimpAdminDownloadTable();
            $dfdListTable = new DOIFDMailchimpAdminListTable();
        } else {
            $dfdDownloadTable = new DOIFDAdminDownloadTable();
            $dfdListTable = new DOIFDAdminListTable();
        }
?>

<div class="wrap">
    <div class="doifdAdminLoader"></div>
    <?php include_once( DOIFD_DIR . '/admin/views/view-admin-header.php' ); ?>

    <div id="tabs">
        <ul>
            <li><h3><a href="#tabs-1"><?php echo apply_filters( 'doifd_download_table_title', __( 'Downloads', 'double-opt-in-for-download' ) ); ?></a></h3></li>
            <li><h3><a href="#tabs-2"><?php echo apply_filters( 'doifd_download_table_title', __( 'Mailing Lists', 'double-opt-in-for-download' ) ); ?></a></h3></li>
            
            </li>
        </ul>
        <div id="tabs-1">
        <a href="#" id="doifdUploadModal" class='button button-primary' >Add Download File</a>

        <hr />

        <?php $dfdDownloadTable->display_table(); ?>

        </div>
        <div id="tabs-2">
        <a href="#" id="doifdListModal" class='button button-primary' >Add Mailing List</a>

        <hr />

        <?php $dfdListTable->display_table(); ?>

        </div>
    </div> 
    
<?php
    
    $dfdDownloadTable->display_doifd_upload_form();

        $dfdDownloadTable->display_download_edit_form();
        
        $dfdDownloadTable->display_doifd_list_form();
        
?>

</div>