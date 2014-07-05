/* Delete Confirmation for subscribers and downloads */

jQuery(document).ready(function() {
    jQuery("#dialog").dialog({
        autoOpen: false,
        modal: true
    });

    jQuery(".confirm").click(function(e) {
        e.preventDefault();
        var targetUrl = jQuery(this).attr("href");

        jQuery("#dialog").dialog({
            buttons: {
                "Delete": function() {
                    window.location.href = targetUrl;
                },
                "Cancel": function() {
                    jQuery(this).dialog("close");
                }
            }
        });

        jQuery("#dialog").dialog("open");
    });
});

/* Facebook Script */

jQuery(document).ready(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id))
        return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=316169008473308";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

/* Admin Options Tabs Script */

jQuery(function() {
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

/* Hide Privacy Options if not seclected */

jQuery(document).ready(function($){
    
     $("#priv_text").hide();
     $("#priv_font_size").hide();
     $("#priv_sel_pag").hide();
    if ($("#use_privacy_policy_y:checked").length > 0){
        $("#priv_text").show();
        $("#priv_font_size").show();
        $("#priv_sel_pag").show();
    
    }

	$('#use_privacy_policy_y').click(function(){
		$('#priv_text').show();
                $("#priv_font_size").show();
                $("#priv_sel_pag").show();
	});

	$('#use_privacy_policy_n').click(function(){
		$('#priv_text').hide();
                $("#priv_font_size").hide();
                $("#priv_sel_pag").hide();
	});

});

jQuery(document).ready(function() {
jQuery('#userfile').bind('change', function() {

    var fsize = jQuery('#userfile')[0].files[0].size;
    
    // Get the PHP maximum file upload size
    var fmax = jQuery('#max_upload_size').val();
    
    // If file exceeds PHP max, show error message
    if(fsize>fmax)
        {
            jQuery( "#toolargedialog" ).dialog({
            modal: true,
            buttons: {
            Ok: function() {
            jQuery( this ).dialog( "close" );
        }
      }
      });

        }

});
});
/* Opens Support submenu link in new window */
 jQuery(document).ready( function($) {   
            $('#doifd-support').parent().attr('target','_blank');  
        });

jQuery(document).ready( function($) {   
            $('#doifd-premium').parent().attr('target','_blank');  
        });