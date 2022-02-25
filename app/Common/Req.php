<?php
/*
 * Autoloader des classes de base et des controleurs (pas des classes des modèles : chargées par le controleur!)
 */
function agoraAutoloader($className)
{
	if(is_file(Req::commonPath.$className.".php"))	{require_once Req::commonPath.$className.".php";}			//exple: "app/common/Txt.php"
	elseif(is_file(Req::modClassPath($className)))	{require_once Req::modClassPath($className);}				//exple: "app/modFile/MdlFile.php"
	else											{throw new Exception("Class '".$className."' unreachable");}//sinon : class inaccessible
}
spl_autoload_register("agoraAutoloader");


/*
 * traite les requetes entrantes
 */
class Req
{
	const commonPath="app/Common/";
	private static $_getPostParams;
	public static $curCtrl;		//exple : "offline"
	public static $curAction;	//exple : "default"

	/*
	 * Init
	 */
	function __construct()
	{
		//Fusionne GET+POST & filtre les XSS  ("onClick", "<script>"...)
		self::$_getPostParams=array_merge($_GET,$_POST);
		foreach(self::$_getPostParams as $tmpKey=>$tmpVal){
			if(is_string($tmpVal)){
				self::$_getPostParams[$tmpKey]=preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $tmpVal);
				self::$_getPostParams[$tmpKey]=preg_replace('#<script[^>]*?.*?</script>#siu', '', $tmpVal);
			}
		}
		//Classe du controleur courant & Methode de l'action courante
		self::$curCtrl=(self::isParam("ctrl")) ? self::getParam("ctrl") : "offline";
		$curCtrlClass="Ctrl".ucfirst(self::$curCtrl);
		self::$curAction=(self::isParam("action")) ? self::getParam("action") : "default";
		$curActionMethod="action".ucfirst(self::$curAction);
		//Init le temps d'execution & charge les Params + Config
		define("TPS_EXEC_BEGIN",microtime(true));
		require_once self::commonPath."params.php";
		require_once PATH_DATAS."config.inc.php";
		//Lance l'action demandée
		try{
			if(self::isInstalling()==false) {$curCtrlClass::initCtrl();}//Pas d'init pour l'install
			if(method_exists($curCtrlClass,$curActionMethod))	{$curCtrlClass::$curActionMethod();}
			else												{throw new Exception("Action '".$curActionMethod."' not found");}
		}
		//Gestion des exceptions
		catch(Exception $e){
			$this->displayExeption($e);
		}
	}

	/*
	 * Verifie si un ou plusieurs parametres GET/POST existent (meme vide!)
	 */
	public static function isParam($keys)
	{
		//init au format "array"
		$isParam=true;
		if(!is_array($keys))	{$keys=[$keys];}
		//verifie l'existance de tous les parametres spécifiés !!
		foreach($keys as $key){
			if(!isset(self::$_getPostParams[$key]))  {$isParam=false;}
		}
		return $isParam;
    }

	/*
	 * Recupere un parametre GET/POST
	 */
	public static function getParam($key, $necessary=false)
	{
		if(self::isParam($key)){
			return (is_string(self::$_getPostParams[$key]))  ?  trim(self::$_getPostParams[$key]) : self::$_getPostParams[$key];
		}
		elseif($necessary==true)	{throw new Exception("Parameter '".$key."' not specified");}
    }

	/*
	 * Path d'une classe dans module  (La 2ème partie du nom de classe contient le nom du module. exple: "MdlFileFolder" => "File")
	 */
	public static function modClassPath($className)
	{
		$majWords=preg_split("/(?=[A-Z])/",trim($className));//'MdlFileFolder' => array('','Mdl','File','Folder') => 'app/ModFile'
		if(!empty($majWords[2]))	{return "app/Mod".ucfirst($majWords[2])."/".$className.".php";}
	}

	/*
	 * Recupère le chemin du module courant
	 */
	public static function getCurModPath()
	{
		return "app/Mod".ucfirst(self::$curCtrl)."/";
	}

	/*
	 * Recupère l'URL de l'espace (exple "www.mon-espace.net/agora/index.php?ctrl=file" => "www.mon-espace.net/agora")
	 */
	public static function getSpaceUrl($httpPrefix=true)
	{
		//Note : Toutes les requêtes passent par l'"index.php" à la racine de l'appli
		$uri=str_replace("index.php","",$_SERVER["REQUEST_URI"]);//si besoin, enlève "index.php"
		if(strstr($uri,"?"))	{$uri=substr($uri,0,strrpos($uri,"?"));}//si besoin, enlève les paramètres
		if($httpPrefix==true)	{$httpPrefix=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=="on") ? "https://" : "http://";}
		else					{$httpPrefix=null;}
		return $httpPrefix.$_SERVER["HTTP_HOST"].rtrim($uri,"/");
	}

	/*
	 * Vérif si l'appli est en cour d'install
	 */
	public static function isInstalling()
	{
		return (self::$curCtrl=="offline" && stristr(self::$curAction,"install"));
	}

	/*
	 * Vérif la version de PHP
	 */
	public static function verifPhpVersion()
	{
		if(version_compare(PHP_VERSION,VERSION_AGORA_PHP_MINIMUM,"<")){
			echo "<h3>".Txt::trad("INSTALL_PhpOldVersion")."</h3><h4>PHP version required : ".VERSION_AGORA_PHP_MINIMUM."</h4><h4>PHP current version : ".PHP_VERSION."</h4>";
			exit;
		}
	}

	/*
	 * Affiche une erreur d'execution
	 */
    private function displayExeption(Exception $exception)
	{
		//Install demandée, Mais pas en cour d'install, et pas de hosting : redirige en page d'install
		if(preg_match("/dbInstall/i",$exception) && self::isInstalling()==false && !defined("HOST_DOMAINE"))
			{Ctrl::redir("?ctrl=offline&action=install&disconnect=1");}
		//Affiche le message
        echo "<h3 style='text-align:center;margin-top:50px;'><img src='app/img/important.png' style='vertical-align:middle'> internal error  :<br><br>".(defined("HOST_DOMAINE")?$exception->getMessage():$exception)."<br><br>[<a href='?ctrl=offline'>Back</a>]</h3>";
		exit;
    }
}