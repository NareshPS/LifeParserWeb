<!DOCTYPE script PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<script language="javascript" type="text/javascript" src="flot/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="flot/jquery.flot.js"></script>
</head>
<body>
<div id="sentPh" style="width: 800; height: 400;"></div>
<div id="recvPh" style="width: 800; height: 400;"></div>
<?php
$sentFileName           = 'mail2naresh@gmail.com.sent';
$recvFileName           = 'mail2naresh@gmail.com.recv';

function fetchDataFS($fileName)
{
    $hMails             = fopen($fileName, 'r');
    
    if ($hMails != FALSE)
    {
        $dataList			= array();
       
        while ($line = fgets($hMails))
        {
            $data			= preg_split('/[\r\n\t]/', $line);
            $day			= trim($data[0]);
            $time			= trim($data[1]);
            $mailList       = trim($data[2]);

            $dataList[]		= array($day, $time, $mailList);
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

function makeEMailCols($fsData)
{
	$count				= count($fsData);
	$eMailDict			= array();
	
	for ($index = 0; $index < $count; $index++)
	{
		if ($fsData[$index][2] != '')
		{
			$eMailArray						= preg_split('* *', $fsData[$index][2]);
			
			if ($eMailArray)
			{
				$addrCount					= count($eMailArray);
				
				for ($addrIdx = 0; $addrIdx < $addrCount; $addrIdx++)
				{
					if (array_key_exists($eMailArray[$addrIdx], $eMailDict) == FALSE)
					{
						$eMailDict[$eMailArray[$addrIdx]]	= array();
					}
					
					$eMailDict[$eMailArray[$addrIdx]][]	= array($fsData[$index][0], $fsData[$index][1]);
				}
			}
		}
		else 
		{
			
			$eMailDict['Empty'][]			= array($fsData[$index][0], $fsData[$index][1]);
		}
		
		$eMailDict['All'][]					= array($fsData[$index][0], $fsData[$index][1]);
	}
	
	unset($fsData);
	
	return $eMailDict;
}

function transformToJSStr($arrayData, $label, $color)
{
	$str					= '{label: "'.$label.'", data: [';
	
	for ($idx = 0; $idx < count($arrayData); $idx++)
	{
		$strTime			= $arrayData[$idx][1];
		$str				.= "['".$arrayData[$idx][0]."', '".$strTime."'],";
	}	
	
	$str[strlen($str) - 1] 	= ']';
	$str					.= ', color: '.$color.'}';
	return $str;
}

function plotCustom($formattedData, $label, $numEntries)
{
	$allKeys				= array_keys($formattedData);
	$maxKeys				= array();
	
	foreach ($allKeys as $key)
	{
		if ($key != 'All')
		{
			$maxKey						= count($formattedData[$key]);
			
			if (array_key_exists($maxKey, $maxKeys) == FALSE)
			{
				$maxKeys[$maxKey]		= array();
			}
			
			$maxKeys[$maxKey][]			= $key;
		}
	}
	
	krsort($maxKeys);
	$count 					= 0;
	$color					= 1;
	$str					= '[';
	
	$str			.= transformToJSStr($formattedData['All'], $label, 5);
	$str			.= ',';
	
	foreach ($maxKeys as $key => $value)
	{
		$count		+= count($value);
		
		foreach ($value as $val)
		{
			$str			.= transformToJSStr($formattedData[$val], $val.'['.$key.']', $color);
			$str			.= ',';
			$color++;
			
			if ($color ==5)
			{
				$color++;
			}
		}
		
		if ($count >= $numEntries)
		{
			break;
		}
	}
	
	$str[strlen($str) - 1] 	= ']';

	return $str;
}

$sentFileData			= fetchDataFS($sentFileName);
$recvFileData           = fetchDataFS($recvFileName);

$formattedSentData		= makeEMailCols($sentFileData);
$formattedRecvData		= makeEMailCols($recvFileData);
?>
<script type="text/javascript">
plotGraph(<?php echo plotCustom($formattedSentData, "Sent Mails", 5);?>, "#sentPh");
plotGraph(<?php echo plotCustom($formattedRecvData, "Received Mails", 5);?>, "#recvPh");
function plotGraph(dataList, placeholder)
{
	for (var dictIdx = 0; dictIdx < dataList.length; dictIdx++)
	{
		dataItem				= dataList[dictIdx]['data'];
		
		for (var index = 0; index < dataItem.length; index++)
		{
			var timeArray		= dataItem[index][1].split(':');
			var hour			= parseInt(timeArray[0]);
			var min				= parseInt(timeArray[1]);
			
			dataItem[index][0]		= new Date(dataItem[index][0]);
			dataItem[index][1]		= (hour + min/60);
		}
	}
	
	$.plot($(placeholder), dataList
							, {
                                  xaxis: {
                                            mode: "time",
                                            timeformat: "%b %y",
                                            min: (new Date("01/01/2005")).getTime(),
                                            max: (new Date("02/01/2011")).getTime(),
                                            minTickSize: [1, "month"],
											monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
                                         },
                                  yaxis: {
                                             min: 0,
                                             max: 24,
                                             tickSize: 2,
                                             minTickSize: 1
                                             
                                         },                                             
                                  points: { show: true },
                                  grid: { backgroundColor: "#fffaff" }
                            });
}
</script>
</body>
</html>