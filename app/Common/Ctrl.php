<?php
/*
 * Controleur principal
 */
abstract class Ctrl
{
	//Propriétés de base
	const moduleName=null;
	public static $moduleOptions=array();
	public static $agora, $curUser, $curSpace;
	public static $isMainPage=false;				//Page principale avec barre de menu, messenger, etc ? ..False pour les iframe
	public static $lightboxClose=false;				//Validation de formulaire : affiche une page vide avec des "JsTriggers" (notifs, parent.reload, etc) et ferme la page
	public static $isUserConnection=false;			//idem : connexion de l'user
	public static $curContainer=null;				//idem : conteneur courant (dossier, sujet, etc)
	public static $curTimezone=null;				//Timezone courante
	public static $curUserCurSpaceGroups=array();	//Groupes affectés à l'user courant et l'espace courant
	protected static $initCtrlFull=true;			//Initialisation complete du controleur (connexion de l'user, selection d'espace, etc)
	protected static $folderObjectType=null;		//Module avec une arborescence
	protected static $cachedObjects=array();		//Objets mis en cache !
	//Messages de Notifications & Triggers Javascript (cf. Vues)
	private static $_msgNotif=array();
	private static $_jsTriggers=array();

	/*
	 * Initialise le controleur principal : session, parametrages, connexion de l'user, selection de l'espace, etc
	 */
	public static function initCtrl()
	{
		////	Controle la mise à jour et init le parametrage
		DbUpdate::lauchUpdate();
		self::$agora=self::getObj("agora");

		////	Gère la session
		if(defined("db_name"))	{session_name("Agora_".db_name);}//pour l'accès à plusieurs espaces d'un même serveur avec le même browser
		session_cache_limiter("nocache");
		session_start();
		if(Req::isParam("disconnect"))	{self::initSession();}//Déconnexion

		////	Init le fuseau horaire. Infos stats ?
		self::$curTimezone=array_search(self::$agora->timezone,Tool::$tabTimezones);
		if(empty(self::$curTimezone))	{self::$curTimezone="Europe/Paris";}
		date_default_timezone_set(self::$curTimezone);

		////	Init l'user et l'espace courant (après init de session). Recup au besoin l'id du dossier courant
		$userId=(!empty($_SESSION["_idUser"])) ? $_SESSION["_idUser"] : "isGuest";
		$spaceId=(!empty($_SESSION["_idSpace"])) ? $_SESSION["_idSpace"] : null;
		self::$curUser=self::getObj("user",$userId);
		self::$curSpace=self::getObj("space",$spaceId);

		////	Init complète du controleur : connexion de l'user/invité, selection d'espace, etc
		if(static::$initCtrlFull==true)
		{
			////	Connection d'un user? Selection d'un espace? Init les stats?
			self::userConnection();
			if(defined("HOST_DOMAINE"))  {Host::connectStats();}//tjs juste après "userConnection()"!!
			self::curSpaceSelection();
			////	Chargement des trads et des "locales"
			Txt::loadTrads();
			////	Affichage des objets : normal/autor/all
			if(empty($_SESSION["displayObjects"]))	{$_SESSION["displayObjects"]="normal";}
			elseif(Req::isParam("displayObjects")){
				if(Req::getParam("displayObjects")=="all" && self::$curUser->isAdminGeneral())	{$_SESSION["displayObjects"]="all";}
				elseif(Req::getParam("displayObjects")=="autor" && self::$curUser->isUser())	{$_SESSION["displayObjects"]="autor";}	
				else																			{$_SESSION["displayObjects"]="normal";}	
			}
			////	Affichage des utilisateurs : space/all
			if(empty($_SESSION["displayUsers"]))	{$_SESSION["displayUsers"]="space";}
			////	Groupes de l'utilisateur courant sur l'espace courant														(ex $_SESSION["espace"]["groupes_user_courant"])
			self::$curUserCurSpaceGroups=MdlUserGroup::getGroups(self::$curSpace,self::$curUser);

			////	Objet à charger et à controler : dossier courant, objet en accès direct, objet à éditer, etc (toujours après chargement des trads!)
			if(Req::isParam("targetObjId"))				{$targetObj=self::getTargetObj();}//"targetObjId" spécifié (dossier courant ou autre)
			elseif(static::$folderObjectType!==null)	{$targetObj=self::getTargetObj(static::$folderObjectType."-1");}//Dossier racine par défaut
			//Charge le dossier courant + controle d'accès?
			if(isset($targetObj) && is_object($targetObj)){
				if($targetObj::isContainer())		{self::$curContainer=$targetObj;}
				if($targetObj->readRight()==false){
					if(static::$isMainPage==true)	{self::redir("?ctrl=".Req::$curCtrl);}//redirige vers controleur principal
					else							{Ctrl::noAccessExit();}//message d'erreur
				}
			}
		}
	}

	/*
	 * Init la session
	 */
	public static function initSession($session_destroy=true)
	{
		$_SESSION=array();
		if($session_destroy)	{session_destroy();}
		setcookie("AGORAP_LOG","",time()-(86400*365*10));
		setcookie("AGORAP_PASS","",time()-(86400*365*10));
	}

	/*
	 * Valide au besoin la connexion d'un utilisateur
	 */
	public static function userConnection()
	{
		$connectViaForm=(Req::isParam(["connectLogin","connectPassword"])) ? true : false;
		$connectViaCookie=(!empty($_COOKIE["AGORAP_LOG"]) && !empty($_COOKIE["AGORAP_PASS"]) && Req::isParam("disconnect")==false) ? true : false;
		if(self::$curUser->isUser()==false && ($connectViaForm==true || $connectViaCookie==true))
		{
			////	CONTROLES DE CONNEXION
			// Connexion demandé ou auto ?
			if($connectViaForm==true)		{$login=Req::getParam("connectLogin");  $passwordSha1=MdlUser::passwordSha1(Req::getParam("connectPassword"));}
			elseif($connectViaCookie==true)	{$login=$_COOKIE["AGORAP_LOG"];			$passwordSha1=$_COOKIE["AGORAP_PASS"];}
			// Identification + recup des infos
			$sqlPasswordSha1="AND password=".Db::format($passwordSha1);
			if(defined("HOST_DOMAINE"))  {$sqlPasswordSha1=Host::sqlPassword(Req::getParam("connectPassword"),$sqlPasswordSha1);}//SHORT.C?
			$tmpUser=Db::getLine("SELECT * FROM ap_user WHERE login=".Db::format($login)." ".$sqlPasswordSha1);
			// Aucun user correspondant => tente une identification ldap pour une crea d'utilisateur a la volee..
			if(empty($tmpUser) && $connectViaForm==true)  {$tmpUser=MdlUser::ldapConnectCreateUser(Req::getParam("connectLogin"),Req::getParam("connectPassword"));}
			// Erreur de connexion?
			if(empty($tmpUser)){
				self::addNotif("MSG_NOTIF_identification");
				self::redir("?disconnect=1");
			}
			////	VALIDATION DE L'UTILISATEUR
			//Compte restreint à une ou plusieurs adresses ip ?
			$controleIpOk = Db::getVal("SELECT count(*) FROM ap_user WHERE _id='".(int)$tmpUser["_id"]."' AND (ipControlAdresses is null OR ipControlAdresses LIKE '%@".$_SERVER["REMOTE_ADDR"]."@%')");
			if(IP_CONTROLES==true && $controleIpOk==0){
				self::addNotif("MSG_NOTIF_adresseip");
				self::redir("?disconnect=1");
			}
			//Compte en cours d'utilisation sur un autre poste? (avec une autre ip?)
			$autreIpConnected=Db::getVal("SELECT count(*) FROM ap_userLivecouter WHERE _idUser='".$tmpUser["_id"]."' AND date > '".(time()-40)."' AND ipAdress NOT LIKE '".$_SERVER["REMOTE_ADDR"]."'");
			if($autreIpConnected>0){
				self::addNotif("MSG_NOTIF_dejapresent");
				self::redir("?disconnect=1");
			}
			// Connexion validé (reinitialise la session, charge l'utilisateur courant, etc)
			$_SESSION=array();
			$_SESSION["_idUser"]=(int)$tmpUser["_id"];
			Db::query("UPDATE ap_user SET lastConnection='".time()."', previousConnection=".Db::format($tmpUser["lastConnection"])." WHERE _id='".(int)$tmpUser["_id"]."'");
			self::$curUser=self::getObj("user",$tmpUser["_id"]);
			self::$isUserConnection=true;
			self::addLog("connexion");
			// Récupère les préférences
			foreach(Db::getTab("select * from ap_userPreference where _idUser=".self::$curUser->_id) as $tmpPref)
				{$_SESSION["pref"][$tmpPref["keyVal"]]=$tmpPref["value"];}
			// Enregistre login & password pour une connexion auto(2A)
			if(Req::isParam("rememberMe")){
				setcookie("AGORAP_LOG", $login, time()+63072000);
				setcookie("AGORAP_PASS", $passwordSha1, time()+63072000);
			}
		}
	}

	/*
	 * Selection de l'espace courant
	 */
	public static function curSpaceSelection()
	{
		//User venant d'être identifié (connexion form/auto)  /  Espace demandé par un guest ou user déjà connecté (sauf si "notifSpaceAccess" & user pas identifié!)  /  Espace déjà sélectionné + ctrl "Offline"
		if(self::$isUserConnection==true || (Req::isParam("_idSpaceAccess") && Req::isParam("notifSpaceAccess")==false) || (!empty(self::$curSpace->_id) && static::moduleName=="offline"))
		{
			////	Liste des espaces de l'user.. et redirection si aucun espace dispo
			$idSpaceSelected=null;
			$spacesOfCurUser=self::$curUser->getSpaces();
			if(empty($spacesOfCurUser)){
				self::addNotif("MSG_NOTIF_acces_aucun_espace");
				self::redir("?disconnect=1");
			}
			////	Espace demandé par un guest ou user connecté
			elseif(Req::isParam("_idSpaceAccess")){
				foreach($spacesOfCurUser as $objSpace){
					if($objSpace->_id==Req::getParam("_idSpaceAccess") && (self::$curUser->isUser() || empty($objSpace->password) || $objSpace->password==Req::getParam("password")))    {$idSpaceSelected=$objSpace->_id;  break;}
				}
			}
			////	Espace de connexion de l'utilisateur OU espace par defaut
			elseif(self::$curUser->isUser())
			{
				if(!empty(self::$curUser->connectionSpace)){
					foreach($spacesOfCurUser as $objSpace){
						if($objSpace->_id==self::$curUser->connectionSpace)    {$idSpaceSelected=$objSpace->_id;  break;}
					}
				}
				if(empty($idSpaceSelected)){
					$firstSpace=reset($spacesOfCurUser);
					$idSpaceSelected=$firstSpace->_id;
				}
			}
			//Chargement de l'espace & Redirection
			if(!empty($idSpaceSelected)){
				$_SESSION["_idSpace"]=$idSpaceSelected;
				$spaceModules=self::getObj("space",$idSpaceSelected)->moduleList();
				if(Req::isParam("targetObjUrl") && self::$curUser->isUser())	{self::redir(Req::getParam("targetObjUrl"));}//Redir vers le controleur/objet demandé (notif)
				if(!empty($spaceModules))										{self::redir("?ctrl=".key($spaceModules));}//Redir vers le premier module de l'espace
				else															{self::addNotif("MSG_NOTIF_acces_aucun_espace");   self::redir("?disconnect=1");}//Aucun module dans l'espace..
			}
			//User identifié : Aucun espace dispo..
			elseif(self::$curUser->isUser()){
				self::addNotif("MSG_NOTIF_acces_aucun_espace");
				self::redir("?disconnect=1");
			}
		}
		//Sortie de l'espace si aucun espace sélectionné & controleur interne sélectionné..
		elseif(empty(self::$curSpace->_id) && static::moduleName!="offline")    {self::redir("?disconnect=1");}
	}

	/*
	 * Recupère un objet, déjà en cache?
	 */
	public static function getObj($objectType, $objIdOrValues=null, $updateCachedObj=false)
	{
		//Objet inexistant / existant (recup/update en cache?)
		$MdlObject="Mdl".ucfirst($objectType);
		if(empty($objIdOrValues))	{return new $MdlObject();}
		else{
			$objId=(!empty($objIdOrValues["_id"])) ? $objIdOrValues["_id"] : (int)$objIdOrValues;
			$objectCachedKey=$MdlObject::objectType."-".$objId;
			//Ajoute/Update l'objet en cache?
			if(!isset(self::$cachedObjects[$objectCachedKey]) || $updateCachedObj==true)	{self::$cachedObjects[$objectCachedKey]=new $MdlObject($objIdOrValues);}
			//Retourne l'objet en cache
			return self::$cachedObjects[$objectCachedKey];
		}
	}

	/*
	 * Recupère un objet (via "targetObjId" envoyé via Get/Post?). Exple "fileFolder-19" ou "fileFolder" pour un nouvel objet
	 * Les "targetObjId" ont un controle d'accès automatique via "initCtrl()" !
	 */
	public static function getTargetObj($targetObjId=null)
	{
		//Aucun $targetObjId en paramètre et "targetObjId" spécifié par Get/Post
		if($targetObjId==null && Req::isParam("targetObjId"))	{$targetObjId=Req::getParam("targetObjId");}
		//renvoie l'objet ciblé
		if(!empty($targetObjId))
		{
			//Nouvel objet / Objet existant
			$targetObjId=explode("-",$targetObjId);
			$targetObj=(empty($targetObjId[1]))  ?  self::getObj($targetObjId[0])  :  self::getObj($targetObjId[0],$targetObjId[1]);
			//Ajoute "_idContainer" pour le controle d'accès d'un nouvel objet (cf "actionBiduleEdit()")
			if(Req::isParam("_idContainer") && empty($targetObj->_id) && empty($targetObj->_idContainer))  {$targetObj->_idContainer=Req::getParam("_idContainer");}
			//renvoie l'objet
			return $targetObj;
		}
	}

	/*
	 * Recupère les objets selectionnés et envoyés via GET/POST. Exple: $_GET["targetObjects[fileFolder]"]="2-4-7"						(ex "SelectedElemsArray()")
	 */
	public static function getTargetObjects($objectType=null)
	{
		$returnObjects=array();
		if(Req::isParam("targetObjects") && is_array(Req::getParam("targetObjects")))
		{
			//Parcourt tous les objets ciblés
			foreach(Req::getParam("targetObjects") as $tmpObjectType=>$tmpObjectIds)
			{
				//Ajoute tous les types d'objets / un type en particulier
				if($objectType==null || $tmpObjectType==$objectType)
				{
					//Ajoute les objets s'ils sont accessibles
					foreach(explode("-",$tmpObjectIds) as $tmpObjectId){
						$tmpObject=self::getObj($tmpObjectType, $tmpObjectId);
						if($tmpObject->readRight())  {$returnObjects[]=$tmpObject;}
					}
				}
			}
		}
		//Retourne les objets
		return $returnObjects;
	}

	/*
	 * Récupère une préférence  (tri des résultats/type d'affichage/etc)											(ex "pref_user()")
	 * Passé en parametre GET/POST ? Enregistre en BDD ?
	 */
	public static function prefUser($prefDbKey, $prefParamKey=null, $prefExcludeVal=null, $getEmptyValue=false)
	{
		//Clé identique en BDD et en GET-POST ?
		if(empty($prefParamKey))	{$prefParamKey=$prefDbKey;}
		//Préférence passé en Get/Post ?
		if(Req::isParam($prefParamKey))									{$prefParamVal=Req::getParam($prefParamKey);}
		elseif($getEmptyValue==true && Req::isParam("formValidate"))	{$prefParamVal="";}//Enregistre une valeur vide? (exple: checkbox non cochée dans un formulaire)
		//Enregistre si besoin la préférence  ("isset" pour aussi enregistrer les valeurs vides) 
		if(isset($prefParamVal) && $prefParamVal!==$prefExcludeVal)
		{
			//Formate la valeur
			if(is_array($prefParamVal))  {$prefParamVal=Txt::tab2txt($prefParamVal);}
			//User : enregistre en Bdd
			if(self::$curUser->isUser()){
				Db::query("DELETE FROM ap_userPreference WHERE _idUser=".self::$curUser->_id." AND keyVal=".Db::format($prefDbKey));
				Db::query("INSERT INTO ap_userPreference SET _idUser=".self::$curUser->_id.", keyVal=".Db::format($prefDbKey).", value=".Db::format($prefParamVal));
			}
			//Enregistre en session
			$_SESSION["pref"][$prefDbKey]=$prefParamVal;
		}
		//retourne la preference
		if(isset($_SESSION["pref"][$prefDbKey]))	{return $_SESSION["pref"][$prefDbKey];}
	}

	/*
	 * Recupère le header menu de la page
	 */
	protected static function getHeaderMenu()
	{
		//Admin de l'espace / lise des espaces
		$vDatas["isAdminCurSpace"]=self::$curUser->isAdminCurSpace();
		$vDatas["menuSpaces"]=Tool::arrayUnsetVal(Ctrl::$curSpace, Ctrl::$curUser->getSpaces());//enlève l'espace courant au menu des espaces
		$vDatas["displayMenuSpaces"]=(!empty($vDatas["menuSpaces"]) || $vDatas["isAdminCurSpace"]==true);
		//Espace Disk / Display (normal/autor/admin) / Couleur du messenger / nom du module courant?
		$vDatas["diskSpacePercent"]=ceil((File::datasFolderSize()/limite_espace_disque)*100);
		$vDatas["diskSpaceLevel"]=ceil($vDatas["diskSpacePercent"]/33);
		$vDatas["displayObjects"]=array("normal","autor");
		if($vDatas["isAdminCurSpace"]==true)  {$vDatas["displayObjects"][]="all";}
		$vDatas["messengerFormMessageColor"]=(!empty($_SESSION["messengerMessageColor"])) ? $_SESSION["messengerMessageColor"] : "#222";
		$vDatas["labelCurModule"]=(self::$agora->moduleLabelDisplay=="page" && Txt::isTrad(strtoupper(static::moduleName)."_headerModuleName")) ? Txt::trad(strtoupper(static::moduleName)."_headerModuleName") : null;
		//Plugin "shortcuts" de chaque module
		$vDatas["pluginsShortcut"]=array();
		$pluginParams=array("type"=>"shortcut");
		foreach(self::$curSpace->moduleList() as $tmpModule){
			if(method_exists($tmpModule["ctrl"],"plugin"))	{$vDatas["pluginsShortcut"]=array_merge($vDatas["pluginsShortcut"],$tmpModule["ctrl"]::plugin($pluginParams));}
		}
		//Liste des modules (Url, Description, Libellé, Class de l'icone)
		$vDatas["moduleList"]=self::$curSpace->moduleList();
		foreach($vDatas["moduleList"] as $moduleKey=>$tmpModule){
			if(preg_match("/(agora|space|log)/i",static::moduleName))	{$tmpModule["iconClass"]="vModulesMenuIconMask";}
			elseif($tmpModule["moduleName"]==static::moduleName)		{$tmpModule["iconClass"]="vModulesMenuIconSelect";}
			else														{$tmpModule["iconClass"]=null;}
			$vDatas["moduleList"][$moduleKey]=$tmpModule;
		}
		//retourne la vue
		return self::getVue(Req::commonPath."VueHeaderMenu.php",$vDatas);
	}

	/*
     * Génère une vue à partir d'un fichier et de parametres
     */
	public static function getVue($filePath, $datas=array())
	{
		if(file_exists($filePath))
		{
			//Démarre la temporisation de sortie + Rend les $datas accessibles à la vue
			ob_start();
			extract($datas);
			//Inclut le fichier vue + renvoie du tampon de sortie
			require $filePath;
			return ob_get_clean();
		}
		else{throw new Exception("File '".$filePath."' unreachable");}
	}

	/*
	 * Affiche une page complete (ensemble de vues)
	 */
	protected static function displayPage($fileMainVue=null, $vDatasMainVue=array())
	{
		//Init
		$vDatas["mainContent"]=$vDatas["headerMenu"]=null;
		////	CORPS DE LA PAGE (sauf si validation de formulaire : "lightboxClose")
		if($fileMainVue!=null){
			$pathVue=(strstr($fileMainVue,Req::commonPath)==false) ? Req::getCurModPath() : null;//"app/Common/" déjà précisé?
			$vDatas["mainContent"]=self::getVue($pathVue.$fileMainVue, $vDatasMainVue);
		}
		////	Page principale : Wallpaper & headerMenu(?) & logo footer
		if(static::$isMainPage==true){
			if(!empty(self::$curSpace->wallpaper))	{$vDatas["pathWallpaper"]=CtrlMisc::pathWallpaper(self::$curSpace->wallpaper);}
			elseif(!empty(self::$agora->wallpaper))	{$vDatas["pathWallpaper"]=CtrlMisc::pathWallpaper(self::$agora->wallpaper);}
			else									{$vDatas["pathWallpaper"]=CtrlMisc::pathWallpaper();}
			if(static::moduleName!="offline")	{$vDatas["headerMenu"]=self::getHeaderMenu();}
			$vDatas["pathLogoUrl"]=(!empty(Ctrl::$agora->logoUrl)) ? Ctrl::$agora->logoUrl : AGORA_PROJECT_URL;
			$vDatas["pathLogoTitle"]=AGORA_PROJECT_URL_DISPLAYED."<br>".Txt::trad("FOOTER_page_generee")." ".round((microtime(true)-TPS_EXEC_BEGIN),3)." sec.";
		}
		////	Affiche le résultat final
		if(Req::isParam("msgNotif")){
			foreach(Req::getParam("msgNotif") as $message)   {self::addNotif($message);}//Ajoute des notifs passées en Get/Post ?
		}
		$vDatas["msgNotif"]=self::$_msgNotif;
		$vDatas["jsTriggers"]=self::$_jsTriggers;
		$vDatas["skinCss"]=(!empty(self::$agora->skin) && self::$agora->skin=="black")  ?  "black"  :  "white";
		echo self::getVue(Req::commonPath."VueStructure.php",$vDatas);
	}

	/*
	 * Ajout d'un log
	 * Action : "connexion", "consult"/"edit" d'un objet, etc
	 */
	public static function addLog($action, $curObj=null, $comment=null)
	{
		//S'il s'agit d'une action d'un user ou d'un invité qui ajoute un élément
		if(self::$curUser->isUser() || $action=="edit")
		{
			////	Init la requête Sql
			$sqlObjectType=$sqlObjectId=null;
			$moduleName=Req::$curCtrl;
			$sqlValues="date=".Db::dateNow().", _idUser=".Db::format(self::$curUser->_id).", _idSpace=".Db::format(self::$curSpace->_id).", ip=".Db::format($_SERVER["REMOTE_ADDR"]);
			////	Element : ajoute un commentaire, update date de mofif, etc
			if($curObj!=null && $curObj->_id>0)
			{
				$sqlObjectId=$curObj->_id;
				$sqlObjectType=$curObj::objectType;
				$moduleName=$curObj::moduleName;
				//Action "edit" : spécifie "ajout" ou "modif"
				if($action=="edit")  {$action=(date("Ymd-H:i:s",strtotime($curObj->dateCrea))==date("Ymd-H:i:s")) ? "add" : "modif";}
				//Nom / titre / description/ etc. : en debut de commentaire
				$delimiter=(!empty($comment)) ? " - " : null;
				if(!empty($curObj->name))									{$comment.=$delimiter.$curObj->name;}
				elseif(!empty($curObj->title))								{$comment.=$delimiter.Txt::reduce($curObj->title);}
				elseif(!empty($curObj->description))						{$comment.=$delimiter.Txt::reduce($curObj->description);}
				elseif(!empty($curObj->adress))								{$comment.=$delimiter.Txt::reduce($curObj->adress);}
				//Plus de détails dans le commentaires
				if($curObj::isInArbo() && $curObj->isRootFolder()==false)	{$comment.=" (".$curObj->containerObj()->folderPath("zip").")";}//arbo : chemin dans le commentaire (format "zip" minimaliste)
				if(self::$curUser->isUser()==false)							{$comment.=" - ".Txt::trad("guest")." : ".Req::getParam("guest");}//Invité : ajoute le pseudo dans le commentaire
				// Telechargement d'un fichier : compteur ++"zip"
				if($action=="consult2")    {Db::query("UPDATE ".$curObj::dbTable." SET downloadsNb=(downloadsNb + 1) WHERE _id=".$sqlObjectId);}
			}
			////	Ajoute le log & Supprime les anciens logs!
			Db::query("INSERT INTO ap_log SET action=".Db::format($action).", moduleName=".Db::format($moduleName).", objectType=".Db::format($sqlObjectType).", _idObject=".Db::format($sqlObjectId).", comment=".Db::format($comment).", ".$sqlValues);
			$logMaxLifetime=time()-(self::$agora->logsTimeOut*86400);//conservés quelques jours (cf. config de l'agora)
			Db::query("DELETE FROM ap_log WHERE UNIX_TIMESTAMP(date) <= ".Db::format($logMaxLifetime));
		}
	}

	/*
	 * Ajoute lance un trigger JavaScript ("$(window).load" + "eval()")
	 */
	public static function addJsTrigger($jsTrigger)
	{
		self::$_jsTriggers[]=$jsTrigger;
	}

	/*
	 * Ajoute une notification à afficher à l'utilisateur final (EX "alert()")
	 */
	public static function addNotif($message, $type="info")
	{
		//Traduction à récupérer ?
		if(Txt::isTrad($message))	{$message=Txt::trad($message);}
		//type de notification : "info" / "success" / "warning"
		self::$_msgNotif[]=array("message"=>$message,"type"=>$type);
	}

	/*
	 * Redirige une page
	 */
	public static function redir($url)
	{
		header("Location: ".$url.self::urlMsgNotif());
		exit;
	}
	
	/*
	 * Recupère les "msgNotif" pour les ajouter à l'url
	 */
	public static function urlMsgNotif()
	{
		$urlMsgNotif=null;
		if(!empty(self::$_msgNotif)){
			foreach(self::$_msgNotif as $message)	{$urlMsgNotif.="&msgNotif[]=".urlencode($message["message"]);}
		}
		return $urlMsgNotif;
	}

	/*
	 * Ferme le lightbox (exple: apres enregistrement de formulaire)						(ex "close_lightbox()")
	 */
	public static function lightboxClose($reloadContainer=true)
	{
		self::$lightboxClose=true;
		$reloadContainer=($reloadContainer==true) ? "true" : "false";
		self::addJsTrigger("lightboxClose(".$reloadContainer.", null, '".self::urlMsgNotif()."');");//ajoute le trigger JS de fermeture de page
		static::displayPage();//n'affiche pas de vue principale, juste les triggers
		exit;
	}

	/*
	 * Recupere les plugins de type "Folder" d'un module
	 */
	public static function getPluginsFolders($pluginParams)
	{
		$pluginsList=array();
		foreach($pluginParams["MdlObjectFolder"]::getPluginObjects($pluginParams) as $objFolder)
		{
			$objFolder->pluginModule=static::moduleName;
			$objFolder->pluginIsFolder=true;
			$objFolder->pluginIcon=static::moduleName."/icon.png";
			$objFolder->pluginLabel=$objFolder->name;
			$objFolder->pluginTitle=$objFolder->description."<br>".$objFolder->displayAutor(true,true);
			$objFolder->pluginJsIcon="redir('".$objFolder->getUrl("container")."',true);";
			$objFolder->pluginJsLabel=$objFolder->pluginJsIcon;
			$pluginsList[]=$objFolder;
		}
		return $pluginsList;
	}

	/*
	 * Affiche "element inaccessible" (ou autre) & sortie de script
	 */
	public static function noAccessExit($message=null)
	{
		if($message===null)    {$message=Txt::trad("elem_inaccessible");}
		echo "<h2><img src='app/img/important.png' style='vertical-align:middle;'> ".$message."</h2>";
		exit;
	}
}