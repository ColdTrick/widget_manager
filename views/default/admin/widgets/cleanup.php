<?php

use Elgg\Database\Select;

echo elgg_view('output/longtext', ['value' => elgg_echo('admin:widgets:cleanup:info')]);

$select = Select::fromTable('entities', 'e');
$select->join('e', 'metadata', 'md_handler', 'md_handler.entity_guid = e.guid');
$select->join('e', 'metadata', 'md_context', 'md_context.entity_guid = e.guid');
$select->select('md_handler.value AS handler');
$select->addSelect('md_context.value AS context');
$select->addSelect('count(*) AS total');
$select->where('e.type = "object"');
$select->andWhere('e.subtype = "widget"');
$select->andWhere('md_handler.name = "handler"');
$select->andWhere('md_context.name = "context"');
$select->groupBy('md_handler.value');
$select->addGroupBy('md_context.value');
$select->orderBy('md_handler.value, md_context.value');

$res = elgg()->db->getData($select);

$handler = '';
foreach ($res as $row) {
	if ($row->handler !== $handler) {
		$handler = $row->handler;
		$title = elgg_format_element('b', [], $handler);
		echo elgg_format_element('div', ['class' => 'mtm'], $title);
	}
	
	$line = "{$row->context} ({$row->total})";
	$line .= elgg_view('output/url', [
		'text' => elgg_echo('delete'),
		'href' => elgg_generate_action_url('widget_manager/cleanup', [
			'handler' => $handler,
			'context' => $row->context,
		]),
		'confirm' => true,
		'class' => 'mlm',
	]);
	
	echo elgg_format_element('div', ['class' => 'pll'], $line);
}
