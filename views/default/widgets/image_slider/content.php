<?php

	$widget = $vars["entity"];
	
	$max_slider_options = 5;
	
	$seconds_per_slide = (int) $widget->seconds_per_slide;
	if(empty($seconds_per_slide)){
		$seconds_per_slide = 10;
	}
	
	$slider_height = (int) $widget->slider_height;
	if(empty($slider_height)){
		$slider_height = 300;
	}
	
	$overlay_color = $widget->overlay_color;
	if(empty($overlay_color)){
		$overlay_color = "4690D6";
	}
	
	$object_id = "slider_" . $widget->getGUID();
	
	if($widget->slider_type == "flexslider"){
		
		echo '<div id="' . $object_id . '">';
		echo '<div class="flexslider">';
		echo '<ul class="slides">';
	
		for($i = 1; $i <= $max_slider_options; $i++){
			$url = $widget->get("slider_" . $i . "_url");
			$text = $widget->get("slider_" . $i . "_text");
			$link = $widget->get("slider_" . $i . "_link");
			
			if(!empty($url)){
	
				echo '<li>';
				
				if(!empty($link)){ 
					echo '<a href="'.$link.'">';
				}
				
				echo '<img class="slider_img" src="' . $url . '" />';
				
				if(!empty($text)){
					echo '<div class="flex-caption">' . $text . '</div>';	
				}
				
				if(!empty($link)){
					echo '</a>';
				}
				
				echo '</li>';
			}
		}
		echo '</ul></div></div>';
		?>
			<link rel="stylesheet" type="text/css" href="<?php echo $vars['url'];?>mod/widget_manager/widgets/image_slider/vendors/flexslider/flexslider.css"></link>
			<script	type="text/javascript" src="<?php echo $vars['url'];?>mod/widget_manager/widgets/image_slider/vendors/flexslider/jquery.flexslider-min.js"></script>
			<style type="text/css">
				.flex-caption {
					background: #<?php echo $overlay_color; ?>;
					filter: alpha(opacity = 70);
					-moz-opacity: 0.7;
					-khtml-opacity: 0.7;
					opacity: 0.7;
				}
			</style>
			<script type="text/javascript">
			    $(document).ready(function() {
			    	$('#<?php echo $object_id; ?> .flexslider').flexslider({
			    		slideshowSpeed: <?php echo $seconds_per_slide * 1000; ?>,
			    		prevText: "<?php echo elgg_echo("previous");?>",           
			    		nextText: "<?php echo elgg_echo("next");?>", 
						pauseOnHover: true

			    		<?php 
				    	/*
			    		animation: "fade",              //String: Select your animation type, "fade" or "slide"
			    		slideDirection: "horizontal",   //String: Select the sliding direction, "horizontal" or "vertical"
			    		slideshow: true,                //Boolean: Animate slider automatically
			    		slideshowSpeed: 7000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
			    		animationDuration: 600,         //Integer: Set the speed of animations, in milliseconds
			    		directionNav: true,             //Boolean: Create navigation for previous/next navigation? (true/false)
			    		controlNav: true,               //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
			    		keyboardNav: true,              //Boolean: Allow slider navigating via keyboard left/right keys
			    		mousewheel: false,              //Boolean: Allow slider navigating via mousewheel
			    		prevText: "Previous",           //String: Set the text for the "previous" directionNav item
			    		nextText: "Next",               //String: Set the text for the "next" directionNav item
			    		pausePlay: false,               //Boolean: Create pause/play dynamic element
			    		pauseText: 'Pause',             //String: Set the text for the "pause" pausePlay item
			    		playText: 'Play',               //String: Set the text for the "play" pausePlay item
			    		randomize: false,               //Boolean: Randomize slide order
			    		slideToStart: 0,                //Integer: The slide that the slider should start on. Array notation (0 = first slide)
			    		animationLoop: true,            //Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
			    		pauseOnAction: true,            //Boolean: Pause the slideshow when interacting with control elements, highly recommended.
			    		pauseOnHover: false,            //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
			    		controlsContainer: "",          //Selector: Declare which container the navigation elements should be appended too. Default container is the flexSlider element. Example use would be ".flexslider-container", "#container", etc. If the given element is not found, the default action will be taken.
			    		manualControls: "",             //Selector: Declare custom control navigation. Example would be ".flex-control-nav li" or "#tabs-nav li img", etc. The number of elements in your controlNav should match the number of slides/tabs.
			    		start: function(){},            //Callback: function(slider) - Fires when the slider loads the first slide
			    		before: function(){},           //Callback: function(slider) - Fires asynchronously with each slider animation
			    		after: function(){},            //Callback: function(slider) - Fires after each slider animation completes
			    		end: function(){}               //Callback: function(slider) - Fires when the slider reaches the last slide (asynchronous)
			    		*/
			    		?>
			        });
			    });
			</script>	
		<?php 
		
	} else {
	?>
	
	<script	type="text/javascript" src="<?php echo $vars['url'];?>mod/widget_manager/widgets/image_slider/vendors/s3slider/s3Slider.js"></script>
	
	<script type="text/javascript">
	    $(document).ready(function() {
	        $('#<?php echo $object_id; ?>').s3Slider({
	            timeOut: <?php echo $seconds_per_slide * 1000; ?>
	        });
	    });
	</script>
	
	<div id="<?php echo $object_id; ?>" class='widgets_image_slider' style="height: <?php echo $slider_height; ?>px;">
		<ul class='widgets_image_slider_content' id="<?php echo $object_id; ?>Content">
		
			<?php 
			
			for($i = 1; $i <= $max_slider_options; $i++){
				$direction = $widget->get("slider_" . $i . "_direction");
				$url = $widget->get("slider_" . $i . "_url");
				$text = $widget->get("slider_" . $i . "_text");
				$link = $widget->get("slider_" . $i . "_link");
				
				if(!empty($url)){
					
					$custom_slider .= "<li class='widgets_image_slider_image'>";
					
					$style = "background-color: #" . $overlay_color . ";";
					
					if(empty($text)){
						$style .= "visibility: hidden;";	
					} 
					if($direction == "left" || $direction == "right"){
						$style .= "height: " . $slider_height . "px;";
					}
					
					$custom_slider .= "<span class='" . $direction ."' style='" . $style . "'>";
					
					$custom_slider .= "<div>";
					$custom_slider .= $text; 
					$custom_slider .= "</div>";
					
					$custom_slider .= "</span>";
				
					if(!empty($link)){
						$custom_slider .= "<a href='" . $link . "'>";
					}
					$custom_slider .= "<img src='" . $url . "'>";
					if(!empty($link)){
						$custom_slider .= "</a>";
					}
					$custom_slider .= "</li>";
				}
			}
			
			if(!empty($custom_slider)){
				echo $custom_slider;
			} else {
				// echo sample data
			?>
				<li class="widgets_image_slider_image">
					<img src="http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/1.jpg">
					<span class="top" style="background-color: #<?php echo $overlay_color; ?>;">
						<div>
							<strong>Lorem ipsum dolor</strong><br>
							Consectetuer adipiscing elit. Donec eu massa vitae arcu laoreet
							aliquet.
						</div>
					</span>
				</li>
				<li class="widgets_image_slider_image">
					<img src="http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/2.jpg">
					<span class="top" style="background-color: #<?php echo $overlay_color; ?>;">
						<div>
							<strong>Praesent</strong><br>
							Maecenas est erat, aliquam a, ornare eu, pretium nec, pede.
						</div>
					</span>
				</li>
				<li class="widgets_image_slider_image">
					<img src="http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/3.jpg">
					<span class="bottom" style="background-color: #<?php echo $overlay_color; ?>;">
						<div>
							<strong>In hac habitasse</strong><br>
							Quisque ipsum est, fermentum quis, sodales nec, consectetuer sed, quam. Nulla feugiat lacinia odio.
						</div>
					</span>
				</li>
				<li class="widgets_image_slider_image">
					<img src="http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/4.jpg">
					<span class="bottom" style="background-color: #<?php echo $overlay_color; ?>;">
						<div>
							<strong>Fusce rhoncus</strong><br>
							Praesent pellentesque nibh sed nibh. Sed ac libero. Etiam quis libero.
						</div>
					</span>
				</li>
				<li class="widgets_image_slider_image">
					<img src="http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/5.jpg">
					<span class="top" style="background-color: #<?php echo $overlay_color; ?>;">
						<div>
							<strong>Morbi malesuada</strong><br>
							Vivamus molestie leo sed justo. In rhoncus, enim non imperdiet feugiat,	felis elit ultricies tortor.
						</div>
					</span>
				</li>
			<?php } ?>
			<div class="clearfloat widgets_image_slider_image"></div>
		</ul>
	</div>
	<div class="clearfloat"></div>
	<!-- // slider -->
<?php }
