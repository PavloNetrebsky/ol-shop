jQuery(document).on('click', '.noo_upload_button', function(event) {
    var $clicked = jQuery(this), frame,
        input_id = $clicked.prev().attr('id'),
        media_type = $clicked.attr('rel');

    event.preventDefault();

    // If the media frame already exists, reopen it.
    if ( frame ) {
        frame.open();
        return;
    }

    // Create the media frame.
    frame = wp.media.frames.frame = wp.media({
        // Set the media type
        library: {
            type: media_type
        },
        view: {

        }
    });


    // When an image is selected, run a callback.
    frame.on( 'select', function() {
        // Grab the selected attachment.
        var attachment = frame.state().get('selection').first();

        jQuery('#' + input_id).val(attachment.attributes.url);

        if(media_type == 'image') jQuery('#' + input_id).parent().parent().parent().find('.screenshot img').attr('src', attachment.attributes.url);

    });

    frame.open();

});