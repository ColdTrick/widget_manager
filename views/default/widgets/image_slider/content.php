<?php

$widget = elgg_extract('entity', $vars);

$max_slider_options = 5;

$seconds_per_slide = sanitise_int($widget->seconds_per_slide);
if (empty($seconds_per_slide)) {
	$seconds_per_slide = 10;
}

$slider_height = sanitise_int($widget->slider_height);
if (empty($slider_height)) {
	$slider_height = 300;
}

$overlay_color = $widget->overlay_color;
if (empty($overlay_color)) {
	$overlay_color = '4690D6';
}

$object_id = 'slider_' . $widget->getGUID();

$slider_type = $widget->slider_type;

$configured_slides = [];
for ($i = 1; $i <= $max_slider_options; $i++) {
	$url = $widget->{'slider_' . $i . '_url'};
	if (!empty($url)) {
		
		$text = $widget->{'slider_' . $i . '_text'};
		$link = $widget->{'slider_' . $i . '_link'};
		$direction = '';
		if ($slider_type != 'flex_slider') {
			$direction = $widget->{'slider_' . $i . '_direction'};
		}
	
		$configured_slides[] = [
			'url' => $url,
			'text' => $text,
			'link' => $link,
			'direction' => $direction,
		];
	}
}

if (empty($configured_slides)) {
	$configured_slides = [[
		'url' => 'http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/1.jpg',
		'text' => '<strong>Lorem ipsum dolor</strong><br>Consectetuer adipiscing elit. Donec eu massa vitae arcu laoreet aliquet.',
		'link' => false,
		'direction' => 'top',
	],[
		'url' => 'http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/2.jpg',
		'text' => '<strong>Praesent</strong><br>Maecenas est erat, aliquam a, ornare eu, pretium nec, pede.',
		'link' => false,
		'direction' => 'top',
	], [
		'url' => 'http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/3.jpg',
		'text' => '<strong>In hac habitasse</strong><br>Quisque ipsum est, fermentum quis, sodales nec, consectetuer sed, quam. Nulla feugiat lacinia odio.',
		'link' => false,
		'direction' => 'bottom',
	], [
		'url' => 'http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/4.jpg',
		'text' => '<strong>Fusce rhoncus</strong><br>Praesent pellentesque nibh sed nibh. Sed ac libero. Etiam quis libero.',
		'link' => false,
		'direction' => 'bottom',
	], [
		'url' => 'http://s3slider-original.googlecode.com/svn/trunk/example_images/wide/5.jpg',
		'text' => '<strong>Morbi malesuada</strong><br>Vivamus molestie leo sed justo. In rhoncus, enim non imperdiet feugiat, felis elit ultricies tortor.',
		'link' => false,
		'direction' => 'bottom',
	]];
}

if ($slider_type == 'flexslider') {
	
	$slides_list_items = '';
	foreach ($configured_slides as $slide) {
		
		$list_item = elgg_view('output/img', [
			'src' => $slide['url'],
			'class' => 'slider_img',
			'alt' => 'slide image',
		]);
		
		if (!empty($slide['text'])) {
			$list_item .= elgg_format_element('div', ['class' => 'flex-caption'], $slide['text']);
		}
		
		if (!empty($slide['link'])) {
			$list_item = elgg_view('output/url', ['href'> $slide['link'], 'text' => $list_item]);
		}
		
		$slides_list_items .= elgg_format_element('li', [], $list_item);
	}
	$slider = elgg_format_element('ul', ['class' => 'slides'], $slides_list_items);
	$slider = elgg_format_element('div', ['class' => 'flexslider'], $slider);
	echo elgg_format_element('div', ['id' => $object_id], $slider);

	echo elgg_format_element('link', [
		'rel' => 'stylesheet',
		'type' => 'text/css',
		'href' => elgg_normalize_url('mod/widget_manager/vendors/flexslider/flexslider.css'),
	]);
	?>
		
		<style type='text/css'>
			.flex-caption {
				background: #<?php echo $overlay_color; ?>;
				filter: alpha(opacity = 70);
				-moz-opacity: 0.7;
				-khtml-opacity: 0.7;
				opacity: 0.7;
			}
		</style>
		
		<script type='text/javascript'>
			require(['jquery', 'widget_manager/widgets/image_slider/flexslider'], function($, flex) {
		    	$('#<?php echo $object_id; ?> .flexslider').flexslider({
		    		slideshowSpeed: <?php echo $seconds_per_slide * 1000; ?>,
		    		prevText: "<?php echo elgg_echo('previous');?>",
		    		nextText: "<?php echo elgg_echo('next');?>",
					pauseOnHover: true,
	
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

	$slides_list_items = '';

	foreach ($configured_slides as $slide) {
		$style = "background-color: #{$overlay_color};";
	
		if (empty($slide['text'])) {
			$style .= 'visibility: hidden;';
		}
		if (in_array($slide['direction'], ['left', 'right'])) {
			$style .= 'height: ' . $slider_height . 'px;';
		}
	
		$slide_text = elgg_format_element('span', [
			'class' => $slide['direction'],
			'style' => $style,
		], elgg_format_element('div', [], $slide['text']));
			
		$slide_image = elgg_view('output/img', [
			'src' => $slide['url'],
			'alt' => 'slide image',
		]);

		if (!empty($slide['link'])) {
			$slide_image = elgg_view('output/url', [
				'href' => $slide['link'],
				'text' => $slide_image,
			]);
		}
		
		$slides_list_items .= elgg_format_element('li', ['class' => 'widgets_image_slider_image'], $slide_text . $slide_image);
	}
	
	echo elgg_format_element('script', [], "require(['jquery', 'widget_manager/widgets/image_slider/s3Slider'], function($) { $('#{$object_id}').s3Slider({ timeOut: {$seconds_per_slide} * 1000}); });");
		
	$slides_list_items .= elgg_format_element('div', ['class' => 'clearfix widgets_image_slider_image']);
	
	$slides_list = elgg_format_element('ul', [
		'class' => 'widgets_image_slider_content',
		'id' => "{$object_id}Content",
	], $slides_list_items);
	
	echo elgg_format_element('div', [
		'id' => $object_id,
		'class' => 'widgets_image_slider',
		'style' => "height: {$slider_height}px;",
	], $slides_list);
	
	echo elgg_format_element('div', ['class' => 'clearfix']);
}
