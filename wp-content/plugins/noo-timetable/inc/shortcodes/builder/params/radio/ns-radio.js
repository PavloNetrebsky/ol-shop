(function ( $ ) {
	$( function () {

		/*
		 Script for Visual Composer, using the vc.atts variable.
		 */
		if( window.vc.atts !== undefined && window.vc.atts.radio === undefined ) {
            window.vc.atts.radio = {
                /**
                 * Used to save multiple values in single string for saving/parsing/opening
                 * @param param
                 * @returns {string}
                 */
                parse: function (param) {
                    var newValue;

                    newValue = '';
                    $('input[name=' + param.param_name + ']', this.content()).each(function () {
                        var self;

                        self = $(this);
                        if (this.checked) {
                            newValue = self.attr('value');
                        }
                    });
                    return newValue;
                },
                /**
                 * Used in shortcode saving
                 * Default: '' empty (unchecked)
                 * Can be overwritten by 'std'
                 * @param param
                 * @returns {string}
                 */
                defaults: function (param) {
                    return ''; // needed for saving - without this default value for param will be first value in array
                }
            };
        }

	} );
})( window.jQuery );