</div> <!-- inner thin -->
</article> <!-- article -->
</main> <!-- site -->
<main id="site-content" class="quiz">
	<div class="post-inner section-inner thin quizclass ">

		<div class="entry-content">
			<h2>Test yourself </h2>
			<p style="color:white">Take a quick quiz about Elizabeth's Plan for <?php echo get_the_title(); ?>. </p>
	<?php
$quiz = get_field('related_quiz') ?: 'Plan Title';
		gravity_form( $quiz, $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = false, $tabindex, $echo = true );
?>
		</div><!-- .entry-content -->
</div><!-- inner thin -->
	</main> <!-- site -->
	<div class="post-inner section-inner thin ctaclass "><div class="entry-content">
	<div class="wp-block-button alignleft"><a class="wp-block-button__link" href="/">Back to all plan</a></div>
			<div class="wp-block-button alignleft"><a class="wp-block-button__link" href="/">Share this plan</a></div>
			<div class="wp-block-button alignleft"><a class="wp-block-button__link" href="https://elizabethwarren.com/join-us" target="_blank">Join the Campaign</a></div>
	</div><!-- .entry-content -->
</div><!-- inner thin -->