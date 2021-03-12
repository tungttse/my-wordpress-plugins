jQuery(document).ready(function($) {
    var modalPreview = jQuery("#aqpp_modal_preview");
    var iframeModalPreview = jQuery("#aqpp_modal_preview iframe");

    // show modal.
    jQuery(".preview_link_action").click(function() {
        var url = jQuery(this).data("preview_link");
        iframeModalPreview.attr('src', url);
        iframeModalPreview.css('width', "100%");
        iframeModalPreview.css('height', "95%");
        modalPreview.css("visibility", "visible");
    });

    // close when click X icon.
    jQuery("#aqpp_modal_preview .aqpp_modal__close").click(function() {
        closeModal();
    }); 

    function closeModal(){
        modalPreview.css("visibility", "hidden");
        iframeModalPreview.css('width', "0px");
        iframeModalPreview.css('height', "0px");
        iframeModalPreview.attr('src', "");
    }
});