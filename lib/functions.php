<?php

global $relevantEntities;
$relevantEntities = ['user', 'group'];

$entity_stats = elgg_get_entity_statistics();

foreach($entity_stats['object'] as $subtype => $counter) {
	if ($subtype != 'plugin' &&
		$subtype != '__base__' &&
		$subtype != 'mdc_certificate' &&
		$subtype != 'mdc_event' &&
		$subtype != 'mdc_partner' &&
		$subtype != 'coverletter' &&
		$subtype != 'wizard' &&
		$subtype != 'admin_notice' &&
		$subtype != 'messages' &&
		$subtype != 'widget' &&
		$subtype != 'site_notification' &&
		$subtype != 'elgg_upgrade' &&
		$subtype != 'api_key' &&
		$subtype != 'eventday' &&
		$subtype != 'eventslot' &&
		$subtype != 'eventregistration' &&
		$subtype != 'eventregistrationquestion' &&
		$subtype != 'tidypics_batch' &&
		$subtype != 'custom_group_field' &&
		$subtype != 'custom_profile_field' &&
		$subtype != 'custom_profile_type' &&
		$subtype != 'custom_profile_field_category' &&
		$subtype != 'collaboration' &&
		$subtype != 'folder' &&
		$subtype != 'badge' &&
		$subtype != 'exam' &&
		$subtype != 'reported_content' &&
		$subtype != 'event_calendar'
		) {
			$relevantEntities[] = $subtype;
	}
}

function auto_sitemap_getCustomUrls($entities) {
	
	// get main url
	$mainurl = elgg_get_plugin_setting('main_url','auto_sitemap');
	$changefreq = elgg_get_plugin_setting('main_url_changefreq','auto_sitemap');
	$priority = elgg_get_plugin_setting('main_url_priority','auto_sitemap');

	if ( !empty($mainurl) ){
		$urls[] = [
			'loc' => $mainurl,
			'changefreq' => $changefreq,
			'priority' => $priority
		];
	}
	
	// get custom urls
	foreach ($entities as $entity) {

		$urlList = [];
		$urlList = explode("\n", elgg_get_plugin_setting( $entity . '_url','auto_sitemap'));
		$priority = elgg_get_plugin_setting( $entity . '_priority','auto_sitemap');

		foreach ($urlList as $url) {
			if ( ! empty($url) ){
				$urls[] = [
					'loc' => $url,
					'changefreq' => $entity,
					'priority' => $priority
				];
			}
		}
	}

	return $urls;
}

function auto_sitemap_getEntityUrls($entity, $page) {

	switch ($entity) {
		case 'user':
			$options['type'] = 'user';
			break;
		case 'group':
			$options['type'] = 'group';
			break;
		default:
			$options['type'] = 'object';
			$options['subtypes'] = $entity;
		break;
	}

	$changefreq = elgg_get_plugin_setting( $entity . '_changefreq','auto_sitemap');
	$priority = elgg_get_plugin_setting( $entity . '_priority','auto_sitemap');
	$max_urls = get_max_urls_count();

	$options['limit'] = $max_urls;
	$options['offset'] = $max_urls*($page - 1);
	$options['access_id'] = ACCESS_PUBLIC;

	$count = elgg_count_entities($options);
	if($count == 0) { return []; }
	
	$objects = elgg_get_entities($options);

	foreach ($objects as $value) {

		$entityUrls[] = [
			'loc' => $value->getURL(),
			'lastmod' => $value->getTimeUpdated(),
			'changefreq' => $changefreq,
			'priority' => $priority
		];
	}

	// Compare
	usort($entityUrls, 'auto_sitemap_compare');

	return $entityUrls;
}

function auto_sitemap_compare($x,$y){
	if ( $x['lastmod'] == $y['lastmod'] )
		return 0;
	else if ( $x['lastmod'] > $y['lastmod'] )
		return -1;
	else
		return 1;
}

function get_max_urls_count() {
	$max_urls = elgg_get_plugin_setting('max_urls','auto_sitemap');
	
	if ( !is_numeric($max_urls) || $max_urls < 1 ) {
		$max_urls = 5000;
	}
	return $max_urls;
}
