<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* config.php                                      *
*                                                 *
* Copyright (C) 2008-20XX By Andreas Dahl         *
*                                                 *
* www.forum.sourceserver.info                     *
* www.hsfighter.net                               *
*                                                 *
**************************************************/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

For support and installation notes visit:
EN: http://forums.alliedmods.net/showthread.php?t=60174
DE: http://www.sourceserver.info/viewtopic.php?f=48&t=451
*/

// --> Begin Settings ----------------------------------------------------------

// Webadmin MySQL Settings:
$servername = "localhost";       // DB hostname
$dbport     = "3306";            // DB port (Default: 3306)
$dbusername = "root";                // DB username
$dbpassword = "";                // DB password
$dbname     = "smadmin";      			 // DB name
$table      = "smwa";            // Tableprefix interface tables.
$smtable    = "sm";              // Tableprefix sourcemod "SQL Admin" tables.

// Sourcebans MySQL Settings (Optional):
$sbservername = "localhost";     // Sourcebans DB hostname
$sbdbport     = "3307";          // Sourcebans DB port (Default: 3306)
$sbdbusername = "root";              // Sourcebans DB username
$sbdbpassword = "";              // Sourcebans DB password
$sbdbname     = "bans";    			 // Sourcebans DB name
$sbtable      = "sb";            // Sourcebans Tableprefix

// --> FTP Settings removed from config.php! <--

// --> End Settings ------------------------------------------------------------
?>
