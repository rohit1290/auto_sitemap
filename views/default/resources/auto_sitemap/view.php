<?php
$type = elgg_extract('type', $vars, 'index');
$page = elgg_extract('page', $vars, 1);

$schema = elgg_get_plugin_setting('schema', 'auto_sitemap');
if ( empty( $schema )) {
	$schema = 'sitemap_org_0_9';
}

// incluir o no los estilos
$flagXsl = (bool)elgg_get_plugin_setting('use_xsl', 'auto_sitemap');

switch ($type) {
	case 'index':
	// custom URLs
	$sitemaps[] = 'custom' ;
	
	// relevant entities
	global $relevantEntities;
	
	foreach ($relevantEntities as $entity) {
		if (elgg_get_plugin_setting( $entity . '_url', 'auto_sitemap')) {
			$sitemaps[] = $entity;
		}
	}
	// index sitemap
	echo elgg_view('auto_sitemap/' . $schema . "/sitemapindex", ['sitemaps' => $sitemaps,'flagXsl'=> $flagXsl]);
	
	return true;
	break;
	
	case 'custom':
	
	$tipos = [ 'always' , 'hourly' , 'daily' ,'weekly' , 'monthly', 'yearly', 'never'];
	$urls = auto_sitemap_getCustomUrls($tipos);
	
	// if no custom urls configured, sitemap doesnt exists
	if (empty($urls)) {
		// sitemap doesnt exists
		return false;
	} else {
		echo elgg_view('auto_sitemap/' . $schema . "/0_9_scheme", ['urls' => $urls,'flagXsl'=> $flagXsl]);
		return true;
	}
	
	
	break;
	
	default:
	
	$urls = auto_sitemap_getEntityUrls($type, $page);
	
	// if this entity is not active in settings, then sitemap doesn't exist
	if (empty($urls)) {
		// sitemap doesnt exists
		return false;
	} else if (!elgg_get_plugin_setting($type . '_url', 'auto_sitemap')) {
		return false;
	} else {
		echo elgg_view('auto_sitemap/' . $schema . "/0_9_scheme", ['urls' => $urls,'flagXsl'=> $flagXsl]);
		return true;
	}
	
	break;
}


