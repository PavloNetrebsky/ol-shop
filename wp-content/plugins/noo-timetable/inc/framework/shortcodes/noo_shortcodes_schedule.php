<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );

$hours = range(0, 24);
foreach ($hours as $k => $v) {
    $hours[$k] = $v . ':00:00';
}

?>
<script>
  jQuery(document).ready(function($) {

    $('.view_month').hide();
    $('.view_agendaDay').hide();
    $('.view_agendaWeek').show();
    $('.popup_yes').hide();
    $('.custom_default_date_yes').hide();
    $('.source_event').hide();
    $('.source_class').show();

    $('.noo-form-group #default_view').change(function() {
      var $this = $(this);
      if($this.find(':selected').val() == "agendaWeek")
      {
        $('.view_month').hide();
        $('.view_agendaDay').hide();
        $('.view_agendaWeek').show();
      }
      else if($this.find(':selected').val() == "agendaDay")
      {
        $('.view_agendaWeek').hide();
        $('.view_month').hide();
        $('.view_agendaDay').show();
      }
      else
      {
        $('.view_agendaWeek').hide();
        $('.view_agendaDay').hide();
        $('.view_month').show();
      }
    });

    $('.noo-form-group #source').change(function() {
      var $this = $(this);
      if($this.find(':selected').val() == "class")
      {
        $('.source_event').hide();
        $('.source_class').show();
      }
      else if($this.find(':selected').val() == "event")
      {
        $('.source_class').hide();
        $('.source_event').show();
      } else {
        $('.source_class').hide();
        $('.source_event').hide();
      }
    });

    $('.noo-form-group #show_popup').change(function() {
      var $this = $(this);
      if($this.find(':selected').val() == "yes")
      {
        $('.popup_yes').show();
      }
      else
      {
        $('.popup_yes').hide();
      }
    });

    $('.noo-form-group #custom_default_date').change(function() {
      var $this = $(this);
      if($this.is(':checked'))
      {
        $('.custom_default_date_yes').show();
      }
      else
      {
        $('.custom_default_date_yes').hide();
      }
    });

  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="source" class="noo-label"><?php _e('Schedule Source', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="source" id="source">
            <option value="class"><?php _e('Class', 'noo-timetable'); ?></option>
            <option value="event"><?php _e('Event', 'noo-timetable'); ?></option>
            <option value="both"><?php _e( 'Both Class and Event', 'noo-timetable' ); ?></option>
          </select>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="type" class="noo-label"><?php _e('Title', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <input type="text" name="title" id="title" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="sub_title" class="noo-label"><?php _e('Sub Title', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <input type="text" name="sub_title" id="sub_title" value=""/>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="default_view" class="noo-label"><?php _e('Default View', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="default_view" id="default_view">
            <option value="agendaWeek"><?php _e('Weekly View', 'noo-timetable'); ?></option>
            <option value="month"><?php _e('Monthly view', 'noo-timetable'); ?></option>
            <option value="agendaDay"><?php _e('Daily view', 'noo-timetable'); ?></option>
          </select>
          <small class="noo-control-desc"><?php _e('You can select weekly, monthly and daily view.', 'noo-timetable'); ?></small>
        </div>
      </div>
      <hr>

      <div class="noo-form-group source_class">
        <label for="class_categories"><?php _e('Class Categories', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <?php
          $categories = get_terms( 'class_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
          ?>
          <select name="class_categories" id="class_categories" multiple="true">
          <option value="all" selected="true"><?php _e('All', 'noo-timetable'); ?></option>
          <?php
          foreach ($categories as $category) {
            echo '<option value="' . $category->term_id . '">';
            echo $category->name . '</option>';
          }
          ?>
          </select>
        </div>
      </div>
      <hr class="source_class">
      <div class="noo-form-group source_event">
        <label for="event_categories"><?php _e('Event Categories', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <?php
          $categories = get_categories( array( 'orderby' => 'NAME', 'order' => 'ASC', 'taxonomy'=>'event_category' ) );
          ?>
          <select name="event_categories" id="event_categories" multiple="true">
          <option value="all" selected="true"><?php _e('All', 'noo-timetable'); ?></option>
          <?php
          foreach ($categories as $category) {
            echo '<option value="' . $category->term_id . '">';
            echo $category->name . '</option>';
          }
          ?>
          </select>
        </div>
      </div>

      <hr class="source_event">

      <div class="noo-form-group view_agendaWeek view_agendaDay">
        <label for="min_time" class="noo-label"><?php _e('Schedule Min Time', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="min_time" id="min_time">
            <?php foreach ($hours as $k => $hour) { ?>
              <option value="<?php echo $hour; ?>"><?php echo $hour; ?></option>  
            <?php } ?>
          </select>
          <small class="noo-control-desc"><?php _e('Time start of Schedule (Hour), ex: 05:00:00', 'noo-timetable'); ?></small>
        </div>
      </div>
      <div class="noo-form-group view_agendaWeek view_agendaDay">
        <label for="max_time" class="noo-label"><?php _e('Schedule Max Time', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="max_time" id="max_time">
            <?php foreach ($hours as $k => $hour) { ?>
              <option value="<?php echo $hour; ?>"><?php echo $hour; ?></option>  
            <?php } ?>
          </select>
          <small class="noo-control-desc"><?php _e('Time end of Schedule (Hour), ex: 21:00:00', 'noo-timetable'); ?></small>
        </div>
      </div>
      <div class="noo-form-group view_agendaWeek view_agendaDay">
        <label for="hide_time_range" class="noo-label"><?php _e('Hide Time Ranges', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="hide_time_range" id="hide_time_range" multiple="true">
            <option value="all" selected="true"><?php _e('All', 'noo-timetable'); ?></option>
            <?php
            $hours = range(1, 23);
            ?>
            <?php foreach ($hours as $k => $hour) { ?>
              <option value="<?php echo $k; ?>"><?php echo $hour . ':00:00'; ?></option>  
            <?php } ?>
          </select>
          <small class="noo-control-desc"><?php _e('Hours selected here will be hidden from the schedule. Note that you shouldn\'t select hours that have classes as it will lead to wrong calculation.', 'noo-timetable'); ?></small>
        </div>
      </div>
      <div class="noo-form-group view_agendaWeek view_agendaDay">
        <label for="show_time_column"><?php _e('Show Time Column', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="show_time_column" id="show_time_column">
            <option value="default"><?php _e('Default', 'noo-timetable'); ?></option>
            <option value="yes"><?php _e('Yes', 'noo-timetable'); ?></option>
            <option value="no"><?php _e( 'No', 'noo-timetable' ); ?></option>
          </select>
          <small class="noo-control-desc"><?php _e('Show / Hide Time Column', 'noo-timetable'); ?></small>
        </div>
      </div>

      <hr class="view_agendaWeek view_agendaDay">
        
      <div class="noo-form-group source_class source_event">
        <label for="show_filter"><?php _e('Show Category Filter', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="show_filter" id="show_filter">
            <option value="default"><?php _e('Default', 'noo-timetable'); ?></option>
            <option value="yes"><?php _e('Yes', 'noo-timetable'); ?></option>
            <option value="no"><?php _e( 'No', 'noo-timetable' ); ?></option>
          </select>
          <small class="noo-control-desc"><?php _e('Show / Hide Filter', 'noo-timetable'); ?></small>
        </div>
      </div>
      <hr class="view_agendaWeek view_agendaDay">
      <div class="noo-form-group">
        <label for="show_weekends"><?php _e('Show Weekends', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="show_weekends" id="show_weekends">
            <option value="default"><?php _e('Default', 'noo-timetable'); ?></option>
            <option value="yes"><?php _e('Yes', 'noo-timetable'); ?></option>
            <option value="no"><?php _e( 'No', 'noo-timetable' ); ?></option>
          </select>
        </div>
      </div>
      <hr>

      <div class="noo-form-group">
        <label for="show_export"><?php _e('Show Export', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="show_export" id="show_export">
            <option value="default"><?php _e('Default', 'noo-timetable'); ?></option>
            <option value="yes"><?php _e('Yes', 'noo-timetable'); ?></option>
            <option value="no"><?php _e( 'No', 'noo-timetable' ); ?></option>
          </select>
          <small class="noo-control-desc"><?php _e('Export to file iCal', 'noo-timetable'); ?></small>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="show_toolbar"><?php _e('Show Toolbar', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="show_toolbar" id="show_toolbar">
            <option value="default"><?php _e('Default', 'noo-timetable'); ?></option>
            <option value="yes"><?php _e('Yes', 'noo-timetable'); ?></option>
            <option value="no"><?php _e( 'No', 'noo-timetable' ); ?></option>
          </select>
          <small class="noo-control-desc"><?php _e('Show forward and backward arrowhead on top of the schedule', 'noo-timetable'); ?></small>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="show_day"><?php _e('Show Date', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="show_day" id="show_day">
            <option value="default"><?php _e('Default', 'noo-timetable'); ?></option>
            <option value="yes"><?php _e('Yes', 'noo-timetable'); ?></option>
            <option value="no"><?php _e( 'No', 'noo-timetable' ); ?></option>
          </select>
          <small class="noo-control-desc"><?php _e('Only Weekly view', 'noo-timetable'); ?></small>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="custom_default_date"><?php _e('Custom Default Date', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <input id="custom_default_date" type="checkbox" name="custom_default_date" />
        </div>
      </div>
      <div class="noo-form-group custom_default_date_yes">
        <label for="default_date"><?php _e('Default Date', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <input type="text" class="noo-date-picker" name="default_date" id="default_date" />
          <small class="noo-control-desc"><?php _e('Leave blank to get the current time', 'noo-timetable'); ?></small>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="show_popup"><?php _e('Show Class/Event info in Popup', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="show_popup" id="show_popup">
            <option value="default"><?php _e('Default', 'noo-timetable'); ?></option>
            <option value="yes"><?php _e('Yes', 'noo-timetable'); ?></option>
            <option value="no"><?php _e( 'No', 'noo-timetable' ); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group popup_yes">
        <label for="popup_style" class="noo-label"><?php _e('Popup Style', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="popup_style" id="popup_style">
            <option value="default"><?php esc_html_e( 'Default', 'noo-timetable' ); ?> </option>
            <option value="1"><?php esc_html_e( 'Fade in and Scale', 'noo-timetable' ); ?> </option>
            <option value="2"><?php esc_html_e( 'Slide in (right)', 'noo-timetable' ); ?> </option>
            <option value="3"><?php esc_html_e( 'Slide in (bottom)', 'noo-timetable' ); ?> </option>
            <option value="4"><?php esc_html_e( 'Newspaper', 'noo-timetable' ); ?> </option>
            <option value="5"><?php esc_html_e( 'Fall', 'noo-timetable' ); ?> </option>
            <option value="6"><?php esc_html_e( 'Side Fall', 'noo-timetable' ); ?> </option>
            <option value="7"><?php esc_html_e( 'Sticky Up', 'noo-timetable' ); ?> </option>
            <option value="8"><?php esc_html_e( '3D Flip (horizontal)', 'noo-timetable' ); ?> </option>
            <option value="9"><?php esc_html_e( '3D Flip (vertical)', 'noo-timetable' ); ?> </option>
            <option value="10"><?php esc_html_e( '3D Sign', 'noo-timetable' ); ?> </option>
            <option value="11"><?php esc_html_e( 'Super Scaled', 'noo-timetable' ); ?> </option>
            <option value="12"><?php esc_html_e( 'Just Me', 'noo-timetable' ); ?> </option>
            <option value="13"><?php esc_html_e( '3D Slit', 'noo-timetable' ); ?> </option>
            <option value="14"><?php esc_html_e( '3D Rotate Bottom', 'noo-timetable' ); ?> </option>
            <option value="15"><?php esc_html_e( '3D Rotate In Left', 'noo-timetable' ); ?> </option>
            <option value="16"><?php esc_html_e( 'Blur', 'noo-timetable'); ?> </option>                  
          </select>
        </div>
      </div>
      <hr class="view_agendaWeek view_agendaDay">
      <div class="noo-form-group view_agendaWeek view_agendaDay">
        <label for="content_height" class="noo-label"><?php _e('Schedule Height', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <input type="text" name="content_height" id="content_height" value=""/>
          <small class="noo-control-desc"><?php _e('Input height of schedule, leave blank for auto height.', 'noo-timetable'); ?></small>
        </div>
      </div>
      <hr>
      <div class="noo-form-group source_class">
        <label for="class_show_category"><?php _e('Show Class category by its color', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="class_show_category" id="class_show_category">
            <option value="default"><?php _e('Default', 'noo-timetable'); ?></option>
            <option value="yes"><?php _e('Yes', 'noo-timetable'); ?></option>
            <option value="no"><?php _e( 'No', 'noo-timetable' ); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group source_class">
        <label for="class_item_style"><?php _e('Class Item Style', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="class_item_style" id="class_item_style">
            <option value="default"><?php esc_html_e( 'Default', 'noo-timetable' ); ?></option>
            <option value="categoryColor"><?php esc_html_e( 'Category Color', 'noo-timetable' ); ?></option>
            <option value="cat_bg_color"><?php esc_html_e( 'Background Color by Category', 'noo-timetable' ); ?></option>
            <option value="item_bg_image"><?php esc_html_e( 'Item Background Image', 'noo-timetable' ); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group source_class">
        <label for="class_show_icon"><?php _e('Class Show Icon', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="class_show_icon" id="class_show_icon">
            <option value="default"><?php _e('Default', 'noo-timetable'); ?></option>
            <option value="yes"><?php _e('Yes', 'noo-timetable'); ?></option>
            <option value="no"><?php _e( 'No', 'noo-timetable' ); ?></option>
          </select>
        </div>
      </div>
      <hr class="source_class">
      <div class="noo-form-group source_event">
        <label for="event_item_style"><?php _e('Event Item Style', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="event_item_style" id="event_item_style">
            <option value="default"><?php esc_html_e( 'Default', 'noo-timetable' ); ?></option>
            <option value="background_color"><?php esc_html_e( 'Background Color', 'noo-timetable' ); ?></option>
            <option value="background_image"><?php esc_html_e( 'Background Image', 'noo-timetable' ); ?></option>
            <option value="background_none"><?php esc_html_e( 'Background None', 'noo-timetable' ); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group source_event">
        <label for="event_show_icon"><?php _e('Event Show Icon', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="event_show_icon" id="event_show_icon">
            <option value="default"><?php _e('Default', 'noo-timetable'); ?></option>
            <option value="yes"><?php _e('Yes', 'noo-timetable'); ?></option>
            <option value="no"><?php _e( 'No', 'noo-timetable' ); ?></option>
          </select>
        </div>
      </div>
      <hr class="source_event">
      <div class="noo-form-group">
        <label for="header_background"><?php _e('Heading Background Color', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <input id="header_background" type="text" name="header_background" class="noo-color-picker" style="display: inline-block;" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="header_color"><?php _e('Heading Text Color', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <input id="header_color" type="text" name="header_color" class="noo-color-picker" style="display: inline-block;" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="today_column"><?php _e('Today\'s background', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <input id="today_column" type="text" name="today_column" class="noo-color-picker" style="display: inline-block;" />
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="class"><?php _e('Class', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <input type="text" name="class" id="class" />
          <small class="noo-control-desc"><?php _e('(Optional) Enter a unique class name.', 'noo-timetable'); ?></small>
        </div>
      </div>
    </div>
    <div class="noo-form-footer">
      <input type="button" name="insert" id="noo-save-shortcodes" class="button button-primary" value="<?php _e('Save', 'noo-timetable'); ?>"/>
      <input type="button" name="cancel" id="noo-cancel-shortcodes" class="button" value="<?php _e('Cancel', 'noo-timetable'); ?>"/>
    </div>
  </form>
</div>