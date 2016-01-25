<?php

if (elgg_is_logged_in()) {
	echo elgg_echo('widget_manager:widgets:index_login:welcome', [elgg_get_logged_in_user_entity()->name, elgg_get_site_entity()->name]);
	return;
}
	
$login_url = elgg_get_site_url();
if (elgg_get_config('https_login')) {
	$login_url = str_replace('http:', 'https:', elgg_get_site_url());
}

echo elgg_view_form('login', ['action' => "{$login_url}action/login"], ['returntoreferer' => TRUE]);
