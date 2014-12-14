//jQuery(document).ready(function($) {
//    var custom_uploader;
//
//    $('.media-button').click(function(e) {
//        e.preventDefault();
//        formfieldID = jQuery(this).next().attr("id");
//        formfield = jQuery("#" + formfieldID).attr('name');
//        //If the uploader object has already been created, reopen the dialog
//        if (custom_uploader) {
//            custom_uploader.open();
//            return;
//        }
//
//        //Extend the wp.media object
//        custom_uploader = wp.media.frames.file_frame = wp.media({
//            title: 'Choose Image',
//            button: {
//                text: 'Choose Image'
//            },
//            multiple: false
//        });
//
//        //When a file is selected, grab the URL and set it as the text field's value
//        custom_uploader.on('select', function() {
//            attachment = custom_uploader.state().get('selection').first().toJSON();
//            jQuery("#" + formfieldID).val(attachment.url);
//        });
//
//        //Open the uploader dialog
//        custom_uploader.open();
//    });
//});

/* ---------------------------- */
/* Admin Options Tabs Script */
/* ---------------------------- */

jQuery(document).ready(function() {
    jQuery("#tabs").tabs({
        beforeLoad: function(event, ui) {
            ui.jqXHR.error(function() {
                ui.panel.html(
                        "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                        "If this wouldn't be a demo.");
            });
        }
    });
});

/*
 *  Hide Privacy Options if not seclected
 */

jQuery(document).ready(function() {

    jQuery("#priv_text").hide();
    jQuery("#priv_font_size").hide();
    jQuery("#priv_sel_pag").hide();
    if (jQuery("#use_privacy_policy_y:checked").length > 0) {
        jQuery("#priv_text").show();
        jQuery("#priv_font_size").show();
        jQuery("#priv_sel_pag").show();

    }

    jQuery('#use_privacy_policy_y').click(function() {
        jQuery('#priv_text').show();
        jQuery("#priv_font_size").show();
        jQuery("#priv_sel_pag").show();
    });

    jQuery('#use_privacy_policy_n').click(function() {
        jQuery('#priv_text').hide();
        jQuery("#priv_font_size").hide();
        jQuery("#priv_sel_pag").hide();
    });

});

/*
 * Check the file size
 */

jQuery(document).ready(function() {
    
    jQuery('#userfile').bind('change', function() {

        var fsize = jQuery('#userfile')[0].files[0].size;

        // Get the PHP maximum file upload size
        var fmax = jQuery('#max_upload_size').val();

        // If file exceeds PHP max, show error message
        if (fsize > fmax)
        {
            fileSizeNotice();
            jQuery('#userfile').val('')
        }

    });
});

// Notice at the bottom left with an offset
function fileSizeNotice() {
    new jBox('Notice', {
        content: ajaxupload.filetoolarge, /* Get Content From wp_localize */
        color: 'red',
        attributes: {
            x: 'left',
            y: 'top'
        },
        position: {// The position attribute defines the distance to the window edges
            x: 175,
            y: 75
        }
    });
}


    
// Validate Upload Form

jQuery(document).ready(function() {

    jQuery('#doifd_admin_download_form').validate({
            errorElement: 'span',
            errorClass: 'uploadError',
            rules: {
                download_name: {
                    required: true,
                    minlength: 4
                },
                doifd_download_landing_page: {
                    required: true
                },
                listMailchimpID: {
                    required: true
                },
                listConstantContactID: {
                    required: true
                },
                userfile: {
                    required: true
                }
            },
            messages: {
                download_name: {
                    required: "Please name your download",
                    minlength: "Name has to be at least 4 characters"
                },
                doifd_download_landing_page: {
                    required: "Please select a landing page"
                },
                listMailchimpID: {
                    required: "Please select a Mailchimp list"
                },
                listConstantContactID: {
                    required: "Please select a Constant Contact list"
                },
                userfile: {
                    required: "Please select a file to upload"
                }
            },

            submitHandler: function(form) {
                    form.submit();
                }
        });
        
});

// Validate Edit Upload Form

jQuery(document).ready(function() {

    jQuery('#doifd_admin_edit_download_form').validate({
            errorElement: 'span',
            errorClass: 'uploadError',
            rules: {
                doifd_edit_name: {
                    required: true,
                    minlength: 4
                },
                doifd_download_edit_landing_page: {
                    required: true
                },
                listMailchimpID: {
                    required: true
                },
                listConstantContactID: {
                    required: true
                },
            },
            messages: {
                doifd_edit_name: {
                    required: "Please name your download",
                    minlength: "Name has to be at least 4 characters"
                },
                doifd_download_edit_landing_page: {
                    required: "Please select a landing page"
                },
                listMailchimpID: {
                    required: "Please select a Mailchimp list"
                },
                listConstantContactID: {
                    required: "Please select a Constant Contact list"
                },
            },

            submitHandler: function(form) {
                    form.submit();
                }
        });
        
});

/*
 * Jbox Upload Form
 */
jQuery(document).ready(function() {

    jQuery('#doifdListModal').jBox('Modal', {
        title: 'Add New Mailing List',
        content: jQuery('#doifdListForm'),
        closeButton: 'title',
        overlay: true,
        onClose: function() {
            var validator = jQuery("#doifd_admin_list_form").validate();
            validator.resetForm();
                
            }
    });
});

/*
 * Jbox Upload Form
 */
jQuery(document).ready(function() {

    jQuery('#doifdUploadModal').jBox('Modal', {
        title: 'Add New Download',
        content: jQuery('#doifdUploadForm'),
        closeButton: 'title',
        overlay: true,
        onClose: function() {
            var validator = jQuery("#doifd_admin_download_form").validate();
            validator.resetForm();
                
            }
    });
});

/*
 * Edit Download Form
 */

jQuery(document).ready(function() {

    jQuery('a.doifdEditUploadModal').click(function() {

        var id = jQuery(this).attr("id");

        var downloadID = {
            action: 'populate_download_edit_form',
            id: id
        };

        jQuery.ajax({url: ajaxurl,
            data: downloadID,
            dataType: 'json',
            type: 'post',
            success: function(data) {
                
                jQuery("#doifd_download_id").val(data.doifd_download_id);
                jQuery("#doifd_edit_name").val(data.doifd_download_name);
                jQuery("#doifd_download_name").val(data.doifd_download_name);
                jQuery("#doifd_download_file_name").val(data.doifd_download_file_name);
                jQuery("#doifd_download_edit_landing_page").val(data.doifd_download_landing_page);
                jQuery("#doifd_edit_tumessage").val(data.doifd_download_tumessage);

                if (jQuery('#listMailchimpID').length)
                {

                    jQuery('#listMailchimpID option').prop('selected', false)
                            .filter('[value="' + data.doifd_download_mailchimp_list_id + '"]')
                            .prop('selected', true);
                    jQuery('input[name=listMailchimpName]').val(data.doifd_download_mailchimp_list_name)
                }
                
                if (jQuery('#listConstantContactID').length)
                {
                    /* Needed to remove the http:// from the retrieved data so it would populate correctly */
                    var ccURL = data.doifd_download_constant_contact_list_id.replace("http://","");
                    
                    jQuery('#listConstantContactID option').prop('selected', false)
                            .filter('[value="' + ccURL + '"]')
                            .prop('selected', true);
                    jQuery('input[name=listConstantContactName]').val(data.doifd_download_constant_contact_list_name)
                }
                 if (jQuery('#listAWeberID').length)
                {

                    jQuery('#listAWeberID option').prop('selected', false)
                            .filter('[value="' + data.doifd_download_aweber_list_id + '"]')
                            .prop('selected', true);
                    jQuery('input[name=listAWeberName]').val(data.doifd_download_aweber_list_name)
                }


            }

        });

        var doifdEditModal = new jBox('Modal', {
            title: 'Edit Download ',
            closeButton: 'title',
            content: jQuery('#doifdEditUploadForm'),
            overlay: true,
            onClose: function() {
            
                var validator = jQuery("#doifd_admin_edit_download_form").validate();
                validator.resetForm();
                
            }
        });

        doifdEditModal.open();


    });
    
     
});

/*
 * CSV Form
 */

jQuery(document).ready(function() {

    jQuery('#doifdCSVModal').jBox('Modal', {
        title: 'Download Subscribers',
        content: jQuery('#doifdCSVForm'),
        closeButton: 'title',
        overlay: true
    });
    
});

/*
 * jBox Tooltip for Allowed File Types
 */

jQuery(document).ready(function() {
    jQuery('.uploadallowedfiletypes').jBox('Tooltip', {
        id: 'jBoxUploadAllowed',
        trigger: 'click',
        target: jQuery('.uploadallowedfiletypes'),
        getTitle: 'data-jbox-title',
        getContent: 'data-jbox-content',
        offset: {x: 50, y: -70},
        pointer: 'bottom:185',
        theme: 'TooltipBorder'
    });
});

jQuery(document).ready(function() {
    jQuery('.uploadfilesizelimit').jBox('Tooltip', {
        id: 'jBoxUploadLimit',
        trigger: 'click',
        target: jQuery('.uploadfilesizelimit'),
        getTitle: 'data-jbox-title',
        getContent: 'data-jbox-content',
        offset: {x: 140, y: -70},
        pointer: 'bottom',
        theme: 'TooltipBorder',
    });
});

jQuery(document).ready(function() {
    jQuery('.editallowedfiletypes').jBox('Tooltip', {
        id: 'jBoxEditAllowed',
        trigger: 'click',
        target: jQuery('.editallowedfiletypes'),
        getTitle: 'data-jbox-title',
        getContent: 'data-jbox-content',
        offset: {x: 40, y: -70},
        pointer: 'bottom:200',
        theme: 'TooltipBorder'
    });
});

jQuery(document).ready(function() {
    jQuery('.editfilesizelimit').jBox('Tooltip', {
        id: 'jBoxEditLimit',
        trigger: 'click',
        target: jQuery('.editfilesizelimit'),
        getTitle: 'data-jbox-title',
        getContent: 'data-jbox-content',
        offset: {x: 140, y: -70},
        pointer: 'bottom',
        theme: 'TooltipBorder',
    });
});

/*
 * jBox Delete Confirmation
 */

jQuery(document).ready(function() {

    new jBox('Confirm', {
        confirmButton: 'OK!',
        cancelButton: 'Cancel'
    });
});


var myModal;

jQuery(document).ready(function() {

    myModal = new jBox('Modal', {
        title: 'Edit Download',
        closeButton: 'title',
        overlay: true
    });

});

function openModal() {
    myModal.open().ajax({
        url: ajaxupload.editdownloadform,
        reload: true
    });
}

jQuery(document).ready(function() {
    jQuery('#listMailchimpID').live('change', function() {

        jQuery('input[name="listMailchimpName"]').val(this.options[this.selectedIndex].text);

    });
});

jQuery(document).ready(function() {
    jQuery('#listConstantContactID').live('change', function() {

        jQuery('input[name="listConstantContactName"]').val(this.options[this.selectedIndex].text);

    });
});

jQuery(document).ready(function() {
    jQuery('#listAWeberID').live('change', function() {

        jQuery('input[name="listAWeberName"]').val(this.options[this.selectedIndex].text);

    });
});

/* Facebook Script */

jQuery(document).ready(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

jQuery(window).load(function() {
	jQuery(".doifdAdminLoader").fadeOut("slow");
})

/* Opens Support submenu link in new window */
 jQuery(document).ready( function($) {   
            $('#doifd-support').parent().attr('target','_blank');  
        });

jQuery(document).ready( function($) {   
            $('#doifd-premium').parent().attr('target','_blank');  
        });