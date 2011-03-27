<?php
require_once 'Gdata_OAuth_Helper.php';
require_once 'siteUtils.php';
require_once 'siteConfig.php';
require_once 'dbFuncs.php';

session_start();

require_once 'openIdAuth.php';

$APP_URL        = getAppUrl();
$openId         = new Gamut_OpenId();

$consumer       = new Gdata_OAuth_Helper($CONSUMER_KEY, $CONSUMER_SEC);

$dbFuncsObj = new dbFuncs(false);
$dbFuncsObj->doConnect();
/**
 * This switch statement performs selective processing
 * based on the supplied 'action'.
 **/

switch (@$_REQUEST['action']) 
{
    case 'request_token':
        $_SESSION['ACCESS_TOKEN']   = $dbFuncsObj->getAccessToken('mail2naresh@gmail.com');
            
        if (isset($_SESSION['ACCESS_TOKEN']))
        {
            $accessToken        = unserialize($_SESSION['ACCESS_TOKEN']);
            //print $accessToken;
            $httpClient         = $accessToken->getHttpClient($consumer->getOauthOptions());
            $emailService   = new Zend_Gdata_EMail($httpClient);
            $emailId        = $emailService->getEMailFeed();
            print $emailId;
            
            renderHTML('User logged-in with access token: <br> <a href="'. getRedirectUrl().'" >Logout </a>' , false);
        }
        else
        {
            $_SESSION['REQUEST_TOKEN']  = serialize(
                                            $consumer->fetchRequestToken(
                                            implode(' ', $SCOPES), $APP_URL . '?action='.getActionString('access')));
            $consumer->authorizeRequestToken();
        }

        break;

    case 'access_token':

        if (!isset($_SESSION['ACCESS_TOKEN']))
        {    
            $_SESSION['ACCESS_TOKEN']       = serialize($consumer->fetchAccessTokenFromOpenId($_SESSION['REQUEST_TOKEN']));
            $dbFuncsObj->setAccessToken($_SESSION['OPENID_EMAIL'], $_SESSION['ACCESS_TOKEN'], true);
        }
        if ($dbFuncsObj->validateAccessToken($_SESSION['OPENID_EMAIL'], $_SESSION['ACCESS_TOKEN']) == false)
        {
            header('Location: ' . getRedirectUrl());
        }
        else
        {
            global $PYTHON_PATH;
            global $BACKEND_DIR;
            global $BACKEND_BIN;
            //Trigger the backend to download emails.
            /*$command                    = $PYTHON_PATH.' '.$BACKEND_DIR.$BACKEND_BIN.' '.$_SESSION['OPENID_EMAIL'].'&';
            //$command                    = 'rm -rf /home/naresh/LifeParser/Data/GMail_DataStore/mail2naresh@gmail.com';
            exec($command, $output, $retVal);
            print_r ($output);
            $command                    = 'rm -rf /home/naresh/LifeParser/Analysis/mail2naresh@gmail.com*';
            $command                    = 'rm -rf /home/naresh/LifeParser/Data/GMail_DataStore/mail2naresh@gmail.com';
            exec($command, $output, $retVal);*/
            header('Location: ' . constructPageUrl('index.php'));
        }
        break;

    case 'logout':
        session_destroy();
        header('Location: ' . $APP_URL);
        exit;

    case 'openid_auth':
        $authorizedToken                = $openId->getRequestToken();
        $_SESSION['REQUEST_TOKEN']      = $authorizedToken;
        $_SESSION['OPENID_EMAIL']       = $openId->getEMailId();
        $_SESSION['OPENID_FIRSTNAME']   = $openId->getFirstName();
        $_SESSION['OPENID_LASTNAME']    = $openId->getLastName();
        header('Location: ' . getRedirectUrl('access'));
        break;

    case 'gadget_login':
        global $openId;
        $redirUrl   = constructPageUrl() . '?action=' . getActionString('openid_auth');
        header('Location: '. $openId->getUrl($redirUrl));
        break;

    case 'login':
    default:
        
        if (!isset($_SESSION['ACCESS_TOKEN'])) 
        {
            renderHTML('login');
        }
        else
        {
            //renderHTML('User: ' . $_SESSION['OPENID_FIRSTNAME'] . ' ' . $_SESSION['OPENID_LASTNAME'] . '<br/> EMail: <b>' . $_SESSION['OPENID_EMAIL'] . '</b> logged-in with access token: <br> <a href="'. getRedirectUrl().'" >Logout </a>' , false);
            renderHTML('flashDisplay');
        }
        break;
}

$dbFuncsObj->doDisconnect();

function renderHTML ($command)
{
?>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link href="default.css" rel="stylesheet" type="text/css">
<title>Life Parser</title>
</head>

<body>
    <table align="center" id="lifeParserMain"  border="0" width="80%">
            <tr>
                <td class="siteTitle" colspan="2">Life Parser</td>
            </tr>
                <?php
                    switch ($command)
                    {
                        case 'login':
                        {
                ?>
	    <tr valign="top">
	        <td>Gmail account is required to use this site.<br></td>
	        <td>
                    <a href="<?php global $openId; $redirUrl   = constructPageUrl() . '?action=' . getActionString('openid_auth');echo $openId->getUrl($redirUrl); ?>">Sign-in with Google Account</a>
		</td>
            </tr>
                <?php
                            break;
                        }

                        case 'flashDisplay':
                        {
                ?>
            <tr>
                <td>
                    Welcome <?php echo $_SESSION['OPENID_FIRSTNAME'] . ' ' . $_SESSION['OPENID_LASTNAME']; ?>
                </td>
                <td>
                    <a href="<?php echo constructPageUrl().'?action='.getActionString('logout'); ?>">Click Here to Logout.</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <link rel="stylesheet" type="text/css" href="history/history.css" />
                    <script type="text/javascript" src="history/history.js"></script>
                    <script type="text/javascript" src="swfobject.js"></script>
                    <script type="text/javascript">

                        <!-- Taken from automatically generated FLEX HTML file -->

                        <!-- For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. --> 

                        var swfVersionStr = "10.0.0";
                        <!-- To use express install, set to playerProductInstall.swf, otherwise the empty string. -->
                                                
                        var xiSwfUrlStr = "playerProductInstall.swf";
                        var flashvars = {};
                        var params = {};

                        <!-- Fill Params Here [Starts]-->
                        params.quality = "high";
                        params.bgcolor = "#ffffff";
                        params.allowscriptaccess = "sameDomain";
                        params.allowfullscreen = "true";

                        <!-- Fill Params Here [Ends]-->

                        var attributes = {};
                        <!-- Fill Params Here [Starts]-->

                        attributes.id = "LifeParserWeb";
                        attributes.name = "LifeParserWeb";
                        attributes.align = "middle";

                        <!-- Fill Params Here [Ends]-->
                        swfobject.embedSWF(
                            "LifeParserWeb.swf", "flashContent", 
                            "800", "600", 
                            swfVersionStr, xiSwfUrlStr, 
                            flashvars, params, attributes);

                        <!-- JavaScript enabled so display the flashContent div in case it is not replaced with a swf object. -->

                        swfobject.createCSS("#flashContent", "display:block;text-align:left;");
                    </script>
                    <div id="flashContent">
                        <p>To view this page ensure that Adobe Flash Player version 10.0.0 or greater is installed. 
                        </p>
                        <script type="text/javascript"> 
                            var pageHost = ((document.location.protocol == "https:") ? "https://" :	"http://"); 
                            document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='"+ pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" ); 
                        </script>
                    </div>
                </td>
            </tr>
                <?php
                            break;
                        }
                    }
                ?>
    </table>
</body>
</html>
<?php
}
?>
