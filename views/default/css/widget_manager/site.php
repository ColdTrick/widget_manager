<?php
?>
/* lightbox */
#widget_manager_widgets_select {
	margin: 0;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_wrapper {
	margin-bottom: 5px;
	border: 1px solid transparent;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_wrapper:hover {
	border: 1px solid #CCCCCC;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_actions {
	float: right;
	padding: 6px;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_actions li.elgg-state-available span,
#widget_manager_widgets_select .widget_manager_widgets_lightbox_actions li.elgg-state-unavailable input {
	display: none;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_actions .submit_button {
	margin: 5px 0;
}

#widget_manager_widgets_search {
	float: right;
    margin-top: -2px;
}

#widget_manager_widgets_search input {
	padding: 0;
	margin: 0;
	font-size: 100%;
	height: 100%;
}

.widget_manager_hide_header_admin > .elgg-head {
	opacity: 0.6;
	filter: alpha(opacity=60);
}

.widget_manager_hide_header > .elgg-head {
	height: 0px;
}

.elgg-module-widget.widget_manager_disable_widget_content_style > .elgg-body,
.widget_manager_hide_header > .elgg-body {
	border-top: 0px;
}

.elgg-module-widget.widget_manager_disable_widget_content_style {
	background: none;
	padding: 0px;
}

.elgg-module-widget.widget_manager_disable_widget_content_style .elgg-widget-content {
	padding: 0px;
}

.elgg-module-widget.widget_manager_disable_widget_content_style > .elgg-head {
	border: 2px solid #DEDEDE;
}

#widget-manager-multi-dashboard {
	background: #CCC;
}

#widget-manager-multi-dashboard-tabs {
	position: absolute;
	top: 20px;
}

.widget-manager-multi-dashboard-tabs-edit {
	display: none;
	vertical-align: middle;
}

.widget-manager-multi-dashboard-tab:hover > a {
	padding-right: 5px;
}

.widget-manager-multi-dashboard-tab:hover .widget-manager-multi-dashboard-tabs-edit {
	display: inline-block;
	margin-left: 5px;
}

#widget-manager-multi-dashboard-tabs .widget-manager-multi-dashboard-tab-active {
	border-color: #666666;
}
#widget-manager-multi-dashboard-tabs .widget-manager-multi-dashboard-tab-hover {
	border-color: #333333;
}

.widget-manager-groups-widgets-top-row {
	width: 100%;
	min-height: 0px !important;
}

.widget-manager-groups-widgets-top-row-highlight {
	min-height: 50px !important;
}