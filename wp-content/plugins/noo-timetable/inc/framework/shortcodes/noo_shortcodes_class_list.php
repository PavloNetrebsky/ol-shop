<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<script>
  jQuery(document).ready(function($) {
    $('.noo-form-group #layout_style').change(function() {
      var $this = $(this);
      if($this.find(':selected').val() == "slider") {
        $('.chk_autoplay').show();
        $('.chk_columns').show();
      } else if($this.find(':selected').val() == "list") {
        $('.chk_autoplay').hide();
        $('.chk_columns').hide();
      } else {
        $('.chk_autoplay').hide();
        $('.chk_columns').show();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
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
        <label for="layout_style" class="noo-label"><?php _e('Layout Style', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="layout_style" id="layout_style">
            <option value="grid"><?php _e('Grid', 'noo-timetable'); ?></option>
            <option value="list"><?php _e('List', 'noo-timetable'); ?></option>
            <option value="slider"><?php _e('Slider', 'noo-timetable'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group chk_autoplay" style="display: none;">
        <label for="autoplay"><?php _e('Auto Play Slider', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <input id="autoplay" type="checkbox" name="autoplay" />
          <small class="noo-control-desc"><?php _e('Auto Play Slider after 3 seconds', 'noo-timetable'); ?></small>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="show_info" class="noo-label"><?php _e('Show Info', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="show_info" id="show_info">
            <option value='all'><?php _e('Show Date & Time', 'noo-timetable'); ?></option>
            <option value='date'><?php _e('Only Date', 'noo-timetable'); ?></option>
            <option value='time'><?php _e('Only Time', 'noo-timetable'); ?></option>
            <option value='null'><?php _e('Hide Date & Time', 'noo-timetable'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group chk_columns">
        <label for="columns"><?php _e('Columns', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <input type="text" id="columns" name="columns" class="noo-slider" value="4" data-min="1" data-max="4"/>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="categories"><?php _e('Categories', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <?php
          $categories = get_terms( 'class_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
          ?>
          <select name="categories" id="categories" multiple="true">
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
      <div class="noo-form-group">
        <label for="orderby" class="noo-label"><?php _e('Order By', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="orderby" id="orderby">
            <option value='default'><?php _e('Default', 'noo-timetable'); ?></option>
            <option value='open_date'><?php _e('Open Date', 'noo-timetable'); ?></option>
            <option value='latest'><?php _e('Recent First', 'noo-timetable'); ?></option>
            <option value='oldest'><?php _e('Older First', 'noo-timetable'); ?></option>
            <option value='alphabet'><?php _e('Title Alphabet', 'noo-timetable'); ?></option>
            <option value='ralphabet'><?php _e('Title Reversed Alphabet', 'noo-timetable'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="limit"><?php _e('Max Number of Classes', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <input type="text" id="limit" name="limit" class="noo-slider" value="4" data-min="1" data-max="50"/>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="pagination" class="noo-label"><?php _e('Style Pagination', 'noo-timetable'); ?></label>
        <div class="noo-control">
          <select name="pagination" id="pagination">
            <option value='disable'><?php _e('Disable pagination', 'noo-timetable'); ?></option>
            <option value='default'><?php _e('Default', 'noo-timetable'); ?></option>
          </select>
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