<?php

$toggle_icon = ' <img src="' . elgg_get_site_url() . 'mod/auto_sitemap/graphics/toggle.png" width=15/>';

$optionsYesNo = [
		elgg_echo('option:yes') => 1,
		elgg_echo('option:no') => 0
];

$optionsSchemas = [
		'sitemaps_org_0_9' => 'Sitemaps Protocol 0.9'
];

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

$body = '<h4>' . elgg_echo('auto_sitemap:sitemap-learn-more'). '<a href="http://www.sitemaps.org/protocol.html">www.sitemaps.org</a></h4>';

// schema
$content = '<h4>' . elgg_echo('auto_sitemap:schema:title') . '</h4>';
$content .= elgg_echo('auto_sitemap:schema:description') . '<br>';
$content .= elgg_view('input/dropdown', [
		'id' => 'slScheme',
		'name' => 'params[schema]',
		'options_values' => $optionsSchemas,
		'value' => $vars['entity']->schema
]);
$content .= '<br><br>';

// max number of urls to display in sitemap
$content .= '<h4>' . elgg_echo('auto_sitemap:max_urls:title') . '</h4>';
$content .= elgg_echo('auto_sitemap:max_urls:description') . '<br>';

$max_urls = $vars["entity"]->max_urls ;
if ( ! is_numeric($max_urls) || ($max_urls < 1) ) {
	$max_urls = 5000;
}

$content .= elgg_view('input/text', [
		'id'=>'inMaxUrls',
		'name'=>'params[max_urls]',
		'value'=> $max_urls
]);
$content .= '<br><br>';

$body .= elgg_view_module(
		'inline',
		elgg_echo('auto_sitemap:basic-config:title'),
		$content
);

// main url
$content .= elgg_echo('auto_sitemap:main_url:description') . '<br>';
$content = elgg_view('input/text', [
		'id'=>'inMainUrl',
		'name'=>'params[main_url]',
		'value'=>( $vars["entity"]->main_url ? $vars["entity"]->main_url : elgg_get_site_url()
)]);

$content .='<div>';
$content .= elgg_echo('auto_sitemap:changefreq:description').'<br>';
$content .= elgg_view('input/dropdown', [
		'name' => 'params[main_url_changefreq]',
		'options_values' => $optionsChangefreq,
		'value' => $vars['entity']->main_url_changefreq
]);

$content .='</div>';
$content .='<div>';

$content .= elgg_echo('auto_sitemap:priority:description').'<br>';
$content .= elgg_view('input/dropdown', [
		'name' => 'params[main_url_priority]',
		'options_values' => $optionsPriority,
		'value' => $vars['entity']->main_url_priority
]);
$content .='</div>';

$moduleHeader = elgg_view('output/url', [
	'href' => '#toggle-main-url',
	'class' => 'elgg-toggle',
	'text' => elgg_echo('auto_sitemap:main_url:title') . $toggle_icon
]);

$body .= elgg_view_module(
		'inline',
		$moduleHeader,
		'<div id="toggle-main-url" style="display:block">' . $content . '</div>'
);

$body .= '<h2>' . elgg_echo('auto_sitemap:entity-urls:title') . '</h2>';
$body .= '<p>' . elgg_echo('auto_sitemap:entity-urls:description') . '</p>';

// Entity URLs

global $relevantEntities;

foreach ($relevantEntities as $relevantEntity) {
	// name attributes
	$entityUrl = $relevantEntity . '_url';
	$entityChangefreq = $relevantEntity . '_url_changefreq';
	$entityPriority = $relevantEntity . '_url_priority';

	$activo = ($vars['entity']->$entityUrl ? 1 : 0);

	$content = elgg_echo('auto_sitemap:module:active:entity', [elgg_echo('collection:object:' . $relevantEntity)]);
	$content .= elgg_view('input/radio', [
				'name'=>'params[' . $relevantEntity . '_url]',
				'value'=> $activo,
				'options'=>$optionsYesNo
		]);

	$content .='<div>';
		$content .= elgg_echo('auto_sitemap:changefreq:description').'<br>';
		$content .= elgg_view('input/dropdown', [
										'name' => 'params[' . $relevantEntity . '_url_changefreq]',
										'options_values' => $optionsChangefreq,
										'value' => $vars['entity']->$entityChangefreq
				]);
	$content .='</div>';
	$content .='<div>';
	$content .= elgg_echo('auto_sitemap:priority:description').'<br>';
	$content .= elgg_view('input/dropdown', [
								'name' => 'params[' . $relevantEntity . '_url_priority]',
								'options_values' => $optionsPriority,
								'value' => $vars['entity']->$entityPriority
		]);
	$content .='</div>';

	$moduleHeader = elgg_view('output/url', [
		'href' => '#toggle-' . $relevantEntity,
		'class' => 'elgg-toggle',
		'text' => 	elgg_echo('collection:object:' . $relevantEntity) . $toggle_icon
	]);

	$body .= elgg_view_module(
				'inline',
				$moduleHeader,
				'<div id="toggle-' . $relevantEntity . '" style="display:' . ( $activo ? 'block' : 'none' ) . '">' . $content . '</div>'
		);
}

// custom URLs

$body .= '<h2>' . elgg_echo('auto_sitemap:custom-urls:title') . '</h2>';
$body .= '<p>' . elgg_echo('auto_sitemap:custom-urls:description') . '</p>';

$frequencies = ['always' , 'hourly' , 'daily' ,'weekly' , 'monthly', 'yearly', 'never'];
foreach ($frequencies as $frequency) {
	// name attributes
	$entityUrl = $frequency . '_url';
	$entityPriority = $frequency . '_url_priority';
	$activo = ( !empty($vars['entity']->$entityUrl) || $vars['entity']->$entityUrl != "");
	$content = elgg_echo('auto_sitemap:changefreq_url:description');
	$content .= elgg_view('input/plaintext', [
				'id'=>'inAlwaysUrl',
				'name'=>'params[' . $frequency . '_url]',
				'value'=>$vars["entity"]->$entityUrl]
		);

	$content .= elgg_echo('auto_sitemap:priority:description').'<br>';
	$content .= elgg_view('input/dropdown', [
				'name' => 'params[' . $frequency . '_url_priority]',
				'options_values' => $optionsPriority,
				'value' => $vars['entity']->$entityPriority
		]);

	$moduleHeader = elgg_view('output/url', [
		'href' => '#toggle-' . $frequency,
		'class' => 'elgg-toggle',
		'text' => elgg_echo('auto_sitemap:module:header:changefreq') . ' ' . elgg_echo('auto_sitemap:updatefreq:' . $frequency) . $toggle_icon
	]);

	$body .= elgg_view_module(
				'inline',
				$moduleHeader,
				'<div id="toggle-' . $frequency . '" style="display:' . ( $activo ? 'block' : 'none' ) . '">' . $content . '</div>'
		);
}

elgg_import_esm("plugins/auto_sitemap/settings");
?>

<style>
	form{
		margin: 0 auto;
	}

	#auto_sitemap_settings_form .elgg-head{
		margin-bottom:0px;
	}
	#auto_sitemap_settings_form .elgg-body{
		background: #f7f7f7;
		padding: 5px 5px 5px 20px;
	}
</style>

<?php
echo '<div id="auto_sitemap_settings_form">' . $body . '</div>';