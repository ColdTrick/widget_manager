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

.widget_manager_widget_actions {
	float: right;
	margin: 2px 2px 0 0;
}

.widget_manager_widget_description {
	background: url("<?php echo $vars["url"]; ?>_graphics/icon_customise_info.gif") no-repeat scroll left top transparent;
	cursor: help;
	width: 14px;
	height: 14px;
	display: inline-block;
}

.widget_manager_widget_move {
	background: url("<?php echo $vars["url"]; ?>_graphics/icon_customise_drag.gif") no-repeat scroll left top transparent;
	cursor: move;
	width: 15px;
	height: 15px;
	display: inline-block;
}

/* tooltips */
.widget_manager_more_info {
	width: 14px;
	height: 14px;
	float: right;
	background: url(<?php echo $vars['url'];?>_graphics/icon_customise_info.gif);
	cursor: pointer;
}

#widget_manager_more_info_tooltip {
	position:absolute;
	border:1px solid #333333;
	background:#e4ecf5;
	color:#333333;
	padding:5px;
	display:none;
	width: 250px;
	line-height: 1.2em;
	font-size: 90%;
}

.widget_manager_more_info_tooltip_text {
	display: none;
}

/* default widgets */
div.widget_manager_default_info {
	font-size: 80%;
	margin: 0 10px 10px;
    padding: 5px;
    border-bottom: 1px dotted #CCCCCC;
}

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

/* lightbox */
#widget_manager_widgets_select .widget_manager_widgets_lightbox_wrapper {
	width: 100%;
	margin-bottom: 5px;
	border: 1px solid transparent;
	padding: 2px;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_wrapper:hover {
	border: 1px solid #CCCCCC;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_actions {
	float: right;
	margin: 0 5px;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_actions .submit_button {
	margin: 5px 0;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_title {
	font-weight: bold;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_description {
	color: #CCCCCC;
	margin-left: 19px;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_present,
#widget_manager_widgets_select .widget_manager_widgets_lightbox_not_present {
	width: 16px;
	height: 16px;
	display: inline-block;
	margin: 2px 3px 0 0;
	float: left;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_present {
	background: url("<?php echo $vars["url"]; ?>mod/widget_manager/_graphics/present.png") no-repeat top left;
}

#widget_manager_widgets_select .widget_manager_widgets_lightbox_present:hover {
	cursor: help;
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

/* target drop area class */
.widgetplaceholder {
	border:2px dashed #AAA;
	margin: 0px 0 20px 0;
}

.widget_manager_widget_placeholder {
	border: 2px dashed #AAA;
	margin: 0px 0 0px 0;
}

/* class of widget while dragging */
#widgets_left .ui-sortable-helper,
#widgets_middle .ui-sortable-helper,
#widgets_right .ui-sortable-helper {
	width:303px;
}

/* make empty cols droppable */
#widgets_top.ui-sortable {
	min-height:100px;
	margin-bottom: 20px;
}

#widgets_left.ui-sortable,
#widgets_middle.ui-sortable,
#widgets_right.ui-sortable{
	min-height:300px;
}

/* show droppable highlighting */
#widgets_top.ui-droppable {
	background-color: #F1F1F1;
}

/* hide standard edit button */
a.toggle_customise_edit_panel{
	display:none;
}

/* 'edit page' button */

#toggle_customise_edit_panel a.toggle_customise_edit_panel_override { 
	padding: 5px 10px 5px 10px;
	display: block;
	text-align: left;
	border:1px solid #cccccc;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	margin: 0 0 10px 0;
}

#toggle_customise_edit_panel a.toggle_customise_edit_panel_override:hover { 
	background: #0054a7;
	border:1px solid #0054a7;
	color: #ffffff;
	text-decoration:none;
}

.collapsable_box_header div.widget_remove_button {
	margin: 0px;
	float:right;
	cursor: pointer;
	width:16px;
	height:16px;
	background: url("<?php echo $vars['url']; ?>mod/widget_manager/_graphics/widget_sprites.png") no-repeat 0 -16px;
}

.collapsable_box_header div.widget_remove_button:hover {
	background-position: 0 0px;
}

.collapsable_box_header a.toggle_box_edit_panel {
	width:16px;
	height:16px;
	background: url("<?php echo $vars['url']; ?>mod/widget_manager/_graphics/widget_sprites.png") no-repeat 0 -48px;
	margin: 0px;
	display: block !important;
}

.collapsable_box_header a.toggle_box_edit_panel:hover {
	background-position: 0 -32px;
}

.collapsable_box_header .widget_tools_wrapper {
	float: right;
	width: 32px;
}

.collapsable_box_header a.toggle_box_contents {
	float: left;
	line-height: 18px;
	margin: 0;
	width: 14px;
}

#widgets_top .widget_manager_broken_widget,
#widgets_left .widget_manager_broken_widget,
#widgets_middle .widget_manager_broken_widget,
#widgets_right .widget_manager_broken_widget {
	display: none;
}

#widgets_top .draggable_widget,
#widgets_left .draggable_widget,
#widgets_middle .draggable_widget,
#widgets_right .draggable_widget{
	cursor: move;
}

#widgets_top .collapsable_box_header h1 a:hover,
#widgets_left .collapsable_box_header h1 a:hover,
#widgets_middle .collapsable_box_header h1 a:hover,
#widgets_right .collapsable_box_header h1 a:hover {
	text-decoration: none;
	<!--  background: transparent;  --> /* ie fix */
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
#widget_manager_frontpage_reorder_form {
	display: none;
}

#widget_table .widget_manager_widget_index_members .usericon {
	float: left;
	margin: 0 3px 3px 0;
}

#widgets_top.frontpage_widgets_top_left {
	float: left;
}

#widgets_top.frontpage_widgets_top_right {
	float: right;
}

#widgets_top.frontpage_widgets_top_75 {
	width: 702px;
}
#widgets_top.frontpage_widgets_top_66 {
	width: 626px;
}
#widgets_top.frontpage_widgets_top_50 {
	width: 485px;
}

#widgets_top.frontpage_widgets_top_full {
	width: 100%;
}


#frontpage_widgets_right_column {
	float: right;
}

#frontpage_widgets_middle_column {
	float: right;
}

#frontpage_widgets_left_column {
	float: left;
}

#widgets_left.frontpage_widgets_layout_75,
#widgets_right.frontpage_widgets_layout_75 {
	width: 692px;
}

#widgets_left.frontpage_widgets_layout_60,
#widgets_right.frontpage_widgets_layout_60 {
	width: 555px;
}

#widgets_left.frontpage_widgets_layout_50,
#widgets_middle.frontpage_widgets_layout_50,
#widgets_right.frontpage_widgets_layout_50 {
	width: 454px;
}

#widgets_left.frontpage_widgets_layout_50_50,
#widgets_right.frontpage_widgets_layout_50_50 {
	width: 464px;
}

#widgets_left.frontpage_widgets_layout_50 {
	margin: 0 10px 20px 0px;
}

#widgets_right.frontpage_widgets_layout_50 {
	margin: 0 0px 20px 10px;
} 

#widgets_left.frontpage_widgets_layout_40,
#widgets_right.frontpage_widgets_layout_40 {
	width: 374px;
}

#widgets_left.frontpage_widgets_layout_25,
#widgets_middle.frontpage_widgets_layout_25,
#widgets_right.frontpage_widgets_layout_25 {
	width: 237px;
}

#widgets_left.frontpage_widgets_layout_75,
#widgets_left.frontpage_widgets_layout_60,
#widgets_left.frontpage_widgets_layout_50_50,
#widgets_left.frontpage_widgets_layout_40,
#widgets_left.frontpage_widgets_layout_25 {
	margin: 0 10px 20px 0;
}

#widgets_right.frontpage_widgets_layout_75,
#widgets_right.frontpage_widgets_layout_60,
#widgets_right.frontpage_widgets_layout_50_50,
#widgets_right.frontpage_widgets_layout_40,
#widgets_right.frontpage_widgets_layout_25 {
	margin: 0 0 20px 10px;
}

#widgets_left.frontpage_widgets_layout_0,
#widgets_middle.frontpage_widgets_layout_0,
#widgets_right.frontpage_widgets_layout_0 {
	width: 0px;
	margin: 0 10px 10px 0;
	display: none;
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

/* Index login widget */
#widget_manager_login_form #socialink_login.contentWrapper {
	margin: 0;
	padding: 0;
}