<?php

header ("Content-Type:text/xml");

$body ='<?xml version="1.0" encoding="UTF-8"?>';
if ( $vars['flagXsl'] ) {
	$body .='<?xml-stylesheet type="text/xsl" href="' . elgg_get_site_url() . 'sitemapindex.xsl"?>
<sitemapindex
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
    http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd"
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
';
} else {
	$body .='<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
}

foreach ($vars['sitemaps'] as $entity) {
	if($entity == "custom") {
		$page = 1;
	} else {
		$type = ($entity == "user" || $entity == "group") ? $entity : "object";
		$count = elgg_count_entities([
			'type' => $type,
			'subtype' => $entity
		]);
		$max_urls = get_max_urls_count();
		$page = ceil($count/$max_urls);
	}

	for ($i = 1; $i <= $page ; $i++) {
		$body .= '<sitemap><loc>' . elgg_get_site_url() . 'auto_sitemap/' . $entity . '/' . $i . '</loc></sitemap>';
	}
}

$body .= '</sitemapindex>';

echo $body;
