<?php

/*
	bf Url Migrate
	Purpose: convert url into node via remote_id
	index_bfurlmigrate.php is a standalone version so update as needed

*/
	class bfUrlMigrate {

		const URL_SOURCE_LINK_TABLE = 'bfurlmigrate';

		function getNewUri($uriString) {
						
			//query contentobject from remote_id match link lookup from url
			$db = eZDB::instance();	
			$sUri = $db->escapeString($uriString);
			$query = "SELECT eco.id as contentobject_id from ezcontentobject eco, bfurlmigrate bfum where bfum.remote_id = eco.remote_id and bfum.url = '".$sUri."'";     	
			$aContent = $db->arrayQuery( $query, array( 'limit' => 1 ) );   

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

		public static function getUrlByRemoteId($remote_id){

			$sSourceUrl = '';
			$remote_id = trim($remote_id);
		
			if(!$remote_id) {return '';}

			//query contentobject from remote_id match link lookup from url
			$query = "SELECT url from ".self::URL_SOURCE_LINK_TABLE." where remote_id='".$remote_id."'";        	

			$db = eZDB::instance();	
        	$aResult = $db->arrayQuery( $query, array( 'limit' => 1 ) );
        	if(count($aResult) == 1) {
			
				//echo 'found url match'."\n";
				if(array_key_exists('url',$aResult[0])) {
					$sSourceUrl = $aResult[0]['url'];
				}        	
			}
			return $sSourceUrl;
		}

	}
?>