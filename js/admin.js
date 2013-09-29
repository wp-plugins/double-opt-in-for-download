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


