/**
 *  
 * @package    YoloTheme/Yolo BeHealth
 * @version    1.0.0
 * @author     Administrator <yolotheme@vietbrain.com>
 * @copyright  Copyright (c) 2015, YoloTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://yolotheme.com
*/

jQuery( document ).ready( function ( $ ) {
    if ($('#portfolio-select').length > 0) {
        // Add class for the child boxes
        
        // Image
        
        $('#noo_post_format_image').addClass('post-formats post-format-image');
        //Link
        
        $('#noo_post_format_link').addClass('post-formats post-format-link');

        // Gallery
        $('#noo_post_format_gallery').addClass('post-formats post-format-gallery');

        // Video
        $('#noo_post_format_video').addClass('post-formats post-format-video');
       

        // Show the active format type
        var checkedElement = $('#portfolio-select').find('input:checked');

        

        $('.post-formats').hide();
        $('#noo_post_format_' + checkedElement.val()).show();

        // When click, display the according format type.
        $('#portfolio-select').find('input').click(function () {

            var $this = $(this);
            var selected = $this.val();

            $('.post-formats').hide();
            $('#noo_post_format_' + selected).show();
        });
    }
} );
