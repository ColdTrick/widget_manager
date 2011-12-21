<?php 
?>
#widget_manager_manage_settings {
	display: none;
}

.widget_manager_table_layout {
	width: 100%;
}

.widget_manager_table_layout th {
	font-weight: bold;
}

.widget_manager_table_layout th,
.widget_manager_table_layout td {
	width: 1%;
	white-space: nowrap;
	text-align: center;
	padding-right: 10px;
}

.widget_manager_table_layout span:hover {
	cursor: help;
}

td.widget_manager_table_title,
th.widget_manager_table_title {
	width: 100%;
	text-align: left;
}

.widget_manager_widget_wrapper {
	border: 1px solid #CCCCCC;
	margin: 3px;
}

.widget_manager_widget_title {
	font-weight: bold;
}


/* default widgets */

span.widget_manager_default_fix {
	width: 18px;
	height: 20px;
	background: url("<?php echo $vars["url"]; ?>mod/widget_manager/_graphics/fix.gif") no-repeat top left;
	display: inline-block;
	cursor: pointer;
	vertical-align: text-top;
}
span.widget_manager_default_fix:hover,
span.widget_manager_default_fix.fixed {
	background-position: 0 -20px;
}


/* Groups widgets */
#widget_manager_groups_reorder_form {
	display: none;
}

#widget_table.group_widgets_layout {
	background: white;
	padding-top: 10px;
}

#widget_table.group_widgets_layout #widgets_left,
#widget_table.group_widgets_layout #widgets_right{
	margin: 0;
	width: 100%;
}

#widget_table.group_widgets_layout #widgets_left {
	min-height: 1px;
}

#widget_table.group_widgets_layout #widgets_left.ui-droppable {
	min-height: 50px;
}

#widget_table.group_widgets_layout #left_column,
#widget_table.group_widgets_layout #right_column{
	margin: 0px;
	width: 350px;
}

#widget_table.group_widgets_layout #widgets_middle{
	margin: 0;
	width: auto;	
}

/* Frontpage widgets */

#widget_table .widget_manager_widget_index_members .usericon {
	float: left;
	margin: 0 3px 3px 0;
}

/* adjustments if header is hidden */
div.collapsable_box_content.widget_manager_hide_header {
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}

div.collapsable_box_content.widget_manager_disable_widget_content_style {
	border-bottom: 1px solid transparent;
    border-left: 1px solid transparent;
    border-right: 1px solid transparent;
	margin: 0;
	padding: 0;
	background: none;
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
}

div.collapsable_box_header.widget_manager_hide_header_admin {
	opacity: 0.6;
	filter: alpha(opacity=60);
}

div.collapsable_box_editpanel select {
	max-width: 100%;
}
