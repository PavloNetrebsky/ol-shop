function noo_product_masonry(){
    "use strict";

    if ( jQuery('.grid-mansory').length > 0 ) {
        var $container = jQuery('.grid-mansory .noo-product-wrap-item');
        $container.imagesLoaded(function(){
            $container.isotope({
                itemSelector : '.product',
                transitionDuration : '0.8s',
                masonry : {
                    'gutter' : 0
                }
            });

        });


    }
}

jQuery(document).ready(function(){
    "use strict";
    noo_product_masonry();

    var $container = jQuery('.grid-mansory .noo-product-wrap-item');
    //Init masonry isotope
    var $filter = jQuery('.noo-filters-product a');
    $filter.click(function(e){
        e.stopPropagation();
        e.preventDefault();

        var $this = jQuery(this);
        // don't proceed if already selected
        if ($this.hasClass('selected')) {
            return false;
        }
        var filters = $this.closest('ul');
        filters.find('.selected').removeClass('selected');
        $this.addClass('selected');

        var options = {},
            key = filters.attr('data-option-key'),
            value = $this.attr('data-option-value');

        value = value === 'false' ? false : value;
        options[key] = value;

        $container.isotope(options);

    });
});
jQuery(window).resize('load resize',function(){
    "use strict";
    noo_product_masonry();
});