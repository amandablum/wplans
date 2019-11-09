<?php
/**
 * The template for displaying the footer
 *
 * Contains the opening of the #site-footer div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

?>
<?php get_template_part( 'template-parts/whoisbehind' ); ?>

			<footer id="site-footer" role="contentinfo" class="header-footer-group">

				<div class="section-inner">

					<div class="footer-credits">

						<p class="footer-copyright">&copy;
							<?php
							echo esc_html(
								date_i18n(
									/* translators: Copyright date format, see https://secure.php.net/date */
									_x( 'Y', 'copyright date format', 'twentytwenty' )
								)
							);
							?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>">WarrenPlans.com</a><span class="disclaim"> is built on the <a href="http://wordpress.org" target="_blank">WordPress</a> platform under the <a href="https://www.gnu.org/licenses/gpl-3.0.en.html" target="_blank">GPL</a>. WarrenPlans.com is not affiliated with Elizabeth Warren for President. <br><a href="https://howlingzoeproductions.com/warrenplans/credits">Site Credits</a>
						</p>

						

					</div><!-- .footer-credits -->

					<a class="to-the-top" href="#site-header">
						<span class="to-the-top-long">
							<?php
							/* translators: %s: HTML character for up arrow */
							printf( esc_html( __( 'To the top %s', 'twentytwenty' ) ), '<span class="arrow">&uarr;</span>' );
							?>
						</span><!-- .to-the-top-long -->
						<span class="to-the-top-short">
							<?php
							/* translators: %s: HTML character for up arrow */
							printf( esc_html( __( 'Up %s', 'twentytwenty' ) ), '<span class="arrow">&uarr;</span>' );
							?>
						</span><!-- .to-the-top-short -->
					</a><!-- .to-the-top -->

				</div><!-- .section-inner -->

			</footer><!-- #site-footer -->

		<?php wp_footer(); ?>
		</div><!--constrains to 1000px-->
	</body>
</html>
