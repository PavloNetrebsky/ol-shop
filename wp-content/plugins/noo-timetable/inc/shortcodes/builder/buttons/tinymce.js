!function($){
	tinymce.create('tinymce.plugins.nootheme', {
		init: function(ed, url){

			ed.addButton('nootheme', {
				title: 'Insert / Edit NooTheme Shortcode',
				cmd: 'nootheme',
				image: url + '/icon.png'
			});
			this.ed = ed;
			this.url = url;

			this._events = {
				disableUndo: function(e){
					return false
				}
			};

			var btnAction = function(){
				var textSelection = this.getTextSelection(),
					handler = $ns.fn.handleShortcodeCall.apply(window, textSelection);
				if (handler.selection !== undefined) {
					// Updating selection: seeking DOM elements for each selection part
					this.applySelection(handler.selection[0], handler.selection[1]);
				}
				if (handler.action === 'insert') {
					$ns.shortcodelist.unbind('select').bind('select', function(name){
                        var content = ed.getContent({format: 'html'}),
                            startTrigger = '!ns-selection-start!',
                            endTrigger = '!ns-selection-end!',
                            newContent = content.substr(0, handler.selection[0]) + startTrigger + content.substring(handler.selection[0], handler.selection[1]) + endTrigger + content.substr(handler.selection[1]);
                        ed.setContent(newContent);
                        // Looking for selection triggers
                        var startContainer, startOffset,
                            endContainer, endOffset,
                            nodeWalker = document.createTreeWalker(ed.getBody(), NodeFilter.SHOW_TEXT, null, false),
                            node;
                        while (node = nodeWalker.nextNode()) {
                            if (!startContainer) {
                                if ((startOffset = node.nodeValue.indexOf(startTrigger)) !== -1) {
                                    node.nodeValue = node.nodeValue.substr(0, startOffset) + node.nodeValue.substr(startOffset + startTrigger.length);
                                    startContainer = node;
                                }
                            }
                            if (!endContainer) {
                                if ((endOffset = node.nodeValue.indexOf(endTrigger)) !== -1) {
                                    node.nodeValue = node.nodeValue.substr(0, endOffset) + node.nodeValue.substr(endOffset + endTrigger.length);
                                    endContainer = node;
                                }
                            }
                        }
                        if (startContainer) {
                            if (endContainer) {
                                var rng = ed.selection.getRng();
                                rng.setStart(startContainer, startOffset);
                                rng.setEnd(endContainer, endOffset);
                                ed.selection.setRng(rng);
                            } else {
                                ed.selection.setCursorLocation(startContainer, startOffset);
                            }
                            // Restoring textarea default value
                            ed.getElement().value = content;
                        } else {
                            ed.setContent(content);
                        }
						ed.insertContent($ns.fn.generateShortcode(name));
						var range = ed.selection.getRng();
						ed.selection.setCursorLocation(range.endContainer, range.endOffset - 1);
						btnAction();
					});
					$ns.shortcodelist.show();
				} else if (handler.action === 'edit') {

					$ns.builder.unbind('save').bind('save', function(values, defaults){
						var shortcode = $ns.fn.generateShortcode(handler.shortcode, values, defaults);
						shortcode = shortcode.replace(/\n/g, '<br> ');

						/*****/
                        var content = ed.getContent({format: 'html'}),
                            startTrigger = '!ns-selection-start!',
                            endTrigger = '!ns-selection-end!',
                            newContent = content.substr(0, handler.selection[0]) + startTrigger + content.substring(handler.selection[0], handler.selection[1]) + endTrigger + content.substr(handler.selection[1]);
                        ed.setContent(newContent);
                        // Looking for selection triggers
                        var startContainer, startOffset,
                            endContainer, endOffset,
                            nodeWalker = document.createTreeWalker(ed.getBody(), NodeFilter.SHOW_TEXT, null, false),
                            node;
                        while (node = nodeWalker.nextNode()) {
                            if (!startContainer) {
                                if ((startOffset = node.nodeValue.indexOf(startTrigger)) !== -1) {
                                    node.nodeValue = node.nodeValue.substr(0, startOffset) + node.nodeValue.substr(startOffset + startTrigger.length);
                                    startContainer = node;
                                }
                            }
                            if (!endContainer) {
                                if ((endOffset = node.nodeValue.indexOf(endTrigger)) !== -1) {
                                    node.nodeValue = node.nodeValue.substr(0, endOffset) + node.nodeValue.substr(endOffset + endTrigger.length);
                                    endContainer = node;
                                }
                            }
                        }
                        if (startContainer) {
                            if (endContainer) {
                                var rng = ed.selection.getRng();
                                rng.setStart(startContainer, startOffset);
                                rng.setEnd(endContainer, endOffset);
                                ed.selection.setRng(rng);
                            } else {
                                ed.selection.setCursorLocation(startContainer, startOffset);
                            }
                            // Restoring textarea default value
                            ed.getElement().value = content;
                        } else {
                            ed.setContent(content);
                        }
                        /*****/
						ed.insertContent(shortcode);
					});
					$ns.builder.show(handler.shortcode, handler.values);
				}
			}.bind(this);

			ed.addCommand('nootheme', btnAction);
		},

		/**
		 * Temporarily disable TinyMCE undo manager
		 */
		disableUndo: function(){
			this.ed.on('BeforeAddUndo', this._events.disableUndo);
		},

		/**
		 * Restore TinyMCE undo manager
		 */
		enableUndo: function(){
			this.ed.off('BeforeAddUndo', this._events.disableUndo);
		},

		/**
		 * Gets plain text representation of the current selection range and selection range positions within this
		 * plain text.
		 *
		 * @return {Array}
		 */
		getTextSelection: function(){
			var range = this.ed.selection.getRng(),
				startTrigger = '!ns-selection-start!',
				endTrigger = '!ns-selection-end!',
				content = this.ed.getContent({format: 'html'}),
				startOffset, endOffset;
			this.disableUndo();
			this.ed.selection.setContent(startTrigger + this.ed.selection.getContent() + endTrigger);
			startOffset = this.ed.getContent().indexOf(startTrigger);
			endOffset = this.ed.getContent().indexOf(endTrigger);
			if (startOffset !== -1 && endOffset !== -1 && endOffset > startOffset) endOffset -= startTrigger.length;
			this.ed.setContent(content);
			this.enableUndo();
			return [content, startOffset, endOffset];
		},

		/**
		 * Apply selection
		 *
		 * @param {Number} start Range start in the html representation
		 * @param {Number} end Range end in the html representation
		 */
		applySelection: function(start, end){
			var content = this.ed.getContent({format: 'html'}),
				startTrigger = '!ns-selection-start!',
				endTrigger = '!ns-selection-end!',
				newContent = content.substr(0, start) + startTrigger + content.substring(start, end) + endTrigger + content.substr(end);
			this.disableUndo();
			this.ed.setContent(newContent);
			// Looking for selection triggers
			var startContainer, startOffset,
				endContainer, endOffset,
				nodeWalker = document.createTreeWalker(this.ed.getBody(), NodeFilter.SHOW_TEXT, null, false),
				node;
			while (node = nodeWalker.nextNode()) {
				if (!startContainer) {
					if ((startOffset = node.nodeValue.indexOf(startTrigger)) !== -1) {
						node.nodeValue = node.nodeValue.substr(0, startOffset) + node.nodeValue.substr(startOffset + startTrigger.length);
						startContainer = node;
					}
				}
				if (!endContainer) {
					if ((endOffset = node.nodeValue.indexOf(endTrigger)) !== -1) {
						node.nodeValue = node.nodeValue.substr(0, endOffset) + node.nodeValue.substr(endOffset + endTrigger.length);
						endContainer = node;
					}
				}
			}
			if (startContainer) {
				if (endContainer) {
					var rng = this.ed.selection.getRng();
					rng.setStart(startContainer, startOffset);
					rng.setEnd(endContainer, endOffset);
					this.ed.selection.setRng(rng);
				} else {
					this.ed.selection.setCursorLocation(startContainer, startOffset);
				}
				// Restoring textarea default value
				this.ed.getElement().value = content;
			} else {
				this.ed.setContent(content);
			}
			this.enableUndo();
		},

		getInfo: function(){
			return {
				longname: 'NooTheme Shortcodes',
				author: 'NooTheme',
				authorurl: 'https://nootheme.com/',
				infourl: 'https://nootheme.com/',
				version: '1.0'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('nootheme', tinymce.plugins.nootheme);
}(jQuery);
