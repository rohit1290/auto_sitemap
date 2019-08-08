<?php
/* ######################################################
 *  Ramón Iglesias / ura soul
 *  www.ureka.org
 * ###################################################### */


// Busco las entidades que deben aparecer en el sitemap y se las paso a la vista
global $relevantEntities;
$relevantEntities = ['user' , 'group' , 'blog' ,'file' , 'event'];

function auto_sitemap_getCustomUrls($tipos) {
	
	// get main url
	$mainurl = elgg_get_plugin_setting('main_url', 'auto_sitemap');
	$changefreq = elgg_get_plugin_setting('main_url_changefreq', 'auto_sitemap');
	$priority = elgg_get_plugin_setting('main_url_priority', 'auto_sitemap');

	if ( !empty($mainurl) ) {
		$urls[] = [
						'loc' => $mainurl,
						'changefreq' => $changefreq,
						'priority' => $priority
					];
	}
	
	// get custmo urls
	foreach ($tipos as $tipo) {
		$urlList = [];
		$urlList = explode("\n", elgg_get_plugin_setting( $tipo . '_url', 'auto_sitemap'));
		$priority = elgg_get_plugin_setting( $tipo . '_url_priority', 'auto_sitemap');

		foreach ($urlList as $url) {
			if ( ! empty($url) ) {
				$urls[] = [
								'loc' => $url,
								'changefreq' => $tipo,
								'priority' => $priority
							];
			}
		}
	}

	return $urls;
}

function auto_sitemap_getEntityUrls($tipo) {

	switch ($tipo) {
		case 'user':
			$options['type'] = 'user';
		break;

		case 'group':
			$options['type'] = 'group';
		break;


		case 'blog':
		case 'file':
			$options['type'] = 'object';
			$options['subtypes'] = $tipo;
		break;

		case 'event':
			$options['type'] = 'object';
			$options['subtypes'] = 'event_calendar';
		break;
	}

	$changefreq = elgg_get_plugin_setting( $tipo . '_url_changefreq', 'auto_sitemap');
	$priority = elgg_get_plugin_setting( $tipo . '_url_priority', 'auto_sitemap');
	$max_urls = elgg_get_plugin_setting('max_urls', 'auto_sitemap');

	if ( ! is_numeric($max_urls) || $max_urls < 1 ) {
		$max_urls = 5000;
	}

	$options['limit'] = $max_urls;
	// $options['wheres'] = array('e.access_id = 2');
	$options['wheres'] = [function(\Elgg\Database\QueryBuilder $qb, $main_alias) {
			return $qb->compare("{$main_alias}.access_id", '=', '2');
	}];
	$entradas = elgg_get_entities($options);

	foreach ($entradas as $value) {
		$entityUrls[] = ['loc' => $value->getURL(),
								'lastmod' => $value->getTimeUpdated(),
								'changefreq' => $changefreq,
								'priority' => $priority
							];
	}

	// Ordeno por fecha
	usort($entityUrls, 'auto_sitemap_comparar');

	return $entityUrls;

}


function auto_sitemap_getOtherEntityUrls($entities) {

	foreach ($entities as $entity) {
		$options['type'] = 'object';
		$options['subtypes'] = $entity;

		$changefreq = elgg_get_plugin_setting('other_url_changefreq', 'auto_sitemap');
		$priority = elgg_get_plugin_setting('other_url_priority', 'auto_sitemap');
		$max_urls = elgg_get_plugin_setting('max_urls', 'auto_sitemap');

		if ( ! is_numeric($max_urls) || $max_urls < 1 ) {
			$max_urls = 5000;
		}

		$options['limit'] = $max_urls;

		$entradas = elgg_get_entities($options);

		foreach ($entradas as $value) {
			$entityUrls[] = ['loc' => $value->getURL(),
									'lastmod' => $value->getTimeUpdated(),
									'changefreq' => $changefreq,
									'priority' => $priority
								];
		}
	}

	// Ordeno por fecha
	usort($entityUrls, 'auto_sitemap_comparar');

	return $entityUrls;

}


function xml_plugin_get_otherEntityTypes() {
	$valid_types = [];
	$entity_stats = get_entity_statistics();

	foreach ($entity_stats['object'] as $subtype => $counter) {
		if ($subtype != 'plugin' &&
			$subtype != '__base__' &&
			$subtype != 'admin_notice' &&
			$subtype != 'messages' &&
			$subtype != 'widget' &&
			$subtype != 'blog' &&
			$subtype != 'file' &&
			$subtype != 'event_calendar'
			) {
				$valid_types[elgg_echo($subtype)] = $subtype;
		}
	}
	return $valid_types;
}

function auto_sitemap_comparar($x, $y) {
	if ( $x['lastmod'] == $y['lastmod'] )
		return 0;
	else if ( $x['lastmod'] > $y['lastmod'] )
		return -1;
	else return 1;
}
