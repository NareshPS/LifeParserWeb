<?php
require_once 'siteConfig.php';
require_once 'siteUtils.php';
require_once 'dbFuncs.php';

global $DATA_DIR, $DATAFILE_MODE, $SENT_SUFFIX, $RECV_SUFFIX;
/*session_start();

$dbFuncsObj     = new dbFuncs(false);
$dbFuncsObj->doConnect();

print $dbFuncsObj->validateAccessToken($_SESSION['ACCESS_TOKEN'], $_SESSION['OPENID_EMAIL']);

if (!isset($_SESSION['ACCESS_TOKEN']) || !isset($_SESSION['OPENID_EMAIL']) || !$dbFuncsObj->validateAccessToken($_SESSION['OPENID_EMAIL'], $_SESSION['ACCESS_TOKEN'])) 
{
    header('Location: '.constructPageUrl('index.php'));
}

$dbFuncsObj->doDisconnect();

global $DATA_DIR, $DATAFILE_MODE, $SENT_SUFFIX, $RECV_SUFFIX;
$sentFileName           = $DATA_DIR.$_SESSION['OPENID_EMAIL'].$SENT_SUFFIX;
$recvFileName           = $DATA_DIR.$_SESSION['OPENID_EMAIL'].$RECV_SUFFIX;
*/

$sentFileName           = $DATA_DIR.'mail2naresh@gmail.com'.$SENT_SUFFIX;
$recvFileName           = $DATA_DIR.'mail2naresh@gmail.com'.$RECV_SUFFIX;

function fetchDataFS($fileName)
{
    $hMails             = fopen($fileName, 'r');
    
    if ($hMails != FALSE)
    {
        $dataList		= array();
       
        while ($line = fgets($hMails))
        {
            $data		= preg_split('/[\r\n\t]/', $line);

            if (count($data) >= 5)
            {
                $day		        = trim($data [0]);
                $time		        = trim($data [1]);
                $subject            = trim($data [2]); 
                $mailList           = trim($data [3]);
                $positiveSentiment  = (int)$data [4];
                $positiveWords      = trim($data [5]); 
                $negativeSentiment  = (int)$data [6];
                $negativeWords      = trim($data [7]); 
                $totalWords         = (int)$data [8];

                $dataList[]	= array("date" => $day, "time" => $time, "subject" => $subject,"info" => $mailList, "positiveSentiment" => $positiveSentiment, "positiveWords" => $positiveWords, "negativeSentiment" => $negativeSentiment, "negativeWords" => $negativeWords, "totalWords" => $totalWords);
            }
        }
        fclose($hMails);

        return $dataList;
    }
    else
    {
        die('Failed to open data file');
    }
    return null;
}

switch(@$_GET['data'])
{
	case 'sentMails':
		$fileName	= $sentFileName;
		break;
	case 'recvMails':
		$fileName	= $recvFileName;
		break;
}

$fileData			= fetchDataFS($fileName);
echo json_encode($fileData);
?>
