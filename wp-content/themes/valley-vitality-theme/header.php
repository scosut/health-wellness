<?php
  $style = current_user_can('administrator') ? "adminbar-on" : ""
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">	
	<link rel="icon" type="image/png" href="/img/favicon.png">
	<?php wp_head(); ?>
</head>
<body class="<?= $style; ?>">
	<div class="container">
<?php
	get_template_part("partials/content", "nav");
?>