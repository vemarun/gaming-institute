<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*					                              *
* serverstatus.inc.php                            *
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
  class HLServerAbfrage {

      var $server_address;
      var $ip;
      var $port;
      var $fp;
      var $challenge;
      var $serverinfo;
      var $playerlist;
      var $cvarlist;


      var $A2S_SERVERQUERY_GETCHALLENGE = "\x57"; // challenge
      var $A2S_INFO = "TSource Engine Query\x00"; // info
      var $A2S_PLAYER = "\x55"; // player
      var $A2S_RULES = "\x56"; // rules
      var $A2S_PING = "\x69"; // ping

      // IP und PORT trennen
      function hlserver($server_address = 0) {
            list($this->ip, $this->port) = explode(":", $server_address);
      }

      // Verbindung zum Server aufbauen
      function connect() {
        $this->fp = fsockopen("udp://".$this->ip, $this->port, $errno, $errstr, 3);
        if (!$this->fp) {
          $Fehler = 1;
        }
      }

       // String-Command" senden
      function send_strcmd($strcmd) {
      

     //Try
	  //{
      fwrite($this->fp, sprintf('%c%c%c%c%s%c', 0xFF, 0xFF, 0xFF, 0xFF, $strcmd, 0x00));
      /*}
	  Catch{
	  ech error
	  }*/
	  
	  
	  }

      // 1 Byte vom Server holen
      function get_byte() {
        return ord(fread($this->fp, 1));
      }

      // 1 Zeichen (1 Byte) vom Server holen
      function get_char() {
        return fread($this->fp, 1);
      }

      // einen int16-Wert (2 Bytes) vom Server holen
      function get_int16() {
        $unpacked = unpack('sint', fread($this->fp, 2));
        return $unpacked[int];
      }

      // einen int32-Wert (4 Bytes) vom Server holen
      function get_int32() {
        $unpacked = unpack('iint', fread($this->fp, 4));
            return $unpacked[int];
      }

      // einen float32-Wert (4 Bytes) vom Server holen
      function get_float32() {
        $unpacked = unpack('fint', fread($this->fp, 4));
        return $unpacked[int];
      }

      // einen String vom Server holen
      function get_string() {
        while(($char = fread($this->fp, 1)) != chr(0)) {
          $str .= $char;
        }
        return $str;
      }
      // 4 bytes von der challenge holen

      function get_4()
      {
       return fread($this->fp, 4);
      }

      // Challenger vom Server holen
      function challenge() {
        $this->connect();
        $this->send_strcmd($this->A2S_SERVERQUERY_GETCHALLENGE);
        $this->get_int32();
        $this->get_byte();
        $challenge = $this->get_4();
        return $challenge;
        fclose($this->fp);
      }
      
      // Ping vom Server holen
      function ping() {
        $this->connect();
        $this->send_strcmd($this -> A2S_PING);
        $this->get_int32();
        $this->get_byte();
        $this->serverping = $this->get_string();
        fclose($this->fp);
        return $this->serverping;
      }

      // Infos vom Server holen
      function infos() {
        $this->connect();
        $this->send_strcmd($this -> A2S_INFO);
        $this->get_int32();
        $this->get_byte();

        $this->serverinfo["network_version"] = $this->get_byte();
        $this->serverinfo["name"] = $this->get_string();
        $this->serverinfo["map"] = $this->get_string();
        $this->serverinfo["directory"] = $this->get_string();
        $this->serverinfo["discription"]= $this->get_string();
        $this->serverinfo["steam_id"] = $this->get_int16();
        $this->serverinfo["players"] = $this->get_byte();
        $this->serverinfo["maxplayers"] = $this->get_byte();
        $this->serverinfo["bot"] = $this->get_byte();
        $this->serverinfo["dedicated"] = $this->get_char();
        $this->serverinfo["os"] = $this->get_char();
        $this->serverinfo["password"] = $this->get_byte();
        $this->serverinfo["secure"] = $this->get_byte();
        $this->serverinfo["version"] = $this->get_string();
        fclose($this->fp);
        return $this->serverinfo;
      }

      // Player-Liste vom Server holen
      function players() {
        $challenge = $this->challenge();
        $this->connect();
        $this->send_strcmd($this->A2S_PLAYER.$challenge);
        $this->get_int32();
        $this->get_byte();

        $playercount = $this->get_byte();

        for($i=1; $i <= $playercount; $i++) {
          $this->playerlist[$i]["index"] = $this->get_byte();
          $this->playerlist[$i]["name"] = $this->get_string();
          $this->playerlist[$i]["frags"] = $this->get_int32();
          $this->playerlist[$i]["time"] = date('H:i:s', round($this->get_float32(), 0)+82800);
        }
        fclose($this->fp);
        return $this->playerlist;
      }

      // Rules-Liste (CVARs) vom Server holen
      function cvars() {
         $challenge = $this->challenge();
         $this->connect();
         $this->send_strcmd($this->A2S_RULES.$challenge);
         $this->get_int32();
         $this->get_byte();

          $cvarcount = $this->get_int16();

          for($i=1; $i <= $cvarcount; $i++) {
            $this->cvarlist[$this->get_string()] = $this->get_string();
          }
          fclose($this->fp);
          return $this->cvarlist;
      }


}



?>
