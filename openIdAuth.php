<?php
require_once 'siteConfig.php';

class Gamut_OpenId
{
    const openIdUrl         = 'https://www.google.com/accounts/o8/ud';
    private $openIdParams   = array(
        'openid.ns'             => 'http://specs.openid.net/auth/2.0',
        'openid.claimed_id'     => 'http://specs.openid.net/auth/2.0/identifier_select',
        'openid.identity'       => 'http://specs.openid.net/auth/2.0/identifier_select',
        'openid.return_to'      => '',
        'openid.realm'          => '',
        'openid.mode'           => 'checkid_setup',
        'openid.ns.ax'          => 'http://openid.net/srv/ax/1.0',
        'openid.ax.mode'        => 'fetch_request',
        'openid.ax.type.email'  => 'http://axschema.org/contact/email',
        'openid.ax.type.firstname'  => 'http://axschema.org/namePerson/first',
        'openid.ax.type.lastname'   => 'http://axschema.org/namePerson/last',
        'openid.ax.required'    => 'email,firstname,lastname',
        'openid.ns.ext2'        => 'http://specs.openid.net/extensions/oauth/1.0',
        'openid.ext2.consumer'  => '',
        'openid.ext2.scope'     => ''
        );

    public function __construct($realm = null, $return_to = null)
    {
        global $SCOPES;
        global $CONSUMER_KEY;

        $this->openIdParams['openid.ext2.consumer'] = $CONSUMER_KEY;
        $this->openIdParams['openid.ext2.scope']    = implode('+', $SCOPES);
        $this->openIdParams['openid.return_to']     = $return_to? $return_to: constructPageUrl ();

        $this->openIdParams['openid.realm']         = $realm? $realm: getBaseUrl ();
    }

    public function getUrl($return_to = null)
    {
        $this->openIdParams['openid.return_to']   = $return_to? $return_to: constructPageUrl ();
        $url        = self::openIdUrl . '?' . arrayToString($this->openIdParams);
        return $url;
    }

    public function getRequestToken()
    {
        return $_REQUEST['openid_ext2_request_token'];
    }

    public function getEMailId()
    {
        return $_REQUEST['openid_ext1_value_email'];
    }

    public function getFirstName()
    {
        return $_REQUEST['openid_ext1_value_firstname'];
    }

    public function getLastName()
    {
        return $_REQUEST['openid_ext1_value_lastname'];
    }
    
    /*
    'https://www.google.com/accounts/o8/id',
    'http://quine.algorithm.cs.sunysb.edu',
    'http://quine.algorithm.cs.sunysb.edu');*/
}
?>
