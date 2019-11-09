</div> <!-- inner thin -->
</article> <!-- article -->
</main> <!-- site -->
<main id="site-content" class="homeplans">
	<div class="post-inner section-inner thin homeplansclass ">

		<div class="entry-content"><a id="daplans"></a>
			<h2>Elizabeth Warren Has a Plan For That!</h2><p>Don't know where to start? <?php echo do_shortcode('[randPost image="1000" alt="Random post"]'); ?></p>

			<div class="allplans">
				<?php
				// Display our filter form.
				warrenprez_display_plan_filter_form();

				// Display the plans we (might) have.
				warrenprez_display_possible_plans();

				wp_reset_postdata();
				?>

			</div><!-- .allplans -->
		</div><!-- .entry-content -->

		<span class="small">Don't see the plan you're looking for? Our volunteer team is adding more by the week. Stay tuned.</span>
	</div><!-- inner thin -->
