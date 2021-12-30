# direwolf_webstat
Web statistics interface for direwolf Software TNC
A simple statistics and information generator for Direwolf software TNC, which allows to monitor load, frames, stations details, statistics from selected time window and more.

Main characteristics:
- Possibility to monitor multiple radio interfaces
- Possibility to show cusom info and custom logo file on the bottom of the page (edit “custom.php” file, which can contain HTML and PHP code)
- Counters of total frames in log
- System status table (reads parameters directly from OS)
- Heard station list table (observation period selectable by the user in a drop-down list) with following fields:
- Heard Callsign
- N. of packets heard for callsign
- Direct link for showing the station on aprs.fi
- Static/moving indicator
- Via indicator (digi/direct/digi+direct)
- Raw packet view for each callsign
- Possibility to sort the table in ascending or descending order on each colum
- Live monitor page: ajax page for watching ax25 traffic in realtime.

Installing and using

Update your system:

    sudo apt-get update && sudo apt-get upgrade

Install a lightweight webserver:

    sudo apt-get install lighttpd

Install PHP and enable the required modules:

If you use a Raspian Wheezy and Raspbian Jessie use:

    sudo apt-get install php5-common php5-cgi php5

If you use a Raspian Stretch or Raspbian Buster use:

    sudo apt-get install php7.3-common php7.3-cgi php

then just copy all files to the webistes folder in your WWW server directory.


To configure, open config.php file with some text editor.

Enter full path to your direwolf log file directory:

$logpath = "/some/path/";

I put it under /var/log/direwolf/ but you are free to decide. Incorrect path will make the script unable to work.

This was the only required step and now software should work.

It's recommended to set also another settings:

Your station latitude and longtitude for distance calculation (in decimal degrees):

$stationlat = 49.013855;

$stationlon = 28.762225;

Normally every time you open statistics website, you have to enter interface index of Direwolf (starts from 0 by default). 
If you want to set static interface callsign (but it can be temporarily changed via website for one session), you can do this here:

$static_if = 1;

$static_if_index = 0;


Set $static_if to 1 to enable

Software stability

This is a BETA software. It can contain some bugs and may be written in non-efficient way. Please contact author if you find any bug.

License

You can modify this program, but please give a credit to original author (IZ7BOJ).
Project is free for non-commercial use. You can modify and publish this software, but you have to put an information about original authors.
