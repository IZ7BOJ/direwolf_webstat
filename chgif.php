<?php
/******************************************************************************************
This file is a part of SIMPLE WEB STATICSTICS GENERATOR FROM direwolf LOG FILE
It's very simple and small direwolf statictics generator in PHP. It's parted to smaller files and they will work independent from each other (but you always need chgif.php).
This script may have a lot of bugs, problems and it's written in very non-efficient way without a lot of good programming rules. But it works for me.
Author: Alfredo IZ7BOJ
You can modify this program, but please give a credit to original author. Program is free for non-commercial use only.
(C) Alfredo IZ7BOJ 2021

Version 0.1beta
*******************************************************************************************/
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="direwolf stats" />
<meta name="Keywords" content="" />
<meta name="Author" content="IZ7BOJ" />
<title>direwolf statistics</title>
</head>
<body>
<center><font size="20"><b>WELCOME to direwolf statistics!</b></font>
<h2>Please select radio interface before proceeding</h2>
<br><br><br>
</center>

<?php
include 'config.php';
include 'functions.php';

logexists();

session_start();
if((((!isset($_SESSION['if'])) or (isset($_SESSION['if']) and ($_SESSION['if'] == ""))) and ((!isset($_GET['if'])) or (isset($_GET['if']) and ($_GET['if'] == "")))) or (isset($_GET['chgif']) and $_GET['chgif'] == "1")) //if interface was not selected
{
	$_SESSION = array();
	session_destroy(); //start session
	session_start();
?>

<form action="chgif.php" method="get">
Interface: <select name="if">

<?php
$i=0;
for ($i=0;$i<=sizeof($interfaces)-1;$i++) {
?>
	<option value=<?php echo $interfaces[$i] ?>><?php echo $interfaces[$i]." - ".$intdesc[$i] ?></option>
<?php
}
?>

</select>
<br><br><br><br><br>
<input type="submit" value="OK">
</form>

<?php
} else {
	if(!isset($_SESSION['if'])) //if now there is "if" variable
	{
		$_SESSION['if'] = $_GET['if'];
	}
	header('Refresh: 0; url=summary.php');
	die();
}
?>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<center><a href="" target="_blank">direwolf Simple Webstat version <?php echo $version; ?></a> by Alfredo IZ7BOJ</center>

</body>
</html>
