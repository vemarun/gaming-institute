-=[ Sourcemod Webadmin Script ]=-

Release: 2.1 Final
Release Date: July 2010

SourceMod Webadmin Script 2007 - 20XX by Andreas Dahl alias HSFighter. All rights reserved.

*****************************************************************************************
* 				                                                       
* Télécharger la dernière version : http://forums.alliedmods.net/showthread.php?t=60174 
* Commentaires et suggestions : http://forums.alliedmods.net/showthread.php?t=60174     
*                               			                                       		
*****************************************************************************************

----- VEUILLEZ LIRE CECI !!! -----

Ce logiciel est fourni sans aucune garantie, explicite ou implicite. Si vous perdez des données à la suite de l'utilisation de ce logiciel ou du script, je ne suis pas à blâmer.

----- Qu'est-ce-que c'est Sourcemod Webadmin? ----

Sourcemod-Webadmin est une interface web pour gérer sourcemod avec le support MySQL.

-----  Configuration requise  -----

Serveur Web:             Apache 2 ou IIS 
php :             PHP4.3 ou supérieur
Base de donnée :  MySQL 4.1 ou supérieur
Requis:		SourceMOD >>> v1.2 <<< ou supérieur.
	                  
----- Comment l'installer ? -----

Si vous avez déjà utiliser le Plugin MySQL-Admin avec une base de données,
veuillez passez à l'étape 4 !!!

1.  Créée une nouvelle base de donnée. Exemple : Webadmin
2.  Importer le fichier "create_admins" dans la base de donnée qui est dans le répertoire "addons/sourcemod/configs/sql-init-scripts/mysql".
3.  Editez le fichier "databases.cfg" qui est dans le répertoire "addons/sourcemod/configs".
4.  Uploader TOUT les dossiers et fichier dans le répertoire de votre choix.
5.  Ouvrez et éditer le fichier "../inc/config.php" et modifiez les valeurs pour la base de donnée.
6.  Ouvrez votre navigateur et exécuter "../setup.php" (Ex : http://localhost/Webadmin/setup.php) (Remplacer localhost par votre IP ou votre nom de domaine).
7.  Créez le dossier "temp" et mettez le en chmod 777 pour Linux ou si vous êtes sous Windows, faite ceci : 
8.  Créez le dossier "../inc/pics/games" et mettez le en chmod 777 pour Linux ou si vous êtes sous Windows, faite ceci : 
Décochez la case "Utiliser la partage simple de fichier (Recommandé)" dans les "Options des dossiers" puis faite un clique droit sur le dossier "../temp" puis choisissez "Propriétés" >> et allez dans l'onglet "Sécurité", choisissez votre nom de session, cochez la case "Contrôle Total" et terminer en cliquant sur "Appliquer" puis sur "OK"
.
9.  Supprimer le fichier "../setup.php" une fois l'installation terminée !
10.  Ouvrez votre navigateur en saissant  "http://localhost/Webadmin//index.php" (Remplacer localhost par votre IP ou votre nom de domaine).
11. Identifiez vous...
    Pseudo: admin
    mot de passe: 123456

12. Vous êtes prêt à gérer...!

----- Mise à jour vers la version 2.1 Final -----

1. Supprimer tout les fichiers présent sur votre serveur web !
2. Uploader TOUT les dossiers et fichier.
3. Ouvrez et éditer le fichier "../inc/config.php" et modifiez les valeurs pour la base de donnée.
4. Ouvrez votre navigateur et exécuter "../setup.php" (Ex : http://localhost/Webadmin/setup.php) (Remplacer localhost par votre IP ou votre nom de domaine).
5. Mettez le dossier "../temp" en chmod 777 ou si vous êtes sous Windows, faite ceci : 
6. Mettez le dossier "../inc/pics/games" en chmod 777 ou si vous êtes sous Windows, faite ceci : 
Vous devez décochez la case "Utiliser la partage simple de fichier (Recommandé)" dans les "Options des dossiers" puis faite un clique droit sur le dossier "../temp" puis choisissez "Propriétés" >> et allez dans l'onglet "Sécurité", choisissez votre nom de session, cochez la case "Contrôle Total" et terminer en cliquant sur "Appliquer" puis sur "OK"
.
7. Supprimer le fichier "../setup.php" une fois l'installation terminée !

8. Vous êtes prêt à gérer...!


---- SQL-Admins for Multiserver-Support ----
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

Gestionnaire du projet et responsable du développement:

    * HSFighter 

Traduction des fichiers de langues :

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

Remerciement à :

    * Bailopan (Développeur de Sourcemod)
    * moggiex
    * muukis
    * Nephyrin
    * MistaGee
    * W][LDF][RE
    * Chrisber
    * NuX
    * DeaD_EyE
    * Isias
    * GonZo 

----- Feedback -----

Tous les commentaires, suggestios, etc... seront les bienvenues:
http://www.forum.sourceserver.info (German support)
ou
http://www.forums.alliedmods.net/showthread.php?t=60174 (English support)

Apprécier :)