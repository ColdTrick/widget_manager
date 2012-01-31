<?php

$md_entities = elgg_extract("md_entities", $vars);
if($md_entities){
	foreach($md_entities as $entity){
		echo $entity->title . "|";
	}
}
