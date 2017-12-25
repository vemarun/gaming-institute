-=[ Sourcemod Webadmin Script ]=-

Release: 2.1 Final
Release Date: juli 2010

SourceMod Webadmin Script 2007 - 20XX by Andreas Dahl alias HSFighter. All rights reserved.

******************************************************************************
* 				                                             *
* News und Download der aktuellsten Version auf: 			     *
* http://www.forum.sourceserver.info/viewtopic.php?f=48&t=451  		     *
*                               			                     *
******************************************************************************

----- Bitte Lesen -----

Es wird keine Haftung für evt. Schäden übernommen
die durch das benutzten dieses Skriptes entstehen.
Wenn ihr Bugs findet und melden wollt, dann bitte in der 
Sourcemod Sektion of http://www.forum.sourceserver.info

---- Was ist Sourcemod Webadmin? ----

Sourcemod Webadmin ist ein PHP-Script um Sourcemod Plugins mit MySQL Support zu verwalten.

----- Systemanforderungen -----

Web server:             Web server Apache, IIS 
Middleware:             PHP4.3 oder höher
Database, core system:  MySQL
Vorrausetzug:		Sourcemod >>> v1.3 <<< oder höher!

----- Installation -----

Wenn bereits das MySQL-Admin Plugin mit einer Datenbank verwendet wird,
bitte bei Schritt 4 anfangen !!!

1.  Erstelle eine neue Datenbank. z.B. admin 
2.  Importiere "create_admins" aus dem Verzeichniss "addons/sourcemod/configs/sql-init-scripts/mysql" in die Datenbank.
3.  Editiere die "databases.cfg" in dem "addons/sourcemod/configs" Verzeichnis auf dem Gameserver.
4.  Endpacke die .rar Datei und lade alle Dateien auf den Webserver hoch.
5.  Öffne die Datei "../inc/config.php" und trage die Einstellungen für deine MySQL Datenbank ein.
6.  Starte die "../setup.php" von deinem Webbrowser aus.
7.  Setze Chmod 777 für das "../temp" Verzeichnis.
8.  Setze Chmod 777 für das "../inc/pics/games" Verzeichnis.
9.  Lösche die "../setup.php" auf deinem Web-Server!!!
10. Öffne das Webinterface über "../index.php" in deinem Webbrowser
11. Login Daten...
    Username: admin
    Passwort: 123456

12. Fertig...!

----- Update zu Ver. 2.1 Final -----

1. Lösche alle Dateien von dem Sourcemod Webadmin auf dem webserver!
2. Endpacke die .rar Datei und lade alle Dateien auf den Webserver hoch.
3. Öffne die Datei "../inc/config.php" und trage die Einstellungen für deine MySQL Datenbank ein.
4. Starte die "../setup.php" von deinem Webbrowser aus.
5. Setze Chmod 777 für das "../temp" Verzeichnis
6. Setze Chmod 777 für das "../inc/pics/games" Verzeichnis.
7. Lösche die "../setup.php" auf deinem Web-Server!!!

8. Fertig...!


---- SQL-Admins Gruppen für verschiedene Server ----
===> Diese Funktion ist optional <===

Um den Mutltiserversupport für SQL-Admins zu nutzen, wird dieses Plugin benötigt
"SQL Admin Plugins MultiServ Edition" >>> http://forums.alliedmods.net/showthread.php?p=730330 <<<

Die benötigten SQL-Tabellen werden automatisch bei der installation oder update auf Sourcemod-Webadmin v2.0 or höer erstellt!
Es müss das "admin-sql-prefetch.smx" oder "admin-sql-threaded.smx" mit dem neuen Plugin ausgetauscht werden.

Wenn ihr vorher keine SQL-Admins genutzt habt, 
dann ist die installation von dem "sql-admin-manager.smx" plugin zusätzlich notwendig.
Dieses Plugin liegt bei Sourcemod bei.


Um die Pluginfunktion im Webinterface zu nutzen müssen die 
"Servergroups" im Menü "Interface" --> "Settings" aktiviert werden.

Auchtung:
* Nur Gruppen die mit Server über "Server Groups" verbunden sind haben Adminrechte auf dem Server
* Alle anderen "gruppen" und "clients" haben keine Adminrechte auf dem Server.
* Servergroups werden beim exportieren der localen Admindateien mit berücksichtigt.

===> End optional <===

Danke an "MistaGee" für dieses excillente Plugin


---- Credits ----

Project Manager, Hauptentwickler:

    * HSFighter 

Sprachübersetzungen:
    
    * HSFighter (*Bad* English) 
    * UnFixed (*Better* English)
    * HSFighter (German)
    * HO!NO! (French)
    * elpouletorange (French) 
    * Fighter777 (French) 
    * manix (French)
    * eric0279 (French)
    * Flyflo (French)
    * S@ndel (Russian) 

Besonderen Dank an:

    * Bailopan (Developer of Sourcemod)
    * moggiex
    * muukis
    * Nephyrin
    * MistaGee
    * -=|JFH|=-Naris
    * W][LDF][RE
    * Chrisber
    * NuX
    * DeaD_EyE
    * Isias
    * GonZo 


----- Feedback -----

Feedback, Kommentare, etc sind gerne erwünscht - Sourcemod Sektion auf:
http://www.forum.sourceserver.info (Deutscher Support)
oder
http://www.forums.alliedmods.net/showthread.php?t=60174 (Englischer Support)

Viel Spaß!
