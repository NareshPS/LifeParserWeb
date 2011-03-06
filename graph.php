<?php

$sentFileName	= 'mail2naresh@gmail.com.sent';
$recvFileName	= 'mail2naresh@gmail.com.recv';
$fileOpenMode	= 'r';

$hSentMails	= fopen($sentFileName, $fileOpenMode);
$hRecvMails	= fopen($recvFileName, $fileOpenMode);

if ($hSentMails != FALSE && $hRecvMails != FALSE)
{
	$dayTimeData		= '[';
	$monthArray			= NULL;
	
	while ($line = fgets($hSentMails))
	{
		$data			= preg_split('/[\r\n\t]/', $line);
		$day			= trim($data[0]);
		$time			= trim($data[1]);
		
		$dayArray		= preg_split('*/*', $day);
		
		if ($dayArray[2] != '2010' && $dayArray[2] != '2009')
		{
			continue;	
		}
		
		$dayTimeData	.= '["'.$day.'","'.$time.'"],';
	}
	
	$dayTimeData[strlen($dayTimeData) - 1]	= ']';
}
else
{
	die('Failed to open sent file');
}

fclose($hRecvMails);
fclose($hSentMails);
?>
<!DOCTYPE script PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<script language="javascript" type="text/javascript" src="flot/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="flot/jquery.flot.js"></script>
</head>
<body>
<div id="placeholder" style="width: 600; height: 400;"></div>
<script id="source" type="text/javascript">
	function convertToJSTime(dateString)
	{
		dateArray	= dateString.split('/');
	
		var dateObj	= new Date(parseInt(dateArray[2]), parseInt(dateArray[0]), parseInt(dateArray[1]),0,0,0,0).getTime();
	
		return dateObj;
	}
	var dataList			= <?php echo $dayTimeData;?>;
	
	for (var index = 0; index < dataList.length; index++)
	{
		var timeArray		= dataList[index][1].split(':');
		var hour			= parseInt(timeArray[0]);
		var min				= parseInt(timeArray[1]);
		
		dataList[index][0]	= convertToJSTime(dataList[index][0]);
		dataList[index][1]	= (hour + min/60);
	}
	
	$.plot($('#placeholder'), [
                            { label: 'Sent Mails', data: dataList}
                            ], {
                                  xaxis: {
                                            mode: "time",
                                            timeformat: "%b %y",
                                            min: (new Date("2009/01/01")).getTime(),
                                            max: (new Date("2010/12/31")).getTime(),
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
</script>
</body>
</html>