<?php get_header(); ?>
<section id="page-content">
	<h1>Contacts</h1>

	<?php	if (have_posts()): ?>
	<div id="grid">
		<?php 
			while (have_posts()):
			the_post();
			$fields = get_field_objects();
		?>
		<div class="block">
			<?php foreach($fields as $field): ?>
			<div class="row">					
					<div><?= $field['label']; ?>:</div>
					<div><?= $field['value']; ?></div>
			</div>
			<?php endforeach; ?>
		</div>
	<?php endwhile; ?>
	</div>
	<a class="button" href="<?= get_post_type_archive_link('contact'); ?>">All Contacts</a>
	<?php else: ?>
	<p>Sorry, no contacts at this time.</p>
	<?php endif; ?>
</section>
<?php get_footer(); ?>