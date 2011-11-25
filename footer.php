<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
	<div id="footer" class="container" role="contentinfo">
		<div id="colophon" class="row">

<?php
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with four columns of widgets.
	 */
	get_template_part( 'sidebars/sidebar-footer' );
?>

			<div id="site-info" class="eightcol">
				<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>
			</div><!-- #site-info -->

			<div id="site-generator" class="fourcol last">
				<?php do_action( 'twentyten_credits' ); ?>
				Site powered by <a href="http://wordpress.org/" title="Semantic Personal Publishing Platform" rel="generator">WordPress</a> and <a href="http://wordpress-for-artists.com/">ArtPress</a>
			</div><!-- #site-generator -->

		</div><!-- #colophon -->
		<div class="clear"></div>
	</div><!-- #footer -->

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
