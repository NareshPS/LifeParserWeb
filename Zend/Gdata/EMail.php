<?php
require_once('Zend/Gdata.php');

class Zend_Gdata_EMail extends Zend_Gdata
{
    const   EMAIL_TAG       = 'email';
    const   EMAIL_FEED_URI  = 'https://www.googleapis.com/userinfo/email?alt=xml';

    public function __construct($client = null, $applicationId = 'New App')
    {
        parent::__construct($client, $applicationId);
    }

    public function getEMailFeed($location = null)
    {
        $xmlFeed        = parent::fetchRequest(self::EMAIL_FEED_URI);
        $doc            = new DOMDocument();
        $success        = @$doc->loadXML($xmlFeed);

        if($success)
        {
            $eMails     = $doc->getElementsByTagName(self::EMAIL_TAG);

            if($eMails->length == 1)
            {
                return $eMails->item(0)->nodeValue;
            }
        }
        else
        {
            require_once 'Zend/Gdata/App/Exception.php';
            throw new Zend_Gdata_App_Exception(
                "DOMDocument cannot parse XML: $php_errormsg");
        }

        return null;
    }
}
?>
