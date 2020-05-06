// 
// 
jQuery(document).ready(function($){
    "use strict";
     var default_filter = [];
     var array_filter = [];
    $('.shortcode-portfolio-wrap').each(function(index, value) {
        $(this).find('.noo-filters-portfolio ul li').first().find('a').addClass('selected');
        default_filter = $(this).find('.noo-filters-portfolio ul li').first().find('a').attr('data-option-value');
        var $filter = $(this).find('.noo-filters-portfolio a');
        var $container = $(this).find('.noo-portfolio-wraper');
        array_filter[index] = $filter;        
        // //Init masonry isotope
        for( var i = 0; i < array_filter.length; i++ ) {
            if( array_filter[i].length == 0 ) {
                default_filter = '';
            }
            $container.isotope({
                itemSelector : '.noo-portfolio-item', // .item
                transitionDuration : '0.8s',
                filter: default_filter
                // filter: default_filter[i]
            });   
        };
        imagesLoaded($(this),function(){
            $container.isotope('layout');
        });

        $(window).resize(function(){
            $container.isotope('layout');
        });
        $filter.click(function(e){
            e.stopPropagation();
            e.preventDefault();

            var $this = $(this);
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
});