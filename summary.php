<?php
/******************************************************************************************
This file is a part of SIMPLE WEB STATICSTICS GENERATOR FROM Direwolf LOG FILE
It's very simple and small Direwolf statictics generator in PHP. It's parted to smaller files and they will work independent from each other (but you always need chgif.php).
This script may have a lot of bugs, problems and it's written in very non-efficient way without a lot of good programming rules. But it works for me.
Author: Alfredo IZ7BOJ
You can modify this program, but please give a credit to original author. Program is free for non-commercial use only.
(C) Alfredo IZ7BOJ 2021
*******************************************************************************************/
include 'config.php';
include 'functions.php';
if(file_exists('custom.php')) include 'custom.php';

logexists();
  
session_start(); //start session
if(!isset($_SESSION['if'])) { //if interface not defined
   	header('Refresh: 0; url=chgif.php?chgif=1');
	die();
}

$if = $_SESSION['if'];

?>
<!DOCTYPE html>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="Description" content="Direwolf statistics" />
      <meta name="Keywords" content="" />
      <meta name="Author" content="IZ7BOJ" />
      <!-- next style is to show arrows in sortable table's column headers to indicate that the table is sortable -->
      <style type="text/css">
         table.sortable th:not(.sorttable_sorted):not(.sorttable_sorted_reverse):not(.sorttable_nosort):after {
         content: " \25B4\25BE"
         }
         table, th, td {
         border: 1px solid black;
         border-collapse: collapse;
         }
      </style>
      <title>Direwolf statistics - summary</title>
   </head>
   <body>
      <?php
         if(file_exists($logourl)){
         ?>
      <center><img src="<?php echo $logourl ?>" width="100px" height="100px" align="middle"></center>
      <br>
      <?php
         }
         ?>
      <center>
         <font size="20"><b>Direwolf statistics</b></font>
         <h2>for interface <font color="red"><b><?php echo $if."-".$intdesc[$if] ?></b></font> - summary</h2>
         <a href="chgif.php?chgif=1">Change interface</a>
         <br>
         <br><b>Show:</b> <a href="summary.php">Summary (main)</a> - <a href="frames.php">frames of a specified station</a><br><br>
         <button onclick="window.open('live.php')">Watch AX.25 realtime traffic</button>
         <br><br>
         <hr>
      </center>
      <br>
      <?php
         // System parameters reading
         $sysver      = NULL;
         $kernelver   = NULL;
         $direwolfver = NULL;
         $cputemp     = NULL;
         $cpufreq     = NULL;
         $uptime      = NULL;
         
         $sysver = shell_exec ("cat /etc/os-release | grep PRETTY_NAME |cut -d '=' -f 2");
         $kernelver = shell_exec ("uname -r");
         $direwolfver = shell_exec ("direwolf --v | grep -m 1 'version' | cut -d ' ' -f 4");
         if (file_exists ("/sys/class/thermal/thermal_zone0/temp")) {
             exec("cat /sys/class/thermal/thermal_zone0/temp", $cputemp);
             $cputemp = $cputemp[0] / 1000;
         }
         if (file_exists ("/sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq")) {
         	exec("cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq", $cpufreq);
         	$cpufreq = $cpufreq[0] / 1000;
         }
         $uptime = shell_exec('uptime -p');
		?>
        
      <br>
      <table style="text-align: left; height: 116px; width: 600px;" border="1" cellpadding="2" cellspacing="2">
         <tbody>
            <tr align="center">
               <td bgcolor="#ffd700" style="width: 600px;" colspan="2" rowspan="1"><span
                  style="color: red; font-weight: bold;">SYSTEM STATUS</span></td>
            </tr>
            <tr>
               <td bgcolor="silver" style="width: 200px;"><b>System Version: </b></td>
               <td style="width: 400px;"><?php echo $sysver ?></td>
            </tr>
            <tr>
               <td bgcolor="silver" style="width: 200px;"><b>Kernel Version: </b></td>
               <td style="width: 400px;"><?php echo $kernelver ?></td>
            </tr>
            <tr>
               <td bgcolor="silver" style="width: 200px;"><b>Direwolf Version: </b></td>
               <td style="width: 400px;"><?php echo $direwolfver ?></td>
            </tr>
            <tr>
               <td bgcolor="silver" style="width: 200px;"><b>System uptime: </b></td>
               <td style="width: 400px;"><?php echo $uptime ?></td>
            </tr>
            <tr>
               <td bgcolor="silver" style="width: 200px;"><b>CPU temperature:</b></td>
               <td style="width: 400px;"><?php echo $cputemp ?> °C </td>
            </tr>
            <tr>
               <td bgcolor="silver" style="width: 200px;"><b>CPU frequency: </b></td>
               <td style="width: 400px;"><?php echo $cpufreq ?> MHz </td>
            </tr>
         </tbody>
      </table>
      <br><br>
      <hr>
      <br><br>
	     <?php		
		 $time = 0; //start of the time from which to read data from log in Unix timestamp type
		 if(!isset($_GET['time']) or ($_GET['time'] == "")) { //if time range not specified
			$time = time() - 3600; //so take frames from last 1 hour
		 }
		 elseif($_GET['time'] == "e") { //if whole log
			$time = 0;
		 }
		 else { //else if the time range is choosen
			$time = time() - ($_GET['time'] * 3600); //convert hours to seconds
		 }

		 $receivedstations = array();        
		 $staticstations = array();
		 $movingstations = array();
		 $otherstations = array();
		 $directstations = array(); //stations received directly
		 $viastations = array(); //stations received via digi
		 $lines = 0;
		 
		 $logfile = file($logpath.gmdate("Y-m-d").".log"); //read log file
		 $linesinlog = count($logfile);
		 while ($lines < $linesinlog) { //read line by line
			$line = $logfile[$lines];
			stationparse($line); // build received stations table
			$lines++;
		}
		 
		 uasort($receivedstations, 'cmp');
		 echo "<b>Number of frames in log: </b>".$linesinlog;
         ?>
	  <br><br>
      <form action="summary.php" method="GET">
      Show stations since last:
      <select name="time">
         <option value="1" <?php if(isset($_GET['time'])&&($_GET['time'] == 1)) echo 'selected="selected"'?>>1 hour</option>
         <option value="2" <?php if(isset($_GET['time'])&&($_GET['time'] == 2)) echo 'selected="selected"'?>>2 hours</option>
         <option value="4" <?php if(isset($_GET['time'])&&($_GET['time'] == 4)) echo 'selected="selected"'?>>4 hours</option>
         <option value="6" <?php if(isset($_GET['time'])&&($_GET['time'] == 6)) echo 'selected="selected"'?>>6 hours</option>
         <option value="12" <?php if(isset($_GET['time'])&&($_GET['time'] == 12)) echo 'selected="selected"'?>>12 hours</option>
         <option value="24" <?php if(isset($_GET['time'])&&($_GET['time'] == 24)) echo 'selected="selected"'?>>1 day</option>
         <option value="e" <?php if(isset($_GET['time'])&&($_GET['time'] == 'e')) echo 'selected="selected"'?>>all</option>
      </select>
      <input type="submit" value="Refresh">
	  <br>
	  <?php
	  echo "<br><br><b>".count($receivedstations)." Stations received on radio interface ".$if." (sorted by Last Time Heard)</b><br><br>";
	  ?>
	  <br>
	  <script src="sorttable.js"></script>
      <table style="text-align: left; height: 116px; width: 1200px;" border="1" class="sortable" id="table">
         <tbody>
            <tr>
               <th bgcolor="#ffd700"><b><font color="blue">Callsign</font></b></th>
               <th bgcolor="#ffd700"><b><font color="blue">Points</font></b></th>
               <td bgcolor="#ffd700"><b><font color="blue">Map show</font></b></td>
               <td bgcolor="#ffd700"><b><font color="blue">Frames</font></b></td>
               <th bgcolor="#ffd700"><b><font color="blue">STATIC/Moving</font></b></th>
               <th bgcolor="#ffd700"><b><font color="blue">Via</font></b></th>
               <th bgcolor="#ffd700"><b><font color="blue">Last time Heard</font></b></th>
               <th bgcolor="#ffd700"><b><font color="blue">Last Distance</font></b></th>
               <th bgcolor="#ffd700"><b><font color="blue">Last Bearing</font></b></th>
            </tr>
            <?php
               while(list($c, $nm) = each($receivedstations))
               {
               ?>
            <tr>
               <td bgcolor="silver"><b><?php echo $c ?></b></td>
               <td align="center"><?php echo $nm[0] ?></td>
               <td><?php echo '<a target="_blank" href="https://aprs.fi/?call='.$c.'">Show on aprs.fi</a>'?></td>
               <td><?php echo '<a target="_blank" href="frames.php?getcall='.$c.'">Show station frames</a>' ?></td>
               <td align="center">
                  <?php
                     if (in_array($c, $staticstations)) echo '<font color="purple">STATIC</font>';
                     elseif (in_array($c, $movingstations)) echo '<font color="orange">MOVING</font>';
                     else echo "OTHER";
                     ?>
               </td>
               <td align="center">
                  <?php
                     if ((in_array($c, $directstations))&&(in_array($c, $viastations))) echo '<font color="BLUE">DIGI+DIRECT</font>';
                     elseif (in_array($c, $directstations)) echo '<font color="RED">DIRECT</font>';
                     else if (in_array($c, $viastations)) echo '<font color="GREEN">DIGI</font>';
                         ?>
               </td>
               <td align="center">
                  <?php
                     echo(date('m/d/Y H:i:s', $nm[1]))
                     ?>
               </td>
               <td align="center">
                  <?php
                     echo $nm[2]." Km"
                     ?>
               </td>
               <td align="center">
                  <?php
                     echo $nm[3]." °"
                     ?>
               </td>
            </tr>
            <?php
               }
               ?>
         </tbody>
      </table>
      <br>
      <hr>
      <br>
      <center><a href="https://github.com/IZ7BOJ/direwolf_webstat" target="_blank">Direwolf Simple Webstat version <?php echo $version; ?></a> by Alfredo IZ7BOJ</center>
      <br>
   </body>
</html>
