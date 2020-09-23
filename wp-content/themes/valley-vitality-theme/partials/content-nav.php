<?php 
$parents = get_pages([
		'parent'      => 0, 
		'sort_order'  => 'ASC', 
		'sort_column' => 'menu_order'
	]);

if (count($parents) > 0):
?>
<header id="header">
	<nav id="nav">
		<i id="hamburger" class="fas fa-bars"></i>
		<ul class="parent-menu">
<?php
foreach ($parents as $parent):
	$parent_link_active = $post->ID == $parent->ID ? " active" : "";
	$parent_title       = get_field("navigation_text", $parent->ID);
	$children           = get_pages(array('parent' => $parent->ID, 'sort_order' => 'ASC', 'sort_column' => 'menu_order', 'meta_key' => 'include_in_navigation', 'meta_value' => 'Yes'));
	if (count($children) > 0):
?>
			<li class="parent-menu-item has-children">
				<a href="<?= get_permalink($parent->ID); ?>" class="parent-menu-item-link<?= $parent_link_active; ?>">
					<?= $parent_title; ?><i class="fas fa-angle-down"></i>
				</a>
				<ul class="child-menu">
					<?php foreach ($children as $child): 
          $child_link_active = $post->ID == $child->ID ? " active" : "";
          ?>
					<li class="child-menu-item">
						<a href="<?= get_permalink($child->ID); ?>" class="child-menu-item-link<?= $child_link_active; ?>">
							<?= get_field("navigation_text", $child->ID); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</li>
	<?php else: ?>
			<li class="parent-menu-item">
				<a href="<?= get_permalink($parent->ID); ?>" class="parent-menu-item-link<?= $parent_link_active; ?>">
					<?= $parent_title; ?>
				</a>
			</li>
	<?php endif; ?>
<?php endforeach; ?>
		</ul>
	</nav>
</header>
<?php endif; ?>