// Note: this file is embedded inline
!function($){
	/**
	 * Insert specified content instead of selection
	 * @param {HTMLTextAreaElement} textarea
	 * @param {String} content
	 */
	var insertContent = function(textarea, content){
		var start = Math.min(textarea.selectionStart, textarea.selectionEnd),
			end = Math.max(textarea.selectionStart, textarea.selectionEnd);
		textarea.value = textarea.value.substr(0, start) + content + textarea.value.substr(end);
	};
	var btnAction = function(btn, textarea, ed){
		var handler = $ns.fn.handleShortcodeCall(textarea.value, textarea.selectionStart, textarea.selectionEnd);
		if (handler.selection !== undefined) {
			textarea.setSelectionRange(handler.selection[0], handler.selection[1]);
		}
		if (handler.action == 'insert') {
			$ns.shortcodelist.unbind('select').bind('select', function(name){
				insertContent(textarea, $ns.fn.generateShortcode(name));
				textarea.setSelectionRange(textarea.selectionEnd - 1, textarea.selectionEnd - 1);
				btnAction(btn, textarea, ed);
			});
			$ns.shortcodelist.show();
		} else if (handler.action == 'edit') {
			$ns.builder.unbind('save').bind('save', function(values, defaults){
				var shortcode = $ns.fn.generateShortcode(handler.shortcode, values, defaults);
				insertContent(textarea, shortcode);
			});
			$ns.builder.show(handler.shortcode, handler.values);
		}
	};

	QTags.addButton('nootheme', 'NooTheme', btnAction);

}(jQuery);
