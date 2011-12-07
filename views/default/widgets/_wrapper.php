<?php

	/**
	 * Elgg widget wrapper
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
	
	$widget = $vars["entity"];
	if ($widget instanceof ElggWidget) {
		$widget = new WidgetManagerWidget($widget->getGUID());
		
		$vars["entity"] = $widget;
		
		// need to have context sensitive get_widget_types to determine if it is still allowed
		$old_context = elgg_get_context();
		
		$can_edit = $widget->canEdit();
		$vars["can_edit"] = $can_edit;
		
		$temp_context = $widget->context;
		$temp_context = str_replace("default_", "", $temp_context); 
		elgg_push_context($temp_context);
		
		$widgettypes = get_widget_types();
		$vars["widgettypes"] = $widgettypes;
		
		elgg_pop_context();
		
		$handler = $widget->handler;
		
		$callback = get_input('callback');
		
		if(elgg_get_plugin_setting("remove_broken_widgets", "widget_manager") == "yes"){
			if(!array_key_exists($handler, $widgettypes) && $can_edit){
				if($widget->delete()){
					echo PHP_EOL; // need to return something or widget will be shown in default object view
					return; // no need for the rest of the code
				}
			}
		}
		
		$show_broken_widgets = false;
		if(elgg_get_plugin_setting("show_broken_widgets", "widget_manager") == "yes"){
			$show_broken_widgets = true;
		}	
		
		$lazy_loading = true;
		if((elgg_get_plugin_setting("lazy_loading_disabled", "widget_manager") == "yes") || ($widget->context == "index")){
			$lazy_loading = false;
		}
		
		$hide_widget = false;
		if(widget_manager_get_widget_setting($handler, "hide")){
			$hide_widget = true;	
		}
		
		// check if widget is broken
		if((!$show_broken_widgets && !array_key_exists($handler, $widgettypes)) || $hide_widget){
			echo "<div class='widget_manager_broken_widget'>";
			if ($can_edit) {
				echo elgg_view('widgets/editwrapper', $vars);
			} 
			echo "</div>";
		} else {
		
			if ($callback != "true") {
				if($can_edit){
					$header_class = "";
					
					if(!$widget->fixed){
						$header_class .= " draggable_widget";
						$class = "free_widgets";
					}
					
					if($widget->widget_manager_hide_header == "yes"){
						$header_class .= " widget_manager_hide_header_admin";
					}
				}
				
				if($widget->fixed){
					$header_class .= " fixed_widget";
				}
				
				if($widget->widget_manager_custom_class){
					$class .= " " . $widget->widget_manager_custom_class;
				}
				if(!empty($class)){
					$class = "class='" . trim($class) . "'";
				}
		?>
		
			<div id="widget<?php echo $widget->getGUID(); ?>" <?php echo $class;?>>
			<div class="collapsable_box">
			<?php if($can_edit || $widget->widget_manager_hide_header != "yes"){?>
			<div class="collapsable_box_header<?php echo $header_class; ?>">
				<?php echo elgg_view("widgets/header", $vars); ?>
			</div>
			<?php }?>
			<?php
		
				if ($can_edit) {
			
			?>
			<div class="collapsable_box_editpanel"><?php 
				
				echo elgg_view('widgets/editwrapper', 
								array(
										'body' => elgg_view("widgets/{$handler}/edit",$vars),
										'entity' => $widget
									  )
							   ); 
				
			?></div><!-- /collapsable_box_editpanel -->
			<?php
		
				}
			
				if($widget->widget_manager_disable_widget_content_style == "yes"){
					$widget_content_class = " widget_manager_disable_widget_content_style";
				} elseif(!$can_edit && $widget->widget_manager_hide_header == "yes"){
					$widget_content_class = " widget_manager_hide_header";
				}
				?>
				
				<div class="collapsable_box_content<?php echo $widget_content_class; ?>">
				<?php 
			
				 
				echo "<div id=\"widgetcontent{$widget->getGUID()}\">";
				
				
			} else { 
				// end if callback != "true"
				// this is a callback so we need script for avatar menu
				
				if($lazy_loading){
					if (elgg_view_exists("widgets/{$handler}/view") && array_key_exists($handler, $widgettypes)){
						if((elgg_get_context() != "default_profile") && (elgg_get_context() != "default_dashboard")){
							echo elgg_view("widgets/{$handler}/view",$vars);
						}
					} else {
						echo elgg_echo('widgets:handlernotfound');
					}
				}
				if(elgg_is_active_plugin("profile")){
				?>
				
				<script language="javascript">
					$(document).ready(function(){
				   		setup_avatar_menu();
					});
				</script>
				
				<?php
				}
			}
			
			if(!$lazy_loading){
				if (elgg_view_exists("widgets/{$handler}/view") && array_key_exists($handler, $widgettypes)){
					if((elgg_get_context() != "default_profile") && (elgg_get_context() != "default_dashboard")){
						echo elgg_view("widgets/{$handler}/view",$vars);
					}
				} else {
					echo elgg_echo('widgets:handlernotfound');
				}
			}
			if ($callback != "true") {
				if($lazy_loading){
					echo elgg_view('ajax/loader');
				}
				
				?>
			</div>
			</div><!-- /.collapsable_box_content -->
			</div><!-- /.collapsable_box -->	
			</div>
			
			<script type="text/javascript">
			$(document).ready(function() {
				<?php if($lazy_loading){ ?>
				$("#widgetcontent<?php echo $widget->getGUID(); ?>").load("<?php echo $vars['url']; ?>view/<?php echo $widget->getGUID(); ?>?shell=no&username=<?php echo elgg_get_page_owner_entity()->username; ?>&context=widget&callback=true");
				<?php } ?>
				// run function to check for widgets collapsed/expanded state
				var forWidget = "widget<?php echo $widget->getGUID(); ?>";
				widget_state(forWidget);
			
			
			});
			</script>
		<?php
		
			}
		}	
	}