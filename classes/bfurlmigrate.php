<?php

/*
	bf Url Migrate
	Purpose: convert url into node via remote_id
	index_bfurlmigrate.php is a standalone version so update as needed

*/
class bfUrlMigrate {

	const URL_SOURCE_LINK_TABLE = 'bfurlmigrate';
	
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


	public static function getRedirectUri($uriString) {
		//query contentobject from remote_id match link lookup from url
		$db = eZDB::instance();	
		$sUri = $db->escapeString($uriString);
		$nContentObjectId = 0; //init
		$sContentClassId = 0;
		$sContentClass = 'tbd';

		$sSQLFindMatch = "SELECT eco.id as contentobject_id, ecc.id as class_id, ecc.identifier as class from ezcontentobject eco, ezcontentclass ecc, bfurlmigrate bfum where bfum.remote_id = eco.remote_id and bfum.url = '".$sUri."' and eco.contentclass_id = ecc.id";
		$aContent = $db->arrayQuery( $sSQLFindMatch, array( 'limit' => 1 ) );
		if(count($aContent) == 1) {
			$nContentObjectId = $aContent[0]['contentobject_id'];
			$sContentClassId = $aContent[0]['class_id'];
			$sContentClass = $aContent[0]['class'];

			if(!empty($nContentObjectId)) {
				
				//try download
				$sDownloadUrl = bfUrlMigrate::getDownloadUrl($nContentObjectId, $sContentClass);
				if(!empty($sDownloadUrl)) {
					return $sDownloadUrl;
				}

				//redirect to node
				$aNodes = eZContentObjectTreeNode::fetchByContentObjectID($nContentObjectId);
				if(count($aNodes) == 1) {			

					$urlAlias = $aNodes[0]->urlAlias();
					if(strlen($urlAlias)) {
						return $urlAlias;
					} else {
						return '/content/view/full/'.$aNodes[0]->attribute('node_id');
					}		        			
				}
			}	        	
		}
		return '';
	}

	public static function getDownloadUrl($nContentObjectId = null, $sContentClass = null) {

		/* todo if $sContentClass is null, lookup class identifier */

		if( empty($sContentClass)) { return ''; } //exit is empty
		if( empty($nContentObjectId)) { return ''; } //exit is empty

		$bfum_ini = $ini = eZINI::instance('bfurlmigrate.ini');
		$aRedirectDownloads = $bfum_ini->BlockValues['RedirectDownload'];

		if( array_key_exists($sContentClass, $aRedirectDownloads)) {
			$sContentDownloadField = $aRedirectDownloads[$sContentClass];

			if(!empty($sContentDownloadField)) {

				$aObject = eZContentObject::fetch($nContentObjectId);
				if ($aObject) {
					$aObjectDataMap = $aObject->dataMap();
					if(array_key_exists($sContentDownloadField, $aObjectDataMap)) {							
						$oDownloadAttr = $aObjectDataMap[$sContentDownloadField];
						$nAttributeId = $oDownloadAttr->attribute('id');
						$nAttributeVersion = $oDownloadAttr->attribute('version');
						$sOriginalFileName = $oDownloadAttr->attribute('content')->attribute('original_filename');
						if(!empty($nAttributeId) && !empty($nAttributeVersion) && !empty($sOriginalFileName)) {
							$sDownloadUrl = '/content/download/' .$nContentObjectId. '/' .$nAttributeId. '/version/' .$nAttributeVersion. '/file/' .$sOriginalFileName;
							return $sDownloadUrl;
						}
					}
				}
			}

		}
		return '';
	}


}
?>