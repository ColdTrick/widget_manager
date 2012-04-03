<?php

$widget = $vars["entity"];
/* @var ElggWidget $widget */

$group = $widget->getOwnerEntity();
/* @var ElggGroup $group */

$group_guid = $group->guid;

$count = sanitise_int($widget->display_count, false);
if(empty($count)){
    $count = 4;
}

$new_link = elgg_view('output/url', array(
    'href' => "polls/add/$group_guid",
    'text' => elgg_echo('polls:addpost'),
    'is_trusted' => true,
));

$options = array(
    'type' => 'object',
    'subtype' => 'poll',
    'container_guid' => $group_guid,
    'limit' => $count,
);
$content = '';
if ($polls = elgg_get_entities($options)) {
    foreach ($polls as $poll) {
        $content .= '<div class="polls-group-widget-box">'
            . elgg_view('polls/poll_widget', array('entity' => $poll))
            . '</div>';
    }
}
if (! $content) {
    $content = '<p>' . elgg_echo("group:polls:empty") . '</p>';
}

echo $content . "<div>" . $new_link . "</div>";
