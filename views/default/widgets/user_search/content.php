<?php

$widget = elgg_extract('entity', $vars);

if (empty($widget)) {
	$guid = (int) get_input('guid');
	if ($guid) {
		$widget = get_entity($guid);
		if (!($widget instanceof ElggWidget)) {
			return;
		}
	}
}

echo elgg_format_element('script', [], 'require(["widget_manager/widgets/user_search"]);');

$q = sanitise_string(get_input('q'));

echo elgg_view('input/form', [
	'body' => elgg_view('input/text', [
		'name' => 'q',
		'title' => elgg_echo('search'),
		'value' => $q,
	]),
	'class' => 'widget-user-search-form',
	'onsubmit' => 'return false;',
	'rel' => $widget->guid,
]);

if (empty($q)) {
	echo elgg_echo('notfound');
	return;
}

$result = [];
$dbprefix = elgg_get_config('dbprefix');
$hidden = access_show_hidden_entities(true);

$entities = elgg_get_entities_from_relationship([
	'type' => 'user',
	'relationship' => 'member_of_site',
	'relationship_guid' => elgg_get_site_entity()->getGUID(),
	'inverse_relationship' => true,
	'joins' => ["JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid"],
	'wheres' => ["((ue.username LIKE '%{$q}%') OR (ue.email LIKE '%{$q}%') OR (ue.name LIKE '%{$q}%'))"],
]);

if ($entities) {
	foreach ($entities as $entity) {
		$entity_data = [];
		
		$entity_data[] = elgg_view('output/url', ['text' => $entity->name, 'href' => $entity->getURL()]);
		
		$entity_data[] = $entity->username;
		$entity_data[] = $entity->email;
		
		if (elgg_get_user_validation_status($entity->getGUID()) !== false) {
			$entity_data[] = elgg_echo('option:yes');
		} else {
			$entity_data[] = elgg_echo('option:no');
		}
		
		$entity_data[] = elgg_echo('option:' . $entity->enabled);
						
		$entity_data[] = htmlspecialchars(date(elgg_echo('friendlytime:date_format'), $entity->time_created));
		
		$result[] = '<td>' . implode('</td><td>', $entity_data) . '</td>';
	}
}

access_show_hidden_entities($hidden);

if (empty($result)) {
	echo elgg_echo('notfound');
	return;
}

echo '<table class="elgg-table mtm"><tr>';
echo '<th>' . elgg_echo('name') . '</th>';
echo '<th>' . elgg_echo('username') . '</th>';
echo '<th>' . elgg_echo('email') . '</th>';
echo '<th>' . elgg_echo('validated') . '</th>';
echo '<th>' . elgg_echo('enabled') . '</th>';
echo '<th>' . elgg_echo('time_created') . '</th>';
echo '</tr><tr>';
echo implode('</tr><tr>', $result);
echo '</tr></table>';
