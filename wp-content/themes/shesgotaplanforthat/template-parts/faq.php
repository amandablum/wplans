</div> <!-- inner thin -->
</article> <!-- article -->
</main> <!-- site -->
<main id="site-content" class="faq">

<div class="post-inner section-inner thin faqclass ">

		<div class="entry-content">
			<h2>FAQ</h2>
<div class="faqsearch"><?php echo do_shortcode('[wd_asp id=2]'); ?></div>
			
<?php 
$args = array(
    'posts_per_page' => 99,
    'post_type' => 'faq',
    'orderby' => 'date',
    'order' => 'ASC',
    'ignore_sticky_posts' => 1,
    'paged' => $paged);
$loop = new WP_Query($args);
if ($loop->have_posts()) :
    while ($loop->have_posts()) : $loop->the_post();
	?>
	<details>
  <summary><div class="limit">
	<?php   
	  the_title();
	  ?></div></summary>
	<div class="limit">	<?php 
	the_content();  ?></div>
</details> <?php   
    endwhile;
endif;
wp_reset_postdata();
	?>
			</div><!-- .entry-content -->
</div><!-- inner thin -->