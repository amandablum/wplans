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
 $the_query = new WP_Query(array ('posts_per_page'=>'1', 'orderby' => 'rand', 'post__not_in' => array( get_the_ID )));
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

