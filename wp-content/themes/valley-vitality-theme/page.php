<?php 
get_header();

while (have_posts()):
	the_post();
?>
<section id="page-content">
<h1><?= the_title(); ?></h1>
<?= the_content(); ?>
</section>
<?php 
endwhile; 

get_footer();
?>