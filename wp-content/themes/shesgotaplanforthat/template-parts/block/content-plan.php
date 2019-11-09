<?php

/**
 * Plan Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'plan-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'plan';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assing defaults.
$headline = get_field('plan_title') ?: 'Plan Title';
$client_company = get_field('plan_copy') ?: 'Plan Copy';
?>


	
<div id="planblock" class="<?php echo esc_attr($className); ?>">
		<details><summary><div class="plantitle"><h2><?php the_field('plan_title'); ?></h2></div><hr class="planline"></summary>
								<div class="plancopy"><?php the_field('plan_copy'); ?></div>
		</details></div>	
	