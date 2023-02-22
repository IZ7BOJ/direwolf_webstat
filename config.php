<?php
/********************************************************
Configuration file for Direwolf SimpleWebstat.
********************************************************/

$version = "0.1beta";
$logpath = "C:\Users\IZ7BOJ\aprs\\"; //path to the direwolf log directory. Be sure to terminate with \\ otherwise it's interpreted as escape character!

//interfaces index declared in direwolf conf file. Indexes separated by commas
$interfaces=array(0,1);

//interface descriptions. Insert strings between "" and separated by commas
$intdesc=array("144.800  VHF Port","432.500 UHF Port");

//static_if skips interface choice every time
$static_if = 1; //1 to enable using static interface 
$static_if_index = 0; //interface index when stati_if is enabled

//station posistion data for calculating distance from received station
$stationlat = 41.248027; //station latitude in decimal degrees
$stationlon = 16.421246;  //station longtitude in decimal degrees

//logo path,with file name, shown on the top of the page
$logourl="direwolf.png";

//miles/km distsance selector
$miles = 0; //1 to enable miles, 0 to enable km

//AX.25 realtime traffic watch config
$refresh=1000; //refresh time in ms. Don't go below 1000
$timestampcolor="silver"; //color of timestamp
$pathcolor="purple"; //color of path string
$startrows=5; //number of last rows displayed at session opening
?>
