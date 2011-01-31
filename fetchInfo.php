<?php

require_once 'Gdata_OAuth_Helper.php';

$client     = new Zend_Http_Client();

$client->setUri('https://www.googleapis.com/userinfo/email');
$response   = $client->request('GET');
print $response;
?>
