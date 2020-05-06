// File inputs
jQuery( function ( $ ) {
	$('.noo-properties-meta-box').on('click','a.insert',function(){
		$(this).closest('.noo-properties-meta-box').find('tbody').append( $(this).data( 'row' ) );
		return false;
	});
	$('.noo-properties-meta-box').on('click','a.delete',function(){
		$(this).closest('tr').remove();
		return false;
	});

    $('.noo-properties-meta-box tbody').sortable({
        items:'tr',
        cursor:'move',
        axis:'y',
        handle: 'td.sort',
        scrollSensitivity:40,
        forcePlaceholderSize: true,
        helper: 'clone',
        opacity: 0.65
    });


    $('.noo-attach-meta-box').on('click','a.insert-attach',function(){
		$(this).closest('.noo-attach-meta-box').find('tbody').append( $(this).data( 'row' ) );
		return false;
	});
	$('.noo-attach-meta-box').on('click','a.delete',function(){
		$(this).closest('tr').remove();
		return false;
	});

    $('.noo-attach-meta-box tbody').sortable({
        items:'tr',
        cursor:'move',
        axis:'y',
        handle: 'td.sort',
        scrollSensitivity:40,
        forcePlaceholderSize: true,
        helper: 'clone',
        opacity: 0.65
    });
	
});