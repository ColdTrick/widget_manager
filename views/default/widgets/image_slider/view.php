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
