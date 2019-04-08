DESCRIPTION
===========

Adds a dynamic sitemap.xml file to your site for SEO purposes. (No physical file, the sitemap is generated for each request).
	
The sitemap is split into various sub-maps indexed in a sitemap index file, this allows lightweight crawling.

Each entity type will be placed in it's own sitemap.
	
Additionally you can manually specify custom URLs of your website.

Sitemaps can be formated with XSL for candy reading	

INSTALL
=======

	- Unzip the code to your mod directory
	- Enable the plugin in the admin panel
	- Configure the sitemap (If blank sitemap is displayed, flush the cache)
	- Submit http://YOURSITE/sitemap.xml to search engines