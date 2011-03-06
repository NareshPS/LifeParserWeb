<?php
require_once 'siteConfig.php';
require_once 'siteUtils.php';
require_once 'dbFuncs.php';

session_start();

$dbFuncsObj     = new dbFuncs(false);
$dbFuncsObj->doConnect();

print $dbFuncsObj->validateAccessToken($_SESSION['ACCESS_TOKEN'], $_SESSION['OPENID_EMAIL']);

if (!isset($_SESSION['ACCESS_TOKEN']) || !isset($_SESSION['OPENID_EMAIL']) || !$dbFuncsObj->validateAccessToken($_SESSION['OPENID_EMAIL'], $_SESSION['ACCESS_TOKEN'])) 
{
    header('Location: '.constructPageUrl('index.php'));
}

$dbFuncsObj->doDisconnect();
?>
<html>
<head>
    <title> GMail Parser </title>
</head>
<body>
<object width="800" height="600">
<param name="movie" value="LifeParserWeb.swf">
<embed src="LifeParserWeb.swf" width="800" height="600">
</embed>
</object>
</body>
</html>
