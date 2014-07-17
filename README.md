bfurlmigrate
============

bfurlmigrate is used to intercept old urls and map to ez objects by remote_id

Installation
------------

* cp extension/bfurlmigrate/bin/php/index_bfurlmigrate.php <ez webroot>
* mysql <ez database> -u<user> -p < extension/bfurlmigrate/sql/mysql/bfurlmigrate.sql
* edit apache rewrites (or .htaccess)
		# Add old url rules (example)
		RewriteRule ^.*story.jhtml.* index_bfurlmigrate\.php [L]
		RewriteRule ^.*news.jhtml.* index_bfurlmigrate\.php [L]
		# Allow bfurlmigrate to run
		RewriteRule index_bfurlmigrate\.php - [L]
		
* Populate bfurlmigrate table with urls and ez object remote_ids
* Browse to old url and be redirected to new ez object!

Usage
-----

When migrating an existing website to eZ publish the old urls will become invalid. 
Apache rewrites can be used to funnel old url patterns into the <webroot>/index_bfurlmigrate.php script.
The bfurlmigrate script will take the old url and lookup a record in the bfurlmigrate table. 
If an old url match is made then the user will be redirected to the ez object's page.
If no old url match is made then the homepage will be presented.
In this way the investment in old urls yields 1-to-1 mapping to ez object pages.
This can preserve search engine traffic and inbound links during the period of transition.

Author
------
David Sayre

Legal
-----

[bfurlmigrate](https://github.com/davidsayre/bfurlmigrate) is distributed under the [AGPL version 3](http://www.gnu.org/licenses/agpl-3.0.html)

This project is in no way affiliated with eZ Systems AS, or eZ Publish

* [eZ Publish](http://www.ez.no) is a trademark of eZ Systems AS

Copyright &copy; 2013 [Beaconfire](http://www.beaconfire.com/)
	
	
