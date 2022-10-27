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
		'version' => '14.1.1',
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
		'group_column_count' => 2,
		'widget_layout' => '33|33|33',
		'lazy_loading_enabled' => 0,
		'lazy_loading_mobile_columns' => 1,
		'lazy_loading_under_fold' => 5,
		'show_collapse_content' => false,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'widget',
			'class' => 'WidgetManagerWidget',
			'capabilities' => [
				'commentable' => false,
			],
		],
		[
			'type' => 'object',
			'subtype' => 'widget_page',
			'class' => 'WidgetPage',
			'capabilities' => [
				'commentable' => false,
			],
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
		'access:collections:write' => [
			'all' => [
				'\ColdTrick\WidgetManager\Access::setWriteAccess' => ['priority' => 999],
			],
		],
		'access:collections:read' => [
			'user' => [
				'\ColdTrick\WidgetManager\Access::addLoggedOutReadAccess' => [],
			],
		],
		'action:validate' => [
			'widgets/add' => [
				'\ColdTrick\WidgetManager\Access::moreRightsForWidgetManager' => [],
			],
			'widgets/move' => [
				'\ColdTrick\WidgetManager\Access::moreRightsForWidgetManager' => [],
			],
		],
		'entity:url' => [
			'object' => [
				'\ColdTrick\WidgetManager\Widgets::getWidgetURL' => ['priority' => 9999],
			],
		],
		'group_tool_widgets' => [
			'widget_manager' => [
				'ColdTrick\WidgetManager\Widgets::groupToolWidgets' => [],
			],
		],
		'handlers' => [
			'widgets' => [
				'\ColdTrick\WidgetManager\Widgets::addDiscussionsWidgetToGroup' => [],
				'\ColdTrick\WidgetManager\Widgets::applyWidgetsConfig' => ['priority' => 9999],
			],
		],
		'permissions_check' => [
			'object' => [
				'\ColdTrick\WidgetManager\Access::canEditWidgetOnManagedLayout' => [],
			],
			'widget_layout' => [
				'\ColdTrick\WidgetManager\Widgets::layoutPermissionsCheck' => [],
			],
		],
		'prepare' => [
			'menu:widget' => [
				'\ColdTrick\WidgetManager\Menus::prepareWidgetEditDeleteMenuItems' => [],
			],
		],
		'register' => [
			'menu:entity' => [
				'\ColdTrick\WidgetManager\Menus::addWidgetPageEntityMenuItems' => [],
			],
			'menu:page' => [
				'\ColdTrick\WidgetManager\Menus::registerAdminPageMenu' => [],
			],
			'menu:title:widgets' => [
				'\ColdTrick\WidgetManager\Menus::addWidgetsContentToggle' => [],
			],
		],
		'setting' => [
			'plugin' => [
				'\ColdTrick\WidgetManager\Settings::implodeSettings' => [],
			],
		],
		'tool_options' => [
			'group' => [
				'\ColdTrick\WidgetManager\Groups::registerGroupWidgetsTool' => [],
			],
		],
		'view' => [
			'object/widget/body' => [
				'\ColdTrick\WidgetManager\Widgets::saveContentInCache' => ['priority' => 9999],
			],
		],
		'view_vars' => [
			'groups/profile/widgets' => [
				'\ColdTrick\WidgetManager\Groups::getGroupWidgetsLayout' => [],
			],
			'object/widget/body' => [
				'\ColdTrick\WidgetManager\Widgets::getContentFromCache' => [],
			],
			'object/widget/elements/controls' => [
				'\ColdTrick\WidgetManager\Widgets::preventControls' => [],
			],
		],
		'widget_settings' => [
			'all' => [
				'\ColdTrick\WidgetManager\Widgets::clearWidgetCacheOnSettingsSave' => [],
			],
		],
	],
];
