<?php
/* enqueue scripts and style from parent theme */
function twentytwenty_styles() {
	wp_enqueue_style( 'parent', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'twentytwenty_styles');

function wpb_add_google_fonts() {

wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:700,700i|Roboto:400,700', false );
}

add_action( 'wp_enqueue_scripts', 'wpb_add_google_fonts' );

if ( ! function_exists('faq_post_type') ) {

// Register FAQ
function faq_post_type() {

	$labels = array(
		'name'                  => _x( 'FAQ', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'FAQ', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'FAQ', 'text_domain' ),
		'name_admin_bar'        => __( 'FAQ', 'text_domain' ),
		'archives'              => __( 'FAQ Archives', 'text_domain' ),
		'attributes'            => __( 'FAQ Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent FAQ:', 'text_domain' ),
		'all_items'             => __( 'All FAQ', 'text_domain' ),
		'add_new_item'          => __( 'Add New FAQ', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New FAQ', 'text_domain' ),
		'edit_item'             => __( 'Edit FAQ', 'text_domain' ),
		'update_item'           => __( 'Update FAQ', 'text_domain' ),
		'view_item'             => __( 'View FAQ', 'text_domain' ),
		'view_items'            => __( 'View FAQ', 'text_domain' ),
		'search_items'          => __( 'Search FAQ', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into FAQ', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this FAQ', 'text_domain' ),
		'items_list'            => __( 'FAQ list', 'text_domain' ),
		'items_list_navigation' => __( 'FAQ list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter FAQ list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'FAQ', 'text_domain' ),
		'description'           => __( 'Q/A submitted by users and answered by plan experts', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'revisions', 'custom-fields' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-buddicons-buddypress-logo',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => '',
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
	);
	register_post_type( 'faq', $args );

}
add_action( 'init', 'faq_post_type', 0 );

}
add_action('acf/init', 'my_acf_init');
function my_acf_init() {

	// check function exists
	if( function_exists('acf_register_block') ) {

		// register a testimonial block
		acf_register_block(array(
			'name'				=> 'plan',
			'title'				=> __('Plan'),
			'description'		=> __('A custom expandable block for EW plans.'),
			'render_callback'	=> 'my_acf_block_render_callback',
			'category'			=> 'common',
			'icon'				=> 'lightbulb',
			'keywords'			=> array( 'plan', 'glance' ),
		));
	}
}
function my_acf_block_render_callback( $block ) {

	// convert name ("acf/plan") into path friendly slug ("plan")
	$slug = str_replace('acf/', '', $block['name']);

	// include a template part from within the "template-parts/block" folder
	if( file_exists( get_theme_file_path("/template-parts/block/content-{$slug}.php") ) ) {
		include( get_theme_file_path("/template-parts/block/content-{$slug}.php") );
	}
}

function randomPost( $atts ) {
 extract(shortcode_atts( array( 'image' => '', 'alt' => 'Banner' ), $atts ));
 $the_query = new WP_Query(array ('posts_per_page'=>'1', 'orderby' => 'rand', 'post__not_in' => array( get_the_ID() )));
 $html = '';
 while(  $the_query->have_posts() ) {
 $the_query->the_post();
 $html .= '
 <a title="'.get_the_title().'" href="'.get_permalink().'" data-mce-href="'.get_permalink().'"> Click here for a random plan. &gt; </a>';
 }
 wp_reset_postdata();
 return $html;
}
add_shortcode('randPost', 'randomPost');

/***************************************************
 * THIS IS ALL MY CODE BELOW. IT IS NICE AND CLEAN.
 **************************************************/


/**
 * The actual filter form.
 *
 * @param  boolean $echo  Whether to echo the form or return it.
 *
 * @return mixed
 */
function warrenprez_display_plan_filter_form( $echo = true ) {

	// Set the arguments for the dropdown.
	$set_dropdown_args  = array(
		'show_option_all'   => '',
		'show_option_none'  => 'Filter Plans By...',
		'orderby'           => 'id',
		'order'             => 'ASC',
		'show_count'        => 0,
		'hide_empty'        => 1,
		'echo'              => false,
		'selected'          => 0,
		'hierarchical'      => 0,
		'name'              => 'single-plan-select',
		'id'                => 'single-plan-select',
		'class'             => 'plan-select-filter',
		'depth'             => 1,
		'taxonomy'          => 'category',
		'hide_if_empty'     => true,
		'option_none_value' => 'none',
		'value_field'       => 'term_id',
		'required'          => false,
	);

	// If we filtered, show that.
	if ( ! empty( $_POST['single-plan-select'] ) ) {
		$set_dropdown_args['selected'] = absint( $_POST['single-plan-select'] );
	}

	// Grab the dropdown.
	$set_dropdown_html  = wp_dropdown_categories( $set_dropdown_args );

	// If we have no markup, bail.
	if ( empty( $set_dropdown_html ) ) {
		return;
	}

	// Set my empty.
	$build  = '';

	// Wrap the form in it's own div.
	$build .= '<div class="plan-select-filter-form-wrap">';

		// Now use an actual form element.
		$build .= '<form id="plan-select-filter-form" class="single-plan-select" action="' . esc_url( home_url( '/' ) ) . '" method="post">';

			// Render the actual markup.
			$build .= $set_dropdown_html;

			// Handle the button to submit.
			$build .= '<button type="submit" class="single-plan-select-button" name="single-plan-filter-button" value="filter">Filter</button>';

			// Include the nonce field.
			$build .= wp_nonce_field( 'warprz_filter_action', 'warprz_filter_nonce', true, false );

		// Close the form.
		$build .= '</form>';

	// Close the div.
	$build .= '</div>';

	// If we don't want it echo'd, return it.
	if ( empty( $echo ) ) {
		return $build;
	}

	// Echo the build.
	echo $build;
}

/**
 * Our basic template to render and display plans.
 *
 * @param  boolean $echo  Whether to echo or return it.
 *
 * @return mixed
 */
function warrenprez_display_possible_plans( $echo = true ) {

	// Run our check for finding plans.
	$maybe_found_plans  = warrenprez_fetch_variable_plans();

	// Something here for no plans.
	if ( empty( $maybe_found_plans ) ) {
		return;
	}

	// Set my empty.
	$build  = '';

	// Loop the plans we found.
	foreach ( $maybe_found_plans as $index => $plan_data ) {

		// Pull out each part of the array.
		$term_data  = $plan_data['term'];
		$plans_list = $plan_data['plans'];

		// Display the term name in an H2.
		$build .= '<h2>' . esc_html( $term_data->name ) . '</h2>';

		// Now loop the plans.
		foreach ( $plans_list as $index => $plan_object ) {

			// Pull out the two parts we want.
			$plan_title = $plan_object->post_title;
			$plan_link  = get_permalink( $plan_object->ID );

			// And my markup.
			$build .= '<div class="planblockhome">';
				$build .= '<a href="' . esc_url( $plan_link ) . '">' . esc_html( $plan_title ) . '</a>';
			$build .= '</div>';
		}

		// No further needed inside each single plan.
	}

	// If we don't want it echo'd, return it.
	if ( empty( $echo ) ) {
		return $build;
	}

	// Echo the build.
	echo $build;
}

/**
 * This is our function to call either the
 * query to get all the plans, or the one that
 * uses the term ID (which will be in our search).
 *
 * @return array
 */
function warrenprez_fetch_variable_plans() {

	// Only run this on the front end.
	if ( is_admin() ) {
		return;
	}

	// First check for the button trigger.
	if ( isset( $_POST['single-plan-filter-button'] ) ) {

		// If we passed a zero
		if ( empty( $_POST['single-plan-select'] ) || 'none' === sanitize_text_field( $_POST['single-plan-select'] ) ) {

			// If there is no select ID, or it is set to none, return all.
			return warrenprez_fetch_all_plans();
		}

		// Bail with a failed nonce. (Maybe we return everything instead?)
		if ( ! isset( $_POST['warprz_filter_nonce'] ) || ! wp_verify_nonce( $_POST['warprz_filter_nonce'], 'warprz_filter_action' ) ) {
			return false;
		}

		// Set our term ID.
		$set_single_plan_id = absint( $_POST['single-plan-select'] );

		// Attempt to get plans by the term passed.
		$get_plans_by_term  = warrenprez_fetch_plans_by_term( $set_single_plan_id );

		// Return something if no plans exist for that term.
		if ( empty( $get_plans_by_term ) ) {
			return false;
		}

		// Now return the array with out term data and post objects.
		return array(
			array(
				'term'  => get_term_by( 'id', $set_single_plan_id, 'category' ),
				'plans' => $get_plans_by_term,
			)
		);

		// Nothing left inside a search.
	}

	// Also check for a manual query string.
	// http://warrenforprez.test/?filter-by-plan=11
	if ( isset( $_GET['filter-by-plan'] ) ) {

		// Set our term ID.
		$set_single_plan_id = absint( $_GET['filter-by-plan'] );

		// Attempt to get plans by the term passed.
		$get_plans_by_term  = warrenprez_fetch_plans_by_term( $set_single_plan_id );

		// Return something if no plans exist for that term.
		if ( empty( $get_plans_by_term ) ) {
			return false;
		}

		// Now return the array with out term data and post objects.
		return array(
			array(
				'term'  => get_term_by( 'id', $set_single_plan_id, 'category' ),
				'plans' => $get_plans_by_term,
			)
		);

	}

	// No other criteria match, so just return all of them.
	return warrenprez_fetch_all_plans();
}

/**
 * Our primary function that will fetch
 * all the currently published plans.
 *
 * @return array
 */
function warrenprez_fetch_all_plans() {

	// Pull all the plan terms.
	$get_plan_terms = get_terms( array(
		'taxonomy' => 'category',
		'orderby'  => 'name',
		'order'    => 'ASC'
	));

	// Bail if we don't have any terms to work with.
	if ( empty( $get_plan_terms ) ) {
		return false;
	}

	// Set an empty array of all the plans.
	$set_sorted_plans_array = array();

	// Now loop our plan terms, parse the terms, and fetch the plans.
	foreach ( $get_plan_terms as $index => $plan_term ) {

		// Attempt to get the plans by term ID.
		$get_plans_by_terms = warrenprez_fetch_plans_by_term( $plan_term->term_id );

		// If we have no plans in this term, skip it.
		if ( empty( $get_plans_by_terms ) ) {
			continue;
		}

		// Now set the array with out term data and post objects.
		$set_sorted_plans_array[ $index ] = array(
			'term'  => $plan_term,
			'plans' => $get_plans_by_terms,
		);

	} // Close up the loop of our plans.

	// Return the entire dataset, or false if we didn't have any.
	return ! empty( $set_sorted_plans_array ) ? $set_sorted_plans_array : false;
}

/**
 * Get all the plans tied to a specific group.
 *
 * @param  integer $term_id  The term ID of the plan we want to use.
 *
 * @return array
 */
function warrenprez_fetch_plans_by_term( $term_id = 0 ) {

	// Bail without our ID.
	if ( empty( $term_id ) ) {
		return false;
	}

	// Set the arguments for the posts attached to each plan.
	$plans_by_term_args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'category'       => absint( $term_id ),
		'posts_per_page' => -1,
		'nopaging'       => true,
	);

	// Attempt to get the plans.
	$get_plans_by_term  = get_posts( $plans_by_term_args );

	// Return the plans, or false.
	return ! empty( $get_plans_by_term ) && ! is_wp_error( $get_plans_by_term ) ? $get_plans_by_term : false;
}
