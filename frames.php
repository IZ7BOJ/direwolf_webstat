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

include 'config.php';
include 'functions.php';

logexists();

session_start(); //needed for reading session interface
if(!isset($_SESSION['if'])) {
	header('Refresh: 0; url=chgif.php?chgif=1');
	die();
}
?>
<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="Direwolf statistics" />
<meta name="Keywords" content="" />
<meta name="Author" content="IZ7BOJ" />
<title>Direwolf statistics - frames search</title>
</head>
<body>
	<?php
	if(file_exists($logourl)){
	?>
	<center><img src="<?php echo $logourl ?>" width="200px" height="100px" align="middle"></center><br>
	<?php
	}
	?>

	<br>
	<form action="frames.php" method="get">
		Show all frames from the callsign: <input type="text" name="getcall" <?php if(isset($_GET['getcall'])) echo "value=\"".$_GET['getcall']."\""; ?>>
		<input type="submit" value="Show">
	</form>
	<br>
	<!-- FRAMES TABLE -->
	<table style="text-align: left; height: 116px; width: 100%;" border="1" class="sortable" id="table">
	<tbody>
		<tr align="center">
		<?php
		$logfile = file($logpath.gmdate("Y-m-d").".log"); //read log file
		$header = str_getcsv($logfile[0]); // take header from first row
		echo '<th bgcolor="#ffd700"><b><font color="blue">Date</font></b></th>';
		echo '<th bgcolor="#ffd700"><b><font color="blue">Time(Z)</font></b></th>';
		for ($c=3; $c < count($header); $c++) {
	        	echo '<th bgcolor="#ffd700"><b><font color="blue">'.$header[$c].'</font></b></th>';
			}
		echo "</tr>";
		if (isset($_GET['getcall']) and ($_GET['getcall'] !== "") and isset($_SESSION['if']) and ($_SESSION['if'] !== "")) {
			$scall = strtoupper($_GET['getcall']);
			$linesinlog = 1; //skip first row
			$linesinlog = count($logfile);
			$counter= 1;
			//parse line by line
			while($counter < $linesinlog) {
				$line = $logfile[$counter];
				$parts = str_getcsv($line,","); //split all fields
				if (($parts[8]==$_GET['getcall']) and ($parts[0]==$_SESSION['if'])) { //if frame is received from selected call and interface
					echo '<tr>';
					 echo '<td align="center">'.substr($parts[2],0,strpos($parts[2],"T")).'</td>';
					 echo '<td align="center">'.substr($parts[2],-strpos($parts[2],"T")+1,-1).'</td>';
					 for ($c=3; $c < count($parts); $c++) {
		                        	echo '<td align="center">'.$parts[$c].'</td>';
                			        }
					echo '</tr>';
					} // close if received from call
				$counter++;
				} //close while
				?>
		<?php
		} //close if
		?>
	</tbody>
	</table>

	<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<center><a href="https://github.com/IZ7BOJ/direwolf_webstat" target="_blank">Direwolf Simple Webstat version <?php echo $version; ?></a> by Alfredo IZ7BOJ</center>
</body>
</html>
