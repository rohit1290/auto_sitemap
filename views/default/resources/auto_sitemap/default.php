<?php
$type = elgg_extract('type', $vars, 'index');

$schema = elgg_get_plugin_setting('schema','auto_sitemap');
if ( empty( $schema )){
  $schema = 'sitemap_org_0_9';
}

// incluir o no los estilos
$flagXsl = elgg_get_plugin_setting('use_xsl','auto_sitemap');

switch ($type) {
  case 'index':
    // custom URLs
    $sitemaps[] = 'custom' ;

    // relevant entities
    global $relevantEntities;

    foreach ($relevantEntities as $entity) {
      if ( elgg_get_plugin_setting( $entity . '_url','auto_sitemap') ){
        $sitemaps[] = $entity;
      }
    }

    // other entities
    $otherActiveEntities = array_filter(explode(',' , elgg_get_plugin_setting('other_urls_types','auto_sitemap')));

    if ( !empty($otherActiveEntities)){
      $sitemaps[] = 'other' ;
    }
    // Pinto el sitemap (indice)
    echo elgg_view('auto_sitemap/' . $schema . "/sitemapindex", array('sitemaps' => $sitemaps,'flagXsl'=> $flagXsl));

    return true;
  break;

  case 'custom':

    $tipos = Array( 'always' , 'hourly' , 'daily' ,'weekly' , 'monthly', 'yearly', 'never');
    $urls = auto_sitemap_getCustomUrls( $tipos );

    // if no custom urls configured, sitemap doesnt exists
    if ( empty($urls) ){
      // sitemap doesnt exists
      return false;

    }else{
      echo elgg_view('auto_sitemap/' . $schema . "/0_9_scheme", array('urls' => $urls,'flagXsl'=> $flagXsl));
      return true;

    }


  break;

  case 'user':
  case 'group':
  case 'blog':
  case 'file':
  case 'event':

    $urls = auto_sitemap_getEntityUrls( $page[0] );

    // if this entity is not active in settings, then sitemap doesn't exist
    if ( ! elgg_get_plugin_setting($page[0] . '_url','auto_sitemap') ){
      return false;

    }else{
      echo elgg_view('auto_sitemap/' . $schema . "/0_9_scheme", array('urls' => $urls,'flagXsl'=> $flagXsl));
      return true;

    }

  break;

  case 'other':

    $otherActiveEntities = array_filter(explode(',' , elgg_get_plugin_setting('other_urls_types','auto_sitemap')));

    $urls = auto_sitemap_getOtherEntityUrls( $otherActiveEntities );

    // if no other entities selected in settings, sitemap doesn't exist
    if ( empty($urls) ){
      return false;
    }else{
      echo elgg_view('auto_sitemap/' . $schema . "/0_9_scheme", array('urls' => $urls,'flagXsl'=> $flagXsl));
      return true;
    }

  break;

  default:
    return false;
  break;
}


 ?>
