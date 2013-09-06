bfurlmigrate
============

Author: David Sayre

Purpose: read url for known path and map into ez object/node

Install: 

1) copy bin/php/index_bfurlmigrate.php to <ez root>
	cp extension/bfurlmigrate/bin/php/index_bfurlmigrate.php <ez root>

2) run sql into ez database
	mysql <ez db> < extension/bfurlmigrate/sql/mysql/bfurlmigrate.sql

3) edit .htaccess / apache .conf for old url patterns

	This is NOT ezurl aware so be sure to create a regex for urls that cannot match ez objects

		RewriteRule <..your conditions..> index_bfurlmigrate\.php [L]
		RewriteRule index_bfurlmigrate\.php - [L]

	example: 
		RewriteRule ^.*story.jhtml.* index_bfurlmigrate\.php [L]
