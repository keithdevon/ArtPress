<?php // A second sidebar for widgets, just because. ?>

<?php if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>

		<div id="sidebar-B" class="sidebar widget-area" role="complementary">
			<ul class="xoxo">
				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
			</ul>
		</div><!-- #secondary .widget-area -->
		
<?php endif; ?>