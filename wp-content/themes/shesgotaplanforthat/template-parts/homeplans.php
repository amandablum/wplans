</div> <!-- inner thin -->
</article> <!-- article -->
</main> <!-- site -->
<main id="site-content" class="homeplans">
	<div class="post-inner section-inner thin homeplansclass ">

		<div class="entry-content"><a id="daplans"></a>
			<h2>Elizabeth Warren Has a Plan For That!</h2><p>Don't know where to start? <?php echo do_shortcode('[randPost image="1000" alt="Random post"]'); ?></p>
<div class="allplans">
<?php echo do_shortcode( '[searchandfilter fields="category" hide_empty="1"]' ); ?>
<?php
/*
 * Loop through Categories and Display Posts within
 */
$post_type = 'post';
 
// Get all the taxonomies for this post type
$taxonomies = get_object_taxonomies( array( 'post_type' => $post_type ) );
 
foreach( $taxonomies as $taxonomy ) :
 
    // Gets every "category" (term) in this taxonomy to get the respective posts
    $terms = get_terms( $taxonomy );
 
    foreach( $terms as $term ) : ?>

        <?php
        $args = array(
                'post_type' => $post_type,
                'posts_per_page' => -1,  //show all posts
                'tax_query' => array(
                    array(
                        'taxonomy' => category,
                        'field' => 'slug',
                        'terms' => $term->slug,
						'orderby' => 'name',
						'order' => 'ASC',
						'parent' => 0,
						'include_children' => 'false' 
						
                    )
                )
 
            );
        $posts = new WP_Query($args);
 
        if( $posts->have_posts() ): ?> 
        
         <h2><?php echo $term->name; ?></h2>
         
        <?php while( $posts->have_posts() ) : $posts->the_post(); 
 
                  
                 echo '<div class="planblockhome"><a href=' . get_permalink() . '>' . get_the_title() . '</a></div>';

 
                        ?>
                   
 
        <?php endwhile; endif; ?>
 
    <?php endforeach;
 
endforeach; ?>
	
	<?php   
wp_reset_postdata();
	?>
	<!-- pagination -->


			</div><!-- .allplans -->
		</div><!-- .entry-content -->
<span class="small">Don't see the plan you're looking for? Our volunteer team is adding more by the week. Stay tuned.</span>
</div><!-- inner thin -->