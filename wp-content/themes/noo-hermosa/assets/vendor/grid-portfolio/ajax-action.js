/**
 *  
 * @package    NooTheme/Noo Hermosa
 * @version    1.0.0
 * @author     Manhnv <manhnv@vietbrain.com>
 * @copyright  Copyright (c) 2018, Nootheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
*/

"use strict";
var PortfolioAjaxAction = {
    htmlTag:{
        load_more:'.load-more',
        portfolio_container: '#portfolio-'
    },
    vars:{
        ajax_url: '',
    },

    processFilter:function(elm, isLoadmore) {
        var $this              = jQuery(elm);
        var l                  = Ladda.create(elm);
        l.start();
        var $overlay_style     = $this.attr('data-overlay-style');
        var $overlay_effect    = $this.attr('data-overlay-effect');
        var $section_id        = $this.attr('data-section-id');
        var $data_source       = $this.attr('data-source');
        var $data_portfolioIds = $this.attr('data-portfolio-ids');
        var $data_show_paging  = $this.attr('data-show-paging');
        var $current_page      = $this.attr('data-current-page');
        var $category          = $this.attr('data-category');
        var $offset            = 0;
        var $post_per_page     = $this.attr('data-post-per-page');
        var $column            = $this.attr('data-column');
        var $padding           = '';
        var $order             = $this.attr('data-order');
        var $thumbnail         = $this.attr('data-thumbnail');
        var $tag               = $this.attr('data-tag');
        var $filter_by         = $this.attr('data-filter-by');
        var $hover_dir         = $this.attr('data-hover-dir');
        var $portfolio_title   = $this.attr('data-portfolio-title');

        jQuery.ajax({
            url: PortfolioAjaxAction.vars.ajax_url,
            data: ({
                action : 'nooframework_portfolio_load_more',
                postsPerPage: $post_per_page, 
                current_page: $current_page,
                thumbnail: $thumbnail,
                tag: $tag,
                hover_dir: $hover_dir,
                portfolio_title: $portfolio_title,
                category : $category,
                columns: $column, 
                colPadding: $padding, 
                offset: 0, 
                order: $order,
                data_source  : $data_source, 
                portfolioIds: $data_portfolioIds, 
                data_show_paging: $data_show_paging,
                filter_by: $filter_by,
                overlay_style: $overlay_style,
                overlay_effect: $overlay_effect,  
                data_section_id: $section_id,
            }),
            success: function(data) {
                l.stop();
                // console.log(data);
                if($data_show_paging=='1') {
                    jQuery('#load-more-' + $section_id).empty();
                    if(jQuery('.paging',data).length>0){
                        var $loadButton = jQuery('.paging a.load-more',data); // Fixed loadmore get a tags don't need
                        $loadButton.attr('data-section-id',$section_id);
                        // console.log($loadButton);
                        jQuery('#load-more-' + $section_id).append($loadButton);
                        PortfolioAjaxAction.registerLoadmore();
                    }
                }
                var $container = jQuery('#portfolio-container-' + $section_id);

                var $item = jQuery('.portfolio-item',data);


                if(isLoadmore == null || !isLoadmore) {
                    $container.isotope();
                    // jQuery('.portfolio-item',$container).each(function(){
                    //     $container.isotope( 'remove', jQuery(this) );
                    // })
                    $container.fadeOut();
                    $item.css('transition','all 0.3s');
                    $item.css('-webkit-transition','all 0.3s');
                    $item.css('-moz-transition','all 0.3s');
                    $item.css('-ms-transition','all 0.3s');
                    $item.css('-o-transition','all 0.3s');
                    $item.css('opacity',0);
                }else{
                    $item.fadeOut();
                }

                $container.append( $item ).isotope( 'appended', $item);
                var $containerIsotope = jQuery('div[data-section-id="' + $section_id + '"]');
                $containerIsotope.imagesLoaded( function() {
                    if( $hover_dir == 'on' ) {
                        jQuery('.portfolio-item > div').hoverdir('destroy');
                        jQuery('.portfolio-item > div').hoverdir('rebuild');
                    }
                    $container.isotope({ 
                    // filter: '*' // @TODO: auto filter to all, change filter to current category by comment this line
                    }); 
                });

                PortfolioAjaxAction.registerPrettyPhoto();
                // Refix padding packery
                PortfolioAjaxAction.fixPackeryPadding();

                if( $hover_dir == 'on' ) {
                    jQuery('.portfolio-item > div.entry-thumbnail').hoverdir();
                }

                $item.fadeIn();

                PortfolioAjaxAction.registerLoadmore($section_id);
            },
            error:function(){
                // Do something
            }
        });
    },

    registerLoadmore:function(sectionId) {
        jQuery('a','#load-more-' + sectionId).off(); // Remove click event
        jQuery('a','#load-more-' + sectionId).click(function() {
            PortfolioAjaxAction.processFilter(this, true);
        });
    },

    fixPackeryPadding:function() {
        if( (typeof padding_width !== 'undefined') && (padding_width != false) && (jQuery(window).width() > 767) ) { // Use padding
            var portfolio_wrapper_width = jQuery('.portfolio-wrapper').width();
            var padding_total = column * padding_width * 2; // Column from portfolio-packery.php file

            var portfolio_item_height = (portfolio_wrapper_width - padding_total) / column;
            // Small squared

            // Landscape
            jQuery('.portfolio-item.landscape').each(function() {
                jQuery(this).css({"height": portfolio_item_height});
                jQuery('img',this).css({"height": portfolio_item_height});
            });
            // Portrait
            jQuery('.portfolio-item.portrait').each(function() {
                jQuery(this).css({"height": (portfolio_item_height+padding_width) * 2 });
                jQuery('img',this).css({"height": (portfolio_item_height+padding_width) * 2 });
            });
            // Big Squared
            jQuery('.portfolio-item.big_squared').each(function() {
                jQuery(this).css({"height": (portfolio_item_height+padding_width) * 2 });
                jQuery('img',this).css({"height": (portfolio_item_height+padding_width) * 2 });
            });
        }
    },

    registerPrettyPhoto:function() {
        jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({
            hook: 'data-rel',
            theme: 'light_rounded',
            slideshow: 5000,
            deeplinking: false,
            social_tools: false
        });
    },

    wrapperContentResize:function() {
        jQuery('#wrapper-content').bind('resize', function(){
            var $container = jQuery('.portfolio-wrapper');
            $container.isotope({
                itemSelector: '.portfolio-item'
            }).isotope('layout');
        });
    },

    init:function(ajax_url, dataSectionId) {
        PortfolioAjaxAction.vars.ajax_url = ajax_url;
        PortfolioAjaxAction.registerLoadmore(dataSectionId);
        PortfolioAjaxAction.fixPackeryPadding();
        PortfolioAjaxAction.registerPrettyPhoto();
        PortfolioAjaxAction.wrapperContentResize();
    }
}