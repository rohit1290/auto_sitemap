<?php
$body ='';

foreach ($vars['urls'] as $element) {
	$body .= '<url>';
	$body .= '<loc>' . trim($element['loc']) . '</loc>';
	
	if(array_key_exists('lastmod', $element)){
		if ( $element['lastmod'] ) {
			$body .= '<lastmod>' . date('Y-m-d', $element['lastmod']  ) . '</lastmod>';
		}
	}
	
	if(array_key_exists('changefreq', $element)){
		if ( $element['changefreq'] && $element['changefreq'] != 'disabled') {
			$body .= '<changefreq>' . $element['changefreq'] . '</changefreq>';
		}
	}
	
	if(array_key_exists('priority', $element)){
		if ( $element['priority'] != 'none') {
			$body .= '<priority>' . $element['priority'] . '</priority>';
		}
	}
	
	$body .= '</url>';
}

echo $body;