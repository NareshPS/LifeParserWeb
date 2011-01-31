<?php

require_once 'siteConfig.php';
require_once 'Zend/Oauth/Token/Access.php';

class dbFuncs
{
    private $connHandle;
    private $traceEnabled;

    public function __construct ($traceEnabled = false)
    {
        $this->connHandle   = null;
        $this->traceEnabled = $traceEnabled;
    }

    public function doConnect ()
    {
        try
        {
            global      $DB_HOST;
            global      $DB_NAME;
            global      $DB_USER;
            global      $DB_PASS;
            $this->connHandle   = new PDO ("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
        }
        catch (PDOException $e)
        {
            if ($this->traceEnabled == true)
            {
                echo 'Connection failed: ' . $e->getMessage ();
            }
        }
    }

    public function isConnected ()
    {
        if ($this->connHandle == null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function doDisconnect ()
    {
        if ($this->isConnected () == true)
        {
            $this->connHandle   = null;
        }
    }

    public function getAccessToken ($emailId)
    {
        if ($this->isConnected () == false)
        {
            if ($this->traceEnabled == true)
            {
                echo 'Not Connected';
            }
            return null;
        }

        try
        {
            $rows = $this->connHandle->query("SELECT fullToken from records where emailId='" . $emailId . "'");

            $email              = null;
            $fullToken          = null;

            foreach ($rows as $row)
            {
                $fullToken      = $row['fullToken'];
            }

            if ($this->traceEnabled == true)
            {
                //echo 'Access Token: ' . $accessToken;
            }

            return $fullToken;
        }
        catch (PDOException $e)
        {
            if ($this->traceEnabled == true)
            {
                echo 'Query Error : ' . $e->getMessage();
            }
        }

        return null;
    }

    public function setAccessToken ($emailId, $fullToken, $force = false)
    {
        try
        {
            if ($this->isConnected () == false)
            {
                if ($this->traceEnabled == true)
                {
                    echo 'Not Connected';
                }
                return null;
            }

            if ($this->getAccessToken($emailId) == null)
            {
                $token                  = unserialize($fullToken);
                $oAuthToken             = $token->getToken();
                $oAuthSecret            = $token->getTokenSecret();
                $this->connHandle->query("INSERT INTO records SET emailId='" . $emailId . "', fullToken='" . $fullToken . "', oauthToken='" . $oAuthToken . "', oauthSecret='" . $oAuthSecret . "'");
                if ($this->traceEnabled)
                {
                    echo 'fullToken not found. Inserting new.';
                }
            }
            else if ($force == true)
            {
                $this->connHandle->query("DELETE FROM records WHERE emailId='" . $emailId . "'");
                
                if ($this->traceEnabled)
                {
                    echo 'fullToken deleted.';
                }

                $this->setAccessToken($emailId, $fullToken);
            }
            
            return true;
         }
         catch (PDOException $e)
         {
            if ($this->traceEnabled == true)
            {
                echo 'Query Error : ' . $e->getMessage();
            }
         }

         return false;
    }
}
