function NooScrollTop() {
    var _top              = jQuery(window).scrollTop();
    var _fixtop           = jQuery('.fixed_top');
    var _logo_transparent = jQuery('.header-logo-transparent');

    var _pos_0 = 40;

    if ( _logo_transparent.length > 0 ) {
        _pos_0 = 120;
    }

    if( _top > _pos_0 && _fixtop.length > 0 ) {
        _fixtop.addClass('fixed-top-eff');
    } else {
        _fixtop.removeClass('fixed-top-eff');
    }
}

function FixHeight() {
    var _fixtop         = jQuery('.fixed_top');
    var _bg_transparent = jQuery('.header-background-transparent');
    var _heightHeader   = _fixtop.height();

    if( _fixtop.length > 0 && _bg_transparent.length == 0 ) {

        _fixtop.after( '<div style="height: ' + _heightHeader + 'px"></div>' );
    }
}

function FixPaddingMegaMenu() {
    var khoangcach = ( jQuery('.site').width() - 1200 ) / 2;
    
    var nmm = jQuery('.noo-main-menu').find('.noo_megamenu');
    nmm.each(function(){
        if ( ! jQuery(this).hasClass('mega-col-columns-2') && ! jQuery(this).hasClass('mega-col-columns-3') ) {
            jQuery(this).find('> .sub-menu').css('padding', '0 '+ khoangcach + 'px');
        }
    });
}

function FixMenuCanvas() {

    if ( jQuery('.noo-main-canvas').length > 0 ) {
        jQuery('.noo-main-canvas').height( jQuery(window).height() - 80 );
    }
}

// ==============
// Blog Masonry
function noo_blog_masonry(){
    "use strict";


    jQuery('.noo-blog-content').each(function(){
        var _$this = jQuery(this);
        _$this.imagesLoaded(function(){
            _$this.isotope({
                itemSelector : '.masonry-item',
                transitionDuration : '0.8s',
                masonry : {
                    'gutter' : 0
                }
            });

        });
    })
}

jQuery(document).ready(function(){
    "use strict";

    FixPaddingMegaMenu();
    FixMenuCanvas();
    FixHeight();
    NooScrollTop();

    //Navbar mobile
    jQuery('#off-canvas-nav li.menu-item-has-children').append('<i class="fa fa-angle-down"></i>');
    jQuery('#off-canvas-nav li.menu-item-has-children i').on("click", function (e) {
        var link_i = jQuery(this); //preselect the link
        link_i.prev().slideToggle(300);
        link_i.parent().toggleClass('active');
    });

    jQuery('.noo-icon-search').click(function(){
        jQuery('.search-header5').fadeIn(1).addClass('search-header-eff');
        jQuery('.search-header5').find('input[type="search"]')
            .val('')
            .attr('placeholder', '')
            .select();
        return false;
    });
    jQuery('.remove-form').click(function(){
        jQuery('.search-header5').fadeOut(1).removeClass('search-header-eff');
    });

    jQuery(document).keyup(function(e) {
         if (e.keyCode == 27) {
            jQuery('.search-header5').fadeOut(1).removeClass('search-header-eff');            
        }
    });
    
    
    jQuery('.noo-control-video').click(function(){
        var $id = jQuery(this).data('id');
        if( jQuery('.noo-lightbox-video').length > 0 ){
            jQuery('.noo-lightbox-video').remove();
        }
        var $html = '';
            $html += '<div class="noo-lightbox-video">';
                $html += '<div class="noo-iframe-video">';
                    $html += '<iframe src="https://www.youtube.com/embed/'+$id+'?autoplay=1" frameborder="0" allowfullscreen></iframe>';
                $html += '</div>';
            $html += '</div>';
        jQuery('body').append($html);

    });
    jQuery('body').on('click','.noo-lightbox-video',function(){
        jQuery('.noo-lightbox-video').remove();
    });


    // jQuery blog
    var $blog_list = jQuery('.noo-blog-content');
    if( $blog_list.length > 0 ){
        noo_blog_masonry();
        jQuery(function(){
            $blog_list.infinitescroll({
                    navSelector  : '.pagination a.page-numbers',    // selector for the paged navigation
                    nextSelector : '.pagination a.next',            // selector for the NEXT link (to page 2)
                    itemSelector : '.masonry-item',           // selector for all items you'll retrieve
                    loading: {
                        msgText:'',
                        finishedMsg: '',
                        img: nooNew.image_loading,
                        selector:'.noo-load-image'
                    }

                },
                // call Isotope as a callback
                function( newElements ) {
                    $blog_list.isotope( 'appended', newElements);
                    noo_blog_masonry();
                }

            );

        });
    }
});

jQuery(window).scroll(function(){
    "use strict";

    NooScrollTop();
});

jQuery( window ).resize(function() {
    FixPaddingMegaMenu(); 
    FixMenuCanvas(); 
});

/**
 * Event in single blog
 */
jQuery(document).ready(function($) {
    if ( $('.noo-post-nav').length > 0 ) {
        $(".noo-post-nav").on('click', '.fa-th-large', function(event) {
            event.preventDefault();
            history.back(1);
        });
    }

    if ( $('.action-like').length > 0 ) {
        $('.action-like').on('click', function(event) {
            event.preventDefault();
            /**
             * VAR
             */
            var $$           = $(this),
                post_id = $$.data( 'id' ),
                data         = {
                    action : 'like_post',
                    security : nooNew.security,
                    id : post_id
                };

            /**
             * Process
             */
            // ----- Check if user did like then not update
            if ( $$.hasClass('disable-like') ) return;

            $.post( nooL10n.ajax_url, data, function( response ) {
                $$.html( response.count );
                $$.addClass( 'disable-like' );
                $.cookie( 'like-post-' + post_id, true, { path: '/' });
            });

        }); 
    }

});