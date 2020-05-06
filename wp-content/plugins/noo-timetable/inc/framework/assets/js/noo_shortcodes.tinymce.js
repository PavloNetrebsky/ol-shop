
(function() {
  tinymce.PluginManager.add('noo_shortcodes', function(editor, url) {

    // if ( tinyMCE.activeEditor.id != 'content' ) {
    //   return null;
    // }

    url = noo_shortcodes_data.url;    
    jQuery.ajaxSetup ({
      // Disable caching of AJAX responses
      cache: false
    });


    var initShortcodeTB = function(title, data) {
      var form = jQuery(data);
      form.appendTo('body').hide();
      var width = jQuery(window).width(), W = ( 720 < width ) ? 640 : width - 80;
      var H = jQuery(window).height() - 100;
      
      tb_show( title, '#TB_inline?width=' + W + '&height=' + H + '&inlineId=noo-shortcodes-form-wrapper' );
      jQuery('#TB_window .noo-form-body').css('max-height', (H - 80) + 'px');
      jQuery('#TB_window .noo-color-picker').toggleClass('inline_block');
      jQuery('#TB_window .noo-color-picker').wpColorPicker();
      jQuery('#TB_window .noo-slider').each(function() {
        var $this = jQuery(this);

        var $slider = jQuery('<div>', {id: $this.attr("id") + "-slider"}).insertAfter($this);
        $slider.slider(
          {
            range: "min",
            value: $this.val() || $this.data('min') || 0,
            min: $this.data('min') || 0,
            max: $this.data('max') || 100,
            step: $this.data('step') || 1,
            slide: function(event, ui) {
              $this.val(ui.value).attr('value', ui.value);
            }
          }
        );

        $this.change(function() {
          $slider.slider( "option", "value", $this.val() );
        });
      });

      jQuery('#TB_window .noo-date-picker').datetimepicker({
          format: 'Y-m-d',
          scrollInput: false,
          timepicker: false,
          datepicker: true,
      });
      
      //font awesome dialog
      jQuery('#TB_window .noo-fontawesome-dialog').click(function(e){
        initIconsDialog(jQuery(this));
        iconsDialogShow();
        e.preventDefault();
        e.stopPropagation();
      });

      jQuery('#TB_window #noo-cancel-shortcodes').click(function () {
        tb_remove();
      });      
    };

    var menu = [
      
      {
        text: noo_shortcodes_str.schedule,
        onclick: function() {
          jQuery("#idIconsDialog").remove();
          jQuery("#noo-shortcodes-form-wrapper").remove();

          jQuery.get(url + "/noo_shortcodes_schedule.php", function(data){
            
            initShortcodeTB(noo_shortcodes_str.schedule, data);

            jQuery('#TB_window #noo-save-shortcodes').click(function(){
              var title = jQuery('#TB_window #title').val();
              var sub_title = jQuery('#TB_window #sub_title').val();

              var source = jQuery('#TB_window #source').val();

              var default_view = jQuery('#TB_window #default_view option:selected').val();

              var show_filter = jQuery('#TB_window #show_filter option:selected').val();
              
              var min_time = jQuery('#TB_window #min_time option:selected').val();
              var max_time = jQuery('#TB_window #max_time option:selected').val();
              var hide_time_range = jQuery('#TB_window #hide_time_range option:selected').map(function(){ return this.value; }).get().join(",");
              var content_height = jQuery('#TB_window #content_height').val();

              var class_categories = jQuery('#TB_window #class_categories option:selected').map(function(){ return this.value; }).get().join(",");
              var event_categories = jQuery('#TB_window #event_categories option:selected').map(function(){ return this.value; }).get().join(",");

              var show_weekends = jQuery('#TB_window #show_weekends option:selected').val();
              var show_time_column = jQuery('#TB_window #show_time_column option:selected').val();
              var show_export = jQuery('#TB_window #show_export option:selected').val();

              var show_toolbar = jQuery('#TB_window #show_toolbar option:selected').val();
              var show_day = jQuery('#TB_window #show_day option:selected').val();

              var custom_default_date = jQuery('#TB_window #custom_default_date').is(":checked");
              var default_date = jQuery('#TB_window #default_date').val();
              var show_popup = jQuery('#TB_window #show_popup option:selected').val();

              var popup_style = jQuery('#TB_window #popup_style option:selected').val();

              var class_show_category = jQuery('#TB_window #class_show_category option:selected').val();
              var class_item_style = jQuery('#TB_window #class_item_style option:selected').val();
              var class_show_icon = jQuery('#TB_window #class_show_icon option:selected').val();
              
              var event_item_style = jQuery('#TB_window #event_item_style option:selected').val();
              var event_show_icon = jQuery('#TB_window #event_show_icon option:selected').val();

              var header_background = jQuery('#TB_window #header_background').val();
              var header_color = jQuery('#TB_window #header_color').val();
              var today_column = jQuery('#TB_window #today_column').val();

              var el_class = jQuery('#TB_window #class').val();



              tb_remove();

              var shortcode = '[ntt_schedule';
              if(title !== "")
                shortcode += ' title="' + title + '"';
              if(sub_title !== "")
                shortcode += ' sub_title="' + sub_title + '"';

              shortcode += ' source="' + source + '"';

              shortcode += ' default_view="' + default_view + '"';

              if( source !== 'both' && show_filter !=='default' )
                shortcode += ' show_filter="' + show_filter + '"';

              if (source === 'class')
                shortcode += ' class_cat="' + class_categories + '"';

              if (source === 'event')
                shortcode += ' event_cat="' + event_categories + '"';
              
              if (default_view === 'agendaWeek' || default_view === 'agendaDay') {
                shortcode += ' min_time="' + min_time + '"';
                shortcode += ' max_time="' + max_time + '"';
                shortcode += ' hide_time_range="' + hide_time_range + '"';

                if( content_height !== "" && !isNaN(content_height) ) {
                  shortcode += ' content_height="' + content_height + '"';
                }

                if( show_time_column !=='default' )
                  shortcode += ' show_time_column="' + show_time_column + '"';
              }

              if( show_weekends !=='default' )
                shortcode += ' show_weekends="' + show_weekends + '"';
              
              if( show_export !=='default' )
                shortcode += ' show_export="' + show_export + '"';

              if( show_toolbar !=='default' )
                shortcode += ' general_header_toolbar="' + show_toolbar + '"';

              if( show_day !=='default' )
                shortcode += ' general_header_day="' + show_day + '"';

              if (custom_default_date){
                shortcode += ' custom_general_default_date="true"';
                if (default_date)
                  shortcode += ' general_default_date="' + default_date + '"';
              }
              
              if( show_popup !=='default' )
                shortcode += ' general_popup="' + show_popup + '"';
              if ( show_popup === 'yes' && popup_style !=='default' ) {
                shortcode += ' general_popup_style="' + popup_style + '"';
              }

              if (source === 'class') {

                if( class_show_category !=='default' )
                  shortcode += ' class_show_category="' + class_show_category + '"';

                if( class_item_style !=='default' )
                  shortcode += ' class_item_style="' + class_item_style + '"';

                if( class_show_icon !=='default' )
                  shortcode += ' class_show_icon="' + class_show_icon + '"';
              }

              if (source === 'event') {

                if( event_item_style !=='default' )  
                  shortcode += ' event_item_style="' + event_item_style + '"';

                if( event_show_icon !=='default' )  
                  shortcode += ' event_show_icon="' + event_show_icon + '"';
              }

              if(header_background !== "")
                shortcode += ' general_header_background="' + header_background + '"';

              if(header_color !== "")
                shortcode += ' general_header_color="' + header_color + '"';

              if(today_column !== "")
                shortcode += ' general_today_column="' + today_column + '"';

              if(el_class !== '')
                shortcode += ' class="' + el_class + '"';

              shortcode += ']';

              editor.insertContent(shortcode);
              tb_remove();
              return false;
            });
          });
        }
      },

      {
        text: noo_shortcodes_str.data_list,
        menu : [

          {
            text: noo_shortcodes_str.trainer_list,
            onclick: function () {
              jQuery("#noo-shortcodes-form-wrapper").remove();
              jQuery.get(url + "/noo_shortcodes_trainer_list.php", function(data){

                initShortcodeTB(noo_shortcodes_str.trainer_list, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var sub_title = jQuery('#TB_window #sub_title').val();

                  var layout_style = jQuery('#TB_window #layout_style option:selected').val();
                  
                  var columns = jQuery('#TB_window #columns').val();

                  var categories = jQuery('#TB_window #categories option:selected').map(function(){ return this.value; }).get().join(",");
                  var orderby = jQuery('#TB_window #orderby option:selected').val();
                  var limit = jQuery('#TB_window #limit').val();

                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[ntt_trainer';
                  if(title !== "")
                    shortcode += ' title="' + title + '"';
                  if(sub_title !== "")
                    shortcode += ' sub_title="' + sub_title + '"';

                  shortcode += ' layout_style="' + layout_style + '"';

                  if(layout_style == "grid")
                    shortcode += ' columns="' + columns + '"';

                  shortcode += ' cat="' + categories + '"';
                  shortcode += ' orderby="' + orderby + '"';
                  shortcode += ' limit="' + limit + '"';

                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  tb_remove();
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.class_list,
            onclick: function () {
              jQuery("#noo-shortcodes-form-wrapper").remove();
              jQuery.get(url + "/noo_shortcodes_class_list.php", function(data){

                initShortcodeTB(noo_shortcodes_str.class_list, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var sub_title = jQuery('#TB_window #sub_title').val();

                  var layout_style = jQuery('#TB_window #layout_style option:selected').val();
                  var autoplay = jQuery('#TB_window #autoplay').is(":checked");
                  
                  var show_info = jQuery('#TB_window #show_info option:selected').val();
                  var columns = jQuery('#TB_window #columns').val();

                  var categories = jQuery('#TB_window #categories option:selected').map(function(){ return this.value; }).get().join(",");
                  var orderby = jQuery('#TB_window #orderby option:selected').val();
                  var limit = jQuery('#TB_window #limit').val();
                  var pagination = jQuery('#TB_window #pagination').val();

                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[ntt_class';
                  if(title !== "")
                    shortcode += ' title="' + title + '"';
                  if(sub_title !== "")
                    shortcode += ' sub_title="' + sub_title + '"';

                  shortcode += ' layout_style="' + layout_style + '"';

                  if(autoplay && 'slider' == layout_style)
                    shortcode += ' autoplay="true"';

                  shortcode += ' show_info="' + show_info + '"';

                  if(layout_style == "grid")
                    shortcode += ' columns="' + columns + '"';

                  shortcode += ' cat="' + categories + '"';
                  shortcode += ' orderby="' + orderby + '"';
                  shortcode += ' limit="' + limit + '"';
                  shortcode += ' pagination="' + pagination + '"';

                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  tb_remove();
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.class_coming_list,
            onclick: function () {
              jQuery("#noo-shortcodes-form-wrapper").remove();
              jQuery.get(url + "/noo_shortcodes_class_coming_list.php", function(data){

                initShortcodeTB(noo_shortcodes_str.class_coming_list, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var sub_title = jQuery('#TB_window #sub_title').val();

                  var layout_style = jQuery('#TB_window #layout_style option:selected').val();
                  var autoplay = jQuery('#TB_window #autoplay').is(":checked");
                  
                  var show_info = jQuery('#TB_window #show_info option:selected').val();
                  var columns = jQuery('#TB_window #columns').val();

                  var categories = jQuery('#TB_window #categories option:selected').map(function(){ return this.value; }).get().join(",");
                  var limit = jQuery('#TB_window #limit').val();
                  var pagination = jQuery('#TB_window #pagination').val();

                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[ntt_class_coming';
                  if(title !== "")
                    shortcode += ' title="' + title + '"';
                  if(sub_title !== "")
                    shortcode += ' sub_title="' + sub_title + '"';

                  shortcode += ' layout_style="' + layout_style + '"';

                  if(autoplay && 'slider' == layout_style)
                    shortcode += ' autoplay="true"';

                  shortcode += ' show_info="' + show_info + '"';

                  if(layout_style == "grid")
                    shortcode += ' columns="' + columns + '"';

                  shortcode += ' cat="' + categories + '"';
                  shortcode += ' limit="' + limit + '"';
                  shortcode += ' pagination="' + pagination + '"';

                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  tb_remove();
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.event_list,
            onclick: function () {
              jQuery("#noo-shortcodes-form-wrapper").remove();
              jQuery.get(url + "/noo_shortcodes_event_list.php", function(data){

                initShortcodeTB(noo_shortcodes_str.event_list, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var sub_title = jQuery('#TB_window #sub_title').val();

                  var layout_style = jQuery('#TB_window #layout_style option:selected').val();
                  var autoplay = jQuery('#TB_window #autoplay').is(":checked");
                  
                  var columns = jQuery('#TB_window #columns').val();

                  var categories = jQuery('#TB_window #categories option:selected').map(function(){ return this.value; }).get().join(",");
                  var orderby = jQuery('#TB_window #orderby option:selected').val();
                  var limit = jQuery('#TB_window #limit').val();
                  var pagination = jQuery('#TB_window #pagination').val();
                  var hide_past_event = jQuery('#TB_window #hide_past_event').is(":checked");

                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[ntt_event';
                  if(title !== "")
                    shortcode += ' title="' + title + '"';
                  if(sub_title !== "")
                    shortcode += ' sub_title="' + sub_title + '"';

                  shortcode += ' layout_style="' + layout_style + '"';

                  if(autoplay && 'slider' == layout_style)
                    shortcode += ' autoplay="true"';

                  if(layout_style == "grid")
                    shortcode += ' columns="' + columns + '"';
                  
                  shortcode += ' cat="' + categories + '"';
                  shortcode += ' orderby="' + orderby + '"';
                  shortcode += ' limit="' + limit + '"';
                  shortcode += ' pagination="' + pagination + '"';
                  if(hide_past_event)
                    shortcode += ' hide_past_event="' + hide_past_event + '"';

                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  tb_remove();
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.event_coming_list,
            onclick: function () {
              jQuery("#noo-shortcodes-form-wrapper").remove();
              jQuery.get(url + "/noo_shortcodes_event_coming_list.php", function(data){

                initShortcodeTB(noo_shortcodes_str.event_coming_list, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var sub_title = jQuery('#TB_window #sub_title').val();

                  var layout_style = jQuery('#TB_window #layout_style option:selected').val();
                  var autoplay = jQuery('#TB_window #autoplay').is(":checked");
                  
                  var columns = jQuery('#TB_window #columns').val();

                  var categories = jQuery('#TB_window #categories option:selected').map(function(){ return this.value; }).get().join(",");
                  var limit = jQuery('#TB_window #limit').val();
                  var pagination = jQuery('#TB_window #pagination').val();

                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[ntt_event_coming';
                  if(title !== "")
                    shortcode += ' title="' + title + '"';
                  if(sub_title !== "")
                    shortcode += ' sub_title="' + sub_title + '"';

                  shortcode += ' layout_style="' + layout_style + '"';

                  if(autoplay && 'slider' == layout_style)
                    shortcode += ' autoplay="true"';

                  if(layout_style == "grid")
                    shortcode += ' columns="' + columns + '"';
                  
                  shortcode += ' cat="' + categories + '"';
                  shortcode += ' limit="' + limit + '"';
                  shortcode += ' pagination="' + pagination + '"';

                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  tb_remove();
                  return false;
                });
              });
            }
          },

        ]
      }

    ];

    editor.addButton('noo_shortcodes_mce_button', {

      type  : 'menubutton',
      title : 'NOO Shortcodes',
      text  : '',
      image : url + '/noo20x20.png',
      style : 'background-image: url("' + url + '/noo20x20.png' + '"); background-repeat: no-repeat; background-position: 2px 2px;"',
      icon  : true,
      menu  : menu
      });

  });

})();
