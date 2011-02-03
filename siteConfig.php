<?php

$APP_NAME       = 'Gamut';
$CONSUMER_KEY   = 'quine.algorithm.cs.sunysb.edu';
$CONSUMER_SEC   = 'XWegMGJIOcnVYaigti7kLw21';

$SCOPES         = array(
                    'https://mail.google.com/',
                    'https://www.googleapis.com/auth/userinfo#email'
                    );
$CON_STATUS     = array(
                    'notConnected'  => 'Sign In',
                    'connected'     => 'Signed In'
                    );

$DB_HOST        = 'localhost';
$DB_NAME        = 'gamut';
$DB_USER        = 'root';
$DB_PASS        = '';
$REDIRECT_PAGE  = 'login.php';
$DATA_DIR       = '/home/naresh/LifeParser/Analysis/';
$SENT_SUFFIX    = '.sent';
$RECV_SUFFIX    = '.recv';

$DEBUG_ENABLED  = true;
?>
