<?php
//Version du soft
define("VERSION_AGORA","3.1.1");//toujours 3 niveaux!
define("VERSION_AGORA_PHP_MINIMUM","5.4");

//Config de base
define("IP_CONTROLES",true);
define("LIVECOUNTER_TIMEOUT",30);
define("LIVECOUNTER_REFRESH",10);
define("MESSENGER_TIMEOUT",86400);//24h
define("AGORA_PROJECT_NAME","Agora-Project");
define("AGORA_PROJECT_URL","https://www.agora-project.net");
define("AGORA_PROJECT_URL_DISPLAYED","www.agora-project.net");

//Chemins de base
if(is_file("Host.php"))	{require_once "Host.php";  Host::initHost();}
else{
	define("PATH_DATAS","DATAS/");
	if(is_dir("stock_fichiers/") && !is_dir(PATH_DATAS))	{rename("stock_fichiers/",PATH_DATAS);}
}
define("PATH_MOD_FILE",	PATH_DATAS."modFile/");
define("PATH_MOD_USER",	PATH_DATAS."modUser/");
define("PATH_MOD_CONTACT", PATH_DATAS."modContact/");
define("PATH_OBJECT_ATTACHMENT", PATH_DATAS."objectAttachment/");
define("PATH_WALLPAPER_CUSTOM", PATH_DATAS."wallpaper/");
define("PATH_WALLPAPER_DEFAULT", "app/img/wallpaper/");
define("WALLPAPER_DEFAULT_PREFIX","default@@");//ID des fonds d'écran par défaut