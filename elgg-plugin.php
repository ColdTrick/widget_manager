<?php

define('ACCESS_LOGGED_OUT', -5);

require_once(dirname(__FILE__) . '/lib/functions.php');
require_once(dirname(__FILE__) . '/lib/hooks.php');

return [
	'bootstrap' => \ColdTrick\WidgetManager\Bootstrap::class,
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
	],
];
