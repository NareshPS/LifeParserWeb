<?php
require_once 'Gdata_OAuth_Helper.php';

session_start();
// App Name goes here.
$APP_NAME = 'Gamut';
$APP_URL = getAppURL();
echo $APP_URL;
// Scope defines the services to which access is required.
$scopes = array(
    'https://mail.google.com/',
);

// Setup OAuth consumer. Thes values should be replaced with your registered
// app's consumer key/secret.
$CONSUMER_KEY = 'anonymous';
$CONSUMER_SECRET = 'anonymous';
$consumer = new Gdata_OAuth_Helper($CONSUMER_KEY, $CONSUMER_SECRET);

// Main controller logic.
switch (@$_REQUEST['action']) {
    case 'logout':
        logout($APP_URL);
        break;
    case 'request_token':
        echo 'In Request Token';
        $_SESSION['REQUEST_TOKEN'] = serialize($consumer->fetchRequestToken(
            implode(' ', $scopes), $APP_URL . '?action=authorize_token'));
        echo $_SERVER[ 'ACCESS_TOKEN' ];
        echo 'Done Request Token';
        $consumer->authorizeRequestToken();
        break;
    case 'authorize_token':
        $consumer->authorizeRequestToken();
        break;
    case 'access_token':
        $_SESSION['ACCESS_TOKEN'] = serialize($consumer->fetchAccessToken());
        header('Location: ' . $APP_URL);
        break;
    default:
        if (isset($_SESSION['ACCESS_TOKEN'])) {
            $accessToken = unserialize($_SESSION['ACCESS_TOKEN']);

            $httpClient = $accessToken->getHttpClient(
                $consumer->getOauthOptions());
        } else {
            renderHTML();
        }
}

/**
 * Returns a the base URL of the current running web app.
 *
 * @return string
 */
function getAppURL() {
    $pageURL = 'http://';

    if ($_SERVER['SERVER_PORT'] != '80') {
        $pageURL .= $_SERVER['SERVER_NAME'] . ':' . 
                    $_SERVER['SERVER_PORT'] . $_SERVER['PHP_SELF'];
    } else {
        $pageURL .= $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
    }
    return $pageURL;
}

/**
 * Removes session data and redirects the user to a URL.
 *
 * @param string $redirectUrl The URL to direct the user to after session data
 *     is destroyed.
 * @return void
 */
function logout($redirectUrl) {
    session_destroy();
    header('Location: ' . $redirectUrl);
    exit;
}

/**
 * Gets the effective URL based on the availability of token.
 *
 * @return string
 **/
function getRedirectUrl()
{
    $redirUrl = $APP_URL;
    if (!isset($_SESSION[ 'ACCESS_TOKEN' ])) {
        $redirUrl = $redirUrl.'?action=request_token';
    }

    print $redirUrl;

    return $redirUrl;
}
/**
 * Prints the token string and secret of the token passed in.
 *
 * @param Zend_OAuth_Token $token An access or request token object to print.
 * @return void
 */
function printToken($token) {
    echo '<b>Token:</b>' . $token->getToken() . '<br>';
    echo '<b>Token secret:</b>' . $token->getTokenSecret() . '<br>';
}
?>

<?php
function renderHTML() {
?>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link href="default.css" rel="stylesheet" type="text/css">
<title>Gamut...Know your life</title>

</head>

<body>
<table align="center" border="0" height="80%" width="60%"
	bgcolor="#77BBFF">
	<tbody>
		<tr rowspan="2">
			<td>
			<table align="center" border="0" height="100%" width="100%">
				<tbody>
					<tr>
						<td class="siteTitle">
						<dl>
							<dd>Gamut.....revisit your past.</dd>
						</dl>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
		<tr rowspan="2">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td>
			<table width=100% height=100% align="center" border="0">
				<tbody>
					<tr height=100%>
						<td width=50%>Gmail account is required to use this site.<br>
						</td>
						<td width=50%>
							<a href="<?php getRedirectUrl();?>">Click Here to Continue</a>
						</td>
					</tr>
				</tbody>
			</table>
	</td>
	</tr>
	</tbody>
</table>

</body>
</html>
<?php
}
?>
