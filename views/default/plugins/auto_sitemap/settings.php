<?php

$optionsChangefreq = [
		'disabled' => elgg_echo('auto_sitemap:updatefreq:disabled'),
		'always' => elgg_echo('auto_sitemap:updatefreq:always'),
		'hourly' => elgg_echo('auto_sitemap:updatefreq:hourly'),
		'daily' => elgg_echo('auto_sitemap:updatefreq:daily'),
		'weekly' => elgg_echo('auto_sitemap:updatefreq:weekly'),
		'monthly' => elgg_echo('auto_sitemap:updatefreq:monthly'),
		'yearly' => elgg_echo('auto_sitemap:updatefreq:yearly'),
		'never' => elgg_echo('auto_sitemap:updatefreq:never')
];

$optionsPriority = [
		'none' => elgg_echo('auto_sitemap:priority:none'),
		'0.0' => '0.0',
		'0.1' => '0.1',
		'0.2' => '0.2',
		'0.3' => '0.3',
		'0.4' => '0.4',
		'0.5' => '0.5',
		'0.6' => '0.6',
		'0.7' => '0.7',
		'0.8' => '0.8',
		'0.9' => '0.9',
		'1.0' => '1.0'
];

$max_urls = $vars['entity']->max_urls;
if (!is_numeric($max_urls) || $max_urls < 1) {
    $max_urls = 5000;
}

/*************************************************************************/

echo elgg_format_element('h4', [], elgg_echo('auto_sitemap:sitemap-learn-more') . elgg_format_element('a', [
	'href' => 'http://www.sitemaps.org/protocol.html',
	'target' => '_blank',
], 'www.sitemaps.org'));

echo elgg_format_element('br');

/********** BASIC SECTION START *********/
echo elgg_view_module('featured', elgg_echo('auto_sitemap:basic-config:title'),
	// Scheme of the sitemap
	elgg_format_element('div', ['class' => 'mtm'],
		elgg_view_field([
			'#type' => 'dropdown',
			'#label' => elgg_echo('auto_sitemap:schema:title'),
			'#help' => elgg_echo('auto_sitemap:schema:description'),
			'class' => "elgg-input-select",
	    'id' => 'slScheme',
	    'name' => 'params[schema]',
	    'options_values' => [
					'sitemaps_org_0_9' => 'Sitemaps Protocol 0.9'
			],
	    'value' => $vars['entity']->schema,
		])
	)
	.
	// Max number of URLs in each sitemap
	elgg_format_element('div', ['class' => 'mtm'],
		elgg_view_field([
			'#type' => 'text',
			'#label' => elgg_echo('auto_sitemap:max_urls:title'),
			'#help' => elgg_echo('auto_sitemap:max_urls:description'),
			'class' => "elgg-input-text",
			'id' => 'inMaxUrls',
			'name' => 'params[max_urls]',
			'value' => $max_urls,
		])
	)
);

/********** BASIC SECTION END *********/

/********** MAIN URL SECTION START *********/
echo elgg_view_module('featured', elgg_echo('auto_sitemap:main_url:title'),
	elgg_format_element('div', ['class' => 'mtm'],
		elgg_view_field([
			'#type' => 'text',
			'#label' => elgg_echo('auto_sitemap:main_url:description'),
			'class' => "elgg-input-text",
			'id'=>'inMainUrl',
			'name'=>'params[main_url]',
			'value'=> $vars["entity"]->main_url ? $vars["entity"]->main_url : elgg_get_site_url()
		])
	)	.
	elgg_format_element('div', ['class' => 'mtm'],
		elgg_view_field([
			'#type' => 'dropdown',
			'#label' => elgg_echo('auto_sitemap:changefreq:description'),
			'class' => "elgg-input-select",
			'name' => 'params[main_url_changefreq]',
			'options_values' => $optionsChangefreq,
			'value' => $vars['entity']->main_url_changefreq,
		])
	)	.
	elgg_format_element('div', ['class' => 'mtm'],
		elgg_view_field([
			'#type' => 'dropdown',
			'#label' => elgg_echo('auto_sitemap:priority:description'),
			'class' => "elgg-input-select",
			'name' => 'params[main_url_priority]',
			'options_values' => $optionsPriority,
			'value' => $vars['entity']->main_url_priority,
		])
	)
);

/********** MAIN URL SECTION END *********/

/********** ENTITY URL SECTION START *********/
global $relevantEntities;
$entityBody = "";
foreach ($relevantEntities as $relevantEntity) {
  $entityChangefreq = $relevantEntity . '_changefreq';
  $entityPriority = $relevantEntity . '_priority';
  $entityActive = $relevantEntity . '_url';
	$entityName = (elgg_echo('collection:object:' . $relevantEntity) != 'collection:object:' . $relevantEntity) ? elgg_echo('collection:object:' . $relevantEntity) : ucfirst($relevantEntity);

	$entityBody .= elgg_view_module('featured', $entityName,
		elgg_view_field([
			'#type' => 'checkbox',
			'#label' => elgg_echo('auto_sitemap:module:active:entity', [elgg_echo('collection:object:' . $relevantEntity)]),
			'name' => 'params[' . $entityActive . ']',
			'value' => 1,
			'checked' => !empty($vars['entity']->$entityActive),
			'class' => 'custom-toggle-switch',
		]) .
		elgg_view_field([
			'#type' => 'dropdown',
			'#label' => elgg_echo('auto_sitemap:changefreq:description'),
			'name' => 'params[' . $entityChangefreq . ']',
			'options_values' => $optionsChangefreq,
			'value' => $vars['entity']->$entityChangefreq,
		]) .
		elgg_view_field([
			'#type' => 'dropdown',
			'#label' => elgg_echo('auto_sitemap:priority:description'),
			'name' => 'params[' . $entityPriority . ']',
			'options_values' => $optionsPriority,
			'value' => $vars['entity']->$entityPriority,
		])
	);
}
echo elgg_view_module('info', elgg_echo('auto_sitemap:entity-urls:title'), $entityBody);
/********** ENTITY URL SECTION END *********/

/********** CUSTOM URL SECTION START *********/
$frequencies = ['always' , 'hourly' , 'daily' ,'weekly' , 'monthly', 'yearly', 'never'];
$frequencyBody = "";
foreach ($frequencies as $frequency) {
	
	$frequencyUrl = $frequency . '_url';
	$frequencyChangefreq = $frequency . '_changefreq';
	$frequencyPriority = $frequency . '_priority';
	$frequencyActive = $frequency . '_url';

	$frequencyBody .= elgg_view_module('featured', elgg_echo('auto_sitemap:module:header:changefreq') . ' ' . elgg_echo('auto_sitemap:updatefreq:' . $frequency),
		elgg_view_field([
			'#type' => 'plaintext',
			'#label' => elgg_echo('auto_sitemap:changefreq_url:description'),
			'id'=>'inAlwaysUrl',
			'name'=>'params[' . $frequencyUrl . ']',
			'value'=>$vars["entity"]->$frequencyUrl
		]) .
		elgg_view_field([
			'#type' => 'dropdown',
			'#label' => elgg_echo('auto_sitemap:priority:description'),
			'name' => 'params[' . $frequencyPriority . ']',
			'options_values' => $optionsPriority,
			'value' => $vars['entity']->$frequencyPriority,
		])
	);
}
echo elgg_view_module('info', elgg_echo('auto_sitemap:custom-urls:title'), $frequencyBody);
/********** CUSTOM URL SECTION END *********/

?>