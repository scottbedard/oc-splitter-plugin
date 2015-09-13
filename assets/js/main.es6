$(function() {

    // Trigger an event when elements with a data-splitter-success attribute
    // are clicked or submitted.
    $(document).on('click', '[data-splitter-id]', function() {
        $.event.trigger('bedard.splitter', $(this).data('splitter-id'));
    });

    // Catch success events, and inform October that they happened
    $(document).on('bedard.splitter', function(e, id) {
        $.request('splitter::onSuccess', {
            data: {
                id: id,
            },
        });
    });
});
