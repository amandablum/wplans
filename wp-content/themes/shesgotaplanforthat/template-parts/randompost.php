<?php
/*
 * Template Name: Redirect to Random Post Template
 */
query_posts(array(
    'showposts' => 1,
    'orderby' => 'rand'
));

if (have_posts()) :
    while (have_posts()) :
        the_post();

        $random_post_url = get_the_permalink();
        ?>

<?php
echo "<a href=$random_post_url>Click here for a random plan. &gt;</a>"
?>
<?php
    endwhile;
endif;
?>