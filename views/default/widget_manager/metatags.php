<?php
	/**
	* Widget Manager - Javascript / JQuery
	* 
	* @package widget_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	$target_column = "widgets_right";
	if($configured_target_column = get_plugin_setting("target_column", "widget_manager")){
		$target_column = $configured_target_column;
	}
	
	$context = get_context();
	
	if((page_owner() && page_owner_entity()->canEdit()) || ($context == "index" && isadminloggedin())){
	
		if($context == "index"){
			$add_url_postfix = "&owner_guid=" . $vars["config"]->site_guid;
		} else {
			$add_url_postfix = "&owner_guid=" . page_owner();
		}
		
		global $fancybox_js_loaded;
		
		if(empty($fancybox_js_loaded)){
			$fancybox_js_loaded = true;
			?>
			<script type="text/javascript" src="<?php echo $vars["url"];?>mod/widget_manager/vendors/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
			<?php 
		}
		?>
	<script type="text/javascript">
		function saveNewWidget(name){
			var guid = $("form[action='<?php echo $vars['url'];?>action/widgets/reorder'] input[name='owner']").val();
			
			$.post("<?php echo $vars['url'];?>pg/widget_manager/widgets/add", { owner_guid: guid, widget_handler: name, context: "<?php echo $context; ?>", column: 3 },function(data){
				// adding the widget to the right column
				if($('#<?php echo $target_column; ?> .free_widgets').length > 0){
					$('#<?php echo $target_column; ?> .free_widgets:first').before(data);
				} else {
					$('#<?php echo $target_column; ?>').append(data);
				}
		
				// enable collapse of edit widget link
				$('#<?php echo $target_column; ?> .free_widgets:first a.toggle_box_edit_panel').click(function () {
					$(this).parents('div.collapsable_box').children("[class=collapsable_box_editpanel]").slideToggle("fast");
					return false;
				});
		
				// binding click event to minimize widget event
				$('#<?php echo $target_column; ?> .free_widgets:first a.toggle_box_contents').bind('click', toggleWidgetContent);
			});
		}
		
		function deleteWidget(elem){
			if(confirm("<?php echo elgg_echo('widget_manager:delete');?>")){
				$(elem).parents('.collapsable_box').parent().remove();
				saveWidgetOrder();
			}
		}
		
		// function to fix default widgets
		function widget_manager_fix_widget(elem, guid){
			var url = "<?php echo elgg_add_action_tokens_to_url($vars["url"] . "action/widget_manager/widgets/toggle_fix"); ?>";
			$.post(url, {guid: guid}, function(data){
				if(data){
					$(elem).toggleClass("fixed");	
					
					$(elem).parents("div.collapsable_box").parent().toggleClass("free_widgets");
					$(elem).parents("div.collapsable_box_header").toggleClass("fixed_widget").toggleClass("draggable_widget");
				}
			});
		}
		
		$(document).ready(function(){
			
			<?php 
			if($context != "groups"){
				?>
				$('#widgets_right').prepend("<div id=\"toggle_customise_edit_panel\"><a href=\"<?php echo $vars["url"]; ?>pg/widget_manager/widgets/lightbox?context=<?php echo $context . $add_url_postfix; ?>\" class=\"toggle_customise_edit_panel_override\"><?php echo elgg_echo("widget_manager:add");?></a></div>");
				<?php 
			}
			?>
			$("a.toggle_customise_edit_panel_override").fancybox({
				height: 600,
				width: 600,
				autoDimensions: false
			});
				
			var cols = ['#widgets_left', '#widgets_middle', '#widgets_right', '#widgets_top'];
			var $cols = $(cols.toString());
		
			$cols.sortable({
				cursor: 'move',
				items: '.free_widgets',
				revert: true,
				opacity: 0.6,
				placeholder: 'widgetplaceholder',
				forcePlaceholderSize: true,
				connectWith: cols,
				zIndex:9000,
				cancel: ".collapsable_box_editpanel, .collapsable_box_content",
				start: function(e,ui){
					$cols.addClass("ui-droppable");
				},
				stop: function(e,ui) {
					$cols.removeClass("ui-droppable");
					$(this).sortable( "refresh" );						
					saveWidgetOrder();
				}	
			});
		});
	</script>
<?php } ?>