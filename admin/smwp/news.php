<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* news.php                                        *
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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

For support and installation notes visit:
EN: http://forums.alliedmods.net/showthread.php?t=60174
DE: http://www.sourceserver.info/viewtopic.php?f=48&t=451
*/

  $tpl->set_file("inhalt", "templates/news.tpl.htm");

  $viewtext['System'][15] = str_replace('%user%', $usercheck['name'], $viewtext['System'][15]);
  
  $tpl->set_var(array(
   "version"                  => $settings['version'],
   "welcome"                  => $viewtext['System'][15]
  ));
  
/*
// include lastRSS
include "inc/lastRSS.php";

// Create lastRSS object
$rss = new lastRSS;

// Set cache dir and cache time limit (1200 seconds)
// (don't forget to chmod cahce dir to 777 to allow writing)
$rss->cache_dir = 'temp';
$rss->cache_time = 0;
$rss->cp = 'US-ASCII';
$rss->date_format = 'l';

// Try to load and parse RSS file of Slashdot.org
$rssurl = '';

if ($rs = $rss->get($rssurl)) {
   // echo '<pre>';
   // print_r($rs);
   // echo '</pre>';
    }
else {
    echo "Error: It's not possible to get $rssurl";
} */

?>
