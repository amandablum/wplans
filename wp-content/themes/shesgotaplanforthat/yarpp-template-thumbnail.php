<?php
/*
YARPP Template: Thumbnails
Description: Requires a theme which supports post thumbnails
Author: mitcho (Michael Yoshitaka Erlewine)
*/ ?>
<h2>Related Plans</h2>

	
<?php if (have_posts()):?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="relatedpost"><div class="relatedexcerpt"><h3>	
	<?php the_title() ?></h3>
	<?php the_excerpt() ?></div>
	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">Go to Plan ></a>
		</div>
	<?php endwhile; ?>


<?php else: ?>

<?php endif; ?>
