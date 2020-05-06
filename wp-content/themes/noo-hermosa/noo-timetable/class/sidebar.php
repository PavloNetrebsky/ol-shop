<?php
    global $wp_locale;
    // Show/ hide filter
    $filter_title         = noo_hermosa_get_option('noo_classes_filter_title');
    $show_level_filter    = noo_hermosa_get_option('noo_classes_show_level_filter', true);
    $show_level_filter    = $show_level_filter && !is_tax('class_level') && ($levels = get_terms('class_level'));
    
    $show_category_filter = noo_hermosa_get_option('noo_classes_show_category_filter', true);
    $show_category_filter = $show_category_filter && !is_tax('class_category') && ($categories = get_terms('class_category'));

    $show_trainer_filter  = noo_hermosa_get_option('noo_classes_show_trainer_filter', true);
    $show_trainer_filter  = $show_trainer_filter && $trainers = get_posts(array('post_type'=>'noo_trainer','posts_per_page'=>-1,'suppress_filters'=>0));

    $show_days_filter     = noo_hermosa_get_option('noo_classes_show_days_filter', true);

    $show_filter = ( $show_level_filter || $show_category_filter || $show_trainer_filter || $show_days_filter );

    // Layout sidebar
    $class_sidebar = '';
    $noo_classes_layout = noo_hermosa_get_option('noo_classes_layout', 'sidebar');
    if( $noo_classes_layout != 'fullwidth' ) :
        switch ( $noo_classes_layout ) {
            case 'left_sidebar':
                $class_sidebar = 'noo-md-3 sidebar-left';
                break;
            default: case 'sidebar':
                $class_sidebar = 'noo-md-3 sidebar-right';
                break;
        }
    endif;
?>
<div class="<?php noo_hermosa_sidebar_class(); ?> <?php echo esc_attr( $class_sidebar ); ?>">
    <div class="noo-sidebar-wrap">
		
		<?php if( $show_filter ) : ?>
            <div class="widget widget-search-classes widget-classes-filters">
                <?php if ( $filter_title != '' ) : ?>
                <h4 class="widget-title">
                    <?php echo apply_filters( 'widget_title', $filter_title ); ?>
                </h4>
                <?php endif; ?>
                <?php if($show_level_filter):?>
                <div class="widget-class-filter search-class-level" data-group="level">
                    <select class="widget-class-filter-control">
                        <option value=""><?php esc_html_e('Select Level','noo-hermosa')?></option>
                        <?php foreach ((array)$levels as $level):?>
                            <option value="filter-level-<?php echo esc_attr($level->term_id)?>"><?php echo esc_html($level->name)?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <?php endif;?>
                <?php if($show_category_filter):?>
                <div class="widget-class-filter search-class-category" data-group="category">
                    <select class="widget-class-filter-control">
                        <option value=""><?php esc_html_e('Select Category','noo-hermosa')?></option>
                        <?php foreach ((array)$categories as $category):?>
                            <option value="filter-cat-<?php echo esc_attr($category->term_id)?>"><?php echo esc_html($category->name)?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <?php endif;?>
                <?php if($show_trainer_filter):
                    $current_trainer = isset( $_GET['trainer'] ) && !empty( $_GET['trainer'] ) ? $_GET['trainer'] : '';
                ?>
                <div class="widget-class-filter search-class-trainer" data-group="trainer">
                    <select class="widget-class-filter-control">
                        <option value=""><?php esc_html_e('Select Trainer','noo-hermosa')?></option>
                        <?php foreach ((array)$trainers as $trainer):?>
                            <option <?php selected( $current_trainer, $trainer->ID ); ?> value="filter-trainer-<?php echo esc_attr($trainer->ID)?>"><?php echo esc_html($trainer->post_title)?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <?php endif;?>
                <?php if ( $show_days_filter ) : ?>
                <div class="widget-class-filter search-class-weekday" data-group="day">
                    <span><?php _e('Filter class by days','noo-hermosa')?></span>
                    <?php for ($day_index = 0; $day_index <= 6; $day_index++) : ?>
                    <label class="noo-xs-6">
                        <input type="checkbox" class="widget-class-filter-control" value="filter-day-<?php echo esc_attr($day_index)?>"> <?php echo esc_html($wp_locale->get_weekday($day_index)) ?>
                    </label>
                    <?php
                    endfor;
                    ?>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php
        $sidebar = noo_hermosa_get_sidebar_id();
        if( ! empty( $sidebar ) ) :
        ?>
            <?php // Dynamic Sidebar
            if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $sidebar ) ) : ?>
                <!-- Sidebar fallback content -->

            <?php endif; // End Dynamic Sidebar sidebar-main ?>
        <?php endif; // End sidebar ?> 
    </div>
</div>

