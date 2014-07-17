<?php

/*
	index_bfurlmigrate.php
	Standalone url migrate script

	Use: edit .htaccess or apache to grab url patterns to try url migration
		This is NOT ezurl aware so be sure to create a regex for urls that cannot match ez objects

		RewriteRule <..your conditions..> index_bfurlmigrate\.php [L]
		RewriteRule index_bfurlmigrate\.php - [L]

	example: 
		RewriteRule ^story.jhtml(.*) index_bfurlmigrate\.php [L]
		
*/

require 'autoload.php';

// Tweaks ini filetime checks if not defined!
// This makes ini system not check modified time so
// that index_treemenu.php can assume that index.php does
// this regular enough, set in config.php to override.
if ( !defined('EZP_INI_FILEMTIME_CHECK') )
{
    define( 'EZP_INI_FILEMTIME_CHECK', false );
}

function ezupdatedebugsettings()
{
}

function eZFatalError()
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error' );
}

function exitWithInternalError()
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error' );
    eZExecution::cleanup();
    eZExecution::setCleanExit();
}

ignore_user_abort( true );
ob_start();
error_reporting ( E_ALL );

eZExecution::addFatalErrorHandler( 'eZFatalError' );
eZDebug::setHandleType( eZDebug::HANDLE_FROM_PHP );

// Trick to get eZSys working with a script other than index.php (while index.php still used in generated URLs):
$_SERVER['SCRIPT_FILENAME'] = str_replace( '/index_bfurlmigrate.php', '/index.php', $_SERVER['SCRIPT_FILENAME'] );
$_SERVER['PHP_SELF'] = str_replace( '/index_bfurlmigrate.php', '/index.php', $_SERVER['PHP_SELF'] );

/**/
$ini = eZINI::instance();
$timezone = $ini->variable( 'TimeZoneSettings', 'TimeZone' );
if ( $timezone )
{
    putenv( "TZ=$timezone" );
}


// init uri code
$GLOBALS['eZGlobalRequestURI'] = eZSys::serverVariable( 'REQUEST_URI' );
eZSys::init( 'index.php', $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) === 'true' );
$uri = eZURI::instance( eZSys::requestURI() );

$GLOBALS['eZRequestedURI'] = $uri;

// Check for extension
eZExtension::activateExtensions( 'default' );

// load siteaccess
$access = eZSiteAccess::match( $uri,
                      eZSys::hostname(),
                      eZSys::serverPort(),
                      eZSys::indexFile() );
$access = eZSiteAccess::change( $access );
$GLOBALS['eZCurrentAccess'] = $access;

// Check for new extension loaded by siteaccess
eZExtension::activateExtensions( 'access' );


/**************************************
	bfUrlMigrate getNewUri() STANDALONE 
*/
function getNewUri($uriString) {
	//query contentobject from remote_id match link lookup from url
	$db = eZDB::instance();	
	$sUri = $db->escapeString($uriString);
	$query = "SELECT eco.id as contentobject_id from ezcontentobject eco, bfurlmigrate bfum where bfum.remote_id = eco.remote_id and bfum.url = '".$sUri."'";     	
	$aContent = $db->arrayQuery( $query, array( 'limit' => 1 ) );   
	if(count($aContent) == 1) {
		if(array_key_exists('contentobject_id',$aContent[0])) {
			$nContentObjectId = $aContent[0]['contentobject_id'];
			if($nContentObjectId) {
				$aNodes = eZContentObjectTreeNode::fetchByContentObjectID($nContentObjectId);		        		
				if($aNodes[0]) {
					$urlAlias = $aNodes[0]->urlAlias();
					if(strlen($urlAlias)) {
						return $urlAlias;
					} else {
						return '/content/view/full/'.$aNodes[0]->attribute('node_id');
					}		        			
				}
			}			
		}	        	
	}
	return '';
}

/* EXECUTE url migrate */
$tryUrl = ($_SERVER['REQUEST_URI']);
$sMigrateToUrl = getNewUri($tryUrl);
if(substr($sMigrateToUrl,0,1) != '/') { $sMigrateToUrl = '/'.$sMigrateToUrl; } //fix prepend
echo 'redirect to ' .$sMigrateToUrl."\n";
eZHTTPTool::redirect( $sMigrateToUrl, array(), '301' );

/* EXIT failsafe */
eZExecution::cleanup();
eZExecution::setCleanExit();

?>