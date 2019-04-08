<?php

return [
	'routes' => [
		'collection:object:auto_sitemap' => [
			'path' => 'auto_sitemap/{type}',
			'resource' => 'auto_sitemap/default',
		],
		'auto_sitemap:object:xmlview' => [
			'path' => 'sitemap.xml',
			'resource' => 'auto_sitemap/default',
		],
		'auto_sitemap:object:xslview' => [
			'path' => 'sitemap.xsl',
			'resource' => 'auto_sitemap/custom',
		],
	],
];
