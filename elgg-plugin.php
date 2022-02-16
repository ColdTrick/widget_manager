<?php

if (!defined('ACCESS_LOGGED_OUT')) {
	define('ACCESS_LOGGED_OUT', -5);
}

$composer_path = '';
if (is_dir(__DIR__ . '/vendor')) {
	$composer_path = __DIR__ . '/';
}

return [
	'plugin' => [
		'version' => '12.0.3',
		'dependencies' => [
			'profile' => [
				'must_be_active' => false,
				'position' => 'after',
			],
			'groups' => [
				'must_be_active' => false,
				'position' => 'after',
			],
		],
	],
	'bootstrap' => \ColdTrick\WidgetManager\Bootstrap::class,
	'settings' => [
		'widget_layout' => '33|33|33',
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
		'widget_manager/fluid_order' => [],
	],
	'views' => [
		'default' => [
			'muuri.js' => $composer_path . 'vendor/npm-asset/muuri/dist/muuri.js',
		],
	],
	'view_extensions' => [
		'css/admin' => [
			'css/widget_manager/admin.css' => [],
		],
		'elements/widgets.css' => [
			'css/widget_manager/site.css' => [],
		],
		'groups/edit/settings' => [
			'widget_manager/forms/groups_widget_access' => [],
		],
		'js/elgg' => [
			'js/widget_manager/site.js' => [],
		],
		'object/widget/elements/content' => [
			'widget_manager/widgets/custom_more' => [],
		],
		'object/widget/header' => [
			'object/widget/toggle' => ['priority' => 400],
		],
		'page/layouts/widgets/add_panel' => [
			'widget_manager/group_tool_widgets' => ['priority' => 400],
		],
	],
	'view_options' => [
		'forms/widget_manager/widget_page' => ['ajax' => true],
		'widget_manager/widgets/settings' => ['ajax' => true],
		'widgets/user_search/content' => ['ajax' => true],
	],
	'events' => [
		'create' => [
			'object' => [
				'\ColdTrick\WidgetManager\Widgets::fixPrivateAccess' =>[],
			],
		],
	],
	'hooks' => [
		'tool_options' => [
			'group' => [
				'\ColdTrick\WidgetManager\Groups::registerGroupWidgetsTool' => [],
			],
		],
	],
];
