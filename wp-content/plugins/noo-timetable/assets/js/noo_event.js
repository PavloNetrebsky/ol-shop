jQuery(document).ready(function($) {
    "use strict";

    // Class filters
    var grop_class_filters ={};
    if($('.widget-event-filter').length && ($('.noo-event-shortcode.grid').length || $('.noo-event-shortcode.list').length)){
        var class_filters = $('.widget-event-filter');
        var masonrycontainer = class_filters.closest('body').find('.noo-event-shortcode .posts-loop-content');
        if(masonrycontainer.length){
            var getFilterStr = function ($filter_item) {
                class_filters.find(':input').each( function() {
                    var $filter_item = $(this);
                    var group = $filter_item.closest('.widget-event-filter').data('group');
                    var filterGroup = grop_class_filters[ group ];
                    if(!filterGroup)
                        filterGroup = grop_class_filters[ group ] = [];

                    if($filter_item.is('select')){
                        if($filter_item.val()==''){
                            delete grop_class_filters[ group ];
                        }else{
                            grop_class_filters[ group ] = '.'+$filter_item.val();
                        }
                    }else if($filter_item.is('input[type="checkbox"]')){
                        grop_class_filters[ group ] = [];
                        $filter_item.closest('.widget-event-filter').find('.widget-event-filter-control').each(function(){
                            if($(this).is(':checked')){
                                grop_class_filters[ group ].push('.'+$(this).val());
                            }
                        });
                    }
                });

                var filter_arr = [];
                var filter_arr2 = [];
                var filter_string = '';
                $.each(grop_class_filters,function(index,values){
                    if($.isArray(values)){
                        filter_arr2 = values;
                    }else{
                        filter_arr.push(values);
                    }
                });
                filter_arr = filter_arr.join('');
                var new_filter_arr=[];
                if(filter_arr2.length){
                    $.each(filter_arr2,function(k2,v2){
                        new_filter_arr.push((v2 + '' + filter_arr));
                    });
                }else{
                    new_filter_arr.push(filter_arr);
                }
                if(new_filter_arr.length){
                    filter_string = new_filter_arr.join(',');
                }else{
                    filter_string = '*';
                }
                if(filter_string == ''){
                    filter_string = '*';
                }

                return filter_string;
            };
            masonrycontainer.isotope();            
            class_filters.find('.widget-event-filter-control').on('change', function () {
                var filter_string = getFilterStr();
                var options = {
                    layoutMode : 'masonry',
                    transitionDuration : '0.8s',
                    'masonry' : {
                        'gutter' : 0
                    }
                }
                options['filter'] = filter_string;
                masonrycontainer.isotope(options);
            });
            
            imagesLoaded(masonrycontainer,function(){
                var filter_string = getFilterStr();
                var options = {
                    layoutMode : 'masonry',
                    transitionDuration : '0.8s',
                    'masonry' : {
                        'gutter' : 0
                    }
                }
                // if( filter_string != '' && filter_string !='*' ) {
                    options['filter'] = filter_string;
                    masonrycontainer.isotope(options);
                // }
            });
        }
    }

    /**
     * Process event when clicking grid/list button on shop page
     */
    $('.noo-button-header-shop').on('click', 'i', function(event) {
        event.preventDefault();
        /**
         * VAR
         */
        var $$      = $(this),
            id      = $$.data( 'id' );

        /**
         * Process
         */
        $( '.noo-button-header-shop i' ).removeClass( 'active' );
        $$.addClass( 'active' );


        if ( id === 'grid' ) {
            if ( $( '.products' ).hasClass('shop-list') ) {
                $( '.products' ).removeClass('shop-list').addClass('shop-grid');
            }
        } else if ( id === 'list' ) {
            if ( $( '.products' ).hasClass('shop-grid') ) {
                $( '.products' ).removeClass('shop-grid').addClass('shop-list');
            }
        }
    });

    /**
     * Process slider single product
     */
    // if ( $('body').hasClass( 'single-product' ) ) {
        
    //     /**
    //      * Call library Carousel
    //      */
    //         $( '.noo-image-big-wrap' ).owlCarousel({

    //             navigation : true,
    //             navigationText : ["<i class='ion-ios-arrow-left'></i>","<i class='ion-ios-arrow-right'></i>"],
    //             slideSpeed : 600,
    //             pagination: false,
    //             paginationSpeed : 400,
    //             addClassActive: true,
    //             singleItem : true,
    //             autoHeight: true

    //         });


    //     /**
    //      * Process event and get index number when clicking item
    //      */
    //         $( '.noo-image-slider-wrap' ).on('click', '.noo-thumbnail-small', function(event) {
    //             event.preventDefault();
    //             /**
    //              * VAR
    //              */
    //             var $$     = $(this),
    //                 index  = $$.index();

    //             /**
    //              * Process
    //              */
    //                 // ----- Remove all 'active' classes  of div tag
    //                     $( '.noo-thumbnails .noo-thumbnail-small' ).removeClass( 'active' );

    //                 // ---- Add 'active' classes when clicking it
    //                     $$.addClass( 'active' );

    //                 // ----- Attach index number to slider and it runs
    //                     $( '.noo-image-big-wrap' ).trigger( "owl.goTo", index );

    //         });

    //     /**
    //      * Process event when clicking button next/prev to slider
    //      */
    //         $( '.owl-next, .owl-prev' ).click(function(event) {
    //             // ----- Remove all 'active' classes of div tag
    //                 $( '.noo-thumbnails .noo-thumbnail-small' ).removeClass( 'active' );

    //             // ----- Get index number item now
    //                 var index = $( '.owl-item' ).index( $( '.active' ) );
                
    //             // ----- Attach index number to images slider
    //                 $( '.noo-thumbnail-small' ).eq(index).addClass( 'active' );

    //         });

    // }

    /**
     * Event widget: Noo Event Slider
     */
    if ( $('.noo-event-slider-wrap').length > 0 ) {
        $('.noo-event-slider-wrap').each(function(index, el) {
            /**
             * VAR
             */
            var $__this = $(this);

            /**
             * Process
             */
            $__this.owlCarousel({

                loop:true,
                margin:10,
                navigation:false,
                pagination: true,
                dots: false,
                autoplay: true,
                autoHeight: true,
                autoplayTimeout:5000,
                autoplayHoverPause:false,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:1
                    },
                    1000:{
                        items:1,
                        dotsEach: 2,
                    }
                }


            });

        });
    }

    /**
     * Process event when clicking grid/list button on shop page
     */
    $('.noo-event-button').on('click', 'i', function(event) {
        event.preventDefault();
        /**
         * VAR
         */
        var $$      = $(this),
            id      = $$.data( 'id' );

        /**
         * Process
         */
        $( '.noo-event-button i' ).removeClass( 'active' );
        $$.addClass( 'active' );


        if ( id === 'grid' ) {
            if ( $( '.archive-noo-event-wrap' ).hasClass('event-list') ) {
                $( '.archive-noo-event-wrap' ).removeClass('event-list').addClass('event-grid');
            }
        } else if ( id === 'list' ) {
            if ( $( '.archive-noo-event-wrap' ).hasClass('event-grid') ) {
                $( '.archive-noo-event-wrap' ).removeClass('event-grid').addClass('event-list');
            }
        }
    });

    /**
     * Live search event
     */
    if ( $('.noo-event-filter-wrap').length > 0 ) {

        $('.noo-event-filter-wrap').each(function(index, el) {
            
            /**
             * VAR
             */
            var __this     = $(this),
                __search   = __this.find('.filter-keyword'),
                __address  = __this.find('.filter-address'),
                __calendar = __this.find('.filter-calendar'),
                __cat      = __this.find('.filter-category'),
                __layout   = $('.noo-event-button').find('i.active').data('id');

            var logic = function( dp,$input ){
                
                /**
                 * VAR
                 */
                var date     = $input.val(),
                    id_cat   = __cat.val(),
                    keyword  = __search.val(),
                    address  = __address.val(),
                    __layout = $('.noo-event-button').find('i.active').data('id'),
                    data     = {
                        action: 'load_event',
                        date: date,
                        address: address,
                        cat: id_cat,
                        keyword: keyword,
                        layout: __layout
                    };

                /**
                 * Process
                 */
                if ( date !== '' ) {
                    $('.archive-noo-event-item-wrap').addClass('hide-item');
                    $.post(nooEvent.ajax_url, data, function( data ) {
                        /**
                         * Update source
                         */
                            $('.archive-noo-event-head').remove();
                            $('.event-wrap').html(data);
                            $('.archive-noo-event-item-wrap').addClass('zoomIn');

                        /**
                         * Update count event
                         */
                            // $( '.noo-event-text span' ).html( $('.archive-noo-event-item').length );
                    });

                }
            };

                    
            __calendar.datetimepicker({
                format:"m/d/Y",
                timepicker: false,
                datepicker: true,
                scrollInput: false,
                onSelectDate:logic
            });

            /**
             * Process
             */
            __search.on( "keyup", function( event ) {
                
                // check if enter
                    if ( event.which !== 13 ) return;

                /**
                 * VAR
                 */
                    var keyword     = $(this).val(),
                        id_cat      = __cat.val(),
                        date        = __calendar.val(),
                        address     = __address.val(),
                        __layout    = $('.noo-event-button').find('i.active').data('id'),
                        data        = {
                            action: 'load_event',
                            keyword: keyword,
                            address: address,
                            date: date,
                            cat: id_cat,
                            layout: __layout
                        };
                /**
                 * Process
                 */
                    $('.archive-noo-event-item-wrap').addClass('hide-item');
                    $.post(nooEvent.ajax_url, data, function( data ) {
                        $('.event-wrap').html(data);
                        $('.archive-noo-event-item-wrap').addClass('zoomIn');
                    });
            });

            /**
             * Process
             */
            __address.on( "keyup", function( event ) {
                
                // check if enter
                    if ( event.which !== 13 ) return;

                /**
                 * VAR
                 */
                    var address     = $(this).val(),
                        id_cat      = __cat.val(),
                        date        = __calendar.val(),
                        keyword     = __search.val(),
                        __layout    = $('.noo-event-button').find('i.active').data('id'),
                        data        = {
                            action: 'load_event',
                            address: address,
                            keyword: keyword,
                            date: date,
                            cat: id_cat,
                            layout: __layout
                        };

                    

                /**
                 * Process
                 */
                    $('.archive-noo-event-item-wrap').addClass('hide-item');
                    $.post(nooEvent.ajax_url, data, function( data ) {
                        $('.event-wrap').html(data);
                        $('.archive-noo-event-item-wrap').addClass('zoomIn');
                    });
            });

            __cat.change(function(event) {

                /**
                 * VAR
                 */
                var id_cat     = $(this).val(),
                    date       = __calendar.val(),
                    keyword    = __search.val(),
                    address     = __address.val(),
                    __layout   = $('.noo-event-button').find('i.active').data('id'),
                    data       = {
                        action: 'load_event',
                        cat: id_cat,
                        address: address,
                        date: date,
                        keyword: keyword,
                        layout: __layout
                    };

                /**
                 * Process
                 */
                
                $('.archive-noo-event-item-wrap').addClass('hide-item');
                $.post(nooEvent.ajax_url, data, function( data ) {
                    /**
                     * Update source
                     */
                        $('.archive-noo-event-head').remove();
                        $('.event-wrap').html(data);
                        $('.archive-noo-event-item-wrap').addClass('zoomIn');

                });
            });

        });

    }

    /**
     * Event Grid Mansory
     */
    // $('.mansonry').each(function() {
    //     /**
    //      * VAR
    //      */
    //     var $__this  = $(this),
    //         _wrap    = $__this.find('.archive-noo-event-wrap');

    //     /**
    //      * Process
    //      */
    //     _wrap.isotope({
    //         itemSelector : '.archive-noo-event-item-wrap',
    //         transitionDuration : '0.8s',
    //         masonry : {
    //             'gutter' : 0
    //         }
    //     });
        
    // });
    
    /**
     * Remove colpan table
     */
        $(".noo-event-calendar-wrap td.fc-event-container").removeAttr('colspan rowspan');

        $('.fc-next-button, .fc-prev-button').click(function(event) {
            $(".noo-event-calendar-wrap td.fc-event-container").removeAttr('colspan rowspan');
        });

    /**
     * Process calendar mobile
     */
        $('.noo-responsive-calendar-wrap').each(function(index, el) {
           /**
            * VAR
            */
                var $$          = $(this);
            /**
             * Process
             */
                $$.on('click', '.res-calendar-navigation a', function(event) {
                    event.preventDefault();
                    /**
                     * VAR
                     */
                        var __this      = $(this),
                            from        = __this.data('from'),
                            to          = __this.data('to'),
                            prev_from   = $$.find('.prev-from-hidden').val(),
                            prev_to     = $$.find('.prev-to-hidden').val(),
                            next_from   = $$.find('.next-from-hidden').val(),
                            next_to     = $$.find('.next-to-hidden').val(),
                            label_start = $$.find('.label-start').val(),
                            label_end   = $$.find('.label-end').val(),
                            data        = {
                                action: 'calendar_mobile',
                                security: nooEvent.security,
                                from: from,
                                to: to
                            };

                    /**
                     * Process
                     */
                        $.post(nooEvent.ajax_url, data, function( response ) {
                            /**
                             * Update source code
                             */
                                $$.html(response);

                        });

                });

        });

});