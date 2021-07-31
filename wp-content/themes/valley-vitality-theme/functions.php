<?php
require_once "Mail.php";

function valley_vitality_files() {
	wp_enqueue_script("script-js", get_theme_file_uri("/js/script.js"), null, '1.0', true);
	wp_enqueue_style("google", "//fonts.googleapis.com/css?family=Roboto+Condensed|Roboto+Slab&display=swap");
	wp_enqueue_style("font-awesome", "//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css");
	wp_enqueue_style("valley_vitality_styles", get_stylesheet_uri());
}

function valley_vitality_features() {
  add_theme_support("title-tag");
}

function valley_vitality_post_types() {
  register_post_type("contact", [
    "public"      => true,
		"has_archive" => "contacts",
    "labels"      => [
      "name"          => "Contacts",
			"add_new_item"  => "Add New Contact",
			"edit_item"     => "Edit Contact",
			"all_items"     => "All Contacts",
			"singular_name" => "Contact"
    ],
    "menu_icon"    => "dashicons-universal-access",
    "supports"     => [
      "title"
    ]
  ]);
}

function contact_us_handler() {
	date_default_timezone_set("America/Los_Angeles");
	$now       = date("mdY_hisa");
	$submitted = date("m/d/Y h:i:s a");
	$title = strtolower(trim($_POST['first'])."_".trim($_POST['last'])."_".$now);
	$title = str_replace(" ", "_", wp_strip_all_tags($title));
	$contact = [
		'post_type'   => 'contact',
		'post_title'  => $title,
		'post_status' => 'publish'
	];
	$contact_id = wp_insert_post($contact);
	$fields = [
		'first', 
		'last', 
		'preference', 
		'email', 
		'phone', 
		'problems',
		'submitted'
	];
	
	$arr = [];

	foreach($fields as $field) {
		switch ($field) {
			case 'email':
				$value = filter_var(trim($_POST[$field]), FILTER_SANITIZE_EMAIL);
				break;
			case 'phone':
				$value = filter_var(trim($_POST[$field]), FILTER_SANITIZE_NUMBER_INT);
				$value = str_replace("+", "", $value);
				break;
			case 'submitted':
				$value = $submitted;
				break;
			default:
				$value = filter_var(trim($_POST[$field]), FILTER_SANITIZE_STRING);
				break;
		}
		$arr[$field] = $value;
		update_field($field, $value, $contact_id);
	}
	
	$host = "ssl://hgws25.win.hostgator.com";
	$user = "me@projectsbyscott.com";
	$pass = "5Z@wt1q2";
	$port = "465";
	
	$to   = "me@projectsbyscott.com";
	$from = "me@projectsbyscott.com";
 	$subj = "New Contact";
	$link = get_the_permalink($contact_id);
  	$msg  = "<html><head><title>New Contact</title></head><body>";
  	$msg .= "<p>Hello, Sarah.</p>";
  	$msg .= "<p>{$arr['first']} {$arr['last']} has submitted the 'Contact Us' form.</p>";
	$msg .= "<p>To view the details of this request, click on the link below:</p>";
	$msg .= "<p><a href=\"{$link}\">".$link."</a></p>";
	$msg .= "</body>";
	$msg .= "</html>";
	$hdrs = [
		'To'           => $to, 
		'From'         => $from, 
		'Subject'      => $subj, 
		'MIME-Version' => 1, 
		'Content-type' => 'text/html;charset=UTF-8'
	];
	$smtp = Mail::factory('smtp', [
			'host' => $host,
			'port' => $port,
			'auth' => true,
			'username' => $user,
			'password' => $pass
		]);
	$mail = $smtp->send($to, $hdrs, $msg);
	
	$to   = $arr['email'];
	$from = "me@projectsbyscott.com";
	$subj = "Welcome to Valley Wellness Collective";
  	$msg  = "<html><head><title>Welcome</title></head><body>";
  	$msg .= "<p>Hello, {$arr['first']}.</p>";
  	$msg .= "<p>Thank you for completing our 'Contact Us' form.</p>";
	$msg .= "<p>A representative from Valley Wellness Collective will contact you shortly to discuss your health-related goals.</p>";
	$msg .= "</body>";
	$msg .= "</html>";
	$hdrs['To']      = $to;
	$hdrs['Subject'] = $subj;	
	if (!empty($to)) {
		$mail = $smtp->send($to, $hdrs, $msg);
	}
	
	wp_redirect(home_url());
	exit();
}

function customize_menu_bar($wp_admin_bar) {
	global $wp_admin_bar;
	
	$arr = [
		"wp-logo", 
		"updates", 
		"comments", 
		"new-content", 
		"customize", 
		"search",
		"edit",
		"user-info",
		"edit-profile",
		"themes",
		"menus",
		"logout",
		"archive",
		"view"
	];
	
	foreach($arr as $item) {
		$wp_admin_bar->remove_menu($item);
	}
	
	$wp_admin_bar->add_node([
		"id"        => "my-contacts",
		"title"     => "Contacts",
		"href"      => site_url("/contacts"),
		"parent"    => "site-name"
	]);	
}

function set_logout_menu($wp_admin_bar) {
	// get my-account menu node for current values
	$my_account = $wp_admin_bar->get_node("my-account");

	// replace 'Howdy' with new text
	$new_title =  "Log Out, ".str_replace("Howdy, ", "", $my_account->title);

	// rebuild menu using old node values and new title
	$wp_admin_bar->add_menu([
		"id"     => $my_account->id,
		"parent" => $my_account->parent,
		"title"  => $new_title,
		"href"   => wp_logout_url(),
		"group"  => $my_account->group,
		"meta"   => [
			"class" => $my_account->meta["class"],
			"title" => $my_account->meta["title"],
		],
	 ]);	
}

function remove_admin_bar() {
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
}

add_action("wp_enqueue_scripts", "valley_vitality_files");
add_action("after_setup_theme", "valley_vitality_features");
add_action("init", "valley_vitality_post_types");
add_action("admin_post_add_contact", "contact_us_handler");
add_action("admin_post_nopriv_add_contact", "contact_us_handler");
add_action('after_setup_theme', 'remove_admin_bar');
add_action("wp_before_admin_bar_render", "customize_menu_bar");
add_action("admin_bar_menu", "set_logout_menu");
?>