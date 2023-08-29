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

example:
0,1640858156,2021-12-30T09:55:56Z,IZ7EUG,IW7EAP-1,48(12/14),0,=,IZ7EUG,/x,41.071667,16.940000,,,,,,,Xastir,,,"APRS con Raspberry PI2 B, SW Xastir"

*/

include 'config.php';
include 'functions.php';

logexists();

if (isset($_GET['ajax'])) {
	session_start();
	$handle = fopen($log, 'r'); //open log
	if (isset($_SESSION['offset'])) { //this part is executed from 2nd cycle
	$rawdata = stream_get_contents($handle, -1, $_SESSION['offset']); //open stream
	if ($rawdata !== "")  { //only if last cycle got something, process new data, otherwise skip to next cycle
		$_SESSION['offset'] += strlen($rawdata); //update offset
		$rows=explode("\n", $rawdata, -1); //if more rows are received in the same cycle, divide it. -1 is necessary because last element would be empty
		foreach($rows as $row) {
			show($row);
			} //close foreach
		} //close  if ($rawdata !== "")
	} else { //only at the beginning, print last rows
		$log=file($log);
		$rows=count($log)-1 -$startrows;
		$counter=1;
		while ($counter<=$startrows) {
				$row=$log[$rows+$counter];
				show($row);
				$counter++;
		} //close while
		fseek($handle, 0, SEEK_END); //put the handle at the end of log
		$_SESSION['offset'] = ftell($handle);
	} //close else of if (isset($_SESSION['offset']))
	exit();
} //close if (isset($_GET['ajax']))

function show($row) {

	$fields=str_getcsv($row,",");
	$timestamp=$fields[2];
    	$source=$fields[3];
	$heard=$fields[4];
	$level=$fields[5];
	$name=$fields[8];
	$lat=$fields[10];
	$long=$fields[11];
	$comment=$fields[21];
	
	if ($fields[0]==$_SESSION['if']) {
		echo '<tr><td width="160px">'.$timestamp.'</td><td width="100px">'.$source.'</td><td width="100px">'.$heard.'</td><td width="100px">'.$level.'</td><td width="100px">'.$name.'</td><td width="100px">'.$lat.'</td><td width="100px">'.$long.'</td><td>'.$comment.'</td></tr>';	     
    }
	
    
} //close function
?>

<!doctype html>
<html lang="en"> 
	<head>
		<meta charset="UTF-8">
		<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
		<script src="http://creativecouple.github.io/jquery-timing/jquery-timing.min.js"></script>
		<script>
		$(function() {
			$.repeat(<?php echo $refresh ?>, function() {
				$.get('live.php?ajax', function(rawdata) {
				$('#tail').append(rawdata);
				});
			});
		});
		</script> 
	</head>
	<body>
	<?php
	if(file_exists($logourl)){
		echo '<center><img src='.$logourl.' width="100px" height="100px" align="middle"></center><br>';
	}	  
	?>
		<div id="tail">
			<i>Real time traffic monitor - Starting up...</i>
			<br><br>
			<b>
			<table>
			<tr>
				<td width="160px">TIMESTAMP</td>
				<td width="100px">SOURCE</td>
				<td width="100px">HEARD</td>
				<td width="100px">LEVEL</td>
				<td width="100px">NAME</td>
				<td width="100px">LAT</td>
				<td width="100px">LONG</td>
				<td>COMMENT</td>
			</tr>
			</b>
			<br>
		</div> 
	</body>
</html>
