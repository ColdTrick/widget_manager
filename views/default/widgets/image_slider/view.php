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
	
	if($widget->canEdit()){
?>
<script type="text/javascript">
	$(document).ready(function() {

		$(".image_slider_settings>h3").live("click", function(){
			$(this).next().toggle();
		});
	});
</script>
<?php }?>

<style type="text/css" media="screen">

.image_slider_settings > span {
	display: none;
}

.image_slider_settings > h3 {
	cursor: pointer;
}

#<?php echo $object_id; ?> {
	width: 100%; /* important to be same as image width */
	height: <?php echo $slider_height; ?>px; /* important to be same as image height */
	position: relative; /* important */
	overflow: hidden; /* important */
	float: left;
}

#<?php echo $object_id; ?>Content {
	width: 100%; /* important to be same as image width or wider */
	position: absolute;
	top: 0;
	margin: 0 0 0 -20px;
	list-style-image: none !important;
}

.<?php echo $object_id; ?>Image {
	float: left;
	position: relative;
	display: none;
	width: 100%;
}

.<?php echo $object_id; ?>Image span {
	position: absolute;
	width: 100%;
	background-color: #<?php echo $overlay_color; ?>;
	filter: alpha(opacity = 80);
	-moz-opacity: 0.8;
	-khtml-opacity: 0.8;
	opacity: 0.8;
	color: #fff;
	display: none;
}

.<?php echo $object_id; ?>Image span div {
	padding: 10px 13px;
}

.<?php echo $object_id; ?>Image span strong {
	font-size: 14px;
}

#<?php echo $object_id; ?>Content .top {
	top: 0;
	left: 0;
}

#<?php echo $object_id; ?>Content .bottom {
	bottom: 0;
	left: 0;
}

#<?php echo $object_id; ?>Content .left {
	top: 0;
	left: 0;
	width: 180px !important;
	height: <?php echo $slider_height; ?>px;
}

#<?php echo $object_id; ?>Content .right {
	right: 0;
	bottom: 0;
	width: 180px !important;
	height: <?php echo $slider_height; ?>px;
}

</style>

<script type="text/javascript">
    $(document).ready(function() {
        $('#<?php echo $object_id; ?>').s3Slider({
            timeOut: <?php echo $seconds_per_slide * 1000; ?>
        });
    });
</script>
<div id="<?php echo $object_id; ?>">
	<ul id="<?php echo $object_id; ?>Content">
	
		<?php 
		
		for($i = 1; $i <= $max_slider_options; $i++){
			$direction = $widget->get("slider_" . $i . "_direction");
			$url = $widget->get("slider_" . $i . "_url");
			$text = $widget->get("slider_" . $i . "_text");
			$link = $widget->get("slider_" . $i . "_link");
			
			if(!empty($url)){
				
				$custom_slider .= "<li class='" . $object_id . "Image'>";
				
				
				if(empty($text)){
					$custom_slider .= "<span class='" . $direction ."' style='visibility: hidden;'>";	
				} else {
					$custom_slider .= "<span class='" . $direction ."'>";	
				}
				
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
			<li class="<?php echo $object_id; ?>Image">
				<img src="http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/1.jpg">
				<span class="top">
					<div>
						<strong>Lorem ipsum dolor</strong><br>
						Consectetuer adipiscing elit. Donec eu massa vitae arcu laoreet
						aliquet.
					</div>
				</span>
			</li>
			<li class="<?php echo $object_id; ?>Image">
				<img src="http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/2.jpg">
				<span class="top">
					<div>
						<strong>Praesent</strong><br>
						Maecenas est erat, aliquam a, ornare eu, pretium nec, pede.
					</div>
				</span>
			</li>
			<li class="<?php echo $object_id; ?>Image">
				<img src="http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/3.jpg">
				<span class="bottom">
					<div>
						<strong>In hac habitasse</strong><br>
						Quisque ipsum est, fermentum quis, sodales nec, consectetuer sed, quam. Nulla feugiat lacinia odio.
					</div>
				</span>
			</li>
			<li class="<?php echo $object_id; ?>Image">
				<img src="http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/4.jpg">
				<span class="bottom">
					<div>
						<strong>Fusce rhoncus</strong><br>
						Praesent pellentesque nibh sed nibh. Sed ac libero. Etiam quis libero.
					</div>
				</span>
			</li>
			<li class="<?php echo $object_id; ?>Image">
				<img src="http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/5.jpg">
				<span class="top">
					<div>
						<strong>Morbi malesuada</strong><br>
						Vivamus molestie leo sed justo. In rhoncus, enim non imperdiet feugiat,	felis elit ultricies tortor.
					</div>
				</span>
			</li>
		<?php } ?>
		<div class="clearfloat <?php echo $object_id; ?>Image"></div>
	</ul>
</div>
<div class="clearfloat"></div>
<!-- // slider -->
