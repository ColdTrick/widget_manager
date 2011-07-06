<?php 
// Load Twitter JS
if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on"){
	// load normal js
	?><script type="text/javascript" src="http://widgets.twimg.com/j/2/widget.js"></script><?php
} else {
	// load secure js
	?><script type="text/javascript" src="https://twitter-widgets.s3.amazonaws.com/j/2/widget.js"></script><?php
}
?>