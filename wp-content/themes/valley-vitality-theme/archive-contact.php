<?php 
$query = new WP_Query(
	[
		'post_type'      => 'contact', 
		'post_status'    => 'publish', 
		'posts_per_page' => -1,
		'order'          => 'DESC',
		'orderby'        => 'meta_value',
		'meta_query'     => [
			'key'          => 'submitted',
			'meta_value'   => date("m/d/Y hh:mm:ss a")
		]
	]
);

get_header();
?>
<section id="page-content">
	<h1>Contacts</h1>

	<?php	if ($query->have_posts()): ?>
	<div id="grid">
		<?php 
			while ($query->have_posts()):
			$query->the_post(); 
			$fields = get_field_objects();
		?>
		<div class="block">
			<?php foreach($fields as $field): ?>
			<div class="row">
					<div><?= $field['label']; ?>:</div>
					<?php if ($field['label'] == "First Name"): ?>
					<div>
						<a href="<?= get_the_permalink(); ?>"><?= $field['value']; ?></a>
					</div>
					<?php else: ?>
					<div><?= $field['value']; ?></div>
					<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	<?php endwhile; ?>
	</div>
	
	<form method="post" action="<?php echo get_template_directory_uri(); ?>/export.php">
		<button class="button" id="export" name="export" type="submit" value="export">Export</button>
	</form>
	<?php 
	wp_reset_postdata(); 
	else:
	?>
	<p>Sorry, no contacts at this time.</p>
	<?php endif; ?>
</section>
<?php get_footer(); ?>