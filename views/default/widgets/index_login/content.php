<?php
/**
 * Elgg login form
 *
 * @package Elgg
 * @subpackage Core
 */
	
	if(!elgg_is_logged_in()){
		$login_url = $vars['url'];
		if ((isset($vars["config"]->https_login)) && ($vars["config"]->https_login)) {
			$login_url = str_replace("http", "https", $vars['url']);
		}
		
		$form_body = "<p class='loginbox'>";
		$form_body .= "<label>" . elgg_echo('username') . "</label><br />";
		$form_body .= elgg_view('input/text', array('name' => 'username'));
		$form_body .= "<br />";
		$form_body .= "<label>" . elgg_echo('password') . "</label><br />";
		$form_body .= elgg_view('input/password', array('name' => 'password'));
		$form_body .= "<br />";
		
		$form_body .= elgg_view('login/extend');
		$form_body .= elgg_view('socialink/login');
		
		$form_body .= elgg_view('input/submit', array('value' => elgg_echo('login')));
		$form_body .= "<div id='persistent_login'>";
		$form_body .= "<label><input type='checkbox' name='persistent' value='true' />" . elgg_echo('user:persistent') . "</label>";
		$form_body .= "</div>";
		$form_body .= "</p>";
		
		$form_body .= "<p class='loginbox'>";
		if(!isset($vars["config"]->disable_registration) || !($vars["config"]->disable_registration)){
			$form_body .= "<a href='" . $vars['url'] . "register/'>" . elgg_echo('register') . "</a> | ";
		}
		$form_body .= "<a href='" . $vars['url'] . "account/forgotten_password.php'>" . elgg_echo('user:password:lost') . "</a></p>";
		
		$form = elgg_view('input/form', array('body' => $form_body, 'action' => $login_url . "action/login", "id" => "widget_manager_login_form"));
	} else {
		$form = sprintf(elgg_echo("widget_manager:widgets:index_login:welcome"), elgg_get_logged_in_user_entity()->name, $vars["config"]->site->name);
	}
	
	echo $form; ?>
<script type="text/javascript">
	$(document).ready(function() { $('#widget_manager_login_form input[name=username]').focus(); });
</script>