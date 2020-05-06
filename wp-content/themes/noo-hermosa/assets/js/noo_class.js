jQuery(document).ready(function($) {

	//Init masonry isotope
	$('.masonry').each(function(){
		var self = $(this);
		var $container = $(this).find('.masonry-container');
		var $filter = $(this).find('.masonry-filters a');
		$container.isotope({
			itemSelector : '.masonry-item',
			transitionDuration : '0.8s',
			masonry : {
				'gutter' : 0
			}
		});
		
		imagesLoaded(self,function(){
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

	// Class filters
	var grop_class_filters ={};
	var onArrange = function () {
		if (getFilterStr() && $('.load-title').length > 0) {
            $.ajax({
                type: 'POST',
                url: nooL10n.ajax_url,
                beforeSend: function() {
                    $(".posts-loop-title .title-have i").fadeIn(150);
                },
                data: {
                    action: 'noo_class_get_count_arrange',
                    filter: getFilterStr(),
                },
                success: function (res) {
                    $(".posts-loop-title .title-have i").fadeOut(150);
                    $(".posts-loop-title .title-have span").html(res);
                }
            });
        }
        else {
            $(".posts-loop-title .title-have span").html($('.masonry-item:visible').length);
        }
		if( $('.masonry-item:visible').length <= 0 && $('.loadmore-action .btn-loadmore').is(':visible') ) {
			$('.loadmore-action .btn-loadmore').click();
		}
	}
	if($('.widget-classes-filters').length){
		var class_filters = $('.widget-classes-filters');
		var masonrycontainer = class_filters.closest('body').find('.noo-classes .masonry-container');
		if(masonrycontainer.length){
			var getFilterStr = function ($filter_item) {
				class_filters.find(':input').each( function() {
					var $filter_item = $(this);
					var group = $filter_item.closest('.widget-class-filter').data('group');
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
						$filter_item.closest('.widget-class-filter').find('.widget-class-filter-control').each(function(){
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

			masonrycontainer.isotope( 'on', 'layoutComplete', onArrange);
			
			class_filters.find('.widget-class-filter-control').on('change', function () {
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
	
	if($('.masonry').length){
		$('.masonry').each(function(){
			var $this = $(this);
			$this.find('div.pagination').hide();
			$this.find('.loadmore-loading').hide();
			$this.nooLoadmore({
				navSelector  : $this.find('div.pagination'),            
		   	    nextSelector : $this.find('div.pagination a.next'),
		   	    itemSelector : '.loadmore-item',
		   	    contentWrapper: $this.find('.masonry-container'),
		   	    loading:{
					speed:1,
					start: undefined
				},
		   	    finishedMsg  : nooL10n.ajax_finishedMsg
			},function(newElements){
				var masonrycontainer = $this.find('.masonry-container');
				$this.find('.masonry-container').isotope('appended', $(newElements));

				masonrycontainer.isotope( 'on', 'layoutComplete', function() {
					setTimeout(onArrange, 850);
				});
				$(window).unbind('.infscr');

				imagesLoaded(masonrycontainer,function(){
					masonrycontainer.isotope('layout');
				});
			});
		});
	}


	// Button Switch display class
	$('.loop-view-mode').click(function(){


	});

	$('.loop-view-mode').on('click', 'a', function(event) {
        event.preventDefault();

        var id = $(this).data( 'id' );

        $( '.loop-view-mode a' ).removeClass( 'active' );
        $(this).addClass( 'active' );


        if ( id === 'grid' ) {
            $( '.noo-classes' ).removeClass('list').addClass('grid');
            $('.masonry-item').addClass('noo-sm-6 noo-md-6');
        } else if ( id === 'list' ) {
            $( '.noo-classes' ).removeClass('grid').addClass('list');
            $('.masonry-item').removeClass('noo-sm-6');
            $('.masonry-item').removeClass('noo-md-6');
        }

        $('.masonry').each(function(){
			var self = $(this);
			var $container = $(this).find('.masonry-container');
			$container.isotope({
				itemSelector : '.masonry-item',
				transitionDuration : '0.8s',
				masonry : {
					'gutter' : 0
				}
			});
			
			imagesLoaded(self,function(){
				$container.isotope('layout');
			});

			setTimeout(function(){
				imagesLoaded(self,function(){
					$container.isotope('layout');
				});
			}, 1000);
		});


    });
});