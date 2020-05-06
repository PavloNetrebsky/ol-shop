;(function($){
    "use strict";
	$(document).ready(function(){
		$("a.bookmark-action").on("click", function(e){
			e.stopPropagation();
			e.preventDefault();
			var $this = $(this);
			var $action = $this.data('action');
			// if( $this.hasClass('bookmarked') ) {
			// 	return false;
			// }
			$.ajax({
                type: 'POST',
                dataType:'json',
                url: nooMemberL10n.ajax_url,
                data: {
                	action: $action,
                	security: $this.data('security'),
                	post_id: $this.data('post-id')
                },
                success: function (data) {
                    if (data.success == true) {
                		if($action == 'noo_remove_bookmark_post'){
                			$this.data('action','noo_bookmark_post');
                			$this.find('i').removeClass('fa-bookmark').addClass('fa-bookmark-o');
                			$this.removeClass('bookmarked');
                		}else{
                			$this.find('i').removeClass('fa-bookmark-o').addClass('fa-bookmark');
                			$this.addClass('bookmarked');
                			$this.data('action','noo_remove_bookmark_post');
                		}
                    	notif({
                    		  type: "success",
                    		  msg: data.message,
                    		  position: "right"
                		});
                    }else{
                    	notif({
                  		  type: "error",
                  		  msg: data.message,
                  		  position: "right"
                    	});
                    }
                    if(nooMemberL10n.is_manage_page === "1"){
                    	window.location.reload();
                    }
                },
                complete: function () {

                },
                error: function () {
                	document.location.reload();
                }
			});

			return false;
		});
		
		$('form#noo-ajax-login-form').on('submit', function (e) {
			e.stopPropagation();
			e.preventDefault();
			var _this = $(this);
			_this.find('.noo-ajax-result').show().html(nooMemberL10n.loadingmessage);
			$.ajax({
                type: 'POST',
                dataType: 'json',
                url: nooMemberL10n.ajax_url,
                data: {
                    action: 'noo_ajax_login',
                    log: _this.find('#log').val(),
                    pwd: _this.find('#pwd').val(),
                    remember: (_this.find('#rememberme').is(':checked') ? true : false),
                    security: _this.find('#security').val()
                },
                success: function (data) {
                	_this.find('.noo-ajax-result').show().html(data.message);
                    if (data.loggedin == true) {
                        if (data.redirecturl == null) {
                            document.location.reload();
                        }
                        else {
                            document.location.href = data.redirecturl;
                        }
                    }
                },
                complete: function () {

                },
                error: function () {
                	_this.off('submit');
                	_this.submit();
                }
			});
		});
        $('form#noo-ajax-register-form').on('submit', function (e) {
            e.stopPropagation();
            e.preventDefault();
            var _this = $(this);
            if(_this.find("#account_reg_term").length && !_this.find("#account_reg_term").is(':checked')){
                _this.find("#account_reg_term").tooltip('show');
                _this.find('.noo-ajax-result').hide();
                return false;
            }else{
                _this.find("#account_reg_term").tooltip('hide');
                _this.find('.noo-ajax-result').show().html(nooMemberL10n.loadingmessage);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: nooMemberL10n.ajax_url,
                    data: {
                        action: 'noo_ajax_register',
                        user_login: _this.find('#user_login').val(),
                        user_email: _this.find("#user_email").val(),
                        user_password: _this.find("#user_password").val(),
                        cuser_password: _this.find("#cuser_password").val(),
                        security: _this.find('#security').val(),
                        user_role: _this.find('#user_role').val()
                    },
                    success: function (data) {
                        _this.find('.noo-ajax-result').show().html(data.message);
                        if (data.success == true) {
                            if (data.redirecturl == null) {
                                document.location.reload();
                            }
                            else {
                                document.location.href = data.redirecturl;
                            }
                        }
                    },
                    complete: function () {
    
                    },
                    error: function () {
                        _this.off('submit');
                        _this.submit();
                    }
                });
            }
        });
		$('form#noo-ajax-subscribe-form').on('submit', function (e) {
			e.stopPropagation();
			e.preventDefault();
			var _this = $(this);
			if(_this.find("#account_reg_term").length && !_this.find("#account_reg_term").is(':checked')){
				_this.find("#account_reg_term").tooltip('show');
				_this.find('.noo-ajax-result').hide();
				return false;
			}else{
				_this.find("#account_reg_term").tooltip('hide');
				_this.find('.noo-ajax-result').show().html(nooMemberL10n.loadingmessage);

                var data = _this.serializeArray();
				$.ajax({
	                type: 'POST',
	                dataType: 'json',
	                url: nooMemberL10n.ajax_url,
	                data: data,
	                success: function (data) {
	                	_this.find('.noo-ajax-result').show().html(data.message);
	                    if (data.success == true) {
	                        if (data.redirecturl == null) {
	                            document.location.reload();
	                        }
	                        else {
	                            document.location.href = data.redirecturl;
	                        }
	                    }
	                },
	                complete: function () {
	
	                },
	                error: function () {
	                	_this.off('submit');
	                	_this.submit();
	                }
				});
			}
		});

        $('form#noo-ajax-subscribe-form .trial-link').on('click', function (e) {
            e.stopPropagation();
            e.preventDefault();
            var _form = $('form#noo-ajax-subscribe-form');
            _form.find('#trial_mode').val('1');
            _form.submit();

            return false;
        });
		
	});
})(jQuery);