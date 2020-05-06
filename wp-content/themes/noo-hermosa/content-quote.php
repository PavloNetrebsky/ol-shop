<?php
$quote = '';
$quote = noo_hermosa_get_post_meta(get_the_id() , '_noo_wp_post_quote', '');
if($quote == '') {
    $quote = get_the_title( get_the_id() );
}
$cite = noo_hermosa_get_post_meta(get_the_id() , '_noo_wp_post_quote_citation', '');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <!--Start header-->
    <header class="entry-header">
        <?php if ( is_singular() ) : ?>
            <h1>
                <?php the_title(); ?>
            </h1>
        <?php else : ?>
            <h3>
                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permanent link to: "%s"','noo-hermosa' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
            </h3>
        <?php endif; ?>
    </header>
    <!--Start end header-->

    <!--Start content-->
    <div class="entry-content">
        <?php the_content(); ?>
        <?php if ( is_single() ) : ?>
            <?php wp_link_pages(); ?>
        <?php else : ?>
            
        <?php endif; ?>
    </div>
    <!--Start end content-->
    
    <?php if ( is_single() ) : ?>
        <!--Start footer-->
        <footer class="entry-footer">
            <?php noo_hermosa_entry_meta(); ?>
        </footer>
        <!--Start end footer-->
    <?php endif; ?>

</article> <!-- /#post- -->