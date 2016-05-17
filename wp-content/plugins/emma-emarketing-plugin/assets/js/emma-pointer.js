jQuery(document).ready( function($) {
    emma_open_pointer(0);
    function emma_open_pointer(i) {
        pointer = emmaPointer.pointers[i];
        options = $.extend( pointer.options, {
            close: function() {
                $.post( ajaxurl, {
                    pointer: pointer.pointer_id,
                    action: 'dismiss-wp-pointer'
                });
            }
        });
 
        $(pointer.target).pointer( options ).pointer('open');
    }
});