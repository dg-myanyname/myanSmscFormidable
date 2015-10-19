<?php
/*
Plugin Name: myAN smsc
Description: Отправляет sms с помощью
Author: myAnyName
Author URI: http://myanyname.com
Version: 0.1.1
License: GPL2 or later
*/
/*

    Copyright (C) Year  Author  Email

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/*
	Since ABSPATH is a constant defined in wp-load.php (or at
	wp-config.php), making sure that the constant is defined its a better
	way to tell if the file is being request directly or included in
	wordpress.
*/
if (!defined('ABSPATH')) die('-1');

require (sprintf("%s/classes/MyanSmsc.php", plugin_dir_path(__FILE__)));

if(class_exists('MyanSmsc')) { 
	$myanSmsc = new MyanSmsc(__FILE__);
}
?>