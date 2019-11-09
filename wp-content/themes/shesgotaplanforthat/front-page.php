<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

get_header();
?>
<div id="dabar" class="hide_on_mobile hideonload"></div>
<main id="site-content" role="main">

<?php
get_template_part( 'template-parts/homeplans' );
get_template_part( 'template-parts/askaq-home' );
	?>		
</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>
