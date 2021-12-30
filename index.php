
﻿<?php
/******************************************************************************************
This file is a part of SIMPLE WEB STATICSTICS GENERATOR FROM Direwolf LOG FILE
It's very simple and small APRX statictics generator in PHP. It's parted to smaller files and they will work independent from each other (but you always need chgif.php).
This script may have a lot of bugs, problems and it's written in very non-efficient way without a lot of good programming rules. But it works for me.
Author: Alfredo IZ7BOJ
You can modify this program, but please give a credit to original author. Program is free for non-commercial use only.
(C) Alfredo IZ7BOJ 2021
*******************************************************************************************/
include 'config.php';
include 'functions.php';

logexists();

if((!isset($_SESSION['if'])) or (isset($_SESSION['if']) and ($_SESSION['if'] == ""))) { //if interface was not selected
	if(($static_if == 1) && isset($static_if_index)) { // if static interface is declared in config.php
		session_start();
		$_SESSION['if'] = $static_if_index; //open session with static interface declared in config.hp
		header('Refresh: 0; url=summary.php');
	} else {
		header('Refresh: 0; url=chgif.php?chgif=1');
	}
	die();
} else { //else if inteface selected
	header('Refresh: 0; url=summary.php');
	die();
}
?>
