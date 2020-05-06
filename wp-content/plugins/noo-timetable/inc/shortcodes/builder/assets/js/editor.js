
!function($){
	if (window.$ns.mixins === undefined) window.$ns.mixins = {};

	/**
	 * Class mutator, allowing bind, unbind, and trigger class instance events
	 * @type {{}}
	 */
	$ns.mixins.Events = {
		/**
		 * Attach a handler to an event for the class instance
		 * @param {String} eventType A string containing event type, such as 'beforeShow' or 'change'
		 * @param {Function} handler A function to execute each time the event is triggered
		 */
		bind: function(eventType, handler){
			if (this.$$events === undefined) this.$$events = {};
			if (this.$$events[eventType] === undefined) this.$$events[eventType] = [];
			this.$$events[eventType].push(handler);
			return this;
		},
		/**
		 * Remove a previously-attached event handler from the class instance
		 * @param {String} eventType A string containing event type, such as 'beforeShow' or 'change'
		 * @param {Function} [handler] The function that is to be no longer executed.
		 * @chainable
		 */
		unbind: function(eventType, handler){
			if (this.$$events === undefined || this.$$events[eventType] === undefined) return this;
			if (handler !== undefined) {
				var handlerPos = $.inArray(handler, this.$$events[eventType]);
				if (handlerPos != -1) {
					this.$$events[eventType].splice(handlerPos, 1);
				}
			} else {
				this.$$events[eventType] = [];
			}
			return this;
		},
		/**
		 * Execute all handlers and behaviours attached to the class instance for the given event type
		 * @param {String} eventType A string containing event type, such as 'beforeShow' or 'change'
		 * @param {Array} extraParameters Additional parameters to pass along to the event handler
		 * @chainable
		 */
		trigger: function(eventType, extraParameters){
			if (this.$$events === undefined || this.$$events[eventType] === undefined || this.$$events[eventType].length == 0) return this;
			for (var index = 0; index < this.$$events[eventType].length; index++) {
				this.$$events[eventType][index].apply(this, extraParameters);
			}
			return this;
		}
	};

	/**
	 * $ns.Field class
	 * Boundable events: beforeShow, afterShow, change, beforeHide, afterHide
	 * @param control
	 * @param form - container form
	 * @constructor
	 */
	$ns.Field = function(control, form){
		this.$control = $(control);
        this.form = form;
		if (this.$control.data('nsfield')) return this.$control.data('nsfield');
		this.type = this.$control.data('param_type');
		this.name = this.$control.data('param_name');
		this.$input = this.$control.find('input[name], textarea[name], select[name]');
		this.inited = false;

		// Overloading by a certain type's declaration, moving parent functions to "parent" namespace: init => parentInit
		if ($ns.Field[this.type] !== undefined) {
			for (var fn in $ns.Field[this.type]) {
				if (!$ns.Field[this.type].hasOwnProperty(fn)) continue;
				if (this[fn] !== undefined) {
					var parentFn = 'parent' + fn.charAt(0).toUpperCase() + fn.slice(1);
					this[parentFn] = this[fn];
				}
				this[fn] = $ns.Field[this.type][fn];
			}
		}

		this.$control.data('nsfield', this);

		// Init on the first show
		var initEvent = function(){
			this.init();
			this.inited = true;
			this.unbind('beforeShow', initEvent);
		}.bind(this);
		this.bind('beforeShow', initEvent);
	};
	$.extend($ns.Field.prototype, $ns.mixins.Events, {
		init: function(){
			this.$input.on('change', function(){
				this.trigger('change', [this.getValue()]);
			}.bind(this));
		},
		deinit: function(){
		},
		getValue: function(){
			return this.$input.val();
		},
		setValue: function(value){
			this.$input.val(value);
			this.render();
			this.trigger('change', [value]);
		},
		render: function(){
		}
	});

    /**
     * $ns.Field type: select/dropdown
     */
    $ns.Field['select'] = $ns.Field['dropdown'] = {
        init: function(){
            this.$input.on('change keyup', function(){
                this.trigger('change', [this.getValue()]);
            }.bind(this));
        },
        setValue: function(value) {
            this.$input.val(value);
            this.$input.find("option").removeAttr("selected");
            this.$input.find("option[value='"+value+"']").attr("selected","selected");
            this.render();
            this.trigger('change', [value]);
        }
    };

    /**
     * $ns.Field type: radio
     */
    $ns.Field['radio'] = {
        getValue: function(){
            var value = '';
            this.$input.each( function(index, radio) {
                if( radio.checked) value = $(this).attr('value');
            });
            return value;
        },
        setValue: function(value){
            this.$input.each( function(index, radio) {
                radio.checked = false;
                if( $(radio).attr('value') == value ) radio.checked = true;
            });
            this.render();
            this.trigger('change', [value]);
        }
    };

	/**
	 * $ns.Field type: checkbox
	 */
	$ns.Field['checkbox'] = {
		init: function(){
			this.parentInit();
			this.$checkboxes = this.$control.find('input[type="checkbox"]');
			this._events = {
				change: function(){
					var value = '';
					this.$checkboxes.each(function(index, checkbox){
						if (checkbox.checked) value += ((value != '') ? ',' : '') + checkbox.value;
					}.bind(this));
					this.$input.val(value).trigger('change');
				}.bind(this)
			};
			this.$checkboxes.on('change', this._events.change);
		},

		render: function(){
			var value = this.getValue().split(',');
			this.$checkboxes.each(function(index, checkbox){
				$(checkbox).attr('checked', ($.inArray(checkbox.value, value) != -1) ? 'checked' : false);
			}.bind(this));
		}
	};

	/**
	 * $ns.Field type: exploded_textarea
	 */
	$ns.Field['exploded_textarea'] = {
        init: function(){
            this.parentInit();
            this.$textarea = this.$control.find('textarea');
            this._events = {
                change: function(){
                    var value = this.encode( this.$textarea.val() );
                    this.$input.val(value).trigger('change');
                }.bind(this)
            };
            this.$textarea.on('change', this._events.change);
        },
        getValue: function(){
        	var value = this.$input.val();
        	value = value.match(/\n/g) ? this.encode( value ) : value;

        	return value;
        },
        render: function(){
            var value = this.decode( this.getValue() );
            this.$textarea.val(value);
        },
        encode: function( value ) {
        	return value.replace(/\n/g, ",");
		},
        decode: function( value ) {
        	return value.split(',').join("\n");
		}
	};

    /**
     * $ns.Field type: textarea_safe
     */
    $ns.Field['textarea_safe'] = {
        init: function(){
            this.parentInit();
            this.$textarea = this.$control.find('textarea');
            this._events = {
                change: function(){
                    var value = this.$textarea.val();
                    value = value.match(/"|(http)/) ? this.encode( value ) : value;

                    this.$input.val(value).trigger('change');
                }.bind(this)
            };
            this.$textarea.on('change', this._events.change);
        },
        getValue: function(){
            var value = this.$input.val();
            value = ( value.match(/"|(http)/) && ! value.match(/^#E\-8_/) ) ? this.encode( value ) : value;

            return value;
        },
        render: function(){
            var value = this.getValue();
            value = value && value.match(/^#E\-8_/) ? this.decode( value ) : value;

            this.$textarea.val(value);
        },
        encode: function( value ) {
            return "#E-8_" + $ns.fn.base64_encode($ns.fn.rawurlencode( value ) );
        },
        decode: function( value ) {
            return $ns.fn.rawurldecode($ns.fn.base64_decode(value.replace(/^#E\-8_/, "")));
        }
    };

	/**
	 * $ns.Field type: exploded_textarea_safe
	 */
	$ns.Field['exploded_textarea_safe'] = {
        init: function(){
            this.parentInit();
            this.$textarea = this.$control.find('textarea');
            this._events = {
                change: function(){
                    var value = this.$textarea.val();
                    value = this.encode( value );

                    this.$input.val(value).trigger('change');
                }.bind(this)
            };
            this.$textarea.on('change', this._events.change);
        },
        getValue: function(){
            var value = this.$input.val();
            value = ( value.match(/\n/g) || ( value.match(/"|(http)/) && ! value.match(/^#E\-8_/) ) ) ? this.encode( value ) : value;

            return value;
        },
        render: function(){
            var value = this.getValue();
            value = this.decode( value );

            this.$textarea.val(value);
        },
        encode: function( value ) {
        	value = value.replace(/\n/g, ",");
            return ( value.match(/"|(http)/) ? "#E-8_" + $ns.fn.base64_encode($ns.fn.rawurlencode( value ) ) : value );
        },
        decode: function( value ) {
            value = value && value.match(/^#E\-8_/) ? $ns.fn.rawurldecode($ns.fn.base64_decode(value.replace(/^#E\-8_/, ""))) : value;
            value = value.split(',').join("\n");

            return value;
        }
	};

    /**
     * $ns.Field type: textarea_raw_html
     */
    $ns.Field['textarea_raw_html'] = {
        init: function(){
            this.parentInit();
            this.$textarea = this.$control.find('textarea');
            this._events = {
                change: function(){
                    var value = this.$textarea.val();
                    value = this.encode( value );

                    this.$input.val(value).trigger('change');
                }.bind(this)
            };
            this.$textarea.on('change', this._events.change);
        },
		getValue: function() {
            var value = this.$input.val();
            var inputtedValue = this.$textarea.val();

            if( value && value == inputtedValue ) {
            	value = this.encode( value );
			}

			return value;
		},
        render: function(){
            var value = this.getValue();
            value = value ? this.decode( value ) : '';

            this.$textarea.val(value);
        },
        encode: function( value ) {
            return $ns.fn.base64_encode($ns.fn.rawurlencode( value ) );
        },
        decode: function( value ) {
            return $ns.fn.rawurldecode($ns.fn.base64_decode(value.trim()));
        }
    };

    $ns.Field['event_categories'] = {
        init: function() {
            this.parentInit();
        },

		render: function() {
            this.$input = this.$control.find('input:first');
            this.$select = this.$control.find('select');
            if(this.getValue() == '') {
                var valueArr = [];
            } else {
                var valueArr = this.getValue().split(",");
            }
            this.$select.val(valueArr);
            this.$select.trigger('chosen:updated');
            this.$select.setSelectionOrder(valueArr);
		}
	};

    $ns.Field['trainer_categories'] = {
        init: function() {
            this.parentInit();
        },

        render: function() {
            this.$input = this.$control.find('input:first');
            this.$select = this.$control.find('select');
            if(this.getValue() == '') {
                var valueArr = [];
            } else {
                var valueArr = this.getValue().split(",");
            }
            this.$select.val(valueArr);
            this.$select.trigger('chosen:updated');
            this.$select.setSelectionOrder(valueArr);
        }
    };

    $ns.Field['class_categories'] = {
        init: function() {
            this.parentInit();
        },

        render: function() {
            this.$input = this.$control.find('input:first');
            this.$select = this.$control.find('select');
            if(this.getValue() == '') {
                var valueArr = [];
			} else {
                var valueArr = this.getValue().split(",");
			}
            this.$select.val(valueArr);
            this.$select.trigger('chosen:updated');
            this.$select.setSelectionOrder(valueArr);
        }
    };

    $ns.Field['class_levels'] = {
        init: function() {
            this.parentInit();
        },

        render: function() {
            this.$input = this.$control.find('input:first');
            this.$select = this.$control.find('select');
            if(this.getValue() == '') {
                var valueArr = [];
            } else {
                var valueArr = this.getValue().split(",");
            }
            this.$select.val(valueArr);
            this.$select.trigger('chosen:updated');
            this.$select.setSelectionOrder(valueArr);
        }
    };

    $ns.Field['class_trainers'] = {
        init: function() {
            this.parentInit();
        },

        render: function() {
            this.$input = this.$control.find('input:first');
            this.$select = this.$control.find('select');
            if(this.getValue() == '') {
                var valueArr = [];
            } else {
                var valueArr = this.getValue().split(",");
            }
            this.$select.val(valueArr);
            this.$select.trigger('chosen:updated');
            this.$select.setSelectionOrder(valueArr);
        }
    };

	/**
	 * $ns.Field type: textarea_html
	 */
	$ns.Field['textarea_html'] = {
		init: function(){
			if (window.tinyMCEPreInit === undefined || !this.$control.is(':visible')) {
				setTimeout(this.init.bind(this), 100);
				return;
			}
			var id = this.$input.attr('id');
			if (id.indexOf('__i__') != -1) return;
			this.$container = this.$control.find('.ns-swysiwyg');
			var curEd = tinymce.get(id);
			var content;
			if (curEd != null) {
				content = curEd.getContent();
				curEd.remove();
			}
			this.mceSettings = this.$container[0].onclick() || {};
			tinyMCEPreInit.mceInit[id] = $.extend(tinyMCEPreInit.mceInit['nootheme'] || {}, this.mceSettings, {
				selector: '#' + id,
				setup: function(editor){
					editor.on('change', function(){
						tinymce.get(id).save();
						this.$input.trigger('change');
						this.$input.val(window.switchEditors.pre_wpautop(editor.getContent()));
					}.bind(this));
					editor.on('init', function(){
						if (content) editor.setContent(content);
					});
					this.$input.on('keyup', function(){
						editor.setContent(window.switchEditors.wpautop(this.$input.val()));
					}.bind(this));
					this.editor = editor;
				}.bind(this)
			});
			// Removing NooTheme button from html field editor
			tinyMCEPreInit.mceInit[id].toolbar1 = tinyMCEPreInit.mceInit[id].toolbar1.replace(',nootheme', '');

			tinymce.init(tinyMCEPreInit.mceInit[id]);
			// Quick Tags
			tinyMCEPreInit.qtInit[id] = {id: id};
			this.$container.find('.quicktags-toolbar').remove();
			quicktags(tinyMCEPreInit.qtInit[id]);
			QTags._buttonsInit();
		},

		render: function(){
			if (this.editor === undefined) return;
			var value = this.getValue();
			this.editor.setContent(value);
		}
	};

    /**
     * $ns.Field type: colorpicker
     */
    $ns.Field['colorpicker'] = {
        init: function(){
            this.parentInit();
            this.changeTimer = null;
            this._events = {
                change: function(value){
                    clearTimeout(this.changeTimer);
                    this.changeTimer = setTimeout(function(){
                        this.$input.trigger('change');
                    }.bind(this), 100);
                }.bind(this)
            };
            this.$input.wpColorPicker({
                change: this._events.change
            });
        },
        render: function(){
            var value = this.getValue();
            this.$input.wpColorPicker('color', value);
        }
    };

	/**
	 * $ns.Field type: attach_images
	 */
	$ns.Field['attach_image'] = $ns.Field['attach_images'] = {

		init: function(){
			this.parentInit();

			this.$field = this.$control.find('.ns-imgattach');
			this.multiple = this.$field.data('multiple');
			this.$list = this.$field.find('.ns-imgattach-list');
			this.$btnAdd = this.$field.find('.ns-imgattach-add');

			this._events = {
				openMediaUploader: this.openMediaUploader.bind(this),
				deleteImg: function(event){
					$(event.target).closest('li').remove();
					this.updateInput();
				}.bind(this),
				updateInput: this.updateInput.bind(this)
			};

			if (this.multiple) {
				this.$list.sortable({stop: this._events.updateInput});
			}
			this.$btnAdd.on('click', this._events.openMediaUploader);
			this.$list.on('click', '.ns-imgattach-delete', this._events.deleteImg);
		},

		render: function(){
			var value = this.getValue(),
				items = {},
				currentIds = [],
				neededIds = value ? value.split(',').map(Number) : [];
			this.$list.children().toArray().forEach(function(item){
				var $item = $(item),
					id = parseInt($item.data('id'));
				items[id] = $item;
				currentIds.push(id);
			});
			var index = 0;
			for (index = 0; index < neededIds.length; index++) {
				var id = neededIds[index],
					currentIndex = currentIds.indexOf(id, index);
				if (currentIndex == index) continue;
				if (currentIndex == -1) {
					// Creating the new item
					var attachment = wp.media.attachment(id);
					items[id] = this.createItem(attachment);
				} else {
					// Moving existing item
					currentIds.splice(currentIndex, 1);
				}
				if (index == 0) {
					items[id].prependTo(this.$list);
				} else {
					items[id].insertAfter(items[neededIds[index - 1]]);
				}
				currentIds.splice(index, 0, id);
			}
			for (; index < currentIds.length; index++) {
				// Removing the excess items
				items[currentIds[index]].remove();
			}
		},
		updateInput: function(){
			var oldValue = this.getValue(),
				imgIds = this.$list.children().toArray().map(function(item){
					return parseInt(item.getAttribute('data-id'));
				}),
				newValue = imgIds.join(',');
			if (newValue != oldValue) {
				this.$input.val(newValue).trigger('change');
			}
		},
		openMediaUploader: function(){
			if (this.frame === undefined) {
				this.frame = wp.media({
					title: this.$btnAdd.attr('title'),
					multiple: this.multiple ? 'add' : false,
					library: {type: 'image'},
					button: {text: this.$btnAdd.attr('title')}
				});
				this.frame.on('open', function(){
					var value = this.getValue(),
						initialIds = value ? value.split(',').map(Number) : [],
						selection = this.frame.state().get('selection');
					initialIds.forEach(function(id){
						selection.add(wp.media.attachment(id));
					});
				}.bind(this));
				this.frame.on('select', function(){
					var value = this.getValue(),
						initialIds = value ? value.split(',').map(Number) : [],
						selection = this.frame.state().get('selection'),
						updatedIds = [];
					selection.forEach(function(attachment){
						if (attachment.id && initialIds.indexOf(attachment.id) == -1) {
							// Adding the new images
							this.$list.append(this.createItem(attachment));
						}
						updatedIds.push(attachment.id);
					}.bind(this));
					initialIds.forEach(function(id){
						if (updatedIds.indexOf(id) == -1) {
							// Deleting images that are not present in the recent selection
							this.$list.find('[data-id="' + id + '"]').remove();
						}
					}.bind(this));
					this.updateInput();
				}.bind(this));
			}
			this.frame.open();
		},
		/**
		 * Prepare item's dom from WP attachment object
		 * @param {Object} attachment
		 * @return {jQuery}
		 */
		createItem: function(attachment){
			if (!attachment || !attachment.attributes.id) return '';
			var html = '<li data-id="' + attachment.id + '">' +
				'<a class="ns-imgattach-delete" href="javascript:void(0)">&times;</a>' +
				'<img width="150" height="150" class="attachment-thumbnail" src="';
			if (attachment.attributes.sizes !== undefined) {
				var size = (attachment.attributes.sizes.thumbnail !== undefined) ? 'thumbnail' : 'full';
				html += attachment.attributes.sizes[size].url;
			}
			html += '"></li>';
			var $item = $(html);
			if (attachment.attributes.sizes === undefined) {
				// Loading missing image via ajax
				attachment.fetch({
					success: function(){
						var size = (attachment.attributes.sizes.thumbnail !== undefined) ? 'thumbnail' : 'full';
						$item.find('img').attr('src', attachment.attributes.sizes[size].url);
					}.bind(this)
				});
			}
			return $item;
		}

	};

    /**
     * $ns.Field type: select
     */
    $ns.Field['params_preset'] = {
    	init: function() {
    		this.parentInit();
            this._events = {
                change: function(){
                    this.render();
                }.bind(this)
            };
            this.$input.on('change', this._events.change);
		},
        render: function(){
        	var params = this.$input.find('option:selected').data('params');
        	this.setOtherParams( params );
        },
		setOtherParams: function( params ) {
            var form = this.form;
            Object.keys(params).forEach( function( key ) {
                form.fields[key].setValue( params[key] );
            } );
		}
    };

	/**
	 * $ns.Field type: vc_link
	 */
	$ns.Field['vc_link'] = {
		init: function(){
			this.$document = $(document);
			this.$btn = this.$control.find('.ns-linkdialog-btn');
			this.$linkUrl = this.$control.find('.ns-linkdialog-url');
			this.$linkTitle = this.$control.find('.ns-linkdialog-title');
			this.$linkTarget = this.$control.find('.ns-linkdialog-target');
			this._events = {
				open: function(event){
					wpLink.open(this.$input.attr('id'));
					wpLink.textarea = this.$input;
					var data = this.decodeLink(this.getValue());
					$('#wp-link-url').val(data.url);
					$('#wp-link-text').val(data.title);
					$('#wp-link-target').prop('checked', (data.target == '_blank'));
					if( ! $("#link-options .vc-link-nofollow").length ) {
                        $vc_link_nofollow = $('<div class="link-target vc-link-nofollow"><label><span></span> <input type="checkbox" id="vc-link-nofollow"> Add nofollow option to link</label></div>');
                        $vc_link_nofollow.insertAfter($("#link-options .link-target"));
                    }
                    $("#vc-link-nofollow").prop('checked', (data.rel == 'nofollow'));
					$('#wp-link-submit').on('click', this._events.submit);
					this.$document.on('wplink-close', this._events.close);
				}.bind(this),
				submit: function(event){
					event.preventDefault();
					var wpLinkText = $('#wp-link-text').val(),
						linkAtts = wpLink.getAttrs(),
						linkRel = $("#vc-link-nofollow")[0].checked ? "nofollow" : "";
					this.setValue(this.encodeLink(linkAtts.href, wpLinkText, linkAtts.target, linkRel));
					this.$input.trigger('change');
					this._events.close();
				}.bind(this),
				close: function(){
					this.$document.off('wplink-close', this._events.close);
					$('#wp-link-submit').off('click', this._events.submit);
					if (typeof wpActiveEditor != 'undefined') wpActiveEditor = undefined;
					wpLink.close();
				}.bind(this)
			};

			this.$btn.on('click', this._events.open);
		},
		render: function(){
			var value = this.getValue(),
				parts = value.split('|'),
				data = {};
			for (var i = 0; i < parts.length; i++) {
				var part = parts[i].split(':', 2);
				if (part.length > 1) data[part[0]] = decodeURIComponent(part[1]);
			}
			this.$linkTitle.text(data.title || '');
			this.$linkUrl.text(this.shortenUrl(data.url || ''));
			this.$linkTarget.text(data.target || '');
		},
		/**
		 * Get shortened version of URL with url's beginning and end
		 * @param url
		 */
		shortenUrl: function(url){
			return (url.length <= 50) ? url : (url.substr(0, 20) + '...' + url.substr(url.length - 21));
		},
		encodeLink: function(url, title, target, rel){
			var result = 'url:' + encodeURIComponent(url);
			if (title) result += '|title:' + encodeURIComponent(title);
			if (target) result += '|target:' + encodeURIComponent(target);
			if (rel) result += '|rel:' + encodeURIComponent(rel);
			return result;
		},
		decodeLink: function(link){
			var atts = link.split('|'),
				result = {url: '', title: '', target: '', rel: ''};
			atts.forEach(function(value, index){
				var param = value.split(':', 2);
				result[param[0]] = decodeURIComponent(param[1]).trim();
			});
			return result;
		}
	};

	/**
	 * $ns.Field type: iconpicker
	 */
	$ns.Field['iconpicker'] = {
		rendered: false,
		render: function(){
			if( ! this.rendered ) {
                var settings = $.extend({
                    iconsPerPage: 64,
                    iconDownClass: "fip-fa fa fa-arrow-down",
                    iconUpClass: "fip-fa fa fa-arrow-up",
                    iconLeftClass: "fip-fa fa fa-arrow-left",
                    iconRightClass: "fip-fa fa fa-arrow-right",
                    iconSearchClass: "fip-fa fa fa-search",
                    iconCancelClass: "fip-fa fa fa-remove",
                    iconBlockClass: "fip-fa"
                }, this.$input.data("settings"));
                this.$input.fontIconPicker(settings);

                this.rendered = true;
			}
		}
	};

	/**
	 * $ns.Field type: google_fonts
	 */
	$ns.Field['google_fonts'] = {
        init: function(){
            this.parentInit();
            this.$fontFamily = this.$control.find('select.ns-google_fonts-font_family-select');
            this.$fontStyle = this.$control.find('select.ns-google_fonts-font_style-select');
            this._events = {
                familyChange: function() {
                    this.renderStyle().trigger('change');
                }.bind(this),
				styleChange: function() {
                    var font_family = this.$fontFamily.val(),
                        font_style = this.$fontStyle.val();
                    font_family = ( typeof font_family === 'string' ) && 0 < font_family.length ? 'font_family' + ":" + encodeURIComponent(font_family) : '';
                    font_style = ( typeof font_style === 'string' ) && 0 < font_style.length ? 'font_style' + ":" + encodeURIComponent(font_style) : '';
                    var value = ( font_family !== '' && font_style !== '' ) ? font_family + '|' + font_style : font_family + font_style;

                    this.$input.val(value).trigger('change');
				}.bind(this)
            };
            this.$fontFamily.on('change', this._events.familyChange);
            this.$fontStyle.on('change', this._events.styleChange);
        },
        render: function(){
            var value = this.getValue(),
                parts = value.split('|'),
                data = {};
            for (var i = 0; i < parts.length; i++) {
                var part = parts[i].split(':', 2);
                if (part.length > 1) data[part[0]] = decodeURIComponent(part[1]);
            }
            this.$fontFamily.val(data.font_family || '');
            this.renderStyle().val(data.font_style || '');
        },
		renderStyle: function(){
            var $font_family_selected = this.$fontFamily.find(":selected"),
                font_types = $font_family_selected.attr("data[font_types]"),
                str_arr = font_types.split(","),
                oel = "",
                default_f_style = this.$fontFamily.attr("default[font_style]");
            for (var str_inner in str_arr) {
                var str_arr_inner = str_arr[str_inner].split(":"),
                    selected = "";
                if( ( typeof default_f_style === 'string' ) && 0 < default_f_style.length && str_arr[str_inner] == default_f_style ) {
                    selected = "selected";
                }
                oel = oel + "<option " + selected + ' value="' + str_arr[str_inner] + '" data[font_weight]="' + str_arr_inner[1] + '" data[font_style]="' + str_arr_inner[2] + '">' + str_arr_inner[0] + "</option>";
            }

            return this.$fontStyle.html(oel);
		}
	};

	/**
	 * $ns.Tabs class
	 *
	 * Boundable events: beforeShow, afterShow, beforeHide, afterHide
	 *
	 * @param container
	 * @constructor
	 */
	$ns.Tabs = function(container){
		this.$container = $(container);
		this.$items = this.$container.find('.ns-tabs-item');
		this.$sections = this.$container.find('.ns-tabs-section');
		this.items = this.$items.toArray().map($);
		this.sections = this.$sections.toArray().map($);
		this.active = 0;
		this.items.forEach(function($elm, index){
			$elm.on('click', this.open.bind(this, index));
		}.bind(this));
	};
	$.extend($ns.Tabs.prototype, $ns.mixins.Events, {
		open: function(index){
			if (index == this.active || this.sections[index] == undefined) return;
			if (this.sections[this.active] !== undefined) {
				this.trigger('beforeHide', [this.active, this.sections[this.active], this.items[this.active]]);
				this.sections[this.active].hide();
				this.items[this.active].removeClass('active');
				this.trigger('afterHide', [this.active, this.sections[this.active], this.items[this.active]]);
			}
			this.trigger('beforeShow', [index, this.sections[index], this.items[index]]);
			this.sections[index].show();
			this.items[index].addClass('active');
			this.trigger('afterShow', [index, this.sections[index], this.items[index]]);
			this.active = index;
		}
	});

	/**
	 * $ns.Form class
	 * @param container
	 * @constructor
	 */
	$ns.Form = function(container){
		this.$container = $(container);
		this.$tabs = this.$container.find('.ns-tabs');
		if (this.$tabs.length) {
			this.tabs = new $ns.Tabs(this.$tabs);
		}

		// Dependencies rules and the list of dependent fields for all the affecting fields
		this.dependency = {};
		this.affects = {};

		this.$fields = this.$container.find('.ns-form-control');
		this.fields = {};
		this.$fields.each(function(index, control){
			var $control = $(control),
				name = $control.data('param_name');
			this.fields[name] = new $ns.Field($control, this);
			this.fields[name].trigger('beforeShow');
			var $dependency = $control.find('.ns-form-control-dependency');
			if ($dependency.length) {
				var dependency = ($dependency[0].onclick() || {});
				if( dependency.element !== undefined ) {
                    this.dependency[name] = dependency;
                    if (this.affects[dependency.element] === undefined)
                    	this.affects[dependency.element] = [];
                    this.affects[dependency.element].push(name);
                }
			}
		}.bind(this));

		$.each(this.affects, function(name, affectedList){
			var onChangeFn = function(){
				for (var index = 0; index < affectedList.length; index++) {
                    if (this.shouldBeVisible(affectedList[index])) {
                        this.fields[affectedList[index]].$control.show();
                        this.fields[affectedList[index]].$control.removeClass('hide');
                    } else {
                        this.fields[affectedList[index]].$control.hide();
                        this.fields[affectedList[index]].$control.addClass('hide');
                    }
                    if (this.dependency[affectedList[index]].callback !== undefined && window[this.dependency[affectedList[index]].callback] !== undefined) {
                        window[this.dependency[affectedList[index]].callback]();
                    }
				}
			}.bind(this);
			this.fields[name].bind('change', onChangeFn);
			onChangeFn();
		}.bind(this));
	};
	$.extend($ns.Form.prototype, {
		/**
		 * Get a particular field value
		 * @param {String} name Field name
		 * @return {String}
		 */
		getValue: function(name){
			return (this.fields[name] === undefined) ? null : this.fields[name].getValue();
		},
		setValue: function(name, value){
			if (this.fields[name] !== undefined) this.field[name].setValue(value);
		},
		getValues: function(){
			var values = {};
			$.each(this.fields, function(name, field){
				values[name] = field.getValue();
			}.bind(this));
			return values;
		},
		setValues: function(values){
			$.each(values, function(name, value){
				if (this.fields[name] !== undefined) this.fields[name].setValue(value);
			}.bind(this));
		},
		/**
		 * Check if the field should be visible
		 * @param {String} name
		 * @return {Boolean}
		 */
		shouldBeVisible: function(name){
            if (this.dependency[name] === undefined) return true;
            var dep = this.dependency[name],
                value = this.fields[dep.element].getValue();
            if (dep.not_empty !== undefined) {
                return value !== undefined && value !== null && value.length > 0;
            }
            if (dep.value !== undefined) {
                if (dep.value instanceof Array) {
                    if( dep.value.indexOf(value) != -1 && !this.fields[dep.element].$control.hasClass('hide') ) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if( value == dep.value && !this.fields[dep.element].$control.hasClass('hide')) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
            return true;
		}
	});

	/**
	 * $ns.Elist class: A popup with elements list to choose from. Behaves as a singleton.
	 * Boundable events: beforeShow, afterShow, beforeHide, afterHide, select
	 * @constructor
	 */
	$ns.ShortcodeList = function(){
		if ($ns.shortcodelist !== undefined) return $ns.shortcodelist;
		this.$container = $('.ns-shortcode-list');
		if (this.$container.length > 0) this.init();
	};
	$.extend($ns.ShortcodeList.prototype, $ns.mixins.Events, {
		init: function(){
			this.$closer = this.$container.find('.ns-shortcode-list-closer');
			this.$list = this.$container.find('.ns-shortcode-list-list');
			this._events = {
				select: function(event){
					var $item = $(event.target).closest('.ns-shortcode-list-item');
					this.hide();
					this.trigger('select', [$item.data('name')]);
				}.bind(this),
				hide: this.hide.bind(this)
			};
			this.$closer.on('click', this._events.hide);
			this.$list.on('click', '.ns-shortcode-list-item', this._events.select);
		},
		show: function(){
			if (this.$container.length == 0) {
				// Loading elements list html via ajax
				$.ajax({
					type: 'post',
					url: $ns.ajaxUrl,
					data: {
						action: 'ns_get_shortcode_list_html'
					},
					success: function(html){
						this.$container = $(html).css('display', 'none').appendTo($(document.body));
						this.init();
						this.show();
					}.bind(this)
				});
				return;
			}

			this.trigger('beforeShow');
			this.$container.css('display', 'block');
			this.trigger('afterShow');
		},
		hide: function(){
			this.trigger('beforeHide');
			this.$container.css('display', 'none');
			this.trigger('afterHide');
		},
		load: function(){
            if (this.$container.length == 0) {
                // Loading elements list html via ajax
                $.ajax({
                    type: 'post',
                    url: $ns.ajaxUrl,
                    data: {
                        action: 'ns_get_shortcode_list_html'
                    },
                    success: function(html){
                        this.$container = $(html).css('display', 'none').appendTo($(document.body));
                        this.init();

                        // Preload to save time
                        $ns.builder.load();
                    }.bind(this)
                });
                return;
            }
		}
	});
	// Singleton instance
	$ns.shortcodelist = new $ns.ShortcodeList;

	// Preload to save time
	setTimeout( function() {
		$ns.shortcodelist.load();
	}, 10000);

	/**
	 * $ns.Builder class: A popup with loadable elements forms
	 * Boundable events: beforeShow, afterShow, beforeHide, afterHide, save
	 * @constructor
	 */
	$ns.Builder = function(){
		this.$container = $('.ns-builder');
		if (this.$container.length != 0) this.init();
	};
	$.extend($ns.Builder.prototype, $ns.mixins.Events, {
		init: function(){
			this.$title = this.$container.find('.ns-builder-title');
			this.titles = this.$title[0].onclick() || {};
			this.$title.removeAttr('onclick');
			this.$closer = this.$container.find('.ns-builder-closer, .ns-builder-btn.for_close');
			// Form containers and class instances
			this.$forms = {};
			this.forms = {};
			// Set of default values for each elements form
			this.defaults = {};
			this.$container.find('.ns-form').each(function(index, form){
				var $form = $(form).css('display', 'none'),
					name = $form.data('shortcode');
				this.$forms[name] = $form;
			}.bind(this));
			this.$btnSave = this.$container.find('.ns-builder-btn.for_save');
			// Active element
			this.active = false;
			this._events = {
				hide: this.hide.bind(this),
				save: this.save.bind(this)
			};
			this.$closer.on('click', this._events.hide);
			this.$btnSave.on('click', this._events.save);
		},
		/**
		 * Show element form for a specified element name and initial values
		 * @param {String} name
		 * @param {Object} values
		 */
		show: function(name, values){
			if (this.$container.length == 0) {
				// Loading builder and initial form's html
				$.ajax({
					type: 'post',
					url: $ns.ajaxUrl,
					data: {
						action: 'ns_get_builder_html'
					},
					success: function(html){
						if (html == '') return;
						html = $ns.fn.enqueueAssets(html);
						this.$container = $(html).css('display', 'none').appendTo($(document.body));
						this.init();
						this.show(name, values);
					}.bind(this)
				});
				return;
			}

			if (this.forms[name] === undefined) {
				// Initializing Form on the first show
				if (this.$forms[name] === undefined) return;
				this.forms[name] = new $ns.Form(this.$forms[name]);
				this.defaults[name] = this.forms[name].getValues();
			}
			// Filling missing values with defaults
			values = $.extend({}, this.defaults[name], values);
			this.forms[name].setValues(values);
			if (this.forms[name].tabs !== undefined) this.forms[name].tabs.open(0);
			this.$forms[name].css('display', 'block');
			this.$title.html(this.titles[name] || '');
			this.active = name;
			this.trigger('beforeShow');
			this.$container.css('display', 'block');
			this.trigger('afterShow');
		},
		hide: function(){
			this.trigger('beforeHide');
			this.$container.css('display', 'none');
			if (this.$forms[this.active] !== undefined) this.$forms[this.active].css('display', 'none');
			this.trigger('afterHide');
		},
		load: function(){
            if (this.$container.length == 0) {
                // Loading builder and initial form's html
                $.ajax({
                    type: 'post',
                    url: $ns.ajaxUrl,
                    data: {
                        action: 'ns_get_builder_html'
                    },
                    success: function(html){
                        if (html == '') return;
                        html = $ns.fn.enqueueAssets(html);
                        this.$container = $(html).css('display', 'none').appendTo($(document.body));
                        this.init();
                    }.bind(this)
                });
                return;
            }
		},
		/**
		 * Get values of the active form
		 * @return {Object}
		 */
		getValues: function(){
			return (this.forms[this.active] !== undefined) ? this.forms[this.active].getValues() : {};
		},
		/**
		 * Get default values of the active form
		 * @return {Object}
		 */
		getDefaults: function(){
			return (this.defaults[this.active] || {});
		},
		save: function(){
			this.hide();
			this.trigger('save', [this.getValues(), this.getDefaults()]);
		}
	});
	// Singletone instance
	$ns.builder = new $ns.Builder;

}(jQuery);

// Helper functions
!function($){
	if ($ns.fn === undefined) $ns.fn = {};
	/**
	 * Retrieve all attributes from the shortcodes tag. (WordPress-function js analog).
	 * @param text
	 * @return {Array} List of attributes and their value
	 */
	$ns.fn.shortcodeParseAtts = function(text){
		// Fixing tinymce transformations
		text = text.replace(/\<br \/\> /g, "\n");
		var atts = {};
		text.replace(/([a-z0-9_\-]+)=\"([^\"\]]*)"/g, function(m, key, value){
			atts[key] = value;
		});
		return atts;
	};
	/**
	 * Generate shortcode string
	 * @param {String} name Shortcode name
	 * @param {{}} atts
	 * @param {{}} attsDefaults
	 * @return {String}
	 */
	$ns.fn.generateShortcode = function(name, atts, attsDefaults){
		var shortcode = '[' + name,
			htmlContent = ($ns.elements[name] && $ns.elements[name].params.content && $ns.elements[name].params.content.type == 'html');
		atts = atts || {};
		attsDefaults = attsDefaults || {};
		$.each(atts, function(att, value){
			if (htmlContent && att == 'content') return;
			if (attsDefaults[att] !== undefined && attsDefaults[att] !== value) shortcode += ' ' + att + '="' + value + '"';
		});
		shortcode += ']';
		if (htmlContent) shortcode += (atts.content || '') + '[/' + name + ']';
		return shortcode;
	};
	/**
	 * Handle "nootheme" action within a plain text and determine what will be the new selection and the way it should
	 * be handled (insert / edit)
	 *
	 * @param {String} html Initial html text with shortcodes
	 * @param {Number} startOffset Selection start offset
	 * @param {Number} endOffset
	 * @return {{}} action, new selection, shortcode data (if found)
	 */
	$ns.fn.handleShortcodeCall = function(html, startOffset, endOffset){
		var handler = {};
		if (typeof html != 'string') html = '';
		if (startOffset == -1) startOffset = 0;
		if (endOffset == -1) endOffset = startOffset;
		if (endOffset < startOffset) {
			// Swapping start and end positions
			endOffset = startOffset + (startOffset = endOffset) - endOffset;
		}
		// If user selected a shortcode or its part
		if (startOffset < endOffset && html[endOffset - 1] == ']') {
			endOffset--;
		}
		var prevOpen = html.lastIndexOf('[', endOffset - 1),
			prevClose = html.lastIndexOf(']', endOffset - 1),
			nextOpen = html.indexOf('[', endOffset),
			nextClose = html.indexOf(']', endOffset),
		// We may fall back to insert at any time, so creating a separate variable for this
			insertHandler = {
				action: 'insert',
				selection: [startOffset, endOffset]
			};
		// Checking out if we're inside of some tag at all
		if (prevOpen == -1 || nextClose == -1 || prevOpen < prevClose || (nextOpen != -1 && nextOpen < nextClose)) {
			return insertHandler;
		}
		// If we're still here, the cursor is inside of some shorcode, so in case of insertion, we'll insert right after it
		insertHandler.selection = [nextClose + 1, nextClose + 1];
		var isOpener = (html.charAt(prevOpen + 1) != '/'),
			editHandler = {
				action: 'edit',
				shortcode: html.substring(prevOpen + (isOpener ? 1 : 2), nextClose + 1).replace(/^([a-zA-Z0-9\-\_]+)[^\[]+/, '$1')
			};
		// Handling only known shortcodes
		if ($ns.elements[editHandler.shortcode] === undefined) return insertHandler;
		var nestingLevel = 1,
			regexp = new RegExp('\\[(\\/?)' + editHandler.shortcode.replace(/\-/g, '\\$&') + '((?=\\])| [^\\]]+)', 'ig'),
			matches;
		if (isOpener) {
			// Opening shortcode: searching forward
			editHandler.values = $ns.fn.shortcodeParseAtts(html.substring(prevOpen, nextClose + 1));
			regexp.lastIndex = nextClose;
			while (matches = regexp.exec(html)) {
				nestingLevel += (matches[1] ? -1 : 1);
				if (nestingLevel == 0) {
					// Found the relevant closer
					editHandler.selection = [prevOpen, html.indexOf(']', regexp.lastIndex - matches[0].length + 1) + 1];
					editHandler.values.content = html.substring(nextClose + 1, regexp.lastIndex - matches[0].length);
					break;
				}
			}
			if (nestingLevel != 0) {
				// No shortcode closer
				editHandler.selection = [prevOpen, nextClose + 1];
			}
		} else {
			// Closing shortcode: searching backward
			var nestingChange = [],
				matchesPos = [];
			while (matches = regexp.exec(html)) {
				if (regexp.lastIndex >= prevOpen) break;
				nestingChange.push(matches[1] ? 1 : -1);
				matchesPos.push(regexp.lastIndex - matches[0].length);
			}
			for (var i = nestingChange.length - 1; i >= 0; i--) {
				nestingLevel += nestingChange[i];
				if (nestingLevel == 0) {
					var openerClose = html.indexOf(']', matchesPos[i]);
					editHandler.selection = [matchesPos[i], nextClose + 1];
					editHandler.values = $ns.fn.shortcodeParseAtts(html.substring(matchesPos[i], openerClose));
					editHandler.values.content = html.substring(openerClose + 1, prevOpen);
					break;
				}
			}
			if (nestingLevel != 0) {
				// Closing shortcode with no opening one: inserting right after it
				return insertHandler;
			}
		}

		return editHandler;
	};
	/**
	 * Parse ajax HTML, insert the needed assets and filter html from them
	 * @param {String} html
	 * @returns {String}
	 * TODO Rework this function to properly handle load-scripts.php
	 */
	$ns.fn.enqueueAssets = function(html){
		var regexp = /(\<link rel=\'stylesheet\' id=\'([^\']+)\'[^\>]+?\>)|(\<style type\=\"text\/css\"\>([^\<]*)\<\/style\>)|(\<script type=\'text\/javascript\' src=\'([^\']+)\'\><\/script\>)|(\<script type\=\'text\/javascript\'\>([^`]*?)\<\/script\>)/g;
		var $head = $(document.head),
			$internalStyles = $('style'),
			$externalScripts = $('script[src]'),
			$internalScripts = $('script:not([src])'),
			i;
		// Inserting only the assets that are not exist on a page yet
		return html.replace(regexp, function(m, m1, styleId, m2, styleContent, m3, scriptSrc, m4, scriptContent){
			if (m.indexOf('<link rel=\'stylesheet\'') == 0) {
				// External style
				if ($('link[rel="stylesheet"]#' + styleId).length != 0) return '';
			} else if (m.indexOf('<style') == 0) {
				// Internal style
				styleContent = styleContent.trim();
				for (i = 0; i < $internalStyles.length; i++) {
					if ($internalStyles[i].innerHTML.trim() == styleContent) return '';
				}
			} else if (m.indexOf('<script type=\'text/javascript\' src=\'') == 0) {
				// External script
				scriptSrc = scriptSrc.replace(/&_=[0-9]+/, '');
				for (i = 0; i < $externalScripts.length; i++) {
					if ($externalScripts[i].src.indexOf(scriptSrc) === 0) return '';
				}
			} else {
				// Internal script
				scriptContent = scriptContent.trim();
				for (i = 0; i < $internalScripts.length; i++) {
					if ($internalScripts[i].innerHTML.trim() == scriptContent) return '';
				}
			}
			$(m).appendTo($head);
			return '';
		});
	};

    $ns.fn.rawurldecode = function(str) {
        return decodeURIComponent(str + '');
    };
    $ns.fn.rawurlencode = function(str) {
        str = (str + '').toString();
        return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A');
    };
    $ns.fn.utf8_decode = function(str_data) {
        var tmp_arr = [],
            i = 0,
            ac = 0,
            c1 = 0,
            c2 = 0,
            c3 = 0;
        str_data += '';
        while (i < str_data.length) {
            c1 = str_data.charCodeAt(i);
            if (c1 < 128) {
                tmp_arr[ac++] = String.fromCharCode(c1);
                i++;
            } else if (c1 > 191 && c1 < 224) {
                c2 = str_data.charCodeAt(i + 1);
                tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = str_data.charCodeAt(i + 1);
                c3 = str_data.charCodeAt(i + 2);
                tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return tmp_arr.join('');
    };
    $ns.fn.utf8_encode = function(argString) {
        if (argString === null || typeof argString === "undefined") {
            return "";
        }
        var string = (argString + '');
        var utftext = "",
            start, end, stringl = 0;
        start = end = 0;
        stringl = string.length;
        for (var n = 0; n < stringl; n++) {
            var c1 = string.charCodeAt(n);
            var enc = null;
            if (c1 < 128) {
                end++;
            } else if (c1 > 127 && c1 < 2048) {
                enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
            } else {
                enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
            }
            if (enc !== null) {
                if (end > start) {
                    utftext += string.slice(start, end);
                }
                utftext += enc;
                start = end = n + 1;
            }
        }
        if (end > start) {
            utftext += string.slice(start, stringl);
        }
        return utftext;
    };
    $ns.fn.base64_decode = function(data) {
        var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
            ac = 0,
            dec = "",
            tmp_arr = [];
        if (!data) {
            return data;
        }
        data += '';
        do {
            h1 = b64.indexOf(data.charAt(i++));
            h2 = b64.indexOf(data.charAt(i++));
            h3 = b64.indexOf(data.charAt(i++));
            h4 = b64.indexOf(data.charAt(i++));
            bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;
            o1 = bits >> 16 & 0xff;
            o2 = bits >> 8 & 0xff;
            o3 = bits & 0xff;
            if (h3 == 64) {
                tmp_arr[ac++] = String.fromCharCode(o1);
            } else if (h4 == 64) {
                tmp_arr[ac++] = String.fromCharCode(o1, o2);
            } else {
                tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
            }
        } while (i < data.length);
        dec = tmp_arr.join('');
        dec = $ns.fn.utf8_decode(dec);
        return dec;
    };
    $ns.fn.base64_encode = function(data) {
        var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
            ac = 0,
            enc = "",
            tmp_arr = [];
        if (!data) {
            return data;
        }
        data = $ns.fn.utf8_encode(data + '');
        do {
            o1 = data.charCodeAt(i++);
            o2 = data.charCodeAt(i++);
            o3 = data.charCodeAt(i++);
            bits = o1 << 16 | o2 << 8 | o3;
            h1 = bits >> 18 & 0x3f;
            h2 = bits >> 12 & 0x3f;
            h3 = bits >> 6 & 0x3f;
            h4 = bits & 0x3f;
            tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
        } while (i < data.length);
        enc = tmp_arr.join('');
        var r = data.length % 3;
        return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
    };
}(jQuery);
