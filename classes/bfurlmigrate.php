<?php

/*
	bf Url Migrate
	Purpose: convert url into node via remote_id
	use in conjunction with data_import
	index_bfurlmigrate.php is a standalone version so update as needed

*/
	class bfUrlMigrate {

		const URL_SOURCE_LINK_TABLE = 'bfurlmigrate';

		function getNewUri($uriString) {
			//echo 'try old url '.$uriString;
			
			//query contentobject from remote_id match link lookup from url
			$query = "SELECT eco.id as contentobject_id from ezcontentobject eco, ".self::URL_SOURCE_LINK_TABLE." lookup where lookup.remote_id = eco.remote_id and lookup.url = '" .$uriString. "'";        	
			
			$db = eZDB::instance();	
        	$aContent = $db->arrayQuery( $query, array( 'limit' => 1 ) );   

        	//print_r($aContent);

			if(count($aContent) == 1) {
				//echo 'found url match'."\n";
				if(array_key_exists('contentobject_id',$aContent[0])) {
					$nContentObjectId = $aContent[0]['contentobject_id'];
					if($nContentObjectId) {
						//echo 'lookup node for object '.$nContentObjectId."\n";
						$aNodes = eZContentObjectTreeNode::fetchByContentObjectID($nContentObjectId);		        		
						if($aNodes[0]) {
	        				//print_r($aNodes );
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

        	return false;
		}

	}
?>