<?php

/**
 * Look in the operator files for documentation on use and parameters definition.
 *
 * @var array $eZTemplateOperatorArray
 */
$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 
	'script'         => 'extension/bfurlmigrate/autoloads/bfurlmigratetemplatefunctions.php',
	'class'          => 'BfUrlMigrateTemplateFunctions',
	'operator_names' => array( 'bfum_url' ) 	
);

?>