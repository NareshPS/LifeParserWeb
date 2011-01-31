<?php
/**
 * Converts an array to GET request while encoding them.
 **/

function arrayToString($args)
{
    if(!is_array($args)) 
    {
        return null;
    }

    $itemIdx    = 0;
    $outString  = '';
    
    foreach($args as $name => $value)
    {
        if($itemIdx++ != 0) 
        {
            $outString  .= '&';
        }

        $outString      .= $name.'='.$value;
    }
    
    return $outString;
}



/**
 * Returns action string based on the input flag.

 **/

function getActionString($actionFlag)
{
    $actionString       = '';

    switch ($actionFlag)
    {
        case 'request':
            $actionString       = 'request_token';
            break;

        case 'access':
            $actionString       = 'access_token';
            break;

        case 'logout':
            $actionString       = 'logout';
            break;

        case 'openid_auth':
            $actionString       = 'openid_auth';
            break;
    }

    return $actionString;
}

/**
 * This function patches URL of the form
 * http://localhost/abc.php to http://localhost/$page
 **/

function constructPageUrl ($page = null)
{
    $newUrl                         = '';
    $selfUrl                        = $_SERVER['PHP_SELF'];

    if ($page)
    {
        $splitUrl                   = explode('/', $selfUrl);
        $lastIndex                  = count($splitUrl) - 1;
        $splitUrl[ $lastIndex ]     = $page;
        $newPath                    = implode('/', $splitUrl);
        $newUrl                     = getBaseUrl() . $newPath;                
    }
    else
    {
        $splitUrl                   = explode('/', $selfUrl);
        $newPath                    = implode('/', $splitUrl);
        $newUrl                     = getBaseUrl() . $newPath; 
    }

    return $newUrl;
}

/**
 * This function returns the base URL of the server.
 **/

function getBaseUrl ()
{
    $pageURL = 'http://';

    if ($_SERVER['SERVER_PORT'] != '80') {
        $pageURL .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'];
    } else {
        $pageURL .= $_SERVER['SERVER_NAME'];
    }

    return $pageURL;
}

/**
 * Returns a the base URL of the current running web app.
 *
 * @return string
 */
function getAppURL() {
    return getBaseUrl() . $_SERVER['PHP_SELF'];
}

/**
 * Gets the effective URL based on the availability of token.
 *
 * @return string
 **/

function getRedirectUrl()
{
    global $APP_URL;
    $redirUrl = $APP_URL;
    
    if (!isset($_SESSION[ 'ACCESS_TOKEN' ])) {
        $redirUrl = $redirUrl.'?action='.getActionString('access');
    }
    else
    {
        $redirUrl = $redirUrl.'?action='.getActionString('logout');
    }
    return $redirUrl;
}

?>
