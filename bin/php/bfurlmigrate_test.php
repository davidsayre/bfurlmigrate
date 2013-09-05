<?php

require 'autoload.php';

if ( file_exists( "config.php" ) )
{
    require "config.php";
}

// Init an eZ Publish script - needed for some API function calls
// and a siteaccess switcher

$ezp_script_env = eZScript::instance( array( 'debug-message' => '',
                                              'use-session' => true,
                                              'use-modules' => true,
                                              'use-extensions' => true ) );

$ezp_script_env->startup();

$ezp_script_env->initialize();



$oldurl = '/pnd/news/story.jhtml?id=14100002';
$oldurl = '/pnd/news/testing.html';

echo 'Lookup url '.$oldurl."\n";

echo bfUrlMigrate::getNewUri($oldurl);

?>