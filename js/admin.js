jQuery(document).ready(function() {
    jQuery("#dialog").dialog({
      autoOpen: false,
      modal: true
    });

  jQuery(".confirm").click(function(e) {
    e.preventDefault();
    var targetUrl = jQuery(this).attr("href");

    jQuery("#dialog").dialog({
      buttons : {
        "Delete" : function() {
          window.location.href = targetUrl;
        },
        "Cancel" : function() {
          jQuery(this).dialog("close");
        }
      }
    });

    jQuery("#dialog").dialog("open");
  });
});
//jQuery('.confirm').click(function(e) {
//    
//            confirm( "Are you sure you want to delete?" );
//            
//            if (e = true ){
//                
//            }else{
//                    e.preventDefault();
//            }
//                
//    });

