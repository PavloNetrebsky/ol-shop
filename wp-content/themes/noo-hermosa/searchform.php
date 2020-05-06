<?php $id_search = uniqid( 'search' ); ?>
<form method="GET" class="form-horizontal" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<label class="note-search" for="<?php echo esc_attr($id_search); ?>"><?php esc_html_e( 'Type and Press Enter to Search', 'noo-hermosa' ); ?></label>
	<input id="<?php echo esc_attr($id_search); ?>" type="search" name="s" class="form-control" placeholder="<?php echo esc_attr__( 'Enter keyword to search...', 'noo-hermosa' ); ?>" value="<?php echo get_search_query(); ?>" />
	<input type="submit" class="hidden" value="<?php echo esc_attr__( 'Search', 'noo-hermosa' ); ?>" />
</form>