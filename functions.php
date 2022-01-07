<?php
/******************************************************************************************
This file is a part of SIMPLE WEB STATICSTICS GENERATOR FROM Direwolf LOG FILE
It's very simple and small Direwolf statictics generator in PHP. It's parted to smaller files and they will work independent from each other (but you always need chgif.php).
This script may have a lot of bugs, problems and it's written in very non-efficient way without a lot of good programming rules. But it works for me.
Author: Alfredo IZ7BOJ
You can modify this program, but please give a credit to original author. Program is free for non-commercial use only.
(C) Alfredo IZ7BOJ 2021
*******************************************************************************************/

/* log structure:
0:chan
1:utime
2:isotime
3:source
4:heard
5:level
6:error
7:dti
8:name
9:symbol
10:latitude
11:longitude
12:speed
13:course
14:altitude
15:frequency
16:offset
17:tone
18:system
19:status
20:telemetry
21:comment
*/

// custom sorting function
function cmp($a, $b) 
{
	if ($a[1] == $b[1]) {
		return 0;
	}
return ($a[1] > $b[1]) ? -1 : 1;
}

function logexists()
{
	global $logpath;
	global $version;
	if(!file_exists($logpath.gmdate("Y-m-d").".log"))  {
			echo '<font color="red" size="6"><b>Error. Cannot open direwolf log file '.$logpath.gmdate("Y-m-d").'.log</b></font>';
			echo '<br><br>Please check, if log file path in config.php is set correctly.<br>Plase check, if file '.$logpath.gmdate("Y-m-d").'.log exists.';
			echo '<br><br><b>Pointless to continue.</b>';
			echo '<br><br><br><br><br><br><center>Direwolf Simple Webstat version '.$version.' by Alfredo IZ7BOJ 2021</center>';
			die();
	}
}


function stationparse($frame) //function for parsing station information
{
	global $stationcall;
	global $receivedstations;
	global $staticstations;
	global $movingstations;
	global $otherstations;
	global $viastations; //stations received via digi
	global $directstations; //stations received directly
	global $callraw;
	global $time;
	global $distance;
	global $bearing;
	global $if;

	if($frame[0]==$if) //if frame received on selected radio interface
	{
		$frame=str_getcsv($frame,",");
		$utime = $frame[1];
		if($utime > $time) { //if frame was received in time range
			$stationcall = strtoupper($frame[8]);
			if(array_key_exists($stationcall, $receivedstations)) { //if this callsign is already on stations list
				$receivedstations[$stationcall][0]++; //increment the number of frames from this station
			} else { //if this callsign is not on the list
				$receivedstations[$stationcall][0] = 1; //add callsign to the list
			}
			$receivedstations[$stationcall][1] = $utime; //add last time
        	if(($frame[10] !=="") and ($frame[11] !== "")) { //if it's a frame with position
				haversine($frame);
				$receivedstations[$stationcall][2] = $distance; //add last distance
				$receivedstations[$stationcall][3] = $bearing; //add last bearing
			} else {
				$receivedstations[$stationcall][2] = "N/A";
				$receivedstations[$stationcall][3] = "N/A";
			}
			if($frame[12]==NULL) { //if speed is not null, it's a static station
						if(!in_array($stationcall, $staticstations)) { 
							$staticstations[] = $stationcall;
						}
			} else {
				$movingstations[] = $stationcall;
			}
		}

		if($frame[3]==$frame[4]) { //if source=heard condition, the frame was heard directly
			if(!in_array($stationcall, $directstations)) {
				$directstations[] = $stationcall;
			}
		} else {
			if(!in_array($stationcall, $viastations)) {
						$viastations[] = $stationcall;
			}
			return;
		}
	} //closes if received on seleceted interface
}

function haversine($frame)
{
	global $stationlat;
	global $stationlon;
	global $distance;
	global $bearing;
	global $declat;
	global $declon;

	$declat = $frame[10];
	$declon = $frame[11];

	//haversine formula for distance calculation
	$latFrom = deg2rad($stationlat);
	$lonFrom = deg2rad($stationlon);
	$latTo = deg2rad($declat);
	$lonTo = deg2rad($declon);

	$latDelta = $latTo - $latFrom;
	$lonDelta = $lonTo - $lonFrom;

	$bearing = rad2deg(atan2(sin($lonDelta)*cos($latTo), cos($latFrom)*sin($latTo)-sin($latFrom)*cos($latTo)*cos($latDelta)));
	if($bearing < 0) $bearing += 360;
	$bearing = round($bearing, 1);
	
	$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
	cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
	$distance = round($angle * 6371, 2); //gives result in km rounded to 2 digits after comma

}
?>
