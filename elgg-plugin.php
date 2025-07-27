<?php
require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'name' => 'Auto Sitemap',
    'version' => '6.1',
    'dependencies' => [],
	],
	'routes' => [
		'collection:object:auto_sitemap' => [
			'path' => 'auto_sitemap/{type}/{page}',
			'resource' => 'auto_sitemap/view',
		],
		'auto_sitemap:object:xmlview' => [
			'path' => 'sitemap.xml',
			'resource' => 'auto_sitemap/index',
		],
		'auto_sitemap:object:xslview' => [
			'path' => 'sitemap.xsl',
			'resource' => 'auto_sitemap/custom',
		],
	],
];
