-=[ Sourcemod Webadmin Script ]=-

Release: 2.1 Final
Release Date: July 2010

SourceMod Webadmin Script 2007 - 20XX by Andreas Dahl alias HSFighter. All rights reserved.

*****************************************************************************************
* 				                                                       
* Download Latest Version: http://forums.alliedmods.net/showthread.php?t=60174  	
* Feedback and Suggestions: http://forums.alliedmods.net/showthread.php?t=60174         
*                               			                                			                                                                      
*****************************************************************************************

----- PLEASE READ -----

This software comes with no warranty, either expressed or implied. If you lose valuable data, sanity,
a wife or wifes, as a result of using this Software or Script, I am not to blame.

----- What is Sourcemod Webadmin? ----

The Sourcemod-Webadmin is a webinterface to manage sourcemod plugin's with MySQL Support. 

----- System Requirements -----

Web server:             Apache, IIS 
Middleware:             PHP4.3 or higher
Database, core system:  MySQL
Requirement:		SourceMOD >>> v1.3 <<< or higher.
	                  
----- How to Install -----

If you allready use the MySQL-Admin Plugin with a database,
please begin with step 4!!!

1.  Create a new mysql database. Ex:admin
2.  Import "create_admins" from "addons/sourcemod/configs/sql-init-scripts/mysql" to the database
3.  Edit "databases.cfg" in "addons/sourcemod/configs" on your gameserver.
4.  Upload all files to your web server to a folder of your choice.
5.  Open the file "../inc/config.php" and change the data to your MySQL Database.
6.  Run the "../setup.php" in your Web browser.
7.  Set the "../temp" directory to "Chmod 777".
8.  Set the "../inc/pics/games" directory to "Chmod 777".
9.  Delete the file "../setup.php" on your web server!
10.  Open the Web interface "../index.php" on your Web browser.
11. Login with...
    Username: admin
    Password: 123456

12. Ready...!

----- Update to Ver. 2.1 Final -----

1. Delete all files from the webadmin on your web server!
2. Upload all files to your web server.
3. Open the file "../inc/config.php" and change the data to your MySQL Database.
4. Run the "../setup.php" on your Web browser.
5. Set the "../temp" directory to "Chmod 777".
6. Set the "../inc/pics/games" directory to "Chmod 777".
7. Delete the file "../setup.php" on your web server!

8. Ready...!

---- SQL-Admins Groups for Different servers ----
===> This function is optional <===

To use Mutltiserversupport for SQL-Admins you need the plugin:
"SQL Admin Plugins MultiServ Edition" >>> http://forums.alliedmods.net/showthread.php?p=730330 <<<

The necessary SQL-Tables will be create if you install or update to Sourcemod-Webadmin v2.0 or higher!
You need to replace the "admin-sql-prefetch.smx" or "admin-sql-threaded.smx" plugin.

If you doun't use SQL-Admins before, you need to install the "sql-admin-manager.smx" plugin to.
This plugin is include with sourcemod packet.

To aktivate the plugin-options in the webinterface
enable the "Servergroups" in the "Interface" --> "Settings" menue.

Beware:
* Groups only exist on the servers they're linked to "Server Groups" have admin-access on the server.
* All other "groups" and "clients" have no admin access on the gameserver!
* Servergroups will be considered with export an local admin file!

===> End optional <===

Thank you to "MistaGee" for this excillent Plugin


---- Credits ----

Project Manager, Lead Developer:

    * HSFighter 

Languagefile Translation:

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

Special Thanks to:

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

Any feedback, comments, etc would be welcomed:
http://www.forum.sourceserver.info (German support)
or
http://www.forums.alliedmods.net/showthread.php?t=60174 (English support)

Enjoy :)