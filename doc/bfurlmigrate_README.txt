/*
	bf url migrate
	purpose: read url for known path and map into ez object
	This is the intercept script that fires with each page call

	Install: 
	option 1) aggressive link check in front of each page request

	..
	eZURLAliasML::urlTranslationEnabledByUri( $uri ) )
    {
        $translateResult = eZURLAliasML::translate( $uri );
        ..

	option 2) fallback link check fired from error page


*/