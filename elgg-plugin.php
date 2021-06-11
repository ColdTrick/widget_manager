<?php

define('ACCESS_LOGGED_OUT', -5);

require_once(dirname(__FILE__) . '/lib/functions.php');

$composer_path = '';
if (is_dir(__DIR__ . '/vendor')) {
	$composer_path = __DIR__ . '/';
}

return [
	'bootstrap' => \ColdTrick\WidgetManager\Bootstrap::class,
	'settings' => [
		'lazy_loading_enabled' => 0,
		'lazy_loading_mobile_columns' => 1,
		'lazy_loading_under_fold' => 5,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'widget',
			'class' => 'WidgetManagerWidget',
		],
		[
			'type' => 'object',
			'subtype' => 'widget_page',
			'class' => 'WidgetPage',
		],
	],
	'upgrades' => [
		'ColdTrick\WidgetManager\Upgrades\CreateWidgetPages',
	],
	'actions' => [
		'widget_manager/lazy_load_widgets' => [
			'access' => 'public',
		],
		'widget_manager/groups/update_widget_access' => [],
		'widget_manager/widgets/toggle_collapse' => [],
		'widget_manager/widgets/toggle_fix' => [
			'access' => 'admin',
		],
		'widget_manager/force_tool_widgets' => [
			'access' => 'admin',
		],
		'widget_manager/manage_widgets' => [
			'access' => 'admin',
		],
		'widget_manager/widget_page' => [
			'access' => 'admin',
		],
		'widget_manager/cleanup' => [
			'access' => 'admin',
		],
	],
	'views' => [
		'default' => [
			'packery.js' => $composer_path . 'vendor/npm-asset/packery/dist/packery.pkgd.js',
		],
	],
];
