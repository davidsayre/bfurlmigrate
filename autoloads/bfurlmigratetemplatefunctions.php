<?php


/**
 * Operators for BfUrlMigrate
 */
 
class BfUrlMigrateTemplateFunctions {

	public function operatorList()
    {
        return array( 'bfum_url' );
    }
	
	public function namedParameterPerOperator()
    {
        return true;
    }
	
	public function namedParameterList()
    {
        return array(
            'bfum_url' => array(
                'remote_id' => array(
                    'type'        => 'string',
                    'required'    => false,
                    'default'     => false
                )
            ),
        );
    }

    public function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'bfum_url': {
                $operatorValue = bfUrlMigrate::getUrlByRemoteId($namedParameters['remote_id']);
                break;
            }
        }
    }
	
}
?>
