<?php
/**
 * Displays status content
 *
 * @package portal
 * @since portal 1.0
 * @license GPL 2.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<div class="entry-meta no-title">
			<?php portal_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'portal' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<?php if(!is_single()) : ?><div class="decoration"></div><?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->