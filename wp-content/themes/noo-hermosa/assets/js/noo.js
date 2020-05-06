/*!
 * NOO Site Script.
 *
 * Javascript used in NOO-Framework
 * This file contains base script used on the frontend of NOO theme.
 *
 * @package    NOO Framework
 * @subpackage NOO Site
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================


;(function($){
	"use strict";	
	var nooGetViewport = function() {
	    var e = window, a = 'inner';
	    if (!('innerWidth' in window )) {
	        a = 'client';
	        e = document.documentElement || document.body;
	    }
	    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
	};
	
	$.fn.nooLoadmore = function(options,callback){
		var defaults = {
				contentSelector: null,
				contentWrapper:null,
				nextSelector: "div.navigation a:first",
				navSelector: "div.navigation",
				itemSelector: "div.post",
				dataType: 'html',
				finishedMsg: "<em>Congratulations, you've reached the end of the internet.</em>",
				loading:{
					speed:'fast',
					start: undefined
				},
				state: {
			        isDuringAjax: false,
			        isInvalidPage: false,
			        isDestroyed: false,
			        isDone: false, // For when it goes all the way through the archive.
				    isPaused: false,
				    isBeyondMaxPage: false,
				    currPage: 1
				}
		};
		var options = $.extend(defaults, options);
		
		return this.each(function(){
			var self = this;
			var $this = $(this),
				wrapper = $this.find('.loadmore-wrap'),
				action = $this.find('.loadmore-action'),
				btn = action.find(".btn-loadmore"),
				loading = action.find('.loadmore-loading');
			
			options.contentWrapper = options.contentWrapper || wrapper;
			
			
				
			var _determinepath = function(path){
				if (path.match(/^(.*?)\b2\b(.*?$)/)) {
	                path = path.match(/^(.*?)\b2\b(.*?$)/).slice(1);
	            } else if (path.match(/^(.*?)2(.*?$)/)) {
	                if (path.match(/^(.*?page=)2(\/.*|$)/)) {
	                    path = path.match(/^(.*?page=)2(\/.*|$)/).slice(1);
	                    return path;
	                }
	                path = path.match(/^(.*?)2(.*?$)/).slice(1);
	
	            } else {
	                if (path.match(/^(.*?page=)1(\/.*|$)/)) {
	                    path = path.match(/^(.*?page=)1(\/.*|$)/).slice(1);
	                    return path;
	                } else {
	                	options.state.isInvalidPage = true;
	                }
	            }
				return path;
			}
			if(!$(options.nextSelector).length){
				return;
			}
			
			
			// callback loading
			options.callback = function(data, url) {
	            if (callback) {
	                callback.call($(options.contentSelector)[0], data, options, url);
	            }
	        };
	        
	        options.loading.start = options.loading.start || function() {
				 	btn.hide();
	                $(options.navSelector).hide();
	                loading.show(options.loading.speed, $.proxy(function() {
	                	loadAjax(options);
	                }, self));
	         };
			
			var loadAjax = function(options){
				var path = $(options.nextSelector).attr('href');
					path = _determinepath(path);
				
				var callback=options.callback,
					desturl,frag,box,children,data;
				
				options.state.currPage++;
				// Manually control maximum page
	            if ( options.maxPage !== undefined && options.state.currPage > options.maxPage ){
	            	options.state.isBeyondMaxPage = true;
	                return;
	            }
	            desturl = path.join(options.state.currPage);
	            box = $('<div/>');
	            box.load(desturl + ' ' + options.itemSelector,undefined,function(responseText){
	            	children = box.children();
	            	if (children.length === 0) {
	            		//loading.hide();
	            		btn.hide();
	            		action.append('<div style="margin-top:5px;">' + options.finishedMsg + '</div>').animate({ opacity: 1 }, 2000, function () {
	            			action.fadeOut(options.loading.speed);
	                    });
	                    return ;
	                }
	            	frag = document.createDocumentFragment();
	                while (box[0].firstChild) {
	                    frag.appendChild(box[0].firstChild);
	                }
	                $(options.contentWrapper)[0].appendChild(frag);
	                data = children.get();
	                loading.hide();
	                btn.show(options.loading.speed);
	                options.callback(data);
	               
	            });
			}
			
			
			btn.on('click',function(e){
				 e.stopPropagation();
				 e.preventDefault();
				 options.loading.start.call($(options.contentWrapper)[0],options);
			});
		});
	};
	
	$.fn.nooSmartSidebar = function(options){
		var defaults = {
				_main_content: $('.noo-main')
			};
		var options = $.extend(defaults, options);
		options._main_content = $(options._main_content);
		if(!options._main_content.length)
			return false;
		
		var isTouch = function(){
			return !!('ontouchstart' in window) || ( !! ('onmsgesturechange' in window) && !! window.navigator.maxTouchPoints);
		}
		
		if(isTouch())
			return;
		
		return this.each(function(){
			var _this = $(this),
				_scroll_top_last,
				_case = '',
				_this_sidebar_with = _this.width(),
				isWebkit = (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1);
			var _get_view_port = function() {
			    var e = window, a = 'inner';
			    if (!('innerWidth' in window )) {
			        a = 'client';
			        e = document.documentElement || document.body;
			    }
			    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
			};
			var _is_smaller_or_equal = function(b, c) {
		       return 1 <= Math.abs(b - c) ? b < c ? true : false : true
		    };
		    var _is_smaller = function(b, c) {
		    	return 1 <= Math.abs(b - c) ? b < c ? true : false : false
		    };
			var _run = function(){
				if(!_this.data('sidebar_is_enabled'))
					return;
				
				var _w_top = $(window).scrollTop(),
					_w_height = _get_view_port().height,
					_navbar_height = 0,
					_dir = '';
				var $navbar = $( '.navbar' );
				if ( $navbar.hasClass( 'fixed-top' ) ) {
					_navbar_height = 0;
				}
				var adminbarHeight = 0;
				// if ( $('body').hasClass( 'admin-bar' ) ) {
				// 	adminbarHeight = $( '#wpadminbar' ).outerHeight();
				// }
				_navbar_height += adminbarHeight;
				
				_w_top != _scroll_top_last && (_dir = _w_top > _scroll_top_last ? "down" : "up"); 
				_scroll_top_last = _w_top;
				
				_w_top += _navbar_height;
				var	_this_content_top = options._main_content.offset().top,
					_this_sidebar_height2 = options._main_content.outerHeight(),
					_this_content_height = _this_sidebar_height2,
					_this_content_bottom = _this_content_top + _this_sidebar_height2,
					_this_sidebar_top = _this.offset().top,
					_this_sidebar_height = _this.outerHeight(),
					_this_sidebar_bottom = _this_sidebar_top + _this_sidebar_height;
				if(options._main_content.css('margin-top') == '-175px'){
					_this_content_top += 175;
				}
				if(_this_content_height <= _this_sidebar_height)
					_case = '6';
				else if (_this_sidebar_height < _w_height) _is_smaller_or_equal(_w_top, _this_content_top) ? _case = "2" : 
						true === _is_smaller(_this_sidebar_bottom, _w_top) ? _is_smaller(_w_top, (_this_content_bottom - _this_sidebar_height)) ? _case = "4" : _case = "3" :
							_is_smaller_or_equal(_this_content_bottom, _this_sidebar_bottom) ? "up" == _dir && _is_smaller_or_equal(_w_top,_this_sidebar_top) ? _case = "4" : _case = "3" : 
								_case = _this_content_bottom - _w_top >= _this_sidebar_height ? "4" : "3";
				else if (true === _is_smaller(_this_sidebar_bottom, _w_top) ? _case = "3" : 
					true === _is_smaller(_this_sidebar_bottom, (_w_top + _w_height)) && true === _is_smaller(_this_sidebar_bottom, _this_content_bottom) && "down" == _dir && _this_content_bottom >= (_w_top + _w_height) ? _case = "1" : 
						true === _is_smaller_or_equal(_this_sidebar_top, _this_content_top) && "up" == _dir && _this_content_bottom >= (_w_top + _w_height) ? _case = "2" : 
							true === _is_smaller_or_equal(_this_content_bottom, _this_sidebar_bottom) && "down" == _dir || _this_content_bottom < (_w_top + _w_height) ? _case = "3" : 
								true === _is_smaller_or_equal(_w_top, _this_sidebar_top) && "up" == _dir && true === _is_smaller_or_equal(_this_content_top, _w_top) && (_case = "4"), "1" == _case  && "up" == _dir || "4" == _case && "down" == _dir) _case = "5";
				var _fix_width = Math.max(_this.data('fix-width'),200);

				switch(_case){
					case '1':
						 if (1 === _this.data('s-case-1')) break;
						 _this.data('s-case-1',1);
						 _this.data('s-case-2',0);
						 _this.data('s-case-3',0);
						 _this.data('s-case-4',0);
						 _this.data('s-case-5',0);
						 _this.data('s-case-6',0);
                         _this.css({
                             width: _fix_width,
                             position: "fixed",
                             top: "auto",
                             bottom: "0",
                             "z-index": "1"
                         });
                         break;
					case '2':
						 if (1 === _this.data('s-case-2')) break;
						 _this.data('s-case-1',0);
						 _this.data('s-case-2',1);
						 _this.data('s-case-3',0);
						 _this.data('s-case-4',0);
						 _this.data('s-case-5',0);
						 _this.data('s-case-6',0);
                         _this.css({
                             width: "auto",
                             position: "static",
                             top: "auto",
                             bottom: "auto",
                         });
						break;
					case '3':
						if (1 === _this.data('s-case-3') && _this.data('last-sidebar-height') == _this_sidebar_height && _this.data('last-content-height') == _this_sidebar_height2) break;
						 _this.data('s-case-1',0);
						 _this.data('s-case-2',0);
						 _this.data('s-case-3',1);
						 _this.data('last-sidebar-height',_this_sidebar_height);
						 _this.data('last-content-height',_this_sidebar_height2);
						 _this.data('s-case-4',0);
						 _this.data('s-case-5',0);
						 _this.data('s-case-6',0);
                        _this.css({
                            width: _fix_width,
                            position: "absolute",
                            top: _this_content_bottom - _this_sidebar_height - _this_content_top,
                            bottom: "auto"
                        });
						break;
					case '4':
						 if (1 === _this.data('s-case-4') && _this.data('last-menu-offset') == _navbar_height) break;
						 _this.data('s-case-1',0);
						 _this.data('s-case-2',0);
						 _this.data('s-case-3',0);
						 _this.data('s-case-4',1);
						 _this.data('last-menu-offset',_navbar_height);
						 _this.data('s-case-5',0);
						 _this.data('s-case-6',0);
                        _this.css({
                            width: _fix_width,
                            position: "fixed",
                            top: _navbar_height,
                            bottom: "auto"
                        });
						break;
					case '5':
						 if (1 === _this.data('s-case-5')) break;
						 _this.data('s-case-1',0);
						 _this.data('s-case-2',0);
						 _this.data('s-case-3',0);
						 _this.data('s-case-4',0);
						 _this.data('s-case-5',1);
						 _this.data('s-case-6',0);
                         _this.css({
                             width: _fix_width,
                             position: "absolute",
                             top: _this_sidebar_top - _this_content_top,
                             bottom: "auto",
                         });
						break;
					case '6':
						 if (1 === _this.data('s-case-6')) break;
						 _this.data('s-case-1',0);
						 _this.data('s-case-2',0);
						 _this.data('s-case-3',0);
						 _this.data('s-case-4',0);
						 _this.data('s-case-5',0);
						 _this.data('s-case-6',1);
                         _this.css({
                             width: "auto",
                             position: "static",
                             top: "auto",
                             bottom: "auto",
                         });
						break;
				}
			};
			var _resize = function(){
				var _w_with = 0;
				_w_with = _get_view_port().width;
				if(_w_with >= 992 ){
					_this.data('fix-width',_this.parent().width());
					_this.data('sidebar_is_enabled',1);
					_run();
				}else{
					 _this.data('fix-width',0);
					 _this.data('sidebar_is_enabled',0);
					 _this.data('s-case-1',0);
					 _this.data('s-case-2',0);
					 _this.data('s-case-3',0);
					 _this.data('s-case-4',0);
					 _this.data('s-case-5',0);
					 _this.data('s-case-6',0);
					 _this.data('last-menu-offset',0);
					 _this.data('last-sidebar-height',0);
					 _this.data('last-content-height',0)
					 _this.css({
                         width: "auto",
                         position: "static",
                         top: "auto",
                         bottom: "auto",
                     });
				}
			};
			
			$(window).bind('resize',function(){
				_resize();
			});
			$(window).scroll(function(){
				if (window.requestAnimationFrame) {
					window.requestAnimationFrame(function() {
						_run();
				        }, document);
				}else{
					_run();
				}
			});
			setTimeout(function(){
				_resize();
			},100);
			return true;
		});
	};
	

	$('.wpb_column.noovc-smart-content').each(function(){
		var _this = $(this);
		_this.parent().find(".noovc-smart-sidebar").nooSmartSidebar({
			_main_content: _this
		});
	});

	var nooGetURLParameters = function(url) {
	    var result = {};
	    var searchIndex = url.indexOf("?");
	    if (searchIndex == -1 ) return result;
	    var sPageURL = url.substring(searchIndex +1);
	    var sURLVariables = sPageURL.split('&');
	    for (var i = 0; i < sURLVariables.length; i++)
	    {       
	        var sParameterName = sURLVariables[i].split('=');      
	        result[sParameterName[0]] = sParameterName[1];
	    }
	    return result;
	};
	var nooInit = function() {
		
		if($( '.navbar' ).length){
			var $window = $( window );
			var $body   = $( 'body' ) ;
			var navTop = $( '.navbar' ).offset().top;
			var adminbarHeight = 0;
			// if ( $body.hasClass( 'admin-bar' ) ) {
			// 	adminbarHeight = $( '#wpadminbar' ).outerHeight();
			// }
			var lastScrollTop = 0,
				navHeight = 0,
				defaultnavHeight = 70;
			
			var navbarInit = function () {
				if(nooGetViewport().width > 992){
					var $this = $( window );
					var $navbar = $( '.navbar' ),
					navHeight = $navbar.outerHeight();
					if ( $navbar.hasClass( 'fixed-top' ) ) {
						var navFixedClass = 'navbar-fixed-top';
						if( $navbar.hasClass( 'shrinkable' )  && !$body.hasClass('one-page-layout')) {
							navFixedClass += ' navbar-shrink';
						}
			
						var checkingPoint = navTop;
						if ( ($this.scrollTop() + adminbarHeight) >= checkingPoint ) {
            
							if( ! $navbar.hasClass('navbar-fixed-top') ) {
								if( $body.hasClass('page-menu-transparent') ) {
									$navbar.closest('.noo-header').css({'height': '1px'});
									$navbar.closest('.noo-header').css({'position': 'relative'});
								} else {
									$('.navbar-wrapper').css({'min-height': navHeight+'px'});
								}
								$navbar.addClass( navFixedClass );
								$navbar.css('top', adminbarHeight);
							}
						} else {
							if( $body.hasClass('page-menu-transparent') ) {
								$navbar.closest('.noo-header').css({'height': ''});
								$navbar.closest('.noo-header').css({'position': ''});
							} else {
								$('.navbar-wrapper').css({'min-height': ''});
							}
							$navbar.removeClass( navFixedClass );
						}
						
					}
				}
			};

			$window.bind('scroll',navbarInit).resize(navbarInit);
			if( $body.hasClass('one-page-layout') ) {
	
				// Scroll link
				$('.navbar-scrollspy > .nav > li > a[href^="#"]').click(function(e) {
					e.preventDefault();
					var target = $(this).attr('href').replace(/.*(?=#[^\s]+$)/, '');
					if (target && ($(target).length)) {
						var position = Math.max(0, $(target).offset().top );
							position = Math.max(0,position - (adminbarHeight + $('.navbar').outerHeight()) + 5);
						
						$('html, body').animate({
							scrollTop: position
						},{
							duration: 800, 
				            easing: 'easeInOutCubic',
				            complete: window.reflow
						});
					}
				});
				
				// Initialize scrollspy.
				$body.scrollspy({
					target : '.navbar-scrollspy',
					offset : (adminbarHeight + $('.navbar').outerHeight())
				});
				
				// Trigger scrollspy when resize.
				$(window).resize(function() {
					$body.scrollspy('refresh');
				});
	
			}
			
		}
		//Category mage manu hover
		$('.cat-mega-menu').each(function(){
			var _this = $(this),
				el = _this.find('.cat-mega-filters a');
				el.on('mouseenter',function(){
					_this.find('.cat-mega-filters li.selected').removeClass('selected');
					$(this).closest('li').addClass('selected');
					var _el_id = $(this).data('cat-id');
					_this.find('.cat-mega-content').hide();
					_this.find('[data-control-id="cat-mega-'+_el_id+'"]').show();
				});
		});
		
		$('.navbar-toggle').on('click',function(e){
			e.stopPropagation();
			e.preventDefault();
			if($('body').hasClass('offcanvas-open')){
				$('body').removeClass('offcanvas-open').addClass('offcanvas-close');
			}else{
				$('body').removeClass('offcanvas-close').addClass('offcanvas-open');
			}
			
		});
		$(document).on('click','.offcanvas-close-btn',function(){
			$('body').removeClass('offcanvas-open').addClass('offcanvas-close');
		});
		$('body').on('mousedown', $.proxy( function(e){
			var element = $(e.target);
			if($('.offcanvas').length && $('body').hasClass('offcanvas-open')){
				if(!element.is('.offcanvas') && element.parents('.offcanvas').length === 0 )
				{
					$('body').removeClass('offcanvas-open');
				}
			}
		}, this) );

		// Slider scroll bottom button
		$('.noo-slider-revolution-container .noo-slider-scroll-bottom').click(function(e) {
			e.preventDefault();
			var sliderHeight = $('.noo-slider-revolution-container').outerHeight();
			$('html, body').animate({
				scrollTop: sliderHeight
			}, 900, 'easeInOutExpo');
		});
	
		$('body').on('mouseleave ', '.masonry-style-elevated .masonry-portfolio.no-gap .masonry-item', function(){
			$(this).closest('.masonry-container').find('.masonry-overlay').hide();
			$(this).removeClass('masonry-item-hover');
		});

		if($('.masonry.noo-category-featured').length){
			$('.masonry.noo-category-featured').each(function(){
				var $this = $(this);
				$this.find('div.pagination').hide();
				$this.find('.loadmore-loading').hide();
				$this.nooLoadmore({
					navSelector  : $this.find('div.pagination'),            
			   	    nextSelector : $this.find('div.pagination a.next'),
			   	    itemSelector : 'div.loadmore-item',
			   	    loading:{
						speed:1,
						start: undefined
					},
			   	    finishedMsg  : nooL10n.ajax_finishedMsg
				},function(newElements){
					$this.find('.masonry-container').isotope('appended', $(newElements));
					$(window).unbind('.infscr');
					if($this.find('.masonry-filters').length){
						var selector = $this.find('.masonry-filters').find('a.selected').data('option-value');
						$this.find('.masonry-container').isotope({ filter: selector });
					}
				});
			});
		}


        //Init masonry isotope
        $('.masonry').each(function(){
            var self = $(this);
            var $container = $(this).find('.masonry-container');
            var $filter = $(this).find('.masonry-filters a');
            var masonry_options = {
                    'gutter' : 0
                };
            $container.isotope({
                itemSelector : '.masonry-item',
                transitionDuration : '0.8s',
                masonry : masonry_options
            });

            imagesLoaded(self,function(){
                $container.isotope('layout');
            });
            $(window).resize(function(){
            	$container.isotope('layout');
            });
            $filter.click(function(e){
                e.stopPropagation();
                e.preventDefault();

                var $this = jQuery(this);
                // don't proceed if already selected
                if ($this.hasClass('selected')) {
                    return false;
                }
                self.find('.masonry-result h3').text($this.text());
                var filters = $this.closest('ul');
                filters.find('.selected').removeClass('selected');
                $this.addClass('selected');

                var options = {
                        layoutMode : 'masonry',
                        transitionDuration : '0.8s',
                        'masonry' : {
                            'gutter' : 0
                        }
                    },
                    key = filters.attr('data-option-key'),
                    value = $this.attr('data-option-value');

                value = value === 'false' ? false : value;
                options[key] = value;

                $container.isotope(options);

            });
        });
		
		//Go to top
		$(window).scroll(function () {
			if ($(this).scrollTop() > 500) {
				$('.go-to-top').addClass('on');
			}
			else {
				$('.go-to-top').removeClass('on');
			}
		});
		$('body').on( 'click', '.go-to-top', function () {
			$("html, body").animate({
				scrollTop: 0
			}, 800);
			return false;
		});
		
		//Search
		$('body').on( 'click', '.search-button', function() {
			if ($('.searchbar').hasClass('hide'))
			{
				$('.searchbar').removeClass('hide').addClass('show');
				$('.searchbar #s').focus();
			}
			return false;
		});
		
		$('body').on('mousedown', $.proxy( function(e){
			var element = $(e.target);
			if(!element.is('.searchbar') && element.parents('.searchbar').length === 0 )
			{
				$('.searchbar').removeClass('show').addClass('hide');
			}
		}, this) );
		

		// MailChimp subscribe
		$( ".mc-subscribe-form" ).submit(function( event ) {
			event.preventDefault();

			var $form = $( this );
			var data = $form.serializeArray();
			$form.find('label.noo-message').remove();
			$.ajax({
				type: 'POST',
				url: nooL10n.ajax_url,
				data: data,
				success: function (response) {
					var result = $.parseJSON(response);
					var message = '';
					if( result.success ) {
						if( result.data !== '' ) {
							message = '<label class="noo-message error" role="alert">' + result.data + '</label>';
							$form.addClass('submited');
							$form.html(message);
						}
					} else {
						if( result.data !== '' ) {
							$form.removeClass('submited');
							$( '<label class="noo-message" role="alert">' + result.data + '</label>' ).prependTo($form);
						}
					}
				},
				error: function (errorThrown) {
				}
			});
		});
	};

	$( document ).ready( function () {
		nooInit();		
	});

	$( window ).load(function() {
		if ( $('body').hasClass( 'enable-preload') ) {
			if( $("#loader-wrapper #loader").length ) {
				$("#loader-wrapper").fadeOut({
					duration: 200,
					complete: function() {
						$(this).remove();
						$(".site").animate({opacity: 1}, { easing: 'swing', duration: 350 });
					}
				});
			} else {
				$(".site").animate({opacity: 1}, { easing: 'swing', duration: 350 });
			}
		}
	});

	$(document).bind('noo-layout-changed',function(){
		nooInit();	
	});
	$( document ).ready( function () {
		if($('[data-paginate="loadmore"]').length && $('[data-paginate="loadmore"]').find('.btn-loadmore').length){
			$(function(){
				var $container = $('[data-paginate="loadmore"] .infinite-wrap');
				$container.infinitescroll({
					navSelector:"div.pagination",
					nextSelector:"div.pagination a.next",
					itemSelector:"article",
					loading: {
						img: '',
						finishedMsg: nooL10n.infinite_scroll_end_msg,
						msg: $('<div class="noo-loader loadmore-loading"><span></span><span></span><span></span><span></span><span></span></div>'),
						finished: function(el, opts) {
							$('.noo-loader').remove();
							$btn.show();
						}
					}
				}, function(newElements){ 
					//callback
					if ($.fn.nooCarouFredSelInit) $.fn.nooCarouFredSelInit();
					if ($.fn.nooNivoLighboxInit) $.fn.nooNivoLighboxInit();
				});
				$(window).unbind('.infscr');

				var $btn = $('[data-paginate="loadmore"] .btn-loadmore');

				$btn.on('click',function(e){
					$btn.hide();
					$container.infinitescroll('retrieve');
					return false;
				});
				$(document).ajaxError(
					function(e,xhr,opt){
						if(xhr.status==404) {
							$btn.remove();
							$('.noo-loader.loadmore-loading').addClass('finished').html('<em>' + nooL10n.infinite_scroll_end_msg + '</em>').animate({ opacity: 1 }, 2000, function () {
				                $(this).fadeOut('fast');
				            });
						}
				});
			});
		}
	});

	jQuery(document).ready(function($) {
		$( document ).on(
			'click button',
			'div.woocommerce > form .cart_item .quantity button',
			function() {
				$( 'div.woocommerce > form input[name="update_cart"]' ).prop( 'disabled', false );
			});
	});

	jQuery(document).ready(function($) {
    "use strict";
    /**
     * Process slider single product
     */
    if ( $('body').hasClass( 'single-product' ) ) {
        
        /**
         * Call library Carousel
         */
            $( '.noo-image-big-wrap' ).owlCarousel({
                // navigation : true,
                // navigationText : ["<i class='ion-ios-arrow-left'></i>","<i class='ion-ios-arrow-right'></i>"],
                // slideSpeed : 600,
                // pagination: false,
                // paginationSpeed : 400,
                // addClassActive: true,
                // singleItem : true,
                // autoHeight: true

                margin:10,
                nav:true,
                addClassActive: true,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:1
                    },
                    1000:{
                        items:1
                    }
                }

            });


        /**
         * Process event and get index number when clicking item
         */
            $( '.noo-image-slider-wrap' ).on('click', '.noo-thumbnail-small', function(event) {
                event.preventDefault();
                /**
                 * VAR
                 */
                var $$     = $(this),
                    index  = $$.index();
                    
                /**
                 * Process
                 */
                    // ----- Remove all 'active' classes  of div tag
                        $( '.noo-thumbnails .noo-thumbnail-small' ).removeClass( 'active' );

                    // ---- Add 'active' classes when clicking it
                        $$.addClass( 'active' );

                    // ----- Attach index number to slider and it runs
                        $( '.noo-image-big-wrap' ).trigger( 'to.owl.carousel', [index, 0]);


            });

        /**
         * Process event when clicking button next/prev to slider
         */
            $( '.owl-next, .owl-prev' ).click(function(event) {
                // ----- Remove all 'active' classes of div tag
                    $( '.noo-thumbnails .noo-thumbnail-small' ).removeClass( 'active' );

                // ----- Get index number item now
                    var index = $( '.owl-item' ).index( $( '.active' ) );
                    console.log(index);
                
                // ----- Attach index number to images slider
                    $( '.noo-thumbnail-small' ).eq(index).addClass( 'active' );

            });

    }
 });
	
})(jQuery);

