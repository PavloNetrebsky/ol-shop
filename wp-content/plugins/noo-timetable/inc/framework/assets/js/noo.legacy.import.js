jQuery(function($){
	if($('.noo_rrule_on_date').length > 0) {
		$('.noo_rrule_on_date').datetimepicker({
	        format: "Y-m-d",
	        timepicker: false,
	        datepicker: true,
	        scrollInput: false,
	        closeOnDateSelect: true,
	    });
    }

	noo_rrule_load_all_trigger();
    function noo_rrule_load_all_trigger(){
    	if ($('.noo_rrule_json').length == 0)
			return;
    	var $input = $('.noo_rrule_json').val();
    	if ( $input.length > 0 ) {

			var $freq   = $('.noo_rrule_freq');
			var $ue     = $('.noo_rrule_use_end');
			var $end    = $('.noo_rrule_end');
			var $af     = $('.noo_rrule_after');
			var $od     = $('.noo_rrule_on_date');
			var $cu     = $('.noo_rrule_freq_use_custom');
			var $vv     = $('.noo_rrule_freq_custom'); 
			var $int    = $('.noo_rrule_interval'); 
			var $bd     = $('.noo_rrule_byday'); 
			var $bmd    = $('.noo_rrule_bymonthday'); 
			var $bm     = $('.noo_rrule_bymonth'); 
			var $ra_me  = $('.month_each'); 
			var $ra_mot = $('.month_on_the'); 
			var $ra_yot = $('.year_on_the'); 
			var $setp   = $('.noo_rrule_bysetpos'); 
			var $bdtype = $('.noo_rrule_bydaytype'); 
			var $week_ch   = $('.noo_rrule_week_choice');
			var $month_ch  = $('.noo_rrule_month_choice');
			var $year_ch   = $('.noo_rrule_year_choice');
			var $onthe     = $('.noo_rrule_onthe_choice');
			var $s = $('#_noo_event_start_date');

    		var $vl = jQuery.extend({}, jQuery.parseJSON($input));
    		
	    	if ( Object.keys($vl).length > 2 ) {
				$cu.show();
				$freq.val('custom');
				$vv.val($vl.freq);
				var $unit = $cu.find('option:selected').data('unit');
				$('.noo_rrule_interval_text').text($unit);

				if ( $vv.val() == 'weekly' ) {
					$week_ch.show();
					var days = $vl.byday.split(',');
					for(var day in days){
						$bd.find('input[value='+days[day]+']').prop("checked", true);
					}
				}
				if ( $vv.val() == 'monthly' ) {
					$month_ch.show();
					$onthe.show();

					if ( typeof( $vl.bymonthday ) != "undefined" ) {
						var days = $vl.bymonthday.split(',');
						for(var day in days){
							$bmd.find('input[value='+days[day]+']').prop("checked", true);
						}
						$ra_me.prop("checked", true);
					}
					if ( typeof( $vl.byday ) != "undefined" ) {
						$setp.prop( 'disabled', false );
						$bdtype.prop( 'disabled', false );
						$ra_mot.prop("checked", true);
						$bmd.find('input[type="checkbox"]').prop( 'disabled', true );
						$bmd.addClass('disabled');
					}
				}
				if ( $vv.val() == 'yearly' ) {
					$year_ch.show();
					$onthe.show();
					var months = $vl.bymonth.split(',');
					for(var month in months){
						$bm.find('input[value='+months[month]+']').prop("checked", true);
					}

					if ( typeof( $vl.byday ) != "undefined" ) {
						$setp.prop( 'disabled', false );
						$bdtype.prop( 'disabled', false );
						$ra_yot.prop("checked", true);
					}

				}
				if ( $vv.val() == 'monthly' || $vv.val() == 'yearly' ) {
					if ( typeof( $vl.byday ) != "undefined" ) {
						if ( $vl.byday === 'mo,tu,we,th,fr' ) {
							$bdtype.val('weekday');
						} else if ( $vl.byday === 'sa,su' ) {
							$bdtype.val('weekend');
						} else if ( $vl.byday === 'mo,tu,we,th,fr,sa,su' ) {
							$bdtype.val('day');
						} else {
							var vl_bysetpos = $vl.byday.substr(0, $vl.byday.length-2);
							var vl_byday = $vl.byday.substr(-2, 2);
							$setp.val(vl_bysetpos);
							$bdtype.val(vl_byday);
						}
					}
					if ( typeof( $vl.bysetpos ) != "undefined" ) {
						$setp.val($vl.bysetpos);
					}
				}
	    	} else {
	    		$freq.val($vl.freq);
	    	}

	    	if (typeof( $vl.count ) != "undefined"){
	    		$end.val('count');
	    		$af.show();
	    		$od.hide();
	    	}
	    	if (typeof( $vl.until ) != "undefined"){
	    		$end.val('until');
	    		$od.show();
	    		$af.hide();
	    	}

	    	$vl.count = (typeof( $vl.count ) != "undefined") ? $vl.count : 1;
	    	$vl.interval = (typeof( $vl.interval ) != "undefined") ? $vl.interval : 1;
    		var $vl_un = (typeof( $vl.until ) != "undefined") ? $vl.until.split('t') : '';
    		if ($vl_un.length > 0) {
	    		$vl_un = $vl_un[0];
	    		$vl_un = $vl_un.slice(0, 4) + "-" + $vl_un.slice(4, 6) + "-" + $vl_un.slice(6, 8);
    		}

	    	$ue.show();
	    	$int.val($vl.interval);
	    	$af.val($vl.count);
	    	$od.val($vl_un);
	    	
    	}
    }

    function noo_rrule_get_today_info(format) {
    	if( typeof( format ) == "undefined" ){
    		format = 'yyyy-mm-dd';
    	}
    	var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1;
		var yyyy = today.getFullYear();
		if (format=='day') {
			var days = ['su','mo','tu','we','th','fr','sa'];
			var today = days[today.getDay()];
		} else if (format=='dd') {
			var today = dd;
		} else if (format=='mm') {
			var today = mm;
		} else {
			if(dd<10) dd='0'+dd;
			if(mm<10) mm='0'+mm;
			var today = yyyy+'-'+mm+'-'+dd;
		}
		return today;
    }

	$('.noo-recurrence-setup').on( "keyup", 'input[type="text"]', function() {
    	$(document).trigger( 'noo_rrule_get_all_value_trigger' );
    });

    $('.noo-recurrence-setup').on( "change", "input, select", function() {
    	$(document).trigger( 'noo_rrule_get_all_value_trigger' );
    });

    $(document).on('noo_rrule_get_all_value_trigger', function( event ){
    	var $oop = [];
		var $freq   = $('.noo_rrule_freq');
		var $end    = $('.noo_rrule_end');
		var $af     = $('.noo_rrule_after');
		var $od     = $('.noo_rrule_on_date');
		var $vv     = $('.noo_rrule_freq_custom'); 
		var $int    = $('.noo_rrule_interval'); 
		var $bd     = $('.noo_rrule_byday'); 
		var $bmd    = $('.noo_rrule_bymonthday'); 
		var $bm     = $('.noo_rrule_bymonth'); 
		var $ra_me  = $('.month_each'); 
		var $ra_mot = $('.month_on_the'); 
		var $ra_yot = $('.year_on_the'); 
		var $setp   = $('.noo_rrule_bysetpos'); 
		var $bdtype = $('.noo_rrule_bydaytype'); 
		var $s = $('#_noo_event_start_date');
		var $str = $('.noo_rrule_string');

		var $freq_val = $freq.val();
		if ( $freq_val !== 'none' ) {

			if ( $freq_val === 'custom' )
				$freq_val = $vv.val();

			$oop.push('freq='+$freq_val);
			
			if ( $end.val() === 'until' ) {
				if ( $od.val().length == 0 )
					$od.val($s.val());
				var $od_val = $od.val();
				if ($od_val.length==0) {
					$od_val = noo_rrule_get_today_info('yyyy-mm-dd');
				}
				$od_val = $od_val.replace(/-/g, "");
				$od_val += 'T165959Z';
				$oop.push($end.val()+'='+$od_val);
			}
			else {
				$oop.push($end.val()+'='+$af.val());
			}

			if ( $freq.val() === 'custom' ) {
				if ( $int.val() > 1 )
					$oop.push('interval='+$int.val());

				if ( $vv.val() === 'weekly' ) {
					if ($bd.find('input:checked').length == 0) {
						w = noo_rrule_get_today_info('day');
						$bd.find('input[value='+w+']').prop("checked", true);
					}
					var _allVals = [];
				    $bd.find('input:checked').each(function() {
				    	_allVals.push($(this).val());
				    });
				     _allVals = _allVals.join(',', _allVals);
					$oop.push('byday='+_allVals);
				}
				
				if ( $vv.val() === 'monthly' || $vv.val() === 'yearly' ) {
					$use_on_the = false;
					if ( $vv.val() === 'monthly' ) {
						if ( $ra_mot.is(':checked') ) {
							$use_on_the = true;
						}
						if ( $ra_me.is(':checked') ) {
							if ($bmd.find('input:checked').length == 0) {
								d = noo_rrule_get_today_info('dd');
								$bmd.find('input[value='+d+']').prop("checked", true);
							}
							var _allVals = [];
						     $bmd.find('input:checked').each(function() {
						       _allVals.push($(this).val());
						     });
						     _allVals = _allVals.join(',', _allVals);
							$oop.push('bymonthday='+_allVals);
						}
					}
					if ( $vv.val() === 'yearly' ) {
						if ( $ra_yot.is(':checked') ) {
							$use_on_the = true;
						}
						if ($bm.find('input:checked').length == 0) {
							mmm = noo_rrule_get_today_info('mm');
							$bm.find('input[value='+mmm+']').prop("checked", true);
						}
						var _allVals = [];
					     $bm.find('input:checked').each(function() {
					       _allVals.push($(this).val());
					     });
					     _allVals = _allVals.join(',', _allVals);
						$oop.push('bymonth='+_allVals);
					}
					if ( $use_on_the ) {
						if ( $bdtype.val().length == 2 ) {
							$oop.push('byday='+$setp.val()+$bdtype.find('option:selected').val());
						} else {
							if ( $bdtype.val() === 'weekend' ) {
								$oop.push('byday=sa,su');
							} else if ( $bdtype.val() === 'weekday' ) {
								$oop.push('byday=mo,tu,we,th,fr');
							} else {
								$oop.push('byday=mo,tu,we,th,fr,sa,su');
							}
							$oop.push('bysetpos='+$setp.val());
						}
					}
				}
			}

			$oop = $oop.join(';', $oop);
			$oop = $oop.toUpperCase();
			$str.val($oop);
		} else {
			$str.val('');
		}
    });

	$('.noo-recurrence-setup').on( "change", ".noo_rrule_freq", function() {
		var $s = $('#_noo_event_start_date');
		var $e = $('#_noo_event_end_date');
		var $cu = $('.noo_rrule_freq_use_custom');
		var $ue = $('.noo_rrule_use_end');

		var $notice_disable = $(this).data('notice-disable-start');
		if ( $(this).val() === 'custom' ) {
			$cu.show();
			$ue.show();
			var $unit = $cu.find('option:selected').data('unit');
			$('.noo_rrule_interval_text').text($unit);
		} else if( $(this).val() === 'none' ) {
			$ue.hide();
			$cu.hide();
		} else {
			$cu.hide();
			$ue.show();
		}
	});

	$('.noo-recurrence-setup').on( "change", ".noo_rrule_freq_custom", function() {
		var $unit      = $(this).find('option:selected').data('unit');
		var $week_ch   = $('.noo_rrule_week_choice');
		var $month_ch  = $('.noo_rrule_month_choice');
		var $year_ch   = $('.noo_rrule_year_choice');
		var $onthe     = $('.noo_rrule_onthe_choice');
		var $bysetpos  = $('.noo_rrule_bysetpos');
		var $bydaytype = $('.noo_rrule_bydaytype');

		$('.noo_rrule_interval_text').text($unit);

		if ( $(this).val() === 'weekly' ) {
			$week_ch.show();
			$month_ch.hide();
			$year_ch.hide();
			if ($('.noo_rrule_byday').find('input:checked').length == 0) {
				w = noo_rrule_get_today_info('day');
				$('.noo_rrule_byday').find('input[value='+w+']').prop("checked", true);
			}
		} else {
			$week_ch.hide();
		}

		if ( $(this).val() === 'monthly' ) {
			$month_ch.show();
			$onthe.show();
			$week_ch.hide();
			$year_ch.hide();
			if ($('.noo_rrule_bymonthday').find('input:checked').length == 0) {
				day = noo_rrule_get_today_info('dd');
				$('.noo_rrule_bymonthday').find('input[value='+day+']').prop("checked", true);
			}
		} else {
			$month_ch.hide();
			$onthe.hide();
		}

		if ( $(this).val() === 'yearly' ) {
			$year_ch.show();
			$onthe.show();
			$week_ch.hide();
			if ($('.noo_rrule_bymonth').find('input:checked').length == 0) {
				month = noo_rrule_get_today_info('mm');
				$('.noo_rrule_bymonth').find('input[value='+month+']').prop("checked", true);
			}
		} else {
			$year_ch.hide();
			$onthe.hide();
		}
		if ( $(this).val() === 'monthly' || $(this).val() === 'yearly' ) {
			$(document).trigger( 'noo_rrule_load_logic_trigger' );
			$onthe.show();
		} else {
			$onthe.hide();
		}

	});

	$('.noo_rrule_month_choice').on( "change", 'input[type="radio"]', function() {
		$(document).trigger( 'noo_rrule_load_logic_trigger' );
	});

	$('.noo_rrule_year_choice').on( "change", 'input.year_on_the', function() {
		$(document).trigger( 'noo_rrule_load_logic_trigger' );
	});

	$(document).on('noo_rrule_load_logic_trigger', function( event ){
    	
    	var $vv = $('.noo_rrule_freq_custom');
    	var $month_ch  = $('.noo_rrule_month_choice');
		var $year_ch   = $('.noo_rrule_year_choice');
		var $bysetpos  = $('.noo_rrule_bysetpos');
		var $bydaytype = $('.noo_rrule_bydaytype');
		var $iradi = $month_ch.find('input.month_on_the');
		var $iche = $year_ch.find('input.year_on_the');
		var $bymonthday = $('.noo_rrule_bymonthday');

    	if ( $vv.val() === 'monthly' ) {
			if ( $iradi.is(':checked') ) {
				$bysetpos.prop( 'disabled', false );
				$bydaytype.prop( 'disabled', false );
				$bymonthday.find('input[type="checkbox"]').prop( 'disabled', true );
				$bymonthday.addClass('disabled');
			} else {
				$bysetpos.prop( 'disabled', true );
				$bydaytype.prop( 'disabled', true );
				$bymonthday.find('input[type="checkbox"]').prop( 'disabled', false );
				$bymonthday.removeClass('disabled');
			}
		}
		if ( $vv.val() === 'yearly' ) {
			if ( $iche.is(':checked') ) {
				$bysetpos.prop( 'disabled', false );
				$bydaytype.prop( 'disabled', false );
			} else {
				$bysetpos.prop( 'disabled', true );
				$bydaytype.prop( 'disabled', true );
			}
		}
    });

	$('.noo-recurrence-setup').on( "click", 'input[type="checkbox"]', function(event) {
		
		var $pa = $(this).closest('div');
		if ($pa.find('input:checked').length == 0) {
			event.preventDefault();
			event.stopPropagation();
		}

    });	

	$('.noo-recurrence-setup').on( "change", ".noo_rrule_end", function() {
		var $s = $('#_noo_event_start_date');
		var $af = $('.noo_rrule_after');
		var $od = $('.noo_rrule_on_date');
		if ( $(this).val() === 'count' ) {
			$af.show();
			$od.hide();
		} else {
			if ($od.val().length == 0)
				$od.val($s.val());
			if ($od.val().length == 0)
				$od.val(noo_rrule_get_today_info('yyyy-mm-dd'));
			$af.hide();
			$od.show();
		}
	});

	$('.noo-class-import-sync').on( "click", "#noo-sync-import-all", function() {
		NooImporterIcal.importAllClasses();
		return false;
	});
	$('.noo-class-import-sync').on( "click", "#noo-sync-save-recurring", function() {
		NooImporterIcal.saveRecurringClasses();
		return false;
	});
	$('.noo-class-import-sync').on( "click", "#noo-sync-preview", function() {
		NooImporterIcal.previewClasses();
		return false;
	});
	$('.noo-class-import-sync').on( "click", "#noo-ical-upload", function() {
		NooImporterIcal.icsFileUploader();
		return false;
	});
	$('.noo-class-import-sync').on( "change", "#noo-import-type", function() {
		NooImporterIcal.showButtonSaveRecurring();
	});
	$('.noo-class-import-sync').on( "change", "#noo-import-source", function() {
		NooImporterIcal.showUploadButton( $(this ).find( 'option:selected' ).val() );
	});
	if ( $('.input-import-start').length > 0 ) {
		$('.input-import-start').datetimepicker({
	        format: "Y-m-d",
	        timepicker: false,
	        datepicker: true,
	        scrollInput: false,
	        closeOnDateSelect: true,
	    });
	}
});
$ = jQuery.noConflict();
var NooImporterIcal = {
	getFormEventData: function() {
		var data = {
			url      		: $('#noo-import-url').val(),
			start    		: $('#noo-import-start').val(),
			class_category 	: $('#noo-import-category').val(),
			color   		: $('#noo-import-color').val()
		};
		return data;
	},
	getFormClassData: function() {
		var data = {
			url      		: $('#noo-import-url').val(),
			start    		: $('#noo-import-start').val(),
			class_category 	: $('#noo-import-category').val(),
			trainer  		: $('#noo-import-trainer').val()
		};
		return data;
	},
	previewClasses: function() {
		var args = NooImporterIcal.getFormClassData();
		args.action   = 'noo_preview_all_classes';
		args._wpnonce = nooIcalImport.security;
		var nootice = $('.noo-notice');

		if ( args.url.length === 0 ) {
			nootice.removeClass().addClass('noo-notice');
			nootice.addClass('notice-error').text("Please enter iCal URL.");
			$('#noo-import-url').focus();
			return;
		}

		$.ajax({
			type : "POST",
			url : nooIcalImport.ajaxurl,
			data : args,
			beforeSend : function(){
				$('.wrap-result-preview').empty();
				nootice.removeClass().addClass('noo-notice');
				nootice.addClass('notice-info').text('');
				nootice.append('<div class="spinner is-active"></div>');
			},
			error: function( response ) {
				console.log( response );
			},
			success : function( response ) {
				if( response == false ) {
					nootice.removeClass().addClass('noo-notice');
					nootice.addClass('notice-error').text('An error occurred.');
					return;
				}
				if( typeof( response.errors ) != "undefined" ){
					nootice.removeClass().addClass('noo-notice');
					nootice.addClass('notice-error').text(response.errors[1]);
				} else {
					nootice.text('').removeClass().addClass('noo-notice');
					$('.wrap-result-preview').prepend(response.body);
				}
				
			}
		});
	},
	importAllClasses : function() {
		if ( $('#noo-import-post-type').val() == 'class' ) {
			var args = NooImporterIcal.getFormClassData();
		} else {
			var args = NooImporterIcal.getFormEventData();
		}
		args.post_type_import = $('#noo-import-post-type').val();
		args.action   = 'noo_import_all_classes';
		args._wpnonce = nooIcalImport.security;
		var nootice = $('.noo-notice');

		if ( args.url.length === 0 ) {
			nootice.removeClass().addClass('noo-notice');
			nootice.addClass('notice-error').text("Please enter iCal URL.");
			$('#noo-import-url').focus();
			return;
		}

		$.ajax({
			type : "POST",
			url : nooIcalImport.ajaxurl,
			data : args,
			beforeSend : function(){
				nootice.removeClass().addClass('noo-notice');
				nootice.addClass('notice-info').text('');
				nootice.append('<div class="spinner is-active"></div>');
			},
			error: function( response ) {
				console.log( response );
			},
			success : function( response ) {
				
				if( response == false ) {
					nootice.removeClass().addClass('noo-notice');
					nootice.addClass('notice-error').text('An error occurred.');
					return;
				}

				if( typeof( response.errors ) != "undefined" ){
					nootice.removeClass().addClass('noo-notice');
					nootice.addClass('notice-error').text(response.errors[1]);
				} else {
					nootice.removeClass().addClass('noo-notice');
					nootice.addClass('notice-success').text( parseInt( response ) + " Item Imported.");	
					NooImporterIcal.clearForm();
				}
				
			}
		});
	},
	saveRecurringClasses: function() {
		if ( $('#noo-import-post-type').val() == 'class' ) {
			var args = NooImporterIcal.getFormClassData();
		} else {
			var args = NooImporterIcal.getFormEventData();
		}
		args.post_type_import = $('#noo-import-post-type').val();
		args.import_type = $('#noo-import-type').val();
		args.action      = 'noo_recurring_classes';
		args._wpnonce    = nooIcalImport.security;
		var nootice = $('.noo-notice');

		if ( args.url.length === 0 ) {
			nootice.removeClass().addClass('noo-notice');
			nootice.addClass('notice-error').text("Please enter iCal URL.");
			$('#noo-import-url').focus();
			return;
		}

		$.ajax({
			type : "POST",
			url : nooIcalImport.ajaxurl,
			data : args,
			beforeSend : function(){
				nootice.removeClass().addClass('noo-notice');
				nootice.addClass('notice-info').text('');
				nootice.append('<div class="spinner is-active"></div>');
			},
			error: function( response ) {
				console.log( response );
			},
			success : function( response ) {
				
				if( response == false ) {
					nootice.removeClass().addClass('noo-notice');
					nootice.addClass('notice-error').text('An error occurred.');
					return;
				}

				if (args.post_type_import == 'event') {
					NooImporterIcal.redirectEvent( "&new_saved_cron=1" );
				} else {
					NooImporterIcal.redirectClass( "&new_saved_cron=1" );
				}

			}
		});
	},
	showButtonSaveRecurring: function( e ) {
		var select = $('#noo-import-type');
		if ( select.val() == 1 ) {
			$('#noo-sync-import-all').show();
			$('#noo-sync-save-recurring').hide();
		} else if( select.val() != 0 ) {
			$('#noo-sync-import-all').hide();
			$('#noo-sync-save-recurring').show();
		} else {
			$('#noo-sync-import-all').hide();
			$('#noo-sync-save-recurring').hide();
		}
	},
	clearForm: function() {
		$('#noo-import-type').val(1);
		$('#noo-import-url').val('');
		$('#noo-import-category').val('');
		$('#noo-import-trainer').val('');
	},
	redirectEvent : function( queryArgs ){
		var url = nooIcalImport.import_event_url;
		url += queryArgs;
		window.location.href = url;
	},
	redirectClass : function( queryArgs ){
		var url = nooIcalImport.import_class_url;
		url += queryArgs;
		window.location.href = url;
	},
	icsFileUploader : function() {
		var custom_uploader = wp.media({
			title : 'Choose .ics File',
			button : {
				text : 'Choose .ics File'
			},
			library : {
				type : 'text/calendar'
			},
			multiple : false
		}).on('select', function() {
			var items = custom_uploader.state().get('selection');
			var attachment = items.models[0].toJSON();
			$('#noo-import-url').val(attachment.url);
		}).open();
	},
	showUploadButton : function( v ){
		var $select = $( '#noo-import-type' );
		if( v == 'file' ){
			$select.prop( 'disabled', true );
			$select.find( '.recurring' ).prop( 'selected', false );
			$select.find( '.single' ).prop( 'selected', true );
			$( '#noo-ical-upload' ).show();
			$( '#noo-sync-import-all').show();
			$( '#noo-sync-save-recurring').hide();
		} else {
			$select.prop( 'disabled', false );
			$( '#noo-ical-upload' ).hide();
		}
	},
}